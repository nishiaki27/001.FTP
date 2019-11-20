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
                fnModeSubmit('next', '', '');
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


<!--{*** EC-CUBE 2.11.1以前用と2.11.2以降用の2種類のテンプレートを定義しています。 ***}-->
<!--{if preg_match('/^2\.11\.[0-1]$/', $smarty.const.ECCUBE_VERSION)}-->
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<!--
div#under02column_shopping table.stockcard {
    width: 540px;
    text-align: center;
    margin: 5px auto;
}

div#under02column_shopping table.stockcard tbody th {
    text-align: center;
    white-space: nowrap;
}

div#under02column_shopping table.stockcard td {
    text-align: center;
    white-space: nowrap;
}
-->
</style>
<script type="text/javascript">//<![CDATA[
var send = false;

window.onunload=function(){
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

function fnCheckSubmit(mode) {
    if(send) {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    } else {
        send = true;
        fnModeSubmit(mode,'','');
        return true;
    }
}

function fnCheckClearSubmit(mode) {
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

        fnModeSubmit(mode,'','');
        return true;
    }
}

function fnCngStock() {
    arr_obj = new Array('card_no', 'card_exp', 'card_hold', 'card_stock');
    flg = document.form1.stock.checked;
    for (i=0; i < arr_obj.length; i++) {
        obj = document.all && document.all(arr_obj[i]) || document.getElementById && document.getElementById(arr_obj[i]);
        if (flg) {
            obj.style.display = "none";
        } else {
            obj.style.display = "";
        }
    }
}

function next(now, next) {
    if (now.value.length >= now.getAttribute('maxlength')) {
    next.focus();
    }
}
//]]>
</script>

