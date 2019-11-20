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
require_once(CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php");
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . "LC_Mdl_PG_MULPAY_FormParam.php");
require_once(MDL_PG_MULPAY_CLASS_REALDIR . "LC_Mdl_PG_MULPAY_CheckError.php");

/**
 * PGマルチペイメントサービス決済モジュールの管理画面クラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY_Config extends LC_Page_Admin_Ex {
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $this->tpl_subtitle = $objPG->getName()."  ".MDL_PG_MULPAY_VERSION /*."_".$objPG->getVersion()*/ . ")";
        $this->arrHour = $GLOBALS['arrHour'];
        $this->arrMinutes = $GLOBALS['arrMinutes'];
        $this->arrCONVENI = $GLOBALS['arrCONVENI'];
        $this->arrMethodPaytimes = $GLOBALS['arrPayMethod'];

        switch (ECCUBE_VERSION) {
        case '2.11.0':
        case '2.11.1':
        case '2.11.2':
        case '2.11.3':
        case '2.11.4':
        case '2.11.5':
            $this->extensionAvailable = true;
            break;
        default:
            $this->extensionAvailable = false;
            break;
        }
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
        $this->tpl_mainpage = MDL_PG_MULPAY_TEMPLATE_PATH . '/admin_config.tpl';

        // パラメータ管理クラス
        $objFormParam = new LC_Mdl_PG_MULPAY_FormParam();

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->install();

        switch($this->getMode()) {
        case 'confirm_overwrite':
            // 最終確認画面
            $this->tpl_mainpage = MDL_PG_MULPAY_TEMPLATE_PATH . '/admin_config_confirm.tpl';
            $this->confirmMode($objFormParam);
            break;
        case 'register':
            // 登録処理
            $this->registerMode($objFormParam);
            break;
        case 'return':
            // 入力画面に戻る
            $this->returnMode($objFormParam);
            break;
        default:
            // 初期入力画面
            $this->defaultMode($objFormParam);
            break;
        }
        $this->setTemplate($this->tpl_mainpage);
    }

    /**
     * 初回表示処理
     *
     */
    function defaultMode(&$objForm) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $subData = $objPG->getUserSettings();

        $this->initParam($objForm, $subData);
        $this->arrForm = $objForm->getFormParamList();
    }

    /**
     * 入力画面に戻る
     *
     */
    function returnMode(&$objForm) {
        $this->initParam($objForm);
        $this->arrForm = $objForm->getFormParamList();
    }

    /**
     * 登録ボタン押下時の処理
     *
     */
    function registerMode(&$objForm) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $this->initParam($objForm);
        if ($arrErr = $this->checkError($objForm)) {
            $this->arrErr  = $arrErr;
            $this->arrForm = $objForm->getFormParamList();

            $objPG->printLog("config error: arrErr=".print_r($this->arrErr,true));
            $objPG->printLog("config error: arrForm=".print_r($this->arrForm,true));

            return;
        }

        $arrForm = $objForm->getHashArray();

        // エラー発生時に入力フォームに戻る
        $this->arrForm = $objForm->getFormParamList();

        // 2click決済機能の有効化。空の場合は、無効にする。
        $enable2clickTran = false;
        if (isset($arrForm['2click_LicenseKey']) && $arrForm['2click_LicenseKey'] != '') {
            if ('6c20d7b3a05a00dec0c91f96d84fec75a106df10' !== sha1($arrForm['2click_LicenseKey'])) {
                $this->arrErr['2click_LicenseKey'] = '2クリック決済のライセンスキーが不正です。';
                return;
            }

            $enable2clickTran = true;

            // 連動して「会員ID登録」を有効にする。
            $arrForm['use_customer_reg'] = 1;
        }

        // モバイルSuica支払期限 日、秒どちらかに値が入力されていた場合は、0を設定する。
        if (strlen($arrForm['suica_PaymentTermDay']) !== 0 && strlen($arrForm['suica_PaymentTermSec']) === 0) {
            $arrForm['suica_PaymentTermSec'] = '0';
        }
        if (strlen($arrForm['suica_PaymentTermDay']) === 0 && strlen($arrForm['suica_PaymentTermSec']) !== 0) {
            $arrForm['suica_PaymentTermDay'] = '0';
        }
        // Mobile Suica支払期限 日、秒どちらかに値が入力されていた場合は、0を設定する。
        if (strlen($arrForm['edy_PaymentTermDay']) !== 0 && strlen($arrForm['edy_PaymentTermSec']) === 0) {
            $arrForm['edy_PaymentTermSec'] = '0';
        }
        if (strlen($arrForm['edy_PaymentTermDay']) === 0 && strlen($arrForm['edy_PaymentTermSec']) !== 0) {
            $arrForm['edy_PaymentTermDay'] = '0';
        }
        
        $install_customize = ! $arrForm['not_install_customize'];
        if ($install_customize) {
            $objPG->printLog('モジュールカスタマイズファイルをインストールします。');
        } else {
            $objPG->printLog('設定によりモジュールカスタマイズファイルをインストールしません。');
        }

        // 設定を保存
        $objPG->registerUserSettings($arrForm);

        // モジュールカスタマイズファイルを本体側へインストール
        if ($install_customize) {
            $files = $objPG->getCustomizeFiles($arrForm['credit_CardStatusChangeFunction'] == '1',
                                               $enable2clickTran);

            $arrFailedFile = $objPG->backupFiles($files, $backup_success, $backup_dir);
            if (!$backup_success) {
                if (!$backup_dir) {
                    $alert = 'バックアップディレクトリの作成に失敗しました。\n';
                    $objPG->printLog("バックアップ作成に失敗しました。$backup_dir");
                }
                if (count($arrFailedFile) > 0) {
                    $alert .= '\n下記のファイルのバックアップに失敗しました。\n\n';
                    $alert .= implode('\n', $arrFailedFile);
                }
                $this->tpl_onload .= "alert('" . $alert . "');";
                return;
            }
            $objPG->printLog("バックアップを作成しました。$backup_dir");

            $arrFailedFile = $objPG->copyFiles($files);
            if (count($arrFailedFile) > 0) {
                foreach($arrFailedFile as $file) {
                    $alert = $file . 'に書き込み権限を与えてください。';
                    $this->tpl_onload .= "alert('" . $alert . "');";
                }
                return;
            }
        }

        // クレジット決済
        if ($arrForm['credit_token'] == '0') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'credit.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_CREDIT, "payment_method" => $objPG->getPaymentName(),)
            );
            $this->deletePaymentType(MDL_PG_MULPAY_PAYMENT_TOKEN);
        } else {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'token.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_TOKEN, "payment_method" => $objPG->getPaymentName(),)
            );
            $this->deletePaymentType(MDL_PG_MULPAY_PAYMENT_CREDIT);
        }
        
        // コンビニ決済
        if (isset($arrForm['use_conveni']) && $arrForm['use_conveni'] == '1') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'conveni.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_CONVENI, "payment_method" => 'コンビニ決済', "upper_rule_max" => CONVENI_RULE_MAX, "upper_rule" => CONVENI_RULE_MAX)
            );
        } else {
            $this->deletePaymentType('2');
        }
        // モバイルSuica
        if (isset($arrForm['use_suica']) && $arrForm['use_suica'] == '1') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'suica.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_SUICA, "payment_method" => 'モバイルSuica', "upper_rule_max" => SUICA_RULE_MAX, "upper_rule" => SUICA_RULE_MAX)
            );
        } else {
            $this->deletePaymentType('3');
        }
        // Mobile Edy
        if (isset($arrForm['use_edy']) && $arrForm['use_edy'] == '1') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'edy.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_EDY, "payment_method" => 'Mobile Edy', "upper_rule_max" => EDY_RULE_MAX, "upper_rule" => EDY_RULE_MAX)
            );
        } else {
            $this->deletePaymentType('4');
        }
        // Pay-easy
        if (isset($arrForm['use_payeasy']) && $arrForm['use_payeasy'] == '1') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'atm.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_ATM, "payment_method" => 'ATM決済', "upper_rule_max" => PAYEASY_RULE_MAX, "upper_rule" => PAYEASY_RULE_MAX)
            );
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'netbank.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_NETBANK, "payment_method" => 'ネットバンキング決済', "upper_rule_max" => PAYEASY_RULE_MAX, "upper_rule" => PAYEASY_RULE_MAX)
            );
        } else {
            $this->deletePaymentType('5');
            $this->deletePaymentType('6');
        }
        // PayPal
        if (isset($arrForm['use_paypal']) && $arrForm['use_paypal'] == '1') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'paypal.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_PAYPAL, "payment_method" => 'PayPal', "upper_rule_max" => PAYPAL_RULE_MAX, "upper_rule" => PAYPAL_RULE_MAX)
            );
        } else {
            $this->deletePaymentType('7');
        }
        // iD
        if (isset($arrForm['use_netid']) && $arrForm['use_netid'] == '1') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'netid.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_NETID, "payment_method" => 'iD決済', "upper_rule_max" => NETID_RULE_MAX, "upper_rule" => NETID_RULE_MAX)
            );
        } else {
            $this->deletePaymentType(MDL_PG_MULPAY_PAYMENT_NETID);
        }
        // WebMoney
        if (isset($arrForm['use_webmoney']) && $arrForm['use_webmoney'] == '1') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'webmoney.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_WEBMONEY, "payment_method" => 'WebMoney', "upper_rule_max" => WEBMONEY_RULE_MAX, "upper_rule" => WEBMONEY_RULE_MAX)
            );
        } else {
            $this->deletePaymentType(MDL_PG_MULPAY_PAYMENT_WEBMONEY);
        }
        // auかんたん決済
        if (isset($arrForm['use_au']) && $arrForm['use_au'] == '1') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'au.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_AU, "payment_method" => 'auかんたん決済', "upper_rule_max" => AU_RULE_MAX, "upper_rule" => AU_RULE_MAX)
            );
        } else {
            $this->deletePaymentType(MDL_PG_MULPAY_PAYMENT_AU);
        }
        // ドコモケータイ払い
        if (isset($arrForm['use_docomo']) && $arrForm['use_docomo'] == '1') {
            $this->updatePaymentTable(
                array('module_path' => MDL_PG_MULPAY_PATH . 'docomo.php', 'memo01' => MDL_PG_MULPAY_PAYMENT_DOCOMO, "payment_method" => 'ドコモケータイ払い', "upper_rule_max" => DOCOMO_RULE_MAX, "upper_rule" => DOCOMO_RULE_MAX)
            );
        } else {
            $this->deletePaymentType(MDL_PG_MULPAY_PAYMENT_DOCOMO);
        }


        $this->tpl_onload = "alert('登録完了しました。". '\n基本情報管理＞支払方法設定より詳細設定をしてください。' . "'); window.close();";
    }

    /**
     * 登録ボタン押下時の処理
     *
     */
    function confirmMode(&$objFormParam) {
        $this->initParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        // 入力パラメーターチェック
        //$this->arrErr = $this->lfCheckError($objFormParam);
        $this->arrForm = $objFormParam->getHashArray();

        $this->arrFormConveni = $objFormParam->getValue('conveni');
        $this->arrFormMethodPaytimes = $objFormParam->getValue('method_paytimes');

        // カスタマイズファイルのリスト
        $enableChangeStatus = $objFormParam->getValue('credit_CardStatusChangeFunction') == 1;
        $twoClick_LicenseKey = $objFormParam->getValue('2click_LicenseKey');
        $enable2clickTran = !empty($twoClick_LicenseKey);
        $this->customizeFiles = LC_Mdl_PG_MULPAY::getCustomizeFiles($enableChangeStatus, $enable2clickTran);
    }

    /**
     * フォームパラメータ初期化
     *
     * @param array $arrData
     * @return SC_FormParam
     */
    function initParam(&$objForm, $arrData = null) {
        if (is_null($arrData)) {
            $arrData = $_POST;
        }
        
        // 初期値設定
        $objDb = new SC_Helper_DB_Ex();
        $arrInfo = $objDb->sfGetBasisData();
        $shopName = $arrInfo['shop_name'];
        if (empty($arrData['use_conveni'])) {
            $arrData['conveni_RegisterDisp1'] = mb_substr(mb_convert_kana($shopName, 'AKS', 'UTF-8'), 0, REGISTER_DISP_LEN);
        }
        if (empty($arrData['use_payeasy'])) {
            $arrData['atm_RegisterDisp1'] = mb_substr(mb_convert_kana($shopName, 'AKS', 'UTF-8'), 0, REGISTER_DISP_LEN);
            $arrData['atm_ReceiptsDisp1'] = mb_substr(mb_convert_kana($shopName, 'AKS', 'UTF-8'), 0, RECEIPT_DISP_LEN);
        }
        if (empty($arrData['netid_jobcd'])) {
            $arrData['netid_jobcd'] = 0; // AUTH
        }
        if (empty($arrData['au_jobcd'])) {
            $arrData['au_jobcd'] = 0; // AUTH
        }
        if (empty($arrData['docomo_jobcd'])) {
            $arrData['docomo_jobcd'] = 0; // AUTH
        }

        // 共通設定
        $objForm->addParam('接続先サーバーURL', 'server_url', MTEXT_LEN, '', array('EXIST_CHECK', 'URL_CHECK'));
        $objForm->addParam('管理画面サーバーURL', 'kanri_server_url', MTEXT_LEN, '', array('EXIST_CHECK', 'URL_CHECK'));
        $objForm->addParam('サイトID', 'site_id', STEXT_LEN, '', array('EXIST_CHECK', 'ALNUM_CHECK'));
        $objForm->addParam('サイトパスワード', 'site_pass', STEXT_LEN, '', array('EXIST_CHECK', 'ALNUM_CHECK'));
        $objForm->addParam('ショップID', 'shop_id', STEXT_LEN, '', array('EXIST_CHECK', 'ALNUM_CHECK'));
        $objForm->addParam('ショップパスワード', 'shop_pass', STEXT_LEN, '', array('EXIST_CHECK', 'ALNUM_CHECK'));
        // クレジット決済
        $objForm->addParam('認証方式', 'credit_token', 1, '', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objForm->addParam('処理区分', 'jobcd', 1, '', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objForm->addParam('支払方法／回数', 'method_paytimes', '', '', array('EXIST_CHECK'));
        $objForm->addParam("カード決済状況変更機能", "credit_CardStatusChangeFunction", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("本人認証サービス", "use3d", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("3Dセキュア表示店舗名", "3d_shop_name", 18, "KV", array("MAX_BYTE_LENGTH_CHECK"));
        $objForm->addParam("セキュリティコード", "use_securitycd", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("会員ID登録", "use_customer_reg", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("自由項目1", "credit_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "credit_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("入力回数制限", "use_limit", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("入力回数制限 検出時間", "limit_min", 3, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("入力回数制限 エラー上限回数", "limit_count", 2, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("入力回数制限 ロック時間", "lock_min", 3, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        // コンビニ決済
        $objForm->addParam("コンビニ決済", "use_conveni", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("コンビニ選択", "conveni", 1, "a", array("NUM_CHECK"));
        $objForm->addParam("支払期限", "conveni_PaymentTermDay", PAYMENT_TERM_DAY_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("POSレジ表示欄1", "conveni_RegisterDisp1", REGISTER_DISP_LEN, 'AKVS', array());
        $objForm->addParam("POSレジ表示欄2", "conveni_RegisterDisp2", REGISTER_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("POSレジ表示欄3", "conveni_RegisterDisp3", REGISTER_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("POSレジ表示欄4", "conveni_RegisterDisp4", REGISTER_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("POSレジ表示欄5", "conveni_RegisterDisp5", REGISTER_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("POSレジ表示欄6", "conveni_RegisterDisp6", REGISTER_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("POSレジ表示欄7", "conveni_RegisterDisp7", REGISTER_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("POSレジ表示欄8", "conveni_RegisterDisp8", REGISTER_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄1", "conveni_ReceiptsDisp1", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄2", "conveni_ReceiptsDisp2", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄3", "conveni_ReceiptsDisp3", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄4", "conveni_ReceiptsDisp4", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄5", "conveni_ReceiptsDisp5", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄6", "conveni_ReceiptsDisp6", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄7", "conveni_ReceiptsDisp7", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄8", "conveni_ReceiptsDisp8", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄9", "conveni_ReceiptsDisp9", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("レシート表示欄10", "conveni_ReceiptsDisp10", RECEIPT_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("お問合せ先", "conveni_ReceiptsDisp11", RECEIPT_DISP11_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","PROHIBITED_KIGO_CHECK"));
        $objForm->addParam("お問合せ先電話番号1", "conveni_ReceiptsDisp12_1", RECEIPT_DISP12_LEN, 'n', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("お問合せ先電話番号2", "conveni_ReceiptsDisp12_2", RECEIPT_DISP12_LEN, 'n', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("お問合せ先電話番号3", "conveni_ReceiptsDisp12_3", RECEIPT_DISP12_LEN, 'n', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("お問合せ先受付時間1", "conveni_ReceiptsDisp13_1", RECEIPT_DISP13_LEN, '', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("お問合せ先受付時間2", "conveni_ReceiptsDisp13_2", RECEIPT_DISP13_LEN, '', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("お問合せ先受付時間3", "conveni_ReceiptsDisp13_3", RECEIPT_DISP13_LEN, '', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("お問合せ先受付時間4", "conveni_ReceiptsDisp13_4", RECEIPT_DISP13_LEN, '', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("自由項目1", "conveni_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "conveni_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        // モバイルSuica
        $objForm->addParam("モバイルSuica", "use_suica", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("支払期限日数", "suica_PaymentTermDay", PAYMENT_TERM_DAY_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("支払期限秒", "suica_PaymentTermSec", PAYMENT_TERM_SEC_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("決済開始メール付加情報", "suicaAddInfo1", SUICA_ADDINFO_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK","NO_SPTAB"));
        $objForm->addParam("決済完了メール付加情報", "suicaAddInfo2", SUICA_ADDINFO_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK","NO_SPTAB"));
        $objForm->addParam("決済内容確認画面付加情報", "suicaAddInfo3", SUICA_ADDINFO_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK","NO_SPTAB"));
        $objForm->addParam("決済完了画面付加情報", "suicaAddInfo4", SUICA_ADDINFO_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK","NO_SPTAB"));
        $objForm->addParam("自由項目1", "suica_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "suica_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        // Mobile Edy
        $objForm->addParam("Mobile Edy", "use_edy", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("支払期限日数", "edy_PaymentTermDay", PAYMENT_TERM_DAY_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("支払期限秒", "edy_PaymentTermSec", PAYMENT_TERM_SEC_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("決済開始メール付加情報", "edyAddInfo1", EDY_ADDINFO1_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK","NO_SPTAB"));
        $objForm->addParam("決済完了メール付加情報", "edyAddInfo2", EDY_ADDINFO2_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK","NO_SPTAB"));
        $objForm->addParam("自由項目1", "edy_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "edy_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        // Pay-easy
        $objForm->addParam("Pay-easy", "use_payeasy", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("支払期限(ATM決済)", "atm_PaymentTermDay", PAYMENT_TERM_DAY_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("ATM表示欄1", "atm_RegisterDisp1", REGISTER_DISP_LEN, 'AKVS', array());
        $objForm->addParam("利用明細表示欄1", "atm_ReceiptsDisp1", RECEIPT_DISP_LEN, 'AKVS', array());
        $objForm->addParam("自由項目1", "atm_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "atm_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        // ネットバンキング決済
        $objForm->addParam("支払期限(ネットバンキング決済)", "netbank_PaymentTermDay", PAYMENT_TERM_DAY_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("自由項目1", "netbank_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "netbank_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        // PayPal
        $objForm->addParam("PayPal", "use_paypal", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("自由項目1", "paypal_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "paypal_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        // iD
        $objForm->addParam("iD決済", "use_netid", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam('処理区分', 'netid_jobcd', 1, '', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objForm->addParam("支払期限", "netid_PaymentTermDay", PAYMENT_TERM_DAY_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("自由項目1", "netid_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "netid_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        // WebMoney
        $objForm->addParam("WebMoney決済", "use_webmoney", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam("支払期限", "webmoney_PaymentTermDay", PAYMENT_TERM_DAY_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("自由項目1", "webmoney_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "webmoney_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
	// auかんたん決済
        $objForm->addParam("auかんたん決済", "use_au", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam('処理区分', 'au_jobcd', 1, '', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objForm->addParam("サービス表示名", "au_ServiceName", AU_SERVICE_NAME_LEN, 'AKVS', array("MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
        $objForm->addParam("表示電話番号1", "au_ServiceTel_1", RECEIPT_DISP12_LEN, 'n', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("表示電話番号2", "au_ServiceTel_2", RECEIPT_DISP12_LEN, 'n', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("表示電話番号3", "au_ServiceTel_3", RECEIPT_DISP12_LEN, 'n', array("NUM_CHECK","MAX_LENGTH_CHECK"));
        $objForm->addParam("支払開始期限秒", "au_PaymentTermSec", PAYMENT_TERM_SEC_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("自由項目1", "au_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "au_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
    // ドコモケータイ払い
        $objForm->addParam("ドコモケータイ払い", "use_docomo", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objForm->addParam('処理区分', 'docomo_jobcd', 1, '', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objForm->addParam("ドコモ表示項目1", "DocomoDisp1", REGISTER_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK"));
        $objForm->addParam("ドコモ表示項目2", "DocomoDisp2", REGISTER_DISP_LEN, 'AKVS', array("MAX_LENGTH_CHECK"));
        $objForm->addParam("支払開始期限秒", "docomo_PaymentTermSec", PAYMENT_TERM_SEC_LEN, "a", array("MAX_LENGTH_CHECK","NUM_CHECK"));
        $objForm->addParam("自由項目1", "docomo_ClientField1", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));
        $objForm->addParam("自由項目2", "docomo_ClientField2", CLIENT_FIELD_LEN, 'KV', array("MAX_BYTE_LENGTH_CHECK","PROHIBITED_KIGO_CHECK","PROHIBITED_CHAR_CHECK"));

        // 2click決済
        $objForm->addParam("2クリック決済", "2click_LicenseKey", 32, "KV", array("MAX_BYTE_LENGTH_CHECK"));
        // EC-CUBE本体側へモジュールのカスタマイズファイルをインストールするかどうか制御する
        $objForm->addParam("カスタマイズのインストール", "not_install_customize", 1, "a", array("NUM_CHECK", "MAX_LENGTH_CHECK"));

        $objForm->setParam($arrData);
        $objForm->convParam();

        return $objForm;
    }

    /**
     * 入力パラメータの検証
     *
     * @param SC_FormParam $objForm
     * @return array|null
     */
    function checkError($objForm) {
        $arrErr = $objForm->checkError();

        $arrForm = $objForm->getHashArray();

        if (!empty($arrForm['use_limit'])) {
            $objLimitForm = new LC_Mdl_PG_MULPAY_FormParam;
            $objLimitForm->addParam("入力回数制限 検出時間", "limit_min", 3, "a", array("EXIST_CHECK", "MAX_LENGTH_CHECK","NUM_CHECK"));
            $objLimitForm->addParam("入力回数制限 エラー上限回数", "limit_count", 2, "a", array("EXIST_CHECK", "MAX_LENGTH_CHECK","NUM_CHECK"));
            $objLimitForm->addParam("入力回数制限 ロック時間", "lock_min", 3, "a", array("EXIST_CHECK", "MAX_LENGTH_CHECK","NUM_CHECK"));
            $objLimitForm->setParam($arrForm);
            $objLimitForm->convParam();

            $arrErr = $this->arrMerge($arrErr, $objLimitForm->checkError());
        }

        if (!empty($arrForm['use_conveni'])) {
            $objConveniForm = new LC_Mdl_PG_MULPAY_FormParam;
            $objConveniForm->addParam("コンビニ選択", "conveni", 1, "a", array("EXIST_CHECK"));
            $objConveniForm->addParam("POSレジ表示欄1", "conveni_RegisterDisp1", REGISTER_DISP_LEN, 'AKVS', array('EXIST_CHECK',"MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
            $objConveniForm->addParam("お問合せ先", "conveni_ReceiptsDisp11", RECEIPT_DISP11_LEN, '', array('EXIST_CHECK'));
            $objConveniForm->addParam("お問合せ先電話番号1", "conveni_ReceiptsDisp12_1", RECEIPT_DISP12_LEN, '', array('EXIST_CHECK'));
            $objConveniForm->addParam("お問合せ先電話番号2", "conveni_ReceiptsDisp12_2", RECEIPT_DISP12_LEN, '', array('EXIST_CHECK'));
            $objConveniForm->addParam("お問合せ先電話番号3", "conveni_ReceiptsDisp12_3", RECEIPT_DISP12_LEN, '', array('EXIST_CHECK'));
            $objConveniForm->addParam("お問合せ先受付時間1", "conveni_ReceiptsDisp13_1", RECEIPT_DISP13_LEN, '', array('EXIST_CHECK'));
            $objConveniForm->addParam("お問合せ先受付時間2", "conveni_ReceiptsDisp13_2", RECEIPT_DISP13_LEN, '', array('EXIST_CHECK'));
            $objConveniForm->addParam("お問合せ先受付時間3", "conveni_ReceiptsDisp13_3", RECEIPT_DISP13_LEN, '', array('EXIST_CHECK'));
            $objConveniForm->addParam("お問合せ先受付時間4", "conveni_ReceiptsDisp13_4", RECEIPT_DISP13_LEN, '', array('EXIST_CHECK'));

            $conveni_ReceiptsDisp12_total = $arrForm['conveni_ReceiptsDisp12_1'] . $arrForm['conveni_ReceiptsDisp12_2']. $arrForm['conveni_ReceiptsDisp12_3'];
            $objConveniForm->addParam("お問合せ先電話番号", "conveni_ReceiptsDisp12", RECEIPT_DISP12_TOTAL_LEN_MAX, '', array('MAX_LENGTH_CHECK'));
            $arrForm['conveni_ReceiptsDisp12'] = $conveni_ReceiptsDisp12_total;
            $objConveniForm->addParam("支払期限", "conveni_PaymentTermDay", PAYMENT_TERM_DAY_MAX, '', array('MAX_CHECK'));
            $objConveniForm->setParam($arrForm);
            $objConveniForm->convParam();

            $arrErr = $this->arrMerge($arrErr, $objConveniForm->checkError());
        }

        if (!empty($arrForm['use_payeasy'])) {
            $objPayeasyForm = new LC_Mdl_PG_MULPAY_FormParam;
            $objPayeasyForm->addParam("ATM表示欄1", "atm_RegisterDisp1", REGISTER_DISP_LEN, '', array('EXIST_CHECK',"MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
            $objPayeasyForm->addParam("利用明細表示欄1", "atm_ReceiptsDisp1", RECEIPT_DISP_LEN, '', array('EXIST_CHECK',"MAX_LENGTH_CHECK","PROHIBITED_CHAR_CHECK","HKIGO_CHECK"));
            $objPayeasyForm->addParam("支払期限", "atm_PaymentTermDay", PAYMENT_TERM_DAY_MAX, '', array('MAX_CHECK'));
            $objPayeasyForm->addParam("支払期限", "netbank_PaymentTermDay", PAYMENT_TERM_DAY_MAX, '', array('MAX_CHECK'));

            $objPayeasyForm->setParam($arrForm);
            $objPayeasyForm->convParam();
            $arrErr = $this->arrMerge($arrErr, $objPayeasyForm->checkError());
        }

        if (!empty($arrForm['use_suica'])) {
            $objSuicaForm = new LC_Mdl_PG_MULPAY_FormParam;
            $objSuicaForm->addParam("支払期限(日)", "suica_PaymentTermDay", PAYMENT_TERM_DAY_MAX, '', array('MAX_CHECK'));
            $objSuicaForm->addParam("支払期限(秒)", "suica_PaymentTermSec", PAYMENT_TERM_SEC_MAX, '', array('MAX_CHECK'));

            $objSuicaForm->addParam("支払期限", "suica_PaymentTerm", $arrForm['suica_PaymentTermSec'], '', array('PAYMENT_TERM_CHECK'));
            $arrForm['suica_PaymentTerm'] = $arrForm['suica_PaymentTermDay'];

            $objSuicaForm->setParam($arrForm);
            $objSuicaForm->convParam();
            $arrErr = $this->arrMerge($arrErr, $objSuicaForm->checkError());
        }

        if (!empty($arrForm['use_edy'])) {
            $objSuicaForm = new LC_Mdl_PG_MULPAY_FormParam;
            $objSuicaForm->addParam("支払期限(日)", "edy_PaymentTermDay", PAYMENT_TERM_DAY_MAX, '', array('MAX_CHECK'));
            $objSuicaForm->addParam("支払期限(秒)", "edy_PaymentTermSec", PAYMENT_TERM_SEC_MAX, '', array('MAX_CHECK'));
            $objSuicaForm->addParam("支払期限", "edy_PaymentTerm", $arrForm['edy_PaymentTermSec'], '', array('PAYMENT_TERM_CHECK'));
            $arrForm['edy_PaymentTerm'] = $arrForm['edy_PaymentTermDay'];
            $objSuicaForm->setParam($arrForm);
            $objSuicaForm->convParam();
            $arrErr = $this->arrMerge($arrErr, $objSuicaForm->checkError());
        }

        if (isset($arrForm['use_au']) && $arrForm['use_au'] == '1') {
            $objAuForm = new LC_Mdl_PG_MULPAY_FormParam;
            $objAuForm->addParam("サービス表示名", "au_ServiceName", AU_SERVICE_NAME_LEN, '', array('EXIST_CHECK'));
            $objAuForm->addParam("表示電話番号1", "au_ServiceTel_1", RECEIPT_DISP12_LEN, '', array('EXIST_CHECK'));
            $objAuForm->addParam("表示電話番号2", "au_ServiceTel_2", RECEIPT_DISP12_LEN, '', array('EXIST_CHECK'));
            $objAuForm->addParam("表示電話番号3", "au_ServiceTel_3", RECEIPT_DISP12_LEN, '', array('EXIST_CHECK'));
            $objAuForm->addParam("支払開始期限秒", "au_PaymentTermSec", PAYMENT_TERM_SEC_MAX, '', array('MAX_CHECK'));
            $arrForm['au_ServiceTel'] = $arrForm['au_ServiceTel_1'] . '-' . $arrForm['au_ServiceTel_2'] . '-' . $arrForm['au_ServiceTel_3'];

            $objPG =& LC_Mdl_PG_MULPAY::getInstance();
            $objPG->printLog('au_ServiceTel'. $arrForm['au_ServiceTel']);

            $objAuForm->addParam("表示電話番号", "au_ServiceTel", AU_SERVICE_TEL_LEN, '', array('MAX_LENGTH_CHECK'));
            $objAuForm->setParam($arrForm);
            $objAuForm->convParam();
            $arrErr = $this->arrMerge($arrErr, $objAuForm->checkError());
        }
        if (isset($arrForm['use_docomo']) && $arrForm['use_docomo'] == '1') {
            $objAuForm = new LC_Mdl_PG_MULPAY_FormParam;
            $objAuForm->addParam("支払開始期限秒", "docomo_PaymentTermSec", PAYMENT_TERM_SEC_MAX, '', array('MAX_CHECK'));
            $objAuForm->setParam($arrForm);
            $objAuForm->convParam();
            $arrErr = $this->arrMerge($arrErr, $objAuForm->checkError());
        }
        return $arrErr;
    }

    /**
     * 支払い方法テーブルを更新する.
     *
     * @param boolean $diffData
     */
    function updatePaymentTable($diffData) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $moduleCode = $objPG->getCode(true);

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objSess = new SC_Session_Ex();

        // 登録データ構築
        $arrPaymentInfo = array(
            "fix"            => '3', // 使用してないのでダミー値
            "module_code"    => $objPG->getCode(true),
            "del_flg"        => "0",
            'memo03'         => "###", // 購入フロー中、決済情報入力ページへの遷移振り分けをmemo03で判定している
            "creator_id"     => $objSess->member_id,
            "update_date"    => "NOW()",
        );

        // ランクの最大値を取得する
        $max_rank = $objQuery->getOne("SELECT max(rank) FROM dtb_payment");
        $arrPaymentInfo['rank'] = $max_rank + 1;

        $arrPaymentInfo = array_merge($arrPaymentInfo, $diffData);
        $payment_id = $objQuery->getOne('SELECT payment_id FROM dtb_payment WHERE module_code = ? AND memo01 = ?', array($moduleCode, $diffData['memo01']));

        if($payment_id) {
            $arrPaymentInfo['payment_id'] = $payment_id;
            $objQuery->update("dtb_payment", $arrPaymentInfo, "module_code = ? AND memo01 = ?", array($moduleCode, $diffData['memo01']));
        } else {
            $arrPaymentInfo['payment_id'] = $objQuery->nextVal('dtb_payment_payment_id');
            $objQuery->insert("dtb_payment", $arrPaymentInfo);
        }
    }

    function deletePaymentType($paymentType) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $moduleCode = $objPG->getCode(true);

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->update(
            "dtb_payment", array('del_flg' => '1'),
            "module_code = ? AND memo01 = ?", array($moduleCode, $paymentType)
        );
    }

    function arrMerge($arrBase, $arrTarget) {
        if (is_null($arrBase)) {
            $arrRet = $arrTarget;
        } else {
            if (is_null($arrTarget)) {
                $arrRet = $arrBase;
            } else {
                $arrRet = array_merge($arrBase, $arrTarget);
            }
            
        }
        return $arrRet;
    }

}

?>
