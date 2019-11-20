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
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

if (file_exists(MODULE_REALDIR . "mdl_gmopg/inc/function.php")) {
    require_once MODULE_REALDIR . 'mdl_gmopg/inc/function.php';
}
/**
 * 商品詳細 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id:LC_Page_Products_Detail.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class LC_Page_Products_Detail extends LC_Page_Ex {

    /** ステータス */
    var $arrSTATUS;

    /** ステータス画像 */
    var $arrSTATUS_IMAGE;

    /** 発送予定日 */
    var $arrDELIVERYDATE;

    /** おすすめレベル */
    var $arrRECOMMEND;

    /** フォームパラメータ */
    var $objFormParam;

    /** アップロードファイル */
    var $objUpFile;

    /** モード */
    var $mode;

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrSTATUS = $masterData->getMasterData("mtb_status");
        $this->arrSTATUS_IMAGE = $masterData->getMasterData("mtb_status_image");
        $this->arrDELIVERYDATE = $masterData->getMasterData("mtb_delivery_date");
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
				
				$this->arrTopicPath = $this->lfTopicPath2($product_id);
				$idousaki = $_POST['mode'];
				$back_page = $_POST['back_page'];
				if ( $idousaki == "cart2" ){
					SC_Response_Ex::sendRedirect("https://www.tokyo-aircon.net/contact/?g=1");
				}
				if ( $idousaki == "cart3" ){
					if($fp = fopen($back_page, "r")){
						SC_Response_Ex::sendRedirect($back_page);
					}else{
						SC_Response_Ex::sendRedirect('https://www.tokyo-aircon.net/');
					}
				}
				if ( $idousaki == "cart4" ){
					SC_Response_Ex::sendRedirect("https://www.tokyo-aircon.net/contact/?g=2");
				}
    }

    /**
     * Page のAction.
     *
     * @return void
     */
    function action() {
        // 顧客クラスs
        $objCustomer = new SC_Customer_Ex();

        // パラメータ管理クラス
        $this->objFormParam = new SC_FormParam_Ex();
        // パラメータ情報の初期化
        $this->arrForm = $this->lfInitParam($this->objFormParam);
        // ファイル管理クラス
        $this->objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        // ファイル情報の初期化
        $this->objUpFile = $this->lfInitFile($this->objUpFile);


//ここからURL変更の為の改造部分
	// 型番を取得 - 20120724
	$product_model = htmlspecialchars($_GET["product_model"]);
	if(strstr($product_model,'XCS-')){
		$product_model=str_replace('-S','/S',$product_model);
	}

	if($product_model != ""){
		// PgSQLへ接続する
		$link = pg_connect("host=localhost port=5479 dbname=FS_ECCUBE user=f04-caa3 password=CDghNPJKAB") or die("PgSQLへの接続に失敗しました。");
		// 型番でデータベースを検索
		$sql = "select product_id from public.dtb_products_class where product_code = '" . $product_model . "';";
		$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
		$rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
		$product_id = $rows['product_id'];
		// PgSQLから切断
		pg_close($link);
	        // プロダクトIDの正当性チェック
	        $product_id = $this->lfCheckProductId($this->objFormParam->getValue('admin'),$product_id);
	}else{
		$product_id = $this->lfCheckProductId($this->objFormParam->getValue('admin'),$this->objFormParam->getValue('product_id'));
	}
//ここまでURL変更の為の改造部分 (121行目が元々の文章)

        // プロダクトIDの正当性チェック
        $this->mode = $this->getMode();

        // 規格選択セレクトボックスの作成
        $this->js_lnOnload .= $this->lfMakeSelect();

        $objProduct = new SC_Product_Ex();
        $objProduct->setProductsClassByProductIds(array($product_id));

        // 規格1クラス名
        $this->tpl_class_name1 = $objProduct->className1[$product_id];

        // 規格2クラス名
        $this->tpl_class_name2 = $objProduct->className2[$product_id];

        // 規格1
        $this->arrClassCat1 = $objProduct->classCats1[$product_id];

        // 規格1が設定されている
        $this->tpl_classcat_find1 = $objProduct->classCat1_find[$product_id];
        // 規格2が設定されている
        $this->tpl_classcat_find2 = $objProduct->classCat2_find[$product_id];

        $this->tpl_stock_find = $objProduct->stock_find[$product_id];
        $this->tpl_product_class_id = $objProduct->classCategories[$product_id]['']['']['product_class_id'];
        $this->tpl_product_type = $objProduct->classCategories[$product_id]['']['']['product_type'];

        $this->tpl_javascript .= 'classCategories = ' . SC_Utils_Ex::jsonEncode($objProduct->classCategories[$product_id]) . ';';
        $this->tpl_javascript .= 'function lnOnLoad(){' . $this->js_lnOnload . '}';
        $this->tpl_onload .= 'lnOnLoad();';

        // モバイル用 規格選択セレクトボックスの作成
        if(SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            $this->lfMakeSelectMobile($this, $product_id,$this->objFormParam->getValue('classcategory_id1'));
        }

        // 商品IDをFORM内に保持する
        $this->tpl_product_id = $product_id;

        switch($this->mode) {
            case 'cart':
                $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam,
                                                    $this->tpl_classcat_find1,
                                                    $this->tpl_classcat_find2);
                if (count($this->arrErr) == 0) {
                    $objCartSess = new SC_CartSession_Ex();
                    $product_class_id = $this->objFormParam->getValue('product_class_id');

                    $objCartSess->addProduct($product_class_id, $this->objFormParam->getValue('quantity'), $this->objFormParam->getValue('attribute'), $this->objFormParam->getValue('attribute2'), $this->objFormParam->getValue('gencho_sign'));

                    // カート「戻るボタン」用に保持
                    if (SC_Utils_Ex::sfIsInternalDomain($_SERVER['HTTP_REFERER'])) {
                        $_SESSION['cart_referer_url'] = $_SERVER['HTTP_REFERER'];
                    }

                    SC_Response_Ex::sendRedirect(CART_URLPATH);
                    exit;
                }
                break;
						
						case 'cart2':
						case 'cart3':
						case 'cart4':
                $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam,
                                                    $this->tpl_classcat_find1,
                                                    $this->tpl_classcat_find2);
                if (count($this->arrErr) == 0) {
									
                    $objCartSess = new SC_CartSession_Ex();
                    $product_class_id = $this->objFormParam->getValue('product_class_id');

										$objCartSess->addProduct($product_class_id, $this->objFormParam->getValue('quantity'), $this->objFormParam->getValue('attribute'), $this->objFormParam->getValue('attribute2'), $this->objFormParam->getValue('gencho_sign'));

										// カート「戻るボタン」用に保持
                    if (SC_Utils_Ex::sfIsInternalDomain($_SERVER['HTTP_REFERER'])) {
                        $_SESSION['cart_referer_url'] = $_SERVER['HTTP_REFERER'];
                    }

										// dtb_genchoテーブルに格納するデータを取得
										$quantity = $_POST['quantity'];
										$wquantity = $_POST['wquantity'];
										$ecost = $_POST['ecost'];
										$wcapability = $_POST['wcapability'];
										if( $wcapability == 6 ){ $wcost = $_POST['wcost']; }
										if( $wcapability == 8 ){ $wcost = $_POST['wcost2']; }
										$sessid = isset($_COOKIE["PHPSESSID"]) ? $_COOKIE["PHPSESSID"] : NULL;
										$kabu_flg = $_POST['kabu_flg'];
										$kabu_gid = $_POST['kabu_gid'];
										$attribute = $_POST['attribute'];
										$attribute2 = $_POST['attribute2'];
										
										// PgSQLへ接続する
										$link = pg_connect("host=localhost port=5479 dbname=FS_ECCUBE user=f04-caa3 password=CDghNPJKAB") or die("PgSQLへの接続に失敗しました。");
										
										//商品が重複している時の処理（セッションIDと商品IDが一致）
										if ( $kabu_flg == 1) {
											//パネルの色が一致している時の処理
											$att1_flg = 0;
											if( $attribute != "" && $attribute2 == "" ) {
												$sql_gencho = "select * from dtb_gencho where sess_id = '".$sessid."' AND product_id = ".$product_id." AND attribute = '".$attribute."'";
												$result_gencho = pg_query($link, $sql_gencho);
												if($result_gencho){
													$rows_gencho = pg_fetch_array($result_gencho, NULL, PGSQL_ASSOC);
													if( $rows_gencho['gencho_id'] ){
														$kabu_gid = $rows_gencho['gencho_id'];
														$att1_flg = 1;
													}
												}
											}
											//電源が一致している時の処理（セッションIDと商品IDが一致）
											$att2_flg = 0;
											if( $attribute2 != "" && $attribute == "" ) {
												$sql_gencho = "select * from dtb_gencho where sess_id = '".$sessid."' AND product_id = ".$product_id." AND attribute2 = '".$attribute2."'";
												$result_gencho = pg_query($link, $sql_gencho);
												if($result_gencho){
													$rows_gencho = pg_fetch_array($result_gencho, NULL, PGSQL_ASSOC);
													if( $rows_gencho['gencho_id'] ){
														$kabu_gid = $rows_gencho['gencho_id'];
														$att2_flg = 1;
													}
												}
											}
											//パネルと電源が一致している時の処理（セッションIDと商品IDが一致）
											$att3_flg = 0;
											if( $attribute == "" && $attribute2 == "" ) {
												$sql_gencho = "select * from dtb_gencho where sess_id = '".$sessid."' AND product_id = ".$product_id." AND attribute = '".$attribute."' AND attribute2 = '".$attribute2."'";
												$result_gencho = pg_query($link, $sql_gencho);
												if($result_gencho){
													$rows_gencho = pg_fetch_array($result_gencho, NULL, PGSQL_ASSOC);
													if( $rows_gencho['gencho_id'] ){
														$kabu_gid = $rows_gencho['gencho_id'];
														$att3_flg = 1;
													}
												}
											}
										}
										if ( $kabu_flg == 1 && $attribute == "" && $attribute2 == "" ) {
											//同じ商品だった場合・パネル・電源なし
											$sql_gencho = "select * from dtb_gencho where gencho_id = " . $kabu_gid . "";
											$result_gencho = pg_query($link, $sql_gencho) or die("クエリの送信に失敗しました。<br />SQL:".$sql_gencho);
											$rows_gencho = pg_fetch_array($result_gencho, NULL, PGSQL_ASSOC);
											$quantity = $rows_gencho['quantity'] + $quantity;
											$wquantity = $rows_gencho['wquantity'] + $wquantity;
											//dtb_genchoを上書き
											$sql = "UPDATE dtb_gencho SET wcost = ".$wcost.", quantity = ".$quantity.", wquantity = ".$wquantity." WHERE gencho_id = ".$kabu_gid."";
											$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
											//同じ商品でパネルや電源が同じだった場合
										}elseif( $att1_flg == 1 || $att2_flg == 1 || $att3_flg == 1 ){
											$sql_gencho = "select * from dtb_gencho where gencho_id = " . $kabu_gid . "";
											$result_gencho = pg_query($link, $sql_gencho) or die("クエリの送信に失敗しました。<br />SQL:".$sql_gencho);
											$rows_gencho = pg_fetch_array($result_gencho, NULL, PGSQL_ASSOC);
											$quantity = $rows_gencho['quantity'] + $quantity;
											$wquantity = $rows_gencho['wquantity'] + $wquantity;
											//dtb_genchoを上書き
											$sql = "UPDATE dtb_gencho SET wcost = ".$wcost.", quantity = ".$quantity.", wquantity = ".$wquantity." WHERE gencho_id = ".$kabu_gid."";
											$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
										}else{
											// dtb_genchoにデータ格納
											$sql = "INSERT INTO dtb_gencho (sess_id, product_id, ecost, wcost, quantity, wquantity, attribute, attribute2) VALUES ('".$sessid."',".$product_id.",".$ecost.",".$wcost.",".$quantity.",".$wquantity.",'".$attribute."','".$attribute2."')";
											$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
										}
										// PgSQLから切断
										pg_close($link);


                    //SC_Response_Ex::sendRedirect(CART_URLPATH);
                    //exit;
                }

								if( $_POST['name01'] || $_POST['kana01'] || $_POST['name02'] || $_POST['kana02'] || $_POST['zip01'] || $_POST['zip02'] || $_POST['pref'] || $_POST['addr01'] || $_POST['addr02'] || $_POST['tel01'] || $_POST['tel02'] || $_POST['tel03'] || $_POST['email'] || $_POST['email02'] || $_POST['contents']) {
									session_start();
									$_SESSION['contact']['name01'] = $_POST['name01'];
									$_SESSION['contact']['kana01'] = $_POST['kana01'];
									$_SESSION['contact']['name02'] = $_POST['name02'];
									$_SESSION['contact']['kana02'] = $_POST['kana02'];
									$_SESSION['contact']['zip01'] = $_POST['zip01'];
									$_SESSION['contact']['zip02'] = $_POST['zip02'];
									$_SESSION['contact']['pref'] = $_POST['pref'];
									$_SESSION['contact']['addr01'] = $_POST['addr01'];
									$_SESSION['contact']['addr02'] = $_POST['addr02'];
									$_SESSION['contact']['tel01'] = $_POST['tel01'];
									$_SESSION['contact']['tel02'] = $_POST['tel02'];
									$_SESSION['contact']['tel03'] = $_POST['tel03'];
									$_SESSION['contact']['email'] = $_POST['email'];
									$_SESSION['contact']['email02'] = $_POST['email02'];
									$_SESSION['contact']['contents'] = $_POST['contents'];
								}

								break;

						
            case "add_favorite":
                // ログイン中のユーザが商品をお気に入りにいれる処理
                if ($objCustomer->isLoginSuccess() === true && $this->objFormParam->getValue('favorite_product_id') > 0 ) {
                    $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam);
                    if(count($this->arrErr) == 0){
                        if(!$this->lfRegistFavoriteProduct($this->objFormParam->getValue('favorite_product_id'),$objCustomer->getValue('customer_id'))){
                            exit;
                        }
                    }
                }

            default:
                break;
        }

        // モバイル用 ポストバック処理
        if(SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            switch($this->mode) {
                case 'select':
                    // 規格1が設定されている場合
                    if($this->tpl_classcat_find1) {
                        // templateの変更
                        $this->tpl_mainpage = "products/select_find1.tpl";
                        break;
                    }

                case 'select2':
                    $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam,$this->tpl_classcat_find1,$this->tpl_classcat_find2);

                    // 規格1が設定されていて、エラーを検出した場合
                    if($this->tpl_classcat_find1 and $this->arrErr['classcategory_id1']) {
                        // templateの変更
                        $this->tpl_mainpage = "products/select_find1.tpl";
                        break;
                    }

                    // 規格2が設定されている場合
                    if($this->tpl_classcat_find2) {
                        $this->arrErr = array();

                        $this->tpl_mainpage = "products/select_find2.tpl";
                        break;
                    }

                case 'selectItem':
                    $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam,$this->tpl_classcat_find1,$this->tpl_classcat_find2);

                    // 規格2が設定されていて、エラーを検出した場合
                    if($this->tpl_classcat_find2 and $this->arrErr['classcategory_id2']) {
                        // templateの変更
                        $this->tpl_mainpage = "products/select_find2.tpl";
                        break;
                    }

                    $this->tpl_product_class_id = $objProduct->classCategories[$product_id][$this->objFormParam->getValue('classcategory_id1')]['#' . $this->objFormParam->getValue('classcategory_id2')]['product_class_id'];

                    // 数量の入力を行う
                    $this->tpl_mainpage = "products/select_item.tpl";
                    break;

                default:
                    $this->tpl_mainpage = "products/detail.tpl";
                    break;
            }
        }

        // 商品詳細を取得
        $this->arrProduct = $objProduct->getDetail($product_id);
		
        // ▼title・discription・keyword整形
        $make_subtitle = ereg_replace("  ", " ", strip_tags(ereg_replace("<br>", " ", $this->arrProduct['comment3'])));
        $parts_subtitle = explode(" ", $make_subtitle);

        $middle_title = "";
        $middle_key = "";
        $url_ar = explode('/', $_SERVER["REQUEST_URI"]); //ページURLを配列化
        if ($url_ar[2] == 'quality') {
            // 商品情報および特徴
            $middle_title = "の商品情報・特徴";
            $middle_key .= "商品情報,特徴,";
        } else if ($url_ar[2] == 'summary') {
            // カタログ情報
            $middle_title = "のエアコン形状・室外機";
            $middle_key .= "エアコン形状,室内機,室外機,";
        } else if ($url_ar[2] == 'catalog') {
            // カタログ情報
            $middle_title = "のカタログ・機能一覧";
            $middle_key .= "カタログ情報,機能,";
        } else if ($url_ar[2] == 'compare') {
            // 比較される商品
            $middle_title = "と比較される商品";
            $middle_key .= "比較される商品,";
        } else if ($url_ar[2] == 'power') {
            // 馬力と能力
            $middle_title = "の馬力と面積";
            $middle_key .= "馬力,面積,";
        } else {
            $middle_key .= "仕様,商品比較,";
        }
        $middle_key .= "商品詳細,送料無料," . str_replace("【", "", $parts_subtitle[0]) . ",";

        // サブタイトルを取得
        $this->tpl_middleTitle = $middle_title;
        $this->tpl_middleKey = $middle_key;
        $this->tpl_subtitle = strip_tags($this->arrProduct['comment4']) . $middle_title . "｜" . str_replace("【", "", $parts_subtitle[0]);
        $this->tpl_subtitle2 = $this->arrProduct['comment4'] . $middle_title . "｜" . str_replace("【", "", $parts_subtitle[0]);

        // 関連カテゴリを取得
        $this->arrRelativeCat = SC_Helper_DB_Ex::sfGetMultiCatTree($product_id);

        // 商品ステータスを取得
        $this->productStatus = $objProduct->getProductStatus($product_id);

        // 画像ファイル指定がない場合の置換処理
        $this->arrProduct['main_image']
            = SC_Utils_Ex::sfNoImageMain($this->arrProduct['main_image']);

        $this->subImageFlag = $this->lfSetFile($this->objUpFile,$this->arrProduct,$this->arrFile);
        //レビュー情報の取得
        $this->arrReview = $this->lfGetReviewData($product_id);

        //関連商品情報表示
        $this->arrRecommend = $this->lfPreGetRecommendProducts($product_id);

				//▼ 追加20170524 ▼

				//商品別情報取得

					// PgSQLへ接続する・データベース dtb_products_spec 呼び出し
					$link = pg_connect("host=".DB_SERVER." port=".DB_PORT." dbname=".DB_NAME." user=".DB_USER." password=".DB_PASSWORD) or die("PgSQLへの接続に失敗しました。");
					$sql = "select * from public.dtb_products_spec where product_id = ". $product_id ." and del_flg = 0";
					$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
					$rows = pg_fetch_array($result);
					$this->arrSpec =$rows;

					// PgSQLへ接続する・データベース dtb_products_feature 呼び出し
					$link = pg_connect("host=".DB_SERVER." port=".DB_PORT." dbname=".DB_NAME." user=".DB_USER." password=".DB_PASSWORD) or die("PgSQLへの接続に失敗しました。");
				if($this->arrSpec['product_id'] != ""){
					// 型番でデータベースを検索 ※form※
					$sql = "select * from public.dtb_products_feature where maker = '" . $this->arrSpec['maker'] . "' and flag1 = '". $this->arrSpec['form'] ."' and type = '". $this->arrSpec['type'] ."' and del_flg = 0";
					$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
					$rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
					$this->arrForm =$rows;

					// 型番でデータベースを検索 ※series※
					$sql = "select * from public.dtb_products_feature where maker = '" . $this->arrSpec['maker'] . "' and flag1 = '". $this->arrSpec['series'] ."' and type = '". $this->arrSpec['type'] ."' and del_flg = 0";
					$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
					$rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
					$this->arrSeries =$rows;

					// 型番でデータベースを検索 ※remote_control_type※
					$sql = "select * from public.dtb_products_feature where maker = '" . $this->arrSpec['maker'] . "' and flag1 = '". $this->arrSpec['remote_control_type'] ."' and type = '". $this->arrSpec['type'] ."' and del_flg = 0";
					$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
					$rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
					$this->arrRemote =$rows;

				}else{
					// 型番でデータベースを検索 ※form※
					$sql = "select * from public.dtb_products_feature where maker = '" . $this->arrSpec['maker'] . "' and flag1 = '". $this->arrSpec['form'] ."' and type = '". $this->arrSpec['type'] ."' and del_flg = 0";
					$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
					$rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
					$this->arrForm =$rows;

					// 型番でデータベースを検索 ※remote_control_type※
					$sql = "select * from public.dtb_products_feature where maker = '" . $this->arrSpec['maker'] . "' and flag1 = '". $this->arrSpec['remote_control_type'] ."' and type = '". $this->arrSpec['type'] ."' and del_flg = 0";
					$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
					$rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
					$this->arrRemote =$rows;

				}
				
				// 型番でデータベースを検索 ※dtb_products_introduction※
				if($this->arrProduct['sub_title1']){
					$sql = "select * from public.dtb_products_introduction where id = '" . $this->arrProduct['sub_title1'] ."'";
					$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
					$rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
					$this->arrIntroduction =$rows;
				}
				
				// PgSQLから切断
				pg_close($link);


				//▲ 追加20170524 ▲


        // ログイン判定
        if ($objCustomer->isLoginSuccess() === true) {
            //お気に入りボタン表示
            $this->tpl_login = true;
            $this->is_favorite = SC_Helper_DB_Ex::sfDataExists('dtb_customer_favorite_products', 'customer_id = ? AND product_id = ?', array($objCustomer->getValue('customer_id'), $product_id));
        }

        // パンくずリストを取得
    	$this->arrTopicPath = $this->lfTopicPath2($product_id);

