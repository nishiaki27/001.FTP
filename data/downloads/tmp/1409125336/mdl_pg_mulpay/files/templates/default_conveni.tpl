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
<div id="under02column">
  <div id="under02column_shopping">
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
        <!--{assign var=key value="conveni"}-->
        <!--{if $arrErr[$key] != ""}-->
        <p class="attention"><!--{$arrErr[$key]}--></p>
        <!--{/if}-->
        <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
        <input type="hidden" name="mode" value="tran" />
	<input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
        <table summary="お支払方法選択">

          <tr>
            <th class="alignC">選択</th>
            <th colspan="2">コンビニの種類</th>
          </tr>
          <!--{section name=cnt loop=$arrUseConveni}-->
          <!--{assign var=val value=$arrUseConveni[cnt]}-->
          <tr>
            <td class="alignC">
              <input type="radio" id="<!--{$smarty.section.cnt.iteration}-->" name="<!--{$key}-->" value="<!--{$arrUseConveni[cnt]}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" <!--{$arrUseConveni[cnt]|sfGetChecked:$arrForm[$key].value}--> />
            </td>
            <td>
              <label for="<!--{$smarty.section.cnt.iteration}-->"><!--{$arrCONVENI[$val]|escape}--></label>
            </td>
          </tr>
          <!--{/section}-->
        </table>

	<!--{include file="`$smarty.const.MDL_PG_MULPAY_TEMPLATE_PATH`default_common_btn.tpl"}-->
    </form>
  </div>
</div>
<!--▲CONTENTS-->
