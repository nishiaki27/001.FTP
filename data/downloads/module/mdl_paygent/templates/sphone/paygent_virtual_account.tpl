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
<!--{*** EC-CUBE 2.11.1以前用と2.11.2以降用の2種類のテンプレートを定義しています。 ***}-->
<!--{if preg_match('/^2\.11\.[0-1]$/', $smarty.const.ECCUBE_VERSION)}-->
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<script type="text/javascript">//<![CDATA[
var send = true;

function fnCheckSubmit(mode) {
    if(send) {
        send = false;
    fnModeSubmit(mode,'','');
        return true;
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

  <h2 class="title"><!--{$tpl_payment_method}--><h2>

    <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="next">
  <table summary="お支払詳細入力" class="entryform">
    <tbody>
      <!--{if $tpl_error != ""}-->
      <tr>
        <td class="lefttd" colspan="2">
          <span class="attention"><!--{$tpl_error}--></span><br>
          <span class="attention"><!--{$tpl_error_detail}--></span>
        </td>
      </tr>
      <!--{/if}-->
      <tr>
        <th>利用者</th>
        <td class="lefttd">
          <!--{assign var=key1 value="billing_family_name"}-->
          <!--{assign var=key1 value="billing_name"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
                    姓&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;<br>
                    名&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20">
          <br /><p class="mini">※ 特殊な漢字は使用できない場合がございます。</p>
        </td>
      </tr>
      <tr>
        <th>利用者(カナ)</th>
        <td class="lefttd">
          <!--{assign var=key1 value="billing_family_name_kana"}-->
          <!--{assign var=key2 value="billing_name_kana"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
                    セイ&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;<br>
                    メイ&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20">
          <br /><p class="mini">※カナに濁点（゛）や半濁点（゜）がある場合、該当記号のみ除外されます。予めご了承ください。</p>
        </td>
      </tr>
      <tr>
      <td colspan="2">お振込みをする銀行口座の名義人を入力してください。<br>また商品はお支払後のご提供となります。</td>
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

  <div class="tblareabtn">
    <p><input type="submit" onclick="return fnCheckSubmit('next');" value="次へ" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="next" id="next" /></p>
        <p><input type="submit" onclick="return fnCheckSubmit('return');" value="戻る" class="spbtn spbtn-medeum" alt="戻る" name="return" id="return" /></p>
  </div>
  </form>

  <form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
  <input type="hidden" name="mode" value="return">
  </form>
</div>
</div>
<!--▲CONTENTS-->
<!--{else}-->
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

<!--▼コンテンツここから -->
<section id="undercolumn">

    <h2 class="title"><!--{$tpl_payment_method}--></h2>

    <!--★インフォメーション★-->
    <div class="information end">
        <!--{if $tpl_error != ""}-->
        <p><span class="attention"><!--{$tpl_error}--></span></p>
        <p><span class="attention"><!--{$tpl_error_detail}--></span></p>
        <!--{/if}-->
    </div>

    <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="next">

        <dl class="form_entry">
            <dt id="customer_dt">利用者</dt>
            <dd id="customer_dd">
                <!--{assign var=key1 value="billing_family_name"}-->
                <!--{assign var=key2 value="billing_name"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <span class="attention"><!--{$arrErr[$key2]}--></span>
                姓&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" class="boxShort text data-role-none" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;
                名&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" class="boxShort text data-role-none" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20"><br />
                <p class="mini">※ 特殊な漢字は使用できない場合がございます。</p>
            </dd>
            <dt id="customer_kana_dt">利用者<br />(カナ)</dt>
            <dd id="customer_kana_dd">
                <!--{assign var=key1 value="billing_family_name_kana"}-->
                <!--{assign var=key2 value="billing_name_kana"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <span class="attention"><!--{$arrErr[$key2]}--></span>
                セイ&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" class="boxShort text data-role-none" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;
                メイ&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" class="boxShort text data-role-none" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20"><br />
                <p class="mini">※カナに濁点（゛）や半濁点（゜）がある場合、該当記号のみ除外されます。予めご了承ください。</p>
            </dd>
        </dl>

        <div class="btn_area">
            <p>
                お振込みをする銀行口座の名義人を入力してください。<br />また商品はお支払後のご提供となります。<br />以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
                <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
            </p>

            <ul class="btn_btm">
                <li><a href="javascript:fnCheckSubmit('next');" class="btn">次へ</a></li>
                <li><a href="javascript:fnCheckSubmit('return');" class="btn_back">戻る</a></li>
            </ul>
        </div>

        <iframe style="height:0px;width:0px;visibility:hidden" src="about:blank">
            this frame prevents back forward cache
        </iframe>

    </form>
</section>
<!--{/if}-->