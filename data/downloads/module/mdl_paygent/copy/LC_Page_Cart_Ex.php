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
require_once CLASS_REALDIR . 'pages/cart/LC_Page_Cart.php';

require_once MODULE_REALDIR. "mdl_paygent/SC_Mdl_Quick_Helper.php";

/**
 * カート のページクラス(拡張).
 *
 * LC_Page_Cart をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Cart_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Cart_Ex extends LC_Page_Cart {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = SC_Mdl_Quick_Helper::sfChangePaymentTemplate('quick_cart_index.tpl', $this->tpl_mainpage);
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
        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objQuickHelper = new SC_Mdl_Quick_Helper();

        // クイック購入関連のセッションクリア
        $objQuickHelper->clearQuickInfo();

        // クイック購入可能か
        $this->tpl_quick = $objQuickHelper->sfCanQuickSettlement($this->cartItems, $objCustomer->getValue('customer_id'));

        switch($this->mode) {
        case 'quick':
            $objFormParam = $this->lfInitParam($_REQUEST);
            $cartKey = $objFormParam->getValue('cartKey');

            // カート内情報の取得
            $cartList = $objCartSess->getCartList($cartKey);
            // カート商品が1件以上存在する場合
            if(count($cartList) > 0) {
                // カートを購入モードに設定
                $this->lfSetCurrentCart($objSiteSess, $objCartSess, $cartKey);
                $this->tpl_uniqid = $objSiteSess->getUniqId();

                // クイック決済用の受注情報を作成する
                $arrResult = $objQuickHelper->sfRegisterOrder($this->tpl_uniqid, $objCustomer, $objCartSess, $cartKey);

                $arrSelectedDeliv = $this->getSelectedDeliv($objPurchase, $arrResult['deliv_id']);
                $this->arrDelivTime = $arrSelectedDeliv['arrDelivTime'];
                $this->saveShippings($objFormParam, $this->arrDelivTime);
                $objPurchase->saveOrderTemp($uniqid, array(), $objCustomer);

                // 購入ページへ
                SC_Response_Ex::sendRedirect($arrResult['redirecturl'], array(), false, true);
                exit;
            }
            break;
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

    /**
     * 配送業者IDから, 支払い方法, お届け時間の配列を取得する.
     *
     * - 'arrDelivTime' - お届け時間の配列
     *
     * @param SC_Helper_Purchase $objPurchase SC_Helper_Purchase インスタンス
     * @param integer $deliv_id 配送業者ID
     * @return array 支払い方法, お届け時間を格納した配列
     */
    function getSelectedDeliv(&$objPurchase, $deliv_id) {
        $arrResults = array();
        $arrResults['arrDelivTime'] = $objPurchase->getDelivTime($deliv_id);

        return $arrResults;
    }

    /**
     * 配送情報を保存する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param array $arrDelivTime 配送時間の配列
     */
    function saveShippings(&$objFormParam, $arrDelivTime) {
        /* TODO
         * SC_Purchase::getShippingTemp() で取得して,
         * リファレンスで代入すると, セッションに添字を追加できない？
         */
        foreach (array_keys($_SESSION['shipping']) as $key) {
            $shipping_id = $_SESSION['shipping'][$key]['shipping_id'];
            $time_id = $objFormParam->getValue('deliv_time_id' . $shipping_id);
            $_SESSION['shipping'][$key]['time_id'] = $time_id;
            $_SESSION['shipping'][$key]['shipping_time'] = $arrDelivTime[$time_id];
            $_SESSION['shipping'][$key]['shipping_date'] = $objFormParam->getValue('deliv_date' . $shipping_id);
        }
    }
}
?>
