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
 * SaveCardを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Savecard extends LC_Mdl_PG_MULPAY_Client {
    /**
     * SaveCardリクエストを送信する
     *
     */
    function request($arrForm) {
        $objCustomer = new SC_Customer;
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $serverUrl = $objPG->getUserSettings('server_url') . 'SaveCard.idPass';
        // カード番号
        $cardNo = $arrForm['card_no'];
        // 有効期限
        $expire = $arrForm['card_year'] . $arrForm['card_month'] ;
        $name   = $arrForm['card_name01'] . ' ' . $arrForm['card_name02'];

        $arrSendData = array(
            'SiteID'   => $objPG->getUserSettings('site_id'),
            'SitePass' => $objPG->getUserSettings('site_pass'),
            'MemberID' => $objCustomer->getValue('customer_id'), // 会員ID
            'SeqMode'  => MDL_PG_MULPAY_SEQMODE, // カード登録連番モード0:論理/1:物理(デフォルト0)
            'CardSeq'  => '',    // 登録連番
            'DefaultFlag' => 1, // 洗替・継続課金フラグ 0:非継続課金対象/1:継続課金対象
            'CardName' => '',    // カード会社略称
            'CardNo' => $cardNo,
            'CardPass' => '',
            'Expire' => $expire,
            'HolderName' => $name, // 名義人
        );

        $objReq = new HTTP_Request($serverUrl);
        $objReq->setMethod('POST');
        $objReq->addPostDataArray($arrSendData);

        $objPG->printLog($serverUrl);
        $arrSendData['CardNo'] = isset($arrSendData['CardNo']) ? '****' : '';
        $objPG->printLog($arrSendData);

        if (PEAR::isError($e = $objReq->sendRequest())) {
            $msg = "$serverUrl と通信ができませんでした。" . $e->getMessage();
            $this->setError($msg);
            $objPG->printLog($msg);
            return;
        }

        $this->parse($objReq->getResponseBody());
    }
}
