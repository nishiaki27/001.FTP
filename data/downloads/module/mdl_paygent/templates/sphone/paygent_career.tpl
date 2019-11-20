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
<meta http-equiv="Cache-Control"content="no-cache" />
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
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
    <input type="hidden" name="php_session_id" value="<!--{$php_session_id}-->">
    <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">
  <table summary="お支払詳細入力" class="entryform">
    <tbody>
      <!--{if $tpl_error != ""}-->
      <tr>
        <td class="lefttd" colspan="2">
          <span class="attention"><!--{$tpl_error}--></span>
        </td>
      </tr>
      <!--{/if}-->
       <tr>
        <th width="200px">キャリア決済選択</th>
        <td class="lefttd">
          <!--{assign var=key1 value="career_type"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
          <option value="" size="20">ご選択ください</option>
          <!--{html_options options=$arrCareer selected=$arrForm[$key1].value}-->
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="lefttd">購入代金を、ご選択いただきました携帯電話キャリアの通話料金とまとめてお支払いいただけます。
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

  <div class="tblareabtn">
    <p><input type="submit" onclick="return fnCheckSubmit('next');" value="次へ" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="next" id="next" /></p>
        <p><input type="submit" onclick="return fnCheckSubmit('return');" value="戻る" class="spbtn spbtn-medeum" alt="戻る" name="return" id="return" /></p>
  </div>
  <table class="entryform">
    <tbody>
      <tr>
        <th>ドコモ払い</th>
        <td class="lefttd">画面からネットワーク暗証番号を入力いただくことでお支払が可能です。対応端末は、spモード契約のあるスマートフォン、およびPCの各種端末からもご利用いただけます。</td>
      </tr>
      <tr>
        <th>auかんたん決済</th>
        <td class="lefttd">画面からau ID/パスワード、続いてセキュリティパスワードを入力いただくことでお支払が可能です。au IDを取得することで国内3キャリアのスマートフォンおよびPCの各種端末からもご利用いただけます。</td>
      </tr>
      <tr>
        <th>ソフトバンク・ワイモバイルまとめて支払い</th>
        <td class="lefttd">画面から暗証番号（電話料金等を口座引落で決済されている場合）/セキュリティコード（電話料金等をカードで決済されている場合）を入力いただくことでお支払が可能です。対応端末は、softbankスマートフォン契約のあるスマートフォンとなります<font color="#FF0000">（フィーチャーフォンには対応しておりません）</font>。</td>
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
<!--{else}-->
<style type="text/css">
<!--
table {
    margin: 15px auto 20px auto;
    border-top: 1px solid #ccc;
    border-left: 1px solid #ccc;
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}
table th {
    padding: 8px;
    border-right: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    color: #333;
    background-color: #f0f0f0;
    font-weight: normal;
}
table td {
    padding: 8px;
    border-right: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
}
-->
</style>
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
      <!--{/if}-->
    </div>

    <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="next">
        <input type="hidden" name="php_session_id" value="<!--{$php_session_id}-->">
        <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">

        <dl class="form_entry">
            <dt>キャリア決済選択</dt>
            <dd>
                <!--{assign var=key1 value="career_type"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="font-size:7pt; <!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxLong data-role-none">
                <option value="" size="20">ご選択ください</option>
                <!--{html_options options=$arrCareer selected=$arrForm[$key1].value}-->
                </select>
            </dd>
        </dl>

        <div class="btn_area">
            <p>
                購入代金を、ご選択いただきました携帯電話キャリアの通話料金とまとめてお支払いいただけます。<br />
               （各支払い方法は下記一覧をご確認ください）<br />
                以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
                <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
            </p>

            <ul class="btn_btm">
                <li><a href="javascript:fnCheckSubmit('next');" class="btn">次へ</a></li>
                <li><a href="javascript:fnCheckSubmit('return');" class="btn_back">戻る</a></li>
            </ul>
        </div>

        <table border='1' class="career_discription">
            <tr>
                <th>ドコモ払い</th>
                <td>画面からネットワーク暗証番号を入力いただくことでお支払が可能です。対応端末は、spモード契約のあるスマートフォン、およびPCの各種端末からもご利用いただけます。</td>
            </tr>
            <tr>
                <th>auかんたん決済</th>
                <td>画面からau ID/パスワード、続いてセキュリティパスワードを入力いただくことでお支払が可能です。au IDを取得することで国内3キャリアのスマートフォンおよびPCの各種端末からもご利用いただけます。</td>
            </tr>
            <tr>
                <th>ソフトバンクまとめて支払い・ワイモバイルまとめて支払い</th>
                <td>画面から暗証番号（電話料金等を口座引落で決済されている場合）/セキュリティコード（電話料金等をカードで決済されている場合）を入力いただくことでお支払が可能です。対応端末は、softbankスマートフォン契約のあるスマートフォンとなります<font color="#FF0000">（フィーチャーフォンには対応しておりません）</font>。</td>
            </tr>
        </table>

        <iframe style="height:0px;width:0px;visibility:hidden" src="about:blank">
            this frame prevents back forward cache
        </iframe>

    </form>
</section>
<!--{/if}-->