<?php
require_once '../require.php';
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

// error_reporting(E_ALL); と同じ
ini_set('error_reporting', E_ALL);

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
        $this->title_parts = $this->location_set();
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

        // リクエストパラメータ取得
        $param = $_GET;
        $loc = htmlspecialchars($param['loc'], ENT_QUOTES, 'UTF-8');
        $location = htmlspecialchars($param['location_id'], ENT_QUOTES, 'UTF-8');
        $this->strLocation = $loc;

        switch ($loc) {
            case 'office':     $location = 1; break;
            case 'school':     $location = 2; break;
            case 'shop':       $location = 3; break;
            case 'restaurant': $location = 4; break;
            case 'beautysalon':$location = 5; break;
            case 'hospital':   $location = 6; break;
            case 'factory':    $location = 7; break;
            case 'workplace':  $location = 8; break;
            case 'other':      $location = 9; break;
            case 'hotel':      $location = 10; break;
            default:
        }

        // 見出し設定
        switch ($location) {
            case 1: $this->title_parts = '事務所'; break;
            case 2: $this->title_parts = '学校関係'; break;
            case 3: $this->title_parts = '商店・店舗'; break;
            case 4: $this->title_parts = '飲食店'; break;
            case 5: $this->title_parts = '理美容室'; break;
            case 6: $this->title_parts = '病院・医院'; break;
            case 7: $this->title_parts = '工場'; break;
            case 8: $this->title_parts = '倉庫・作業場'; break;
            case 9: $this->title_parts = 'その他'; break;
            case 10:$this->title_parts = '宿泊施設'; break;
            default:
        }

        $htmlData = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/include/industrial_aircon_location/' . $location . '.txt');
        if ($htmlData) {
            $this->htmlData = $htmlData;
        }

        // 宿泊施設の表示を事務所と同じにする為
        if ($location == 10) {
            $location = 1;
        }

        // 検索クエリパラメータ
        $arrParams = array();
        $arrParams['location'] = $location;

        // 設置場所データ取得
        $objQuery = SC_Query::getSingletonInstance();
        $this->arrLocationData = $this->getResults($arrParams);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    function getResults($arrParams = array()) {
        $where = '';
        $arrValues = array();
        if (isset($arrParams['location'])) {
            $where .= 'use = ? ORDER BY id ASC LIMIT 30 OFFSET 0';
            $arrValues[] = $arrParams['location'];
        }

        $objQuery = SC_Query::getSingletonInstance();
        $arrResults = $objQuery->select('*', 'mtb_location', $where, $arrValues);
        return $arrResults;
    }
}


$objPage = new LC_Page_User();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
