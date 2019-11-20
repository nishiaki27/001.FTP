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
 * WebMoney決済情報入力画面 のページクラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY_Webmoney extends LC_Page_Mdl_PG_MULPAY {
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
        $objPG->printLog('##### Webmoney Start: '. print_r($_POST, true));
        
        // 決済実行
        switch($this->getMode()) {
        case 'WebmoneyReturn':
            $this->webmoneyReturnMode($_POST);
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
        $r = parent::tranModeImpl();
        if (!is_array($r)) {
            return;
        }
        $arrEntryRet = $r['arrEntryRet'];
        $arrExecRet = $r['arrExecRet'];

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $webmoneyStartUrl = $objPG->getUserSettings('server_url') . 'WebmoneyStart.idPass';
        $this->tpl_mainpage = MDL_PG_MULPAY_TEMPLATE_PATH . 'webmoney.tpl';
        $this->tpl_onload = 'OnLoadEvent();';
        $this->webmoneyStartUrl = $webmoneyStartUrl;
        $this->webmoneyAccessID = $arrEntryRet['AccessID'];
        $this->setTokenTo();
        $this->objSiteSess->setRegistFlag();
        $_SESSION['MDL_PG_MULPAY']['Webmoney_OrderID'] = $arrEntryRet['OrderID']; // 戻りの確認に使用する。

        if (Net_UserAgent_Mobile::isMobile()) {
            SC_Helper_Mobile_Ex::sfMobileSetExtSessionId('paypal_order_id', $arrEntryRet['OrderID'],
                                                         'shopping/load_payment_module.php');
        }

        $objPG->printLog('-> redirect to webmoney site: ' . $webmoneyStartUrl);
    }

    function webmoneyReturnMode($arrData) {
        // キャンセルしたかどうか判定する。
        $errors = $this->parseWebmoneyResult($arrData['ErrCode'], $arrData['ErrInfo']);
        $errors_str = implode(',', $errors);
        if ($this->isWebmoneyResultCancel($arrData)) {
            $this->showCancelPage($arrData['OrderID']);
            $this->objSiteSess->setRegistFlag();
            return;
        } else if ($this->isWebmoneyResultError($errors)) {
            $msg = "エラーが発生しました。ショップに下記エラーコードをご連絡頂くか、<br>または別のお支払い方法を選択して、購入手続きを行っていただけますよう御願いします。<br><br>エラーコード: $errors_str";
            $this->panic($arrData['OrderID'], $msg);
        }
        // 処理成功

        // Statusにより受注完了ステータスを設定する
        $status = strtoupper($_POST['Status']);
        switch ($status) {
        case 'PAYSUCCESS':
            $order_status = ORDER_PRE_END;	// 入金済み
            break;
        case 'REQSUCCESS':
            $order_status = ORDER_PAY_WAIT;	// 入金待ち
            break;
        case 'PAYFAIL':
        default:
            $order_status = ORDER_PENDING; // 問題発生
            break;
        }
        $sqlval['memo04'] = $status;

        $this->orderComplete($_SESSION['order_id'], $sqlval, $order_status);
    }

    /**
     * 利用金額上限を取得する。
     * 
     * @return integer 利用金額上限
     */
    function getRuleMax() {
        return WEBMONEY_RULE_MAX;
    }

    /**
     * EntryTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getEntryTranClassName() {
        return 'Entry_Webmoney';
    }

    /**
     * ExecTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getExecClassName() {
        return 'Exec_Webmoney';
    }

    /**
     * モードを返す.
     *
     * @return string
     */
    function getMode() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $mode = '';

        $savedOrderID = $_SESSION['MDL_PG_MULPAY']['Webmoney_OrderID'];
        unset($_SESSION['MDL_PG_MULPAY']['Webmoney_OrderID']);

        // Webmoneyからの戻りかどうかを判定する。
        if (!empty($savedOrderID)
            && $_POST['OrderID'] === $savedOrderID
            && isset($_POST['Status'])
            && isset($_POST['TranDate'])
            && isset($_POST['ManagementNo'])
            && isset($_POST['SettleCode'])
            && isset($_POST['PayCancel']))
        {
            $mode = 'WebmoneyReturn';
        } elseif (isset($_POST['mode'])) {
            $mode = $_POST['mode'];
        }

        $objPG->printLog('webmoney mode:' . $mode);

        return $mode;
    }

    function parseWebmoneyResult($errCode, $errInfo) {
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

    function isWebmoneyResultCancel($arrData) {
        return $arrData['Status'] === 'REQSUCCESS' && $arrData['PayCancel'] === '1';
    }

    function isWebmoneyResultError($errors) {
        return !empty($errors);
    }
}
/*
 * Local variables:
 * coding: utf-8
 * End:
 */
?>