/* CUORECUSTOM START */
        // 商品閲覧履歴保存
        $this->Set_ItemHistory();
/* CUORECUSTOM END */


		}

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /* プロダクトIDの正当性チェック */
    function lfCheckProductId($admin_mode,$product_id) {
        // 管理機能からの確認の場合は、非公開の商品も表示する。
        if (isset($admin_mode) && $admin_mode == 'on') {
            SC_Utils_Ex::sfIsSuccess(new SC_Session_Ex());
            $status = true;
            $where = 'del_flg = 0';
        } else {
            $status = false;
            $where = 'del_flg = 0 AND status = 1';
        }

        if(!SC_Utils_Ex::sfIsInt($product_id)
            || SC_Utils_Ex::sfIsZeroFilling($product_id)
            || !SC_Helper_DB_Ex::sfIsRecord('dtb_products', 'product_id', (array)$product_id, $where))
            SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
        return $product_id;
    }

    /* ファイル情報の初期化 */
    function lfInitFile($objUpFile) {
        $objUpFile->addFile("詳細-メイン画像", 'main_image', array('jpg'), IMAGE_SIZE, true, NORMAL_IMAGE_WIDTH, NORMAL_IMAGE_HEIGHT);
        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $objUpFile->addFile("詳細-サブ画像$cnt", "sub_image$cnt", array('jpg'), IMAGE_SIZE, false, NORMAL_SUBIMAGE_HEIGHT, NORMAL_SUBIMAGE_HEIGHT);
        }
        return $objUpFile;
    }

    /* 規格選択セレクトボックスの作成 */
    function lfMakeSelect() {
        return  'fnSetClassCategories('
            . 'document.form1, '
            . SC_Utils_Ex::jsonEncode($this->objFormParam->getValue('classcategory_id2'))
            . '); ';
    }

    /* 規格選択セレクトボックスの作成(モバイル) */
    function lfMakeSelectMobile(&$objPage, $product_id,$request_classcategory_id1) {

        $classcat_find1 = false;
        $classcat_find2 = false;
        // 在庫ありの商品の有無
        $stock_find = false;

        // 規格名一覧
        $arrClassName = SC_Helper_DB_Ex::sfGetIDValueList("dtb_class", "class_id", 'name');
        // 規格分類名一覧
        $arrClassCatName = SC_Helper_DB_Ex::sfGetIDValueList("dtb_classcategory", "classcategory_id", 'name');
        // 商品規格情報の取得
        $arrProductsClass = $this->lfGetProductsClass($product_id);

        // 規格1クラス名の取得
        $objPage->tpl_class_name1 = $arrClassName[$arrProductsClass[0]['class_id1']];
        // 規格2クラス名の取得
        $objPage->tpl_class_name2 = $arrClassName[$arrProductsClass[0]['class_id2']];

        // すべての組み合わせ数
        $count = count($arrProductsClass);

        $classcat_id1 = "";

        $arrSele1 = array();
        $arrSele2 = array();

        for ($i = 0; $i < $count; $i++) {
            // 在庫のチェック
            if($arrProductsClass[$i]['stock'] <= 0 && $arrProductsClass[$i]['stock_unlimited'] != '1') {
                continue;
            }

            $stock_find = true;

            // 規格1のセレクトボックス用
            if($classcat_id1 != $arrProductsClass[$i]['classcategory_id1']){
                $classcat_id1 = $arrProductsClass[$i]['classcategory_id1'];
                $arrSele1[$classcat_id1] = $arrClassCatName[$classcat_id1];
            }

            // 規格2のセレクトボックス用
            if($arrProductsClass[$i]['classcategory_id1'] == $request_classcategory_id1 and $classcat_id2 != $arrProductsClass[$i]['classcategory_id2']) {
                $classcat_id2 = $arrProductsClass[$i]['classcategory_id2'];
                $arrSele2[$classcat_id2] = $arrClassCatName[$classcat_id2];
            }
        }

        // 規格1
        $objPage->arrClassCat1 = $arrSele1;
        $objPage->arrClassCat2 = $arrSele2;

        // 規格1が設定されている
        if(isset($arrProductsClass[0]['classcategory_id1']) && $arrProductsClass[0]['classcategory_id1'] != '0') {
            $classcat_find1 = true;
        }

        // 規格2が設定されている
        if(isset($arrProductsClass[0]['classcategory_id2']) && $arrProductsClass[0]['classcategory_id2'] != '0') {
            $classcat_find2 = true;
        }

        $objPage->tpl_classcat_find1 = $classcat_find1;
        $objPage->tpl_classcat_find2 = $classcat_find2;
        $objPage->tpl_stock_find = $stock_find;
    }

    /* パラメータ情報の初期化 */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam("規格1", "classcategory_id1", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("規格2", "classcategory_id2", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("数量", 'quantity', INT_LEN, 'n', array("EXIST_CHECK", "ZERO_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("属性", 'attribute', LTEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("属性2", 'attribute2', LTEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("管理者ログイン", 'admin', INT_LEN, 'a', array('ALNUM_CHECK',"MAX_LENGTH_CHECK"));
        $objFormParam->addParam("商品ID", "product_id", INT_LEN, 'n', array("EXIST_CHECK", "ZERO_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お気に入り商品ID", "favorite_product_id", INT_LEN, 'n', array("ZERO_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("商品規格ID", "product_class_id", INT_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
				$objFormParam->addParam("現場調査", "gencho_sign", INT_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        // 値の取得
        $objFormParam->setParam($_REQUEST);
        // 入力値の変換
        $objFormParam->convParam();
        // 入力情報を渡す
        return $objFormParam->getFormParamList();
    }

    /* 商品規格情報の取得 */
    function lfGetProductsClass($product_id) {
        $objProduct = new SC_Product_Ex();
        return $objProduct->getProductsClassFullByProductId($product_id);
    }

    /* 登録済み関連商品の読み込み */
    function lfPreGetRecommendProducts($product_id) {
        $arrRecommend = array();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->setOrder("rank DESC");
        $arrRecommendData = $objQuery->select("recommend_product_id, comment", "dtb_recommend_products", "product_id = ?", array($product_id));

        $arrRecommendProductId = array();
        foreach($arrRecommendData as $recommend){
            $arrRecommendProductId[] = $recommend["recommend_product_id"];
            $arrRecommendData[$recommend["recommend_product_id"]] = $recommend['comment'];
        }

        $objProduct = new SC_Product_Ex();

        $where = "";
        if (!empty($arrRecommendProductId)) {
            $where = 'product_id IN (' . implode(',', $arrRecommendProductId) . ')';
        } else {
            return $arrRecommend;
        }
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->setWhere($where);
        $arrProducts = $objProduct->lists($objQuery, $arrRecommendProductId);

        //取得している並び順で並び替え
        // FIXME SC_Productあたりにソート処理はもってくべき
        $arrProducts2 = array();
        foreach($arrProducts as $item) {
            $arrProducts2[ $item['product_id'] ] = $item;
        }
        $arrProducts = array();
        foreach($arrRecommendProductId as $product_id) {
            $arrProducts2[$product_id]['comment'] = $arrRecommendData[$product_id];
            $arrRecommend[] = $arrProducts2[$product_id];
        }

        return $arrRecommend;
    }

    /* 入力内容のチェック */
    function lfCheckError($mode,&$objFormParam,$tpl_classcat_find1 = null ,$tpl_classcat_find2 = null) {

        switch ($mode) {
        case 'add_favorite':
            $objCustomer = new SC_Customer_Ex();
            $objErr = new SC_CheckError_Ex();
            $customer_id = $objCustomer->getValue('customer_id');
            if (SC_Helper_DB_Ex::sfDataExists('dtb_customer_favorite_products', 'customer_id = ? AND product_id = ?', array($customer_id, $favorite_product_id))) {
                $objErr->arrErr['add_favorite'.$favorite_product_id] = "※ この商品は既にお気に入りに追加されています。<br />";
            }
            break;
        default:
            // 入力データを渡す。
            $arrRet =  $objFormParam->getHashArray();
            $objErr = new SC_CheckError_Ex($arrRet);
            $objErr->arrErr = $objFormParam->checkError();

            // 複数項目チェック
            if ($tpl_classcat_find1) {
                $objErr->doFunc(array("規格1", "classcategory_id1"), array("EXIST_CHECK"));
            }
            if ($tpl_classcat_find2) {
                $objErr->doFunc(array("規格2", "classcategory_id2"), array("EXIST_CHECK"));
            }
            break;
        }

        return $objErr->arrErr;
    }

    //商品ごとのレビュー情報を取得する
    function lfGetReviewData($id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        //商品ごとのレビュー情報を取得する
        $col = "create_date, reviewer_url, reviewer_name, recommend_level, title, comment";
        $from = "dtb_review";
        $where = "del_flg = 0 AND status = 1 AND product_id = ? ORDER BY create_date DESC LIMIT " . REVIEW_REGIST_MAX;
        $arrval[] = $id;
        $arrReview = $objQuery->select($col, $from, $where, $arrval);
        return $arrReview;
    }

    /*
     * ファイルの情報をセットする
     * @return $subImageFlag
     */
    function lfSetFile($objUpFile,$arrProduct,&$arrFile) {
        // DBからのデータを引き継ぐ
        $objUpFile->setDBFileList($arrProduct);
        // ファイル表示用配列を渡す
        $arrFile = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH, true);

        // サブ画像の有無を判定
        $subImageFlag = false;
        for ($i = 1; $i <= PRODUCTSUB_MAX; $i++) {
            if ($arrFile["sub_image" . $i]['filepath'] != "") {
                $subImageFlag = true;
            }
        }
        return $subImageFlag;
    }

    /*
     * お気に入り商品登録
     * @return void
     */
    function lfRegistFavoriteProduct($favorite_product_id,$customer_id) {
        // ログイン中のユーザが商品をお気に入りにいれる処理
        if(!SC_Helper_DB_Ex::sfIsRecord("dtb_products", "product_id", $favorite_product_id, "del_flg = 0 AND status = 1")) {
            SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
            return false;
        } else {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $count = $objQuery->count("dtb_customer_favorite_products", "customer_id = ? AND product_id = ?", array($customer_id, $favorite_product_id));

            if ($count == 0) {
                $sqlval['customer_id'] = $customer_id;
                $sqlval['product_id'] = $favorite_product_id;
                $sqlval['update_date'] = "now()";
                $sqlval['create_date'] = "now()";

                $objQuery->begin();
                $objQuery->insert('dtb_customer_favorite_products', $sqlval);
                $objQuery->commit();
            }
            // お気に入りに登録したことを示すフラグ
            $this->just_added_favorite = true;
            return true;
        }
    }


    /*
     * パンくずリストを設定
     * @return array
     */
    function lfTopicPath2($product_id){
        $objDb = new SC_Helper_DB_Ex();
        $arrCategory_id = $objDb->sfGetCategoryId($product_id);
        $arrCatId = $objDb->sfGetParents("dtb_category", "parent_category_id", "category_id", $arrCategory_id[0]);
        foreach($arrCatId as $key => $val){
          $arrCatName = $objDb->sfGetCat($val);
          if( $val == 100000){
            $arrRet[] = '';
          }else{
	          if( $val == 200000){
		          $arrRet[] = '<a href="../housing/">'. $arrCatName['name'] . '</a> > ';
		        }else{
		        	$arrRet[] = '<a href="./list.php?category_id=' .$val. '&orderby=price">'. $arrCatName['name'] . '</a> > ';
		        }
	        }

        }
        $arrRet[] = '<a href="https://www.tokyo-aircon.net/products/'. str_replace('/S','-S',$this->arrProduct['comment4']) .'.html">'.mb_strimwidth (strip_tags($this->arrProduct['comment4']), 0, 75, "...",utf8). '</a>';
        return $arrRet;
    }



/* CUORECUSTOM START */
    /**
     * 商品閲覧履歴保存
     */
    function Set_ItemHistory()
    {

//ここからURL変更の為の改造部分
	// 型番を取得 - 20120724
	$product_model = htmlspecialchars($_GET["product_model"]);
	if($product_model != ""){
		// PgSQLへ接続する
		$link = pg_connect("host=localhost port=5479 dbname=FS_ECCUBE user=f04-caa3 password=CDghNPJKAB") or die("PgSQLへの接続に失敗しました。");
		// 型番でデータベースを検索
		$sql = "select product_id from public.dtb_products_class where product_code = '" . $product_model . "';";
		$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
		$rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
		$product_id = $rows['product_id'];
		// PgSQLから切断
		pg_close($link);

        //クッキーに重複項目がないか判定処理
        $duplicateFlg = true;
        if (count($_COOKIE['product']) > 0) {
            foreach ($_COOKIE['product'] as $name => $value) {
                if($value == $product_id){
                    $duplicateFlg = false;
                }
            }
        }

        //重複がない場合クッキーに設定
        $cnt = count($_COOKIE['product']);
        $cookie_time = time()+60*60*24*30;

        if($duplicateFlg){
            if($cnt < 5){
                $cnt = $cnt + 1;
                setcookie("product[".$cnt."]", $product_id, $cookie_time, '/');
            } else {
                $reNum = 1;
                if (count($_COOKIE) > 0) {
                    foreach ($_COOKIE['product'] as $key => $value) {
                        if($reNum > 1){
                            $setNum = $reNum -1;
                            setcookie("product[".$setNum."]", $value, $cookie_time, '/');
                        }
                        $reNum = $reNum + 1;
                    }
                    setcookie("product[5]", $product_id, $cookie_time, '/');
                }
            }
        }




	}else{


        //クッキーに重複項目がないか判定処理
        $duplicateFlg = true;
        if (count($_COOKIE['product']) > 0) {
            foreach ($_COOKIE['product'] as $name => $value) {
                if($value == $_GET['product_id']){
                    $duplicateFlg = false;
                }
            }
        }

        //重複がない場合クッキーに設定
        $cnt = count($_COOKIE['product']);
        $cookie_time = time()+60*60*24*30;

        if($duplicateFlg){
            if($cnt < 5){
                $cnt = $cnt + 1;
                setcookie("product[".$cnt."]", $_GET['product_id'], $cookie_time, '/');
            } else {
                $reNum = 1;
                if (count($_COOKIE) > 0) {
                    foreach ($_COOKIE['product'] as $key => $value) {
                        if($reNum > 1){
                            $setNum = $reNum -1;
                            setcookie("product[".$setNum."]", $value, $cookie_time, '/');
                        }
                        $reNum = $reNum + 1;
                    }
                    setcookie("product[5]", $_GET['product_id'], $cookie_time, '/');
                }
            }
        }

	}

    }
/* CUORECUSTOM END */
}
?>
