<?php
/*
 * This file is part of EC-CUBE PAYMENT MODULE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.net/product/payment/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
require_once(MDL_PG_MULPAY_CLASS_REALDIR . "pages/LC_Page_Mdl_PG_MULPAY.php");
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY_Client.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'utils/LC_Mdl_PG_MULPAY_Utils.php');

/**
 * ドコモケータイ払い入力画面 のページクラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY_Docomo extends LC_Page_Mdl_PG_MULPAY {
    function doValidToken($is_admin = false) {
        // XXX チェックしない
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $this->objCartSess = new SC_CartSession_Ex();
        $this->objSiteSess = new SC_SiteSession_Ex();

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog('##### Docomo Start: '. print_r($_POST, true));
        
        // 決済実行
        switch($this->getMode()) {
        case 'DocomoReturn':
            $this->docomoReturnMode($_POST);
	    break;
        case 'return':
            if ($this->is2clickFlow) {
                $this->return2clickPayment();
            } else {
                $this->returnMode();
            }
            exit;
	    break;
        default:
            if ($this->is2clickFlow) {
                $this->save2Click();
            } else {
                $this->tranMode();
            }
	    break;
        }
    }

    function tranMode() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $objCustomer = new SC_Customer_Ex();
        if ($objCustomer->isLoginSuccess(true)) {
            // 会員ID登録の実行
            $objSaveMember = LC_Mdl_PG_MULPAY_Client::factory('Savemember');
            $objSaveMember->request();
            if ($objSaveMember->isError()) {
                $objPG->printLog('-> failed savemember');
                $this->errorHandler($objSaveMember->getError(), $objForm);
                return;
            }
        }

        $r = parent::tranModeImpl();
        if (!is_array($r)) {
            return;
        }
        $arrEntryRet = $r['arrEntryRet'];
        $arrExecRet = $r['arrExecRet'];

        $docomoStartUrl = $arrExecRet['StartURL'];
        $this->tpl_mainpage = MDL_PG_MULPAY_TEMPLATE_PATH . 'docomo.tpl';
        $this->tpl_onload = 'OnLoadEvent();';
        $this->docomoStartUrl = $docomoStartUrl;
        $this->docomoAccessID = $arrEntryRet['AccessID'];
        $this->docomoToken = $arrExecRet['Token'];
        $this->setTokenTo();
        $this->objSiteSess->setRegistFlag();
        $_SESSION['MDL_PG_MULPAY']['Docomo_OrderID'] = $arrEntryRet['OrderID']; // 戻りの確認に使用する。

        if (Net_UserAgent_Mobile::isMobile()) {
            SC_Helper_Mobile_Ex::sfMobileSetExtSessionId('OrderID', $arrEntryRet['OrderID'],
                                                         'shopping/load_payment_module.php');
        }

        $objPG->printLog('-> redirect to docomo site: ' . $docomoStartUrl);
    }

    function docomoReturnMode($arrData) {
        // キャンセルしたかどうか判定する。
        $errors = $this->parseDocomoResult($arrData['ErrCode'], $arrData['ErrInfo']);
        $errors_str = implode(',', $errors);
        if ($this->isDocomoResultCancel($errors) || $this->isDocomoResultError($errors)) {
            LC_Mdl_PG_MULPAY::printLog("docomo: error. order_id=".$arrData['OrderID']." errors=".print_r($errors,true));
            $this->showRetryPage($arrData['OrderID']);
            return;
        }

        // 処理成功
        $this->completeDocomo($arrData);
    }

    function completeDocomo($arrData, $donotRecursive = false) {

        // Statusにより受注完了ステータスを設定する
        $status = strtoupper($arrData['Status']);
        switch ($status) {
        case 'AUTH':
        case 'CAPTURE':
            // 決済成功
            $this->orderComplete($_SESSION['order_id'], array('memo04' => $status, 'memo05' => ORDER_NEW), ORDER_NEW); // 新規受付
            break;
        case 'PAYFAIL':
            // 決済失敗
            LC_Mdl_PG_MULPAY::printLog("docomo: order_id=".$_SESSION['order_id']." status=$status");
            $this->showRetryPage($_SESSION['order_id']);
            break;
        default:
            // 不明な状態: エラー
            LC_Mdl_PG_MULPAY::printLog("docomo: unexpected status. order_id=".$_SESSION['order_id']." status=$status");
            $msg = '取引状態が不明です。ドコモ携帯画面で決済の状態を確認して下さい。';
            $this->panic($_SESSION['order_id'], $msg);
            break;
        }
    }

    function showRetryPage($order_id) {
        // 受注をロールバックして、再度、購入手続を取れるようにする。
        $this->showCancelPage($order_id);
        $this->objSiteSess->setRegistFlag();
    }

    /**
     * 利用金額上限を取得する。
     * 
     * @return integer 利用金額上限
     */
    function getRuleMax() {
        return DOCOMO_RULE_MAX;
    }

    /**
     * EntryTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getEntryTranClassName() {
        return 'Entry_Docomo';
    }

    /**
     * ExecTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getExecClassName() {
        return 'Exec_Docomo';
    }

    /**
     * モードを返す.
     *
     * @return string
     */
    function getMode() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $mode = '';

        $savedOrderID = $_SESSION['MDL_PG_MULPAY']['Docomo_OrderID'];
        unset($_SESSION['MDL_PG_MULPAY']['Docomo_OrderID']);

        // Docomoからの戻りかどうかを判定する。
        if (!empty($savedOrderID)
            && $_POST['OrderID'] === $savedOrderID
            && isset($_POST['Status'])
            && isset($_POST['TranDate']))
        {
            $mode = 'DocomoReturn';
        } elseif (isset($_POST['mode'])) {
            $mode = $_POST['mode'];
        }

        $objPG->printLog('docomo mode:' . $mode);

        return $mode;
    }

    function parseDocomoResult($errCode, $errInfo) {
        $errCode = trim($errCode);
        $errInfo = trim($errInfo);
        if (empty($errCode)) {
            return array();
        }

        $arrErrCode = explode('|', $errCode);
        $arrErrInfo = explode('|', $errInfo);

        foreach ($arrErrCode as $code) {
            $info = array_shift($arrErrInfo);
            $errors[] = $code . '-' . $info;
        }

        return $errors;
    }

    function isDocomoResultCancel($errors) {
/*
   foreach ($errors as $error) {
            if ($error == 'AU1-AU1000005') { // キャンセル
                return true;
            }
        }
*/
        return false;
    }

    function isDocomoResultError($errors) {
        return !empty($errors);
    }
}
/*
 * Local variables:
 * coding: utf-8
 * End:
 */
?>
