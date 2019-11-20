<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
require_once CLASS_REALDIR . 'SC_Fpdf.php';
/* 2014/02/12 追記　*/
require_once CLASS_REALDIR . 'SC_Fpdf_OrderFax.php';

/**
 * 帳票出力 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Order_Pdf.php 20970 2011-06-10 10:27:24Z Seasoft $
 */
class LC_Page_Admin_Order_FaxPdf extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'order/Faxpdf_input.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'pdf';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = 'FAX出力';

        $this->SHORTTEXT_MAX = STEXT_LEN;
        $this->MIDDLETEXT_MAX = MTEXT_LEN;
        $this->LONGTEXT_MAX = LTEXT_LEN;

        /* 2014/02/12 追記　*/
        $this->arrType[0] = "お見積書 兼 ご注文書";
        //$this->arrType[1] = "納品書";

        $this->arrDownload[0] = "ブラウザに開く";
        $this->arrDownload[1] = "ファイルに保存";
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
        $objDate = new SC_Date_Ex(1901);
        $objDate->setStartYear(RELEASE_YEAR);
        $this->arrYear = $objDate->getYear();
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();

        // パラメーター管理クラス
        $this->objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($this->objFormParam);
        $this->objFormParam->setParam($_POST);
        // 入力値の変換
        $this->objFormParam->convParam();

        // どんな状態の時に isset($arrRet) == trueになるんだ? これ以前に$arrRet無いが、、、、
        if (!isset($arrRet)) $arrRet = array();
        switch($this->getMode()) {
            case 'confirm':
                $status = $this->createPdf($this->objFormParam);
                if($status === true){
                    exit;
                }else{
                    $this->arrErr = $status;
                }
                break;
            default:
                $this->arrForm = $this->createFromValues($_GET['order_id'],$_POST['pdf_order_id']);
                break;
        }
        $this->setTemplate($this->tpl_mainpage);
    }

    /**
     *
     * PDF作成フォームのデフォルト値の生成
     */
    function createFromValues($order_id,$pdf_order_id){
        // ここが$arrFormの初登場ということを明示するため宣言する。
        $arrForm = array();

        // 注文番号があったら、セットする
        if (SC_Utils_Ex::sfIsInt($order_id)) {
            $arrForm = $this->GetOrderMailData($order_id);
            $arrForm['order_id'][0] = $order_id;
        } elseif (is_array($pdf_order_id)) {
            sort($pdf_order_id);
            foreach ($pdf_order_id AS $key => $val) {
                $arrForm['order_id'][] = $val;
            }
        }

        // タイトルをセット
        $arrForm['title'] = "お見積書 兼 ご注文書";

        // 但し書きをセット
        $arrForm['tadashi_msg'] = "※※ご入金確認後、メーカー正式手配となります※※";

        // 今日の日付をセット
        $arrForm['year']  = date('Y');
        $arrForm['month'] = date('m');
        $arrForm['day']   = date('d');

        // 在庫状況
        if (!$arrForm['zaiko_jokyo']) {
            $arrForm['zaiko_jokyo'] = "";
        }
        // お届目安なければデフォルト
        if (!$arrForm['otodoke_meyasu']) {
            $arrForm['otodoke_meyasu'] = "";
        }

        // メッセージ
        $arrForm['msg1'] = 'ご検討の程、よろしくお願いいたします。';
        $arrForm['msg2'] = '';
        $arrForm['msg3'] = '';

        return $arrForm;
    }

    /**
     *
     * PDFの作成
     * @param SC_FormParam $objFormParam
     */
    function createPdf(&$objFormParam){

        $arrErr = $this->lfCheckError($objFormParam);
        $arrRet = $objFormParam->getHashArray();

        $this->arrForm = $arrRet;
        // エラー入力なし
        /* 2014/02/12 追記　*/
        if (count($arrErr) == 0) {
            if ($arrRet['type'] == 0) {
                // お見積書・ご注文書の発行の場合
                $objFpdf = new SC_Fpdf_OrderFax($arrRet['download'], $arrRet['title']);
            }

            if (isset($objFpdf)) {
                foreach ($arrRet['order_id'] AS $key => $val) {
                    $arrPdfData = $arrRet;
                    $arrPdfData['order_id'] = $val;
                    $objFpdf->setData($arrPdfData);
                }
                $objFpdf->createPdf();
                return true;
            }
        } else {
            return $arrErr;
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

    /**
     *  パラメーター情報の初期化 
     *  @param SC_FormParam 
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam("注文番号", "order_id", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("注文番号", "pdf_order_id", INT_LEN, 'n', array( "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("発行日", 'year', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("発行日", 'month', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("発行日", 'day', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("担当者番号", "tanto_id", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("担当者名", "tanto_name", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("在庫状況", "zaiko_jokyo", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お届目安", "otodoke_meyasu", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票の種類", 'type', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("ダウンロード方法", 'download', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("帳票タイトル", 'title', STEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票注意書き", 'tadashi_msg', STEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ1行目", "msg1", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ2行目", "msg2", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ3行目", "msg3", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("備考1行目", "etc1", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("備考2行目", "etc2", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("備考3行目", "etc3", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
//        $objFormParam->addParam("ポイント表記", "disp_point", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
    }

    //データがあれば中身を呼び出す
    function GetOrderMailData($order_id){
        $objQuery = new SC_Query_Ex();
        $where = "id = ?";
        $objQuery->setOrder("dtb_order_mail_history");
        $mailHistorys = $objQuery->select("*", "dtb_order_mail_history", $where, array($order_id));

        if (count($mailHistorys) > 0) {
            $order_mail = explode("\r\n", $mailHistorys[0]['text']);
            $tanto = explode("　　", $order_mail[0]);
            $tanto_id = str_replace("見積もりNo.", "", $tanto[0]);

            $tan = explode("-", $tanto_id);
            $arrForm['tanto_id'] = $tan[0];
            $arrForm['tanto_name'] = str_replace("担当者：", "", $tanto[1]);

            $tanto_id = str_replace($arrForm['tanto_id'], "", $tanto_id);
            $arrForm['month'] = mb_substr($tan[1], 0, 2);
            $arrForm['day'] = mb_substr($tan[1], 2, 2);

            $arrForm['zaiko_jokyo'] = str_replace("在庫状況：", "", $order_mail[1]);
            $arrForm['otodoke_meyasu'] = str_replace("お届目安日：", "", $order_mail[2]);
            $arrForm['etc1'] = str_replace("　", "", $order_mail[5]);
            $arrForm['etc2'] = str_replace("　", "", $order_mail[6]);
            $arrForm['etc3'] = str_replace("　", "", $order_mail[7]);
        }

        return $arrForm;
    }

    /**
     *  入力内容のチェック
     *  @var SC_FormParam
     */
    function lfCheckError(&$objFormParam) {
        // 入力データを渡す。
        $arrRet = $objFormParam->getHashArray();
        $arrErr = $objFormParam->checkError();

        $year = $objFormParam->getValue('year');
        if (!is_numeric($year)) {
            $arrErr['year'] = "発行年は数値で入力してください。";
        }

        $month = $objFormParam->getValue('month');
        if (!is_numeric($month)) {
            $arrErr['month'] = "発行月は数値で入力してください。";
        } else if (0 >= $month && 12 < $month) {
            $arrErr['month'] = "発行月は1〜12の間で入力してください。";
        }

        $day = $objFormParam->getValue('day');
        if (!is_numeric($day)) {
            $arrErr['day'] = "発行日は数値で入力してください。";
        } else if (0 >= $day && 31 < $day) {
            $arrErr['day'] = "発行日は1〜31の間で入力してください。";
        }

        $tanto_id = $objFormParam->getValue('tanto_id');
        if (!$tanto_id) {
            $arrErr['tanto_id'] = "担当者番号を入力してください。";
        }

        $tanto_name = $objFormParam->getValue('tanto_name');
        if (!$tanto_name) {
            $arrErr['tanto_name'] = "担当者名を入力してください。";
        }

        $zaiko_jokyo = $objFormParam->getValue('zaiko_jokyo');
        if (!$zaiko_jokyo) {
            $arrErr['zaiko_jokyo'] = "在庫状況を入力してください。";
        }

        $otodoke_meyasu = $objFormParam->getValue('otodoke_meyasu');
        if (!$otodoke_meyasu) {
            $arrErr['otodoke_meyasu'] = "お届目安日を入力してください。";
        }

        return $arrErr;
    }

}

