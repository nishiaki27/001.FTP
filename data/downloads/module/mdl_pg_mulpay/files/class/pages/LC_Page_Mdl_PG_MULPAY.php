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
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY_Client.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'SC_Helper_Mdl_PG_MULPAY_Purchase.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'utils/LC_Mdl_PG_MULPAY_Utils.php');

/**
 * 決済情報入力画面 のページクラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY extends LC_Page_Ex {
    // {{{ properties

    /** テンプレートファイル */
    var $tpl_file;

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

        // 2clickフローフラグを設定する。
        $this->is2clickFlow = ($_SESSION['mdl_pg_mulpay']['2click'] === true);

        // tplファイルを設定する
        $this->tpl_mainpage = $this->getTemplateDir($this->tpl_file);

        // 決済情報入力画面では、headerとfooterを表示しない。
        $this->arrPageLayout['header_chk'] = 2;
        $this->arrPageLayout['footer_chk'] = 2;
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
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

        $this->setTemplateVars();

        // memo06から支払い情報をロード
        if ($_SESSION['mdl_pg_mulpay']['2click'] === 'charge') {
            $objPurchase = new SC_Helper_Purchase_Ex();
            $arrOrder = $objPurchase->getOrder($_SESSION['order_id']);

            if (!$arrOrder) {
                $this->panic($_SESSION['order_id'], '2クリック決済情報の取得に失敗しました。');
            }
            $memo06 = unserialize($arrOrder['memo06']);
            LC_Mdl_PG_MULPAY::printLog('memo06 charge: '.print_r($memo06,true));
            if (is_array($memo06) && isset($memo06['conveni'])) {
                // コンビニ決済
                $_POST['mode'] = 'tran';
                $_POST['conveni'] = $memo06['conveni'];
            } else if (is_array($memo06) && isset($memo06['email']) && isset($memo06['email_domain'])) {
                // edy or suica
                $_POST['mode'] = 'tran';
                $_POST['email'] = $memo06['email'];
                $_POST['email_domain'] = $memo06['email_domain'];
            }
        }

        switch($this->getMode()) {
        // 次へボタン押下時
        case 'tran':
            if ($this->is2clickFlow) {
                $this->save2Click();
            } else {
                $this->tranMode();
            }
            break;

        // 戻るボタン押下時
        case 'return':
            if ($this->is2clickFlow) {
                $this->return2clickPayment();
            } else {
                $this->returnMode();
            }
            exit;
            break;

        // 初回表示
        default:
            $arrData = $this->getTotalConfirm();
            if ($this->getRuleMax() < $arrData['payment_total']) {
                $rule_err = "お支払合計がご選択されたお支払方法の上限を超えています。<br>";
                $rule_err .= "申し訳ありませんが。その他のお支払方法をお選びください。";
                $this->panic($rule_err);
            }
            $this->checkParamError($uniqid, $this->objSiteSess);
            $objForm = $this->initParam();
            $this->arrForm = $objForm->getFormParamList();
            
            break;
        }
    }

    /**
     * 決済処理が正常終了
     *
     */
    function orderComplete($order_id, $sqlval = array(), $order_status = ORDER_NEW) {
        LC_Mdl_PG_MULPAY::printLog("orderComplete: order_staus=$order_status, sqlval=".print_r($sqlval,true));

        $objPurchase = new SC_Helper_Purchase_Ex();

        // 使用ポイントを減算する
        $this->updateCustomerPoint($order_id);

        // 受注ステータスを「決済処理中」から更新する。
        if ($order_status != ORDER_PENDING) { // iDでは更新しない
            $objPurchase->sfUpdateOrderStatus($order_id, $order_status, null, null, $sqlval);
        } else if (!empty($sqlval)) {
            $objPurchase->registerOrder($order_id, $sqlval);
        }

        // 受注完了メールを送信する。
        $this->sendOrderMail($objPurchase, $order_id);

        // 完了ページへ受注IDを渡す。
        $_SESSION['mdl_pg_mulpay_complete_order_id'] = $_SESSION['order_id'];

        // セッション情報をクリア
        unset($_SESSION['MDL_PG_MULPAY']);

        // 購入完了ページへリダイレクト
        $this->toCompletePage();
    }

    /**
     * テンプレート変数をassignする
     *
     */
    function setTemplateVars() {
    }

    /**
     * エラー情報の表示用データをassignする.
     *
     * @param array $arrErr
     * @param SC_FormParam $objForm
     */
    function errorHandler($arrErr, $objForm, $doRollback = true) {
        // 2クリック決済でエラーが発生した場合は、2クリックフローに戻る
        if ($_SESSION['mdl_pg_mulpay']['2click'] === 'charge') {
            $_SESSION['mdl_pg_mulpay']['2click'] = true;
            $this->is2clickFlow = true;
        }

        // 2クリックフロー中なら受注情報を回復する
        if ($doRollback && $this->is2clickFlow) {
            LC_Mdl_PG_MULPAY::printLog("errorHandler 2click-flow rollback");
            $this->rollbackOrder($_SESSION['order_id']);
        }

        $this->arrErr = $arrErr;
        $this->initParamAdd($objForm); // カード情報詳細画面
        $this->arrForm = $objForm->getFormParamList();
        //LC_Mdl_PG_MULPAY::printLog('arrForm: '.print_r($this->arrForm,true));
    }

    /**
     * 次へボタン押下時の処理.決済処理を行う
     *
     */
    function tranMode() {
        $r = $this->tranModeImpl();
        if (!is_array($r)) {
            // 決済失敗
            return;
        }

        // ペイジーの場合、完了画面から選択画面へ遷移するformを表示する。
//        $encryptReceiptNo = $this->arrExecRet['EncryptReceiptNo'];
//        if ($encryptReceiptNo) {
//            $objPG->printLog('EncryptReceiptNo:' . $encryptReceiptNo);
//            $_SESSION['EncryptReceiptNo'] = $encryptReceiptNo;
//        } else {
//            unset($_SESSION['EncryptReceiptNo']);
//    	}

        // 購入完了処理を行う
        $this->orderComplete($this->arrData['order_id'], $sqlval);
    }

    function tranModeImpl() {
        $objPurchase = new SC_Helper_Purchase_Ex();

        // 決済対象の受注情報を取得する。
        $arrOrder = $objPurchase->getOrder($_SESSION['order_id']);

        $objForm = $this->initParam();
        if ($arrErr = $objForm->checkError()) {
            $this->errorHandler($arrErr, $objForm);
            return;
        }

        // 2click決済用情報を保存する。
        $this->save2clickPaymentMethod($objForm, $objPurchase, $arrOrder);

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog('##### tranMode START #####');

        // カート集計を元に最終計算
        $this->arrData = $this->getTotalConfirm();

        $objPG->printLog('OrderInfo: ' . print_r($this->arrData,true));
        $objEntry = LC_Mdl_PG_MULPAY_Client::factory($this->getEntryTranClassName());
        // EntryTranが実行済みでなければEntryTranへリクエストする.
        if ($objEntry->isComplete($this->arrData['order_id']) == false) {
            $objEntry->request($this->arrData);
            // エラー判定
            if ($objEntry->isError()) {
                $objPG->printLog('-> failed entry tran');
                $this->errorHandler($objEntry->getError(), $objForm);
                return;
            }
        } else {
            $objPG->printLog('-> skip entry tran');
        }

        // ExecTranへリクエスト送信
        $arrEntryRet = $objEntry->getResults();
        $this->arrForm  = $objForm->getHashArray();

        $objExec = LC_Mdl_PG_MULPAY_Client::factory($this->getExecClassName());
        $objExec->request($arrEntryRet, $this);
        if ($objExec->isError()) {
            $objPG->printLog('-> failed exec tran');
            $this->errorHandler($objExec->getError(), $objForm);
            return;
        }

        // 結果をログ出力
        $this->arrExecRet = $objExec->getResults();
        $objPG->printLog(print_r($this->arrExecRet, true));

        // セッション中のアクセスIDをクリアする。
        $objEntry->unsetCompleteSession();

        // 購入完了画面、完了メールへ反映される情報を記録する。
        $message = $this->getSerializeMessage($this->arrExecRet);
        if (strlen($message) !== 0) {
            $sqlval['memo02'] = serialize($message);
        }

        // AccessIDを記録して、receive.phpで検索条件として使う。
        $sqlval['memo03'] = $arrEntryRet['AccessID'];

        // ExecTran成功時の決済状況はREQSUCCESS
        if ($this->getExecClassName() == 'Exec_Netid') {
            $sqlval['memo04'] = 'REQSUCCESS';
        }

        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->registerOrder($this->arrData['order_id'], $sqlval);

        return array('arrEntryRet' => $arrEntryRet, 'arrExecRet' => $this->arrExecRet);
    }

    /**
     * カート集計から最終計算を行う。
     *
     */
    function getTotalConfirm() {
        // 受注テーブルの読込
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrData = $objPurchase->getOrder($_SESSION['order_id']);
        return $arrData;
    }

    /**
     * 完了画面へ遷移する
     *
     */
    function toCompletePage() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        // 購入完了ページへリダイレクト
        $objSiteSess = new SC_SiteSession_Ex();
        $objSiteSess->setRegistFlag();

        $objPG->printLog('-> redirect to complete page ' . SHOPPING_COMPLETE_URLPATH);

        SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
    }

    /**
     * フォームパラメータの初期化
     *
     * @return SC_FormParam
     */
    function initParam() {
        $objForm = new SC_FormParam_Ex();
        $objForm->setParam($_POST);
        $objForm->convParam();
        return $objForm;
    }

    function initParamAdd(&$objForm) {
        return $objForm;
    }

    /**
     * モードを返す.
     *
     * @return string
     */
    function getMode() {
        $mode = '';
        if (isset($_POST['mode'])) {
            $mode = $_POST['mode'];
        }
        return $mode;
    }

    /**
     * 戻るボタン押下時の処理
     *
     */
    function returnMode() {
        // 処理中の受注情報を論理削除する。
        LC_Mdl_PG_MULPAY::printLog("return rollbackOrder: ".$_SESSION['order_id']);
        $this->rollbackOrder($_SESSION['order_id']);

        $objSiteSess = new SC_SiteSession_Ex;
        $objSiteSess->setRegistFlag();
        SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
        exit;
    }

    function return2clickConfirm() {
        $objSiteSess = new SC_SiteSession_Ex;
        $objSiteSess->setRegistFlag();
        SC_Response_Ex::sendRedirect(HTTPS_URL . 'twoClick/confirm.php');
        exit;
    }

    function return2clickPayment() {
        $objSiteSess = new SC_SiteSession_Ex;
        $objSiteSess->setRegistFlag();
        SC_Response_Ex::sendRedirect(HTTPS_URL . 'twoClick/payment.php');
        exit;
    }

    function save2clickPaymentMethod(&$objForm, &$objPurchase, $arrOrder) {
        $sqlval['update_date'] = 'Now()';
        $sqlval['memo06'] = serialize(''); // 空文字列を設定
        $objPurchase->registerOrder($_SESSION['order_id'], $sqlval);
    }

    function lfSetConvMSG($name, $value){
        return array("name" => $name, "value" => $value);
    }

    /**
     * リクエストパラメータに使用する値をチェックする。
     *
     * @return void
     */
    function checkParamError($uniqid, $objSiteSess) {
        return;
    }

    /**
     * EntryTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getEntryTranClassName() {
        return '';
    }

    /**
     * ExecTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getExecTranClassName() {
        return '';
    }

    /**
     * 受注一時情報.汎用項目2(dtb_order_temp.memo02)に格納する値を取得する。
     *
     * @return array 汎用項目2格納値
     */
    function getSerializeMessage($arrExecRet) {
        return array();
    }

    /**
     * 利用金額上限を取得する。
     * 
     * @return integer 利用金額上限
     */
    function getRuleMax() {
        return 0;
    }

    function getTemplateDir($tpl_file) {
        switch (SC_Display::detectDevice()) {
        case DEVICE_TYPE_MOBILE:
            $dir = 'mobile'; 
            break;
        case DEVICE_TYPE_SMARTPHONE:
            $dir = 'sphone';
            break;
        default:
            $dir = 'default';
            break;
        }
        //$dir .= '/'; // XXX モジュール内で同名のファイルは許されるか要確認
        $dir .= '_';

        return MDL_PG_MULPAY_TEMPLATE_PATH . $dir . $tpl_file;
    }

    function showCancelPage($cancel_order_id = false) {
        if ($cancel_order_id === false) {
            $cancel_order_id = $_SESSION['order_id'];
        }

        LC_Mdl_PG_MULPAY::printLog("受注ID:${cancel_order_id}がキャンセルされました。");
        $this->rollbackOrder($cancel_order_id);

        unset($_SESSION['MDL_PG_MULPAY']);

        SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                                     "お支払い手続がキャンセルされました。<br />再度、購入の手続をお願い致します。");
    }

    function rollbackOrder($order_id) {
        $objPurchase = method_exists('SC_Helper_Purchase_Ex', 'rollbackOrder')
            ? new SC_Helper_Purchase_Ex()
            : new SC_Helper_Mdl_PG_MULPAY_Purchase();

        // rollback対象は処理中(ORDER_PENDING)の受注情報
        $arrOrder = $objPurchase->getOrder($order_id);
        if ($arrOrder['status'] == ORDER_PENDING) { 
            LC_Mdl_PG_MULPAY::printLog("rollbackOrder order_id=$order_id");
            $objPurchase->rollbackOrder($order_id, ORDER_PENDING, true);
        } else {
            LC_Mdl_PG_MULPAY::printLog("rollbackOrder unexpected: order_id=$order_id, status=".$arrOrder['status']." (expect ".ORDER_PENDING.")");
        }
    }

    function save2Click() {
        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objFormParam = new SC_FormParam_Ex();

        // ユーザユニークIDの取得と購入状態の正当性をチェック
        $uniqid = $objSiteSess->getUniqId();
        $objPurchase->verifyChangeCart($uniqid, $objCartSess);

        $objForm = $this->initParam();
        if ($arrErr = $objForm->checkError()) {
            $this->errorHandler($arrErr, $objForm, false);
            return;
        }

        // 2クリック中フロー中では、$_SESSION['payment_id']は有効
        $arrOrderTemp['memo06'] = $this->getMemo06($objForm, $_SESSION['payment_id']);
        LC_Mdl_PG_MULPAY::printLogU('save2Click: payment_id='.$_SESSION['payment_id'].
                                    ' memo06='.print_r($arrOrderTemp,true), $uniqid);
        $objPurchase->saveOrderTemp($uniqid, $arrOrderTemp);

        $this->return2clickConfirm();
    }

    function getMemo06(&$objForm, $payment_id = -1) {
        return serialize(''); // 空文字列を設定
    }

    function sendOrderMail(&$objPurchase, $order_id) {
        LC_Mdl_PG_MULPAY::printLog('send complete mail');

        if (MDL_PG_MULPAY_ORDER_MAIL_USE_CLASS_PURCHASE) {
            $objPurchase->sendOrderMail($order_id);
            return;
        }

        switch (SC_Display::detectDevice()) {
        case DEVICE_TYPE_MOBILE:
            $mail_templ_id = MDL_PG_MULPAY_ORDER_MAIL_MOBILE_TEMPLATE;
            break;
        case DEVICE_TYPE_SMARTPHONE:
            $mail_templ_id = MDL_PG_MULPAY_ORDER_MAIL_SPHONE_TEMPLATE;
            break;
        default:
            $mail_templ_id = MDL_PG_MULPAY_ORDER_MAIL_DEFAULT_TEMPLATE;
            break;
        }

        $mailHelper = new SC_Helper_Mail_Ex();
        $mailHelper->sfSendOrderMail($order_id, $mail_templ_id);
    }

    function panic($order_id, $message) {
        LC_Mdl_PG_MULPAY::printLog("panic: order_id:$order_id message:$message");

        $subject = "PGマルチペイメントサービス決済モジュール 決済処理エラー検出";
        $body = "オーダーID： $order_id の決済処理中にエラーを検出しました。\n";
        $body .= "購入者樣に状況をご確認いただき、対応をお願い致します。\n\n";
        $body .= "エラーメッセージ: $message \n";
        $this->sendmail($subject, $body);

        SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, "処理中に次のエラーが発生しました。<br /><br /><br />・お取引に問題が発生した可能性がございます。<br />お手数ですが、ショップまでご連絡くださいませ。<br /><br /><br />オーダーID: $order_id <br /><br /><br />この手続きは無効となりました。");

        exit;
    }

    function sendmail($subject, $body) {
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $objSendMail = new SC_SendMail_Ex();

        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $to = $arrInfo['email01'];
        $to_name =  $arrInfo['shop_name'];

        $objSendMail->setItem('', $subject, $body, $from, "PGマルチペイメントサービス決済モジュール", $from, $error, $error);
        $objSendMail->setTo($to, $to_name);
        $objSendMail->sendMail();
    }

    function updateCustomerPoint($order_id) {
        if (! MDL_PG_MULPAY_ROLLBACK_USE_POINT) {
            return;
        }
        
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrOrder = $objPurchase->getOrder($order_id);
        if ($arrOrder['use_point'] <= 0) {
            return;
        }
        LC_Mdl_PG_MULPAY::printLog("update point: ".$arrOrder['use_point']);

        $sqlval['update_date'] = 'Now()';
        $arrRawSql['point'] = 'point - ?';
        $arrRawSqlVal[] = $arrOrder['use_point'];
        $where = 'customer_id = ?';
        $arrVal[] = $arrOrder['customer_id'];
        $objQuery->update('dtb_customer', $sqlval, $where, $arrVal, $arrRawSql, $arrRawSqlVal);
    }
}
?>
