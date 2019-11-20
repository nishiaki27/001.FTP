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
<!--{if $paygent_token_js_url}-->
  <script type="text/javascript" src="<!--{$paygent_token_js_url}-->" charset="UTF-8"></script>
<!--{/if}-->

<script type="text/javascript">//<![CDATA[
var send = false;

window.onunload=function onunloadCashClear() {
    if (send) {
        send = false;
        return false;
    } else {
        return false;
    }
}

window.onload=function onloadCashClear() {
    if (send) {
        send = false;
        return false;
    } else {
        return false;
    }
}

function fnCheckClearSubmit() {
    if(send) {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    } else {
        send = true;

        document.form1.card_no01.removeAttribute('name');
        document.form1.card_no02.removeAttribute('name');
        document.form1.card_no03.removeAttribute('name');
        document.form1.card_no04.removeAttribute('name');
        document.form1.card_month.removeAttribute('name');
        document.form1.card_year.removeAttribute('name');
        document.form1.card_name02.removeAttribute('name');
        document.form1.card_name01.removeAttribute('name');

        if (document.form1.security_code != null) {
            document.form1.security_code.removeAttribute('name');
        }

        var element = document.createElement("input");
        element.name='deletecard';
        document.form1.appendChild(element);

        document.form1.submit();
    }
}

var merchant_id= <!--{$merchant_id}-->;
var token_key= "<!--{$token_key|h}-->";
var paygent_token_connect_url= "<!--{$paygent_token_connect_url}-->";

<!--{$token_js}-->

function startCreateToken() {

    var form = document.form1;

    //二重注文制御
    if(send) {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    } else {

        send = true;

        //登録カード使用の場合
        if (form.stock != null && form.stock.checked == true) {

            //セキュリティーコード未使用時はトークン生成しない
            if (form.security_code == null) {
                form.submit();
                return true;
            }
            callCreateTokenCvc();
        } else {
            callCreateToken();
        }
    }
}

//]]>
</script>

<form method="post" action="<!--{$smarty.server.PHP_SELF|h}-->" name="form1">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="next">
<input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">
<input type="hidden" name="card_token" value="">
<input type="hidden" name="card_token_stock" value="">

<!--{if $tpl_error != ""}-->
<font color="#ff0000"><!--{$tpl_error}--></font><br /><br />
<!--{/if}-->

下記に必要事項を入力してください。<br /><br />

<!--{if $tpl_payment_image != ""}-->
■ご利用いただけるカードの種類<br>
<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$tpl_payment_image}-->"><br /><br />
<!--{/if}-->

■支払回数<br>
<!--{assign var=key1 value="payment_class"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
<select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
<!--{html_options options=$arrPaymentClass selected=$arrForm[$key1].value}-->
</select>
<br /><br />

<!--{if $cnt_card >= 1}-->
■登録カード<br>
<!--{assign var=key1 value="CardSeq"}-->
<!--{assign var=key2 value="stock"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
<input type="checkbox" name="stock" value="1" <!--{if $arrForm[$key2].value == 1}-->checked<!--{/if}-->>登録カードを利用する<br />
<font size="2">登録カードをご利用の方は、カード情報の入力は不要です。<br /><font color="#ff6600">入力されても適用されませんので、ご注意ください。</font></font>
<table border>
  <tr>
    <td>選択</th>
    <td>カード番号</td>
    <td>有効期限</td>
    <td>カード名義</td>
  </tr>
  <!--{foreach name=cardloop from=$arrCardInfo item=card}-->
  <tr>
    <td><input type="radio" name="<!--{$key1}-->" value="<!--{$card[$key1]}-->" <!--{if $arrForm[$key1].value == $card[$key1]}-->checked<!--{/if}-->></td>
    <td><!--{$card.CardNo}--></td>
    <td><!--{$card.Expire|substr:0:2}-->月/<!--{$card.Expire|substr:2:4}-->年</td>
    <td><!--{$card.HolderName}--></td>
  </tr>
  <!--{/foreach}-->
</table>
<!--{if $token_pay == 1}-->
<input type="submit" name="deletecard" onclick="fnCheckClearSubmit();return false" value="選択カードの削除">
<!--{else}-->
<input type="submit" name="deletecard" value="選択カードの削除">
<!--{/if}-->
<br /><br />
<!--{/if}-->

■カード番号<br>
<font size="2" color="#ff6600">ご本人名義のカードをご使用ください。</font><br />
半角入力（例：1234-5678-9012-3456）</font><br />
<!--{assign var=key1 value="card_no01"}-->
<!--{assign var=key2 value="card_no02"}-->
<!--{assign var=key3 value="card_no03"}-->
<!--{assign var=key4 value="card_no04"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
<font color="#ff0000"><!--{$arrErr[$key2]}--></font>
<font color="#ff0000"><!--{$arrErr[$key3]}--></font>
<font color="#ff0000"><!--{$arrErr[$key4]}--></font>
<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" size="6" istyle="4">-
<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" size="6" istyle="4">-
<input type="text" name="<!--{$key3}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" size="6" istyle="4">-
<input type="text" name="<!--{$key4}-->" value="<!--{$arrForm[$key4].value|h}-->" maxlength="<!--{$arrForm[$key4].length}-->" size="6" istyle="4">
<br /><br />

<!--{if $security_code == 1}-->
■セキュリティコード<br>
<!--{assign var=key1 value="security_code"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" size="6" istyle="4">
<br /><br />
<!--{/if}-->

■有効期限<br>
<!--{assign var=key1 value="card_month"}-->
<!--{assign var=key2 value="card_year"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
<font color="#ff0000"><!--{$arrErr[$key2]}--></font>
<select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->">
<option value="">--</option>
<!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
</select>月/
<select name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->">
<option value="">--</option>
<!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
</select>年
<br /><br />

■カード名義（ローマ字）<br>
<font size="2">半角入力（例：TARO YAMADA）</font><br />
<!--{assign var=key2 value="card_name01"}-->
<!--{assign var=key1 value="card_name02"}-->
<font color="#ff0000"><!--{$arrErr[$key1]}--></font>
<font color="#ff0000"><!--{$arrErr[$key2]}--></font>
名<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" istyle="3" size="15"><br />
姓<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" istyle="3" size="15">
<br /><br />

<!--{if $stock_flg == 1}-->
■カード登録<br>
<!--{assign var=key1 value="stock_new"}-->
<input type="checkbox" name="stock_new" value="1" <!--{if $arrForm[$key1].value == 1}-->checked<!--{/if}-->>登録する<br />
<font size="2">カード情報を登録しておくと、次回以降の購入時にカード情報入力が省略でき、大変便利です。<br />最大<!--{$smarty.const.CARD_STOCK_MAX}-->件まで登録できます。</font>
<br /><br />
<!--{/if}-->

<br>

以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
<font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br />

<!--{if $token_pay == 1}-->
<font size="2" color="#ff6600">※JavaScriptが利用できない端末では正常に動作致しません。</font><br />
<center><input type="submit" onclick="startCreateToken();return false" value="次へ"></center>
<!--{else}-->
<center><input type="submit" value="次へ"></center>
<!--{/if}-->

</form>
<form action="./load_payment_module.php" method="post">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="return">
<center><input type="submit" value="戻る"></center>
</form>

