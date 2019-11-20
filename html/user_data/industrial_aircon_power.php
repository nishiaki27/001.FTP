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
        $this->title_parts = $this->power_set();
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
        $pw = htmlspecialchars($param['pw'], ENT_QUOTES, 'UTF-8'); //馬力
        $power = htmlspecialchars($param['power'], ENT_QUOTES, 'UTF-8');
        $maker_id = htmlspecialchars($param['maker_id'], ENT_QUOTES, 'UTF-8'); //メーカーID
        $op4 = htmlspecialchars($param['op_4'], ENT_QUOTES, 'UTF-8'); //省エネタイプ
        $op5 = htmlspecialchars($param['op_5'], ENT_QUOTES, 'UTF-8'); //電源タイプ
        $op6 = htmlspecialchars($param['op_6'], ENT_QUOTES, 'UTF-8'); //リモコンタイプ

        // メーカー指定
        switch ($maker_id) {
            case 1: $maker_query = 'ダイキン'; break;
            case 2: $maker_query = '東芝'; break;
            case 3: $maker_query = '三菱電機'; break;
            case 4: $maker_query = '日立'; break;
            case 5: $maker_query = '三菱重工'; break;
            case 6: $maker_query = 'パナソニック'; break;
            default:
        }

        // 馬力
        switch ($pw) {
            case '1_5hp': $power = 40; break;
            case '1_8hp': $power = 45; break;
            case '2hp': $power = 50; break;
            case '2_3hp': $power = 56; break;
            case '2_5hp': $power = 63; break;
            case '3hp': $power = 80; break;
            case '4hp': $power = 112; break;
            case '5hp': $power = 140; break;
            case '6hp': $power = 160; break;
            case '8hp': $power = 224; break;
            case '10hp': $power = 280; break;
            case '12hp': $power = 335; break;
            default:
        }

        // 馬力文字
        $mt_start_id = 110001;
        switch ($power) {
            case 40:
                $start_id_plus = 0;
                $title_parts = '1.5馬力';
                $op_query = '1.5馬力';
                break;
            case 45:
                $start_id_plus = 1;
                $title_parts = '1.8馬力';
                $op_query = '1.8馬力';
                break;
            case 50:
                $start_id_plus = 2;
                $title_parts = '2馬力';
                $op_query = '2馬力 not12馬力';
                break;
            case 56:
                $start_id_plus = 3;
                $title_parts = '2.3馬力';
                $op_query = '2.3馬力';
                break;
            case 63:
                $start_id_plus = 4;
                $title_parts = '2.5馬力';
                $op_query = '2.5馬力';
                break;
            case 80:
                $start_id_plus = 5;
                $title_parts = '3馬力';
                $op_query = '3馬力 not2.3馬力';
                break;
            case 112:
                $start_id_plus = 6;
                $title_parts = '4馬力';
                $op_query = '4馬力';
                break;
            case 140:
                $start_id_plus = 7;
                $title_parts = '5馬力';
                $op_query = '5馬力 not1.5馬力 not2.5馬力';
                break;
            case 160:
                $start_id_plus = 8;
                $title_parts = '6馬力';
                $op_query = '6馬力';
                break;
            case 224:
                $start_id_plus = 9;
                $title_parts = '8馬力';
                $op_query = '8馬力';
                break;
            case 280:
                $start_id_plus = 10;
                $title_parts = '10馬力';
                $op_query = '10馬力';
                break;
            case 335:
                $start_id_plus = 11;
                $title_parts = '12馬力';
                $op_query = '12馬力';
                break;
            default:
        }
        $start_id = $mt_start_id + $start_id_plus;

        // 省エネタイプ指定
        switch ($op4) {
            case 1: $op4_query = '標準省エネ'; break;
            case 2: $op4_query = '超省エネ'; break;
            case 3: $op4_query = '冷房専用'; break;
            case 4: $op4_query = '寒冷地'; break;
            default: $op4_query = '業務用エアコン';
        }

        // リモコンタイプ指定
        switch ($op5) {
            case 1: $op5_query = '三相200V'; break;
            case 2: $op5_query = '単相200V'; break;
            default:
        }

        // 電源タイプ指定
        switch ($op6) {
            case 1: $op6_query = 'ワイヤード'; break;
            case 2: $op6_query = 'ワイヤレス'; break;
            default:
        }

        $this->pw = $pw;
        $this->type = $type;
        $this->maker_id = $maker_id;
        $this->maker_dai = $maker_dai;
        $this->aircon_img = $aircon_img;
        $this->op4 = $op4;
        $this->op5 = $op5;
        $this->op6 = $op6;
        $this->op_query = $op_query;
        $this->op4_query = $op4_query;

        $title_parts_sub = '業務用エアコン&nbsp;' . $title_parts;
        $this->title_parts_sub = $title_parts_sub;

        $op_array1 = array($maker_query2, '標準省エネ', $op5_query, $op6_query);
        $op_array2 = array($maker_query2, '超省エネ', $op5_query, $op6_query);
        $op_array3 = array($maker_query2, '冷房専用', $op5_query, $op6_query);
        $op_array4 = array($maker_query2, '寒冷地', $op5_query, $op6_query);
        $op_array5 = array($maker_query2, '', $op5_query, $op6_query);

        $op_str[1] = implode(' ', $op_array1); //標準省エネ
        $op_str[2] = implode(' ', $op_array2); //超省エネ
        $op_str[3] = implode(' ', $op_array3); //冷房専用
        $op_str[4] = implode(' ', $op_array4); //寒冷地用
        $op_str[5] = implode(' ', $op_array5); //

        if ($op_str1 != '  ') {
            $option_strings = '&name=' . urlencode($op_str1);
            if ($op6 == 1) {
                $title_parts = $title_parts . ' ' . $op_str . ' ワイヤード';
            } else {
                $title_parts = $title_parts . ' ' . $op_str1;
            }
        }


        // 形状（現：13個）
        $arr_keijo = array('天井カセット形4方向', '天井カセット形コンパクト', '天井カセット形2方向', '天井カセット形1方向', '天吊形', '壁掛形', '床置形', '厨房用', 'ビルトイン形', 'ダクト形', '大型店舗用', '天吊自在形', 'クリーンエアコン');
        $keijo_cnt = count($arr_keijo);
        $arr_type = array('シングル', '同時ツイン', '個別ツイン', '同時トリプル', '個別トリプル', '同時フォー', '個別フォー');
        $type_cnt = count($arr_type);
        $arr_type_add = array('', '', '(冷房専用)', '(寒冷地用)');

        // タイプごとのカテゴリIDの間隔値（シングル→同時ツイン→個別ツイン etc...）
        $id_type_interval = 20;
        // 形状ごとのカテゴリIDの間隔値（天井カセット形4方向→天井カセット形コンパクト→天井カセット形2方向 etc...）
        $id_interval = $id_type_interval * $type_cnt;

        // カテゴリIDの範囲設定（シングル＜同時ツイン＜個別ツイン＜同時トリプル＜個別トリプル＜同時フォー＜個別フォー）
        $line1_1_st = $mt_start_id;
        $line1_1_ed = $line1_1_st + $id_interval;
        $line1_2_st = $line1_1_ed;
        $line1_2_ed = $line1_2_st + $id_interval;
        $line1_3_st = $line1_2_ed;
        $line1_3_ed = $line1_3_st + $id_interval;
        $line1_4_st = $line1_3_ed;
        $line1_4_ed = $line1_4_st + $id_interval;

        $line2_1_st = $line1_4_ed;
        $line2_1_ed = $line2_1_st + $id_interval;
        $line2_2_st = $line2_1_ed;
        $line2_2_ed = $line2_2_st + $id_interval;
        $line2_3_st = $line2_2_ed;
        $line2_3_ed = $line2_3_st + $id_interval;
        $line2_4_st = $line2_3_ed;
        $line2_4_ed = $line2_4_st + $id_interval;

        $line3_1_st = $line2_4_ed;
        $line3_1_ed = $line3_1_st + $id_interval;
        $line3_2_st = $line3_1_ed;
        $line3_2_ed = $line3_2_st + $id_interval;
        $line3_3_st = $line3_2_ed + $id_interval; //大型店舗用を非表示
        $line3_3_ed = $line3_3_st + $id_interval;
        //$line3_4_st = $line3_3_ed;
        //$line3_4_ed = $line3_4_st + $id_interval;

        // 冷房専用・寒冷地用IDの設定
        $line1_1 = 100101;
        $line1_2 = $line1_1 + 1;
        $line1_3 = $line1_2 + 1;
        $line1_4 = $line1_3 + 1;
        $line2_1 = $line1_4 + 1;
        $line2_2 = $line2_1 + 1;
        $line2_3 = $line2_2 + 1;
        $line2_4 = $line2_3 + 1;
        $line3_1 = $line2_4 + 1;
        $line3_2 = $line3_1 + 1;
        $line3_3 = $line3_2 + 2; //大型店舗用を非表示


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
        $arrNormalData_line3_3 = array();

        // 検索クエリ条件
        $arrQueryParam = array();
        $arrQueryParam['maker_id'] = $maker_id;

