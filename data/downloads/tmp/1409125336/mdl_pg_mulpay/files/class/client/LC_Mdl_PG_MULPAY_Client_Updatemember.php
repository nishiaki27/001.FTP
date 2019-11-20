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
 * UpdateMemberを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Updatemember extends LC_Mdl_PG_MULPAY_Client {
    /**
     * SaveMemberリクエストを送信する
     *
     */
    function request() {
        $objCustomer = new SC_Customer;
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $serverUrl = $objPG->getUserSettings('server_url') . 'UpdateMember.idPass';

        $arrSendData = array(
            'SiteID'   => $objPG->getUserSettings('site_id'),
            'SitePass' => $objPG->getUserSettings('site_pass'),
            'MemberID' => $objCustomer->getValue('customer_id'), // 会員ID
            'MemberName' => $objCustomer->getValue('secret_key'), // 会員固有内部キー
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
    }

    /**
     * 結果の解析を行う
     *
     * @param string $ret
     */
    function parse($ret) {
        $arrRet = LC_Mdl_PG_MULPAY_Utils::parse($ret);

        if (isset($arrRet[0]['ErrCode'])) {
            // エラーが『「未登録」だけ』の場合はエラーとして扱わない
            if ($arrRet[0]['ErrCode'] == 'E01'
            && $arrRet[0]['ErrInfo'] == 'E01390002'
            && !isset($arrRet[1])) {

                $objPG =& LC_Mdl_PG_MULPAY::getInstance();
                $objPG->printLog('-> already no-register customer');
                return;
            }
            $this->setError($this->createErrCode($arrRet));
        } else {
            $this->setResults($arrRet);
        }
    }
}
?>
