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
<div id="under02column">
  <div id="under02column_shopping">
    <h2 class="title">お支払い方法：<!--{$tpl_payment_method}--></h2><br />

    <!--{if $arrErr.gmo_request}-->
    <table summary="お支払い方法">
      <tr>
        <td class="lefttd">
          <p class="attention">エラーが発生しました：<!--{$arrErr.gmo_request|escape}--></p>
	  <!--{if $arrErr.gmo_request == 'E01-E01230009'}-->
	  <p class="attention">カードの最大登録数５件を超えています。新しいカード情報を登録する場合には、
登録済みのカード情報を呼び出して、<br/>削除してください。</p>
	  <!--{/if}-->
        </td>
      </tr>
    </table>
    <!--{/if}-->

    <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
    <input type="hidden" name="mode" value="register" />
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="usecard" value="" />
    <input type="hidden" name="deleteCardSeq" value="" />
    <table summary="決済情報の入力">
      <tr>
        <th>カード番号<span class="attention">※</span></th>
        <td>
          <!--{assign var=key value="card_no"}-->
          <span class="attention"><!--{$arrErr[$key]}--></span>
          <input type="text"
		 id="<!--{$key}-->"
                 name="<!--{$key}-->"
                 value=""
                 maxlength="<!--{$arrForm[$key].length}-->"
                 class="box120"
                 style="ime-mode: disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->"/>
          <p class="mini">半角入力(例：1234567890123456)</p>
        </td>
      </tr>
      <tr>
        <th>有効期限<span class="attention">※</span></th>
        <td>
          <!--{assign var=key1 value="card_month"}-->
          <!--{assign var=key2 value="card_year"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
          <select id="<!--{$key1}-->" name="<!--{$key1}-->" value="" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
          <option value="">--</option>
          <!--{html_options options=$arrMonth}-->
          </select>　月　/　
          <select id="<!--{$key2}-->" name="<!--{$key2}-->" value="" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" >
          <option value="">--</option>
          <!--{html_options options=$arrYear}-->
          </select>　年
        </td>
      </tr>
      <tr>
        <th>カード名義(ローマ字氏名)<span class="attention">※</span></th>
        <td>
          <!--{assign var=key1 value="card_name01"}-->
          <!--{assign var=key2 value="card_name02"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
          <p class="mini">
            名&nbsp;<input type="text"
			  id="<!--{$key1}-->"
                          name="<!--{$key1}-->"
                          value=""
                          maxlength="<!--{$arrForm[$key1].length}-->"
                          style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"
                          size="20"
                          class="box120">&nbsp;&nbsp;
             姓&nbsp;<input type="text"
			  id="<!--{$key2}-->"
                          name="<!--{$key2}-->"
                          value=""
                          maxlength="<!--{$arrForm[$key2].length}-->"
                          class="box120"
                          style="ime-mode: disabled; <!--{$arrErr[$key2]|sfGetErrorColor}-->"
                          size="20"><br />
            半角入力(例：TAROU YAMADA)
          </p>
        </td>
      </tr>
      <!--{if $enable_security_code}-->
      <tr>
        <th>セキュリティコード</th>
        <td>
            <!--{assign var=key value="security_code"}-->
            <span class="attention"><!--{$arrErr[$key]}--></span>
            <input type="text"
		   id="<!--{$key}-->"
                   name="<!--{$key}-->"
                   value=""
                   maxlength="<!--{$arrForm[$key].length}-->"
                   class="box60"
                   style="ime-mode: disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->"/><br>
            <img src="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.USER_DIR}-->code_visa.gif" width="160" height="110" />
            <img src="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.USER_DIR}-->code_amex.gif" width="160" height="110" />
        </td>
      </tr>
      <!--{/if}-->
      <!--{if $credit_jobcd != '1'}-->
      <tr>
        <th>お支払い方法<span class="attention">※</span></th>
        <td>
          <!--{assign var=key value="method"}-->
          <span class="attention"><!--{$arrErr[$key]}--></span>
          <select id="<!--{$key}-->" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" >
          <!--{html_options options=$arrPayMethod selected=$arrForm[$key].value|escape}-->
          </select>
        </td>
      </tr>
      <!--{else}-->
      <input type="hidden" name="method" value="1-0" />
      <!--{/if}-->
      <!--{if $enable_customer_regist}-->
      <tr>
        <th>このカード情報を登録する</th>
        <td>
            <!--{assign var=key value="register_card"}-->
            <input type="checkbox"
                   id="<!--{$key}-->"
                   name="<!--{$key}-->"
                   value="1"
		   <!--{if $is2clickFlow}--> disabled="disabled" <!--{/if}-->
                   class="button" <!--{if $arrForm[$key].value || $isEnable2click}--> checked=checked <!--{/if}-->/>　<!--{if $is2clickFlow}-->カード情報を登録します<!--{else}-->カード情報を登録する<!--{/if}--><br />
            <p class="mini">
            ※カード情報を登録すると、次回以降、下の「登録済みのカード情報を呼び出す」ボタンで<br />登録したカードを利用することができます。(最大5件まで登録できます)</p>
        </td>
      </tr>
      <!--{/if}-->
      </table>

      <!--{include file="`$smarty.const.MDL_PG_MULPAY_TEMPLATE_PATH`common_attention.tpl"}-->

      <div class="btn_area">
        <ul>
          <li>
            <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg','back03')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg','back03')" onclick="fnModeSubmit('return','',''); return false;">
              <img src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back03" id="back03" /></a>
          </li>
          <li>
            <!--{if $is2clickFlow}-->
            <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_determine_on.jpg','next')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_determine.jpg','next')" onclick="doPurchase(); return false;">
              <img src="<!--{$TPL_URLPATH}-->img/button/btn_determine.jpg" alt="決定する" name="next" id="next" /></a>
            <!--{else}-->
            <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_order_complete_on.jpg','next')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg','next')" onclick="doPurchase(); return false;">
              <img src="<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg" alt="ご注文完了ページへ" name="next" id="next" /></a>
            <!--{/if}-->
          </li>
        </ul>
      </div>

      <!--{if $enable_customer_regist}-->
      <table>
        <tr><td style="text-align:center" colspan="5">
            <input type="image"
                   onclick="fnModeSubmit('getcard','',''); return false;"
                   onmouseover="chgImgImageSubmit('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.USER_DIR}-->gmo_id_on.gif',this)"
                   onmouseout="chgImgImageSubmit('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.USER_DIR}-->gmo_id.gif',this)"
                   src="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.USER_DIR}-->gmo_id.gif">

            </td>
        </tr>
        <!--{if $cardNum}-->
        <tr>
            <td class="alignC">選択<!--{if $arrErr.CardSeq}--><br /><span class="attention mini">使用するカードを選択して下さい</span><!--{/if}--></td>
            <td>カード番号</td>
            <td>有効期限</td>
            <td>名義人</td>
            <td class="alignC">削除</td>
        </tr>
        <!--{foreach name=cardloop from=$arrCardInfo item=card}-->
        <!--{if $card.DeleteFlag == 0}-->
        <tr>
            <td class="alignC"><input type="radio" name="CardSeq" value="<!--{$card.CardSeq}-->"></td>
            <td><!--{$card.CardNo}--></td>
            <td><!--{$card.Expire|substr:2:4}-->月 / <!--{$card.Expire|substr:0:2}-->年</td>
            <td><!--{$card.HolderName}--></td><!--{* 名義人 *}-->
            <td class="alignC"><input type="button" onClick="return fnCheckSubmit('deletecard', 'deleteCardSeq', '<!--{$card.CardSeq}-->')" value="削除"></td>
        </tr>
        <!--{/if}-->
        <!--{/foreach}-->
        <!--{/if}-->
      </table>
      <!--{if $cardNum}-->
      <!--{if $credit_jobcd != '1'}-->
      <table>
      <tr>
        <th>お支払い方法<span class="attention">※</span></th>
        <td>
          <!--{assign var=key value="paymethod_usecard"}-->
          <span class="attention"><!--{$arrErr[$key]}--></span>
          <select name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" >
          <!--{html_options options=$arrPayMethod selected=$arrForm[$key].value|escape}-->
          </select>
        </td>
      </tr>
      </table>
      <!--{else}-->
      <input type="hidden" name="paymethod_usecard" value="1-0" />
      <!--{/if}-->
      
      <!--{include file="`$smarty.const.MDL_PG_MULPAY_TEMPLATE_PATH`common_attention.tpl"}-->

      <div class="btn_area">
        <ul>
          <li>
            <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg','back04')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg','back04')" onclick="fnModeSubmit('return','',''); return false;">
              <img src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back04" id="back04" /></a>
          </li>
          <li>
            <!--{if $is2clickFlow}-->
            <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_determine_on.jpg','next02')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_determine.jpg','next02')" onclick="fnCheckSubmit('register', 'usecard', '1');return false;">
              <img src="<!--{$TPL_URLPATH}-->img/button/btn_determine.jpg" alt="決定する" name="next02" id="next02" /></a>
            <!--{else}-->
            <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_order_complete_on.jpg','next02')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg','next02')" onclick="fnCheckSubmit('register', 'usecard', '1'); return false;">
              <img src="<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg" alt="ご注文完了ページへ" name="next02" id="next02" /></a>
            <!--{/if}-->
          </li>
        </ul>
      </div>

      <!--{/if}-->
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
  </div>
</div>
<!--▲CONTENTS-->
