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
class LC_Page_Mdl_PG_MULPAY_Credit extends LC_Page_Mdl_PG_MULPAY {
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        $this->tpl_file = 'credit.tpl';
        parent::init();
        $this->tpl_column_num = 1;
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
        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $this->setTemplateVars();

        // 2clickフローフラグを設定する。
        $this->is2clickFlow = ($_SESSION['mdl_pg_mulpay']['2click'] === true);

        LC_Mdl_PG_MULPAY::printDLog('mode=' . $this->getMode() . ' 2click_ses=' . $_SESSION['mdl_pg_mulpay']['2click']);

        // memo06から決済情報をロードする。
        if ($_SESSION['mdl_pg_mulpay']['2click'] === 'charge') {
            $objPurchase = new SC_Helper_Purchase_Ex();
            $arrOrder = $objPurchase->getOrder($_SESSION['order_id']);

            if (!$arrOrder) {
                $this->panic($_SESSION['order_id'], '2クリック決済クレジット情報の取得に失敗しました。');
            }
            if ($arrOrder['del_flg'] == '1') {
                $this->panic($_SESSION['order_id'], 'ご注文情報が無効になっています。');
            }

            $memo06 = unserialize($arrOrder['memo06']);
            LC_Mdl_PG_MULPAY::printLog('memo06 charge credit: '.print_r($memo06,true));
            if (is_array($memo06) && isset($memo06['CardSeq'])) {
                $_POST['mode'] = 'register';
                foreach($memo06 as $key => $value) {
                    $_POST[$key] = $value;
                }
                $_POST['paymethod_usecard'] = $_POST['paymethod']; // XXX これでOK
            }
        }

        switch($this->getMode()) {
        // 次へボタン押下時
        case 'register':
            if ($this->is2clickFlow) {
                $this->save2Click();
            } else {
                $this->registerMode();
                // 正常に決済完了した場合は、registerMode内で完了ページへリダイレクトする。
                // 決済エラーの場合、ここを通ってクレジット入力画面に戻る。
            }
            break;

        // 戻るボタン押下時
        case 'return':
            if ($this->is2clickFlow) {
                // 2クリックフローでは、支払方法選択画面に戻る。
                $this->return2clickPayment();
            } else {
                $this->returnMode();
            }
            exit; // リダイレクトするためexitする
            break;

        // カードの削除(削除後、カード情報の再取得を行う)
        case 'deletecard':
            $this->deleteCardMode();
            $this->getCardMode();
            break;

        // カード情報の取得
        case 'getcard':
            $this->getCardMode();
            break;

        // 3Dセキュアの戻り(カード会社サイトからブラウザを経由して、ここへ遷移する)
        case 'SecureTran':
            $this->secureTranMode();
            break;

        // 初回表示
        default:
            $objForm = $this->initParam();
            $this->arrForm = $objForm->getFormParamList();
            break;
        }

