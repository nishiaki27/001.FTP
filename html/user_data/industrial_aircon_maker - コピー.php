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
        $this->title_parts = $this->maker_set() . " " . $this->keijyo_set();
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
        $mi = htmlspecialchars($param['mi'], ENT_QUOTES, 'UTF-8'); //メーカーID
        $ma = htmlspecialchars($param['ma'], ENT_QUOTES, 'UTF-8'); //メーカー名
        $type = htmlspecialchars($param['category_id'], ENT_QUOTES, 'UTF-8'); //カテゴリID
        $keijyo = htmlspecialchars($param['keijyo'], ENT_QUOTES, 'UTF-8'); //形状
        $op4 = htmlspecialchars($param['op_4'], ENT_QUOTES, 'UTF-8'); //省エネタイプ
        $op5 = htmlspecialchars($param['op_5'], ENT_QUOTES, 'UTF-8'); //電源タイプ
        $op6 = htmlspecialchars($param['op_6'], ENT_QUOTES, 'UTF-8'); //リモコンタイプ

        // メーカー指定
        switch ($ma) {
            case 'daikin':
                $mi = 1;
                $maker_num = 49;
                $maker_img = 'daikin';
                break;
            case 'toshiba':
                $mi = 2;
                $maker_num = 69;
                $maker_img = 'toshiba';
                break;
            case 'mitsubishidenki':
                $mi = 3;
                $maker_num = 89;
                $maker_img = 'mitsuD';
                break;
            case 'hitachi':
                $mi = 4;
                $maker_num = 109;
                $maker_img = 'hitachi';
                break;
            case 'mitsubishijyuko':
                $mi = 5;
                $maker_num = 129;
                $maker_img = 'mitsuJk';
                break;
            case 'panasonic':
                $mi = 6;
                $maker_num = 149;
                $maker_img = 'panasonic';
                break;
            default:
        }

        // 形状指定
        switch ($keijyo) {
            case 'tenkase4':
                $type = 72;
                $aircon_img = 'ten4';
                break;
            case 'compact':
                $type = 73;
                $aircon_img = 'comp';
                break;
            case 'tenkase2':
                $type = 74;
                $aircon_img = 'ten2';
                break;
            case 'tenkase1':
                $type = 75;
                $aircon_img = 'ten1';
                break;
            case 'tenturi':
                $type = 76;
                $aircon_img = 'tentsuru';
                break;
            case 'kabekake':
                $type = 77;
                $aircon_img = 'kabe';
                break;
            case 'yukaoki':
                $type = 78;
                $aircon_img = 'yuka';
                break;
            case 'chubo':
                $type = 79;
                $aircon_img = 'chubou';
                break;
            case 'builtin':
                $type = 80;
                $aircon_img = 'builtin';
                break;
            case 'duct':
                $type = 81;
                $aircon_img = 'duct';
                break;
            case 'ogatatenpo':
                $type = 82;
                break;
            case 'wonderful':
                $type = 83;
                $aircon_img = 'tenjizai';
                break;
            case 'clean':
                $type = 84;
                break;
            default:
        }

        // 形状仕様
        $start_id = 110001;
        switch ($type) {
            case 72:
                $start_id = $start_id;
                $title_parts = '天井カセット形4方向';
                break;
            case 73:
                $start_id = $start_id + 140 * 1;
                $title_parts = '天井カセット形コンパクト';
                break;
            case 74:
                $start_id = $start_id + 140 * 2;
                $title_parts = '天井カセット形2方向';
                break;
            case 75:
                $start_id = $start_id + 140 * 3;
                $title_parts = '天井カセット形1方向';
                break;
            case 76:
                $start_id = $start_id + 140 * 4;
                $title_parts = '天吊形';
                break;
            case 77:
                $start_id = $start_id + 140 * 5;
                $title_parts = '壁掛形';
                break;
            case 78:
                $start_id = $start_id + 140 * 6;
                $title_parts = '床置形';
                break;
            case 79:
                $start_id = $start_id + 140 * 7;
                $title_parts = '厨房用';
                break;
            case 80:
                $start_id = $start_id + 140 * 8;
                $title_parts = 'ビルトイン形';
                break;
            case 81:
                $start_id = $start_id + 140 * 9;
                $title_parts = 'ダクト形';
                break;
            case 82:
                $start_id = $start_id + 140 * 10;
                $title_parts = '大型店舗用';
                break;
            case 83:
                $start_id = $start_id + 140 * 11;
                $title_parts = '天吊自在形';
                break;
            case 84:
                $start_id = $start_id + 140 * 12;
                $title_parts = 'クリーンエアコン';
                break;
            default:
        }

        // 省エネタイプ指定
        switch ($op4) {
            case 1: $op4_query = '標準省エネ'; break;
            case 2: $op4_query = '超省エネ'; break;
            case 3: $op4_query = '冷房専用'; break;
            case 4: $op4_query = '寒冷地用'; break;
            default: $op4_query = '標準省エネ'; break;
        }

        // 電源タイプ指定
        switch ($op5) {
            case 1: $op5_query = '三相200V'; break;
            case 2: $op5_query = '単相200V'; break;
            default:
        }

        // リモコンタイプ指定
        switch ($op6) {
            case 1:
                if ($type == 78) $op6_query = '';
                else $op6_query = 'ワイヤード';
                break;
            case 2:
                $op6_query = 'ワイヤレス';
                break;
            default:
        }

        // メーカー指定
        switch ($mi) {
            case 1:
                $title_parts2 = 'ダイキン';
                $this->main_img = 'Daikin';
                break;
            case 2:
                $title_parts2 = '東芝';
                $this->main_img = 'Toshiba';
                break;
            case 3:
                $title_parts2 = '三菱電機';
                $this->main_img = 'Mitsubishi_denki';
                break;
            case 4:
                $title_parts2 = '日立';
                $this->main_img = 'Hitachi';
                break;
            case 5:
                $title_parts2 = '三菱重工';
                $this->main_img = 'Mitsubishi_jukou';
                break;
            case 6:
                $title_parts2 = 'パナソニック';
                $this->main_img = 'Panasonic';
                break;
            default:
        }

        $this->type = $type;
        $this->ma = $ma;
        $this->mi = $mi;
        $this->keijyo = $keijyo;
        $this->maker_img = $maker_img;
        $this->aircon_img = $aircon_img;
        $this->op4 = $op4;
        $this->op5 = $op5;
        $this->op6 = $op6;
        $this->op4_query = $op4_query;
        $this->title_h2 = $title_parts2 . ' ' . $title_parts;

        $op_array1 = array($maker_query, '標準省エネ', $op5_query, $op6_query);
        $op_array2 = array($maker_query, '超省エネ', $op5_query, $op6_query);
        $op_array3 = array($maker_query, '冷房専用', $op5_query, $op6_query);
        $op_array4 = array($maker_query, '寒冷地', $op5_query, $op6_query);
        $op_array5 = array($maker_query, '標準省エネ 超省エネ', $op5_query, $op6_query);

        $op_str[1] = implode(' ', $op_array1); //標準省エネ
        $op_str[2] = implode(' ', $op_array2); //超省エネ
        $op_str[3] = implode(' ', $op_array3); //冷房専用
        $op_str[4] = implode(' ', $op_array4); //寒冷地用
        $op_str[5] = implode(' ', $op_array5); //

        if ($op_str != '  ') {
            $option_strings = '&name=' . urlencode($op_str);
            if ($type == 78 && $op6 == 1) {
                $title_parts = $title_parts . ' ' . $op_str . ' ワイヤード';
            } else {
                $title_parts = $title_parts . ' ' . $op_str;
            }
        }


        // 馬力値の設定（必須：要素数が同じ 現：12個）
        $hp = array(1.5, 1.8, 2, 2.3, 2.5, 3, 4, 5, 6, 8, 10, 12); //馬力
        $hp_mold = array(40, 45, 50, 56, 63, 80, 112, 140, 160, 224, 280, 335); //形
        $hp_val = array('1.5馬力', '1.8馬力', '2馬力 not12馬力', '2.3馬力', '2.5馬力', '3馬力 not2.3馬力', '4馬力', '5馬力 not1.5馬力 not2.5馬力', '6馬力', '8馬力', '10馬力', '12馬力'); //（現：12個）

        $view_str = array();
        foreach ($hp as $key => $value) {
            $view_str[] = $value . '馬力(' . $hp_mold[$key] . '形)';
        }
        $hp_cnt = count($view_str);

        // タイプごとのカテゴリIDの間隔値（シングル→同時ツイン→個別ツイン etc...）
        $id_interval = 20;

        // カテゴリIDの範囲設定（シングル＜同時ツイン＜個別ツイン＜同時トリプル＜個別トリプル＜同時フォー＜個別フォー）
        $line1_1_st = $start_id;
        $line1_1_ed = $line1_1_st + $hp_cnt;
        $line1_2_st = $line1_1_st + $id_interval;
        $line1_2_ed = $line1_2_st + $hp_cnt;
        $line1_3_st = $line1_2_st + $id_interval * 2;
        $line1_3_ed = $line1_3_st + $hp_cnt;
        $line1_4_st = $line1_3_st + $id_interval * 2;
        $line1_4_ed = $line1_4_st + $hp_cnt;

        $line2_1_st = $line1_2_st + $id_interval;
        $line2_1_ed = $line2_1_st + $hp_cnt;
        $line2_2_st = $line2_1_st + $id_interval * 2;
        $line2_2_ed = $line2_2_st + $hp_cnt;
        $line2_3_st = $line2_2_st + $id_interval * 2;
        $line2_3_ed = $line2_3_st + $hp_cnt;

        $line3 = 100000 + $maker_num + $type;

        // ▼標準省エネ格納
        $arrNormalData_line1 = array(); //1段目
        $arrNormalData_line1_1 = array();
        $arrNormalData_line1_2 = array();
        $arrNormalData_line1_3 = array();
        $arrNormalData_line1_4 = array();
        $arrNormalData_line2 = array(); //2段目
        $arrNormalData_line2_1 = array();
        $arrNormalData_line2_2 = array();
        $arrNormalData_line2_3 = array();
        $arrNormalData_line3 = array(); //3段目
        $arrNormalData_line3_1 = array();
        $arrNormalData_line3_2 = array();

        // 検索クエリ条件
        $arrQueryParam = array();
        $arrQueryParam['maker_id'] = $mi;

