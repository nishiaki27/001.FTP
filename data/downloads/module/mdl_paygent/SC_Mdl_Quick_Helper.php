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
require_once realpath(dirname( __FILE__)). '/LC_Page_Mdl_Paygent_Config.php';

class SC_Mdl_Quick_Helper {

    /**
     * コンストラクタ
     *
     * @return void
     */
    function SC_Mdl_Quick_Helper() {
    }

    /**
     * クイック購入可能な条件が全て揃っているかチェック
     *
     * 連想配列のキーに商品種別
     *
     * @param array cartItems カート情報
     * @param $customer_id 顧客ID
     * @return array クイック購入 1:使用可 0:不可配列
     */
    function sfCanQuickSettlement($cartItems, $customer_id) {
        $ret[PRODUCT_TYPE_NORMAL] = '0';
        $ret[PRODUCT_TYPE_DOWNLOAD] = '0';

        // クイック決済機能要・不要チェック、会員顧客のチェック
        if($this->getIsQuickFunction() == false || !(0 < $customer_id)) {
            return $ret;
        }

        // 過去受注との比較
        $arrOrder = $this->getMaxOrder($customer_id);
        $products_Class = $this->getProducts_Type_id($arrOrder['order_id']);
        if (!SC_Utils_Ex::isBlank($products_Class)) {
            $ret[PRODUCT_TYPE_NORMAL] = '1';

            if(!SC_Utils_Ex::isBlank($cartItems[PRODUCT_TYPE_DOWNLOAD]) && $products_Class['product_type_id'] == PRODUCT_TYPE_DOWNLOAD) {
                $ret[PRODUCT_TYPE_DOWNLOAD] = '1';
            }
        }
        return $ret;
    }

    /**
     * 指定した顧客IDの最新の受注を取得
     *
     * @param $customer_id 顧客ID
     * @return  array 顧客の最新受注
     */
    function getMaxOrder($customer_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "deliv_id, order_id, payment_id, charge, payment_method, quick_flg, quick_memo";
        $table = "dtb_order";
        $where = "order_id = (select max(order_id) from dtb_order where del_flg = '0' AND customer_id = ? AND status <> ?)";
        $vals = array($customer_id, ORDER_PENDING);

        $arrOrder = $objQuery->getRow($col, $table, $where, $vals);

        return $arrOrder;
    }

    /**
     * 指定した受注の商品種別を取得
     *
     * @param string order_id 受注番号
     * @return array 商品種別
     */
    function getProducts_Type_id($order_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "product_type_id";
        $table = "dtb_products_class";
        $where = "product_class_id = (select min(product_class_id) from dtb_order_detail where order_id = ?)";
        $vals = array($order_id);

        $products_Class = $objQuery->getRow($col, $table, $where, $vals);

        return $products_Class;
    }

    /**
     * クイック決済用の受注情報を作成する
     *
     * @param string uniqid ユニークキー
     * @param SC_Customer objCustomer ログイン中の SC_Customer インスタンス
     * @param SC_CartSess objCartSess SC_CartSess インスタンス
     * @param string cartKey カートキー
     * @return array 受注情報作成結果
     */
    function sfRegisterOrder($uniqid, &$objCustomer, &$objCartSess, $cartKey) {

        // 前回の受注情報を取得する
        $arrOrder = $this->getMaxOrder($objCustomer->getValue('customer_id'));

        // 前回の支払方法が利用できるか判定
        $arrPaymentCheck = $this->sfIsPaymentCheck($arrOrder, $objCartSess, $cartKey);

        // クイック決済用受注の作成
        $arrResult = $this->sfRegisterOrderCommit($uniqid, $objCustomer, $arrOrder, $cartKey, $arrPaymentCheck);

        // セッションへクイック関連情報を保存
        $quick_Info['quick_flg'] = $arrResult['quick_flg'];
        if($arrResult['quick_flg'] == "1") {
             $quick_Info['payment_id'] = $arrResult['payment_id'];
             $quick_Info['old_order_id'] = $arrResult['old_order_id'];
             if($arrResult['paygent_flg'] == true) {
                  $quick_Info['memo03'] = $arrResult['paygent_flg'];
             }
        }
        $this->saveQuickInfo($quick_Info);

        return $arrResult;
    }

