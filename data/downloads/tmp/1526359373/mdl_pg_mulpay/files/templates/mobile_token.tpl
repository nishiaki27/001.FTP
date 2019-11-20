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
<script src="<!--{$js_urlpath}-->/ext/js/token.js"></script>
<script type="text/javascript">
    function execPurchase(response) {
        if (response.resultCode != 000) {
            window.alert("購入処理中にエラーが発生しました");
            window.location.assign(window.location.href);
        } else {
            //カード情報は念のため値をhttp://sccm.tma.com.vn/CMApplicationCatalog/#/SoftwareLibrary/AppListPageView.xaml
            document.getElementById("card_no").value = '';

            var securityCode = document.getElementById("security_code");
            if (securityCode != null) {
                document.getElementById("security_code").value = '';
            }

            //予め購入フォームに用意した token フィールドに、値を設定
            document.getElementById("token").value = response.tokenObject.token;
            document.getElementById("paymethod").value = document.getElementById("method").value;
            document.getElementById("mode").value = 'register';
            document.getElementById("registerCard").value = '';
            var register_card = document.getElementById("register_card");
            if (register_card != null && register_card.checked) {
                document.getElementById("registerCard").value = '1';
            }
            document.getElementById("cardYear").value = document.getElementById("card_year").value;
            document.getElementById("cardMonth").value = document.getElementById("card_month").value;
            document.getElementById("cardName01").value = document.getElementById("card_name01").value;
            document.getElementById("cardName02").value = document.getElementById("card_name02").value;
            //スクリプトからフォームを submit 
            document.getElementById("purchaseForm").submit();
        }
    }

    function doPurchase() {
        var cardno = document.getElementById("card_no").value;
        if (cardno == "") {
            alert("※ カード番号が入力されていません。");
            return;
        }

        var date = new Date();
        var year = date.getFullYear();
        year = String(year);
        year = year.substring(0,2);
        var mm = document.getElementById("card_month").value;
        if (mm == "") {
            alert("※ 有効期限：月が入力されていません。");
            return;
        }
        var yy = document.getElementById("card_year").value;
        if (yy == "") {
            alert("※ 有効期限：年が入力されていません。");
            return;
        }
        var expire = year + yy + mm;

        var card_name1 = document.getElementById("card_name01").value;
        if (card_name1 == "") {
            alert("※ カード名義人名：名が入力されていません。");
            return;
        }
        var card_name2 = document.getElementById("card_name02").value;
        if (card_name2 == "") {
            alert("※ カード名義人名：姓が入力されていません。");
            return;
        }
        var holdername = card_name1.concat(card_name2);

        var securityCode;
        var security_code = document.getElementById("security_code");

        if (security_code == null || security_code.value == "") {
            securityCode = '';
        } else {
            securityCode = security_code.value;
        }

        // Disable button 
        var btnNext, btnBack, classBtnNext, classBtnBack;
        btnNext = document.getElementById("next");
        btnBack = document.getElementById("back03");
        classBtnNext = document.getElementById("next").getAttribute('class');
        classBtnBack = document.getElementById("back03").getAttribute('class');
        btnNext.setAttribute("class", classBtnNext + " disabled");
        btnBack.setAttribute("class", classBtnBack + " disabled");

        Multipayment.init("<!--{$tshop}-->");
        Multipayment.getToken(
            {
                cardno: cardno,
                expire: expire,
                securitycode: securityCode,
                holdername: holdername
            }, execPurchase
        );
    }
</script>
<center>クレジット決済</center>

<hr>

<!--{* 決済時のエラーを表示 *}-->
<!--{assign var=key value="gmo_request"}-->
<!--{if $arrErr[$key]}-->
<font color="red">
エラーが発生しました。<br>
エラーコード：<!--{$arrErr[$key]|nl2br}-->
<!--{if $arrErr[$key] == 'E01-E01230009'}-->
<br>カードの最大登録数５件を超えています。新しいカード情報を登録する場合には、
登録済みのカード情報を呼び出して、削除してください。<br>
<!--{/if}-->
</font>
<br>
<!--{/if}-->

