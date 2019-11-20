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
require_once CLASS_REALDIR . 'SC_FpdfSouhu_Order.php';

/**
 * 帳票出力 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Order_Pdf.php 20970 2011-06-10 10:27:24Z Seasoft $
 */
class LC_Page_Admin_Order_SouhuFaxPdf extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'order/SouhuFaxpdf_input.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'pdf';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = '帳票出力';

        $this->SHORTTEXT_MAX = STEXT_LEN;
        $this->MIDDLETEXT_MAX = MTEXT_LEN;
        $this->LONGTEXT_MAX = LTEXT_LEN;

        /* 2014/02/12 追記　*/
        $this->arrType[0]  = "書類送付のご案内";

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
        if (!isset($arrRet)) {
            $arrRet = array();
        }

        switch ($this->getMode()) {
            case 'confirm':
                $status = $this->createPdf($this->objFormParam);

                if ($status === true) {
                    exit;
                } else {
                    $this->arrErr = $status;
                }
                break;
            default:
                $this->arrForm = $this->createFromValues($_GET['order_id'], $_POST['pdf_order_id']);
                break;
        }
        $this->setTemplate($this->tpl_mainpage);
    }

    /**
     *
     * PDF作成フォームのデフォルト値の生成
     */
    function createFromValues($order_id, $pdf_order_id) {
        // デフォルト格納
        $arrForm = array();

        // タイトルをセット
        $arrForm['title'] = "書類送付のご案内";

        // 今日の日付をセット
        $arrForm['year']  = date('Y');
        $arrForm['month'] = date('m');
        $arrForm['day']   = date('d');

        $arrForm['tmp1'] = 0;
        $arrForm['tmp2'] = 0;
        $arrForm['tmp3'] = 0;
        $arrForm['tmp4'] = 0;

        // メッセージをセット
        $arrForm['msg1'] = '貴社におかれましては、ますますご清栄のことと心よりお慶び申し上げます。';
        $arrForm['msg2'] = '平素は格別のご高配を賜り、厚く御礼申し上げます。';
        $arrForm['msg3'] = '早速ではございますが、下記の書類をお送りします。';
        $arrForm['msg4'] = 'ご査収の上よろしくご手配を賜りますようお願い申し上げます。';

        // 注文番号をセット
        if (SC_Utils_Ex::sfIsInt($order_id)) {
            $arrForm['order_id'][0] = $order_id;
        } elseif (is_array($pdf_order_id)) {
            sort($pdf_order_id);
            foreach ($pdf_order_id AS $key=>$val) {
                $arrForm['order_id'][] = $val;
            }
        }

        // DBから受注情報を読み込む
        $objQuery = new SC_Query_Ex();
        $where = "order_id = ?";
//        $arrRet = $objQuery->select("*", "dtb_shipping", $where, array($order_id)); //お届け先情報を取得
        $arrRet = $objQuery->select("*", "dtb_order", $where, array($order_id)); //注文者情報を取得
        $this->arrDisp = $arrRet[0];

        // 都道府県名リスト
        $pref_text = array(
            '','北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県',
            '山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県',
            '徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'
        );

        // 送付先住所をセット
        $arrForm['user_zip1'] = $this->arrDisp["order_zip01"];
        $arrForm['user_zip2'] = $this->arrDisp["order_zip02"];
        $arrForm['user_addr1'] = $pref_text[$this->arrDisp["order_pref"]] . " " . $this->arrDisp["order_addr01"];
        $arrForm['user_addr2'] = $this->arrDisp["order_addr02"];

        // 宛先名をセット
        $arrForm['user_name1'] = $this->arrDisp["order_name01"];
        $arrForm['user_name2'] = $this->arrDisp["order_name02"];
        $arrForm['dear_text'] = '様';

        return $arrForm;
    }

    /**
     * PDFの作成
     *
     * @param SC_FormParam $objFormParam
     */
    function createPdf(&$objFormParam) {
        $arrErr = $this->lfCheckError($objFormParam);
        $arrRet = $objFormParam->getHashArray();

        $this->arrForm = $arrRet;
        // エラー入力なし
        /* 2014/02/12 追記　*/
        if (count($arrErr) == 0) {
            if ($arrRet['type'] == 0) {
                // 送付状の発行の場合
                $objFpdf = new SC_FpdfSouhu_Order($arrRet['download'], $arrRet['title']);
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
        }else{
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
     *
     *  @param SC_FormParam
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam("注文番号", "order_id", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("注文番号", "pdf_order_id", INT_LEN, 'n', array( "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("宛先会社名", 'user_name1', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("宛先お名前", 'user_name2', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("宛先名", 'dear_text', STEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("発行日", 'year', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("発行日", 'month', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("発行日", 'day', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("帳票の種類", 'type', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("ダウンロード方法", 'download', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("帳票タイトル", 'title', STEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ1行目", "msg1", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ2行目", "msg2", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ3行目", "msg3", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ4行目", "msg4", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("宛先郵便番号1", 'user_zip1', STEXT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("宛先郵便番号2", 'user_zip2', STEXT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("宛先住所1", 'user_addr1', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("宛先住所2", 'user_addr2', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("領収書数", "tmp1", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("請求書数", "tmp2", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("保証書数", "tmp3", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("その他", "tmp4_text",  STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("その他書類数", "tmp4", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    }

    /**
     *  入力内容のチェック
     *
     *  @var SC_FormParam
     */
    function lfCheckError(&$objFormParam) {
        // 入力データを渡す。
        $arrRet = $objFormParam->getHashArray();
        $arrErr = $objFormParam->checkError();

        // ▼発行日チェック
        $year = $objFormParam->getValue('year');
        if (!is_numeric($year)) {
            $arrErr['year'] = "※発行年は数値で入力してください。";
        }

        $month = $objFormParam->getValue('month');
        if (!is_numeric($month)) {
            $arrErr['month'] = "※発行月は数値で入力してください。";
        } else if (0 >= $month && 12 < $month) {
            $arrErr['month'] = "※発行月は1〜12の間で入力してください。";
        }

        $day = $objFormParam->getValue('day');
        if (!is_numeric($day)) {
            $arrErr['day'] = "※発行日は数値で入力してください。";
        } else if (0 >= $day && 31 < $day) {
            $arrErr['day'] = "※発行日は1〜31の間で入力してください。";
        }

        // ▼宛先名チェック
        $dear_text = $objFormParam->getValue('dear_text');
        if ($dear_text === '御中') {
            if ($objFormParam->getValue('user_name1') === '') {
                $arrErr['dear_text'] = "※宛先の会社名を入力してください。";
            }
        } else if ($dear_text === '様') {
            if ($objFormParam->getValue('user_name1') === '' && $objFormParam->getValue('user_name2') === '') {
                $arrErr['dear_text'] = "※宛先の会社名もしくはお名前を入力してください。";
            }
        } else {
            $arrErr['dear_text'] = "※宛先名を正しく選択してください。";
        }

        // ▼送付書類チェック
        $tmp1 = $objFormParam->getValue('tmp1');
        $tmp2 = $objFormParam->getValue('tmp2');
        $tmp3 = $objFormParam->getValue('tmp3');
        $tmp4 = $objFormParam->getValue('tmp4');
        if (is_numeric($tmp1) && $tmp1 > 0) {
        } else if (is_numeric($tmp2) && $tmp2 > 0) {
        } else if (is_numeric($tmp3) && $tmp3 > 0) {
        } else if (is_numeric($tmp4) && $tmp4 > 0) {
        } else {
            $arrErr['tmp1'] = "※送付書類を入力してください。";
        }

        if (is_numeric($tmp4) && $tmp4 > 0) {
            if ($objFormParam->getValue('tmp4_text') === '') {
                $arrErr['tmp4_text'] = "※その他の内容を入力してください。";
            }
        }

        return $arrErr;
    }

}

