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
 * コンビニ決済決済 ExecTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Exec_Conveni extends LC_Mdl_PG_MULPAY_Client_Exec {
    /**
     * 旧コンビニコードを新コンビニコードに変換する
     *
     * @return string 新コンビニコード
     */
    function convOld2NewCVSCD($code) {
        $r = $code;

        switch ($r) {
        case CONVENI_LOSON:
            $r = CONVENI_LOSON_NEW;
            break;

        case CONVENI_FAMILYMART:
            $r = CONVENI_FAMILYMART_NEW;
            break;

        case CONVENI_MINISTOP:
            $r = CONVENI_MINISTOP_NEW;
            break;

        case CONVENI_SUNKUS:
            $r = CONVENI_SUNKUS_NEW;
            break;

        case CONVENI_CIRCLEK:
            $r = CONVENI_CIRCLEK_NEW;
            break;

        default:
            break;
        }

        return $r;
    }

    /**
     * 新コンビニコードを旧コンビニコードに変換する
     *
     * @return string 旧コンビニコード
     */
    function convNew2OldCVSCD($code) {
        $r = $code;

        switch ($r) {
        case CONVENI_LOSON_NEW:
            $r = CONVENI_LOSON;
            break;

        case CONVENI_FAMILYMART_NEW:
            $r = CONVENI_FAMILYMART;
            break;

        case CONVENI_MINISTOP_NEW:
            $r = CONVENI_MINISTOP;
            break;

        case CONVENI_SUNKUS_NEW:
            $r = CONVENI_SUNKUS;
            break;

        case CONVENI_CIRCLEK_NEW:
            $r = CONVENI_CIRCLEK;
            break;

        default:
            break;
        }

        return $r;
    }

    /**
     * リクエストパラメータを取得する
     *
     * @return array リクエストパラメータ
     */
    function getSendRequestParam($arrEntryRet, $objPage) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $objPG->printLog('-> exec tran conveni request start');
        // コンビニコード
        $convenience = $this->convOld2NewCVSCD($objPage->arrForm['conveni']);
        // 氏名
        $customerName = $objPage->arrData['order_name01'] . "　" . $objPage->arrData['order_name02'];
        // フリガナ
        $customerKana = $objPage->arrData['order_kana01'] . "　" . $objPage->arrData['order_kana02'];
        $customerKana = mb_convert_kana($customerKana, 'AKV', 'UTF-8');
        // 電話番号
        $telNo = $objPage->arrData['order_tel01'] . "-" . $objPage->arrData['order_tel02'] . "-" . $objPage->arrData['order_tel03'];
        // POSレジ表示欄
        $registerDisp1 = $objPG->getUserSettings('conveni_RegisterDisp1');
        $registerDisp2 = $objPG->getUserSettings('conveni_RegisterDisp2');
        $registerDisp3 = $objPG->getUserSettings('conveni_RegisterDisp3');
        $registerDisp4 = $objPG->getUserSettings('conveni_RegisterDisp4');
        $registerDisp5 = $objPG->getUserSettings('conveni_RegisterDisp5');
        $registerDisp6 = $objPG->getUserSettings('conveni_RegisterDisp6');
        $registerDisp7 = $objPG->getUserSettings('conveni_RegisterDisp7');
        $registerDisp8 = $objPG->getUserSettings('conveni_RegisterDisp8');
        // レシート表示欄
        $receiptsDisp1 = $objPG->getUserSettings('conveni_ReceiptsDisp1');
        $receiptsDisp2 = $objPG->getUserSettings('conveni_ReceiptsDisp2');
        $receiptsDisp3 = $objPG->getUserSettings('conveni_ReceiptsDisp3');
        $receiptsDisp4 = $objPG->getUserSettings('conveni_ReceiptsDisp4');
        $receiptsDisp5 = $objPG->getUserSettings('conveni_ReceiptsDisp5');
        $receiptsDisp6 = $objPG->getUserSettings('conveni_ReceiptsDisp6');
        $receiptsDisp7 = $objPG->getUserSettings('conveni_ReceiptsDisp7');
        $receiptsDisp8 = $objPG->getUserSettings('conveni_ReceiptsDisp8');
        $receiptsDisp9 = $objPG->getUserSettings('conveni_ReceiptsDisp9');
        $receiptsDisp10 = $objPG->getUserSettings('conveni_ReceiptsDisp10');
        // お問合せ先
        $receiptsDisp11 = $objPG->getUserSettings('conveni_ReceiptsDisp11');
        // お問合せ先電話番号
        $arrReceiptsDisp12 = array($objPG->getUserSettings('conveni_ReceiptsDisp12_1'),
                                   $objPG->getUserSettings('conveni_ReceiptsDisp12_2'),
                                   $objPG->getUserSettings('conveni_ReceiptsDisp12_3'));
        $receiptsDisp12 = implode('-', $arrReceiptsDisp12);
        if (strlen($receiptsDisp12) > 12) {
            $receiptsDisp12 = implode('', $arrReceiptsDisp12);
        }

        // お問合せ先受付時間
        $receiptsDisp13 = $objPG->getUserSettings('conveni_ReceiptsDisp13_1') . ":" .
                          $objPG->getUserSettings('conveni_ReceiptsDisp13_2') . "-" .
                          $objPG->getUserSettings('conveni_ReceiptsDisp13_3') . ":" .
                          $objPG->getUserSettings('conveni_ReceiptsDisp13_4');
        // 加盟店自由項目
        $clientField1 = $objPG->getUserSettings('conveni_ClientField1');
        $clientField2 = $objPG->getUserSettings('conveni_ClientField2');

        $arrSendData = array(
            'AccessID'        => trim($arrEntryRet['AccessID']),
            'AccessPass'      => trim($arrEntryRet['AccessPass']),
            'OrderID'         => $objPage->arrData['order_id'],
            'Convenience'     => $convenience,
            'CustomerName'    => $customerName,
            'CustomerKana'    => $customerKana,
            'TelNo'           => $telNo,
            'ReserveNo'       => $objPage->arrData['order_id'],
            'RegisterDisp1'   => $registerDisp1,
            'RegisterDisp2'   => $registerDisp2,
            'RegisterDisp3'   => $registerDisp3,
            'RegisterDisp4'   => $registerDisp4,
            'RegisterDisp5'   => $registerDisp5,
            'RegisterDisp6'   => $registerDisp6,
            'RegisterDisp7'   => $registerDisp7,
            'RegisterDisp8'   => $registerDisp8,
            'ReceiptsDisp1'   => $receiptsDisp1,
            'ReceiptsDisp2'   => $receiptsDisp2,
            'ReceiptsDisp3'   => $receiptsDisp3,
            'ReceiptsDisp4'   => $receiptsDisp4,
            'ReceiptsDisp5'   => $receiptsDisp5,
            'ReceiptsDisp6'   => $receiptsDisp6,
            'ReceiptsDisp7'   => $receiptsDisp7,
            'ReceiptsDisp8'   => $receiptsDisp8,
            'ReceiptsDisp9'   => $receiptsDisp9,
            'ReceiptsDisp10'  => $receiptsDisp10,
            'ReceiptsDisp11'  => $receiptsDisp11,
            'ReceiptsDisp12'  => $receiptsDisp12,
            'ReceiptsDisp13'  => $receiptsDisp13,
            'ClientField1'    => $clientField1,
            'ClientField2'    => $clientField2,
            'ClientField3'    => MDL_PG_MYLPAY_CLIENT_FIELD3,
            'ClientFieldFlag' => '1',
        );
        // GMOPGから購入者へのメール
        if (MDL_PG_MULPAY_CONF_PGMAIL_CONVENI || $this->shouldSendFromGmoPgMail($objPage->arrForm['conveni'])) {
            //$objPG->printLog('MDL_PG_MULPAY_CONF_PGMAIL_CONVENI' . $objPage->arrForm['conveni']);
            $arrSendData['MailAddress'] = $objPage->arrData['order_email'];
        }
        // 会員ID
        $objCustomer = new SC_Customer_Ex();
        if($objCustomer->hasValue('customer_id')) {
            $arrSendData['MemberNo'] = $objCustomer->getValue('customer_id');
        }
        // 支払い期限
        if (strlen($objPG->getUserSettings('conveni_PaymentTermDay')) !== 0) {
            $arrSendData['PaymentTermDay'] = $objPG->getUserSettings('conveni_PaymentTermDay');
        }
        $objPG->printLog($arrSendData);
        return $arrSendData;
    }

    /**
     * 結果の解析を行う
     *
     * @param string $ret
     */
    function parse($ret) {
        parent::parse($ret);

        $arrParam = $this->getResults();

        // 新コンビニコードを旧コンビニコードに変換する
        if (isset($arrParam['Convenience']) &&
            !is_null($arrParam['Convenience'])) {
            $arrParam['Convenience'] =
                $this->convNew2OldCVSCD($arrParam['Convenience']);
        }

        $this->setResults($arrParam);
    }

    /**
     * ExecTran名を取得する
     *
     * @return stirng ExecTran名
     */
    function getExecTranName() {
        return 'ExecTranCvs.idPass';
    }

    /**
     * GMOPGからのメールを送信するかのconfigを取得
     *
     * @return boolean
     */
    function shouldSendFromGmoPgMail($conveni) {
        $mailconf = array(
                      CONVENI_LOSON => MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_LAWSON,
                      CONVENI_FAMILYMART => MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_FAMILYMART,
                      CONVENI_SUNKUS => MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_SUNKUS,
                      CONVENI_CIRCLEK => MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_CIRCLEK,
                      CONVENI_MINISTOP => MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_MINISTOP,
                      CONVENI_DAILYYAMAZAKI => MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_DAILYYAMAZAKI,
                      CONVENI_SEVENELEVEN => MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_SEVENELEVEN
                      );

        // 範囲外は空でfalseになる
        return $mailconf[$conveni];
    }
}
?>
