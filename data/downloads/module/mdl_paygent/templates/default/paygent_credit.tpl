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
<style type="text/css">
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

<!--▼CONTENTS-->
<div id="undercolumn">
<div id="undercolumn_shopping">
  <p class="flow_area"><img src="<!--{$TPL_URLPATH}-->img/picture/img_flow_03.jpg" alt="購入手続きの流れ" /></p>
  <h2 class="title1"><!--{$tpl_payment_method}--></h2>

  <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  <input type="hidden" name="mode" value="next">
  <input type="hidden" name="delete_card" value="" />
  <input type="hidden" name="card_token" value="">
  <input type="hidden" name="card_token_stock" value="">
  <table summary="お支払詳細入力" class="delivname">
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
          <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$tpl_payment_image}-->">
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
	 <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="3">
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
                    名&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;
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
          <br /><p class="mini">カード情報を登録しておくと、次回以降の購入時にカード情報入力が省略でき、大変便利です。<br />最大<!--{$smarty.const.CARD_STOCK_MAX}-->件まで登録できます。</p>
        </td>
      </tr>
      <!--{/if}-->
      </div>
    </tbody>
  </table>

  <table>
    <tr>
      <td>
                以上の内容で間違いなければ、下記「完了ページへ」ボタンをクリックしてください。<br />
        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
      </td>
    </tr>
  </table>

  <div class="btn_area">
    <ul>
      <li>
        <input type="image" onclick="return fnCheckClearSubmit('return');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back03" id="back03"/>
      </li>
      <li>
        <!--{if $token_pay == 1}-->
          <input type="image" onclick="startCreateToken();return false" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_order_comp_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_order_comp_on.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_order_comp.jpg" alt="完了ページへ" border="0" name="next" id="next" />
        <!--{else}-->
          <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" border="0" name="next" id="next" />
        <!--{/if}-->
      </li>
    </ul>
  </div>
  </form>

</div>
</div>
<!--▲CONTENTS-->