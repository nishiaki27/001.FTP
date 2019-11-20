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
require_once DATA_REALDIR . 'module/Request.php';

class LC_Mdl_PG_MULPAY_Client {
    var $arrErr = array('gmo_request' => null);
    var $arrResults = null;

    function factory($classname) {
        $class = 'LC_Mdl_PG_MULPAY_Client_' . $classname;
        require_once MDL_PG_MULPAY_CLASS_REALDIR . 'client/' . $class . '.php';
        return new $class;
    }

    function isError() {
        return isset($this->arrErr['gmo_request']) ? true : false;
    }

    function setError($msg) {
        $this->arrErr['gmo_request'] = $msg;
    }

    function getError() {
        return $this->arrErr;
    }

    function setResults($arrResults) {
        $this->arrResults = $arrResults;
    }

    function getResults() {
        return $this->arrResults;
    }

    function request() {}

    /**
     * 結果の解析を行う
     *
     * @param string $ret
     */
    function parse($ret) {
        $arrRet = LC_Mdl_PG_MULPAY_Utils::parse($ret);

        if (isset($arrRet[0]['ErrCode'])) {
            $this->setError($this->createErrCode($arrRet));
        } else {
            $this->setResults($arrRet);
        }
    }

    /**
     * エラーコード文字列を構築する
     *
     * @param array $arrRet
     * @return string
     */
    function createErrCode($arrRet) {
        $msg = '';
        foreach($arrRet as $ret) {
            $msg .= sprintf('%s-%s,', $ret['ErrCode'], $ret['ErrInfo']);
        }
        $msg = substr($msg, 0, strlen($msg)-1); // 最後の,をカット
        return $msg;
    }

    /**
     * 送信データの文字コード変換（UTF-8→SJIS）を行う。
     *
     * @param array $arrSendData 変換対象
     * @return array 変換した値
     */
    function send_data_encoding($arrSendData) {
        foreach ($arrSendData as $key => $val) {
            $arrSendData[$key] = mb_convert_encoding($val, "SJIS", "UTF-8");
        }
        return $arrSendData;
    }

    function getModuleRetUrl($params = array(), $redirectURL = MDL_PG_MULPAY_RETURL) {
        $url = new Net_URL($redirectURL);

        foreach ($params as $key => $val) {
            $url->addQueryString($key, $val);
        }

        return $url->getURL();
    }
}
?>