<!--▼CONTENTS-->
<div id="undercolumn">
<div id="undercolumn_shopping">
  <h2 class="title"><!--{$tpl_payment_method}--><h2>

    <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="next">
    <input type="hidden" name="delete_card" value="" />
    <input type="hidden" name="card_token" value="">
    <input type="hidden" name="card_token_stock" value="">

  <table summary="お支払詳細入力" class="entryform">
    <tbody>
      <!--{if $tpl_error != ""}-->
      <tr>
        <td colspan="2">
          <span class="attention"><!--{$tpl_error}--></span>
        </td>
      </tr>
      <!--{/if}-->
      <!--{if $tpl_payment_image != ""}-->
      <tr>
        <th>ご利用いただけるカードの種類</th>
        <td>
          <img src="<!--{$smarty.const.IMAGE_SAVE_URL}--><!--{$tpl_payment_image}-->">
        </td>
      </tr>
      <!--{/if}-->
      <tr>
        <th>支払回数</th>
        <td>
          <!--{assign var=key1 value="payment_class"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
          <!--{html_options options=$arrPaymentClass selected=$arrForm[$key1].value}-->
          </select>
        </td>
      </tr>
      <!--{if $cnt_card >= 1}-->
      <tr>
        <th>登録カード</th>
        <td>
          <!--{assign var=key1 value="CardSeq"}-->
          <!--{assign var=key2 value="stock"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <input type="checkbox" name="stock" value="1" onclick="fnCngStock();" <!--{if $arrForm[$key2].value == 1}-->checked<!--{/if}-->>登録カードを利用する
          <table class="stockcard">
            <tr>
              <th>選択</th>
              <th>カード番号</th>
              <th>有効期限</th>
              <th>カード名義</th>
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
          <input type="button" onClick="return fnCheckClearSubmit('deletecard');" value="選択カードの削除">
        </td>
      </tr>
      <!--{/if}-->
      <tr id="card_no">
        <th>カード番号</th>
        <td>
          <!--{assign var=key1 value="card_no01"}-->
          <!--{assign var=key2 value="card_no02"}-->
          <!--{assign var=key3 value="card_no03"}-->
          <!--{assign var=key4 value="card_no04"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
          <span class="attention"><!--{$arrErr[$key3]}--></span>
          <span class="attention"><!--{$arrErr[$key4]}--></span>
          <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="6" onkeyup="next(this, this.form.<!--{$key2}-->)" >&nbsp;-&nbsp;
          <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key2]|sfGetErrorColor}-->"  size="6" onkeyup="next(this, this.form.<!--{$key3}-->)" >&nbsp;-&nbsp;
          <input type="text" name="<!--{$key3}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key3]|sfGetErrorColor}-->"  size="6" onkeyup="next(this, this.form.<!--{$key4}-->)" >&nbsp;-&nbsp;
          <input type="text" name="<!--{$key4}-->" value="<!--{$arrForm[$key4].value|h}-->" maxlength="<!--{$arrForm[$key4].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key4]|sfGetErrorColor}-->"  size="6">
          <br /><p class="mini"><span class="attention">ご本人名義のカードをご使用ください。</span><br />半角入力（例：1234-5678-9012-3456）</p>
        </td>
      </tr>
      <!--{if $security_code == 1}-->
      <tr id="card_security">
    <th>セキュリティコード</th>
    <td>
     <!--{assign var=key1 value="security_code"}-->
     <span class="attention"><!--{$arrErr[$key1]}--></span>
     <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="6">
    </td>
      </tr>
      <!--{/if}-->
      <tr id="card_exp">
        <th>有効期限</th>
        <td>
          <!--{assign var=key1 value="card_month"}-->
          <!--{assign var=key2 value="card_year"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
          <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
          <option value="">--</option>
          <!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
          </select>月/
          <select name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" >
          <option value="">--</option>
          <!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
          </select>年
        </td>
      </tr>
      <tr id="card_hold">
        <th>カード名義<br />（ローマ字）</th>
        <td>
          <!--{assign var=key2 value="card_name01"}-->
          <!--{assign var=key1 value="card_name02"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
                    名&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;<br>
                    姓&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20">
          <br /><p class="mini">半角入力（例：TARO YAMADA）</p>
        </td>
      </tr>
      <!--{if $stock_flg == 1}-->
      <tr id="card_stock">
        <th>カード登録</th>
        <td>
          <!--{assign var=key1 value="stock_new"}-->
          <input type="checkbox" name="stock_new" value="1" <!--{if $arrForm[$key1].value == 1}-->checked<!--{/if}-->>登録する
          <br /><p class="mini">カード情報を登録しておくと、次回以降の購入時にカード情報入力が省略でき、大変便利です。<br />最大5件まで登録できます。</p>
        </td>
      </tr>
      <!--{/if}-->
      </div>
    </tbody>
  </table>

  <table>
    <tr>
      <td>
                以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
      </td>
    </tr>
  </table>

  <div class="tblareabtn">

    <!--{if $token_pay == 1}-->
    <p><input type="submit" onclick="startCreateToken();return false" value="次へ" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="next" id="next" /></p>
    <!--{else}-->
    <p><input type="submit" onclick="return fnCheckSubmit('next');" value="次へ" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="next" id="next" /></p>
    <!--{/if}-->
        <p><input type="submit" onclick="return fnCheckClearSubmit('return');" value="戻る" class="spbtn spbtn-medeum" alt="戻る" name="return" id="return" /></p>
  </div>

  </form>

</div>
</div>
<!--▲CONTENTS-->
<!--{else}-->
<style type="text/css">
<!--
table {
    margin: 15px auto 20px auto;
    border-top: 1px solid #ccc;
    border-left: 1px solid #ccc;
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}
table th {
    padding: 8px;
    border-right: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    color: #333;
    background-color: #f0f0f0;
    font-weight: normal;
}
table td {
    padding: 8px;
    border-right: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
}
-->
</style>
<script type="text/javascript">//<![CDATA[
var send = false;

window.onunload=function(){
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

function fnCheckSubmit(mode) {
    if(send) {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    } else {
        send = true;
        fnModeSubmit(mode,'','');
        return true;
    }
}

function fnCheckClearSubmit(mode) {
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

        fnModeSubmit(mode,'','');
        return true;
    }
}

function fnCngStock() {
    arr_obj = new Array('card_no_dt', 'card_no_dd', 'card_exp_dt', 'card_exp_dd', 'card_hold_dt', 'card_hold_dd', 'card_stock_dt', 'card_stock_dd');
    flg = document.form1.stock.checked;
    for (i=0; i < arr_obj.length; i++) {
        obj = document.all && document.all(arr_obj[i]) || document.getElementById && document.getElementById(arr_obj[i]);
        if (flg) {
            obj.style.display = "none";
        } else {
            obj.style.display = "";
        }
    }
}

function next(now, next) {
    if (now.value.length >= now.getAttribute('maxlength')) {
    next.focus();
    }
}
//]]>
</script>

<!--▼コンテンツここから -->
<section id="undercolumn">

    <h2 class="title"><!--{$tpl_payment_method}--></h2>

    <!--★インフォメーション★-->
    <div class="information end">
      <!--{if $tpl_error != ""}-->
        <p><span class="attention"><!--{$tpl_error}--></span></p>
      <!--{/if}-->
      <!--{if $tpl_payment_image != ""}-->
        <p>ご利用いただけるカードの種類<br><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$tpl_payment_image}-->"></p>
      <!--{/if}-->
    </div>

    <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="next">
        <input type="hidden" name="delete_card" value="" />
        <input type="hidden" name="card_token" value="">
        <input type="hidden" name="card_token_stock" value="">

        <dl class="form_entry">
            <dt>支払回数</dt>
            <dd>
                <!--{assign var=key1 value="payment_class"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxLong data-role-none">
                    <!--{html_options options=$arrPaymentClass selected=$arrForm[$key1].value}-->
                </select>
            </dd>

          <!--{if $cnt_card >= 1}-->
            <dt>登録カード</dt>
            <dd>
                <!--{assign var=key1 value="CardSeq"}-->
                <!--{assign var=key2 value="stock"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <input type="checkbox" name="stock" value="1" onclick="fnCngStock();" class="data-role-none" <!--{if $arrForm[$key2].value == 1}-->checked<!--{/if}-->>&nbsp;登録カードを利用する

                <table border='1' class="stockcard">
                    <tr>
                        <th>選択</th>
                        <th>カード番号</th>
                        <th>有効期限</th>
                        <th>カード名義</th>
                    </tr>
                  <!--{foreach name=cardloop from=$arrCardInfo item=card}-->
                    <tr>
                        <td><input type="radio" name="<!--{$key1}-->" value="<!--{$card[$key1]}-->" class="data-role-none" <!--{if $arrForm[$key1].value == $card[$key1]}-->checked<!--{/if}-->></td>
                        <td><!--{$card.CardNo}--></td>
                        <td><!--{$card.Expire|substr:0:2}-->月/<!--{$card.Expire|substr:2:4}-->年</td>
                        <td><!--{$card.HolderName}--></td>
                    </tr>
                  <!--{/foreach}-->
                </table>

                <input type="button" onClick="return fnCheckClearSubmit('deletecard');" value="選択カードの削除" class="data-role-none">
            </dd>
          <!--{/if}-->
            <dt id="card_no_dt">カード番号</dt>
            <dd id="card_no_dd">
                <!--{assign var=key1 value="card_no01"}-->
                <!--{assign var=key2 value="card_no02"}-->
                <!--{assign var=key3 value="card_no03"}-->
                <!--{assign var=key4 value="card_no04"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <span class="attention"><!--{$arrErr[$key2]}--></span>
                <span class="attention"><!--{$arrErr[$key3]}--></span>
                <span class="attention"><!--{$arrErr[$key4]}--></span>
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" class="text data-role-none" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="6" onkeyup="next(this, this.form.<!--{$key2}-->)" >&nbsp;-
                <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" class="text data-role-none" style="ime-mode: disabled; <!--{$arrErr[$key2]|sfGetErrorColor}-->"  size="6" onkeyup="next(this, this.form.<!--{$key3}-->)" >&nbsp;-
                <input type="text" name="<!--{$key3}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" class="text data-role-none" style="ime-mode: disabled; <!--{$arrErr[$key3]|sfGetErrorColor}-->"  size="6" onkeyup="next(this, this.form.<!--{$key4}-->)" >&nbsp;-
                <input type="text" name="<!--{$key4}-->" value="<!--{$arrForm[$key4].value|h}-->" maxlength="<!--{$arrForm[$key4].length}-->" class="text data-role-none" style="ime-mode: disabled; <!--{$arrErr[$key4]|sfGetErrorColor}-->"  size="6"><br />
                <p class="mini">
                    <span class="attention">ご本人名義のカードをご使用ください。</span><br />
                    半角入力（例：1234-5678-9012-3456）
                </p>
            </dd>

          <!--{if $security_code == 1}-->
            <dt>セキュリティコード</dt>
            <dd>
                <!--{assign var=key1 value="security_code"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" class="text data-role-none" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="3">
            </dd>
          <!--{/if}-->

            <dt id="card_exp_dt">有効期限</dt>
            <dd id="card_exp_dd">
                <!--{assign var=key1 value="card_month"}-->
                <!--{assign var=key2 value="card_year"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <span class="attention"><!--{$arrErr[$key2]}--></span>
                <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" class="boxShort data-role-none" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
                    <option value="">--</option>
                    <!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
                </select>月/
                <select name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" class="boxShort data-role-none" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" >
                    <option value="">--</option>
                    <!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
                </select>年
            </dd>

            <dt id="card_hold_dt">カード名義<br />（ローマ字）</dt>
            <dd id="card_hold_dd">
                <!--{assign var=key2 value="card_name01"}-->
                <!--{assign var=key1 value="card_name02"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <span class="attention"><!--{$arrErr[$key2]}--></span>
                名&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" class="boxShort text data-role-none" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;
                姓&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" class="boxShort text data-role-none" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20"><br />
                <p class="mini">半角入力（例：TARO YAMADA）</p>
            </dd>

          <!--{if $stock_flg == 1}-->
            <dt id="card_stock_dt">カード登録</dt>
            <dd id="card_stock_dd">
                <!--{assign var=key1 value="stock_new"}-->
                <input type="checkbox" name="stock_new" value="1" class="data-role-none" <!--{if $arrForm[$key1].value == 1}-->checked<!--{/if}-->>&nbsp;登録する<br />
                <p class="mini">
                    カード情報を登録しておくと、次回以降の購入時にカード情報入力が省略でき、大変便利です。<br />最大<!--{$smarty.const.CARD_STOCK_MAX}-->件まで登録できます。
                </p>
            </dd>
          <!--{/if}-->
        </dl>

        <div class="btn_area">
            <p>
                以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
                <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
            </p>

            <ul class="btn_btm">
                <!--{if $token_pay == 1}-->
                    <li><a href="javascript:startCreateToken();" class="btn">次へ</a></li>
                <!--{else}-->
                    <li><a href="javascript:fnCheckSubmit('next');" class="btn">次へ</a></li>
                <!--{/if}-->
                <li><a href="javascript:fnCheckClearSubmit('return');" class="btn_back">戻る</a></li>
            </ul>
        </div>

        <iframe style="height:0px;width:0px;visibility:hidden" src="about:blank">
            this frame prevents back forward cache
        </iframe>

    </form>
</section>
<!--{/if}-->