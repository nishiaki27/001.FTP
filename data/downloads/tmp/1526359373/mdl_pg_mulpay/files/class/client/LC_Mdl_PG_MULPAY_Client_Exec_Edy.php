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
 * Mobile Edy決済 ExecTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Exec_Edy extends LC_Mdl_PG_MULPAY_Client_Exec {
    /**
     * リクエストパラメータを取得する
     *
     * @return array リクエストパラメータ
     */
    function getSendRequestParam($arrEntryRet, $objPage) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        // 決済開始メール付加情報
        $edyAddInfo1 = $objPG->getUserSettings('edyAddInfo1');
        // 決済完了メール付加情報
        $edyAddInfo2 = $objPG->getUserSettings('edyAddInfo2');

        // 加盟店自由項目
        $clientField1 = $objPG->getUserSettings('edy_ClientField1');
        $clientField2 = $objPG->getUserSettings('edy_ClientField2');

        $arrSendData = array(
            'AccessID'        => trim($arrEntryRet['AccessID']),
            'AccessPass'      => trim($arrEntryRet['AccessPass']),
            'OrderID'         => $objPage->arrData['order_id'],
            'EdyAddInfo1'     => $edyAddInfo1,
            'EdyAddInfo2'     => $edyAddInfo2,
            'ClientField1'    => $clientField1,
            'ClientField2'    => $clientField2,
            'ClientField3'    => MDL_PG_MYLPAY_CLIENT_FIELD3,
            'ClientFieldFlag' => '1',
        );

        // MailAddressは必須パラメータ
        $arrSendData['MailAddress'] = $objPage->arrForm['email'] . "@" . $objPage->arrForm['email_domain'];

        // 支払い期限
        if (strlen($objPG->getUserSettings('edy_PaymentTermDay')) !== 0) {
            $arrSendData['PaymentTermDay'] = $objPG->getUserSettings('edy_PaymentTermDay');
            $arrSendData['PaymentTermSec'] = $objPG->getUserSettings('edy_PaymentTermSec');
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
        return 'ExecTranEdy.idPass';
    }

}
?>