        $this->isEnable2click = $objPG->isEnable2click();
        $this->credit_jobcd = $objPG->getUserSettings('jobcd');
    }

    /**
     * テンプレート変数をassignする
     *
     */
    function setTemplateVars() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $this->tpl_payment_method = $objPG->getPaymentName();

        $objDate = new SC_Date();
        $objDate->setStartYear(date('Y'));
        $objDate->setEndYear(date('Y') + CREDIT_ADD_YEAR);
        $this->arrYear  = $objDate->getZeroYear();
        $this->arrMonth = $objDate->getZeroMonth();

        $this->arrPayMethod = $this->getPayMethod($objPG);

        $this->enable_customer_regist = $objPG->isEnableCustomerRegist();
        $this->enable_security_code = $objPG->isEnableSecurityCode();

    }

    /**
     * 次へボタン押下時の処理.決済処理を行う
     *
     */
    function registerMode() {
        // 通常の決済と登録カード使用時の決済で$objFormを切り替え
        $useCard = (isset($_POST['usecard']) && $_POST['usecard'] == '1');
        $objForm = $useCard ? $this->initParamUseCard() : $this->initParam();
        if ($arrErr = $objForm->checkError()) {
            if ($useCard) {
                $this->getCardMode();
            }
            $this->errorHandler($arrErr, $objForm);
            return;
        }

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog('##### registerMode START #####');

        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objDb = new SC_Helper_DB_Ex();
        $arrInfo = $objDb->sfGetBasisData();

        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrData = $objPurchase->getOrder($_SESSION['order_id']);
        if ($arrData['del_flg'] == '1') {
            $this->panic($_SESSION['order_id'], 'ご注文情報が無効になっています。');
        }

        $objPG->printDLog($arrData);
        $objPG->printDLog($objPurchase->getOrderDetail($_SESSION['order_id']));
        $objPG->printDLog($objPurchase->getShippings($_SESSION['order_id'],true));

        // 「カード情報を登録する」にチェックが入っている場合、(会員ID登録+)カード登録処理を行う
        // または、2クリック決済かつ登録済カードを使用しない場合も登録を行う。
        if ($objForm->getValue('register_card') == '1' ||
            (!$useCard && $this->is2clickFlow)) {
            $objPG->printLog('-> register_card');
            // 会員ID登録の実行
            $objSaveMember = LC_Mdl_PG_MULPAY_Client::factory('Savemember');
            $objSaveMember->request();
            if ($objSaveMember->isError()) {
                $objPG->printLog('-> failed savemember');
                $this->errorHandler($objSaveMember->getError(), $objForm);
                return;
            }
            // カード登録の実行
            $arrForm  = $objForm->getHashArray();
            $objSaveCard = LC_Mdl_PG_MULPAY_Client::factory('Savecard');
            $objSaveCard->request($arrForm);
            if ($objSaveCard->isError()) {
                $objPG->printLog('-> failed savecard');
                $this->errorHandler($objSaveCard->getError(), $objForm);
                return;
            }

            // 登録したカード番号を取得
            $savedCard = $objSaveCard->getResults();
        }


        // memo*をupdateする条件
        // 1. 2clickフローである(chargeは除外)
        // 2. 通常フローでカード使用
        // 3. 通常フローでカード登録あり
        if ($this->is2clickFlow
            || (!isset($_SESSION['mdl_pg_mulpay']['2click']) && ($useCard || $objForm->getValue('register_card') == '1'))) {

            // memo06にCardSeqを残す
            $card = array('paymethod' => $objForm->getValue('paymethod'),
                          'CardSeq'   => is_array($savedCard) ? $savedCard[0]['CardSeq'] : $objForm->getValue('CardSeq'),
                          //'deleteCardSeq' => '',
                          'usecard'   => 1, //$objForm->getValue('usecard'),//必ず登録済カードを使う
                          'paymethod_usecard' => $objForm->getValue('paymethod_usecard'),
                          //'security_code' => $objForm->getValue('security_code'),
                          'payment_id' => $arrData['payment_id'],
                          );


            if ($useCard) {
                $arrCard = $_SESSION['mdl_pg_mulpay']['2click_cardinfo'][$objForm->getValue('CardSeq')];

                $tmp_expire = str_split($arrCard['Expire'], 2);
                $arrCard['Expire'] = $tmp_expire[1] . '月／' . $tmp_expire[0] . '年';

                unset($_SESSION['mdl_pg_mulpay']['2click_cardinfo']);
            } else {
                $arrCard = array('CardNo' => $savedCard[0]['CardNo'],
                                 'Expire' => $objForm->getValue('card_month') . '月／' . $objForm->getValue('card_year') . '年',
                                 'HolderName' => $objForm->getValue('card_name01') . ' ' . $objForm->getValue('card_name02'),
                                 );
            }
            $arrCard['paymethod'] = $this->arrPayMethod[$card['paymethod']];

            $card['user_confirm'] = array('CardNo' => $arrCard['CardNo'],
                                          'Expire' => $arrCard['Expire'],
                                          'HolderName' => $arrCard['HolderName'],
                                          'paymethod' => $arrCard['paymethod'],
                                          );

            $sqlval['memo06'] = serialize($card);

            $objPurchase->registerOrder($_SESSION['order_id'], $sqlval);
        }

        /*
         * 決済を実行する。
         */

        $objPG->printLog('OrderInfo: ' . print_r($arrData,true));
        $objEntry = LC_Mdl_PG_MULPAY_Client::factory('Entry_Credit');
        // EntryTranが実行済みでなければEntryTranへリクエストする.
        if ($objEntry->isComplete($arrData['order_id']) == false) {
            $objEntry->request($arrData);
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
        $arrForm  = $objForm->getHashArray();
        $orderId = $arrData['order_id'];
        $useCard = $objForm->getValue('usecard');

        $objExec = LC_Mdl_PG_MULPAY_Client::factory('Exec_Credit');
        $objExec->request($arrEntryRet, $arrForm, $orderId, $useCard, $this->enable_security_code);
        if ($objExec->isError()) {
            $objPG->printLog('-> failed exec tran');
            $this->errorHandler($objExec->getError(), $objForm);
            return;
        }

        // 結果をログ出力
        $arrExecRet = $objExec->getResults();
        $objPG->printLog(print_r($arrExecRet, true));

        // クレジット決済では、完了画面/メール文面に渡すパラメータは無い
        $sqlval['memo02'] = "";

        // receive.phpで検索条件に使う
        $sqlval['memo03'] = $arrEntryRet['AccessID'];
        
        // カード決済状況変更機能の初期値を設定
        switch ($objPG->getUserSettings('jobcd')) {
        case '0':
        	$sqlval['memo04'] = "AUTH";
            break;
        case '1':
        	$sqlval['memo04'] = "CHECK";
         	$sqlval['del_flg'] = 1; // 有効性チェックの場合、論理削除した状態で作成する。
            break;
        case '2':
            $sqlval['memo04'] = "CAPTURE";
            break;
        }
        
        $objPurchase->registerOrder($orderId, $sqlval);

        // 3Dセキュア有効時はカード会社サイトへのリダイレクトページを出力
        // 3D無効カード時はACSUrlが戻らないため、完了ページに遷移する。
        if (isset($arrExecRet['ACSUrl']) && $objPG->isEnable3DSecure()) {
            $objPG->printLog('-> 3d secure enable');
            $this->tpl_mainpage = MDL_PG_MULPAY_TEMPLATE_PATH . 'credit3d.tpl';
            $this->tpl_onload = 'OnLoadEvent();';
            $this->arrExecRet = $arrExecRet;
            $objSiteSess->setRegistFlag();
            $_SESSION['MDL_PG_MULPAY']['MD'] = $arrExecRet['MD']; // secureTranMode()での確認に使う
            return;
        }

        // 決済成功
        $this->orderComplete($orderId);
    }

    /**
     * 3Dセキュアの戻り
     *
     */
    function secureTranMode() {
        $objForm = $this->initParam();
        $this->arrForm = $objForm->getFormParamList();

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog('##### secureTranMode START #####');

        // ExecTranで取得したMDと同じかどうかを判定する
        if ($_POST['MD'] !== $_SESSION['MDL_PG_MULPAY']['MD']) {
            $objPG->printLog("-> secure tran MD NG: POST:".$_POST['MD']." SESSION:".$_SESSION['MDL_PG_MULPAY']['MD']);
            $this->panic($_SESSION['order_id'], '3DセキュアMDパラメータが一致しませんでした。');
        }

        $objPG->printLog('-> secure tran MD OK');
        $objPG->printLog(print_r($_POST, true));

        $objSecure = LC_Mdl_PG_MULPAY_Client::factory('Secure');
        $objSecure->request();

        // パスワード入力画面で、「キャンセル」で戻ってきたとき
        if ($objSecure->isCancel()) {
            $objPG->printLog('-> cancel input password');
            $this->showCancelPage();
            return;
        }

        if ($objSecure->isError()) {
            $objPG->printLog('-> failed 3d secure');
            $this->errorHandler($objSecure->getError(), $objForm);
            return;
        }

        // 決済成功
        $this->orderComplete($_SESSION['order_id']);
    }

    /**
     * 完了画面へ遷移する
     *
     */
    function toCompletePage() {
        // セッションに保存した情報をクリアする。
        unset($_SESSION['MDL_PG_MULPAY']);

        parent::toCompletePage();
    }

    /**
     * フォームパラメータの初期化
     *
     * @return SC_FormParam
     */
    function initParam() {
        $objForm = new SC_FormParam();
        $this->initParamAdd($objForm);
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        if ($objPG->isEnableSecurityCode()) {
            $objForm->addParam("セキュリティコード", "security_code", SECURITY_CODE_LEN, "n", array("MAX_LENGTH_CHECK", 'NUM_CHECK'));
        }
        
        $objForm->setParam($_POST);
        $objForm->convParam();
        return $objForm;
    }

    function initParamAdd(&$objForm) {
        $objForm->addParam('カード番号', 'card_no', CREDIT_NO_MAX_LEN, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objForm->addParam("カード期限年", "card_year", 2, "n", array("EXIST_CHECK", "NUM_COUNT_CHECK", "NUM_CHECK"));
        $objForm->addParam("カード期限月", "card_month", 2, "n", array("EXIST_CHECK", "NUM_COUNT_CHECK", "NUM_CHECK"));
        $objForm->addParam("名", "card_name01", STEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "ALPHA_CHECK"));
        $objForm->addParam("姓", "card_name02", STEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "ALPHA_CHECK"));
        $objForm->addParam("支払方法", "paymethod", STEXT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("カード登録", "register_card", 1, "n", array("MAX_LENGTH_CHECK", 'NUM_CHECK'));
        return $objForm;
    }

    /**
     * フォームパラメータの初期化(登録カード使用時)
     *
     * @return SC_FormParam
     */
    function initParamUseCard() {
        $_POST['paymethod'] = isset($_POST['paymethod_usecard']) ? $_POST['paymethod_usecard'] : '';

        $objForm = new SC_FormParam();
        $objForm->addParam("支払方法", "paymethod", STEXT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("使用するカード", "CardSeq", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", 'NUM_CHECK'));
        $objForm->addParam("カード登録", "usecard", 1, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", 'NUM_CHECK'));

        $objForm->setParam($_POST);
        $objForm->convParam();
        return $objForm;
    }

    /**
     * モードを返す.
     *
     * @return string
     */
    function getMode() {
        $mode = '';
        // 3Dセキュアの戻り
        if (isset($_POST['PaRes']) && isset($_POST['MD'])) {
            $mode = 'SecureTran';

        // モバイル：カード呼び出し
        } elseif (isset($_POST['getcard']) && $_POST['getcard'] == '登録ｶｰﾄﾞを呼び出す') {
            $mode = 'getcard';

        // モバイル：登録済みカードを使用して決済
        } elseif (isset($_POST['register']) && $_POST['register'] == '選択したｶｰﾄﾞで購入') {
            $mode = 'register';
            $_POST['usecard'] = '1';

        // モバイル：登録済みカードの削除
        } elseif (isset($_POST['deletecard']) && $_POST['deletecard'] == '選択したｶｰﾄﾞの削除') {
            $mode = 'deletecard';
            $_POST['deleteCardSeq'] = isset($_POST['CardSeq']) ? $_POST['CardSeq'] : '';

        } elseif (isset($_POST['mode'])) {
            $mode = $_POST['mode'];
        }

        return $mode;
    }

    /**
     * カード情報を取得する
     *
     */
    function getCardMode() {
        $objForm = $this->initParam();
        $this->arrForm = $objForm->getFormParamList();

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        if ($objPG->isEnableCustomerRegist() == false) return;

        $objPG->printLog('##### getCardMode START #####');

        $objSearchCard = LC_Mdl_PG_MULPAY_Client::factory('Searchcard');
        $objSearchCard->request();

        if ($objSearchCard->isError()) {
            $objPG->printLog('-> failed search card');
            $this->errorHandler($objSearchCard->getError(), $objForm, false);
            return;
        }
        $this->arrCardInfo = $objSearchCard->getResults();
        $this->cardNum = LC_Mdl_PG_MULPAY_Utils::countCard($this->arrCardInfo);

        // 確認画面表示用
        $_SESSION['mdl_pg_mulpay']['2click_cardinfo'] = $this->arrCardInfo;
    }

    /**
     * カード情報を削除する
     *
     */
    function deleteCardMode() {
        $objForm = $this->initParam();
        $this->arrForm = $objForm->getFormParamList();

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        if ($objPG->isEnableCustomerRegist() == false) return;

        $objPG->printLog('##### deleteCardMode START #####');

        if (isset($_POST['deleteCardSeq'])
        &&  SC_Utils_Ex::sfIsInt($_POST['deleteCardSeq'])) {
            $objDeleteCard = LC_Mdl_PG_MULPAY_Client::factory('Deletecard');
            $objDeleteCard->request($_POST['deleteCardSeq']);
            if ($objDeleteCard->isError()) {
                $objPG->printLog('-> failed search card');
                $this->errorHandler($objDeleteCard->getError(), $objForm, false);
                return;
            }
        } else {
            $this->errorHandler(array('CardSeq' => '1'), $objForm, false);
        }
    }

    /* お支払い方法の構築 */
    function getPayMethod(&$objPG) {
        $result = array();
        $method_paytimes = $objPG->getUserSettings('method_paytimes');
        foreach ($method_paytimes as $key => $val) {
            $result[$val] = $GLOBALS['arrPayMethod'][$val];
        }
        return $result;
    }

    function save2Click() {
        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();

        // ユーザユニークIDの取得と購入状態の正当性をチェック
        $uniqid = $objSiteSess->getUniqId();
        $objPurchase->verifyChangeCart($uniqid, $objCartSess);

        // 通常の決済と登録カード使用時の決済で$objFormを切り替え
        $useCard = (isset($_POST['usecard']) && $_POST['usecard'] == '1');
        $objForm = $useCard ? $this->initParamUseCard() : $this->initParam();
        if ($arrErr = $objForm->checkError()) {
            if ($useCard) {
                $this->getCardMode();
            }
            $this->errorHandler($arrErr, $objForm, false);
            return;
        }

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLogU('##### save2click START #####', $uniqid);


        // 「カード情報を登録する」にチェックが入っている場合、(会員ID登録+)カード登録処理を行う
        // または、2クリック決済かつ登録済カードを使用しない場合も登録を行う。
        if ($objForm->getValue('register_card') == '1' ||
            (!$useCard && $this->is2clickFlow)) {
            $objPG->printLogU('-> register_card', $uniqid);
            // 会員ID登録の実行
            $objSaveMember = LC_Mdl_PG_MULPAY_Client::factory('Savemember');
            $objSaveMember->request();
            if ($objSaveMember->isError()) {
                $objPG->printLogU('-> failed savemember', $uniqid);
                $this->errorHandler($objSaveMember->getError(), $objForm, false);
                return;
            }
            // カード登録の実行
            $arrForm  = $objForm->getHashArray();
            $objSaveCard = LC_Mdl_PG_MULPAY_Client::factory('Savecard');
            $objSaveCard->request($arrForm);
            if ($objSaveCard->isError()) {
                $objPG->printLogU('-> failed savecard', $uniqid);
                $this->errorHandler($objSaveCard->getError(), $objForm, false);
                return;
            }

            // 登録したカード番号を取得
            $savedCard = $objSaveCard->getResults();
        }


        // memo*をupdateする条件
        // 1. 2clickフローである(chargeは除外)
        // 2. 通常フローでカード使用
        // 3. 通常フローでカード登録あり
        if ($this->is2clickFlow
            || (!isset($_SESSION['mdl_pg_mulpay']['2click']) && ($useCard || $objForm->getValue('register_card') == '1'))) {

            // memo06にCardSeqを残す
            $card = array('paymethod' => $objForm->getValue('paymethod'),
                          'CardSeq'   => is_array($savedCard) ? $savedCard[0]['CardSeq'] : $objForm->getValue('CardSeq'),
                          //'deleteCardSeq' => '',
                          'usecard'   => 1, //$objForm->getValue('usecard'),//必ず登録済カードを使う
                          'paymethod_usecard' => $objForm->getValue('paymethod_usecard'),
                          //'security_code' => $objForm->getValue('security_code'),
                          'payment_id' => $_SESSION["payment_id"], // 2クリックフロー中では有効
                          );


            if ($useCard) {
                $arrCard = $_SESSION['mdl_pg_mulpay']['2click_cardinfo'][$objForm->getValue('CardSeq')];

                $tmp_expire = str_split($arrCard['Expire'], 2);
                $arrCard['Expire'] = $tmp_expire[1] . '月／' . $tmp_expire[0] . '年';

                unset($_SESSION['mdl_pg_mulpay']['2click_cardinfo']);
            } else {
                $arrCard = array('CardNo' => $savedCard[0]['CardNo'],
                                 'Expire' => $objForm->getValue('card_month') . '月／' . $objForm->getValue('card_year') . '年',
                                 'HolderName' => $objForm->getValue('card_name01') . ' ' . $objForm->getValue('card_name02'),
                                 );
            }
            $arrCard['paymethod'] = $this->arrPayMethod[$card['paymethod']];

            $card['user_confirm'] = array('CardNo' => $arrCard['CardNo'],
                                          'Expire' => $arrCard['Expire'],
                                          'HolderName' => $arrCard['HolderName'],
                                          'paymethod' => $arrCard['paymethod'],
                                          );

            $sqlval['memo06'] = serialize($card);

            LC_Mdl_PG_MULPAY::printLogU('save2Click: payment_id='.$_SESSION['payment_id']. // 2クリックフロー中では有効
                                        ' memo06='.print_r($sqlval,true), $uniqid);
            $objPurchase->saveOrderTemp($uniqid, $sqlval);
        }

        // 2クリック確認画面に戻る。
        $this->return2clickConfirm();
    }
}
/*
 * Local variables:
 * coding: utf-8
 * End:
 */
?>
