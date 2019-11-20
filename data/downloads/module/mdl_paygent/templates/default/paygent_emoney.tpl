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
		sendo = true;
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
  <input type="hidden" name="php_session_id" value="<!--{$php_session_id}-->">
  <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">
  <table summary="お支払詳細入力" class="delivname">
    <tbody>
      <!--{if $tpl_error != ""}-->
      <tr>
        <td class="lefttd" colspan="2">
          <span class="attention"><!--{$tpl_error}--></span>
        </td>
      </tr>
      <!--{/if}-->
      <tr>
        <th>利用決済選択</th>
        <td class="lefttd">
          <!--{assign var=key1 value="emoney_type"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
          <option value="">ご選択ください</option>
		  <!--{html_options options=$arrEmoney selected=$arrForm[$key1].value}-->
          </select>
        </td>
      </tr>
      <tr>
		<td colspan="2" class="lefttd">プリペイドカードおよびウォレットにチャージされている電子マネーからお支払いいただけます。
		<br>（各支払い方法は下記一覧をご確認ください）</td>
      </tr>
    </tbody>
  </table>

  <table>
    <tr>
      <td class="lefttd">
                以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
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

  <table>
    <tbody>
      <tr>
	<th>WebMoney</th>
        <td class="lefttd">プリペイドカードご利用の場合はカード記載の番号、ウォレットご利用の場合はウォレットID/パスワードに続いてセキュアパスワードを入力することによりお支払が可能です。</td>
      </tr>
    </tbody>
  </table>

  <iframe style="height:0px;width:0px;visibility:hidden" src="about:blank">
	this frame prevents back forward cache
  </iframe>
  </form>

</div>
</div>
<!--▲CONTENTS-->