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
require_once(MDL_PG_MULPAY_CLASS_REALDIR . "pages/LC_Page_Mdl_PG_MULPAY_Payeasy.php");
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY_Client.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'utils/LC_Mdl_PG_MULPAY_Utils.php');

/**
 * ATM決済処理クラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY_ATM extends LC_Page_Mdl_PG_MULPAY_Payeasy {
    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        // エラーチェック
        parent::checkParamError($uniqid, $this->objSiteSess);

        if ($this->is2clickFlow) {
            $this->save2Click();
        } else {
            // 決済実行
            parent::tranMode();
        }
    }

    /**
     * 受注一時情報.汎用項目2(dtb_order_temp.memo02)に格納する値を取得する。
     *
     * @return array 汎用項目2格納値
     */
    function getSerializeMessage($arrExecRet) {
        $message['title'] = $this->lfSetConvMSG(ATM_MEG_TITLE, true);
        $message['cust_id'] = $this->lfSetConvMSG("お客様番号", $arrExecRet['CustID']);
        $message['bk_code'] = $this->lfSetConvMSG("収納機関番号", $arrExecRet['BkCode']);
        $message['conf_no'] = $this->lfSetConvMSG("確認番号", $arrExecRet['ConfNo']);
        $message['payment_term'] = $this->lfSetConvMSG("お支払期限", LC_Mdl_PG_MULPAY_Utils::convertDate($arrExecRet['PaymentTerm'], "Y年m月d日") . "\n\n");
        $message['message'] = $this->lfSetConvMSG(ATM_MEG_SUB_TITLE, ATM_MEG_BODY);
        return $message;
    }

    /**
     * EntryTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getEntryTranClassName() {
        return 'Entry_ATM';
    }

    /**
     * ExecTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getExecClassName() {
        return 'Exec_ATM';
    }

}

?>
