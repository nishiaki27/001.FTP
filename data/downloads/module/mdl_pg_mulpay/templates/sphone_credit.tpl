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
<script type="text/javascript">//<![CDATA[

var done = {};
done.card_no1 = false;
done.card_no2 = false;
done.card_no3 = false;

function next(now, next) {
    if (now.value.length >= now.getAttribute('maxlength') && !done[now.name]) {
        next.focus();
        done[now.name] = true;
    } else if (now.value.length < now.getAttribute('maxlength')) {
        done[now.name] = false;
    }
}

var send = true;

function fnCheckSubmit(mode, key, val) {
    if(send) {
        send = false;
        fnModeSubmit(mode, key, val);
        return true;
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    }
}

function gmopgDeleteCard() {
    var cardSeq = $('input[name="CardSeq"]:checked').val();
    if (typeof cardSeq === "undefined") {
        alert("削除するカードを選択して下さい。");
        return false;
    } else {
        return fnCheckSubmit('deletecard', 'deleteCardSeq', cardSeq);
    }
}

//]]>
</script>
<!--▼CONTENTS-->
<section id="undercolumn">

<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|h}-->">
<input type="hidden" name="mode" value="register" />
<input type="hidden" name="transactionid" value="<!--{$transactionid|h}-->" />
<input type="hidden" name="usecard" value="" />
<input type="hidden" name="deleteCardSeq" value="" />

<h2 class="title">お支払い方法：<!--{$tpl_payment_method}--></h2>

    <!--{if $arrErr.gmo_request}-->
    <div class="information end">
      <p class="attention">エラーが発生しました：<!--{$arrErr.gmo_request|h}--></p>
      <!--{if $arrErr.gmo_request == 'E01-E01230009'}-->
      <p class="attention">カードの最大登録数５件を超えています。新しいカード情報を登録する場合には、
