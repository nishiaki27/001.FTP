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

/*
 * SearchMemberを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Searchmember extends LC_Mdl_PG_MULPAY_Client {
    /**
     * SearchCardリクエストを送信する
     *
     */
    function request() {
        $objCustomer = new SC_Customer;
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $serverUrl = $objPG->getUserSettings('server_url') . 'SearchMember.idPass';

        $arrSendData = array(
            'SiteID'   => $objPG->getUserSettings('site_id'),
            'SitePass' => $objPG->getUserSettings('site_pass'),
            'MemberID' => $objCustomer->getValue('customer_id'), // 会員ID
        );

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

        $this->parse($objReq->getResponseBody());
        $this->checkUserId();
    }

    function checkUserId() {
        $objCustomer = new SC_Customer();

        $arrRes = $this->getResults();
        if (SC_Utils_Ex::isBlank($arrRes)) {
            return;
        }
        $flag = false;
        foreach ($arrRes as $arrData) {
            if (SC_Utils_Ex::isBlank($arrData)) {
                continue;
            }
            if (SC_Utils_Ex::isBlank($arrData['MemberName'])) {
                // 空欄は旧方式として更新
                $objUpdateMember = LC_Mdl_PG_MULPAY_Client::factory('Updatemember');
                $objUpdateMember->request();
                // 運営者にメール通知すべき？
                break;
            } else {
               if ($arrData['MemberName'] != $objCustomer->getValue('secret_key')) {
                   // キーが違う
                   $flag = true;
               } 
            }
        }

        // 削除処理
        if ($flag) {
            $objPG =& LC_Mdl_PG_MULPAY::getInstance();
            $objPG->printLog('Notice: customer_id key not match. delete card data!!');
            $objSearchCard = LC_Mdl_PG_MULPAY_Client::factory('Searchcard');
            $objSearchCard->request(true);
            $arrRes = $objSearchCard->getResults();
            if (SC_Utils_Ex::isBlank($arrRes)) {
                return;
            }
            $objDeleteCard = LC_Mdl_PG_MULPAY_Client::factory('Deletecard');
            foreach ($arrRes as $arrData) {
                $objDeleteCard->request($arrData['CardSeq']);
            }
            $objUpdateMember = LC_Mdl_PG_MULPAY_Client::factory('Updatemember');
            $objUpdateMember->request();
        }
    }
}
