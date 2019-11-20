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

<!--{if $show_attention}-->
<br><br>
<font color="#ff0000">
ご注文者情報を変更する場合は、MYページの「登録内容変更」から登録内容を変更してください。<br>
お届け先情報を変更する場合は、「お届け先の指定」まで戻って登録内容を変更してください。<br>
</font>
<!--{/if}-->

<!--{if $is_available_later != 1}-->
下記に必要事項を入力してください。<br><br>
<!--{/if}-->

<!--{if $tpl_payment_image != ""}-->
■ご利用いただける決済の種類<br>
<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$tpl_payment_image}-->"><br><br>
<!--{/if}-->

<!--{if $is_available_later != 1}-->
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
<!--{/if}-->

<br>

以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br>
<font size="2" color="#ff6600">
※決済用サイトに遷移します。ドメインが変わりますが、そのままお手続きを進めてください。<br>
※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。<br>
</font>
<center><input type="submit" value="次へ"></center>
</form>
<form action="./load_payment_module.php" method="post">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="return">
<center><input type="submit" value="戻る"></center>
</form>
