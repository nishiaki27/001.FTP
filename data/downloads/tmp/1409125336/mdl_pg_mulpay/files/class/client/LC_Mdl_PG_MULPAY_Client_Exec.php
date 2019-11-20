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
 * ExecTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Exec extends LC_Mdl_PG_MULPAY_Client {
    /**
     * ExecTranリクエストを送信する
     *
     * @param array $arrEntryRet
     * @param object $objPage
     */
    function request($arrEntryRet, $objPage) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog('-> exec tran request start');

        $serverUrl = $objPG->getUserSettings('server_url') . $this->getExecTranName();
        $objPG->printLog("-> send data to $serverUrl");

        $objReq = new HTTP_Request($serverUrl);
        $objReq->setMethod('POST');
        
        $arrSendData = $this->getSendRequestParam($arrEntryRet, $objPage);
        
        $objReq->addPostDataArray(parent::send_data_encoding($arrSendData));

        if (PEAR::isError($e = $objReq->sendRequest())) {
            $this->setError("$serverUrl と通信ができませんでした。" . $e->getMessage());
            $objPG->printLog('-> failed http request send');
            return;
        }

        if ($objReq->getResponseCode() !== 200) {
            $this->setError("$serverUrl と通信ができませんでした。");
            $objPG->printLog('-> invalid response code:' . $objReq->getResponseCode());
            return;
        }
        $objPG->printLog('-> request is valid');

        foreach ($objReq->getResponseHeader() as $name => $value) {
            $objPG->printLog("header: $name: $value");
        }

        $this->parse($objReq->getResponseBody());
    }

    /**
     * 結果の解析を行う
     *
     * @param string $ret
     */
    function parse($ret) {
        $ret = mb_convert_encoding($ret, "UTF-8", "SJIS");
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $errRegex = '|^ErrCode\=(.+?)&ErrInfo\=(.+?)$|';
        if (preg_match($errRegex, $ret, $matches)) {
            $errCode = explode('|', $matches[1]);
            $errInfo = explode('|', $matches[2]);
            $count = count($errInfo);
            $msg = '';
            for ($i = 0; $i < $count; $i++) {
                $msg .= sprintf('ERRCODE:%s ERRINFO:%s', $errCode[$i], $errInfo[$i]) . "\n";
            }

            $this->setError($msg);
            $objPG->printLog(print_r($matches, true));
            return;
        }

        $arrParam = array();
        $arrTmp = explode('&' , $ret);
        foreach ($arrTmp as $tmp) {
            list($k, $v) = split('=', $tmp);
            $arrParam[$k] = $v;
        }

        $objPG->printLog($ret);

        $this->setResults($arrParam);
    }

    /**
     * リクエストパラメータを取得する
     *
     * @return array リクエストパラメータ
     */
    function getSendRequestParam($arrEntryRet, $objPage) {
        return array();
    }

    /**
     * ExecTran名を取得する
     *
     * @return stirng ExecTran名
     */
    function getExecTranName() {
        return '';
    }
    
}
?>
