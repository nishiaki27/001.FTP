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

/**
 * payeasy決済処理クラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY_Payeasy extends LC_Page_Mdl_PG_MULPAY {
    /**
     * パラメータエラーチェック
     *
     * @param string $uniqid      ユニークID
     * @param object $objSiteSess サイトセッション
     */
    function checkParamError($uniqid, $objSiteSess) {
        // 一時受注テーブルの読込
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrData = $objPurchase->getOrder($_SESSION['order_id']);
        if ($arrData['del_flg'] == '1') {
            $this->panic($_SESSION['order_id'], 'ご注文情報が無効になっています。');
        }

        $customerName = $arrData['order_name01'] . "　" . $arrData['order_name02'];    // 氏名
        $customerKana = $arrData['order_kana01'] . "　" . $arrData['order_kana02'];    // フリガナ
        $telNo = $arrData['order_tel01'] . "-" . $arrData['order_tel02'] . "-" . $arrData['order_tel03'];    // 電話番号
        $message = '';
        if (strlen(mb_convert_encoding($customerName, "SJIS", "UTF-8")) > CUSTOMER_NAME_LEN) {
            $message = "お客様のご氏名の桁数がシステムの許容範囲外です。<br>";
        }
        if (strlen(mb_convert_encoding($customerKana, "SJIS", "UTF-8")) > CUSTOMER_KANA_LEN) {
            $message .= "お客様のフリガナの桁数がシステムの許容範囲外です。<br>";
        }
        if (strlen($telNo) > TEL_NO_LEN) {
            $message .= "お客様のお電話番号の桁数がシステムの許容範囲外です<br>";
        }
        if (strlen($message) != 0) {
            $message .= "<br><br>ご選択されたお支払方法は、ご利用になれません。<br>";
            $message .= "申し訳ありませんが、その他のお支払方法をお選びください。";
            $this->panic($_SESSION['order_id'], $message);
        }
    }

    /**
     * 決済処理が正常終了
     *
     * @return void
     */
    function orderComplete($order_id, $sqlval = array(), $order_status = ORDER_PAY_WAIT) {
        parent::orderComplete($order_id, $sqlval, $order_status);
    }
    
    /**
     * 利用金額上限を取得する。
     * 
     * @return integer 利用金額上限
     */
    function getRuleMax() {
        return PAYEASY_RULE_MAX;
    }

}

?>