    /**
     * 前回の支払方法が利用できるか判定
     *
     * @param array $arrPreOrder 前回の受注情報配列
     * @param SC_CartSess objCartSess SC_CartSess インスタンス
     * @param string cartKey カートキー
     * @return array 支払方法利用可否結果
     */
    function sfIsPaymentCheck($arrPreOrder, &$objCartSess, $cartKey) {
        $arrResult = array();

        // 前回の支払方法がペイジェントか
        $paygentFlg = false;
        // 前回の支払方法が今回使用可能か
        $paymentFlg = false;
        // 前回の支払方法と同じ種類のものが使用可能か
        $arrResult['ischeck'] = 1;

        // 前回の支払方法情報を取得
        $payment = $this->getPayment($arrPreOrder['payment_id']);

        if (SC_Utils_Ex::isBlank($payment)) {
            $arrResult['ischeck'] = -1;
            return $arrResult;
        }

        // 前回の支払方法がペイジェントかの判定
        if($payment['module_code'] == MDL_PAYGENT_CODE) {
            $paygentFlg = true;
            $arrResult['memo03'] = $payment['memo03'];
        }
        $arrResult['paygent_flg'] = $paygentFlg;

        // 今回利用可の名決済の取得
        $total = $objCartSess->getAllProductsTotal($cartKey);
        $arrPayment = $this->getPaymentsByPrice($total, $arrPreOrder['deliv_id']);

        // 前回の支払方法が今回使用可能かの判定
        foreach ($arrPayment as $data) {
            if($data['payment_id'] == $arrPreOrder['payment_id']) {
                $paymentFlg = true;
                break;
            }
        }

        if($paymentFlg == false) {
            $arrResult['ischeck'] = 0;
            if($paygentFlg == true) {
                // 前回の支払方法と同じ種類で、使用条件が異なる場合はそちらを使用
                foreach ($arrPayment as $data) {
                    if($payment['memo03'] == $data['memo03']) {
                        $arrResult['ischeck'] = 2;
                        $arrResult['charge'] = $data['charge'];
                        $arrResult['payment_id'] = $data['payment_id'];
                        $arrResult['payment_method'] = $data['payment_method'];
                        break;
                    }
                }
            }
        }
        return $arrResult;
    }

    /**
     * 決済方法を取得する
     *
     * @param int payment_id 決済ID
     * @return array 決済方法取得結果
     */
    function getPayment($payment_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "memo03, module_code, upper_rule, upper_rule";
        $table = "dtb_payment";
        $where = "payment_id = ?";
        $vals = array($payment_id);

        $payment = $objQuery->getRow($col, $table, $where, $vals);

        return $payment;
    }

    /**
     * クイック決済用受注の作成
     *
     * 前回と今回の受注で商品種別が違う場合、
     * 購入フロー・支払方法の関係で処理を変える。
     * 通常-通常                 確認画面
     * 通常-ダウンロード         支払方法選択画面
     * ダウンロード-通常         支払方法選択画面
     * ダウンロード-ダウンロード 確認画面
     *
     * @param string uniqid ユニークキー
     * @param SC_Customer objCustomer ログイン中の SC_Customer インスタンス
     * @param array arrOrder 前回の受注情報配列
     * @param string cartKey カートキー
     * @param array arrPaymentCheck 支払方法利用可否結果
     * @return array 受注作成結果
     */
    function sfRegisterOrderCommit($uniqid, &$objCustomer, $arrOrder, $cartKey, $arrPaymentCheck) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrResult = array();

        $arrResult['quick_flg'] = "1";
        $arrResult['redirecturl'] = SHOPPING_CONFIRM_URLPATH;
        $arrResult['old_order_id'] = $arrOrder['order_id'];

        // dtb_products_class取得
        $products_Class = $this->getProducts_Type_id($arrOrder['order_id']);

