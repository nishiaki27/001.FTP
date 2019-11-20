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
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrRECOMMEND = $masterData->getMasterData("mtb_recommend");
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

      //レビュー情報の取得
        $this->arrReview = $this->lfGetReviewData();

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    //商品ごとのレビュー情報を取得する
    function lfGetReviewData() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        //商品ごとのレビュー情報を取得する
        $col = "t1.create_date, t1.reviewer_url, t1.reviewer_name, t1.recommend_level, t1.title, t1.comment, t2.product_id, t2.name, t2.main_list_image, t2.comment4";
        $from = "dtb_review as t1 left join dtb_products as t2 using (product_id)";
//        $where = "t1.del_flg = 0 AND t1.status = 1 ORDER BY t1.create_date DESC";
        $where = "t1.del_flg = 0 AND t1.status = 1 AND t1.sex = 1 ORDER BY t1.create_date DESC";
        $arrReview = $objQuery->select($col, $from, $where, $arrval);
        return $arrReview;
    }
}

$objPage = new LC_Page_User();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();