<form method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="mode" value="register">
<input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
<!--{if $enable_customer_regist}-->
■登録したカードで注文<br>
<input type="submit" name="getcard" value="登録カードを呼び出す"><br><br>
    <!--{* カード一覧ここから *}-->
    <!--{if $cardNum}-->
    <!--{if $arrErr.CardSeq}--><br><font color="red">カードを選択して下さい</font><br><!--{/if}-->
    <table>
    <tr>
        <td>選択</td>
        <td>カード番号</td>
        <td>有効期限</td>
        <td>名義人</td>
    </tr>
    <!--{foreach name=cardloop from=$arrCardInfo item=card}-->
        <!--{if $card.DeleteFlag == 0}-->
        <tr>
            <td><input type="radio" name="CardSeq" value="<!--{$card.CardSeq}-->"></td>
            <td><!--{$card.CardNo|substr:-8:8}--></td>
            <td><!--{$card.Expire|substr:2:4}-->月/<!--{$card.Expire|substr:0:2}-->年</td>
            <td><!--{$card.HolderName}--></td><!--{* 名義人 *}-->
        </tr>
        <!--{/if}-->
    <!--{/foreach}-->
    </table>
    <!--{/if}-->
    <!--{* カード一覧ここまで *}-->
    
    <!--{if $cardNum}-->
    <!--{if $credit_jobcd != '1'}-->
    <br>
    ■お支払い方法<br>
    <!--{assign var=key value="paymethod_usecard"}-->
    <font color="red"><!--{$arrErr[$key]}--></font><br>
    <select name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->">
    <!--{html_options options=$arrPayMethod selected=$arrForm[$key].value|escape}-->
    </select>
    <!--{else}-->
    <input type="hidden" name="paymethod_usecard" value="1-0" />
    <!--{/if}-->
    <br>
    <br>
    <!--{if $is2clickFlow}-->
    <input type="submit" name="register" value="選択したカードで購入">
    <!--{else}-->
    <font color="red">以上の内容で間違いなければ、下記「選択したカードで購入」ボタンをクリックしてください。<br>
    ご注文完了ページへ切り替わるまで、ご注文完了メールも受信できない場合は、<br>
    お手数ですが、ショップまでご連絡くださいませ。<br></font>
    <input type="submit" name="register" value="選択したカードで購入"><br>
    <!--{/if}-->
    <br>
    <input type="submit" name="deletecard" value="選択したカードの削除"><br><br>
    <!--{/if}-->
<hr><br>
<!--{/if}-->

■カード番号<br>
<!--{assign var=key value="card_no"}-->
<!--{if $arrErr[$key] != ""}-->
<font color="red"><!--{$arrErr[$key]}--></font>
<!--{/if}-->
<input type="text" id="<!--{$key}-->" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" size="<!--{$arrForm[$key].length}-->" istyle="<!--{$arrForm[$key].length}-->">
<br>

■有効期限<br>
<!--{assign var=key_month value="card_month"}-->
<!--{if $arrErr[$key_month] != ""}-->
<font color="red"><!--{$arrErr[$key_month]}--></font>
<!--{/if}-->
<!--{assign var=key_year value="card_year"}-->
<!--{if $arrErr[$key_year] != ""}-->
<font color="red"><!--{$arrErr[$key_year]}--></font>
<!--{/if}-->
<select id="<!--{$key_month}-->" name="<!--{$key_month}-->">
<!--{html_options options=$arrMonth selected=$arrForm[$key_month].value}-->
</select>
月
<select id="<!--{$key_year}-->" name="<!--{$key_year}-->">
<!--{html_options options=$arrYear selected=$arrForm[$key_year].value}-->
</select>
年
<br><br>

