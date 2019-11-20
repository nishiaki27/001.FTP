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

function set_shohin_name($kataban, $txt) {
	$text = mb_convert_kana($txt, 'kvrn');
	$str = split(" ", $text);

	if (preg_match("/.*ﾊﾟﾈﾙ$/", $str[0])) {
		$str[1] = str_replace("ﾊﾟﾈﾙ：", "：", $str[1]);
	} else {
		if (preg_match("/.*(ﾌﾗｯﾄ)/", $str[0])) {
			$str[0] = str_replace("(ﾌﾗｯﾄ)", "", $str[0]);
			$str[0] = str_replace("ﾊﾟﾈﾙ：", "ﾌﾗｯﾄﾊﾟﾈﾙ：", $str[0]);
		}
	}
	$str_text = implode(" ", $str);

	$str_shohin = $kataban . " " . $str_text;
	$str_shohin = str_replace(" ：", "：", $str_shohin);
	$len = mb_strlen($str_shohin);

	if ($len > 40) {
	//40文字以上の場合、色の名称など省略化
	}
	return $str_shohin;
}

function post_enc($text) {
	$str_text = trim($text);
	$str_text = nl2br($str_text);
	$str_text = htmlspecialchars($str_text);
	return $str_text;
}

function comp_name($name) {
	$company_name = str_replace("㈱", "株式会社", $name);
	$company_name = str_replace("㈲", "有限会社", $company_name);
	return $company_name;
}

$company_name = comp_name($_POST["company"]);

$text = "■店舗CD：" . post_enc($_POST["shop_cd"]) ."<br>
■店舗名：" . post_enc($_POST["shop_name"])."<br>
■掛け率：0.05<br>
■ご注文番号：" . post_enc($_POST["order_id"])."<br>
■担当者CD：" . post_enc($_POST["tanto_cd"])."<br>
<br>
■件名：" . post_enc($_POST["order_title"])."<br>
■会社名：" . $company_name."<br>
■お名前：" . post_enc($_POST["name"])."<br>
■郵便番号：〒" . post_enc($_POST["zip"])."<br>
■住所：" . post_enc($_POST["addr"])."<br>
■電話番号：" . post_enc($_POST["tel"])."<br>
";

$num = $_POST["shohin_count"];
for ($i = 1; $i <= $num; $i++) {
	$shohin_name = set_shohin_name($_POST["kataban_$i"], $_POST["shohin_data_$i"]);

	$text .= "<br>
■商品CD：" . post_enc($_POST["kikan_cd_$i"]) . "<br>
";
	if ($_POST["shiire_cd_$i"]) {
		$text .= "■仕入先：" . post_enc($_POST["shiire_cd_$i"]) . "<br>
";
	}
	$text .= "■規格：" . post_enc($_POST["kataban_$i"]) . "<br>
■商品名：" . post_enc(mb_convert_kana($shohin_name, 'kvrn')) . "<br>
■単価：" . post_enc($_POST["tanka_$i"]) . "<br>
■数量：" . post_enc($_POST["count_$i"]) . "<br>
";
}
$msg = str_replace("<br>", "", $text);

//言語設定、内部エンコーディングを指定する
mb_language("japanese");
mb_internal_encoding("UTF-8");

//日本語メール送信
$to = "tokyo-aircon_import@mitax.co.jp,hayashi@mitax.co.jp,dowaki@mitax.co.jp,saitou@mitax.co.jp";
$subject = "空調センター基幹取込用：No." . htmlspecialchars($_POST["order_id"]);
$body = mb_convert_encoding($msg, "ISO-2022-JP-ms", "UTF-8");
$from = "info@tokyo-aircon.net";

//基幹宛
if (mb_send_mail($to, $subject, $body, "From:" . $from)) {
	$kikan_msg = "基幹取込送信完了しました。";
} else {
	$kikan_msg ="基幹取込送信失敗しました。";
}

//空調センター宛控え
$to = "info@tokyo-aircon.net";
$subject = "基幹取込空調センター控え用：No." . htmlspecialchars($_POST["order_id"]);
if (mb_send_mail($to, $subject, $body, "From:" . $from)) {
	$setsubi_msg = "空調センター用控えメール送信完了しました。";
} else {
	$setsubi_msg = "空調センター用控えメール送信失敗しました。";
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
	<head>
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
		<meta http-equiv="content-script-type" content="text/javascript" />
		<meta http-equiv="content-style-type" content="text/css" />
		<link rel="stylesheet" href="/user_data/packages/admin/css/admin_contents.css" type="text/css" media="all" />
		<script type="text/javascript" src="/js/navi.js"></script>
		<script type="text/javascript" src="/js/win_op.js"></script>
		<script type="text/javascript" src="/js/site.js"></script>
		<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/user_data/packages/admin/js/admin.js"></script>
		<script type="text/javascript" src="/js/css.js"></script>
		<title>基幹宛送信結果</title>
	</head>

	<body>
		<div align="center" style="padding-top:20px">
			<div align="left" style="width:80%;">
				<table>
					<tr><th>基幹送信結果</th><td><? echo $kikan_msg ?></td></tr>
					<tr><th>空調センター送信結果</th><td><? echo $setsubi_msg ?></td></tr>
				</table>以下の内容で送信しました。

				<div style="border:1px solid #a9a9a9;padding:10px;margin:5px 0 10px 0;">
					<? echo $text ?>
				</div>
			</div>
			<a class="btn-action" onclick="window.close(); return false;"><span class="btn-next">閉じる</span></a>
		</div>
	</body>
</html>