        // ダウンロード商品の場合は、支払方法のみ取得
        if ($cartKey == PRODUCT_TYPE_DOWNLOAD && $cartKey == $products_Class['product_type_id']) {
            $arrValues = $this->setDownLoadValues($arrOrder, $arrPaymentCheck);
            $arrResult['payment_id'] = $arrValues['payment_id'];
            $arrResult['paygent_flg'] = $arrPaymentCheck['paygent_flg'];
            if($arrPaymentCheck['paygent_flg'] == true) {
                $arrResult['memo03'] = $arrPaymentCheck['memo03'];
            }
            $objPurchase->saveOrderTemp($uniqid, $arrValues, $objCustomer);

        } elseif ($cartKey == PRODUCT_TYPE_NORMAL) {
            $arrShipping = $this->getShippingAddress($arrOrder['order_id']);
            $arrValues = array();

            // 前回の住所がない場合、会員住所を設定
            if (SC_Utils_Ex::isBlank($arrShipping)) {
                $objPurchase->copyFromCustomer($arrValues, $objCustomer, 'shipping');
            } else {
                $objPurchase->copyFromOrder($arrValues, $arrShipping, 'shipping', '');
            }
            // 前回と今回の商品種別が違う場合
            if ($cartKey == $products_Class['product_type_id']) {
                $arrValues = $this->setNormalValues($uniqid, $arrOrder, $arrPaymentCheck, true, $arrValues);
                $arrResult['payment_id'] = $arrValues['payment_id'];
                $arrResult['paygent_flg'] = $arrPaymentCheck['paygent_flg'];
                if($arrPaymentCheck['paygent_flg'] == true) {
                    $arrResult['memo03'] = $arrPaymentCheck['memo03'];
                }
                $objPurchase->saveOrderTemp($uniqid, $arrValues, $objCustomer);
            } else {
                $arrValues = $this->setNormalValues($uniqid, $arrOrder, $arrPaymentCheck, false, $arrValues);
                $objPurchase->saveOrderTemp($uniqid, $arrValues, $objCustomer);
                $arrResult['quick_flg'] = "0";
                $arrResult['redirecturl'] = SHOPPING_PAYMENT_URLPATH;
            }
        } else {
            $arrResult['quick_flg'] = "0";
            $arrResult['redirecturl'] = SHOPPING_PAYMENT_URLPATH;
        }

        if($arrPaymentCheck['ischeck'] <= 0) {
            $arrResult['redirecturl'] = SHOPPING_PAYMENT_URLPATH;
            $arrResult['quick_flg'] = "0";
        }

        $arrResult['deliv_id'] = $arrOrder['deliv_id'];

