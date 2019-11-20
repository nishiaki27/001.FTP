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
<input type="hidden" name="agent" value="<!--{$agent}-->">
<input type="hidden" name="php_session_id" value="<!--{$php_session_id}-->">
<input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">

<!--{if $tpl_error != ""}-->
<font color="#ff0000"><!--{$tpl_error}--></font><br><br>
<!--{/if}-->

下記に必要事項を入力してください。<br><br>

■キャリア決済選択<br>
<!--{assign var=key1 value="career_type"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
	<select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->">
		<option value="">ご選択ください</option>
		<!--{html_options options=$arrCareer selected=$arrForm[$key1].value|default:$career_type}-->
	</select>
<br><br>
<font size="2">
購入代金を、ご選択いただきました携帯電話キャリアの通話料金とまとめてお支払いいただけます。
<br>（各支払い方法は下記一覧をご確認ください）
</font>

<br><br><br>

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
■ドコモ払い<br>
画面からネットワーク暗証番号を入力いただくことでお支払が可能です。対応端末は、iモード契約のあるフィーチャーフォン、spモード契約のあるスマートフォン、およびPCの各種端末からもご利用いただけます。<br>
■auかんたん決済<br>
画面からau ID/パスワード、続いてセキュリティパスワードを入力いただくことでお支払が可能です。au IDを取得することで国内3キャリアのスマートフォンおよびPCの各種端末からもご利用いただけます。<br>
■ソフトバンク・ワイモバイルまとめて支払い<br>
画面から暗証番号（電話料金等を口座引落で決済されている場合）/セキュリティコード（電話料金等をカードで決済されている場合）を入力いただくことでお支払が可能です。対応端末は、softbankスマートフォン契約のあるスマートフォンとなります<font color="#FF0000">（フィーチャーフォンには対応しておりません）</font>。<br>
</font>

<br>