//        if ($op4 == '' || $op4 == 1 || $op4 == 3 || $op4 == 4) {
            // 該当する商品があるカテゴリ情報を取得
            switch($op4){
            	case 1:
                // 標準省エネで検索
                $arrQueryParam['comment3'] = explode(' ', $op_str[1]);
            	break;
            	case 2:
                // 超省エネで検索
                $arrQueryParam['comment3'] = explode(' ', $op_str[2]);
            	break;
            	case 3:
                // 条件を冷房専用へ
                $arrQueryParam['comment3'] = explode(' ', $op_str[3]);
            	break;
            	case 4:
                // 条件を寒冷地用へ
                $arrQueryParam['comment3'] = explode(' ', $op_str[4]);
            	break;
            	default:
                // 標準省エネ、超省エネを検索
                $arrQueryParam['comment3'] = explode(' ', $op_str[5]);
            	break;
            }
/*
            if ($op4 == 3) {
                // 条件を冷房専用へ
                $arrQueryParam['comment3'] = explode(' ', $op_str[3]);
            } else if ($op4 == 4) {
                // 条件を寒冷地用へ
                $arrQueryParam['comment3'] = explode(' ', $op_str[4]);
            } else {
                // 標準省エネで検索
                $arrQueryParam['comment3'] = explode(' ', $op_str[1]);
            }
*/
            $arrQueryParam['start_id'] = $start_id;
            $arrCategoryNormalData = $this->getResults($arrQueryParam);

            foreach ($arrCategoryNormalData as $value) {
                $category_id = $value['category_id'];

                if ($category_id >= $line1_1_st && $category_id < $line1_1_ed) {
                    $key = $category_id - $line1_1_st;
                    $arrNormalData_line1_1[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line1_2_st && $category_id < $line1_2_ed) {
                    $key = $category_id - $line1_2_st;
                    $arrNormalData_line1_2[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line1_3_st && $category_id < $line1_3_ed) {
                    $key = $category_id - $line1_3_st;
                    $arrNormalData_line1_3[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line1_4_st && $category_id < $line1_4_ed) {
                    $key = $category_id - $line1_4_st;
                    $arrNormalData_line1_4[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line2_1_st && $category_id < $line2_1_ed) {
                    $key = $category_id - $line2_1_st;
                    $arrNormalData_line2_1[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line2_2_st && $category_id < $line2_2_ed) {
                    $key = $category_id - $line2_2_st;
                    $arrNormalData_line2_2[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line2_3_st && $category_id < $line2_3_ed) {
                    $key = $category_id - $line2_3_st;
                    $arrNormalData_line2_3[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                }

            }

            if ($op4 == '') {
                // ▼冷房専用
                // 該当する商品があるカテゴリ情報を取得
                $arrQueryParam['comment3'] = explode(' ', $op_str[3]);
                $arrCategoryNormalReiData = $this->getResults($arrQueryParam);

                foreach ($arrCategoryNormalReiData as $value) {
                    $category_id = $value['category_id'];
                    if ($category_id > $line2_3_ed) break;

                    $key = ($category_id - $line1_1_st) % $id_interval;
                    if ($key > 0 && $key < $hp_cnt) {
                        $arrNormalData_line3_1[$key] = array('name' => urlencode($hp_val[$key]), 'value' => $view_str[$key], 'id' => $line3);
                    }
                }

                // ▼寒冷地用
                // 該当する商品があるカテゴリ情報を取得
                $arrQueryParam['comment3'] = explode(' ', $op_str[4]);
                $arrCategoryNormalkanData = $this->getResults($arrQueryParam);

                foreach ($arrCategoryNormalkanData as $value) {
                    $category_id = $value['category_id'];
                    if ($category_id > $line2_3_ed) break;

                    $key = ($category_id - $line1_1_st) % $id_interval;
                    if ($key > 0 && $key < $hp_cnt) {
                        $arrNormalData_line3_2[$key] = array('name' => urlencode($hp_val[$key]), 'value' => $view_str[$key], 'id' => $line3);
                    }
                }
            }
//        }

        $arrNormalData_line1[1] = $arrNormalData_line1_1;
        $arrNormalData_line1[2] = $arrNormalData_line1_2;
        $arrNormalData_line1[3] = $arrNormalData_line1_3;
        $arrNormalData_line1[4] = $arrNormalData_line1_4;
        $arrNormalData_line2[1] = $arrNormalData_line2_1;
        $arrNormalData_line2[2] = $arrNormalData_line2_2;
        $arrNormalData_line2[3] = $arrNormalData_line2_3;
        ksort($arrNormalData_line3_1);
        $arrNormalData_line3[1] = $arrNormalData_line3_1;
        ksort($arrNormalData_line3_2);
        $arrNormalData_line3[2] = $arrNormalData_line3_2;

        $this->arrNormalData_line1 = $arrNormalData_line1;
        $this->arrNormalData_line2 = $arrNormalData_line2;
        $this->arrNormalData_line3 = $arrNormalData_line3;

/*
        // ▼超省エネ格納
        $arrSuperData_line1 = array(); //1段目
        $arrSuperData_line2 = array(); //2段目

        if ($op4 == '' || $op4 == 2) {
            // 該当する商品があるカテゴリ情報を取得
            $arrQueryParam['start_id'] = $start_id;
            $arrQueryParam['comment3'] = explode(' ', $op_str[2]);
            $arrCategorySuperData = $this->getResults($arrQueryParam);

            foreach ($arrCategorySuperData as $value) {
                $category_id = $value['category_id'];

                if ($category_id >= $line1_1_st && $category_id < $line1_1_ed) {
                    $key = $category_id - $line1_1_st;
                    $arrSuperData_line1_1[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line1_2_st && $category_id < $line1_2_ed) {
                    $key = $category_id - $line1_2_st;
                    $arrSuperData_line1_2[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line1_3_st && $category_id < $line1_3_ed) {
                    $key = $category_id - $line1_3_st;
                    $arrSuperData_line1_3[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line1_4_st && $category_id < $line1_4_ed) {
                    $key = $category_id - $line1_4_st;
                    $arrSuperData_line1_4[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line2_1_st && $category_id < $line2_1_ed) {
                    $key = $category_id - $line2_1_st;
                    $arrSuperData_line2_1[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line2_2_st && $category_id < $line2_2_ed) {
                    $key = $category_id - $line2_2_st;
                    $arrSuperData_line2_2[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                } else if ($category_id >= $line2_3_st && $category_id < $line2_3_ed) {
                    $key = $category_id - $line2_3_st;
                    $arrSuperData_line2_3[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                }
            }
        }

        $arrSuperData_line1[1] = $arrSuperData_line1_1;
        $arrSuperData_line1[2] = $arrSuperData_line1_2;
        $arrSuperData_line1[3] = $arrSuperData_line1_3;
        $arrSuperData_line1[4] = $arrSuperData_line1_4;
        $arrSuperData_line2[1] = $arrSuperData_line2_1;
        $arrSuperData_line2[2] = $arrSuperData_line2_2;
        $arrSuperData_line2[3] = $arrSuperData_line2_3;

        $this->arrSuperData_line1 = $arrSuperData_line1;
        $this->arrSuperData_line2 = $arrSuperData_line2;
*/
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
        $from  = "dtb_products AS alldtl ";
        $from .= "JOIN dtb_product_categories AS T2 ";
        $from .= "ON alldtl.product_id = T2.product_id ";

        $where  = "alldtl.del_flg = 0 ";
        $where .= "AND alldtl.status = 1 ";
        $where .= "AND alldtl.comment1 is NULL ";
        $arrValues = array();
        if (!empty($arrParams['start_id'])) {
            $where .= "AND T2.category_id >= ? ";
            $arrValues[] = $arrParams['start_id'];
        }
        if (!empty($arrParams['maker_id'])) {
            $where .= "AND alldtl.maker_id = ? ";
            $arrValues[] = $arrParams['maker_id'];
        }
        if (isset($arrParams['comment3'])) {
	        	$count=0;
            foreach ($arrParams['comment3'] as $value) {
                if ($value) {
                		if($count == 0){
	                    $where_text= "AND alldtl.comment3 LIKE ? ";
	                    $count++;
										}else{
											if($value == "超省エネ"){
											  $where_text= "AND (alldtl.comment3 LIKE ? OR alldtl.comment3 LIKE ? )";
		                  }else{
		                  	$where_text.= "AND alldtl.comment3 LIKE ? ";
		                  }
										}
                    $arrValues[] = "%" . $value . "%";
                }
            }
            $where .=$where_text;
        }
        $where .= "GROUP BY T2.category_id ";
        $where .= "HAVING COUNT(alldtl.product_id) > 0 ";
        $where .= "ORDER BY T2.category_id ";

        $objQuery = SC_Query::getSingletonInstance();
        $arrResults = $objQuery->select('T2.category_id', $from, $where, $arrValues);
        return $arrResults;
    }
}


$objPage = new LC_Page_User();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
