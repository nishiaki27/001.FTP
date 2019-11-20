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
 * SecureTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Secure extends LC_Mdl_PG_MULPAY_Client {
    /**
     * SecureTranリクエストを送信する
     *
     */
    function request() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $serverUrl = $objPG->getUserSettings('server_url') . 'SecureTran.idPass';

        $arrSendData = array(
            'PaRes' => $_POST['PaRes'],
            'MD'    => $_POST['MD'],
        );

        $objPG->printLog($serverUrl);
        $objPG->printLog($arrSendData);

        $objReq = new HTTP_Request($serverUrl, $option);
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
     * カード会社サイトのパスワード入力画面で
     * 「キャンセル」を押したときのエラーかどうかを判定する.
     *
     * @return boolean
     */
    function isCancel() {
        $arrErr = $this->getError();
        if ($arrErr['gmo_request'] === 'E21-E21020002') {
            return true;
        }
        return false;
    }
}
