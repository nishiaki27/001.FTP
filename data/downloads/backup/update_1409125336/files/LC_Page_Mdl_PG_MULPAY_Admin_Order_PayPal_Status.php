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

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

require_once MODULE_REALDIR . 'mdl_pg_mulpay/class/utils/LC_Mdl_PG_MULPAY_Export.php';


/**
 * GMOPayPal決済状況管理 のページクラス.
 *
 * @package Page
 * @author SOFTHOUSE CO.,LTD.
 * @version $Id: LC_Page_Mdl_PG_MULPAY_Admin_Order_PayPal_Status.php 4139 2012-04-24 05:21:13Z takashi $
 */
class LC_Page_Mdl_PG_MULPAY_Admin_Order_Paypal_Status extends LC_Page_Admin_Ex {

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();
        $this->tpl_mainpage = $obj_LC_Mdl_PG_MULPAY_Export->getTplDirPath() . 'admin/order/gmopg_paypal_status.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'gmopg_paypal_status';
        $this->tpl_subtitle = 'PayPal決済ステータス管理';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrORDERSTATUS = $masterData->getMasterData("mtb_order_status");
        $this->arrORDERSTATUS_COLOR = $masterData->getMasterData("mtb_order_status_color");

        $this->arrPAYPALSTATUS = $obj_LC_Mdl_PG_MULPAY_Export->getPaypalStatusArray();
        $this->arrPAYPALSTATUS_CHANGE_TABLE = $obj_LC_Mdl_PG_MULPAY_Export->getPaypalStatusChangeArray();

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $this->gmopg_enableCardStatusChange = $objPG->isEnableCardStatusChangeFunction();
        $this->gmopg_enablePaypal = $objPG->isEnablePaypal();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objDb = new SC_Helper_DB_Ex();

        // パラメータ管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメータ情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        // 入力値の変換
        $objFormParam->convParam();

        $this->arrForm = $objFormParam->getHashArray();

        //支払方法の取得
        $this->arrPayment = $objDb->sfGetIDValueList("dtb_payment", "payment_id", "payment_method");

        switch ($this->getMode()){
        case 'update':
            switch ($objFormParam->getValue('change_status')) {
            case '':
                break;
                // 削除
            case 'delete':
                $this->lfDelete($objFormParam->getValue('move'));
                break;
                // 更新
            default:
                /*
                $status = isset($_POST['change_status']) ? $_POST['change_status'] : "";
                if (!empty($status)) {
                    $this->lfStatusMove($status, $_POST['move']);
                }
                */
                $status = $objFormParam->getValue('change_status');
                $this->lfStatusMove($status, $objFormParam->getValue('move'));
                break;
            }

            //ステータス情報  変更後のステータスを表示する
            //$status = !is_null($objFormParam->getValue('status')) ? $objFormParam->getValue('status') : "";
            break;

        case 'search':
            $status = isset($_POST['status']) ? $_POST['status'] : "";
            break;

        default:
            //デフォルトで入金済み一覧表示
            $status = 'CAPTURE';
            break;
        }

        if ($status == "") $status = 'CAPTURE';

        // 可能な次状態を列挙する
        $this->arrPAYPALSTATUS_CHANGE = $this->arrPAYPALSTATUS_CHANGE_TABLE[$status];

        //ステータス情報
        $this->SelectedStatus = $status;
        //検索結果の表示
        $this->lfStatusDisp($status, $_POST['search_pageno']);
    }

    /**
     *  パラメータ情報の初期化
     *  @param SC_FormParam
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam("注文番号", "order_id", INT_LEN, 'n', array( "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("変更前ステータス", 'status', INT_LEN, 'n', array( "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("変更後ステータス", "change_status", STEXT_LEN, 'KVa', array( "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("ページ番号", "search_pageno", INT_LEN, 'n', array( "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("移動注文番号", 'move', INT_LEN, 'n', array( "MAX_LENGTH_CHECK", "NUM_CHECK"));
    }

    /**
     *  入力内容のチェック
     *  @param SC_FormParam
     */
    function lfCheckError(&$objFormParam) {
        // 入力データを渡す。
        $arrRet = $objFormParam->getHashArray();
        $arrErr = $objFormParam->checkError();
        if(is_null($objFormParam->getValue('search_pageno'))){
            $objFormParam->setValue('search_pageno', 1);
        }

        if($this->getMode() == 'change'){
            if(is_null($objFormParam->getValue('change_status'))){
                $objFormParam->setValue('change_status',"");
            }
        }

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    //ステータス一覧の表示
    function lfStatusDisp($status,$pageno){
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();

        $select ="*";
        $from = "dtb_order";
        // 有効性チェックの受注情報は論理削除された状態で存在する。
        //$where = "del_flg = ? AND memo04 = ? AND payment_id = ?";
        //$arrval[] = $status === "CHECK" ? "1" : "0"; 
        $where = "del_flg = " . ($status === "CHECK" ? "1" : "0") . " AND memo04 = ? AND payment_id = ?";
        $arrval[] = $status;
        $arrval[] = $obj_LC_Mdl_PG_MULPAY_Export->getGmoPaypalPaymentId();
        $order = "order_id DESC";

        $linemax = $objQuery->count($from, $where, $arrval);
        $this->tpl_linemax = $linemax;

        // ページ送りの処理
        $page_max = ORDER_STATUS_MAX;
        
        // ページ送りの取得
        $objNavi = new SC_PageNavi($pageno, $linemax, $page_max, "fnNaviSearchOnlyPage", NAVI_PMAX);
        $this->tpl_strnavi = $objNavi->strnavi;      // 表示文字列
        $startno = $objNavi->start_row;

        $this->tpl_pageno = $pageno;

        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setLimitOffset($page_max, $startno);

        //表示順序
        $objQuery->setOrder($order);

        //検索結果の取得
        $this->arrStatus = $objQuery->select($select, $from, $where, $arrval);
    }

    //ステータス情報の更新
    function lfStatusMove($paypal_status, $arrOrderId){
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrUpdate = array('update_date' => 'NOW()');
        $arrUpdate['memo04'] = $paypal_status;
        
        if (isset($arrOrderId)) {
            foreach ($arrOrderId as $order_id) {
                if ($order_id != "") {

                    $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();

                    $responseArray = $obj_LC_Mdl_PG_MULPAY_Export->requestPaypalCancel($order_id);
                    if (!is_array($responseArray)) {
                        $this->tpl_onload = "window.alert('受注番号${order_id}でエラーが発生しました。\\n$responseArray');";
                        return;
                    }

                    $objQuery->update('dtb_order', $arrUpdate, 'order_id = ?', array($order_id));
                }
            }
        }

        $message = $this->arrPAYPALSTATUS[$paypal_status] . 'へ変更';
        $this->tpl_onload = "window.alert('選択項目を" . $message . "しました。');";
    }
}
?>
