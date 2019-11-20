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
<script src="<!--{$js_urlpath}-->/ext/js/token.js"></script>
<script type="text/javascript">
    function execPurchase(response) {
        if (response.resultCode != 000) {
            window.alert("購入処理中にエラーが発生しました");
            window.location.assign(window.location.href);
        } else {
            //カード情報は念のため値をhttp://sccm.tma.com.vn/CMApplicationCatalog/#/SoftwareLibrary/AppListPageView.xaml
            $("#card_no").val('');

            if ($("#security_code").size()) {
                $("#security_code").val('');
            }

            //予め購入フォームに用意した token フィールドに、値を設定
            $("#token").val(response.tokenObject.token);
            $("#paymethod").val($("#method").val());
            $("#mode").val('register');
            $("#registerCard").val('');
            var register_card = $("#register_card");
            if (register_card.size() && register_card.is(":checked")) {
                $("#registerCard").val('1');
            }
            $("#cardYear").val($("#card_year").val());
            $("#cardMonth").val($("#card_month").val());
            $("#cardName01").val($("#card_name01").val());
            $("#cardName02").val($("#card_name02").val());
            //スクリプトからフォームを submit 
            $("#purchaseForm").submit();
        }
    }

    function doPurchase() {
        var cardno = $("#card_no").val();
        if (cardno == "") {
            alert("※ カード番号が入力されていません。");
            return;
        }

        var date = new Date();
        var year = date.getFullYear();
        year = String(year);
        year = year.substring(0,2);
        var mm = $("#card_month").val();
        if (mm == "") {
            alert("※ 有効期限：月が入力されていません。");
            return;
        }
        var yy = $("#card_year").val();
        if (yy == "") {
            alert("※ 有効期限：年が入力されていません。");
            return;
        }
        var expire = year + yy + mm;

        var card_name1 = $("#card_name01").val();
        if (card_name1 == "") {
            alert("※ カード名義人名：名が入力されていません。");
            return;
        }
        var card_name2 = $("#card_name02").val();
        if (card_name2 == "") {
            alert("※ カード名義人名：姓が入力されていません。");
            return;
        }
        var holdername = card_name1.concat(card_name2);

        var securityCode;
        var security_code = $("#security_code");
        if (!security_code.size() || security_code.val() == "") {
            securityCode = '';
        } else {
            securityCode = security_code.val();
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
	     id="<!--{$key}-->"
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
        <select id="<!--{$key1}-->" name="<!--{$key1}-->" value="" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxHalf data-role-none">
          <option value="">--</option>
          <!--{html_options options=$arrMonth}-->
        </select>&nbsp;&nbsp;月&nbsp;&nbsp;/&nbsp;&nbsp;
        <select id="<!--{$key2}-->" name="<!--{$key2}-->" value="" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" class="boxHalf data-role-none">
          <option value="">--</option>
          <!--{html_options options=$arrYear}-->
        </select>&nbsp;&nbsp;年
      </dd>

      <dt>カード名義(ローマ字名)<span class="attention">※</span></dt>
      <dd>
        <!--{assign var=key1 value="card_name01"}-->
        <span class="attention"><!--{$arrErr[$key1]}--></span>
        名&nbsp;<input type="text"
		          id="<!--{$key1}-->"
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
		          id="<!--{$key2}-->"
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
	       id="<!--{$key}-->"
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
        <!--{assign var=key value="method"}-->
        <span class="attention"><!--{$arrErr[$key]}--></span>
        <select id="<!--{$key}-->" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="boxHalf data-role-none">
          <!--{html_options options=$arrPayMethod selected=$arrForm[$key].value|h}-->
        </select>
      </dd>
      <!--{else}-->
      <input type="hidden" name="method" value="1-0" />
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
	<li><a href="#" onclick="doPurchase(); return false;" class="btn" id="next"><!--{if $is2clickFlow}-->決定する<!--{else}-->ご注文完了ページへ<!--{/if}--></a></li>
	<li><a href="#" onclick="fnModeSubmit('return','',''); return false;" class="btn_back" id="back03">戻る</a></li>
      </ul>
    </div>
      
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
</section>
<!--▲CONTENTS-->

<!--{* 「TOPへ」ボタンを無効にする *}-->
<script type="text/javascript">//<![CDATA[
function setTopButton(topURL) {}
//]]>
</script>
