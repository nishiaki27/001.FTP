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
<script type="text/javascript">//<![CDATA[
var send = true;

window.onunload=function(){
}

window.onload=function onloadCashClear() {
	if (send) {
		return false;
	} else {
		send = true;
		return false;
	}
}

function fnCheckSubmit(mode) {
    if(send) {
        send = false;
        fnModeSubmit(mode,'','');
        return false;
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
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
<div id="under02column">
<div id="under02column_shopping">
  <p class="flow_area"><img src="<!--{$TPL_URLPATH}-->img/picture/img_flow_03.jpg" alt="購入手続きの流れ" /></p>
  <h2 class="title1"><!--{$tpl_payment_method}--></h2>

  <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  <input type="hidden" name="mode" value="next">
  <table summary="お支払詳細入力" class="delivname">
    <tbody>
      <!--{if $tpl_error != ""}-->
      <tr>
        <td class="lefttd" colspan="2">
          <span class="attention"><!--{$tpl_error}--></span><br>
          <span class="attention"><!--{$tpl_error_detail}--></span>
        </td>
      </tr>
      <!--{/if}-->
      <!--{if $tpl_payment_image != ""}-->
      <tr>
        <th>ご利用いただける金融機関の種類</th>
        <td class="lefttd">
          <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$tpl_payment_image}-->">
        </td>
      </tr>
      <!--{/if}-->
      <tr>
        <th>利用者</th>
        <td class="lefttd">
          <!--{assign var=key1 value="customer_family_name"}-->
          <!--{assign var=key2 value="customer_name"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
                    姓&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;
                    名&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20">
          <br /><p class="mini">※ 特殊な漢字は使用できない場合がございます。</p>
        </td>
      </tr>
      <tr>
        <th>利用者(カナ)</th><td class="lefttd">
          <!--{assign var=key1 value="customer_family_name_kana"}-->
          <!--{assign var=key2 value="customer_name_kana"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
                    セイ&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;
                    メイ&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20">
          <br /><p class="mini">※カナに濁点（゛）や半濁点（゜）がある場合、該当記号のみ除外されます。予めご了承ください。</p>
        </td>
      </tr>
      <tr>
	<td colspan="2" class="lefttd">三菱UFJ銀行などメガバンク、JNB、楽天銀行、ゆうちょ銀行など<br>全国1,400行以上のネットバンキングでお支払いが可能です。
	<br>なお、商品はお支払い後のご提供となります。</td>
      </tr>
    </tbody>
  </table>

  <table>
    <tr>
      <td class="lefttd">
        <a href="http://www.paygent.co.jp/merchant_info/help/shophelp_netbank.html" target="_blank">『お支払方法の説明』</a>を事前にご確認ください。<br />
                以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <span class="attention">
                ※決済用サイトに遷移します。ドメインが変わりますが、そのままお手続きを進めてください。<br />
                ※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。
        </span>
      </td>
    </tr>
  </table>

  <div class="btn_area">
    <ul>
      <li>
        <input type="image" onclick="return fnCheckSubmit('return');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_keep_shopping.png',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_keep_shopping.png" alt="戻る" border="0" name="back03" id="back03"/>
      </li>
      <li>
        <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" border="0" name="next" id="next" />
      </li>
    </ul>
  </div>
  </form>

</div>
</div>
<!--▲CONTENTS-->