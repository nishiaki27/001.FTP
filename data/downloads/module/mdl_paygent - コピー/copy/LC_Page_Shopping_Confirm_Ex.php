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
require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_Confirm.php';

require_once MODULE_REALDIR . "mdl_paygent/SC_Mdl_Quick_Helper.php";
require_once MODULE_REALDIR . "mdl_paygent/LC_Page_Mdl_Paygent_Helper.php";

/**
 * 入力内容確認 のページクラス(拡張).
 *
 * LC_Page_Shopping_Confirm をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Shopping_Confirm_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Shopping_Confirm_Ex extends LC_Page_Shopping_Confirm {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = SC_Mdl_Quick_Helper::sfChangePaymentTemplate('quick_shopping_confirm.tpl', $this->tpl_mainpage);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->paygentAction();
        $this->sendResponse();
    }

    /**
     * paygentAction
     *
     * @return void
     */
    function paygentAction() {
        $objQuery = new SC_Query_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objQuickHelper = new SC_Mdl_Quick_Helper();

        // ペイジェントモジュール注文ID退避領域をクリア
        unset($_SESSION['paygent_order_id']);

        // クイック決済機能要・不要チェック
        if ($objQuickHelper->getIsQuickFunction() == false) {
            $this->quick_Flg = "0";
            return;
        }

        // セッションからクイック決済情報をクリア
        $objQuickHelper->clearQuickPayInfo();

        // 前回の受注を取得
        $arrOrder = $objQuickHelper->getMaxOrder($objCustomer->getValue('customer_id'));

        // 今回の受注を取得
        $arrOrderTmp = $objQuickHelper->getOrderTmp($this->tpl_uniqid);

        // 前回と今回のpayment_idが一致するかチェック
        if($arrOrder['payment_id'] == $arrOrderTmp['payment_id']
            && $arrOrder['quick_flg'] == "1") {

            $this->quick_Flg = "1";
            $this->memo03 = $arrOrderTmp['memo03'];
            $this->quick_memo = unserialize($arrOrder['quick_memo']);

            switch($this->memo03) {
            // 前のページに戻る
            case PAY_PAYGENT_CREDIT:
                $arrStockCarrdData = $objQuickHelper->getStockCardData($arrOrder['order_id'], $this->quick_memo['CardSeq']);
                if($arrStockCarrdData['result'] == "0") {
                    $this->quick_Flg = "0";
                } else {
                    $this->card_info = $arrStockCarrdData['card_info'];
                    // 支払回数取得
                    $objPaygentHelper = new LC_Page_Mdl_Paygent_Helper();
                    $arrPaymentClass = $objPaygentHelper->getPaymentClass();
                    if (SC_Utils_Ex::isBlank($this->quick_memo['split_count'])) {
                        $this->paymentDivision = $arrPaymentClass[$this->quick_memo['payment_class']];
                    } else {
                        $this->paymentDivision = $arrPaymentClass[$this->quick_memo['payment_class'] . "-" . $this->quick_memo['split_count']];
                    }
                    if ($this->paymentDivision == "") {
                    	$this->quick_Flg = "0";
                    	break;
                    }
                    // セキュリティコード
                    $objConfig = new LC_Page_Mdl_Paygent_Config();
                    $arrConfig = $objConfig->getConfig();
                    // セキュリティコード入力要・不要チェック
                    if ($arrConfig['security_code'] == 1) {
                        $this->security_code_flg = 1;
                        $this->security_code = "";

                        $this->merchant_id = $arrConfig['merchant_id'];
                        $this->token_pay = $arrConfig['token_pay'];
                        $this->token_key = $arrConfig['token_key'];
                        $this->paygent_token_connect_url = PAYGENT_TOKEN_CONNECT_URL;

                        if ($arrConfig['token_env'] === "1") {
                            $this->paygent_token_js_url = PAYGENT_TOKEN_JS_URL_LIVE;
                        } else {
                            $this->paygent_token_js_url = PAYGENT_TOKEN_JS_URL_SANDBOX;
                        }

                        $this->token_js = file_get_contents(PATH_JS_TOKEN);
                    }
                }
                break;
            case PAY_PAYGENT_CONVENI_NUM:
                $arrConvenience = getConvenience();
                $this->convenience = $arrConvenience[$this->quick_memo['cvs_company_id']];
                break;
            case PAY_PAYGENT_ATM:
            case PAY_PAYGENT_BANK:
                // 直接セット
                break;
            case PAY_PAYGENT_EMONEY:
            	$arrEmoney = getEmoney();
            	$this->emoney = $arrEmoney[$this->quick_memo['emoney_type']];
                break;
            case PAY_PAYGENT_LATER_PAYMENT:
                $this->quick_Flg = "0";
                break;
            }

            switch($this->getMode()) {
            // クイック決済を行う
            case 'quick':
                // セッションへクイック決済情報を保存
                $this->security_code = $_POST['security_code'];;
                $quick_pay_info['quick_flg'] = "1";
                $quick_pay_info['security_code_flg'] = $this->security_code_flg;
                $quick_pay_info['security_code'] = $this->security_code;
                $quick_pay_info['quick_memo'] = $this->quick_memo;
                $quick_pay_info['card_token'] = $_POST['card_token'];
                $objQuickHelper->saveQuickPayInfo($quick_pay_info);

                /*
                 * 決済モジュールで必要なため, 受注番号を取得
                 */
                $this->arrForm["order_id"] = $objQuery->nextval("dtb_order_order_id");
                $_SESSION["order_id"] = $this->arrForm['order_id'];

                // 集計結果を受注一時テーブルに反映
                $objPurchase->saveOrderTemp($this->tpl_uniqid, $this->arrForm,
                                            $objCustomer);

                // 正常に登録されたことを記録しておく
                $objSiteSess->setRegistFlag();

                // 決済モジュールを使用する場合
                if ($this->use_module) {
                    $objPurchase->completeOrder(ORDER_PENDING);
                    SC_Response_Ex::sendRedirect(SHOPPING_MODULE_URLPATH);
                }
                exit;
                break;
            }
        }
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
}
?>
