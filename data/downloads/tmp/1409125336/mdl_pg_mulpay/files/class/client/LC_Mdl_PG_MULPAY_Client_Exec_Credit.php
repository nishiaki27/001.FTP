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
 * ExecTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Exec_Credit extends LC_Mdl_PG_MULPAY_Client {
    /**
     * ExecTranリクエストを送信する
     *
     * @param array $arrEntryRet
     * @param array $arrForm
     * @param array $orderId
     * @param boolean $useCard 登録カードを使用するかどうか
     */
    function request($arrEntryRet, $arrForm, $orderId, $useCard=false, $use_security_code=false) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog('-> exec tran request start');

        // 支払い方法, 分割回数
        list($method, $payTimes) = split("-", $arrForm['paymethod']);
        // カード番号
        $cardNo = $arrForm['card_no'];
        // 有効期限
        $expire = $arrForm['card_year'] . $arrForm['card_month'] ;
        // 加盟店自由項目
        $clientField1 = $objPG->getUserSettings('credit_ClientField1');
        $clientField2 = $objPG->getUserSettings('credit_ClientField2');

        $arrSendData = array(
            'AccessID'   => trim($arrEntryRet['AccessID']),
            'AccessPass' => trim($arrEntryRet['AccessPass']),
            'OrderID'    => $orderId,
            'Method'     => $method,
            'CardNo'     => $cardNo,
            'Expire'     => $expire,
            'ClientField1' => $clientField1,
            'ClientField2' => $clientField2,
            'ClientField3' => MDL_PG_MYLPAY_CLIENT_FIELD3,
            'ClientFieldFlag' => '1',
        );

        if ($payTimes != 0) {
            $arrSendData['PayTimes'] = $payTimes;
        }

        // セキュリティコード
        if ($use_security_code) {
            $arrSendData['SecurityCode'] = $arrForm['security_code'];
        }
        // 登録済みカードを使用する場合はパラメータを追加する
        if ($useCard) {
            $arrSendData = $this->addUseCardParam($arrSendData, $arrForm['CardSeq']);
        }

        // 3Dセキュア設定が有効であればパラメータ追加する
        if ($objPG->isEnable3DSecure()) {
            $arrSendData = $this->add3DParam($arrSendData);
        }
        $serverUrl = $objPG->getUserSettings('server_url') . 'ExecTran.idPass';
        $objPG->printLog("-> send data to $serverUrl");

        $objReq = new HTTP_Request($serverUrl);
        $objReq->setMethod('POST');
        $objReq->addPostDataArray(parent::send_data_encoding($arrSendData));

        $arrSendData['CardNo'] = isset($arrSendData['CardNo']) ? '****' : 'nocard';
        $arrSendData['SecurityCode'] = isset($arrSendData['SecurityCode']) ? '****' : 'nocode';
        $objPG->printLog($arrSendData);

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

        // 3Dセキュア設定が有効で、かつカードも対応していれば3D用パラメータ解析を行う
        if ($objPG->isEnable3DSecure()
        && !(isset($arrParam['ACS']) && $arrParam['ACS'] == '0') ) {
            $objPG->printLog($ret);
            $this->parse3D($ret);
            return;
        }

        $objPG->printLog($ret);

        $this->setResults($arrParam);
    }

    /**
     * 3Dセキュア用パラメータを付加する
     *
     * @param array $arrSendParam
     * @return array
     */
    function add3DParam($arrSendParam) {
        $arr3DParam = array(
            'HttpAccept'     => $_SERVER['HTTP_ACCEPT'],
            'HttpUserAgent'  => $_SERVER['HTTP_USER_AGENT'],
            'DeviceCategory' => '0', // 端末種類 0:PC 1:モバイル モバイルはGMO側が未実装(2007年2月現在)
        );
        return array_merge($arrSendParam, $arr3DParam);
    }

    /**
     * ３Dセキュア時の戻り値を解析する
     *
     * @param string $ret
     */
    function parse3D($queryString) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $arrRet = null;
        // 3Dセキュアを使用する場合は正規表現で。
        if (!empty($queryString)) {
            $queryString = trim($queryString);
            $regex = '|^ACS=1&ACSUrl\=(.+?)&PaReq\=(.+?)&MD\=(.+?)$|';
            $ret = preg_match_all($regex, $queryString, $matches);

            if ($ret !== false && $ret > 0) {
                $objPG->printLog(print_r($matches, true));
                $arrRet['ACSUrl'] = $matches[1][0];
                $arrRet['PaReq']  = $matches[2][0];
                $arrRet['MD']     = $matches[3][0];
            } else {
                $this->setError('本人認証サービスの実行に失敗しました。');
                $objPG->printLog('-> 3D response failed: ' . $queryString);
                return;
            }
        }

        $this->setResults($arrRet);
    }

    /**
     * 登録カード使用時のパラメータを追加する
     *
     * @param array $arrSendParam
     * @param integer $cardSeq
     * @return array
     */
    function addUseCardParam($arrSendParam, $cardSeq) {
        $objCustomer = new SC_Customer();
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $arrUseCardParam = array(
            'SiteID'   => $objPG->getUserSettings('site_id'),
            'SitePass' => $objPG->getUserSettings('site_pass'),
            'MemberID' => $objCustomer->getValue('customer_id'),
            'SeqMode'  => MDL_PG_MULPAY_SEQMODE, // カード登録連番モード0:論理/1:物理
            'CardSeq'  => $cardSeq,
        );
        return array_merge($arrSendParam, $arrUseCardParam);
    }
}
?>
