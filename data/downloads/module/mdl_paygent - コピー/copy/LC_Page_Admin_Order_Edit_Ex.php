<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright (c) 2006 PAYGENT Co.,Ltd. All rights reserved.
 *
 * https://www.paygent.co.jp/
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

// {{{ requires
require_once CLASS_REALDIR . 'pages/admin/order/LC_Page_Admin_Order_Edit.php';

require_once MODULE_REALDIR . 'mdl_paygent/include.php';

/**
 * 受注修正 のページクラス(拡張).
 *
 * LC_Page_Admin_Order_Edit をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Order_Edit_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Admin_Order_Edit_Ex extends LC_Page_Admin_Order_Edit {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->arrDispKind = getDispKind();
        $this->arrInvoiceSendType = getInvoiceSendTypeOption();
        $this->arrCarriersCompanyCode = getCarriersCompanyCode();
        $this->arrClientReasonCode = getClientReasonCode();
        $this->tpl_mainpage = MODULE_REALDIR . "mdl_paygent/templates/admin/order_edit.tpl";
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id']: '';

        switch($this->getMode()) {
        case 'paygent_order':
            $this->arrErr = $this->checkError($_POST['paygent_type']);
            if (!SC_Utils_Ex::isBlank($this->arrErr)) {
                break;
            }
            $this->paygent_return = sfPaygentOrder($_POST['paygent_type'], $order_id, '', '', $this->getPaygentRequst());
            break;
        }
        $this->arrOrderPaygent = $this->lfGetOrderPaygent($order_id);

        parent::process();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    // ペイジェント決済情報の取得
    function lfGetOrderPaygent($order_id){
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrder = $objPurchase->getOrder($order_id);

        return $arrOrder;
    }

    /**
     * 入力チェック
     *
     * @return チェック結果
     */
    function checkError($paygent_type) {
        $objErr = new SC_CheckError_Ex();
        switch($paygent_type) {
            case 'later_payment_bill_reissue':
                if (SC_Utils_Ex::isBlank($_POST['client_reason_code'])) {
                    $objErr->arrErr['client_reason_code'] = '※ 依頼理由が選択されていません。<br />';
                }
                break;
            case 'later_payment_clear':
                if (SC_Utils_Ex::isBlank($_POST['carriers_company_code'])) {
                    $objErr->arrErr['carriers_company_code'] = '※ 運送会社コードが選択されていません。<br />';
                }
                if (SC_Utils_Ex::isBlank($_POST['delivery_slip_number'])) {
                    $objErr->arrErr['delivery_slip_number'] = '※ 配送伝票番号が入力されていません。<br />';
                } else if (!preg_match("/^[a-zA-Z0-9-]+$/", $_POST['delivery_slip_number'])) {
                    $objErr->arrErr['delivery_slip_number'] = '※ 配送伝票番号は半角英数・ハイフンで入力してください。<br />';
                } else if (mb_strlen($_POST['delivery_slip_number']) < 5 || mb_strlen($_POST['delivery_slip_number']) > 20) {
                    $objErr->arrErr['delivery_slip_number'] = '※ 配送伝票番号は5桁から20桁で入力してください。<br />';
                }
                break;
        }
        return $objErr->arrErr;
    }

    /**
     * 入力値を取得する
     *
     * @return 入力値
     */
    function getPaygentRequst() {
        $arrRequest = array();
        switch($_POST['paygent_type']) {
            case 'later_payment_reduction':
                $arrRequest['invoice_send_type'] = $_REQUEST['invoice_send_type'];
                break;
            case 'later_payment_clear':
                $arrRequest['delivery_company_code'] = $_REQUEST['carriers_company_code'];
                $arrRequest['delivery_slip_no'] = $_REQUEST['delivery_slip_number'];
                break;
            case 'later_payment_bill_reissue':
                $arrRequest['reason_code'] = $_REQUEST['client_reason_code'];
                break;
        }
        return $arrRequest;
    }

    /**
     * 入力値を初期化する
     *
     * @return void
     */
    function lfInitParam(&$objFormParam)
    {
        // 検索条件のパラメーターを初期化
        parent::lfInitParam($objFormParam);

        // 請求書送付方法
        $objFormParam->addParam('請求書送付方法', 'invoice_send_type', '', 'n', array());
        // 依頼理由
        $objFormParam->addParam('依頼理由', 'client_reason_code', '', 'n', array());
        // 運送会社コード
        $objFormParam->addParam('運送会社コード', 'carriers_company_code', '', 'n', array());
        // 配送伝票番号
        $objFormParam->addParam('配送伝票番号', 'delivery_slip_number', '', 'a', array());
    }
}
?>