■ローマ字氏名<br>
<!--{assign var=key_name01 value="card_name01"}-->
<!--{if $arrErr[$key_name01] != ""}-->
<font color="red"><!--{$arrErr[$key_name01]}--></font>
<!--{/if}-->
<!--{assign var=key_name02 value="card_name02"}-->
<!--{if $arrErr[$key_name02] != ""}-->
<font color="red"><!--{$arrErr[$key_name02]}--></font>
<!--{/if}-->
名<input type="text" id="<!--{$key_name01}-->" name="<!--{$key_name01}-->" value="<!--{$arrForm[$key_name01].value|escape}-->" size="10" istyle="3">
姓<input type="text" id="<!--{$key_name02}-->" name="<!--{$key_name02}-->" value="<!--{$arrForm[$key_name02].value|escape}-->" size="10" istyle="3"><br>

半角英字入力<br>
例：TARO YAMADA<br>
<br>

<!--{if $enable_security_code}-->
■セキュリティコード<br>
<!--{assign var=key value="security_code"}-->
<!--{if $arrErr[$key] != ""}-->
<font color="red"><!--{$arrErr[$key]}--></font>
<!--{/if}-->
<input type="text" id="<!--{$key}-->" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" size="<!--{$arrForm[$key].length}-->"><br>
<img src="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.USER_DIR}-->code_visa.gif" width="160" height="110" /><br>
<img src="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.USER_DIR}-->code_amex.gif" width="160" height="110" /><br><br>
<!--{/if}-->

<!--{if $credit_jobcd != '1'}-->
■お支払い方法<br>
<!--{assign var=key value="method"}-->
<!--{if $arrErr[$key] != ""}-->
<font color="red"><!--{$arrErr[$key]}--></font>
<!--{/if}-->
<select id="<!--{$key}-->" name="<!--{$key}-->">
<!--{html_options options=$arrPayMethod selected=$arrForm[$key].value}-->
</select>
<!--{else}-->
<input type="hidden" name="method" value="1-0" />
<!--{/if}-->

<br>
<br>
<!--{if $enable_customer_regist}-->
■このカード情報を登録する<br>
<br>
<!--{assign var=key value="register_card"}-->
<input type="checkbox"
       id="<!--{$key}-->"
       name="<!--{$key}-->"
       value="1"
       <!--{if $is2clickFlow}--> disabled="disabled" <!--{/if}-->
       class="button" <!--{if $arrForm[$key].value || $isEnable2click}--> checked=checked <!--{/if}-->/>登録する
<br>
※カード情報を登録すると、<br>
次回以降、「登録済みのカード情報を呼び出す」ボタンで<br>
登録したカードを利用することが出来ます。(最大5件まで)<br>
<br>
<br>
<!--{/if}-->

<!--{if $is2clickFlow}-->
<center><input type="button" id="next" name="register" value="決定する" onclick="doPurchase();"></center>
<!--{else}-->
<font color="red">以上の内容で間違いなければ、下記「注文完了ページへ」ボタンをクリックしてください。<br>
ご注文完了ページへ切り替わるまで、他の操作を行わずにそのままお待ち下さい。 <br>
またご注文完了ページが表示されず、ご注文完了メールも受信できない場合は、<br>
お手数ですが、ショップまでご連絡くださいませ。<br></font>
<center><input type="button" id="next" name="register" value="注文完了ページへ" onclick="doPurchase();"></center>
<!--{/if}-->
</form>
<form id="purchaseForm" name="purchaseForm" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
  <p>
    <input type="hidden" value="" id="paymethod" name="paymethod" />
    <input type="hidden" value="" id="token" name="token" />
    <input type="hidden" value="" id="mode" name="mode"/>
    <input type="hidden" value="" id="registerCard" name="register_card"/>
    <input type="hidden" value="" id="cardYear" name="card_year" />
    <input type="hidden" value="" id="cardMonth" name="card_month" />
    <input type="hidden" value="" id="cardName01" name="card_name01" />
    <input type="hidden" value="" id="cardName02" name="card_name02" />
  </p>
</form>
<form method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="mode" value="return">
<input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
<center><input type="submit" id="back03" name="return" value="戻る"></center>
</form>

<br>
