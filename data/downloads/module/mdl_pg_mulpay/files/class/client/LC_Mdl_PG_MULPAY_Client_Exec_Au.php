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
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'client/LC_Mdl_PG_MULPAY_Client_Exec.php');
/**
 * PayPal決済 ExecTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Exec_Au extends LC_Mdl_PG_MULPAY_Client_Exec {
    /**
     * ExecTran名を取得する
     *
     * @return stirng ExecTran名
     */
    function getExecTranName() {
        return 'ExecTranAu.idPass';
    }

    /**
     * リクエストパラメータを取得する
     *
     * @return array リクエストパラメータ
     */
    function getSendRequestParam($arrEntryRet, $objPage) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        // ItemNameを生成
        $orderNo = '注文番号：' . mb_convert_kana($objPage->arrData['order_id'], 'N', 'UTF-8');
        $tempProductName = LC_Mdl_PG_MULPAY_Utils::getFirstProductName($objPage->arrData['order_id']);
        $tempProductName = mb_convert_kana($tempProductName, 'AKV', 'UTF-8');
        $tempProductName = LC_Mdl_PG_MULPAY_Utils::convertProhibitedChar($tempProductName);
        $tempProductName = LC_Mdl_PG_MULPAY_Utils::convertProhibitedKigo($tempProductName);
        $tempProductName = mb_convert_kana($tempProductName, 'S', 'UTF-8');
        $itemName = LC_Mdl_PG_MULPAY_Utils::subString($orderNo . '　' . $tempProductName, AU_ITEM_NAME);
        //$objPG->printLog("AU_ITEM_NAME: $itemName");

        // 加盟店自由項目
        $clientField1 = $objPG->getUserSettings('au_ClientField1');
        $clientField2 = $objPG->getUserSettings('au_ClientField2');

        $au_ServiceTel = implode('-', array($objPG->getUserSettings('au_ServiceTel_1'),
                                            $objPG->getUserSettings('au_ServiceTel_2'),
                                            $objPG->getUserSettings('au_ServiceTel_3')));

        $arrSendData = array(
            'ShopID'   		  => $objPG->getUserSettings('shop_id'),
            'ShopPass' 		  => $objPG->getUserSettings('shop_pass'),
            'AccessID'        => trim($arrEntryRet['AccessID']),
            'AccessPass'      => trim($arrEntryRet['AccessPass']),
            'OrderID'         => $objPage->arrData['order_id'],
            'ClientField1'    => $clientField1,
            'ClientField2'    => $clientField2,
            'ClientField3'    => MDL_PG_MYLPAY_CLIENT_FIELD3,
            //'ClientFieldFlag' => '1',
            'Commodity'		  => $itemName,
            'RetURL'	  	  => MDL_PG_MULPAY_RETURL,
            'ServiceName'	  => $objPG->getUserSettings('au_ServiceName'),
            'ServiceTel'	  => $au_ServiceTel
        );

        // 会員購入ならばMemberIDを指定する。
        $objCustomer = new SC_Customer;
        if ($objCustomer->isLoginSuccess(true)) {
            $arrSendData['SiteID'] = $objPG->getUserSettings('site_id');
            $arrSendData['SitePass'] = $objPG->getUserSettings('site_pass');
            $arrSendData['MemberID'] = $objCustomer->getValue('customer_id');
            $arrSendData['CreateMember'] = '0';
        }

        // 支払開始期限秒
        if (strlen($objPG->getUserSettings('au_PaymentTermSec')) !== 0) {
            $arrSendData['PaymentTermSec'] = $objPG->getUserSettings('au_PaymentTermSec');
        }

        $objPG->printLog($arrSendData);
        return $arrSendData;
    }
}
?>
