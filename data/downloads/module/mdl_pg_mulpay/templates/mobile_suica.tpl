<!--{*
 * This file is part of EC-CUBE PAYMENT MODULE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.net/product/payment/
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
<center>モバイルSuica</center>

<hr>

事前にモバイルSuicaの会員登録が必要です。<br>
会員の登録方法についてはモバイルSuicaのサイトでご確認ください。<br>
<br>

<!--{* 決済時のエラーを表示 *}-->
<!--{assign var=key value="gmo_request"}-->
<!--{if $arrErr[$key]}-->
<font color="red">
エラーが発生しました。<br>
エラーコード：<!--{$arrErr[$key]|nl2br}--></font>
<br>
<!--{/if}-->

<form method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="mode" value="tran">
<input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />

■携帯メールアドレス<br>
<!--{assign var=key value="email"}-->
<!--{assign var=key2 value="email_all"}-->
<!--{if $arrErr[$key] != ""}-->
<font color="red"><!--{$arrErr[$key]}--></font>
<!--{/if}-->
<!--{if $arrErr[$key2] != ""}-->
<font color="red"><!--{$arrErr[$key2]}--></font>
<!--{/if}-->
<input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" size="40" istyle="40">@
<!--{assign var=key value="email_domain"}-->
<select name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" >
<!--{html_options options=$arrMobileMailDomain selected=$arrForm[$key].value|escape}-->
</select>
<br>
<br>

<!--{if $is2clickFlow}-->
<center><input type="submit" name="tran" value="決定する"></center>
<!--{else}-->
<font color="red">以上の内容で間違いなければ、下記「注文完了ページへ」ボタンをクリックしてください。<br>
ご注文完了ページへ切り替わるまで、他の操作を行わずにそのままお待ち下さい。 <br>
またご注文完了ページが表示されず、ご注文完了メールも受信できない場合は、<br>
お手数ですが、ショップまでご連絡くださいませ。<br></font>
<center><input type="submit" name="tran" value="注文完了ページへ"></center>
<!--{/if}-->
</form>
<form method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="mode" value="return">
<input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
<center><input type="submit" name="return" value="戻る"></center>
</form>

<br>
