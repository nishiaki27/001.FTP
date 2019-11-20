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

<h2 class="title">お支払い方法：<!--{$tpl_payment_method|h}--></h2>

    <!--{if $arrErr.gmo_request}-->
    <table summary="お支払い方法">
      <tr>
        <td class="lefttd">
          <p class="attention">エラーが発生しました：<!--{$arrErr.gmo_request|escape}--></p>
        </td>
      </tr>
    </table>
    <!--{/if}-->

    <section class="pay_area">
      <h3 class="subtitle">コンビニの選択</h3>
        <!--{assign var=key value="conveni"}-->
        <!--{if $arrErr[$key] != ""}-->
        <p class="attention"><!--{$arrErr[$key]}--></p>
        <!--{/if}-->
	<ul id="payment">
        <!--{section name=cnt loop=$arrUseConveni}-->
        <!--{assign var=val value=$arrUseConveni[cnt]}-->
	<li>
          <input type="radio" id="<!--{$smarty.section.cnt.iteration}-->" name="<!--{$key}-->" value="<!--{$arrUseConveni[cnt]}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" <!--{$arrUseConveni[cnt]|sfGetChecked:$arrForm[$key].value}--> class="data-role-none"/>
          <label for="<!--{$smarty.section.cnt.iteration}-->"><!--{$arrCONVENI[$val]|h}--></label>
	</li>
        <!--{/section}-->
	</ul>
    </section>

    <!--{include file="`$smarty.const.MDL_PG_MULPAY_TEMPLATE_PATH`sphone_common_btn.tpl"}-->
</form>
</section>
<!--▲CONTENTS-->

<!--{* 「TOPへ」ボタンを無効にする *}-->
<script type="text/javascript">//<![CDATA[
function setTopButton(topURL) {}
//]]>
</script>
