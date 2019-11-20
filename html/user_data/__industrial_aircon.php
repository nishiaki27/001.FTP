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
        $this->title_parts = $this->keijyo_set();
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
        $keijyo = htmlspecialchars($param['keijyo'], ENT_QUOTES, 'UTF-8'); //形状
        $maker_id = htmlspecialchars($param['maker_id'], ENT_QUOTES, 'UTF-8'); //メーカーID
        $product_id = htmlspecialchars($param['product_id'], ENT_QUOTES, 'UTF-8'); //商品ID
        $category_id = htmlspecialchars($param['category_id'], ENT_QUOTES, 'UTF-8'); //カテゴリID
        $op4 = htmlspecialchars($param['op_4'], ENT_QUOTES, 'UTF-8'); //省エネタイプ
        $op5 = htmlspecialchars($param['op_5'], ENT_QUOTES, 'UTF-8'); //電源タイプ
        $op6 = htmlspecialchars($param['op_6'], ENT_QUOTES, 'UTF-8'); //リモコンタイプ

        // メーカー指定
        $maker_query = '';
        switch ($maker_id) {
            case 1: $maker_query = "ダイキン"; break;
            case 2: $maker_query = "東芝"; break;
            case 3: $maker_query = "三菱電機"; break;
            case 4: $maker_query = "日立"; break;
            case 5: $maker_query = "三菱重工"; break;
            case 6: $maker_query = "パナソニック"; break;
            default:
        }

        // 形状指定
        switch ($keijyo) {
            case 'tenkase4':
                $type = 72;
                $maker_dai = 100121;
                $maker_tos = 100141;
                $maker_mid = 100161;
                $maker_hit = 100181;
                $maker_mij = 100201;
                $aircon_img = 'ten4';
                break;
            case 'compact':
                $type = 73;
                $maker_dai = 100122;
                $maker_tos = 100142;
                $maker_mid = 100162;
                $maker_hit = 100182;
                $maker_mij = 100202;
                $aircon_img = 'comp';
                break;
            case 'tenkase2':
                $type = 74;
                $maker_dai = 100123;
                $maker_tos = 100143;
                $maker_mid = 100163;
                $maker_hit = 100183;
                $maker_mij = 100203;
                $aircon_img = 'ten2';
                break;
            case 'tenkase1':
                $type = 75;
                $maker_dai = 100124;
                $maker_tos = 100144;
                $maker_mid = 100164;
                $maker_hit = 100184;
                $maker_mij = 100204;
                $aircon_img = 'ten1';
                break;
            case 'tenturi':
                $type = 76;
                $maker_dai = 100125;
                $maker_tos = 100145;
                $maker_mid = 100165;
                $maker_hit = 100185;
                $maker_mij = 100205;
                $aircon_img = 'tentsuru';
                break;
            case 'kabekake':
                $type = 77;
                $maker_dai = 100126;
                $maker_tos = 100146;
                $maker_mid = 100166;
                $maker_hit = 100186;
                $maker_mij = 100206;
                $aircon_img = 'kabe';
                break;
            case 'yukaoki':
                $type = 78;
                $maker_dai = 100127;
                $maker_tos = 100147;
                $maker_mid = 100167;
                $maker_hit = 100187;
                $maker_mij = 100207;
                $aircon_img = 'yuka';
                break;
            case 'chubo':
                $type = 79;
                $maker_dai = 100128;
                $maker_tos = 100148;
                $maker_mid = 100168;
                $maker_hit = 100188;
                $maker_mij = 100208;
                $aircon_img = 'chubou';
                break;
            case 'builtin':
                $type = 80;
                $maker_dai = 100129;
                $maker_tos = 100149;
                $maker_mid = 100169;
                $maker_hit = 100189;
                $maker_mij = 100209;
                $aircon_img = 'builtin';
                break;
            case 'duct':
                $type = 81;
                $maker_dai = 100130;
                $maker_tos = 100150;
                $maker_mid = 100170;
                $maker_hit = 100190;
                $maker_mij = 100210;
                $aircon_img = 'duct';
                break;
            case 'ogatatenpo':
                $type = 82;
                $maker_dai = 100131;
                $maker_tos = 100151;
                $maker_mid = 100171;
                $maker_hit = 100191;
                $maker_mij = 100211;
                break;
            case 'wonderful':
                $type = 83;
                $maker_dai = 100132;
                $maker_tos = 100152;
                $maker_mid = 100172;
                $maker_hit = 100192;
                $maker_mij = 100212;
                $aircon_img = 'tenjizai';
                break;
            case 'clean':
                $type = 84;
                $maker_dai = 100133;
                $maker_tos = 100153;
                $maker_mid = 100173;
                $maker_hit = 100193;
                $maker_mij = 100213;
                break;
            default:
        }

        // カテゴリ仕様
        $start_id = 110001;
        switch ($type) {
            case 72:
                $start_id = $start_id ;
                $title_parts = '天井カセット形4方向';
                $maker_type = 4;
                break;
            case 73:
                $start_id = $start_id + 140 * 1;
                $title_parts = '天井カセット形コンパクト';
                $maker_type = 5;
                break;
            case 74:
                $start_id = $start_id + 140 * 2;
                $title_parts = '天井カセット形2方向';
                $maker_type = 6;
                break;
            case 75:
                $start_id = $start_id + 140 * 3;
                $title_parts = '天井カセット形1方向';
                $maker_type = 7;
                break;
            case 76:
                $start_id = $start_id + 140 * 4;
                $title_parts = '天井吊形';
                $maker_type = 8;
                break;
            case 77:
                $start_id = $start_id + 140 * 5;
                $title_parts = '壁掛形';
                $maker_type = 9;
                break;
            case 78:
                $start_id = $start_id + 140 * 6;
                $title_parts = '床置形';
                $maker_type = 10;
                break;
            case 79:
                $start_id = $start_id + 140 * 7;
                $title_parts = '厨房用';
                $maker_type = 11;
                break;
            case 80:
                $start_id = $start_id + 140 * 8;
                $title_parts = 'ビルトイン形';
                $maker_type = 12;
                break;
            case 81:
                $start_id = $start_id + 140 * 9;
                $title_parts = 'ダクト形';
                $maker_type = 13;
                break;
            case 82:
                $start_id = $start_id + 140 * 10;
                $title_parts = '大型店舗用';
                $maker_type = 14;
                break;
            case 83:
                $start_id = $start_id + 140 * 11;
                $title_parts = '天吊自在形';
                $maker_type = 44;
                break;
            case 84:
                $start_id = $start_id + 140 * 12;
                $title_parts = 'クリーンエアコン';
                $maker_type = 45;
                break;
            default:
        }

        // 省エネタイプ
        $op4_query = '';
        switch ($op4) {
            case 1: $op4_query = '標準省エネ'; break;
            case 2: $op4_query = '超省エネ'; break;
            case 3: $op4_query = '冷房専用'; break;
            case 4: $op4_query = '寒冷地'; break;
            default: $op4_query = '標準省エネ';
        }

        // 電源タイプ
        $op5_query = '';
        switch ($op5) {
            case 1: $op5_query = '三相200V'; break;
            case 2: $op5_query = '単相200V'; break;
            default:
        }

        // リモコンタイプ
        $op6_query = '';
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

        $htmlData = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/include/industrial_aircon/'. $type .'.txt');
        if ($htmlData) {
            $this->htmlData = $htmlData;
        }

        $this->type = $type;
        $this->keijyo = $keijyo;
        $this->maker_id = $maker_id;
        $this->maker_dai = $maker_dai;
        $this->aircon_img = $aircon_img;
        $this->op4 = $op4;
        $this->op5 = $op5;
        $this->op6 = $op6;
        $this->op4_query = $op4_query;
        $title_parts_sub = $title_parts;
        $this->title_parts_sub = $title_parts_sub;

        $op_array1 = array($maker_query, '標準省エネ', $op5_query, $op6_query);
        $op_array2 = array($maker_query, '超省エネ', $op5_query, $op6_query);
        $op_array3 = array($maker_query, '冷房専用', $op5_query, $op6_query);
        $op_array4 = array($maker_query, '寒冷地用', $op5_query, $op6_query);

        $op_str = array();
        $op_str[1] = implode(' ', $op_array1); //標準省エネ
        $op_str[2] = implode(' ', $op_array2); //超省エネ
        $op_str[3] = implode(' ', $op_array3); //冷房専用
        $op_str[4] = implode(' ', $op_array4); //寒冷地用

        // タイトル
        $title_array = array();
        if ($maker_query != '') $title_array[] = $maker_query;
        if ($op4_query != '') $title_array[] = $op4_query;
        if ($op5_query != '') $title_array[] = $op5_query;
        if ($op6_query != '') $title_array[] = $op6_query;
        $title_str = implode(' ', $title_array);

        if (mb_substr_count($title_str, ' ') >= 2) {
            $num = strpos($title_str, ' ', strpos($title_str, ' ') + 1);
            $title_str = substr_replace($title_str, '<br>', $num, 1);
        }

        if ($title_str != '  ') {
            if ($type == 78 && $op6 == 1) {
                $title_parts .= '<br><span>' . $title_str . ' ワイヤード</span>';
            } else {
                $title_parts .= '<br><span>' . $title_str . '</span>';
            }
        }
        $this->title_parts2 = $title_parts;

        $t_id = $_SESSION['transactionid'];
        $this->t_id = $t_id;


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

        $line3 = $maker_dai - $id_interval;

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

        // メーカーごとの商品存在チェック
        $this->arrMakerInProducts = array(
            1 => false, //ダイキン
            2 => false, //東芝
            3 => false, //三菱電機
            4 => false, //日立
            5 => false, //三菱重工
            6 => false, //パナソニック
        );

        // 検索クエリ条件
        $arrQueryParam = array();
        $arrQueryParam['maker_id'] = $mi;

        if ($op4 == '' || $op4 == 1 || $op4 == 3 || $op4 == 4) {
            // 該当する商品があるカテゴリ情報を取得
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
            $arrQueryParam['start_id'] = $start_id;
            $arrCategoryNormalData = $this->getResults($arrQueryParam);

            foreach ($arrCategoryNormalData as $value) {
                $category_id = $value['category_id'];

                if ($category_id >= $line1_1_st && $category_id < $line1_1_ed) {
                    $key = $category_id - $line1_1_st;
                    $arrNormalData_line1_1[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line1_2_st && $category_id < $line1_2_ed) {
                    $key = $category_id - $line1_2_st;
                    $arrNormalData_line1_2[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line1_3_st && $category_id < $line1_3_ed) {
                    $key = $category_id - $line1_3_st;
                    $arrNormalData_line1_3[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line1_4_st && $category_id < $line1_4_ed) {
                    $key = $category_id - $line1_4_st;
                    $arrNormalData_line1_4[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line2_1_st && $category_id < $line2_1_ed) {
                    $key = $category_id - $line2_1_st;
                    $arrNormalData_line2_1[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line2_2_st && $category_id < $line2_2_ed) {
                    $key = $category_id - $line2_2_st;
                    $arrNormalData_line2_2[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line2_3_st && $category_id < $line2_3_ed) {
                    $key = $category_id - $line2_3_st;
                    $arrNormalData_line2_3[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
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
        }

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
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line1_2_st && $category_id < $line1_2_ed) {
                    $key = $category_id - $line1_2_st;
                    $arrSuperData_line1_2[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line1_3_st && $category_id < $line1_3_ed) {
                    $key = $category_id - $line1_3_st;
                    $arrSuperData_line1_3[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line1_4_st && $category_id < $line1_4_ed) {
                    $key = $category_id - $line1_4_st;
                    $arrSuperData_line1_4[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line2_1_st && $category_id < $line2_1_ed) {
                    $key = $category_id - $line2_1_st;
                    $arrSuperData_line2_1[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line2_2_st && $category_id < $line2_2_ed) {
                    $key = $category_id - $line2_2_st;
                    $arrSuperData_line2_2[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
                } else if ($category_id >= $line2_3_st && $category_id < $line2_3_ed) {
                    $key = $category_id - $line2_3_st;
                    $arrSuperData_line2_3[$category_id] = array('name' => '', 'value' => $view_str[$key]);
                    $this->checkMakerProducts($value);
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
     * メーカーごとに該当する商品が存在するかチェックする
     *
     * @return
     */
    function checkMakerProducts($arrResults) {
        $arrMakerInProducts = $this->arrMakerInProducts;

        // メーカーの商品数チェック
        foreach ($arrMakerInProducts as $keys => $values) {
            if ($values == false && $arrResults['maker_'.$keys] > 0) {
                $arrMakerInProducts[$keys] = true;
            }
        }

        $this->arrMakerInProducts = $arrMakerInProducts;
        return;
    }

    /**
     * 該当する商品のカテゴリ情報を取得する
     *
     * @return array
     */
    function getResults($arrParams = array()) {
        $column  = "T2.category_id";
        $column .= ", count(CASE WHEN alldtl.maker_id = 1 THEN 1 ELSE null END) as maker_1 ";
        $column .= ", count(CASE WHEN alldtl.maker_id = 2 THEN 1 ELSE null END) as maker_2 ";
        $column .= ", count(CASE WHEN alldtl.maker_id = 3 THEN 1 ELSE null END) as maker_3 ";
        $column .= ", count(CASE WHEN alldtl.maker_id = 4 THEN 1 ELSE null END) as maker_4 ";
        $column .= ", count(CASE WHEN alldtl.maker_id = 5 THEN 1 ELSE null END) as maker_5 ";
        $column .= ", count(CASE WHEN alldtl.maker_id = 6 THEN 1 ELSE null END) as maker_6 ";

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
            foreach ($arrParams['comment3'] as $value) {
                if ($value) {
                    $where .= "AND alldtl.comment3 LIKE ? ";
                    $arrValues[] = "%" . $value . "%";
                }
            }
        }
        $where .= "GROUP BY T2.category_id ";
        $where .= "HAVING COUNT(alldtl.product_id) > 0 ";
        $where .= "ORDER BY T2.category_id ";

        $objQuery = SC_Query::getSingletonInstance();
        $arrResults = $objQuery->select($column, $from, $where, $arrValues);
        return $arrResults;
    }
}


$objPage = new LC_Page_User();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
