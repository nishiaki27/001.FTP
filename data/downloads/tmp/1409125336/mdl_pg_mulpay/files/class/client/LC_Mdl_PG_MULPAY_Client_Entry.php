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
/**
 * EntryTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Entry extends LC_Mdl_PG_MULPAY_Client {
    /**
     * EntryTranリクエストを送信する
     *
     * @param array $arrData 受注情報
     */
    function request($arrData) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $serverUrl = $objPG->getUserSettings('server_url') . $this->getEntryTranName();
        $arrSendData = $this->getSendRequestParam($arrData);
        $objPG->printLog($serverUrl);
        $objPG->printLog($arrSendData);

        $objReq = new HTTP_Request($serverUrl);
        $objReq->setMethod('POST');
        $objReq->addPostDataArray($arrSendData);
        if (PEAR::isError($e = $objReq->sendRequest())) {
            $msg = "$serverUrl と通信ができませんでした。" . $e->getMessage();
            $this->setError($msg);
            $objPG->printLog($msg);
            return;
        }

        $ret = $objReq->getResponseBody();
        $objPG->printLog($ret);
        $this->parse($ret);

        // EntryTranを再実行すべきか判定するために、
        // (オーダーID,AccessID,AccessPass)の組を記録しておく
        $this->setEntryTranResults($arrData['order_id']);
    }

    /**
     * リクエストパラメータを取得する
     * 
     * @param array $arrData 受注情報
     * @return array リクエストパラメータ
     */
    function getSendRequestParam($arrData) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $arrSendData = array(
            'ShopID'   => $objPG->getUserSettings('shop_id'),  // ショップID
            'ShopPass' => $objPG->getUserSettings('shop_pass'),// ショップパスワード
            'OrderID'  => $arrData['order_id'],                 // 店舗ごとに一意な注文IDを送信する.
            'Amount'   => $arrData['payment_total'],            // 金額
            'Tax'      => '0',                                  // 消費税
         );
         return $arrSendData;
     }

     /**
      * EntryTran名を取得する
      *
      * @return stirng EntryTran名
      */
     function getEntryTranName() {
         return '';
     }

    /**
     * Entryリクエストが実行済みかどうかを判定する.
     *
     * @return boolean
     */
    function isComplete($order_id) {
        //SC_Utils_Ex::sfDomainSessionStart();
        $res = $this->getResults();
        return isset($res['OrderID']) && $res['OrderID'] === $order_id
            && isset($res['AccessID']) && isset($res['AccessPass']);
    }

    function setResults($arrResults) {
        $this->arrResults = $arrResults[0]; // 余分な一次元目を削除
    }

    function setEntryTranResults($order_id) {
        //SC_Utils_Ex::sfDomainSessionStart();
        $_SESSION['MDL_PG_MULPAY']['OrderID'] = $order_id;
        $_SESSION['MDL_PG_MULPAY']['AccessID'] = $this->arrResults['AccessID'];
        $_SESSION['MDL_PG_MULPAY']['AccessPass'] = $this->arrResults['AccessPass'];

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog($_SESSION['MDL_PG_MULPAY']);
    }

    function getResults() {
        //SC_Utils_Ex::sfDomainSessionStart();
        return $_SESSION['MDL_PG_MULPAY'];
    }

    function unsetCompleteSession() {
        //SC_Utils_Ex::sfDomainSessionStart();
        unset($_SESSION['MDL_PG_MULPAY']);
    }
}
?>
