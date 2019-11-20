<?php
require_once '../require.php';
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * ユーザーカスタマイズ用のページクラス
 *
 * 管理画面から自動生成される
 *
 * @package Page
 */
class LC_Page_User extends LC_Page_Ex {

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {

		$objQuery = SC_Query_Ex::getSingletonInstance();
		
        $this->arrForm = $_REQUEST;

		//現在のページ番号取得
		$this->tpl_pageno = $this->arrForm['pageno'];

		//新着情報の件数を取得する
		$count = 0;
		$where_count = "del_flg = '0'";
		$linemax = $objQuery->count("dtb_news", $where_count);
		$this->tpl_linemax = $linemax;

		//1ページの表示件数取得
		$page_max = SC_Utils_Ex::sfGetSearchPageMax($arrForm['search_page_max']);
		$page_max = "";
		//$page_max = 3; //確認用にハードコードしました

		//ページ送りの取得
        $urlParam = "pageno=#page#";
		$objNavi = new SC_PageNavi_Ex($this->tpl_pageno, $linemax, $page_max,'fnNaviPage', NAVI_PMAX, $urlParam);
		$this->arrPagenavi = $objNavi->arrPagenavi;
		
		//表示文字列アサイン
		$this->tpl_strnavi = $objNavi->strnavi;

		//開始行番号取得
		$startno = $objNavi->start_row;
		
		//取得範囲の指定(開始行番号、行数のセット)
		$objQuery->setLimitOffset($page_max, $startno);

		//新着情報を取得する
		$col = "* , cast(news_date as date) as news_date_disp";
		$from = "dtb_news";
		$where = "del_flg = 0 ORDER BY rank DESC";
		$this->arrNews = $objQuery->select($col, $from, $where, $arrval);

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
}


$objPage = new LC_Page_User();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();