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
 * モバイルSuica決済 ExecTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Exec_Suica extends LC_Mdl_PG_MULPAY_Client_Exec {
    /**
     * リクエストパラメータを取得する
     *
     * @return array リクエストパラメータ
     */
    function getSendRequestParam($arrEntryRet, $objPage) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        // 決済開始メール付加情報
        $suicaAddInfo1 = $objPG->getUserSettings('suicaAddInfo1');
        // 決済完了メール付加情報
        $suicaAddInfo2 = $objPG->getUserSettings('suicaAddInfo2');
        // 決済内容確認画面付加情報
        $suicaAddInfo3 = $objPG->getUserSettings('suicaAddInfo3');
        // 決済完了画面付加情報
        $suicaAddInfo4 = $objPG->getUserSettings('suicaAddInfo4');
        // 加盟店自由項目
        $clientField1 = $objPG->getUserSettings('suica_ClientField1');
        $clientField2 = $objPG->getUserSettings('suica_ClientField2');
        // 商品名
        $firstProductName = LC_Mdl_PG_MULPAY_Utils::getFirstProductName($objPage->arrData['order_id']);
        $ItemName = LC_Mdl_PG_MULPAY_Utils::convertProhibitedKigo($firstProductName);
        $ItemName = LC_Mdl_PG_MULPAY_Utils::convertProhibitedChar($ItemName);
        $ItemName = mb_convert_kana($ItemName, 'KV', 'UTF-8');
        $ItemName = LC_Mdl_PG_MULPAY_Utils::subString($ItemName, SUICA_ITEM_NAME_LEN);

        $arrSendData = array(
            'AccessID'        => trim($arrEntryRet['AccessID']),
            'AccessPass'      => trim($arrEntryRet['AccessPass']),
            'OrderID'         => $objPage->arrData['order_id'],
            'ItemName'        => $ItemName,
            'SuicaAddInfo1'   => $suicaAddInfo1,
            'SuicaAddInfo2'   => $suicaAddInfo2,
            'SuicaAddInfo3'   => $suicaAddInfo3,
            'SuicaAddInfo4'   => $suicaAddInfo4,
            'ClientField1'    => $clientField1,
            'ClientField2'    => $clientField2,
            'ClientField3'    => MDL_PG_MYLPAY_CLIENT_FIELD3,
            'ClientFieldFlag' => '1',
        );

        // MailAddressは必須パラメータ
        $arrSendData['MailAddress'] = $objPage->arrForm['email'] . "@" . $objPage->arrForm['email_domain'];

        // 支払い期限
        if (strlen($objPG->getUserSettings('suica_PaymentTermDay')) !== 0) {
            $arrSendData['PaymentTermDay'] = $objPG->getUserSettings('suica_PaymentTermDay');
            $arrSendData['PaymentTermSec'] = $objPG->getUserSettings('suica_PaymentTermSec');
        }
        $objPG->printLog($arrSendData);
        return $arrSendData;
    }

    /**
     * ExecTran名を取得する
     *
     * @return stirng ExecTran名
     */
    function getExecTranName() {
        return 'ExecTranSuica.idPass';
    }

}
?>
