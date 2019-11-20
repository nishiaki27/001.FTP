<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright (c) 2006 PAYGENT Co.,Ltd. All rights reserved.
 *
 * https://www.paygent.co.jp/
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

// {{{ requires
require_once realpath(dirname( __FILE__)) . "/include.php";
require_once CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php";
require_once MODULE_REALDIR . "mdl_paygent/SC_Mdl_Quick_Helper.php";

/**
 * ペイジェント決済モジュールのページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mdl_Paygent_Config extends LC_Page_Admin_Ex {
     var $objFormParam;
     var $arrErr;
     var $module_name;
     var $module_title;

     /**
     * コンストラクタ
     *
     * @return void
     */
     function LC_Page_Mdl_Paygent_Config() {
        $this->module_name = MDL_PAYGENT_CODE;
        $this->objFormParam = new SC_FormParam();
        $this->arrUpdateFile = array(
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/paygent_differencenotice.php",
                  "dst" => USER_REALDIR. MODULE_DIR. $this->module_name. "/paygent_differencenotice.php",
                  "disp" => "html/" . USER_DIR. MODULE_DIR. $this->module_name. "/paygent_differencenotice.php"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/paygent_order_commit.php",
                  "dst" => USER_REALDIR. MODULE_DIR. $this->module_name. "/paygent_order_commit.php",
                  "disp" => "html/" . USER_DIR. MODULE_DIR. $this->module_name. "/paygent_order_commit.php"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/LC_Page_Admin_Order_Ex.php",
                  "dst" => CLASS_EX_REALDIR . "page_extends/admin/order/LC_Page_Admin_Order_Ex.php",
                  "disp" => "data/class_extends/page_extends/admin/order/LC_Page_Admin_Order_Ex.php"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/LC_Page_Admin_Order_Edit_Ex.php",
                  "dst" => CLASS_EX_REALDIR . "page_extends/admin/order/LC_Page_Admin_Order_Edit_Ex.php",
                  "disp" => "data/class_extends/page_extends/admin/order/LC_Page_Admin_Order_Edit_Ex.php"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/LC_Page_Shopping_Complete_Ex.php",
                  "dst" => CLASS_EX_REALDIR . "page_extends/shopping/LC_Page_Shopping_Complete_Ex.php",
                  "disp" => "data/class_extends/page_extends/shopping/LC_Page_Shopping_Complete_Ex.php"),

            // クイック決済用ファイル追加
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/btn_quickkessai_off.jpg",
                  "dst" => HTML_REALDIR . "user_data/packages/default/img/button/btn_quickkessai_off.jpg",
                  "disp" => "html/user_data/packages/default/img/button/btn_quickkessai_off.jpg"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/btn_quickkessai_on.jpg",
                  "dst" => HTML_REALDIR . "user_data/packages/default/img/button/btn_quickkessai_on.jpg",
                  "disp" => "html/user_data/packages/default/img/button/btn_quickkessai_on.jpg"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/btn_quickbuy_off.jpg",
                  "dst" => HTML_REALDIR . "user_data/packages/default/img/button/btn_quickbuy_off.jpg",
                  "disp" => "html/user_data/packages/default/img/button/btn_quickbuy_off.jpg"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/btn_quickbuy_on.jpg",
                  "dst" => HTML_REALDIR . "user_data/packages/default/img/button/btn_quickbuy_on.jpg",
                  "disp" => "html/user_data/packages/default/img/button/btn_quickbuy_on.jpg"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/LC_Page_Cart_Ex.php",
                  "dst" => CLASS_EX_REALDIR . "page_extends/cart/LC_Page_Cart_Ex.php",
                  "disp" => "data/class_extends/page_extends/cart/LC_Page_Cart_Ex.php"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/LC_Page_Shopping_Confirm_Ex.php",
                  "dst" => CLASS_EX_REALDIR . "page_extends/shopping/LC_Page_Shopping_Confirm_Ex.php",
                  "disp" => "data/class_extends/page_extends/shopping/LC_Page_Shopping_Confirm_Ex.php"),

            // 後払い決済
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/banner_atodene_pc.gif",
                "dst" => HTML_REALDIR . "user_data/packages/default/img/banner/banner_atodene_pc.gif",
                "disp" => "html/user_data/packages/default/img/banner/banner_atodene_pc.gif"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/banner_atodene_sp.gif",
                "dst" => HTML_REALDIR . "user_data/packages/sphone/img/banner/banner_atodene_sp.gif",
                "disp" => "html/user_data/packages/sphone/img/banner/banner_atodene_sp.gif"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/banner_atodene_m.gif",
                "dst" => HTML_REALDIR . "user_data/packages/mobile/img/banner/banner_atodene_m.gif",
                "disp" => "html/user_data/packages/mobile/img/banner/banner_atodene_m.gif"),
            
            // Paidy決済
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/banner_paidy_checkout_all.png",
                "dst" => HTML_REALDIR . "user_data/packages/default/img/banner/banner_paidy_checkout_all.png",
                "disp" => "html/user_data/packages/default/img/banner/banner_paidy_checkout_all.png"),
            array("src" => MODULE_REALDIR. $this->module_name. "/copy/banner_paidy_checkout_all.png",
                "dst" => HTML_REALDIR . "user_data/packages/sphone/img/banner/banner_paidy_checkout_all.png",
                "disp" => "html/user_data/packages/sphone/img/banner/banner_paidy_checkout_all.png")
        );
        // 2.11.0対応
	    if(ECCUBE_VERSION == "2.11.0") {
	    	$this->arrUpdateFile[] = array("src" => MODULE_REALDIR. $this->module_name. "/copy/SC_Helper_Purchase_Ex.php",
	          "dst" => CLASS_EX_REALDIR . "helper_extends/SC_Helper_Purchase_Ex.php",
	          "disp" => "data/class_extends/helper_extends/SC_Helper_Purchase_Ex.php");
	    }
     }

     /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->module_title = "ペイジェント決済モジュール";
        $this->tpl_mainpage = MODULE_REALDIR . $this->module_name. "/templates/admin/config.tpl";
        $this->tpl_subtitle = $this->module_title;
        $this->arrErr = array();
        $this->arrSettlement = getSettlement();
        $this->arrPayment = getPayment();
        $this->arrLinkPayment = getLinkPayment();
        $this->arrActive = getOptionActive();
        $this->arrCardClass = getCardClass();
        $this->arrNumberingType = getNumberingType();
        $this->arrResultGetType = getResultGetType();
        $this->arrExamResultNotificationType = getExamResultNotificationType();
        $this->arrInvoiceIncludeOption = getInvoiceIncludeOption();
        $this->arrAutoCancelType = getAutoCancelType();
        $this->arrCartPaymentCategory = getCartPaymentCategory();
        $this->arrTokenEnv = getTokenEnv();
        $this->arrCareerPaymentCategory = getCareerPaymentCategory();
        $this->arrEmoneyPaymentCategory = getEmoneyPaymentCategory();
        // 不足しているカラムがあれば追加する。
        $this->updateTable();
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
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // 最初の状態の決済種別を取得する
        $first_settlement_division = $this->getSettlementDivision();
        $settlement_division = $_POST['settlement_division'];

        // javascript実行
        if(strlen($settlement_division) > 0) {
            $this->tpl_onload .= "lfCheckSettlement($settlement_division);lfnCheckPayment();lfnCheckLinkPayment();";
        } elseif (strlen($first_settlement_division) > 0) {
            $this->tpl_onload .= "lfCheckSettlement($first_settlement_division);lfnCheckPayment();lfnCheckLinkPayment();";
        } else {
            $this->tpl_onload .= "lfCheckSettlement(". SETTLEMENT_MODULE. ");lfnCheckPayment();lfnCheckLinkPayment();";
        }

        //バリデーションの判定に必要な入力値をセット
        $arrInputForValidation = array();
        $arrInputForValidation['token_pay'] = $_POST['token_pay'];

        // パラメータ管理クラス
        $this->initParam($settlement_division, $arrInputForValidation);
        // POST値の取得
        $this->objFormParam->setParam($_POST);

        switch ($this->getMode()) {
        case 'edit':
            // 入力エラー判定
            $this->arrErr = $this->checkError($settlement_division);
            // エラーなしの場合にはデータを更新
            if (count($this->arrErr) == 0) {
                // 支払い方法登録
                $this->setPaymentDB($settlement_division);
                // 設定情報登録
                $this->setConfig();
                if ($this->updateFile()) {
                    // javascript実行
                    $this->tpl_onload .= 'alert("登録完了しました。\n基本情報＞支払方法設定より詳細設定をしてください。"); window.close();';
                } else {
                    // javascript実行
                    foreach($this->arrUpdateFile as $array) {
                        if(!is_writable($array['dst'])) {
                            $alert = $array['dst'] . "に書き込み権限を与えてください。";
                            $this->tpl_onload.= "alert(\"". $alert. "\");";
                        }
                    }
                }
            }
            break;
        case 'module_del':
            // 汎用項目の存在チェック
            $objDB = new SC_Helper_DB_Ex();
            if ($objDB->sfColumnExists("dtb_payment", "memo01")) {
                // 支払方法の削除フラグを立てる
                $arrDel = array('del_flg' => "1");
                $objQuery->update("dtb_payment", $arrDel, " module_code = ?", array($this->module_name));
            }
            break;
        default:
            // データのロード
            $arrConfig = $this->getConfig();
            $this->objFormParam->setParam($arrConfig);
            break;
        }

        $this->arrForm = $this->objFormParam->getFormParamList();
        $this->setTemplate($this->tpl_mainpage);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
      *  パラメータ情報の初期化
      *  2.12.0からデフォルト値（入力不可でも）も文字列チェックの対象となったため、
      *  加盟店名系の項目のデフォルト値を空文字としている
      */
    function initParam($settlement_division, $arrInputForValidation) {
        $arrSiteInfo = SC_Helper_DB::sfGetBasisData(true);
        // デフォルト値
        $arrDefault  = array(
            'settlement_division' => SETTLEMENT_MODULE,
            'credit_3d' => "0",
            'stock_card' => "0",
            'security_code' => "0",
            'quick_pay' => "0",
            'token_pay' => "0",
            'token_env' => "0",
            'payment_division' => "",
            'conveni_limit_date_num' => 15,
            'conveni_limit_date_call' => 30,
            'conveni_valid_limit_date_call' => 30,
            'atm_limit_date' => 30,
            //'payment_detail' => $arrSiteInfo['shop_kana'],
            'payment_detail' => "",
            'asp_payment_term' => 7,
            'card_class' => "0",
            'card_conf' => "0",
            'link_payment_term' => 5,
            //'claim_kanji' => $arrSiteInfo['shop_name'],
            'claim_kanji' => "",
            //'claim_kana' => $arrSiteInfo['shop_kana'],
            'claim_kana' => "",
            //'merchant_name' => $arrSiteInfo['shop_name'],
            'merchant_name' => "",
        	'career_division' => "",
        	'emoney_division' => "",
            'numbering_type' => "0",
            'virtual_account_limit_date' => 30,
            'result_get_type' => "0",
            'exam_result_notification_type' => "0",
            'link_result_get_type' => "0",
            'link_auto_cancel_type' => "0",
            'invoice_include' => "",
            'link_invoice_include' => "",
            'api_key' => "",
            'logo_url' => "",
            'paidy_store_name' => "",
        );
        $this->objFormParam->addParam("システム種別", "settlement_division", "", "n", array("NUM_CHECK", "EXIST_CHECK"), $arrDefault['settlement_division']);

        /*
         * マーチャントID
         *
         * 半角数字に変換
         * - 半角数字チェック
         * - 必須チェック
         * - 最大文字数チェック(9桁)
         */
        $this->objFormParam->addParam("マーチャントID", "merchant_id", 9, "n", array("NUM_CHECK", "EXIST_CHECK", "MAX_LENGTH_CHECK"));

        /*
         * モジュール型, 混合型のチェック
         *
         * 決済種別がリンク型の場合、下記の項目はエラーチェック対象外
         */
        if ($settlement_division != SETTLEMENT_LINK) {

            /*
             * 接続ID
             *
             * 半角英数字に変換
             * - 半角英数字チェック
             * - 必須チェック
             * - 最大文字数チェック(32桁)
             */
            $this->objFormParam->addParam("接続ID", "connect_id", 32, "a", array("ALNUM_CHECK", "EXIST_CHECK", "MAX_LENGTH_CHECK"));

            /*
             * 接続パスワード
             *
             * 半角英数字に変換
             * - 半角英数字チェック
             * - 必須チェック
             * - 最大文字数チェック(32桁)
             */
            $this->objFormParam->addParam("接続パスワード", "connect_password", 32, "a", array("ALNUM_CHECK", "EXIST_CHECK", "MAX_LENGTH_CHECK"));

            /*
             * モジュール型のチェック
             *
             *  決済種別が混合型の場合、下記の項目はエラーチェック対象外
             */
            if ($settlement_division != SETTLEMENT_MIX) {

                /*
                 * 利用決済
                 *
                 * - 必須チェック
                 */
                $this->objFormParam->addParam("利用決済", "payment", "", "", array("EXIST_CHECK"));

                /*
                 * 3Dセキュア
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 */
                $this->objFormParam->addParam("3Dセキュア", "credit_3d", "", "n", array("NUM_CHECK"), $arrDefault['credit_3d']);

                /*
                 * カードお預かり機能
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 */
                $this->objFormParam->addParam("カード情報お預かり機能", "stock_card", "", "n", array("NUM_CHECK"), $arrDefault['stock_card']);

                /*
                 * 支払回数
                 *
                 * - 必須チェック
                 */
                $this->objFormParam->addParam("支払回数", "payment_division", "", "", array());

                /*
                 * セキュリティコード
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 */
                $this->objFormParam->addParam("セキュリティコード", "security_code", "", "n", array("NUM_CHECK"), $arrDefault['security_code']);

                /*
                 * セキュリティコード
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 */
                $this->objFormParam->addParam("クイック決済", "quick_pay", "", "n", array("NUM_CHECK"), $arrDefault['quick_pay']);

                /*
                 * トークン決済
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 */
                $this->objFormParam->addParam("トークン決済", "token_pay", "", "n", array("NUM_CHECK"), $arrDefault['token_pay']);

                /*
                 * トークン接続先
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 */
                $this->objFormParam->addParam("トークン接続先", "token_env", "", "n", array("NUM_CHECK"), $arrDefault['token_env']);

                /*
                 * トークン生成鍵
                 *
                 * 半角英数字に変換
                 * - 半角英数字記号チェック
                 * - 必須チェック(トークン決済の場合)
                 * - 最大文字数チェック(100桁)
                 */
                //トークン決済の場合は必須チェック
                if ($arrInputForValidation['token_pay'] === "1") {
                    $this->objFormParam->addParam("トークン生成鍵", "token_key", 100, "a", array("GRAPH_CHECK", "EXIST_CHECK", "MAX_LENGTH_CHECK", "NO_SPTAB"));
                } else {
                    $this->objFormParam->addParam("トークン生成鍵", "token_key", 100, "a", array("GRAPH_CHECK", "MAX_LENGTH_CHECK", "NO_SPTAB"));
                }

                /* ------------------------------------------------------------
                 * コンビニ決済番号方式 のチェック
                 * ------------------------------------------------------------
                 */

                /*
                 * 支払期限日
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 * - 最大文字数チェック(2桁)
                 */
                $this->objFormParam->addParam("支払期限日", "conveni_limit_date_num", 2, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), $arrDefault['conveni_limit_date_num']);

                /* ------------------------------------------------------------
                 * コンビニ決済払込方式 のチェック
                 * ------------------------------------------------------------
                 */

                /*
                 * 支払期限日
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 * - 最大文字数チェック(3桁)
                 */
                //$this->objFormParam->addParam("支払期限日", "conveni_limit_date_call", 3, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), $arrDefault['conveni_limit_date_call']);
                /*
                 * 有効期限日
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 * - 最大文字数チェック(3桁)
                 */
                //$this->objFormParam->addParam("有効期限日", "conveni_valid_limit_date_call", 3, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), $arrDefault['conveni_valid_limit_date_call']);
                /*
                 * 支払情報
                 *
                 * 全角文字に変換
                 * - 最大文字数チェック(30桁)
                 */
                //$this->objFormParam->addParam("支払情報", "conveni_free_memo_call", 30, "KVA", array("MAX_LENGTH_CHECK"));

                /* ------------------------------------------------------------
                 * ATM方式 のチェック
                 * ------------------------------------------------------------
                 */

                /*
                 * 支払期限日
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 * - 最大文字数チェック(2桁)
                 */
                $this->objFormParam->addParam("支払期限日", "atm_limit_date", 2, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), $arrDefault['atm_limit_date']);

                /*
                 * 店舗名(カナ)
                 *
                 * 全角に変換
                 * - カナチェック
                 * - 最大文字数チェック(12桁)
                 */
                $this->objFormParam->addParam("店舗名（カナ）", "payment_detail", 12, "CKVa", array("MAX_LENGTH_CHECK", "KANA_CHECK"), $arrDefault['payment_detail']);

                /* ------------------------------------------------------------
                 * 銀行ネット決済方式 のチェック
                 * ------------------------------------------------------------
                 */

                /*
                 * 支払期限日
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 * - 最大文字数チェック(2桁)
                 */
                $this->objFormParam->addParam("支払期限日", "asp_payment_term", 2, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), $arrDefault['asp_payment_term']);

                /*
                 * 店舗名
                 *
                 * 全角に変換
                 * - 最大文字数チェック(12桁)
                 */
                $this->objFormParam->addParam("店舗名", "claim_kanji", 12, "KVA", array("MAX_LENGTH_CHECK"), $arrDefault['claim_kanji']);

                /*
                 * 店舗名(カナ)
                 *
                 * 全角に変換
                 * - カナチェック
                 * - 最大文字数チェック(12桁)
                 */
                $this->objFormParam->addParam("店舗名（カナ）", "claim_kana", 12, "CKVa", array("MAX_LENGTH_CHECK", "KANA_CHECK"), $arrDefault['claim_kana']);

                /*
                 * コピーライト
                 *
                 * 文字種チェックは checkError() 関数で行う
                 *
                 * 半角英数字に変換
                 * - 最大文字数チェック(32桁)
                 */
                $this->objFormParam->addParam("コピーライト", "copy_right", 32, "KVa", array("MAX_LENGTH_CHECK"));

                /*
                 * 自由メモ欄
                 *
                 * 全角に変換
                 * - 最大文字数チェック(128桁)
                 */
                $this->objFormParam->addParam("自由メモ欄", "free_memo", 128, "KVA", array("MAX_LENGTH_CHECK"));

                /* ------------------------------------------------------------
                 * 携帯キャリア決済方式 のチェック
                 * ------------------------------------------------------------
                 */

                /*
                 * 支払回数
                 *
                 * - 必須チェック
                 */
                $this->objFormParam->addParam("利用決済", "career_division", "", "", array());

                /* ------------------------------------------------------------
                 * 電子マネー決済方式 のチェック
                 * ------------------------------------------------------------
                 */

                /*
                 * 支払回数
                 *
                 * - 必須チェック
                 */
                $this->objFormParam->addParam("利用決済", "emoney_division", "", "", array());


                /* ------------------------------------------------------------
                 * 仮想口座決済方式 のチェック
                 * ------------------------------------------------------------
                 */

                /*
                 * 付番区分
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 */
                $this->objFormParam->addParam("付番区分", "numbering_type", "", "n", array("NUM_CHECK"), $arrDefault['numbering_type']);

                /*
                 * 支払期限日
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 * - 最大文字数チェック(2桁)
                 */
                $this->objFormParam->addParam("支払期限日", "virtual_account_limit_date", 3, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), $arrDefault['virtual_account_limit_date']);

                /* ------------------------------------------------------------
                 * 後払い決済方式 のチェック
                 * ------------------------------------------------------------
                 */

                /*
                 * 結果取得区分
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 */
                $this->objFormParam->addParam("結果取得区分", "result_get_type", "", "n", array("NUM_CHECK"), $arrDefault['result_get_type']);

                /*
                 * 審査結果通知メール
                 *
                 * 半角数字に変換
                 * - 数値チェック
                 */
                $this->objFormParam->addParam("審査結果通知メール", "exam_result_notification_type", "", "n", array("NUM_CHECK"), $arrDefault['exam_result_notification_type']);

                /*
                 * 請求書の同梱
                 *
                 */
                $this->objFormParam->addParam("請求書の同梱", "invoice_include", "", "", array());
                
                /* ------------------------------------------------------------
                 * Paidy決済方式 のチェック
                 * ------------------------------------------------------------
                 */
                
                /*
                 * Paidy API_KEY
                 */
                $this->objFormParam->addParam("パブリックキー", "api_key", "", "", array(), $arrDefault['api_key']);
                
                /*
                 * Paidy Logo_URL
                 */
                $this->objFormParam->addParam("ロゴURL", "logo_url", URL_LEN, "KVa", array("MAX_LENGTH_CHECK", "URL_CHECK"));
                
                /*
                 * Paidy 店舗名(全角)
                 */
                $this->objFormParam->addParam("店舗名", "paidy_store_name", "12", "", array("MAX_LENGTH_CHECK"), $arrDefault['paidy_store_name']);
            }
        }

        /*
         * リンク型, 混合型のチェック
         *
         * 決済種別がリンク型/混合型の場合、下記の項目を追加
         */
        if ($settlement_division != SETTLEMENT_MODULE) {

            /*
             * 利用決済
             *
             */
            $this->objFormParam->addParam("利用決済", "link_payment", "", "", array());

            /*
             * カード支払区分
             *
             * 半角数字に変換
             * - 数値チェック
             */
            $this->objFormParam->addParam("カード支払区分", "card_class", "", "n", array("NUM_CHECK"), $arrDefault['card_class']);

            /*
             * カード確認番号
             *
             * 半角数字に変換
             * - 数値チェック
             */
            $this->objFormParam->addParam("カード確認番号", "card_conf", "", "n", array("NUM_CHECK"), $arrDefault['card_conf']);

            /*
             * 支払期限日
             *
             * 半角数字に変換
             * - 数値チェック
             * - 最大文字数チェック(2桁)
             */
            $this->objFormParam->addParam("支払期限日", "link_payment_term", 2, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), $arrDefault['link_payment_term']);

            /*
             * 自動取得区分
             *
             * 半角数字に変換
             * - 数値チェック
             */
            $this->objFormParam->addParam("自動取得区分", "link_result_get_type", "1", "n", array("NUM_CHECK"), $arrDefault['link_result_get_type']);

            /*
             * 自動キャンセル区分
             *
             * 半角数字に変換
             * - 数値チェック
             */
            $this->objFormParam->addParam("自動キャンセル区分", "link_auto_cancel_type", "1", "n", array("NUM_CHECK"), $arrDefault['link_auto_cancel_type']);

            /*
             * 請求書の同梱
             *
             */
            $this->objFormParam->addParam("請求書の同梱", "link_invoice_include", "", "", array());

            /*
             * リクエスト先URLチェック
             *
             * 半角英数字に変換
             * - URLチェック
             * - 最大文字数チェック(300桁)
             */
            $this->objFormParam->addParam("リクエスト先URL", "link_url", URL_LEN, "KVa", array("MAX_LENGTH_CHECK", "URL_CHECK"));

            /*
             * ハッシュ値生成キー
             *
             * 半角英数字に変換
             * - 最大文字数チェック(84桁)
             */
            $this->objFormParam->addParam("ハッシュ値生成キー", "hash_key", 84, "KVa", array("MAX_LENGTH_CHECK"));

            /*
             * 差分通知ハッシュ値生成キー
             *
             * 半角英数字に変換
             * - 最大文字数チェック(84桁)
             */
            $this->objFormParam->addParam("差分通知ハッシュ値生成キー", "sabun_hash_key", 84, "KVa", array("MAX_LENGTH_CHECK"));
            
            /*
             * 店舗名
             *
             * 全角文字に変換
             * - 最大文字数チェック(32桁)
             */
            $this->objFormParam->addParam("店舗名", "merchant_name", 32, "KVA", array("MAX_LENGTH_CHECK"), $arrDefault['merchant_name']);

            /*
             * 自由メモ欄
             *
             * 全角文字に変換
             * - 最大文字数チェック(128桁)
             */
            $this->objFormParam->addParam("自由メモ欄", "link_free_memo", 128, "KVA", array("MAX_LENGTH_CHECK"));

            /*
             * コピーライト
             *
             * 文字種チェックは checkError() 関数で行う
             *
             * 半角英数字に変換
             * - 最大文字数チェック(256桁)
             */
            $this->objFormParam->addParam("コピーライト", "link_copy_right", 128, "KVa", array("MAX_LENGTH_CHECK"));
        }
    }

    /**
     * エラーチェック
     */
    function checkError($settlement_division){
        $this->objFormParam->convParam();
        $arrErr = $this->objFormParam->checkError();

        // 全角文字チェック
        $arrWideCharParams = array('conveni_free_memo_call' => "支払情報",
                                   'payment_detail' => "店舗名（カナ）",
                                   'claim_kana' => "店舗名（カナ）",
                                   'claim_kanji' => "店舗名",
                                   'free_memo' => "自由メモ欄",
                                   'merchant_name' => "店舗名",
                                   'link_free_memo' => "自由メモ欄");

        foreach ($arrWideCharParams as $key => $val) {
            $value = $this->objFormParam->getValue($key);
            if (isset($_POST[$key]) && !empty($value)) {
                if (preg_match('/\s/', $value)) {
                    $arrErr[$key] = "※ " . $val . "に半角スペース・改行は入力できません<br />";
                }
            }
        }

        // 決済種別がモジュール型の場合のみ、このエラーチェックを通す
        if ($settlement_division == SETTLEMENT_MODULE) {
            foreach ($_POST["payment"] as $key => $val) {
                // クレジット
                if ($val == PAY_PAYGENT_CREDIT) {
                    if (!isset($_POST['payment_division']) || count($_POST['payment_division']) === 0) {
                        $arrErr['payment_division'] = "※ 支払回数が入力されていません。<br />";
                    }
                }
            }

            // コンビニ(番号方式)
            $conveni_limit_date_num = $this->objFormParam->getValue('conveni_limit_date_num');
            if (isset($_POST['conveni_limit_date_num']) && !($conveni_limit_date_num >= 1 && $conveni_limit_date_num <= 60)) {
                $arrErr['conveni_limit_date_num'] = "※ 支払期限日は1～60日で設定してください。<br />";
            }
            /*
             * // コンビニ(払込票方式)
             * $conveni_limit_date_call = $this->objFormParam->getValue('conveni_limit_date_call');
             * $conveni_valid_limit_date_call = $this->objFormParam->getValue('conveni_valid_limit_date_call');
             * if (isset($_POST['conveni_limit_date_call']) && !($conveni_limit_date_call >= 1 && $conveni_limit_date_call <= 364)) {
             *     $arrErr['conveni_limit_date_call'] = "※ 支払期限日は1～364日で設定してください。<br />";
             * } elseif (strlen($conveni_valid_limit_date_call) > 0 && $conveni_limit_date_call > $conveni_valid_limit_date_call) {
             *     $arrErr['conveni_limit_date_call'] = "※ 支払期限日は有効期限日以下で設定してください。<br />";
             * }
             * if (isset($_POST['conveni_valid_limit_date_call']) && !($conveni_valid_limit_date_call >= 1 && $conveni_valid_limit_date_call <= 364)) {
             *     $arrErr['conveni_valid_limit_date_call'] = "※ 有効期限日は1～364日で設定してください。<br />";
             * }
             * if (isset($_POST['conveni_free_memo_call']) && strlen($_POST['conveni_free_memo_call']) === 0) {
             *     $arrErr['conveni_free_memo_call'] = "※ 支払情報が入力されていません。<br />";
             * }
            */
            // ATM
            $atm_limit_date = $this->objFormParam->getValue('atm_limit_date');
            if (isset($_POST['atm_limit_date']) && !($atm_limit_date >= 1 && $atm_limit_date <= 60 || $atm_limit_date == "0")) {
                $arrErr['atm_limit_date'] = "※ 支払期限日は0～60日で設定してください。<br />";
            }
            if (isset($_POST['payment_detail']) && strlen($_POST['payment_detail']) === 0) {
                $arrErr['payment_detail'] = "※ 店舗名(カナ)が入力されていません。<br />";
            }

            // 銀行ネット
            $asp_payment_term = $this->objFormParam->getValue('asp_payment_term');
            if (isset($_POST['asp_payment_term']) && !($asp_payment_term >= 1 && $asp_payment_term <= 99)) {
                $arrErr['asp_payment_term'] = "※ 支払期限日は1～99日で設定してください。<br />";
            }
            if (isset($_POST['copy_right']) && strlen($_POST['copy_right']) > 0) {
                $copy_right = $this->objFormParam->getValue('copy_right');
                if (!eregi("^[[:alnum:][:space:]/\\,.()-]+$", $copy_right)) {
                    $arrErr['copy_right'] = "※ 決済ページ用コピーライトは英数字で入力してください。<br />";
                }
            }
            if (isset($_POST['claim_kanji']) && strlen($_POST['claim_kanji']) === 0) {
                $arrErr['claim_kanji'] = "※ 店舗名が入力されていません。<br />";
            }
            if (isset($_POST['claim_kana']) && strlen($_POST['claim_kana']) === 0) {
                $arrErr['claim_kana'] = "※ 店舗名(カナ)が入力されていません。<br />";
            }
            // 携帯キャリア
            foreach ($_POST["payment"] as $key => $val) {
            	// 携帯キャリア
                if ($val == PAY_PAYGENT_CAREER) {
                    if (!isset($_POST['career_division']) || count($_POST['career_division']) === 0) {
                        $arrErr['career_division'] = "※ 利用決済が入力されていません。<br />";
                    }
                }
            }
            // 電子マネー
            foreach ($_POST["payment"] as $key => $val) {
            	// 電子マネー
                if ($val == PAY_PAYGENT_EMONEY) {
                    if (!isset($_POST['emoney_division']) || count($_POST['emoney_division']) === 0) {
                        $arrErr['emoney_division'] = "※ 利用決済が入力されていません。<br />";
                    }
                }
            }
            // 仮想口座
            $virtual_account_limit_date = $this->objFormParam->getValue('virtual_account_limit_date');
            if (isset($_POST['virtual_account_limit_date']) && !(strval($virtual_account_limit_date) != '' && $virtual_account_limit_date >= 0 && $virtual_account_limit_date <= 364)) {
                $arrErr['virtual_account_limit_date'] = "※ 支払期限日は0～364日で設定してください。<br />";
            }
            // Paidy
            if (isset($_POST['api_key']) && strlen($_POST['api_key']) === 0) {
                $arrErr['api_key'] = "※ パブリックキーが入力されていません。<br />";
            }
        }

        // 決済種別がリンク型/混合型の場合、このエラーチェックを通す
        if ($settlement_division != SETTLEMENT_MODULE) {
            if (isset($_POST['link_url']) && strlen($_POST['link_url']) === 0) {
                $arrErr['link_url'] = "※ リクエスト先URLが入力されていません。<br />";
            }
            if (isset($_POST['hash_key']) && strlen($_POST['hash_key']) > 0) {
                if(!file_exists(realpath(dirname( __FILE__)). "/paygent_hash.php")) {
                    $arrErr['hash_key'] = "※ ペイジェント提供のハッシュ生成プログラムを設置してください。<br />";
                }
                if (!function_exists("hash") && !function_exists("mhash")) {
                    $arrErr['hash_key'] = "※ hash関数かmhash関数の利用が必須です。<br />";
                }
            }
            $link_payment_term = $this->objFormParam->getValue('link_payment_term');
            if (isset($_POST['link_payment_term']) && !($link_payment_term >= 2 && $link_payment_term <= 60)) {
                $arrErr['link_payment_term'] = "※ 支払期限日は2～60日で設定してください。<br />";
            }
            if (isset($_POST['link_copy_right']) && strlen($_POST['link_copy_right']) > 0) {
                $copy_right = $this->objFormParam->getValue('link_copy_right');
                if (!eregi("^[[:alnum:][:space:]/\\,.()-]+$", $copy_right)) {
                    $arrErr['link_copy_right'] = "※ 決済ページ用コピーライトは英数字で入力してください。<br />";
                }
            }
        }

        // 決済種別がモジュール型/混合型の場合、このエラーチェックを通す
        if ($settlement_division != SETTLEMENT_LINK) {
            // モジュールファイルの存在チェック
            $path = realpath(dirname( __FILE__)). "/jp/co/ks/merchanttool/connectmodule/";
            if(!file_exists(realpath(dirname( __FILE__)). "/jp/co/ks/merchanttool/connectmodule/")) {
                $arrErr['err'] = "※ ペイジェント提供モジュールを設置してください。<br /> " . $path;
            // 接続テストを実行
            } else {
                // マーチャントID
                $arrParam['merchant_id'] = $this->objFormParam->getValue('merchant_id');
                // 接続ID
                $arrParam['connect_id'] = $this->objFormParam->getValue('connect_id');
                // 接続パスワード
                $arrParam['connect_password'] = $this->objFormParam->getValue('connect_password');
                // 実行
                if(!sfPaygentTest($arrParam)) {
                    $arrErr['err'] = "※ 接続試験に失敗しました。<br />";
                    if (isset($arrParam['result_message']) && $arrParam['result_message']) {
                    	$arrErr['err'] .= nl2br($arrParam['result_message']);
                    }
                } else {
	                // クイック決済チェック
		            $objQuickHelper = new SC_Mdl_Quick_Helper();
		            $quickFlg = $objQuickHelper->getIsStockCardData($arrParam);
		            if($quickFlg == false) {
		            	if ($_POST['quick_pay'] == 1) {
		            		$arrErr['quick_pay'] = "クイック決済サービスの利用許可がありません。<br />";
		            	}
		            }
                }
            }
        }
        return $arrErr;
    }

    /**
     * 最初の状態の決済種別を取得する
     * @return $first_settlement_division 最初の状態の決済種別
     */
    function getSettlementDivision() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrRet = $objQuery->select("sub_data", "dtb_module", "module_code = 'mdl_paygent'");
        $arrSubData = unserialize($arrRet[0]['sub_data']);
        $first_settlement_division = $arrSubData['settlement_division'];

        return $first_settlement_division;
    }

    /**
     * 設定を保存
     */
    function setConfig() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $sqlval = array();
        $arrConfig = $this->objFormParam->getHashArray();
        $sqlval['sub_data'] = serialize($arrConfig);
        $objQuery->update("dtb_module", $sqlval, "module_code = ?", array($this->module_name));
    }

    /**
     * 設定を取得
     */
    function getConfig() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrRet = $objQuery->select("sub_data", "dtb_module", "module_code = ?", array($this->module_name));
        $arrConfig = unserialize($arrRet[0]['sub_data']);
        return $arrConfig;
    }

    /**
     * 支払方法DBからデータを取得
     */
    function getPaymentDB($type){
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrRet = array();
        $sql = "SELECT module_code
                FROM dtb_payment WHERE module_code = ? AND memo03 = ?";
        $arrRet = $objQuery->getall($sql, array($this->module_name, $type));
        return $arrRet;
    }

    /**
     * 支払方法の更新処理
     */
    function setPaymentDB($settlement_division) {
    	$objQuery =& SC_Query_Ex::getSingletonInstance();

        // 関連する支払方法の削除フラグを立てる
        $arrDel = array('del_flg' => "1");
        $objQuery->update("dtb_payment", $arrDel, " module_code = ?", array($this->module_name));

        // モジュール型
        if ($settlement_division == SETTLEMENT_MODULE && count($_POST["payment"]) > 0) {
            // データ登録
            foreach ($_POST["payment"] as $key => $val) {
                // クレジット登録
                if ($val == PAY_PAYGENT_CREDIT) {
                    $arrParam = array();
                    $arrParam['security_code'] = $this->objFormParam->getValue('security_code');
                    $arrParam['credit_3d'] = $this->objFormParam->getValue('credit_3d');
                    $arrParam['stock_card'] = $this->objFormParam->getValue('stock_card');
                    $arrParam['payment_division'] = $this->objFormParam->getValue('payment_division');
                    $arrParam['quick_pay'] = $this->objFormParam->getValue('quick_pay');
                    $arrParam['token_pay'] = $this->objFormParam->getValue('token_pay');
                    $arrParam['token_env'] = $this->objFormParam->getValue('token_env');
                    $arrParam['token_key'] = $this->objFormParam->getValue('token_key');
                    $arrData = array(
                        "payment_method" => "ペイジェント クレジット"
                        ,"upper_rule" => ""
                        ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_credit.php"
                        ,"charge_flg" => "2"
                        ,"upper_rule_max" => ""
                    );
                }

                // コンビニ登録(番号方式)
                if ($val == PAY_PAYGENT_CONVENI_NUM) {
                    $arrParam = array();
                    $arrParam['payment_limit_date'] = $this->objFormParam->getValue('conveni_limit_date_num');
                    $arrData = array(
                        "payment_method" => "ペイジェント コンビニ(番号方式)"
                        ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_conveni.php"
                        ,"charge_flg" => "1"
                    );
                }
/*
 *                // コンビニ登録(払込票方式)
 *                if ($val == PAY_PAYGENT_CONVENI_CALL) {
 *                    $arrParam = array();
 *                    $arrParam['payment_limit_date'] = $this->objFormParam->getValue('conveni_limit_date_call');
 *                    $arrParam['bill_expiration_date'] = $this->objFormParam->getValue('conveni_valid_limit_date_call');
 *                    $arrParam['site_info'] = $this->objFormParam->getValue('conveni_free_memo_call');
 *                    $arrData = array(
 *                        "payment_method" => "PAYGENTコンビニ(払込票方式)"
 *                        ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_conveni_call.php"
 *                        ,"charge_flg" => "1"
 *                    );
 *                }
 */
                // ATM決済登録
                if ($val == PAY_PAYGENT_ATM) {
                    $arrParam = array();
                    $arrParam['payment_detail'] = $this->objFormParam->getValue('payment_detail');
                    $arrParam['payment_limit_date'] = $this->objFormParam->getValue('atm_limit_date');
                    $arrData = array(
                        "payment_method" => "ペイジェント ATM決済"
                        ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_atm.php"
                        ,"charge_flg" => "1"
                    );
                }

                // 銀行NET登録
                if ($val == PAY_PAYGENT_BANK) {
                    $arrParam = array();
                    $arrParam['claim_kana'] = $this->objFormParam->getValue('claim_kana');
                    $arrParam['claim_kanji'] = $this->objFormParam->getValue('claim_kanji');
                    $arrParam['asp_payment_term'] = $this->objFormParam->getValue('asp_payment_term');
                    $arrParam['copy_right'] = $this->objFormParam->getValue('copy_right');
                    $arrParam['free_memo'] = $this->objFormParam->getValue('free_memo');
                    $arrData = array(
                        "payment_method" => "ペイジェント 銀行ネット"
                        ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_bank.php"
                        ,"charge_flg" => "1"
                    );
                }

                // 携帯キャリア決済
                if ($val == PAY_PAYGENT_CAREER) {
                    $arrParam = array();
                    $arrParam['career_division'] = $this->objFormParam->getValue('career_division');
                    $arrData = array(
                        "payment_method" => "ペイジェント 携帯キャリア"
                        //,"rule" => ""
                        ,"rule_min" => ""
                        ,"upper_rule" => ""
                        ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_career.php"
                        ,"charge_flg" => "1"
                        ,"upper_rule_max" => ""
                    );
                }
                // 電子マネー決済
                if ($val == PAY_PAYGENT_EMONEY) {
                	$arrParam = array();
                	$arrParam['emoney_division'] = $this->objFormParam->getValue('emoney_division');
                	$arrData = array(
                        "payment_method" => "ペイジェント 電子マネー"
                        //,"rule" => ""
                        ,"rule_min" => ""
                        ,"upper_rule" => ""
                        ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_emoney.php"
                        ,"charge_flg" => "1"
                        ,"upper_rule_max" => ""
                    );
                }
                // Yahoo!ウォレット決済
                if ($val == PAY_PAYGENT_YAHOOWALLET) {
                	$arrParam = array();
                	$arrData = array(
                        "payment_method" => "ペイジェント Yahoo!ウォレット"
                        ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_yahoowallet.php"
                        ,"charge_flg" => "1"
                    );
                }
                // 仮想口座決済
                if ($val == PAY_PAYGENT_VIRTUAL_ACCOUNT) {
                    $arrParam = array();
                    $arrParam['numbering_type'] = $this->objFormParam->getValue('numbering_type');
                    $arrParam['payment_limit_date'] = $this->objFormParam->getValue('virtual_account_limit_date');
                    $arrData = array(
                        "payment_method" => "ペイジェント 銀行振込",
                        "module_path" => MODULE_REALDIR . "mdl_paygent/paygent_virtual_account.php",
                        "charge_flg" => "1",
                    );
                }
                // 後払い決済
                if ($val == PAY_PAYGENT_LATER_PAYMENT) {
                    $arrParam = array();
                    $arrParam['result_get_type'] = $this->objFormParam->getValue('result_get_type');
                    $arrParam['exam_result_notification_type'] = $this->objFormParam->getValue('exam_result_notification_type');
                    $arrParam['invoice_include'] = $this->objFormParam->getValue('invoice_include');
                    $arrData = array(
                        "payment_method" => "ペイジェント 後払い（コンビニ・銀行）",
                        "module_path" => MODULE_REALDIR . "mdl_paygent/paygent_later_payment.php",
                        "charge_flg" => "1",
                    );
                }
                // Paidy
                if ($val == PAY_PAYGENT_PAIDY) {
                    $arrParam = array();
                    $arrParam['api_key'] = $this->objFormParam->getValue('api_key');
                    $arrParam['logo_url'] = $this->objFormParam->getValue('logo_url');
                    $arrParam['paidy_store_name'] = $this->objFormParam->getValue('paidy_store_name');
                    $arrData = array(
                        "payment_method" => "ペイジェント Paidy翌月払い（コンビニ/銀行）"
                        ,"upper_rule" => ""
                        ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_paidy.php"
                        ,"charge_flg" => "1"
                        ,"upper_rule_max" => ""
                    );
                }

                // データ更新
                $this->setPaymentDB_sub($arrData, $arrParam, $val);
            }

        // リンク型/混合型
        } elseif ($settlement_division != SETTLEMENT_MODULE) {
            $arrParam = array();
            $arrParam['link_url'] = $this->objFormParam->getValue('link_url');
            $arrParam['hash_key'] = $this->objFormParam->getValue('hash_key');
            $arrParam['payment_term_day'] = $this->objFormParam->getValue('link_payment_term');
            $arrParam['merchant_name'] = $this->objFormParam->getValue('merchant_name');
            $arrParam['free_memo'] = $this->objFormParam->getValue('link_free_memo');
            $arrParam['copy_right'] = $this->objFormParam->getValue('link_copy_right');
            $arrParam['payment_class'] = $this->objFormParam->getValue('card_class');
            $arrParam['use_card_conf_number'] = $this->objFormParam->getValue('card_conf');
            $arrParam['result_get_type'] = $this->objFormParam->getValue('link_result_get_type');
            $arrParam['auto_cancel_type'] = $this->objFormParam->getValue('link_auto_cancel_type');
            $arrData = array(
                "payment_method" => "PAYGENT決済"
                ,"module_path" => MODULE_REALDIR . "mdl_paygent/paygent_link.php"
                ,"charge_flg" => "1"
            );
            $this->setPaymentDB_sub($arrData, $arrParam, PAY_PAYGENT_LINK);
        }
    }

    /**
     * 支払方法の更新処理（共通処理）
     */
    function setPaymentDB_sub($arrData, $arrParam, $val) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrData['fix'] = 3;
        $arrData['creator_id'] = $_SESSION['member_id'];
        $arrData['create_date'] = "now()";
        $arrData['update_date'] = "now()";
        $arrData['module_code'] = $this->module_name;
        $arrData['memo01'] = $this->objFormParam->getValue("merchant_id");
        $arrData['memo02'] = $this->objFormParam->getValue("connect_id");
        $arrData['memo03'] = $val;
        $arrData['memo04'] = $this->objFormParam->getValue("connect_password");
        $arrData['memo05'] = serialize($arrParam);
        $arrData['del_flg'] = "0";
        $arrData['charge']  = "0";

        // 支払方法データを取得
        $arrPayment = $this->getPaymentDB($val);
        // 支払方法データが存在すればUPDATE
        if (count($arrPayment) > 0) {
            $objQuery->update("dtb_payment", $arrData, "module_code = ? AND memo03 = ?", array($this->module_name, $val));
        // 支払方法データが無ければINSERT
        } else {
            // ランクの最大値を取得
            $max_rank = $objQuery->getOne("SELECT max(rank) FROM dtb_payment");
            $arrData["rank"] = $max_rank + 1;
            $arrData['payment_id'] = $objQuery->nextVal('dtb_payment_payment_id');
            $objQuery->insert("dtb_payment", $arrData);
        }
    }

    /**
     * テーブルを更新
     */
    function updateTable(){
        $objDB = new SC_Helper_DB_Ex();
        $objDB->sfColumnExists("dtb_payment", "module_code", "text", "", true);
        $objDB->sfColumnExists("dtb_customer", "paygent_card", "int2", "", true);
        $objDB->sfColumnExists("dtb_customer", "virtual_account_bank_code", "text", "", true);
        $objDB->sfColumnExists("dtb_customer", "virtual_account_branch_code", "text", "", true);
        $objDB->sfColumnExists("dtb_customer", "virtual_account_number", "text", "", true);

        $objDB->sfColumnExists("dtb_order", "quick_flg", "int2", "", true);
        $objDB->sfColumnExists("dtb_order", "quick_memo", "text", "", true);
        $objDB->sfColumnExists("dtb_order", "invoice_send_type", "int2", "", true);
    }

    /**
     * ファイルを更新
     */
    function updateFile(){
        foreach ($this->arrUpdateFile as $array) {
            $dst_file = $array['dst'];
            $src_file = $array['src'];
            // ファイルが異なる場合
            if (!file_exists($dst_file) || sha1_file($src_file) != sha1_file($dst_file)) {
                SC_Utils_Ex::sfMakeDir($dst_file);
                if (is_writable($dst_file) || is_writable(dirname($dst_file))) {
                    copy($src_file, $dst_file);
                } else {
                    return false;
                }
            }
        }
        return true;
    }
}
?>