//★        if ($op4 == '' || $op4 == 1 || $op4 == 3 || $op4 == 4) {
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
            $arrCategoryNormalData = $this->getResults($arrQueryParam);

            foreach ($arrCategoryNormalData as $value) {
                $category_id = $value['category_id'];

                if ($category_id >= $line1_1_st + $start_id_plus && $category_id < $line1_1_ed) {
                    $key = floor(($category_id - $line1_1_st) / $id_type_interval);
                    if ($category_id == $line1_1_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line1_1[$line1_1_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line1_2_st + $start_id_plus && $category_id < $line1_2_ed) {
                    $key = floor(($category_id - $line1_2_st) / $id_type_interval);
                    if ($category_id == $line1_2_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line1_2[$line1_2_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line1_3_st + $start_id_plus && $category_id < $line1_3_ed) {
                    $key = floor(($category_id - $line1_3_st) / $id_type_interval);
                    if ($category_id == $line1_3_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line1_3[$line1_3_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line1_4_st + $start_id_plus && $category_id < $line1_4_ed) {
                    $key = floor(($category_id - $line1_4_st) / $id_type_interval);
                    if ($category_id == $line1_4_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line1_4[$line1_4_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line2_1_st + $start_id_plus && $category_id < $line2_1_ed) {
                    $key = floor(($category_id - $line2_1_st) / $id_type_interval);
                    if ($category_id == $line2_1_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line2_1[$line2_1_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line2_2_st + $start_id_plus && $category_id < $line2_2_ed) {
                    $key = floor(($category_id - $line2_2_st) / $id_type_interval);
                    if ($category_id == $line2_2_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line2_2[$line2_2_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line2_3_st + $start_id_plus && $category_id < $line2_3_ed) {
                    $key = floor(($category_id - $line2_3_st) / $id_type_interval);
                    if ($category_id == $line2_3_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line2_3[$line2_3_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line2_4_st + $start_id_plus && $category_id < $line2_4_ed) {
                    $key = floor(($category_id - $line2_4_st) / $id_type_interval);
                    if ($category_id == $line2_4_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line2_4[$line2_4_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line3_1_st + $start_id_plus && $category_id < $line3_1_ed) {
                    $key = floor(($category_id - $line3_1_st) / $id_type_interval);
                    if ($category_id == $line3_1_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line3_1[$line3_1_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line3_2_st + $start_id_plus && $category_id < $line3_2_ed) {
                    $key = floor(($category_id - $line3_2_st) / $id_type_interval);
                    if ($category_id == $line3_2_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line3_2[$line3_2_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                } else if ($category_id >= $line3_3_st + $start_id_plus && $category_id < $line3_3_ed) {
                    $key = floor(($category_id - $line3_3_st) / $id_type_interval);
                    if ($category_id == $line3_3_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrNormalData_line3_3[$line3_3_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key] . $arr_type_add[$op4-1]);
                    }
                }
            }

            if ($op4 == '') {
                // ▼冷房専用
                // 該当する商品があるカテゴリ情報を取得
                $arrQueryParam['comment3'] = explode(' ', $op_str[3]);
                $arrCategoryNormalReiData = $this->getResults($arrQueryParam);

                foreach ($arrCategoryNormalReiData as $value) {
                    $category_id = $value['category_id'];

                    if ($category_id >= $line1_1_st + $start_id_plus && $category_id < $line1_1_ed) {
                        $key = floor(($category_id - $line1_1_st) / $id_type_interval);
                        if ($category_id == $line1_1_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line1_1['reisen'] = array('id' => $line1_1);
                        }
                    } else if ($category_id >= $line1_2_st + $start_id_plus && $category_id < $line1_2_ed) {
                        $key = floor(($category_id - $line1_2_st) / $id_type_interval);
                        if ($category_id == $line1_2_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line1_2['reisen'] = array('id' => $line1_2);
                        }
                    } else if ($category_id >= $line1_3_st + $start_id_plus && $category_id < $line1_3_ed) {
                        $key = floor(($category_id - $line1_3_st) / $id_type_interval);
                        if ($category_id == $line1_3_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line1_3['reisen'] = array('id' => $line1_3);
                        }
                    } else if ($category_id >= $line1_4_st + $start_id_plus && $category_id < $line1_4_ed) {
                        $key = floor(($category_id - $line1_4_st) / $id_type_interval);
                        if ($category_id == $line1_4_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line1_4['reisen'] = array('id' => $line1_4);
                        }
                    } else if ($category_id >= $line2_1_st + $start_id_plus && $category_id < $line2_1_ed) {
                        $key = floor(($category_id - $line2_1_st) / $id_type_interval);
                        if ($category_id == $line2_1_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line2_1['reisen'] = array('id' => $line2_1);
                        }
                    } else if ($category_id >= $line2_2_st + $start_id_plus && $category_id < $line2_2_ed) {
                        $key = floor(($category_id - $line2_2_st) / $id_type_interval);
                        if ($category_id == $line2_2_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line2_2['reisen'] = array('id' => $line2_2);
                        }
                    } else if ($category_id >= $line2_3_st + $start_id_plus && $category_id < $line2_3_ed) {
                        $key = floor(($category_id - $line2_3_st) / $id_type_interval);
                        if ($category_id == $line2_3_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line2_3['reisen'] = array('id' => $line2_3);
                        }
                    } else if ($category_id >= $line2_4_st + $start_id_plus && $category_id < $line2_4_ed) {
                        $key = floor(($category_id - $line2_4_st) / $id_type_interval);
                        if ($category_id == $line2_4_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line2_4['reisen'] = array('id' => $line2_4);
                        }
                    } else if ($category_id >= $line3_1_st + $start_id_plus && $category_id < $line3_1_ed) {
                        $key = floor(($category_id - $line3_1_st) / $id_type_interval);
                        if ($category_id == $line3_1_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line3_1['reisen'] = array('id' => $line3_1);
                        }
                    } else if ($category_id >= $line3_2_st + $start_id_plus && $category_id < $line3_2_ed) {
                        $key = floor(($category_id - $line3_2_st) / $id_type_interval);
                        if ($category_id == $line3_2_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line3_2['reisen'] = array('id' => $line3_2);
                        }
                    } else if ($category_id >= $line3_3_st + $start_id_plus && $category_id < $line3_3_ed) {
                        $key = floor(($category_id - $line3_3_st) / $id_type_interval);
                        if ($category_id == $line3_3_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line3_3['reisen'] = array('id' => $line3_3);
                        }
                    }
                }

                // ▼寒冷地用
                // 該当する商品があるカテゴリ情報を取得
                $arrQueryParam['comment3'] = explode(' ', $op_str[4]);
                $arrCategoryNormalkanData = $this->getResults($arrQueryParam);

                foreach ($arrCategoryNormalkanData as $value) {
                    $category_id = $value['category_id'];

                    if ($category_id >= $line1_1_st + $start_id_plus && $category_id < $line1_1_ed) {
                        $key = floor(($category_id - $line1_1_st) / $id_type_interval);
                        if ($category_id == $line1_1_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line1_1['kanrei'] = array('id' => $line1_1);
                        }
                    } else if ($category_id >= $line1_2_st + $start_id_plus && $category_id < $line1_2_ed) {
                        $key = floor(($category_id - $line1_2_st) / $id_type_interval);
                        if ($category_id == $line1_2_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line1_2['kanrei'] = array('id' => $line1_2);
                        }
                    } else if ($category_id >= $line1_3_st + $start_id_plus && $category_id < $line1_3_ed) {
                        $key = floor(($category_id - $line1_3_st) / $id_type_interval);
                        if ($category_id == $line1_3_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line1_3['kanrei'] = array('id' => $line1_3);
                        }
                    } else if ($category_id >= $line1_4_st + $start_id_plus && $category_id < $line1_4_ed) {
                        $key = floor(($category_id - $line1_4_st) / $id_type_interval);
                        if ($category_id == $line1_4_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line1_4['kanrei'] = array('id' => $line1_4);
                        }
                    } else if ($category_id >= $line2_1_st + $start_id_plus && $category_id < $line2_1_ed) {
                        $key = floor(($category_id - $line2_1_st) / $id_type_interval);
                        if ($category_id == $line2_1_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line2_1['kanrei'] = array('id' => $line2_1);
                        }
                    } else if ($category_id >= $line2_2_st + $start_id_plus && $category_id < $line2_2_ed) {
                        $key = floor(($category_id - $line2_2_st) / $id_type_interval);
                        if ($category_id == $line2_2_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line2_2['kanrei'] = array('id' => $line2_2);
                        }
                    } else if ($category_id >= $line2_3_st + $start_id_plus && $category_id < $line2_3_ed) {
                        $key = floor(($category_id - $line2_3_st) / $id_type_interval);
                        if ($category_id == $line2_3_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line2_3['kanrei'] = array('id' => $line2_3);
                        }
                    } else if ($category_id >= $line2_4_st + $start_id_plus && $category_id < $line2_4_ed) {
                        $key = floor(($category_id - $line2_4_st) / $id_type_interval);
                        if ($category_id == $line2_4_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line2_4['kanrei'] = array('id' => $line2_4);
                        }
                    } else if ($category_id >= $line3_1_st + $start_id_plus && $category_id < $line3_1_ed) {
                        $key = floor(($category_id - $line3_1_st) / $id_type_interval);
                        if ($category_id == $line3_1_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line3_1['kanrei'] = array('id' => $line3_1);
                        }
                    } else if ($category_id >= $line3_2_st + $start_id_plus && $category_id < $line3_2_ed) {
                        $key = floor(($category_id - $line3_2_st) / $id_type_interval);
                        if ($category_id == $line3_2_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line3_2['kanrei'] = array('id' => $line3_2);
                        }
                    } else if ($category_id >= $line3_3_st + $start_id_plus && $category_id < $line3_3_ed) {
                        $key = floor(($category_id - $line3_3_st) / $id_type_interval);
                        if ($category_id == $line3_3_st + ($id_type_interval * $key) + $start_id_plus) {
                            $arrNormalData_line3_3['kanrei'] = array('id' => $line3_3);
                        }
                    }
                }
           }

//★        }

        $arrNormalData_line1[1] = $arrNormalData_line1_1;
        $arrNormalData_line1[2] = $arrNormalData_line1_2;
        $arrNormalData_line1[3] = $arrNormalData_line1_3;
        $arrNormalData_line1[4] = $arrNormalData_line1_4;
        $arrNormalData_line2[1] = $arrNormalData_line2_1;
        $arrNormalData_line2[2] = $arrNormalData_line2_2;
        $arrNormalData_line2[3] = $arrNormalData_line2_3;
        $arrNormalData_line2[4] = $arrNormalData_line2_4;
        $arrNormalData_line3[1] = $arrNormalData_line3_1;
        $arrNormalData_line3[2] = $arrNormalData_line3_2;
        $arrNormalData_line3[3] = $arrNormalData_line3_3;

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

                if ($category_id >= $line1_1_st + $start_id_plus && $category_id < $line1_1_ed) {
                    $key = floor(($category_id - $line1_1_st) / $id_type_interval);
                    if ($category_id == $line1_1_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line1_1[$line1_1_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line1_2_st + $start_id_plus && $category_id < $line1_2_ed) {
                    $key = floor(($category_id - $line1_2_st) / $id_type_interval);
                    if ($category_id == $line1_2_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line1_2[$line1_2_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line1_3_st + $start_id_plus && $category_id < $line1_3_ed) {
                    $key = floor(($category_id - $line1_3_st) / $id_type_interval);
                    if ($category_id == $line1_3_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line1_3[$line1_3_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line1_4_st + $start_id_plus && $category_id < $line1_4_ed) {
                    $key = floor(($category_id - $line1_4_st) / $id_type_interval);
                    if ($category_id == $line1_4_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line1_4[$line1_4_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line2_1_st + $start_id_plus && $category_id < $line2_1_ed) {
                    $key = floor(($category_id - $line2_1_st) / $id_type_interval);
                    if ($category_id == $line2_1_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line2_1[$line2_1_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line2_2_st + $start_id_plus && $category_id < $line2_2_ed) {
                    $key = floor(($category_id - $line2_2_st) / $id_type_interval);
                    if ($category_id == $line2_2_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line2_2[$line2_2_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line2_3_st + $start_id_plus && $category_id < $line2_3_ed) {
                    $key = floor(($category_id - $line2_3_st) / $id_type_interval);
                    if ($category_id == $line2_3_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line2_3[$line2_3_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line2_4_st + $start_id_plus && $category_id < $line2_4_ed) {
                    $key = floor(($category_id - $line2_4_st) / $id_type_interval);
                    if ($category_id == $line2_4_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line2_4[$line2_4_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line3_1_st + $start_id_plus && $category_id < $line3_1_ed) {
                    $key = floor(($category_id - $line3_1_st) / $id_type_interval);
                    if ($category_id == $line3_1_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line3_1[$line3_1_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line3_2_st + $start_id_plus && $category_id < $line3_2_ed) {
                    $key = floor(($category_id - $line3_2_st) / $id_type_interval);
                    if ($category_id == $line3_2_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line3_2[$line3_2_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
                } else if ($category_id >= $line3_3_st + $start_id_plus && $category_id < $line3_3_ed) {
                    $key = floor(($category_id - $line3_3_st) / $id_type_interval);
                    if ($category_id == $line3_3_st + ($id_type_interval * $key) + $start_id_plus) {
                        $arrSuperData_line3_3[$line3_3_st + ($id_type_interval * $key) + $start_id_plus] = array('value' => $arr_type[$key]);
                    }
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
        $arrSuperData_line2[4] = $arrSuperData_line2_4;
        $arrSuperData_line3[1] = $arrSuperData_line3_1;
        $arrSuperData_line3[2] = $arrSuperData_line3_2;
        $arrSuperData_line3[3] = $arrSuperData_line3_3;

        $this->arrSuperData_line1 = $arrSuperData_line1;
        $this->arrSuperData_line2 = $arrSuperData_line2;
        $this->arrSuperData_line3 = $arrSuperData_line3;
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
/*
            foreach ($arrParams['comment3'] as $value) {
                if ($value) {
                    $where .= "AND alldtl.comment3 LIKE ? ";
                    $arrValues[] = "%" . $value . "%";
                }
            }
*/
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
