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
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'client/LC_Mdl_PG_MULPAY_Client_Entry.php');
/**
 * EntryTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Entry_Token extends LC_Mdl_PG_MULPAY_Client_Entry {
    /**
     * EntryTranリクエストを送信する
     *
     * @param array $arrData 受注情報
     */
    function request($arrData) {
        $objDB = new SC_Helper_DB_Ex;
        $arrInfo = $objDB->sfGetBasisData();

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        $serverUrl = $objPG->getUserSettings('server_url') . 'EntryTran.idPass';
        $shop_name = mb_convert_encoding($objPG->getUserSettings("3d_shop_name"), "eucJP-win", "UTF-8");
        $tdTenantName = '';
        for($i = 1; $i <= mb_strlen($shop_name); $i++){
            $tmp = mb_substr($shop_name, 0, $i);
            if(strlen(base64_encode($tmp)) <= 25){
                $tdTenantName = $tmp;
            }else{
                break;
            }
        }

        $arrSendData = array(
            'ShopID'   => $objPG->getUserSettings('shop_id'),  // ショップID
            'ShopPass' => $objPG->getUserSettings('shop_pass'),// ショップパスワード
            'OrderID'  => $arrData['order_id'],                // 店舗ごとに一意な注文IDを送信する.
            'JobCd'    => $objPG->getJobCd(),                  // 処理区分
            'TdFlag'   => $objPG->isEnable3DSecure() ? '1' : '',   // 3D認証をする場合は1
            'TdTenantName' => base64_encode($tdTenantName), // 3D認証画面で表示する店舗名
        );

        // 有効性チェックの場合、金額と消費税をパラメータに含めない
        if ($arrSendData['JobCd'] !== 'CHECK') {
            $arrSendData['Amount'] = $arrData['payment_total']; // 金額
            $arrSendData['Tax'] = '0';                          // 消費税
        }

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

        // 正常に処理されれば、AccessID, AccessPassが返る。
        $ret = $objReq->getResponseBody();
        $this->parse($ret);

        // EntryTranを再実行すべきか判定するために、
        // (オーダーID,AccessID,AccessPass)の組を記録しておく
        $this->setEntryTranResults($arrData['order_id']);
    }
}
?>