登録済みのカード情報を呼び出して、<br/>削除してください。</p>
      <!--{/if}-->
    </div>
    <!--{/if}-->

    <!--{if $enable_customer_regist}-->
    <div class="btn_area">
      <p><a href="#" class="btn data-role-none" onclick="fnCheckSubmit('getcard', '', '');return false;">登録済みのカード情報を呼び出す</a></p>
    </div>

    <!--{if $cardNum}-->
    <div class="form_area">
      <span class="attention">カードを選択して下さい</span>
      <ul>
	<!--{foreach name=cardloop from=$arrCardInfo item=card}-->
	<!--{if $card.DeleteFlag == 0}-->
	<li>
	  <input type="radio" name="CardSeq" id="CardSeq<!--{$card.CardSeq}-->" value="<!--{$card.CardSeq}-->">
	  <label for="CardSeq<!--{$card.CardSeq}-->">
	    <p><!--{$card.CardNo|h}-->&nbsp;&nbsp;<!--{$card.Expire|substr:2:4}-->月 / <!--{$card.Expire|substr:0:2}-->年</p>
	    <p><!--{$card.HolderName|h}--></p>
	  </label>
	</li>
	<!--{/if}-->
	<!--{/foreach}-->
      </ul>
    </div>
    <!--{/if}-->

    <!--{if $cardNum}-->
    <!--{if $credit_jobcd != '1'}-->
    <dl class="form_entry">
      <dt>お支払い方法<span class="attention">※</span></dt>
      <dd>
        <!--{assign var=key value="paymethod_usecard"}-->
        <span class="attention"><!--{$arrErr[$key]}--></span>
        <select name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="boxHalf data-role-none">
          <!--{html_options options=$arrPayMethod selected=$arrForm[$key].value|h}-->
        </select>
      </dd>
    </dl>
    <!--{else}-->
    <input type="hidden" name="paymethod_usecard" value="1-0" />
    <!--{/if}-->
      
    <div class="form_area">
      <!--{include file="`$smarty.const.MDL_PG_MULPAY_TEMPLATE_PATH`common_attention.tpl"}-->
    </div>

    <div class="btn_area">
      <ul class="btn_btm">
	<li><a href="#" onclick="fnCheckSubmit('register', 'usecard', '1');return false;" class="btn"><!--{if $is2clickFlow}-->決定する<!--{else}-->ご注文完了ページへ<!--{/if}--></a></li>
	<li><a href="#" onClick="return gmopgDeleteCard();" class="btn_back">削除する</a></li>
	<li><a href="#" onclick="fnModeSubmit('return','',''); return false;" class="btn_back">戻る</a></li>
      </ul>
    </div>

    <div>
      <br/><br/>
    </div>
    <!--{/if}-->
    <!--{/if}-->

    <dl class="form_entry">
      <dt>カード番号<span class="attention">※</span></dt>
      <dd>
      <!--{assign var=key value="card_no"}-->
      <!--{if $arrErr[$key1]}-->
      <div class="attention"><!--{$arrErr[$key]}--></div>
      <!--{/if}-->
      <input type="text"
             name="<!--{$key}-->"
             value=""
             maxlength="<!--{$arrForm[$key].length}-->"
             class="boxLong text data-role-none"
             style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/>
          <p class="mini">半角入力(例：1234567890123456)</p>
      </dd>

      <dt>有効期限<span class="attention">※</span></dt>
      <dd>
        <!--{assign var=key1 value="card_month"}-->
        <!--{assign var=key2 value="card_year"}-->
	<!--{if $arrErr[$key1]}-->
        <span class="attention"><!--{$arrErr[$key1]}--></span>
	<!--{/if}-->
	<!--{if $arrErr[$key2]}-->
        <span class="attention"><!--{$arrErr[$key2]}--></span>
	<!--{/if}-->
        <select name="<!--{$key1}-->" value="" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxHalf data-role-none">
          <option value="">--</option>
          <!--{html_options options=$arrMonth}-->
        </select>&nbsp;&nbsp;月&nbsp;&nbsp;/&nbsp;&nbsp;
        <select name="<!--{$key2}-->" value="" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" class="boxHalf data-role-none">
          <option value="">--</option>
          <!--{html_options options=$arrYear}-->
        </select>&nbsp;&nbsp;年
      </dd>

      <dt>カード名義(ローマ字名)<span class="attention">※</span></dt>
      <dd>
        <!--{assign var=key1 value="card_name01"}-->
        <span class="attention"><!--{$arrErr[$key1]}--></span>
        名&nbsp;<input type="text"
                          name="<!--{$key1}-->"
                          value=""
                          maxlength="<!--{$arrForm[$key1].length}-->"
                          style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"
                          class="boxHarf text data-role-none">
        <p class="mini">半角英字入力(例：TAROU)</p>
      </dd>

      <dt>カード名義(ローマ字姓)<span class="attention">※</span></dt>
      <dd>
        <!--{assign var=key2 value="card_name02"}-->
        <span class="attention"><!--{$arrErr[$key2]}--></span>
        姓&nbsp;<input type="text"
                          name="<!--{$key2}-->"
                          value=""
                          maxlength="<!--{$arrForm[$key2].length}-->"
                          style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"
                          class="boxHarf text data-role-none">
        <p class="mini">半角英字入力(例：YAMADA)</p>
      </dd>

      <!--{if $enable_security_code}-->
      <dt>セキュリティコード</dt>
      <dd>
        <!--{assign var=key value="security_code"}-->
        <span class="attention"><!--{$arrErr[$key]}--></span>
        <input type="text"
               name="<!--{$key}-->"
               value=""
               maxlength="<!--{$arrForm[$key].length}-->"
               class="boxShort text data-role-none"
               style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/><br/>
        <img src="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.USER_DIR}-->code_visa.gif" width="160" height="110" />
	<img src="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.USER_DIR}-->code_amex.gif" width="160" height="110" />
      </dd>
      <!--{/if}-->

      <!--{if $credit_jobcd != '1'}-->
      <dt>お支払い方法<span class="attention">※</span></dt>
      <dd>
        <!--{assign var=key value="paymethod"}-->
        <span class="attention"><!--{$arrErr[$key]}--></span>
        <select name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="boxHalf data-role-none">
          <!--{html_options options=$arrPayMethod selected=$arrForm[$key].value|h}-->
        </select>
      </dd>
      <!--{else}-->
      <input type="hidden" name="paymethod" value="1-0" />
      <!--{/if}-->

      <!--{if $enable_customer_regist}-->
      <dt>このカード情報を登録する</dt>
      <dd>
        <!--{assign var=key value="register_card"}-->
        <input type="checkbox"
	       id="<!--{$key}-->"
               name="<!--{$key}-->"
               value="1"
	       class="data-role-none"
	       <!--{if $is2clickFlow}--> disabled="disabled" <!--{/if}-->
               <!--{if $arrForm[$key].value || $isEnable2click}--> checked="checked" <!--{/if}--> />&nbsp;&nbsp;<label for="<!--{$key}-->"><!--{if $is2clickFlow}-->カード情報を登録します<!--{else}-->カード情報を登録する<!--{/if}--></label>
        <p class="mini">
          ※カード情報を登録すると次回以降、上の「登録済みのカード情報を呼び出す」ボタンで登録したカードを利用することができます。(最大5件まで登録できます)</p>
      </dd>
    </dl>
    <!--{/if}-->

    <div class="form_area">
      <!--{include file="`$smarty.const.MDL_PG_MULPAY_TEMPLATE_PATH`common_attention.tpl"}-->
    </div>

    <div class="btn_area">
      <ul class="btn_btm">
	<li><a href="#" onclick="fnCheckSubmit('register', '', '');return false;" class="btn"><!--{if $is2clickFlow}-->決定する<!--{else}-->ご注文完了ページへ<!--{/if}--></a></li>
	<li><a href="#" onclick="fnModeSubmit('return','',''); return false;" class="btn_back">戻る</a></li>
      </ul>
    </div>
      
</form>
</section>
<!--▲CONTENTS-->

<!--{* 「TOPへ」ボタンを無効にする *}-->
<script type="text/javascript">//<![CDATA[
function setTopButton(topURL) {}
//]]>
</script>
