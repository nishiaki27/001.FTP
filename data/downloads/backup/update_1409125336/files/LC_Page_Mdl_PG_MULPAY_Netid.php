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
require_once MDL_PG_MULPAY_CLASS_REALDIR . "pages/LC_Page_Mdl_PG_MULPAY.php";
require_once MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY.php';
require_once MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY_Client.php';
require_once MDL_PG_MULPAY_CLASS_REALDIR . 'utils/LC_Mdl_PG_MULPAY_Utils.php';
require_once MDL_PG_MULPAY_CLASS_REALDIR . 'utils/LC_Mdl_PG_MULPAY_Export.php';

/**
 * Netid決済情報入力画面 のページクラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY_Netid extends LC_Page_Mdl_PG_MULPAY {
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
    }

    function doValidToken($is_admin = false) {
        // XXX チェックしない
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function action() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog('##### Netid Start: '. print_r($_POST, true));
        
        // 決済実行
        switch($this->getMode()) {
        case 'center':
            $this->netidReturnMode($_POST);
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
        $netidStartUrl = $objPG->getUserSettings('server_url') . 'NetidStart.idPass';
        $this->tpl_mainpage = MDL_PG_MULPAY_TEMPLATE_PATH . 'netid.tpl';
        $this->tpl_onload = 'OnLoadEvent();';
        $this->netidStartUrl = $netidStartUrl;
        $this->netidAccessID = $arrEntryRet['AccessID'];

        if (Net_UserAgent_Mobile::isMobile()) {
            SC_Helper_Mobile_Ex::sfMobileSetExtSessionId('netid_order_id', $arrEntryRet['OrderID'],
                                                         'shopping/load_payment_module.php');
        }

        $objSiteSess = new SC_SiteSession_Ex();
        $objSiteSess->setRegistFlag();

        $objPG->printLog('-> redirect to netid site: ' . $netidStartUrl);
    }

    function netidReturnMode($arrData) {
        // 結果通知による決済状況変更を確認する。
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrder = $objPurchase->getOrder($_SESSION['order_id']);
        $status = $arrOrder['memo04'];
        LC_Mdl_PG_MULPAY::printLog("netidReturnMode: status=$status");

        $this->completeNetid($status);
    }

    function completeNetid($status, $donotRecursive = false) {
        LC_Mdl_PG_MULPAY::printLog("completeNetid:$donotRecursive status=$status");
        $panic_msg = "お取引に問題が発生した可能性がございます。<br>お手数ですが、ショップまでご連絡くださいませ。<br><br>";

        switch ($status) {
        case 'AUTH':
        case 'CAPTURE':
        case 'SALES': // 念の為
            // 決済成功
            $this->orderComplete($_SESSION['order_id'], array('memo04' => $status), ORDER_NEW); // 新規受付
            break;
        case 'PAYFAIL':
            // 決済失敗
            $msg = $panic_msg."エラーコード: PAYFAIL_".$_SESSION['order_id'];
            $this->panic($_SESSION['order_id'], $msg);
            break;
        case 'REQSUCCESS':
            if ($donotRecursive == false) {
                // REQSUCCESSの場合、取引状態を確認する。
                $objExport = new LC_Mdl_PG_MULPAY_Export;
                $res = $objExport->requestSEARCHTRADEMULTI($_SESSION['order_id'], MDL_PG_MULPAY_NETID_PAY_TYPE);
                if (!is_array($res)) {
                    // SearchTradeMultiの実行に失敗
                    $msg = $panic_msg."エラーコード: REQSUCCESS_".$_SESSION['order_id'];
                    $this->panic($_SESSION['order_id'], $msg);
                }

                $this->completeNetid($res['Status'], true);
            } else {
                // REQSUCCESSのまま変化がないものは、「戻る」操作を行ったとみなす。
                $this->showCancelPage($_SESSION['order_id']);
            }
            break;
        case 'EXPIRED':
            // このタイミングでは、恐らくありえない
            $msg = $panic_msg."エラーコード: EXPIRED_".$_SESSION['order_id'];
            $this->panic($_SESSION['order_id'], $msg);
            break;
        default:
            // 不明な状態: エラー
            $msg = $panic_msg."エラーコード: BADSTATUS_$status_".$_SESSION['order_id'];
            $this->panic($_SESSION['order_id'], $msg);
            break;
        }
    }

    /**
     * 利用金額上限を取得する。
     * 
     * @return integer 利用金額上限
     */
    function getRuleMax() {
        return NETID_RULE_MAX;
    }

    /**
     * EntryTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getEntryTranClassName() {
        return 'Entry_Netid';
    }

    /**
     * ExecTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getExecClassName() {
        return 'Exec_Netid';
    }

    /**
     * モードを返す.
     *
     * @return string
     */
    function getMode() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $mode = '';

        $savedOrderID = $_SESSION['MDL_PG_MULPAY']['Netid_OrderID'];
        unset($_SESSION['MDL_PG_MULPAY']['Netid_OrderID']);
        $objPG->printLog('netid getMode:' . $savedOrderID . ':' . print_r($_POST,true));

        // confirmからは$_SERVER['REQUEST_METHOD'] == 'GET'
        if (isset($_POST['mode'])) {
            $mode = $_POST['mode'];
        } elseif (isset($_GET['mode'])) {
            $mode = $_GET['mode'];
        }

        $objPG->printLog('netid mode:' . $mode);

        return $mode;
    }
}
/*
 * Local variables:
 * coding: utf-8
 * End:
 */
?>
