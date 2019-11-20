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
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'client/LC_Mdl_PG_MULPAY_Client_Exec_Payeasy.php');
/**
 * ネットバンキング決済 ExecTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Exec_NetBank extends LC_Mdl_PG_MULPAY_Client_Exec_Payeasy {
    /**
     * リクエストパラメータを取得する
     *
     * @return array リクエストパラメータ
     */
    function getSendRequestParam($arrEntryRet, $objPage) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        // 氏名
        $customerName = $objPage->arrData['order_name01'] . "　" . $objPage->arrData['order_name02'];
        // フリガナ
        $customerKana = $objPage->arrData['order_kana01'] . "　" . $objPage->arrData['order_kana02'];
        $customerKana = mb_convert_kana($customerKana, 'AKV', 'UTF-8');
        // 電話番号
        $telNo = $objPage->arrData['order_tel01'] . "-" . $objPage->arrData['order_tel02'] . "-" . $objPage->arrData['order_tel03'];

        // 加盟店自由項目
        $clientField1 = $objPG->getUserSettings('netbank_ClientField1');
        $clientField2 = $objPG->getUserSettings('netbank_ClientField2');

        $arrSendData = array(
            'AccessID'        => trim($arrEntryRet['AccessID']),
            'AccessPass'      => trim($arrEntryRet['AccessPass']),
            'OrderID'         => $objPage->arrData['order_id'],
            'CustomerName'    => $customerName,
            'CustomerKana'    => $customerKana,
            'TelNo'           => $telNo,
            'ReceiptsDisp11'  => 'dummy',
            'ReceiptsDisp12'  => '00-0000-0000',
            'ReceiptsDisp13'  => '00:00-00:00',
            'ClientField1'    => $clientField1,
            'ClientField2'    => $clientField2,
            'ClientField3'    => MDL_PG_MYLPAY_CLIENT_FIELD3,
            'ClientFieldFlag' => '1',
            'PaymentType'     => 'E',
        );

        // GMOPGから購入者へのメール
        if (MDL_PG_MULPAY_CONF_PGMAIL_NETBANK) {
            //$objPG->printLog('MDL_PG_MULPAY_CONF_PGMAIL_NETBANK');
            $arrSendData['MailAddress'] = $objPage->arrData['order_email'];
        }
        // 支払い期限
        if (strlen($objPG->getUserSettings('netbank_PaymentTermDay')) !== 0) {
            $arrSendData['PaymentTermDay'] = $objPG->getUserSettings('netbank_PaymentTermDay');
        }
        $objPG->printLog($arrSendData);
        return $arrSendData;
    }

}
?>
