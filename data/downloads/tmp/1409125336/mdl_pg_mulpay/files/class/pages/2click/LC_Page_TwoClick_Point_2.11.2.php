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
require_once CLASS_EX_REALDIR . 'page_extends/shopping/LC_Page_Shopping_Payment_Ex.php';

/**
 * ポイント指定 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id:LC_Page_TwoClick_Payment.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class LC_Page_TwoClick_Point extends LC_Page_Ex {
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_onload = 'fnCheckInputPoint();';
        $this->tpl_title = "ポイントご利用の指定";

        $this->tpl_mainpage = 'twoClick/point.tpl';
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
        $objSiteSess = new SC_SiteSession_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objFormParam = new SC_FormParam_Ex();

        $this->is_multiple = $objPurchase->isMultiple();

        // カートの情報を取得
        $this->arrShipping =& $objPurchase->getShippingTemp($this->is_multiple);

        $this->tpl_uniqid = $objSiteSess->getUniqId();
        $cart_key = $objCartSess->getKey();
        $objPurchase->verifyChangeCart($this->tpl_uniqid, $objCartSess);

        // 会員情報の取得
        if ($objCustomer->isLoginSuccess(true)) {
            $this->tpl_login = '1';
            $this->tpl_user_point = $objCustomer->getValue('point');
            $this->name01 = $objCustomer->getValue('name01');
            $this->name02 = $objCustomer->getValue('name02');
        }

        $arrOrderTemp = $objPurchase->getOrderTemp($this->tpl_uniqid);
        // 正常に受注情報が格納されていない場合はカート画面へ戻す
        if (SC_Utils_Ex::isBlank($arrOrderTemp)) {
            SC_Response_Ex::sendRedirect(CART_URLPATH); // XXX 2clickモード解除
            exit;
        }

        // カート内商品の妥当性チェック
        $this->tpl_message = $objCartSess->checkProducts($cart_key);
        if (strlen($this->tpl_message) >= 1) {
            SC_Response_Ex::sendRedirect(CART_URLPATH); // XXX 2clickモード解除
            exit;
        }

        // 購入金額の取得
        $this->arrPrices = $objCartSess->calculate($cart_key, $objCustomer, 0, $objPurchase->getShippingPref($this->is_multiple));

        switch($this->getMode()) {
        // 登録処理
        case 'confirm':
            // パラメータ情報の初期化
            $this->setFormParams($objFormParam, $_POST);

            $this->arrErr = LC_Page_Shopping_Payment_Ex::lfCheckError($objFormParam, $this->arrPrices['subtotal'], $this->tpl_user_point);

            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                $this->lfRegistData($this->tpl_uniqid, $objFormParam->getDbArray(), $objPurchase, array('dummy'));

                // 正常に登録されたことを記録しておく
                $objSiteSess->setRegistFlag();
                // 確認ページへ移動
                SC_Response_Ex::sendRedirect('./confirm.php');
                exit;
            } else {
                // 受注一時テーブルからの情報を格納
                $objFormParam->setParam($objPurchase->getOrderTemp($this->tpl_uniqid));
            }
            break;

        // 前のページに戻る
        case 'return':

            // 正常な推移であることを記録しておく
            $objSiteSess->setRegistFlag();
            SC_Response_Ex::sendRedirect('./confirm.php');
            exit;
            break;

        default:
            $this->setFormParams($objFormParam, $arrOrderTemp);

            break;
        }

        // モバイル用 ポストバック処理
        //        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
        //            $this->tpl_mainpage = $this->getMobileMainpage($this->is_single_deliv, $this->getMode());
        //	      }

        $this->arrForm = $objFormParam->getFormParamList();
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
     * パラメータの初期化を行い, 初期値を設定する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param array $arrParam 設定する値の配列
     */
    function setFormParams(&$objFormParam, $arrParam) {
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($arrParam);
        $objFormParam->convParam();
    }

    /**
     * パラメータ情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam("ポイント", "use_point", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK", "ZERO_START"));
        $objFormParam->addParam("ポイントを使用する", "point_check", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"), '2');
    }

    /**
     * 受注一時テーブルへ登録を行う.
     *
     * @param integer $uniqid 受注一時テーブルのユニークID
     * @param array $arrForm フォームの入力値
     * @param SC_Helper_Purchase $objPurchase SC_Helper_Purchase インスタンス
     * @param array $arrPayment お支払い方法の配列
     * @return void
     */
    function lfRegistData($uniqid, $arrForm, &$objPurchase, $arrPayment) {
        $arrForm['order_temp_id'] = $uniqid;
        $arrForm['update_date'] = 'Now()';

        if($arrForm['point_check'] != '1') {
            $arrForm['use_point'] = 0;
        }

        $objPurchase->saveOrderTemp($uniqid, $arrForm);
    }
}
?>
