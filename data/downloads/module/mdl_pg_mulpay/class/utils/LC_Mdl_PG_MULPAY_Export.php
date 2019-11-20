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
require_once MODULE_REALDIR . 'mdl_pg_mulpay/class/LC_Mdl_PG_MULPAY.php';

//http://www.php.net/manual/ja/function.array-diff-key.php
if (!function_exists('array_diff_key')) {
   function array_diff_key() {
        $arrs = func_get_args();
        $result = array_shift($arrs);
        foreach ($arrs as $array) {
            foreach ($result as $key => $v) {
                if (array_key_exists($key, $array)) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
   }
}

class LC_Mdl_PG_MULPAY_Export {

    function &getInstance() {
        static $singlton;
        if (empty($singlton)) {
            $singlton = new LC_Mdl_PG_MULPAY_Export();
        }
        //$singlton->init();
        return $singlton;
    }

    /**
     * インタフェースの出力パラメータを配列で返す
     *
     * @param string $string レスポンス
     * @return array 解析結果 またはエラー文字列
     */
    function requestPayment($constUrl, $inputArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $objReq = new HTTP_Request($constUrl);
        $objReq->setMethod('POST');
        $objReq->addPostDataArray($inputArray);

        LC_Mdl_PG_MULPAY::printLog("admin request: $constUrl" . print_r($inputArray,true));

        $e = $objReq->sendRequest();
        if (PEAR::isError($e)) {
            $msg = "$constUrl と通信ができませんでした。requestPayment:" . $e->getMessage();
            $objPG->printLog($msg);
            return $msg;
        }

        $responseArray = $this->parse($objReq->getResponseBody());
        LC_Mdl_PG_MULPAY::printLog("admin response: $constUrl" . print_r($responseArray,true));
        if ($responseArray['ErrCode'] != "") {
            return $this->getErrMsg($responseArray);
        } else {
            return $responseArray;
        }
    }

    function requestPaymentMulti($constUrl, $inputArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $objReq = new HTTP_Request($constUrl);
        $objReq->setMethod('POST');
        $objReq->addPostDataArray($inputArray);

        LC_Mdl_PG_MULPAY::printLog("admin request: $constUrl" . print_r($inputArray,true));

        $e = $objReq->sendRequest();
        if (PEAR::isError($e)) {
            $msg = "$constUrl と通信ができませんでした。requestPayment:" . $e->getMessage();
            $objPG->printLog($msg);
            return $msg;
        }

        $responseArray = $this->parse($objReq->getResponseBody());
        LC_Mdl_PG_MULPAY::printLog("admin response: $constUrl" . print_r($responseArray,true));
        if ($responseArray['ErrCode'] != "") {
            $arrErrCode = explode('|', $responseArray['ErrCode']);
            $arrErrInfo = explode('|', $responseArray['ErrInfo']);

            foreach ($arrErrCode as $code) {
                $info = array_shift($arrErrInfo);
                $errors[] = $code . '-' . $info;
            }

            $errors_str = implode(',', $errors);

            return $errors_str;
        } else {
            return $responseArray;
        }
    }

    /**
     * レスポンスを解析する
     *
     * @param string $string レスポンス
     * @return array 解析結果
     */
    function parse($string) {
        $string = trim($string);

        $arrTmpAnd = explode('&', $string);

        foreach($arrTmpAnd as $eqString) {
            // $eqString -> CardSeq=2|0|1, DefaultFlag=0|0|0...
            list($key, $val) = explode('=', $eqString);
            $arrRet[$key] = trim($val);
        }
        return $arrRet;
    }    

    /*
     * tpl path
     */

    function getTplDirPath() {
        return MODULE_REALDIR . "mdl_pg_mulpay/templates/";
    }

    /*
     * 定数
     */

    /* カード */
    function getCardStatus() {
        static $_mtb_order_card_status_master;
        require_once(dirname(__FILE__) . "/../../data/mtb_order_card_status.php");
        return $_mtb_order_card_status_master;
    }

    function getCardError() {
        static $_mtb_card_error_master;
        require_once(dirname(__FILE__) . "/../../data/mtb_card_error.php");
        return $_mtb_card_error_master;
    }

    function getCardStatusChange() {
        static $_mtb_order_card_status_change_master;
        require_once(dirname(__FILE__) . "/../../data/mtb_order_card_status_change.php");
        return $_mtb_order_card_status_change_master;
    }

    function getCardStatusChangeNothingChange() {
        static $_mtb_order_card_status_change_nothing_change_master;
        require_once(dirname(__FILE__) . "/../../data/mtb_order_card_status_change_nothing_change.php");
        return $_mtb_order_card_status_change_nothing_change_master;
    }

    /* PayPal */
    function getPaypalStatusArray() {
        return array('REQSUCCESS' => '入金待ち',
                     'CAPTURE' => '入金済み',
                     'PAYFAIL' => '決済失敗',
                     'CANCEL' => 'キャンセル',
                     'EXPIRED' => '期限切れ'
                     );
    }

    function getPaypalStatusChangeArray() {
        return array('CAPTURE' => array('CANCEL' => 'キャンセル'));
    }

    /* Netid */
    function getNetidStatusArray() {
        return array('AUTH' => '仮売上',
                     'SALES' => '実売上',
                     'CAPTURE' => '即時売上',
                     'CANCEL' => 'キャンセル',
                     'PAYFAIL' => '決済失敗',
                     'EXPIRED' => '期限切れ'
                     );
    }

    function getNetidStatusChangeArray() {
        return array('AUTH' => array('SALES' => '実売上',
                                     'CANCEL' => 'キャンセル'),
                     'SALES' => array('CANCEL' => 'キャンセル'),
                     'CAPTURE' => array('CANCEL' => 'キャンセル'));
    }

    function getNetidStatusChangeArray2() {
        return array('AUTH' => array('SALES' => '実売上',
                                     'CHANGE' => '金額変更',
                                     'CANCEL' => 'キャンセル'),
                     'SALES' => array('CANCEL' => 'キャンセル'),
                     'CAPTURE' => array('CANCEL' => 'キャンセル'));
    }

    /* Docomo */
    function getDocomoStatusArray() {
        return array('AUTH' => '仮売上',
                     'SALES' => '実売上',
                     'CAPTURE' => '即時売上',
                     'CANCEL' => 'キャンセル',
                     'RETURN' => '返品',
                     'PAYFAIL' => '決済失敗'
                     );
    }
    /* Au */
    function getAuStatusArray() {
        return array('AUTH' => '仮売上',
                     'SALES' => '実売上',
                     'CAPTURE' => '即時売上',
                     'CANCEL' => 'キャンセル',
                     'RETURN' => '返品',
                     'PAYFAIL' => '決済失敗'
                     );
    }

    // auかんたん決済状況変更 選択肢
    function getAuStatusChangeArray() {
        return array('AUTH' => array('SALES' => '実売上',
                                     'CANCEL' => 'キャンセル'),
                     'SALES' => array('CANCEL' => 'キャンセル'),
                     'CAPTURE' => array('CANCEL' => 'キャンセル'));
    }
    // ドコモケータイ払い状況変更 選択肢
    function getDocomoStatusChangeArray() {
        return array('AUTH' => array('SALES' => '実売上',
                                     'CANCEL' => 'キャンセル'),
                     'SALES' => array('CANCEL' => 'キャンセル'),
                     'CAPTURE' => array('CANCEL' => 'キャンセル'));
    }

    function getAuPayMethodArray() {
        return array('01' => '合算',
                     '02' => 'クレジット',
                     '03' => 'WebMoney'
                     );
    }
    function getDocomoPayMethodArray() {
        return array();
    }

    // 受注編集 決済状況変更 選択肢
    function getAuStatusChangeArray2() {
        return array('AUTH' => array('SALES' => '実売上',
                                     'CANCEL' => 'キャンセル'),
                     'SALES' => array('CANCEL' => 'キャンセル'),
                     'CAPTURE' => array('CANCEL' => 'キャンセル'));
    }

    // 受注編集 決済状況変更 選択肢
    function getDocomoStatusChangeArray2() {
        return array('AUTH' => array('SALES' => '実売上',
                                     'CANCEL' => 'キャンセル'),
                     'SALES' => array('CANCEL' => 'キャンセル'),
                     'CAPTURE' => array('CANCEL' => 'キャンセル'));
    }
    /*
     * GMO決済であるかを判定
     */

    function isGmoCreditPaymentId($payment_id) {
        $credit_id = LC_Mdl_PG_MULPAY_Export::getGmoCreditPaymentId();
        $token_id  = LC_Mdl_PG_MULPAY_Export::getGmoTokenPaymentId();
        return ($payment_id == $credit_id || $payment_id == $token_id);
    }

    function isGmoPaypalPaymentId($payment_id) {
        return $payment_id == LC_Mdl_PG_MULPAY_Export::getGmoPaypalPaymentId();
    }

    function isGmoNetidPaymentId($payment_id) {
        return $payment_id == LC_Mdl_PG_MULPAY_Export::getGmoNetidPaymentId();
    }

    function isGmoAuPaymentId($payment_id) {
        return $payment_id == LC_Mdl_PG_MULPAY_Export::getGmoAuPaymentId();
    }

    function isGmoDocomoPaymentId($payment_id) {
        return $payment_id == LC_Mdl_PG_MULPAY_Export::getGmoDocomoPaymentId();
    }

    function getGmoCreditPaymentId() {
        return LC_Mdl_PG_MULPAY_Export::getGmoPaymentIdByCode(MDL_PG_MULPAY_PAYMENT_CREDIT);
    }

    function getGmoTokenPaymentId() {
        return LC_Mdl_PG_MULPAY_Export::getGmoPaymentIdByCode(MDL_PG_MULPAY_PAYMENT_TOKEN);
    }

    function getGmoPaypalPaymentId() {
        return LC_Mdl_PG_MULPAY_Export::getGmoPaymentIdByCode(MDL_PG_MULPAY_PAYMENT_PAYPAL);
    }

    function getGmoNetidPaymentId() {
        return LC_Mdl_PG_MULPAY_Export::getGmoPaymentIdByCode(MDL_PG_MULPAY_PAYMENT_NETID);
    }

    function getGmoAuPaymentId() {
        return LC_Mdl_PG_MULPAY_Export::getGmoPaymentIdByCode(MDL_PG_MULPAY_PAYMENT_AU);
    }

    function getGmoDocomoPaymentId() {
        return LC_Mdl_PG_MULPAY_Export::getGmoPaymentIdByCode(MDL_PG_MULPAY_PAYMENT_DOCOMO);
    }

    function getGmoPaymentIdByCode($code) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        return $objQuery->get('payment_id', 'dtb_payment',
                              'memo01 = ? AND module_code = ? AND del_flg = 0',
                              array($code, 'mdl_pg_mulpay'));
    }

    function getGmoPaymentCode($payment_id) {
        if (!is_numeric($payment_id)) return MDL_PG_MULPAY_PAYMENT_ERROR;

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $result = $objQuery->select('memo01,module_code', 'dtb_payment',
                                    'payment_id = ? AND del_flg = 0',
                                    array($payment_id));

        if (count($result) > 0) {
            $result = $result[0];
            if ($result['module_code'] === 'mdl_pg_mulpay')
                return $result['memo01'];
            else if (!empty($result['module_code']))
                return MDL_PG_MULPAY_PAYMENT_OTHER_MODULE;
            else 
                return MDL_PG_MULPAY_PAYMENT_NOT_MODULE;
        } else {
            return MDL_PG_MULPAY_PAYMENT_ERROR;
        }
    }

    function isGmoPaymentOrNotModuleId($payment_id) {
        switch ($this->getGmoPaymentCode($payment_id)) {
        case MDL_PG_MULPAY_PAYMENT_OTHER_MODULE:
        case MDL_PG_MULPAY_PAYMENT_ERROR:
            return false;
        }
        return true;
    }

    function getAllGmoPayments() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $result = $objQuery->select('*', 'dtb_payment',
                                    '(module_code = ? OR module_code IS NULL) AND del_flg = 0',
                                    array('mdl_pg_mulpay'));
        return $result;
    }

    function isGmoPaymentId($payment_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $result = $objQuery->select('payment_id', 'dtb_payment',
                                    'payment_id = ? AND module_code = ? AND del_flg = 0',
                                    array($payment_id, 'mdl_pg_mulpay'));
        return count($result) > 0;
    }

    function isNotModulePaymentId($payment_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $result = $objQuery->select('payment_id', 'dtb_payment',
                                    'payment_id = ? and module_code IS NULL AND del_flg = 0',
                                    array($payment_id));
        return count($result) > 0;
    }

    function getGmoPaymentIDValueList() {
        $keyname = 'payment_id';
        $valname = 'payment_method';
        $table = 'dtb_payment';
        $where = 'module_code = ?';
        $arrval = array('mdl_pg_mulpay');

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = "$keyname, $valname";
        $objQuery->setwhere("del_flg = 0");
        $objQuery->setorder("rank DESC");
        $arrList = $objQuery->select($col, $table, $where, $arrval);
        $count = count($arrList);
        for($cnt = 0; $cnt < $count; $cnt++) {
            $key = $arrList[$cnt][$keyname];
            $val = $arrList[$cnt][$valname];
            $arrRet[$key] = $val;
        }
        return $arrRet;
    }


    /*
     * 戻り値: 成功 - $responseArray, 失敗 - エラー文字列
     */
    function requestSEARCHTRADE($order_id) {
        // GMO決済モジュールの設定情報を取得する
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array(
                      'ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'OrderID' => $order_id
                      );
        $serverUrl = $objPG->getUserSettings('server_url') . 'SearchTrade.idPass';
        return $this->requestPayment($serverUrl, $data);
    }

    function requestSEARCHTRADEMULTI($order_id, $pay_type) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array(
                      'ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'OrderID' => $order_id,
                      'PayType' => $pay_type
                      );
        $serverUrl = $objPG->getUserSettings('server_url') . 'SearchTradeMulti.idPass';
        return $this->requestPaymentMulti($serverUrl, $data);
    }

    function requestALTERTRAN($job_cd, $amount, $tax, $responseArray) {

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'JobCd' => $job_cd,
                      'Amount' => $amount,
                      'Tax' => $tax,
                      'Method' => $responseArray['Method'],
                      'PayTimes' => $responseArray['PayTimes']
                      );

        $serverUrl = $objPG->getUserSettings('server_url') . 'AlterTran.idPass';
        return $this->requestPayment($serverUrl, $data);
    }

    function requestCHANGETRAN($job_cd, $amount, $responseArray) {

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'JobCd' => $job_cd,
                      'Amount' => $amount,
                      'Tax' => 0
                      );

        $serverUrl = $objPG->getUserSettings('server_url') . 'ChangeTran.idPass';
        return $this->requestPayment($serverUrl, $data);
    }

    function getErrMsg($responseArray){
        $cardStatusError = $this->getCardError();

        $errMsg = $responseArray['ErrCode']." : ".$responseArray['ErrInfo']."\\n";
        $arr1 = strtok($responseArray['ErrCode'], '|');
        $arr2 = strtok($responseArray['ErrInfo'], '|');
        $errMsg = $errMsg.$cardStatusError[$arr1][$arr2][2]."\\n".$cardStatusError[$arr1][$arr2][3]."\\n";
        while ($arr1) {
            $arr1 = strtok('|');
            $arr2 = strtok('|');
            $errMsg = $errMsg.$cardStatusError[$arr1][$arr2][2]."\\n".$cardStatusError[$arr1][$arr2][3]."\\n";
        }
        return $errMsg;    	
    }

    function requestCANCELTRANPAYPAL($order_id, $amount, $responseArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'OrderID' => $order_id,
                      'Amount' => $amount
                      //Tax
                      );

        $serverUrl = $objPG->getUserSettings('server_url') . 'CancelTranPaypal.idPass';
        return $this->requestPaymentMulti($serverUrl, $data);
    }

    function requestPaypalCancel($order_id) {
        $res = $this->requestSEARCHTRADEMULTI($order_id, 5/*MDL_PG_MULPAY_PAYPAL_PAY_TYPE*/);
        if (!is_array($res)) {
            return $res;
        }

        if ($res['Status'] === 'CAPTURE') {
            $res = $this->requestCANCELTRANPAYPAL($order_id, $res['Amount'], $res);
        } else {
            $status = $res['Status'];
            $res = "現在の決済状況がCAPTUREでないためキャンセルできません。(現在状況:$status)";
        }

        return $res;
    }

    function requestCANCELTRANNETID($order_id, $amount, $responseArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'OrderID' => $order_id,
                      'Amount' => $amount
                      //Tax
                      );

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog("CANCELTRANNETID ".print_r($data,true));

        $serverUrl = $objPG->getUserSettings('server_url') . 'CancelTranNetid.idPass';
        return $this->requestPaymentMulti($serverUrl, $data);
    }

    function requestSALESTRANNETID($order_id, $amount, $responseArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'OrderID' => $order_id,
                      'Amount' => $amount
                      //Tax
                      );

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog("SALESTRANNETID ".print_r($data,true));

        $serverUrl = $objPG->getUserSettings('server_url') . 'SalesTranNetid.idPass';
        return $this->requestPaymentMulti($serverUrl, $data);
    }

    function requestCHANGETRANNETID($order_id, $amount, $responseArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'OrderID' => $order_id,
                      'Amount' => $amount
                      //Tax
                      );

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog("CHANGETRANNETID ".print_r($data,true));

        $serverUrl = $objPG->getUserSettings('server_url') . 'ChangeTranNetid.idPass';
        return $this->requestPaymentMulti($serverUrl, $data);
    }

    function requestChangeNetidStatus($order_id, $next_status, $amount = 0) {
        $res = $this->requestSEARCHTRADEMULTI($order_id, 6/*MDL_PG_MULPAY_NETID_PAY_TYPE*/);
        if (!is_array($res)) {
            return $res;
        }

        switch ($next_status) {
        case 'CANCEL':
            $res = $this->requestCANCELTRANNETID($order_id, $res['Amount'], $res);
            break;
        case 'SALES':
            $res = $this->requestSALESTRANNETID($order_id, $res['Amount'], $res);
            break;
        case 'CHANGE':
            $res = $this->requestCHANGETRANNETID($order_id, $amount, $res);
            break;
        default:
            break;
        }

        return $res;
    }

    function requestCANCELTRANAU($order_id, $amount, $responseArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'OrderID' => $order_id,
                      'CancelAmount' => $amount
                      //Tax
                      );

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog("CANCELTRANAU " . print_r($data,true));

        $serverUrl = $objPG->getUserSettings('server_url') . 'AuCancelReturn.idPass';
        return $this->requestPaymentMulti($serverUrl, $data);
    }

    function requestCANCELTRANDOCOMO($order_id, $amount, $responseArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'OrderID' => $order_id,
                      'CancelAmount' => $amount
                      //Tax
                      );

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog("CANCELTRANDOCOMO " . print_r($data,true));

        $serverUrl = $objPG->getUserSettings('server_url') . 'DocomoCancelReturn.idPass';
        return $this->requestPaymentMulti($serverUrl, $data);
    }

    function requestAUSALES($order_id, $amount, $responseArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'OrderID' => $order_id,
                      'Amount' => $amount
                      //Tax
                      );

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog("AUSALES " . print_r($data,true));

        $serverUrl = $objPG->getUserSettings('server_url') . 'AuSales.idPass';
        return $this->requestPaymentMulti($serverUrl, $data);
    }

    function requestDOCOMOSALES($order_id, $amount, $responseArray) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $data = array('ShopID' => $objPG->getUserSettings('shop_id'),
                      'ShopPass' => $objPG->getUserSettings('shop_pass'),
                      'AccessID' => $responseArray['AccessID'],
                      'AccessPass' => $responseArray['AccessPass'],
                      'OrderID' => $order_id,
                      'Amount' => $amount
                      //Tax
                      );

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog("DOCOMOSALES " . print_r($data,true));

        $serverUrl = $objPG->getUserSettings('server_url') . 'DocomoSales.idPass';
        return $this->requestPaymentMulti($serverUrl, $data);
    }

    function requestChangeAuStatus($order_id, $next_status, $amount = 0) {
        $res = $this->requestSEARCHTRADEMULTI($order_id, 8/*MDL_PG_MULPAY_AU_PAY_TYPE*/);
        if (!is_array($res)) {
            return $res;
        }

        // 画面からの金額がなければ、SEARCHしたものを使う
        if ($amount == 0) {
            $amount = $res['Amount'];
        }

        switch ($next_status) {
        case 'CANCEL':
            $res = $this->requestCANCELTRANAU($order_id, $amount, $res);
            break;
        case 'SALES':
            $res = $this->requestAUSALES($order_id, $amount, $res);
            break;
        default:
            break;
        }

        return $res;
    }

    function requestChangeDocomoStatus($order_id, $next_status, $amount = 0) {
        $res = $this->requestSEARCHTRADEMULTI($order_id, 9/*MDL_PG_MULPAY_AU_PAY_TYPE*/);
        if (!is_array($res)) {
            return $res;
        }

        // 画面からの金額がなければ、SEARCHしたものを使う
        if ($amount == 0) {
            $amount = $res['Amount'];
        }

        switch ($next_status) {
        case 'CANCEL':
            $res = $this->requestCANCELTRANDOCOMO($order_id, $amount, $res);
            break;
        case 'SALES':
            $res = $this->requestDOCOMOSALES($order_id, $amount, $res);
            break;
        default:
            break;
        }

        return $res;
    }
    /*
     * GMO決済カスタマイズ
     *
     * 1. GMO決済ならば、他の決済への変更を禁止する。
     * 2. 他の決済から、GMO決済への変更を禁止する。
     */
    function gmoAwareArrPayment($payment_id, $arrPayment) {
        // 商品の追加、変更(mode: select_product_detail)では、$this->arrForm['payment_id']はarrayに設定される。
        // see lfInsertProduct() の $this->arrForm = $this->objFormParam->getFormParamList();
        if (is_array($payment_id)) $payment_id = $payment_id['value'];

        $arrGmoPayment = $this->getGmoPaymentIDValueList();
        if (isset($arrGmoPayment[$payment_id])) {
            // GMO決済なら、自身以外は表示しない。
            return array($payment_id => $arrPayment[$payment_id]);
        } else {
            // GMO決済以外なら、全てのGMO決済を除外する。
            return array_diff_key($arrPayment, $arrGmoPayment);
        }
    }

    function removeOtherPayment($arrPayment) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $result = $objQuery->select('payment_id, module_code', 'dtb_payment');

        foreach ($result as $val) {
            if (!empty($val['module_code']) && $val['module_code'] != 'mdl_pg_mulpay') {
                foreach ($arrPayment as $key => $pay) {
                    if ($pay['payment_id'] == $val['payment_id']) {
                        unset($arrPayment[$key]);
                    }
                }
            }
        }

        return $arrPayment;
    }

    function getNextCancelStatus($cur_status, $proc_date) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $today = date('Ymd');
        $today_start = $today.'000000';
        $today_end = $today.'235959';
        if ($today_start <= $proc_date && $proc_date <= $today_end)  { // YYYYMMDDHHmmssで比較
            // 当日内 取消
            $objPG->printLog("nextCancelStatus0: VOID: $cur_status, $today_start <= $proc_date <= $today_end");
            return 'VOID';
        }

        $year = substr($today, 0, 4);
        $month = substr($today, 4, 2);
        $month_start = $year.$month.'01'; // 当月1日
        $month_end = $this->getMonthEndDay((int)$year, (int)$month); // 当月末日
        $proc_day = substr($proc_date, 0, 8);
        if ($month_start <= $proc_day && $proc_day <= $month_end) { // YYYYMMDDで比較
            // 当月内 返品
            $objPG->printLog("nextCancelStatus1: RETURN: $cur_status, $proc_date, $month_start <= $proc_day <= $month_end");
            return 'RETURN';
        }

        $proc_year = substr($proc_date, 0, 4);
        $proc_month = substr($proc_date, 4, 2);
        $proc_day = substr($proc_date, 6, 2);
        $proc_after180 = $this->computeDate((int)$proc_year, (int)$proc_month, (int)$proc_day, 180);
        if ($today <= $proc_after180) { // YYYYMMDDで比較
            // 処理日から180日以内

            // 仮売上と簡易オーソリは返品、それ以外は月跨返品
            $nextStatus = ($cur_status == 'AUTH' || $cur_status == 'SAUTH')
                ? 'RETURN'   // 返品
                : 'RETURNX'; // 月跨返品

            $objPG->printLog("nextCancelStatus2: $nextStatus: $cur_status, $proc_date, $today <= $proc_after180");
            return $nextStatus;
        }

        return false; // エラー
    }
    
    function getMonthEndDay($year, $month) {
        // mktime関数で日付を0にすると前月の末日を指定したことになる
        // $month + 1 をしていますが、結果13月のような値になっても自動で補正される
        $dt = mktime(0, 0, 0, $month + 1, 0, $year);
        return date('Ymd', $dt);
    }

    function computeDate($year, $month, $day, $addDays) {
        $baseSec = mktime(0, 0, 0, $month, $day, $year); // 基準日を秒で取得
        $addSec = $addDays * 86400; // 日数×1日の秒数
        $targetSec = $baseSec + $addSec;
        return date('Ymd', $targetSec);
    }

    function customizeShoppingComplate(&$objPage) {
        if (isset($_SESSION['mdl_pg_mulpay_complete_order_id'])
            && !SC_Utils_Ex::isBlank($_SESSION['mdl_pg_mulpay_complete_order_id'])
            && SC_Utils_Ex::sfIsInt($_SESSION['mdl_pg_mulpay_complete_order_id'])) {

            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $arrResults = $objQuery->getAll("SELECT memo02 FROM dtb_order WHERE order_id = ? ", array($_SESSION['mdl_pg_mulpay_complete_order_id']));
            if (count($arrResults) > 0) {
                if (isset($arrResults[0]["memo02"])) {
                    $arrOther = unserialize($arrResults[0]["memo02"]);

                    LC_Mdl_PG_MULPAY::printLog("show complete page: ".print_r($arrOther,true));

                    foreach($arrOther as $key => $val){
                        if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $val["value"])) {
                            $arrOther[$key]["value"] = "<a href='#' onClick=\"window.open('". $val["value"] . "'); \" >" . $val["value"] ."</a>";
                        }
                    }

                    $objPage->arrOther = $arrOther;
                }
            }
        }

        unset($_SESSION['MDL_PG_MULPAY']);
        unset($_SESSION['mdl_pg_mulpay_complete_order_id']);
        unset($_SESSION['order_id']);
    }

    function customizeShoppingPayment(&$arrForm) {
        // GMOPGモジュール決済以外では、2click用決済情報を確実にクリアする。
        $arrForm['memo06'] = '';
    }

    function customizeMypageDeliveryAddr(&$objPage) {
        // 配送先設定画面から2クリックフローへ復帰できるようにする。
        $objPage->validUrl[] = ROOT_URLPATH . 'twoClick/deliv.php';
    }

    function customizeCartAction(&$objPage) {
        // カートのテンプレートを2クリック対応版に変更する。
        $objPage->tpl_mainpage = 'twoClick/cart_index.tpl';

        // 機能が有効ならば、2クリック購入ボタンを表示する。
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPage->isEnable2click = $objPG->isEnable2click();

        if ($objPage->isEnable2click
            && ($objPage->getMode() == 'mdl_pg_mulpay_2click' ||
                (SC_MobileUserAgent::isMobile() && isset($_POST['mdl_pg_mulpay_2click']))))
        {
            // 2クリック決済処理を実行する。
            LC_Mdl_PG_MULPAY_Export::customizeCart2ClickAction($objPage);

            // FALLTHROUGH エラー時不正なモードはカート画面のまま
            $_POST['mode'] = 'confirm';
        }

        // 通常フローの開始
        unset($_SESSION['mdl_pg_mulpay']['2click']);
    }

    /**
     * 2クリック決済処理
     */
    function customizeCart2ClickAction(&$objPage) {
        // LC_Page_Cartのmode=confirmと同一の処理を実行後、
        // 2クリック決済確認画面へ遷移する。

        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();

        $objFormParam = $objPage->lfInitParam($_REQUEST);
        $objPage->mode = $objPage->getMode();

        $objPage->cartKeys = $objCartSess->getKeys();
        foreach ($objPage->cartKeys as $key) {
            // 商品購入中にカート内容が変更された。
            if($objCartSess->getCancelPurchase($key)) {
                $objPage->tpl_message = "商品購入中にカート内容が変更されましたので、お手数ですが購入手続きをやり直して下さい。";
            }
        }
        $objPage->cartItems =& $objCartSess->getAllCartList();

        $cart_no = $objFormParam->getValue('cart_no');
        $cartKey = $objFormParam->getValue('cartKey');


        // カート内情報の取得
        $cartList = $objCartSess->getCartList($cartKey);
        // カート商品が1件以上存在する場合
        if(count($cartList) > 0) {
            // カートを購入モードに設定
            $objPage->lfSetCurrentCart($objSiteSess, $objCartSess, $cartKey);

            // 2クリック決済フローの開始フラグ
            $_SESSION['mdl_pg_mulpay']['2click'] = true;

            // 2クリック決済確認画面へ
            define('MDL_PG_MULPAY_2CLICK_SHOPPING_URL', HTTPS_URL . "twoClick/" . DIR_INDEX_PATH);
            SC_Response_Ex::sendRedirect(MDL_PG_MULPAY_2CLICK_SHOPPING_URL);
            exit;
        }
    }

    function customizeGetPaymentsByPrice($arrPayment) {
        //モバイルなら、PayPal決済を除外する
        $paypalPaymentId = LC_Mdl_PG_MULPAY_Export::getGmoPaypalPaymentId();
        if (Net_UserAgent_Mobile::isMobile() && !empty($paypalPaymentId)) {
            for ($i = 0; $i < count($arrPayment); $i++) {
                $data = $arrPayment[$i];
                if ($data['payment_id'] == $paypalPaymentId) {
                    array_splice($arrPayment, $i, 1);
                    break;
                }
            }
        }

        //docomoでないなら、iD決済を除外する。
        $netidPaymentId = LC_Mdl_PG_MULPAY_Export::getGmoNetidPaymentId();
        if (!Net_UserAgent_Mobile::isDoCoMo() && !empty($netidPaymentId)) {
            for ($i = 0; $i < count($arrPayment); $i++) {
                $data = $arrPayment[$i];
                if ($data['payment_id'] == $netidPaymentId) {
                    array_splice($arrPayment, $i, 1);
                    break;
                }
            }
        }

        return $arrPayment;
    }

    function customizeCompleteOrder($orderStatus) {
        // GMOPGモジュール決済の場合、使用ポイントを復元する。
        // 決済成功時に改めて使用ポイントを減算する。

        $objPurchase = new SC_Helper_Purchase_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $order_id = $_SESSION["order_id"];

        $arrOrder = $objPurchase->getOrder($order_id);
        $payment_id = $arrOrder['payment_id'];

        if (MDL_PG_MULPAY_ROLLBACK_USE_POINT &&
            $arrOrder['use_point'] > 0 && LC_Mdl_PG_MULPAY_Export::isGmoPaymentId($payment_id))
        {
            LC_Mdl_PG_MULPAY::printLog("rollback point: ".$arrOrder['use_point']);

            $sqlval['update_date'] = 'Now()';
            $arrRawSql['point'] = 'point + ?';
            $arrRawSqlVal[] = $arrOrder['use_point'];
            $where = 'customer_id = ?';
            $arrVal[] = $arrOrder['customer_id'];
            $objQuery->update('dtb_customer', $sqlval, $where, $arrVal, $arrRawSql, $arrRawSqlVal);
        }
    }

    function customizePageAdminInit(&$objPage) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPage->gmopg_enableCardStatusChange = $objPG->isEnableCardStatusChangeFunction();
        $objPage->gmopg_enablePaypal = $objPG->isEnablePaypal();
        $objPage->gmopg_enableNetid = $objPG->isEnableNetid();
        $objPage->gmopg_enableAu = $objPG->isEnableAu();
        $objPage->gmopg_enableDocomo = $objPG->isEnableDocomo();
        $objPage->gmopg_enableUseLimit = $objPG->isEnableUseLimit();
    }

    function customizePageAdminOrderEditInit(&$objPage) {
        $objPage->objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();

        if ($objPage->gmopg_enableCardStatusChange) {
            $objPage->tpl_mainpage = $obj_LC_Mdl_PG_MULPAY_Export->getTplDirPath() . 'admin/order/edit.tpl';

            $objPage->cardStatusMaster = $obj_LC_Mdl_PG_MULPAY_Export->getCardStatus();
            $objPage->cardStatusChange = $obj_LC_Mdl_PG_MULPAY_Export->getCardStatusChange();

            $objPage->gmoCreditPaymentId = $obj_LC_Mdl_PG_MULPAY_Export->getGmoCreditPaymentId();
            $objPage->gmoTokenPaymentId = $obj_LC_Mdl_PG_MULPAY_Export->getGmoTokenPaymentId();
            $objPage->gmoPaypalPaymentId = $obj_LC_Mdl_PG_MULPAY_Export->getGmoPaypalPaymentId();
            $objPage->gmoNetidPaymentId = $obj_LC_Mdl_PG_MULPAY_Export->getGmoNetidPaymentId();
            $objPage->gmoAuPaymentId = $obj_LC_Mdl_PG_MULPAY_Export->getGmoAuPaymentId();
            $objPage->gmoDocomoPaymentId = $obj_LC_Mdl_PG_MULPAY_Export->getGmoDocomoPaymentId();
        }
    }

    function customizePageAdminOrderEditAction(&$objPage) {
        // GMO決済カスタマイズ 支払方法を制御
        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();
        $payment_id = $objPage->arrForm['payment_id']['value'];
        $objPage->arrPayment = $obj_LC_Mdl_PG_MULPAY_Export->gmoAwareArrPayment($payment_id, $objPage->arrPayment);

        // ステータス表示
        $cur_status = $objPage->arrForm['memo04']['value'];

        // クレジット
        if (!empty($cur_status) && $obj_LC_Mdl_PG_MULPAY_Export->isGmoCreditPaymentId($payment_id)) {
            $objPage->currCardStatus = $objPage->cardStatusMaster[$cur_status];
            $objPage->arrCardStatus = $objPage->cardStatusChange[$cur_status];
        } else {
            $objPage->currCardStatus = $objPage->cardStatusMaster['BEFORE'];
            $objPage->arrCardStatus = $objPage->cardStatusChange['BEFORE'];
        }
        
        // Paypal
        if ($obj_LC_Mdl_PG_MULPAY_Export->isGmoPaypalPaymentId($payment_id)) {
            $paypalStatus = $obj_LC_Mdl_PG_MULPAY_Export->getPaypalStatusArray();
            $objPage->currPaypalStatus = $cur_status;
            $objPage->currPaypalStatusString = $paypalStatus[$cur_status];
        }

        // Netid
        if ($obj_LC_Mdl_PG_MULPAY_Export->isGmoNetidPaymentId($payment_id)) {
            $netidStatus = $obj_LC_Mdl_PG_MULPAY_Export->getNetidStatusArray();
            $objPage->currNetidStatus = $cur_status;
            $objPage->currNetidStatusString = $netidStatus[$cur_status];
            $netidStatusChange = $obj_LC_Mdl_PG_MULPAY_Export->getNetidStatusChangeArray2();
            $objPage->arrNetidStatus = $netidStatusChange[$cur_status];
        }

        // Au
        if ($obj_LC_Mdl_PG_MULPAY_Export->isGmoAuPaymentId($payment_id)) {
            $auStatus = $obj_LC_Mdl_PG_MULPAY_Export->getAuStatusArray();
            $objPage->currAuStatus = $cur_status;
            $objPage->currAuStatusString = $auStatus[$cur_status];
            $auStatusChange = $obj_LC_Mdl_PG_MULPAY_Export->getAuStatusChangeArray2();
            $objPage->arrAuStatus = $auStatusChange[$cur_status];
        }

        // Docomo
        if ($obj_LC_Mdl_PG_MULPAY_Export->isGmoDocomoPaymentId($payment_id)) {
            $docomoStatus = $obj_LC_Mdl_PG_MULPAY_Export->getAuStatusArray();
            $objPage->currDocomoStatus = $cur_status;
            $objPage->currDocomoStatusString = $docomoStatus[$cur_status];
            $docomoStatusChange = $obj_LC_Mdl_PG_MULPAY_Export->getDocomoStatusChangeArray2();
            $objPage->arrDocomoStatus = $docomoStatusChange[$cur_status];
        }
    }

    function customizePageAdminOrderEditInitParam(&$objFormParam) {
        // DB読込用
        $objFormParam->addParam("memo04", "memo04");
        $objFormParam->addParam("memo05", "memo05");
        // 変更操作
        $objFormParam->addParam("gmo_credit_next_status", "gmo_credit_next_status");
        $objFormParam->addParam("gmo_credit_change_status", "gmo_credit_change_status");
        $objFormParam->addParam("gmo_paypal_next_status", "gmo_paypal_next_status");
        $objFormParam->addParam("gmo_netid_next_status", "gmo_netid_next_status");
        $objFormParam->addParam("gmo_au_next_status", "gmo_au_next_status");
        $objFormParam->addParam("gmo_docomo_next_status", "gmo_docomo_next_status");
    }

    function customizePageAdminOrderEditCheckError(&$objPage, &$objFormParam, $arrErr) {
        if (!$objPage->gmopg_enableCardStatusChange)
            return $arrErr;

        $payment_id = $objFormParam->getValue('payment_id');
        $next_status = $objFormParam->getValue('gmo_credit_next_status');
        $change_status = $objFormParam->getValue('gmo_credit_change_status');

        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();
        if ($next_status == "CHANGE" && empty($change_status)
            && $obj_LC_Mdl_PG_MULPAY_Export->isGmoCreditPaymentId($payment_id))
        {
            $arrErr['gmo_credit_change_status'] = "金額変更後のカード決済状況を選択してください。";	
        }

        return $arrErr;
    }

    function customizePageAdminOrderEditDoRegister(&$objPage, $order_id, &$objPurchase, &$objFormParam, &$message) {
        if ($order_id != null) { // 更新
            $payment_id = $objFormParam->getValue('payment_id');

            // credit,token,netid,au以外での金額変更を禁止する。
            if (LC_Mdl_PG_MULPAY_Export::isGmoPaymentId($payment_id) &&
                $payment_id !== $objPage->gmoCreditPaymentId &&
                $payment_id !== $objPage->gmoTokenPaymentId &&
                $payment_id !== $objPage->gmoNetidPaymentId &&
                $payment_id !== $objPage->gmoAuPaymentId &&
                $payment_id !== $objPage->gmoDocomoPaymentId &&
                LC_Mdl_PG_MULPAY_Export::gmopg_changeAmount($order_id, $objPurchase, $objFormParam))
            {
                $errmsg = "このGMO-PG決済の金額を変更することはできません。";
            } else if ($objPage->gmopg_enableCardStatusChange &&
                       ($payment_id === $objPage->gmoCreditPaymentId ||
                        $payment_id === $objPage->gmoTokenPaymentId)) {
                $errmsg = LC_Mdl_PG_MULPAY_Export::changeCreditState($objPage, $order_id, $objPurchase, $objFormParam);
            } else if ($payment_id === $objPage->gmoPaypalPaymentId) {
                $errmsg = LC_Mdl_PG_MULPAY_Export::changePaypalState($objPage, $order_id, $objPurchase, $objFormParam);
            } else if ($payment_id === $objPage->gmoNetidPaymentId) {
                $errmsg = LC_Mdl_PG_MULPAY_Export::changeNetidState($objPage, $order_id, $objPurchase, $objFormParam);
            } else if ($payment_id === $objPage->gmoAuPaymentId) {
                $errmsg = LC_Mdl_PG_MULPAY_Export::changeAuState($objPage, $order_id, $objPurchase, $objFormParam);
            } else if ($payment_id === $objPage->gmoDocomoPaymentId) {
                $errmsg = LC_Mdl_PG_MULPAY_Export::changeDocomoState($objPage, $order_id, $objPurchase, $objFormParam);
            }

            if (!empty($errmsg)) {
                $message = $errmsg;
                return -1; // エラー
            }
        }

        return 1;
    }

    function gmopg_changeAmount($order_id, &$objPurchase, &$objFormParam) {
        // POSTデータ
        $payment_total = $objFormParam->getValue('payment_total');
        // データベースからの取得データ
        $arrOrder = $objPurchase->getOrder($order_id);

        $cur_payment_id = $arrOrder['payment_id'];
        $cur_payment_total = $arrOrder['payment_total'];

        if ($cur_payment_total != $payment_total) {
            LC_Mdl_PG_MULPAY::printLog("GMO-PGの決済金額は変更できません: order_id:$order_id, payment_id:$cur_payment_id, $cur_payment_total -> $payment_total");
            return true;
        }

        return false;
    }

    function changeCreditState($objPage, $order_id, &$objPurchase, &$objFormParam) {
        // POSTデータ
        $next_status = $objFormParam->getValue('gmo_credit_next_status');
        $change_status = $objFormParam->getValue('gmo_credit_change_status');
        $payment_id = $objFormParam->getValue('payment_id');
        $payment_total = $objFormParam->getValue('payment_total');
        // データベースからの取得データ
        $arrOrder = $objPurchase->getOrder($order_id);
	// SEARCHTRADEからの取得データ
	$tax = 0;

        $cur_status = $arrOrder['memo04']; // 現在の状態
        $cur_payment_id = $arrOrder['payment_id'];
        $cur_payment_total = $arrOrder['payment_total'];

        // OK 処理を続行する。
        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();
        if ($next_status != '') {
            $res = $obj_LC_Mdl_PG_MULPAY_Export->requestSEARCHTRADE($order_id);
            if (!is_array($res)) {
                return $res;
            }

            if ($next_status == "CHANGE") { // 金額変更
                $memo04 = $change_status;
                $res = $obj_LC_Mdl_PG_MULPAY_Export->requestCHANGETRAN($memo04, $payment_total, $res);
            } else { // 状態変更
                $memo04 = $next_status;
                if ($next_status == 'CANCEL') {
                    $cur_state = $res['Status'];
                    $proc_date = $res['ProcessDate'];

                    $memo04 = $obj_LC_Mdl_PG_MULPAY_Export->getNextCancelStatus($cur_state, $proc_date);
                    if (!$memo04) {
                        return "受注番号$order_idはキャンセルできません。";
                    }

		    // cancelの時のみSEARCHTRADEで取得した金額を用いる。
		    $payment_total = $res['Amount'];
		    $tax = $res['Tax'];
                }
                $res = $obj_LC_Mdl_PG_MULPAY_Export->requestALTERTRAN($memo04, $payment_total, $tax, $res);
            }
            if (!is_array($res)) {
                return $res;
            }
        } else {
	    if ($payment_total != $cur_payment_total) {
	        return "エラー: 合計金額が変更されています。【クレジット決済状況変更】を選択して下さい。";
	    }

            $memo04 = $cur_status; // 変更しない
        }

        // DBに反映
        $objFormParam->setValue('memo04', $memo04);

        return ""; // 成功
    }

    function isCancelState($state) {
        return $state == 'VOID' || $state == 'RETURN' || $state == 'RETURNX' || $state == 'CANCEL' || $state == 'SAUTH';
    }

    function isAuthState($state) {
        return $state == 'AUTH' || $state == 'CAPTURE';
    }

    function isReAuth($cur_status, $memo04) {
        return LC_Mdl_PG_MULPAY_Export::isCancelState($cur_status) && LC_Mdl_PG_MULPAY_Export::isAuthState($memo04);
    }

    function changePaypalState(&$objPage, $order_id, &$objPurchase, &$objFormParam) {
        if ($objFormParam->getValue('gmo_paypal_next_status') === 'CANCEL') {
            $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();
            $res = $obj_LC_Mdl_PG_MULPAY_Export->requestPaypalCancel($order_id);
            if (!is_array($res)) {
                $res = "PayPalキャンセル操作でエラーが発生しました:$res";
                return $res;
            }

            // 対応状況は変更しない
            //$objFormParam->setValue('status', ORDER_CANCEL);

            $objFormParam->setValue('memo04', 'CANCEL');

            LC_Mdl_PG_MULPAY::printLog("paypal cancel: " . print_r($res, true));
        }

        return ""; // 成功
    }

    function changeNetidState(&$objPage, $order_id, &$objPurchase, &$objFormParam) {
        $next_status = $objFormParam->getValue('gmo_netid_next_status');
        $payment_id = $objFormParam->getValue('payment_id');
        $payment_total = $objFormParam->getValue('payment_total');

        // データベースからの取得データ
        $arrOrder = $objPurchase->getOrder($order_id);
        $cur_status = $arrOrder['memo04']; // 現在の状態
        $cur_payment_id = $arrOrder['payment_id'];
        $cur_payment_total = $arrOrder['payment_total'];

        LC_Mdl_PG_MULPAY::printLog("changeNetidState: next:$next_status payment_total:$payment_total cur_payment_total:$cur_payment_total");

        if ($next_status != 'CHANGE' && $payment_total != $cur_payment_total) {
            return 'エラー: 「金額変更」を選択して下さい';
        } else if ($next_status == 'CHANGE' && $payment_total == $cur_payment_total) {
            return 'エラー: 金額が変更されていません';
        }

        switch ($next_status) {
        case 'CANCEL':
        case 'SALES':
        case 'CHANGE':
            break;
        default:
            return ""; // 処理対象外
        }

        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();
        $res = $obj_LC_Mdl_PG_MULPAY_Export->requestChangeNetidStatus($order_id, $next_status, $payment_total);
        if (!is_array($res)) {
            $res = "iDステータス変更操作でエラーが発生しました:$res";
            return $res;
        }

        if ($next_status != 'CHANGE') {
            $objFormParam->setValue('memo04', $next_status);
        } else {
            // 金額変更の選択をクリアする
            $objFormParam->setValue('gmo_netid_next_status', '');
        }

        return ""; // 成功
    }

    function changeAuState(&$objPage, $order_id, &$objPurchase, &$objFormParam) {
        $next_status = $objFormParam->getValue('gmo_au_next_status');
        $payment_id = $objFormParam->getValue('payment_id');
        $payment_total = $objFormParam->getValue('payment_total');

        // データベースからの取得データ
        $arrOrder = $objPurchase->getOrder($order_id);
        $cur_status = $arrOrder['memo04']; // 現在の状態
        $cur_payment_id = $arrOrder['payment_id'];
        $cur_payment_total = $arrOrder['payment_total'];

        LC_Mdl_PG_MULPAY::printLog("changeAuState: next:$next_status payment_total:$payment_total cur_payment_total:$cur_payment_total");

        /*
         * SALES, CANCEL共にAUTH時より少い金額(減額)で実行できる。
         * 0円はエラーとする。
         * CANCEL時は、変更前金額 - 変更後金額 = キャンセル金額を計算する。
         */

        if ($cur_payment_total == 0) {
            return 'エラー: 金額0円は不正です。金額は、1円以上からオーソリ時金額以下の値を設定して下さい。';
        }
        if ($cur_payment_total < $payment_total) {
            return 'エラー: オーソリ時以上の金額を指定できません。金額は、1円以上からオーソリ時金額以下の値を設定して下さい。';
        }
        
        switch ($next_status) {
        case 'CANCEL':
            if ($payment_total < $cur_payment_total) {
                // 減額分をCancelAmountパラメータに設定する。
                $payment_total = $cur_payment_total - $payment_total;
            }
            break;
        case 'SALES':
            // 実売上は減額後の値をそのまま設定する。
            break;
        default:
            return ""; // 処理対象外
        }

        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();
        $res = $obj_LC_Mdl_PG_MULPAY_Export->requestChangeAuStatus($order_id, $next_status, $payment_total);
        if (!is_array($res)) {
            $res = "auステータス変更操作でエラーが発生しました:$res";
            return $res;
        }

        // キャンセルの場合、AuCancelReturn.idPassのStatusパラメータが
        // CANCEL or RETURNになるので、それを次状態とする。
        $next_status = $res['Status'];

        $objFormParam->setValue('memo04', $next_status);

        return ""; // 成功
    }

    function changeDocomoState(&$objPage, $order_id, &$objPurchase, &$objFormParam) {
        $next_status = $objFormParam->getValue('gmo_docomo_next_status');
        $payment_id = $objFormParam->getValue('payment_id');
        $payment_total = $objFormParam->getValue('payment_total');

        // データベースからの取得データ
        $arrOrder = $objPurchase->getOrder($order_id);
        $cur_status = $arrOrder['memo04']; // 現在の状態
        $cur_payment_id = $arrOrder['payment_id'];
        $cur_payment_total = $arrOrder['payment_total'];

        LC_Mdl_PG_MULPAY::printLog("changeDocomoState: next:$next_status payment_total:$payment_total cur_payment_total:$cur_payment_total");

        /*
         * SALES, CANCEL共にAUTH時より少い金額(減額)で実行できる。
         * 0円はエラーとする。
         * CANCEL時は、変更前金額 - 変更後金額 = キャンセル金額を計算する。
         */
        if ($cur_payment_total == 0) {
            return 'エラー: 金額0円は不正です。金額は、1円以上からオーソリ時金額以下の値を設定して下さい。';
        }
        if ($cur_payment_total < $payment_total) {
            return 'エラー: オーソリ時以上の金額を指定できません。金額は、1円以上からオーソリ時金額以下の値を設定して下さい。
';
        }

        // 決済翌日12時以降出ないと返品は不可
        // 当日の扱いは20:00まで
        // 2.11モジュールは、create_dateしか残らない為、こちらで判断（正確性は低い)
        if ($next_status == 'CANCEL') {
            if (date('H') >= 12) { // 12時以降？
                $to = strtotime('-1 days', date('Y/m/d 20:00:00'));
            } else {
                $to = strtotime('-2 days', date('Y/m/d 20:00:00'));
            }
            $target = strtotime($arrOrder['create_date']);
            if ($target > $to) {
                return 'エラー： キャンセルは翌日の12:00以降から可能です。当日の扱いは20:00までの取引です。';
            }
        }

        switch ($next_status) {
        case 'CANCEL':
            if ($payment_total < $cur_payment_total) {
                // 減額分をCancelAmountパラメータに設定する。
                $payment_total = $cur_payment_total - $payment_total;
            }
            break;
        case 'SALES':
            // 実売上は減額後の値をそのまま設定する。
            break;
        default:
            return ""; // 処理対象外
        }
        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();
        $res = $obj_LC_Mdl_PG_MULPAY_Export->requestChangeDocomoStatus($order_id, $next_status, $payment_total);
        if (!is_array($res)) {
            $res = "ドコモステータス変更操作でエラーが発生しました:$res";
            return $res;
        }

        // キャンセルの場合、DocomoCancelReturn.idPassのStatusパラメータが
        // CANCEL or RETURNになるので、それを次状態とする。
        $next_status = $res['Status'];

        $objFormParam->setValue('memo04', $next_status);

        return ""; // 成功
    }

}
?>