        return $arrResult;
    }

    /**
     * ダウンロード用の更新情報設定
     *
     * @param array arrOrder 前回の受注情報配列
     * @param array arrPaymentCheck 支払方法利用可否結果
     * @return 更新情報設定配列
     */
    function setDownLoadValues($arrOrder, $arrPaymentCheck) {
        $arrValues['deliv_id'] = $arrOrder['deliv_id'];

        if($arrPaymentCheck['ischeck'] == 1) {
            $arrValues['charge'] = $arrOrder['charge'];
            $arrValues['payment_id'] = $arrOrder['payment_id'];
            $arrValues['payment_method'] = $arrOrder['payment_method'];
        } elseif ($arrPaymentCheck['ischeck'] == 2) {
            $arrValues['charge'] = $arrPaymentCheck['charge'];
            $arrValues['payment_id'] = $arrPaymentCheck['payment_id'];
            $arrValues['payment_method'] = $arrPaymentCheck['payment_method'];
        }
        return $arrValues;
    }

    /**
     * 通常用の更新情報設定
     *
     * @param string uniqid ユニークキー
     * @param array arrOrder 前回の受注情報配列
     * @param array arrPaymentCheck 支払方法利用可否結果
     * @param boolean product_type_flg true：前回通常配送、false：前回ダンロード配送
     * @return 更新情報設定配列
     */
    function setNormalValues($uniqid, $arrOrder, $arrPaymentCheck, $product_type_flg, $arrValues) {
        $objPurchase = new SC_Helper_Purchase_Ex();

        $arrValues['deliv_id'] = $arrOrder['deliv_id'];

        if($product_type_flg == true) {
            $objPurchase->saveShippingTemp($arrValues);
            // 前回の支払方法をそのまま使用
            if($arrPaymentCheck['ischeck'] == 1) {
                $arrValues['payment_id'] = $arrOrder['payment_id'];
                $arrValues['charge'] = $arrOrder['charge'];
                $arrValues['payment_method'] = $arrOrder['payment_method'];
            // 前回の支払方法と同じ種類で、使用条件が異なる場合はそちらを使用
            } elseif ($arrPaymentCheck['ischeck'] == 2) {
                $arrValues['payment_id'] = $arrPaymentCheck['payment_id'];
                $arrValues['charge'] = $arrPaymentCheck['charge'];
                $arrValues['payment_method'] = $arrPaymentCheck['payment_method'];
            }
            $arrValues['order_temp_id'] = $uniqid;
            $arrValues['use_point'] = 0;
            $arrValues['point_check'] = '2';
            $arrValues['update_date'] = 'Now()';
        } else {
            $objPurchase->saveShippingTemp($arrValues);
        }
        return $arrValues;
    }

    /**
     * 指定した受注の住所情報を取得
     *
     * @param string order_id 受注番号
     * @return array 住所の配列
     *
     */
    function getShippingAddress($order_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "shipping_name01 as name01,
                shipping_name02 as name02,
                shipping_kana01 as kana01,
                shipping_kana02 as kana02,
                shipping_tel01 as tel01,
                shipping_tel02 as tel02,
                shipping_tel03 as tel03,
                shipping_pref as pref,
                shipping_zip01 as zip01,
                shipping_zip02 as zip02,
                shipping_addr01 as addr01,
                shipping_addr02 as addr02,
                shipping_time";

        $table = "dtb_shipping";

        $where  = "del_flg = '0'";
        $where .= " AND order_id = ?";

        $order = "shipping_id";

        $vals = array($order_id);

        $objQuery->setOrder($order);
        $arrShipping = $objQuery->getRow($col, $table, $where, $vals);

        return $arrShipping;
    }

    /**
     * 指定したユニークキーの受注tmpを取得
     *
     * @param string uniqid ユニークキー
     * @return array dtb_paymentの情報
     */
    function getOrderTmp($uniqid) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "ot.payment_id, p.memo03";

        $table = "dtb_order_temp ot, dtb_payment p";
        $where = "ot.order_temp_id = ? AND ot.payment_id = p.payment_id";

        $vals = array($uniqid);
        $arrOrderTmp = $objQuery->getRow($col, $table, $where, $vals);

        return $arrOrderTmp;
    }

    /**
     * 購入金額に応じた支払方法を取得する.
     *
     * @param integer $total 購入金額
     * @param integer $deliv_id 配送業者ID
     * @return array 購入金額に応じた支払方法の配列
     */
    function getPaymentsByPrice($total, $deliv_id) {
        $objPurchase = new SC_Helper_Purchase_Ex();

        $arrPaymentIds = $objPurchase->getPayments($deliv_id);
        if (SC_Utils_Ex::isBlank($arrPaymentIds)) {
            return array();
        }

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // 削除されていない支払方法を取得
        $where = 'del_flg = 0 AND payment_id IN (' . implode(', ', array_pad(array(), count($arrPaymentIds), '?')) . ')';
        $objQuery->setOrder("rank DESC");
        if(preg_match('/^2\.11/', ECCUBE_VERSION)) {
            $payments = $objQuery->select("payment_id, payment_method, rule, upper_rule, note, payment_image, charge, memo03", "dtb_payment", $where, $arrPaymentIds);
        } else {
            $payments = $objQuery->select("payment_id, payment_method, rule_max as rule, upper_rule, note, payment_image, charge, memo03", "dtb_payment", $where, $arrPaymentIds);
        }
        foreach ($payments as $data) {
            // 下限と上限が設定されている
            if (strlen($data['rule']) != 0 && strlen($data['upper_rule']) != 0) {
                if ($data['rule'] <= $total && $data['upper_rule'] >= $total) {
                    $arrPayment[] = $data;
                }
            }
            // 下限のみ設定されている
            elseif (strlen($data['rule']) != 0) {
                if($data['rule'] <= $total) {
                    $arrPayment[] = $data;
                }
            }
            // 上限のみ設定されている
            elseif (strlen($data['upper_rule']) != 0) {
                if($data['upper_rule'] >= $total) {
                    $arrPayment[] = $data;
                }
            }
            // いずれも設定なし
            else {
                $arrPayment[] = $data;
            }
        }
        return $arrPayment;
    }

    /**
     * 指定した顧客カードIDのカード情報を取得する
     *
     * @param string order_id 受注番号
     * @param string card_Seq 顧客カードID
     * @return array カード情報
     */
    function getStockCardData($order_id, $card_Seq) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrResult = array();
        $arrResult['result'] = "0";

        $arrData = $objPurchase->getOrder($order_id);

        // 登録者の確認
        $ret = $objQuery->select("paygent_card", "dtb_customer", "customer_id != 0 AND customer_id = ?", array($arrData['customer_id']));

        // 登録者の情報取得
        if (count($ret) > 0) {
            $this->stock_flg = 1;
            if ($ret[0]['paygent_card'] == 1) {
                $arrRet = sfGetPaygentCreditStock($arrData);
                // 成功
                if ($arrRet[0]['result'] === "0") {
                    foreach ($arrRet as $key => $val) {
                        if ($key != 0) {
                            if($val['customer_card_id'] == $card_Seq) {
                                $arrResult['card_info'] = array("CardSeq" => $val['customer_card_id'],
                                                                "CardNo" => $val['card_number'],
                                                                "Expire" => $val['card_valid_term'],
                                                                "HolderName" => $val['cardholder_name']);
                                $arrResult['result'] = "1";
                                break;
                            }
                        }
                    }

                    // 失敗
                } else {
                    $arrResult['result'] = "0";
                }
            }
        }

        return $arrResult;
    }

    /**
     * セッションへクイック購入情報を保存する
     *
     * @param array quick_info クイック情報
     * @return void
     */
    function saveQuickInfo($quick_info) {
        $this->saveQuickSession("quick_info", $quick_info);
    }

    /**
     * セッションへクイック決済情報を保存する
     *
     * @param array quick_pay_info クイック情報
     * @return void
     */
    function saveQuickPayInfo($quick_pay_info) {
        $this->saveQuickSession("quick_pay_info", $quick_pay_info);
    }

    /**
     * セッションへクイック関連情報を保存する
     *
     * @param array quick_mode セッションに登録するキー
     * @param array quick クイック情報
     * @return void
     */
    function saveQuickSession($quick_mode, $quick) {
        $_SESSION[$quick_mode] = $quick;
    }

    /**
     * セッションからクイック購入情報を削除する
     */
    function clearQuickInfo() {
        $this->clearQuickSession("quick_info");
    }

    /**
     * セッションからクイック決済情報を削除する
     */
    function clearQuickPayInfo() {
        $this->clearQuickSession("quick_pay_info");
    }

    /**
     * セッションからクイック関連情報を削除する
     *
     * @param array quick_mode セッションから削除するキー
     */
    function clearQuickSession($quick_mode) {
        $_SESSION[$quick_mode] = array();
    }

    /**
     * セッションからクイック購入情報を取得する
     */
    function getQuickInfo() {
        return $this->getQuickSession("quick_info");
    }

    /**
     * セッションからクイック決済情報を取得する
     */
    function getQuickPayInfo() {
        return $this->getQuickSession("quick_pay_info");
    }

    /**
     * セッションからクイック関連情報を取得する
     *
     * @param string quick_mode セッションから取得するキー
     */
    function getQuickSession($quick_mode) {
        return $_SESSION[$quick_mode];
    }

    /**
     * ペイジェント指定テンプレートの設定
     *
     * @param $paygentTemplate テンプレートの指定
     * @param $defaultTemplate デフォルトテンプレート
     */
    function sfChangePaymentTemplate($paygentTemplate, $defaultTemplate) {
        if (!$paygentTemplate) {
            return  $defaultTemplate;
        }

        switch(SC_Display_Ex::detectDevice()) {
            case DEVICE_TYPE_MOBILE :
                $tpl_dir = 'mobile/';
                break;
            case DEVICE_TYPE_SMARTPHONE :
                $tpl_dir = 'sphone/';
                break;
            case DEVICE_TYPE_PC :
            default:
                $tpl_dir = 'tokyo-aircon/';
                break;
        }

        $templatePath = MODULE_REALDIR . "mdl_paygent/templates/" . $tpl_dir . $paygentTemplate;
        if ($paygentTemplate != "" && file_exists($templatePath)) {
            $template = $templatePath;
        } else {
            $template = $defaultTemplate;
        }
        return $template;
    }

    /**
     * クイック機能の要・不要を返す
     *
     * @return true：クイック機能要、false:クイック機能不要
     *
     */
    function getIsQuickFunction() {
        $objConfig = new LC_Page_Mdl_Paygent_Config();
        $arrConfig = $objConfig->getConfig();
        if ($arrConfig['quick_pay'] == "0") {
            return false;
        }
        return true;
    }

    /**
     * クイック機能の契約状態を返す
     *
     * @return true：利用可能、false:利用不可能
     */
    function getIsStockCardData($arrParam) {
        $objPurchase = new SC_Helper_Purchase_Ex();

        $arrResult = false;
        $arrData['customer_id'] = "0"; // ダミー番号設定

        // カードお預り情報取得
        $arrRet = sfGetPaygentCreditStockQuick($arrData, $arrParam);

        // 成功
        if ($arrRet[0]['result'] === "0") {
            $arrResult = true;

            // 失敗
        } else {
            if($arrRet[0]['response'] != "（P021）") {
            	$arrResult = true;
            }
        }

        return $arrResult;
    }
}
?>
