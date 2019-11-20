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
<!--▼CONTENTS-->
<section id="undercolumn">

<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="tran" />

<h2 class="title">お支払い方法：<!--{$tpl_payment_method}--></h2><br />

    <!--{if $arrErr.gmo_request}-->
    <table summary="お支払い方法">
      <tr>
        <td class="lefttd">
          <p class="attention">エラーが発生しました：<!--{$arrErr.gmo_request|escape}--></p>
        </td>
      </tr>
    </table>
    <!--{/if}-->
    <table>
      <tr>
        <td class="lefttd">事前にモバイルSuicaの会員登録が必要です。<br />
         会員の登録方法につきましては<a href="<!--{$smarty.const.SUICA_PC_SITE_LINK}-->" target="_blank">モバイルSuicaのサイト</a>をご確認ください。</td>
      </tr>
    </table>

    <dl class="form_entry">
      <dt>携帯メールアドレス<span class="attention">※</span></dt>
      <dd>
        <!--{assign var=key value="email"}-->
        <!--{assign var=key2 value="email_all"}-->
        <span class="attention"><!--{$arrErr[$key]}--></span>
        <span class="attention"><!--{$arrErr[$key2]}--></span>
        <input type="text"
               name="<!--{$key}-->"
               value="<!--{$arrForm[$key].value|escape}-->"
               maxlength="<!--{$arrForm[$key].length}-->"
               class="boxHarf text data-role-none"
               style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/>&nbsp;&nbsp;@
        <!--{assign var=key value="email_domain"}-->
        <select name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="boxHalf data-role-none">
          <!--{html_options options=$arrMobileMailDomain selected=$arrForm[$key].value|escape}-->
        </select>
      </dd>
    </dl>

    <!--{include file="`$smarty.const.MDL_PG_MULPAY_TEMPLATE_PATH`sphone_common_btn.tpl"}-->
</form>
</section>
<!--▲CONTENTS-->

<!--{* 「TOPへ」ボタンを無効にする *}-->
<script type="text/javascript">//<![CDATA[
function setTopButton(topURL) {}
//]]>
</script>
