<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright (c) 2006 PAYGENT Co.,Ltd. All rights reserved.
 *
 * https://www.paygent.co.jp/
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
require_once realpath(dirname( __FILE__)) . "/LC_Page_Mdl_Paygent_Helper.php";
/**
 * 出力フィルタをフックできない2.11系のための拡張クラス
 */
class LC_Page_Mdl_Paygent_Helper_Ex_2_11 extends LC_Page_Mdl_Paygent_Helper {
	function sendResponse() {

		if (isset($this->objPlugin)) { // FIXME モバイルエラー応急対応
			// post-prosess処理(暫定的)
			$this->objPlugin->process($this);
		}

		$this->objDisplay->prepare($this);
		outputfilterPaygentCareerTransform($this->objDisplay->response->body, $this);
		$this->objDisplay->response->write();
	}
}

// 出力フィルタを咬ませる方法が2.11系と2.12系で異なる
$objPage = null;
if(strpos(ECCUBE_VERSION, '2.11.') === 0) {
	$objPage = new LC_Page_Mdl_Paygent_Helper_Ex_2_11(PAY_PAYGENT_CAREER);
} else  {
	$objPage = new LC_Page_Mdl_Paygent_Helper(PAY_PAYGENT_CAREER);
	// 出力フィルターを設定
	$objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->objPage->plugin_activate_flg);
	$objPlugin->addAction('outputfilterTransform', 'outputfilterPaygentCareerTransform');
}

$objPage->init();
$objPage->process();

// 出力バッファをフラッシュする
while (ob_get_level()) {
	ob_end_flush();
}

/**
 * Smarty出力フィルタ用コールバック関数
 *
 * @param &string    $source         レンダリング後のHTML文字列
 * @param LC_Page_Ex $objPage        LC_Page_Ex継承クラスのインスタンス
 * @param string     $current_file   不明
 */
function outputfilterPaygentCareerTransform(&$source, $objPage, $current_file=null) {

	// ソフトバンクまとめて支払い(B)対応...ページのcharsetをSJISに変換
	if (
		strpos($objPage->tpl_mainpage, 'paygent_career_d.tpl') !== FALSE
		&& preg_match('/NAME="pay_method" VALUE="softbank2"/', $source)
	) {
		switch(SC_Display_Ex::detectDevice()) {
			// PCとスマートフォンのみ実施
			case DEVICE_TYPE_PC :
			case DEVICE_TYPE_SMARTPHONE :
				// 出力用のエンコーディングを Shift JIS に設定し、出力バッファ処理を指定
				mb_http_output('SJIS-win');
				ob_start('mb_output_handler');
				// HTML内のmeta要素を置換
				$source = str_replace(
					array(
					    '<?xml version="1.0" encoding="'.CHAR_CODE.'"?>',
						'<meta charset="'.CHAR_CODE.'">',
						'<meta http-equiv="Content-Type" content="text/html; charset='.CHAR_CODE.'" />'
					)
					,array(
					    '<?xml version="1.0" encoding="SJIS-win"?>',
						'<meta charset="SJIS-win">',
						'<meta http-equiv="Content-Type" content="text/html; charset=SJIS-win" />'
					)
					, $source
				);

				/**
				 * Note:
				 * 下記の方式ではFireFox/Chromeで文字化けが発生したため上記の実装としました。
				 * 1.header()でContent-TypeをSJISに設定
				 * 2.preg_replace()でHTMLのmeta要素を置換
				 * 3.mb_convert_encoding()でHTMLをSJISに変換
				 */

				break;
			case DEVICE_TYPE_MOBILE :
			default:
				// nop
				break;
		}
	}
}
?>