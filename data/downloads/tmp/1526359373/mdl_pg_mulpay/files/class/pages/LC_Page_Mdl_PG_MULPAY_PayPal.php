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

// http://php.net/manual/en/function.str-split.php
if (!function_exists('str_split')) {
    function str_split($text, $split = 1) {
        if ($split < 1) return false;

        $array = array();
           
        for ($i = 0; $i < strlen($text); $i += $split) {
            $array[] = substr($text, $i, $split);
        }
           
        return $array;
    }
} 

/**
 * クレジット決済情報入力画面 のページクラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY_PayPal extends LC_Page_Mdl_PG_MULPAY {
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
        $objPG->printLog('##### PayPal Start: '. print_r($_POST, true));
        
        // 決済実行
        switch($this->getMode()) {
        case 'PayPalReturn':
            $this->paypalReturnMode();
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
        $paypalStartUrl = $objPG->getUserSettings('server_url') . 'PaypalStart.idPass';
        $this->tpl_mainpage = MDL_PG_MULPAY_TEMPLATE_PATH . 'paypal.tpl';
        $this->tpl_onload = 'OnLoadEvent();';
        $this->paypalStartUrl = $paypalStartUrl;
        $this->paypalShopID = $objPG->getUserSettings('shop_id');
        $this->paypalAccessID = $arrEntryRet['AccessID'];
        $this->setTokenTo();
        $this->objSiteSess->setRegistFlag();
        $_SESSION['MDL_PG_MULPAY']['PayPal_OrderID'] = $arrEntryRet['OrderID']; // 戻りの確認に使用する。

        $objPG->printLog('-> redirect to paypal site: ' . $paypalStartUrl);
    }

    function paypalReturnMode() {
        // キャンセルしたかどうか判定する。
        $errors = $this->parsePaypalResult($_POST['ErrCode'], $_POST['ErrInfo']);
        $errors_str = implode(',', $errors);
        if ($this->isPaypalResultCancel($errors)) {
            $this->showCancelPage($_POST['OrderID']);
            $this->objSiteSess->setRegistFlag();
            return;
        } else if ($this->isPaypalResultError($errors)) {
            $msg = "エラーが発生しました。ショップに下記エラーコードをご連絡頂くか、<br>または別のお支払い方法を選択して、購入手続きを行っていただけますよう御願いします。<br><br>エラーコード: $errors_str";
            $this->panic($_POST['OrderID'], $msg);
        }
        // 処理成功

        // Statusにより受注完了ステータスを設定する
        $status = strtoupper($_POST['Status']);
        switch ($status) {
        case 'CAPTURE':
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
        return PAYPAL_RULE_MAX;
    }

    /**
     * EntryTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getEntryTranClassName() {
        return 'Entry_PayPal';
    }

    /**
     * ExecTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getExecClassName() {
        return 'Exec_PayPal';
    }

    /**
     * モードを返す.
     *
     * @return string
     */
    function getMode() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $mode = '';

        $savedOrderID = $_SESSION['MDL_PG_MULPAY']['PayPal_OrderID'];
        unset($_SESSION['MDL_PG_MULPAY']['PayPal_OrderID']);
        $objPG->printLog('paypal getMode:' . $savedOrderID);

        // PayPalからの戻りかどうかを判定する。
        if (!empty($savedOrderID)
            && $_POST['ShopID'] === trim($objPG->getUserSettings('shop_id'))
            && $_POST['OrderID'] === $savedOrderID
            && isset($_POST['Status'])
            && isset($_POST['TranID'])
            && isset($_POST['TranDate']))
        {
            $mode = 'PayPalReturn';
        } elseif (isset($_POST['mode'])) {
            $mode = $_POST['mode'];
        }

        $objPG->printLog('paypal mode:' . $mode);

        return $mode;
    }

    function parsePaypalResult($errCode, $errInfo) {
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

    function isPaypalResultCancel($errors) {
        foreach ($errors as $error) {
            // ユーザーによるキャンセルをチェック
            if ("P03-P03000003" === $error) {
                return true;
            }
        }

        return false;
    }

    function isPaypalResultError($errors) {
        return !$this->isPaypalResultCancel($errors) && count($errors) != 0;
    }
}
/*
 * Local variables:
 * coding: utf-8
 * End:
 */
?>
