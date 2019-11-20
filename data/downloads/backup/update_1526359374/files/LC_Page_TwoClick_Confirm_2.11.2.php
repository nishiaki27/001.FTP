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

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
require_once CLASS_EX_REALDIR . 'page_extends/shopping/LC_Page_Shopping_Deliv_Ex.php';
require_once CLASS_EX_REALDIR . 'page_extends/shopping/LC_Page_Shopping_Payment_Ex.php';
require_once MODULE_REALDIR . 'mdl_pg_mulpay/class/LC_Mdl_PG_MULPAY.php';
require_once MODULE_REALDIR . 'mdl_pg_mulpay/class/utils/LC_Mdl_PG_MULPAY_Export.php';

/**
 * 入力内容確認のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_TwoClick_Confirm_2.11.2.php 3978 2012-03-18 18:26:08Z takashi $
 */
class LC_Page_TwoClick_Confirm extends LC_Page_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_title = "ご入力内容のご確認";
        $masterData = new SC_DB_MasterData();
        $this->arrPref = $masterData->getMasterData('mtb_pref');
        $this->arrSex = $masterData->getMasterData("mtb_sex");
        $this->arrMAILMAGATYPE = $masterData->getMasterData("mtb_mail_magazine_type");
        $this->arrReminder = $masterData->getMasterData("mtb_reminder");
        $this->arrDeliv = SC_Helper_DB_Ex::sfGetIDValueList("dtb_deliv", "deliv_id", "service_name");
        $this->httpCacheControl('nocache');

        $this->tpl_mainpage = 'twoClick/confirm.tpl';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objQuery = new SC_Query_Ex();
        $objDb = new SC_Helper_DB_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objFormParam = new SC_FormParam_Ex();

        $this->is_multiple = $objPurchase->isMultiple();

        // 前のページで正しく登録手続きが行われた記録があるか判定
        /*
        if (!$objSiteSess->isPrePage()) {
            SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, $objSiteSess);
        }
        */

        // ユーザユニークIDの取得と購入状態の正当性をチェック
        $this->tpl_uniqid = $objSiteSess->getUniqId();
        $objPurchase->verifyChangeCart($this->tpl_uniqid, $objCartSess);

        $this->cartKey = $objCartSess->getKey();

        // カート内商品のチェック
        $this->tpl_message = $objCartSess->checkProducts($this->cartKey);
        if (!SC_Utils_Ex::isBlank($this->tpl_message)) {
            SC_Response_Ex::sendRedirect(CART_URLPATH);
            exit;
        }

        // 2クリック決済情報を設定
        if ($this->getMode() != 'confirm') {
            $this->setup2ClickPurchase($objPurchase, $objCustomer, $objCartSess, $objQuery, $this->tpl_uniqid, $this->cartKey);
        }

        // カートの商品を取得
        $this->arrShipping = $objPurchase->getShippingTemp($this->is_multiple);
        $this->arrCartItems = $objCartSess->getCartList($this->cartKey);
        // 合計金額
        $this->tpl_total_inctax[$this->cartKey] = $objCartSess->getAllProductsTotal($this->cartKey);
        // 税額
        $this->tpl_total_tax[$this->cartKey] = $objCartSess->getAllProductsTax($this->cartKey);
        // ポイント合計
        $this->tpl_total_point[$this->cartKey] = $objCartSess->getAllProductsPoint($this->cartKey);

        // 一時受注テーブルの読込
        $arrOrderTemp = $objPurchase->getOrderTemp($this->tpl_uniqid);

        // カート集計を元に最終計算
        $arrCalcResults = $objCartSess->calculate($this->cartKey, $objCustomer,
                                                  $arrOrderTemp['use_point'],
                                                  $objPurchase->getShippingPref($this->is_multiple),
                                                  $arrOrderTemp['charge'],
                                                  $arrOrderTemp['discount'],
                                                  $arrOrderTemp['deliv_id']);
        $this->arrForm = array_merge($arrOrderTemp, $arrCalcResults);

        // 会員ログインチェック
        if($objCustomer->isLoginSuccess(true)) {
            $this->tpl_login = '1';
            $this->tpl_user_point = $objCustomer->getValue('point');
        }

        // 決済モジュールを使用するかどうか
        $this->use_module = $this->useModule($this->arrForm['payment_id']);

        $this->arrDelivTime = $objPurchase->getDelivTime($this->arrForm['deliv_id']);


        switch($this->getMode()) {
        // 前のページに戻る
        case 'return':
            // 正常な推移であることを記録しておく
            $objSiteSess->setRegistFlag();
            SC_Response_Ex::sendRedirect(CART_URLPATH);
            exit;
            break;
        case 'confirm':

            // パラメータ情報の初期化
            $this->setFormParams($objFormParam, $_POST, false, $this->arrShipping);

            $this->arrErr = $objFormParam->checkError();
            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                $this->arrForm = array_merge($this->arrForm, $objFormParam->getDbArray());
                $this->saveShippings($this->arrForm, $this->arrDelivTime);
            } else {
                // 確認ページを再表示
                break;
            }

            /*
             * 決済モジュールで必要なため, 受注番号を取得
             */
            $this->arrForm["order_id"] = $objQuery->nextval("dtb_order_order_id");

            // 集計結果を受注一時テーブルに反映
            $objPurchase->saveOrderTemp($this->tpl_uniqid, $this->arrForm,
                                        $objCustomer);

            // 正常に登録されたことを記録しておく
            $objSiteSess->setRegistFlag();

            // 決済モジュールを使用する場合
            if ($this->use_module) {
                $_SESSION["order_id"] = $this->arrForm['order_id'];
                $objPurchase->completeOrder(ORDER_PENDING);
                LC_Mdl_PG_MULPAY::printLogU("goto module: 2click order_id= ".$_SESSION["order_id"], $this->tpl_uniqid);

                // 2click決済実行フラグを立てる。
                $_SESSION['mdl_pg_mulpay']['2click'] = 'charge';
                SC_Response_Ex::sendRedirect(SHOPPING_MODULE_URLPATH);
            }
            // 購入完了ページ
            else {
                $objPurchase->completeOrder(ORDER_NEW);
                $objPurchase->sendOrderMail($this->arrForm["order_id"]);
                SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
            }
            exit;
            break;
        default:

            break;
        }

        // 決済情報の表示文字列作成
        $this->show2ClickPurchase($this->arrForm);

        // お届け時間の選択肢を準備する。
        $this->arrDelivDate = $objPurchase->getDelivDate($objCartSess, $objCartSess->getKey());
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
     * 決済モジュールを使用するかどうか.
     *
     * dtb_payment.memo03 に値が入っている場合は決済モジュールと見なす.
     *
     * @param integer $payment_id 支払い方法ID
     * @return boolean 決済モジュールを使用する支払い方法の場合 true
     */
    function useModule($payment_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $memo03 = $objQuery->get('memo03', 'dtb_payment', 'payment_id = ?',
                                 array($payment_id));
        return !SC_Utils_Ex::isBlank($memo03);
    }

    // XXX 追加

    function registerDeliv($deliv_check, $uniqid, &$objPurchase, &$objCustomer) {
        $arrValues = array();
        // 会員登録住所がチェックされている場合
        if ($deliv_check == '-1') {
            $objPurchase->copyFromCustomer($arrValues, $objCustomer, 'shipping');
            $objPurchase->saveShippingTemp($arrValues);
            $objPurchase->saveOrderTemp($uniqid, $arrValues, $objCustomer);
            return true;
        }
        // 別のお届け先がチェックされている場合
        elseif ($deliv_check >= 1) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $arrOtherDeliv = $objQuery->getRow("*", "dtb_other_deliv",
                                               "customer_id = ? AND other_deliv_id = ?",
                                               array($objCustomer->getValue('customer_id'), $deliv_check));
            if (SC_Utils_Ex::isBlank($arrOtherDeliv)) {
                return false;
            }

            $objPurchase->copyFromOrder($arrValues, $arrOtherDeliv, 'shipping', '');;
            $objPurchase->saveShippingTemp($arrValues);
            $objPurchase->saveOrderTemp($uniqid, $arrValues, $objCustomer);
            return true;
        }
        // お届け先チェックが不正な場合
        else {
            return false;
        }
    }

    /**
     * 2クリック決済情報の表示文字列を作成する。
     */
    function show2ClickPurchase(&$arrData) {
        $memo06 = !empty($arrData['memo06']) ? unserialize($arrData['memo06']) : '';

        LC_Mdl_PG_MULPAY::printLogU('show2ClickPurchase memo06='.print_r($memo06,true), $this->tpl_uniqid);

        if (LC_Mdl_PG_MULPAY_Export::isGmoCreditPaymentId($arrData['payment_id'])) {
            if (is_array($memo06['user_confirm'])) {
                $user_confirm = $memo06['user_confirm'];

                $arrData['user_confirm_CardNo'] = $user_confirm['CardNo'];
                $arrData['user_confirm_HolderName'] = $user_confirm['HolderName'];
                $arrData['user_confirm_Expire'] = $user_confirm['Expire'];
                $arrData['user_confirm_paymethod'] = $user_confirm['paymethod'];
            }
        } else if (is_string($memo06['user_confirm'])) {
            $arrData['user_confirm'] = $memo06['user_confirm'];
        }
    }

    function setup2ClickPurchase(&$objPurchase, &$objCustomer, &$objCartSess, &$objQuery, $uniqid, $product_type_id) {
        LC_Mdl_PG_MULPAY::printLogU("setup2ClickPurchase product_type_id=$product_type_id", $uniqid);

        $cartKey = $objCartSess->getKey();

        if ($cartKey == PRODUCT_TYPE_DOWNLOAD) {
            $objPurchase->saveOrderTemp($uniqid, array(), $objCustomer);
        } else
        if (! $objPurchase->getShippingTemp($this->is_multiple)) {
            // デフォルトの配送先として会員住所を設定する。
            LC_Mdl_PG_MULPAY::printLogU("set default delivery address.", $uniqid);

            LC_Page_Shopping_Deliv_Ex::registerDeliv('-1', $uniqid, $objPurchase, $objCustomer);
        }
     
        /*
         * 仮受注情報の一貫性をチェックする。
         */
        $arrOrderTemp = $objPurchase->getOrderTemp($uniqid);
        list($deliv_id, $arrPayment) = $this->check2ClickPurchase($arrOrderTemp['payment_id'],
                                                                  $arrOrderTemp['memo06'],
                                                                  $arrOrderTemp['deliv_id'],
                                                                  $product_type_id,
                                                                  $objPurchase, $objCartSess, $objQuery,
                                                                  $uniqid);
        if ($arrPayment && $deliv_id == $arrOrderTemp['deliv_id']) {
            LC_Mdl_PG_MULPAY::printLogU("check2ClickPayment: ok valid payment:".print_r($arrPayment,true), $uniqid);
            return;
        }

        /*
         * 最新の受注情報を取得する。
         */
        $customer_id = $objCustomer->getValue('customer_id');

        // 更新情報
        $arr2Click = array('use_point' => 0);

        $sql = new SC_Query_Ex();
        $sql->setOrder('order_id DESC');
        $sql->setLimit(1);
        $lastOrder = $sql->getRow('*', 'dtb_order',
                                  'customer_id = ? and status <> ? and del_flg = 0',
                                  array($customer_id, ORDER_PENDING));
        if (!$lastOrder) {
            // 受注情報が存在しない、かつ、前段のチェックで無効な
            // レコードなので、初期化を行う。

            LC_Mdl_PG_MULPAY::printLogU("lastOrder not found, clear payment info.", $uniqid);

            $arr2Click['deliv_id'] = NULL;
            $arr2Click['payment_id'] = NULL;
            $arr2Click['payment_method'] = NULL;
            $arr2Click['charge'] = 0;
            $arr2Click['memo06'] = NULL;

            $arr2Click['update_date'] = 'Now()';
            $objPurchase->saveOrderTemp($uniqid, $arr2Click, $objCustomer);
            return;
        }

        LC_Mdl_PG_MULPAY::printLogU("lastOrder found: ".print_r($lastOrder,true), $uniqid);

        // 受注アドレス(order_*)は引き継がない。
        // dtb_order_tempレコード作成時に現在の会員住所が設定されている。

        // 一貫性をチェック
        list($deliv_id, $arrPayment) = $this->check2ClickPurchase($lastOrder['payment_id'],
                                                                  $lastOrder['memo06'],
                                                                  $lastOrder['deliv_id'],
                                                                  $product_type_id,
                                                                  $objPurchase, $objCartSess, $objQuery,
                                                                  $uniqid);
        if ($arrPayment) {
            // 配送方法を引き継ぐ
            // 2クリックでは複数宛先を設定しない。
            // deliv_feeは後のSC_CartSession::calculateで計算される。

            if ($cartKey != PRODUCT_TYPE_DOWNLOAD) {
                LC_Page_Shopping_Deliv_Ex::registerDeliv($deliv_id, $uniqid, $objPurchase, $objCustomer);
            }
            $arr2Click['deliv_id'] = $deliv_id;
            // 支払い方法を引き継ぐ
            $arr2Click['payment_id'] = $lastOrder['payment_id'];
            $arr2Click['memo06'] = $lastOrder['memo06'];
            // payment_method, chargeは最新の値を使用する。
            $arr2Click['payment_method'] = $arrPayment['payment_method'];
            $arr2Click['charge'] = $arrPayment['charge'];

            LC_Mdl_PG_MULPAY::printLogU("set payment info: ".print_r($arr2Click,true), $uniqid);
        } else {
            // 確実にクリアする
            LC_Mdl_PG_MULPAY::printLogU("clear payment info", $uniqid);

            $arr2Click['deliv_id'] = NULL;
            $arr2Click['payment_id'] = NULL;
            $arr2Click['payment_method'] = NULL;
            $arr2Click['charge'] = 0;
            $arr2Click['memo06'] = NULL;
        }

        $arr2Click['update_date'] = 'Now()';
        $objPurchase->saveOrderTemp($uniqid, $arr2Click, $objCustomer);
    }

    /**
     * パラメータの初期化を行い, 初期値を設定する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param array $arrParam 設定する値の配列
     * @param boolean $deliv_only deliv_id チェックのみの場合 true
     * @param array $arrShipping 配送先情報の配列
     */
    function setFormParams(&$objFormParam, $arrParam, $deliv_only, &$arrShipping) {
        $this->lfInitParam($objFormParam, $deliv_only, $arrShipping);
        $objFormParam->setParam($arrParam);
        $objFormParam->convParam();
    }

    /**
     * パラメータ情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param boolean $deliv_only deliv_id チェックのみの場合 true
     * @param array $arrShipping 配送先情報の配列
     * @return void
     */
    function lfInitParam(&$objFormParam, $deliv_only, &$arrShipping) {
        $objFormParam->addParam("その他お問い合わせ", 'message', LTEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        
        $objFormParam->addParam("配送業者", "deliv_id", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("お支払い方法", "payment_id", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));

        if (!$deliv_only) {
            foreach ($arrShipping as $val) {
                $objFormParam->addParam("お届け時間", "deliv_time_id" . $val['shipping_id'], INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
                $objFormParam->addParam("お届け日", "deliv_date" . $val['shipping_id'], STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
            }
        }
    }

    /**
     * 配送情報を保存する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param array $arrDelivTime 配送時間の配列
     */
    function saveShippings(&$arrForm, $arrDelivTime) {
        $deliv_id = $arrForm['deliv_id'];

        /* TODO
         * SC_Purchase::getShippingTemp() で取得して,
         * リファレンスで代入すると, セッションに添字を追加できない？
         */
        foreach (array_keys($_SESSION['shipping']) as $key) {
            $shipping_id = $_SESSION['shipping'][$key]['shipping_id'];
            $time_id = $arrForm['deliv_time_id' . $shipping_id];
            $_SESSION['shipping'][$key]['deliv_id'] = $deliv_id;
            $_SESSION['shipping'][$key]['time_id'] = $time_id;
            $_SESSION['shipping'][$key]['shipping_time'] = $arrDelivTime[$time_id];
            $_SESSION['shipping'][$key]['shipping_date'] = $arrForm['deliv_date' . $shipping_id];
        }
    }


    /**
     * 2クリック決済情報に矛盾がないか検査する
     */
    function check2ClickPurchase($payment_id, $memo06, $deliv_id, $product_type_id,
                                 &$objPurchase, &$objCartSess, &$objQuery, $uniqid)
    {
        /*
         * 現在の商品種別と配送業者の一貫性をチェック
         */
        $deliv = $objQuery->getRow('*', 'dtb_deliv', 'deliv_id = ? and del_flg = 0', array($deliv_id));
        if (isset($deliv['product_type_id']) && $deliv['product_type_id'] != $product_type_id) {
            // 支払方法と商品種別を元に配送業者を選び直す。
            // 複数業者がマッチする場合は、選び直しにする。
            $arrDeliv = $objPurchase->getDeliv($product_type_id);
            $where = 'payment_id = ? AND deliv_id IN (' . implode(', ', array_pad(array(), count($arrDeliv), '?')) . ')';
            $arrParams = array($payment_id);
            foreach ($arrDeliv as $deliv) {
                $arrParams[] = $deliv['deliv_id'];
            }

            //LC_Mdl_PG_MULPAY::printLogU("where " . $where);
            //LC_Mdl_PG_MULPAY::printLogU("arrParams " . print_r($arrParams,true));

            $arrDelivIds = $objQuery->getCol('deliv_id', 'dtb_payment_options', $where, $arrParams);

            if (count($arrDelivIds) == 1) {
                LC_Mdl_PG_MULPAY::printLogU("change deliv_id: $deliv_id -> " . $arrDelivIds[0]);
                $deliv_id = $arrDelivIds[0];
            } else {
                LC_Mdl_PG_MULPAY::printLogU("can't find delive_id for this payment_id: $payment_id, arrDelivIds:".
                                            print_r($arrDelivIds,true));
                return false;
            }
        }

        /*
         * 現在選択されている支払方法($payment_id)が、有効な支払方法であるかチェックする。
         * see LC_Page_Shopping_Payment::getSelectedDeliv
         * 小計に対してチェックを行う。
         */
        $total = $objCartSess->getAllProductsTotal($objCartSess->getKey());
        $arrPayments = $objPurchase->getPaymentsByPrice($total, $deliv_id);

        $foundPayment = false;
        foreach ($arrPayments as $arrPayment) {
            if ($arrPayment['payment_id'] == $payment_id) {
                $foundPayment = $arrPayment;
                break;
            }
        }
        if (! $foundPayment) {
            return false;
        }

        /*
         * $payment_id, $memo06 に矛盾がないかチェックする。
         */

        if (!empty($memo06))
            $memo06 = unserialize($memo06);
        $memo06_id = is_array($memo06) ? $memo06['payment_id'] : '';

        $paycode = LC_Mdl_PG_MULPAY_Export::getGmoPaymentCode($payment_id);
        switch ($paycode) {
        case MDL_PG_MULPAY_PAYMENT_CREDIT:
        case MDL_PG_MULPAY_PAYMENT_CONVENI:
        case MDL_PG_MULPAY_PAYMENT_SUICA:
        case MDL_PG_MULPAY_PAYMENT_EDY:
            // memo06に記録された支払い情報と矛盾がないかチェックする。
            if ($payment_id == $memo06_id) {
                LC_Mdl_PG_MULPAY::printLogU("check2ClickPurchase:A $payment_id:$memo06_id", $uniqid);
                return array($deliv_id, $foundPayment);
            }
            break;
        case MDL_PG_MULPAY_PAYMENT_PAYPAL:
        case MDL_PG_MULPAY_PAYMENT_ATM:
        case MDL_PG_MULPAY_PAYMENT_NETBANK:
        case MDL_PG_MULPAY_PAYMENT_NETID:
        case MDL_PG_MULPAY_PAYMENT_WEBMONEY:
        case MDL_PG_MULPAY_PAYMENT_AU:
        case MDL_PG_MULPAY_PAYMENT_NOT_MODULE: // モジュールでない決済
            // PayPal決済はモバイルで無効
            $isMobile = SC_MobileUserAgent::isMobile();
            if ($paycode == MDL_PG_MULPAY_PAYMENT_PAYPAL && $isMobile) {
                return false;
            }
            // iD決済はモバイルでのみ有効
            if ($paycode == MDL_PG_MULPAY_PAYMENT_NETID && !$isMobile) {
                return false;
            }

            // これらの支払いでは、memo06に情報は存在しないことをチェックする。
            if (empty($memo06)) {
                LC_Mdl_PG_MULPAY::printLogU("check2ClickPurchase:B $payment_id:$memo06_id", $uniqid);
                return array($deliv_id, $foundPayment);
            }
            break;
        default: // 他モジュールの決済。2クリックの決済手段として利用できない。
            LC_Mdl_PG_MULPAY::printLogU("check2ClickPurchase:C paycode=$paycode $payment_id:$memo06_id", $uniqid);
            break;
        }

        return false;
    }
}
?>
