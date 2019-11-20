<?php
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

class LC_Page_Admin_Customer_Contact extends LC_Page {

    function init() {
        parent::init();
        $this->tpl_mainpage = 'customer/contact.tpl';
        $this->tpl_subnavi = 'customer/subnavi.tpl';
        $this->tpl_mainno = 'customer';
        $this->tpl_subno = 'contact';
        $this->tpl_pager = 'pager.tpl';
        $this->tpl_subtitle = 'お問い合わせ管理';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPref = $masterData->getMasterData('mtb_pref');
        $this->arrPageMax = $masterData->getMasterData("mtb_page_max");
   }

   /**
    * Page のプロセス.
    *
    * @return void
    */

    function process() {
        // 認証可否の判定
        $objSess = new SC_Session();
        SC_Utils_Ex::sfIsSuccess($objSess);

        // モードチェック
        if(!isset($_POST['mode'])) {
            $_POST['mode'] = "";
        } elseif($_POST['mode'] == 'delete') {
            if(SC_Utils_Ex::sfIsInt($_POST['contact_id'])) {
                $objQuery = new SC_Query();
                $where = "contact_id = ?";
                $sqlval['del_flg'] = '1';
                $objQuery->update("dtb_contact", $sqlval, $where, array($_POST['contact_id']));
            }
        }

        // 表示順の指定
        $order = "create_date DESC";
        // 読み込む列とテーブルの指定
        $col = "*";
        $from = "dtb_contact";
        $where = "del_flg = 0";
        $objQuery = new SC_Query();
        // 行数の取得
        $linemax = $objQuery->count($from, $where);
        $this->tpl_linemax = $linemax;    // 何件が該当しました。表示用

        // ページ送り用
        if(is_numeric($_POST['search_page_max'])) {
            $page_max = $_POST['search_page_max'];
        } else {
//            $page_max = SEARCH_PMAX;
            $page_max = 30;
        }

        // ページ送りの取得
        $this->arrHidden['search_pageno'] =
            isset($_POST['search_pageno']) ? $_POST['search_pageno'] : "";
        $objNavi = new SC_PageNavi($this->arrHidden['search_pageno'],
                                   $linemax, $page_max,
                                   "fnNaviSearchPage", NAVI_PMAX);
        $startno = $objNavi->start_row;
        $this->arrPagenavi = $objNavi->arrPagenavi;

        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setlimitoffset($page_max, $startno);
        // 表示順序
        $objQuery->setorder($order);
        // 検索結果の取得
        $this->arrResults = $objQuery->select($col, $from, $where);

        $objView = new SC_AdminView();
        $objView->assignobj($this);
        $objView->display(MAIN_FRAME);
    }
}
?>