<!--{*
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
*}-->
<form name="form1" method="post" action="<!--{$smarty.server.PHP_SELF|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="next">
<input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">

<!--{if $tpl_error != ""}-->
<font color="#ff0000"><!--{$tpl_error}--><br><!--{$tpl_error_detail}--></font><br><br>
<!--{/if}-->

下記に必要事項を入力してください。<br><br>

<!--{if $tpl_payment_image != ""}-->
■ご利用いただけるコンビニの種類<br>
<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$tpl_payment_image}-->"><br><br>
<!--{/if}-->

■コンビニ選択<br>
<!--{assign var=key1 value="cvs_company_id"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
	<select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->">
		<option value="">ご選択ください</option>
		<!--{html_options options=$arrConvenience selected=$arrForm[$key1].value}-->
	</select>
<br><br>

■利用者<br>
<font size="2">※ 特殊な漢字は使用できない場合がございます。</font><br>
<!--{assign var=key1 value="customer_family_name"}-->
<!--{assign var=key2 value="customer_name"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
<font color="#ff0000"><!--{$arrErr[$key2]}--></font>
姓<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" istyle="1" size="15"><br>
名<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" istyle="1" size="15">
<br><br>

■利用者(カナ)<br>
<font size="2">※カナに濁点（゛）や半濁点（゜）がある場合、該当記号のみ除外されます。予めご了承ください。</font><br>
<!--{assign var=key1 value="customer_family_name_kana"}-->
<!--{assign var=key2 value="customer_name_kana"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
<font color="#ff0000"><!--{$arrErr[$key2]}--></font>
セイ<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" istyle="2" size="15"><br>
メイ<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" istyle="2" size="15">
<br><br>

■お電話番号<br>
<!--{assign var=key1 value="customer_tel"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" istyle="4" size="20">
<br><br>

<font size="2">ご選択いただきましたコンビニエンスストアでのお支払いが可能です（各支払い方法は下記一覧をご確認ください）。
<br>なお、商品はお支払い後のご提供となります。</font>
<br><br>

<br>

以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br>
<font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>
<center><input type="submit" value="次へ"></center>
</form>
<form action="./load_payment_module.php" method="post">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="return">
<center><input type="submit" value="戻る"></center>
</form>

<font size="2">
■セブン-イレブン<br>
セブン-イレブンのレジ店頭にてお支払いが可能です。<br>
■ファミリーマート<br>
ファミリーマート店内に設置されている「Famiポート」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。<br>
■ローソン、ミニストップ<br>
ローソン、ミニストップ店内に設置されている「Loppi」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。<br>
■デイリーヤマザキ<br>
デイリーヤマザキのレジ店頭にてお支払いが可能です。<br>
■セイコーマート<br>
セイコーマート店内に設置されている「クラブステーション」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。<br>
</font>
