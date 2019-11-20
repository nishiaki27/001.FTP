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
//require_once CLASS_REALDIR . 'SC_Fpdf_Order.php';
/* 2017/05/08 追記　*/
require_once CLASS_REALDIR . 'SC_Fpdf_Order_Bill.php';
require_once CLASS_REALDIR . 'SC_Fpdf_Order_Send.php';

/**
 * 帳票出力 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Order_Pdf.php 20970 2011-06-10 10:27:24Z Seasoft $
 */
class LC_Page_Admin_Order_Pdf extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'order/pdf_input.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'pdf';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = '帳票出力';

        $this->SHORTTEXT_MAX = STEXT_LEN;
        $this->MIDDLETEXT_MAX = MTEXT_LEN;
        $this->LONGTEXT_MAX = LTEXT_LEN;

        /* 2017/05/22 編集 */
        $this->arrType[0]  = "納品書";
        $this->arrType[1]  = "請求書";
        $this->arrType[2]  = "お届け日のお知らせ";
        /* 2017/05/22 不要の為削除 */
        //$this->arrType[3]  = "納品書 兼 領収書";

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
     * PDF作成フォームのデフォルト値の生成
     *
     */
    function createFromValues($order_id, $pdf_order_id) {
        // デフォルト格納
        $arrForm = array();

        // 種類をセット
        $arrForm['type'] = '0';

        // タイトルをセット
        $arrForm['title'] = "納品書";

        // 但し書きをセット
        //$arrForm['tadashi_msg'] = "エアコン代として、下記正に領収いたしました。";

        // 今日の日付
        $today_y = date('Y');
        $today_m = date('m');
        $today_d = date('d');

        // 発行日をセット
        $arrForm['year']  = $today_y;
        $arrForm['month'] = $today_m;
        $arrForm['day']   = $today_d;

        // 納品日をセット
        $arrForm['nohin_year']  = $today_y;
        $arrForm['nohin_month'] = $today_m;
        $arrForm['nohin_day']   = $today_d;

        // お届け日をセット
        $arrForm['otodoke_year']  = $today_y;
        $arrForm['otodoke_month'] = $today_m;
        $arrForm['otodoke_day']   = $today_d;

        // 請求日をセット
        if ($_GET['pay_date']) {
            $pay_date = strtotime($_GET['pay_date']);
            $arrForm['pay_year']  = date('Y', $pay_date);
            $arrForm['pay_month'] = date('m', $pay_date);
            $arrForm['pay_day']   = date('d', $pay_date);
        } else {
            $arrForm['pay_year']  = $today_y;
            $arrForm['pay_month'] = $today_m;
            $arrForm['pay_day']   = $today_d;
        }

        // メッセージをセット
        $arrForm['msg1'] = 'このたびはお買上げいただきありがとうございます。';
        $arrForm['msg2'] = '下記の内容にて納品させていただきます。';
        $arrForm['msg3'] = 'ご確認くださいますよう、お願いいたします。';

        // 注文番号があったら、セットする
        if (SC_Utils_Ex::sfIsInt($order_id)) {
            $arrForm['order_id'][0] = $order_id;
        } elseif (is_array($pdf_order_id)) {
            sort($pdf_order_id);
            foreach ($pdf_order_id AS $key => $val) {
                $arrForm['order_id'][] = $val;
            }
        }

        // DBから受注情報を読み込む
        $objQuery = new SC_Query_Ex();
        $where = "order_id = ?";
        $arrRet = $objQuery->select("*", "dtb_shipping", $where, array($order_id)); //納品先情報を取得
        $this->arrDisp = $arrRet[0];

        // 都道府県名リスト
        $pref_text = array(
            '','北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県',
            '山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県',
            '徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'
        );

        // 納品先住所をセット
        $arrForm['user_addr1'] = $pref_text[$this->arrDisp["shipping_pref"]] . " " . $this->arrDisp["shipping_addr01"];
        $arrForm['user_addr2'] = $this->arrDisp["shipping_addr02"];
        $arrForm['user_company'] = $this->arrDisp["shipping_name01"]; //会社名

        // DBから受注情報を読み込む
        $objQuery = new SC_Query_Ex();
        $where = "order_id = ?";
        $arrRet = $objQuery->select("*", "dtb_order", $where, array($order_id)); //注文者情報を取得
        $this->arrDisp = $arrRet[0];

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
        /* 2017/05/02 更新　*/
        if (count($arrErr) == 0) {
            $objFpdf = "";
            if ($arrRet['type'] == 0) {
                //納品書の発行の場合
                $objFpdf = new SC_Fpdf($arrRet['download'], $arrRet['title']);
            } elseif ($arrRet['type'] == 1) {
                //請求書の発行の場合
                $objFpdf = new SC_Fpdf_Order_Bill($arrRet['download'], $arrRet['title']);
            } elseif ($arrRet['type'] == 2) {
                //お届け日のお知らせの場合
                $objFpdf = new SC_Fpdf_Order_Send($arrRet['download'], $arrRet['title']);
/*
            } elseif ($arrRet['type'] == 0) {
                //納品書兼領収書の発行の場合
                $objFpdf = new SC_Fpdf_Order($arrRet['download'], $arrRet['title']);
*/
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
        $objFormParam->addParam("宛先会社名", 'user_name1', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("宛先お名前", 'user_name2', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("宛先名", 'dear_text', STEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("発行日", 'year', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("発行日", 'month', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("発行日", 'day', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("納品日", 'nohin_year', INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("納品日", 'nohin_month', INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("納品日", 'nohin_day', INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("請求日", 'pay_year', INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("請求日", 'pay_month', INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("請求日", 'pay_day', INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("お届け日", 'otodoke_year', INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("お届け日", 'otodoke_month', INT_LEN, 'n', array( "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("お届け日", 'otodoke_day', INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("お届け日時", 'otodoke_text', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票の種類", 'type', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("ダウンロード方法", 'download', INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("帳票タイトル", 'title', STEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        //$objFormParam->addParam("但し書き", 'tadashi_msg', STEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("納品先住所1", 'user_addr1', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("納品先住所2", 'user_addr2', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("納品先会社名", 'user_company', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ1行目", "msg1", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ2行目", "msg2", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("帳票メッセージ3行目", "msg3", STEXT_LEN*3/5, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("備考1行目", "etc1", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("備考2行目", "etc2", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("備考3行目", "etc3", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("ポイント表記", "disp_point", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
    }

    /**
     *  入力内容のチェック
     *
     *  @var SC_FormParam
     */
    function lfCheckError(&$objFormParam) {
        // 入力データを渡す
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

        if ($objFormParam->getValue('type') === '0') { //納品書
            // ▼納品日チェック
            $year = $objFormParam->getValue('nohin_year');
            if (!is_numeric($year)) {
                $arrErr['nohin_year'] = "※納品年は数値で入力してください。";
            }

            $month = $objFormParam->getValue('nohin_month');
            if (!is_numeric($month)) {
                $arrErr['nohin_month'] = "※納品月は数値で入力してください。";
            } else if (0 >= $month && 12 < $month) {
                $arrErr['nohin_month'] = "※納品月は1〜12の間で入力してください。";
            }

            $day = $objFormParam->getValue('nohin_day');
            if (!is_numeric($day)) {
                $arrErr['nohin_day'] = "※納品日は数値で入力してください。";
            } else if (0 >= $day && 31 < $day) {
                $arrErr['nohin_day'] = "※納品日は1〜31の間で入力してください。";
            }

        } else if ($objFormParam->getValue('type') === '1') { //請求書
            // ▼請求日チェック
            $year = $objFormParam->getValue('pay_year');
            if (!is_numeric($year)) {
                $arrErr['pay_year'] = "※請求年は数値で入力してください。";
            }

            $month = $objFormParam->getValue('pay_month');
            if (!is_numeric($month)) {
                $arrErr['pay_month'] = "※請求月は数値で入力してください。";
            } else if (0 >= $month && 12 < $month) {
                $arrErr['pay_month'] = "※請求月は1〜12の間で入力してください。";
            }

            $day = $objFormParam->getValue('pay_day');
            if (!is_numeric($day)) {
                $arrErr['pay_day'] = "※請求日は数値で入力してください。";
            } else if (0 >= $day && 31 < $day) {
                $arrErr['pay_day'] = "※請求日は1〜31の間で入力してください。";
            }

        } else if ($objFormParam->getValue('type') === '2') { //お届け日お知らせ
            // ▼お届け日チェック
            $year = $objFormParam->getValue('otodoke_year');
            if (!is_numeric($year)) {
                $arrErr['otodoke_year'] = "※お届け年は数値で入力してください。";
            }

            $month = $objFormParam->getValue('otodoke_month');
            if (!is_numeric($month)) {
                $arrErr['otodoke_month'] = "※お届け月は数値で入力してください。";
            } else if (0 >= $month && 12 < $month) {
                $arrErr['otodoke_month'] = "※お届け月は1〜12の間で入力してください。";
            }

            $day = $objFormParam->getValue('otodoke_day');
            if (!is_numeric($day)) {
                $arrErr['otodoke_day'] = "※お届け日は数値で入力してください。";
            } else if (0 >= $day && 31 < $day) {
                $arrErr['otodoke_day'] = "※お届け日は1〜31の間で入力してください。";
            }
        }
        return $arrErr;
    }
}
