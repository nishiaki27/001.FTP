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
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<style type="text/css">
  .info { padding: 0 4px; }
</style>
<script type="text/javascript">//<![CDATA[
self.moveTo(20,20);
self.resizeTo(620, 650);
self.focus();
//]]>
</script>
<script type="text/javascript">//<![CDATA[
function site_win(){
	var server_url = document.form1.kanri_server_url.value;
	if (server_url == '') {
		alert("管理画面サーバURLを設定してください。");
		return;
	}
	var site_id = document.form1.site_id.value;
		if (site_id == '') {
		alert("サイトIDを設定してください。");
		return;
	}
	
	var WIN;
	WIN = window.open(server_url + 'site/' +site_id + '/index');
	WIN.focus();
}

function shop_win(){
	var server_url = document.form1.kanri_server_url.value;
	if (server_url == '') {
		alert("管理画面サーバURLを設定してください。");
		return;
	}
	var shop_id = document.form1.shop_id.value;
		if (shop_id == '') {
		alert("ショップIDを設定してください。");
		return;
	}
	
	var WIN;
	WIN = window.open(server_url + 'shop/' + shop_id + '/index');
	WIN.focus();
}

function toggleBox() {
	for (var i = 1; i < toggleBox.arguments.length; i++) {
		var inputBox = document.getElementById(toggleBox.arguments[i]);
		if (toggleBox.arguments[0].checked) {
			inputBox.disabled = false;
		} else {
			inputBox.disabled = true;
		}
	}
}

function toggleConveniBox() {
	var isDisabled = document.form1.use_conveni.checked;
	for (var i = 0; i < document.form1.elements['conveni[]'].length; i++) {
		if (document.form1.use_conveni.checked) {
			document.form1.elements['conveni[]'][i].disabled = false;
		} else {
			document.form1.elements['conveni[]'][i].disabled = true;
		}
	}
}

function toggleNetidBox() {
	for (var i = 0; i < document.form1.elements['netid_jobcd'].length; i++) {
		if (document.form1.use_netid.checked) {
			document.form1.elements['netid_jobcd'][i].disabled = false;
		} else {
			document.form1.elements['netid_jobcd'][i].disabled = true;
		}
	}
}

function toggleAuBox() {
	for (var i = 0; i < document.form1.elements['au_jobcd'].length; i++) {
		if (document.form1.use_au.checked) {
			document.form1.elements['au_jobcd'][i].disabled = false;
		} else {
			document.form1.elements['au_jobcd'][i].disabled = true;
		}
	}
}

function toggleDocomoBox() {
    for (var i = 0; i < document.form1.elements['docomo_jobcd'].length; i++) {
        if (document.form1.use_docomo.checked) {
            document.form1.elements['docomo_jobcd'][i].disabled = false;
        } else {
            document.form1.elements['docomo_jobcd'][i].disabled = true;
        }
    }
}
//]]>
</script>
<h2><!--{$tpl_subtitle}--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid|escape}-->" />
<input type="hidden" name="mode" value="confirm_overwrite">
<!--{assign var=key value="not_install_customize"}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
<p class="remark">
PGマルチペイメントサービス決済モジュールをご利用頂く為には、
ユーザ様ご自身でGMOペイメントゲートウェイ株式会社様とご契約を行っていただく必要があります。
お申し込みにつきましては、下記のページから、お申し込みを行って下さい。<br/><br/>
<a href="http://www.gmo-pg.com/" target="_blank"> ＞＞ GMOペイメントゲートウェイ決済システムについて</a>
</p>

<!--{if $arrErr.err != ""}-->
<div class="attention"><!--{$arrErr.err}--></div>
<!--{/if}-->

<table class="form">
  <colgroup width="20%"></colgroup>
  <colgroup width="40%"></colgroup>

  <tr>
    <th colspan="2">▼設定</th>
  </tr>
  <tr>
    <th>接続先サーバーURL<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=server_url}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="text"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length}-->">
    </td>
  </tr>
  <tr>
    <th>管理画面サーバーURL<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=kanri_server_url}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length}-->">
    </td>
  </tr>
  <tr>
    <th>サイトID<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=site_id}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length}-->"><br /><br />
      <span class="info">※PGマルチペイメントサービスの管理画面にログインするIDとは異なりますので、ご注意ください。</span>
    </td>
  </tr>
  <tr>
    <th>サイトパスワード<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=site_pass}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="password"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length}-->"><br /><br />
      <span class="info">※PGマルチペイメントサービスの管理画面にログインするパスワードとは異なりますので、ご注意ください。</span>
    </td>
  </tr>
  <tr>
    <th>ショップID<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=shop_id}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length}-->"><br /><br />
      <span class="info">※PGマルチペイメントサービスの管理画面にログインするIDとは異なりますので、ご注意ください。</span>
    </td>
  </tr>
  <tr>
    <th>ショップパスワード<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=shop_pass}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="password"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length}-->"><br /><br />
      <span class="info">※PGマルチペイメントサービスの管理画面にログインするパスワードとは異なりますので、ご注意ください。</span>
    </td>
  </tr>
  <tr>
    <th>結果通知プログラムURL<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=termurl}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <p><!--{$smarty.const.HTTPS_URL}--><!--{$smarty.const.RESULT_RECEIVE_PATHNAME}--><p>
	<span class="info">※ショップ管理画面よりログインして頂き、タブ「ショップの管理」＞タブ「メール/結果通知設定」で結果通知プログラムURLに設定してください。</span>
    </td>
  </tr>
  <tr>
    <th>サイト管理</th>
    <td>
      <a href="javascript:site_win()">＞＞サイト管理画面</a><br><br><br>
      <span class="info">※本番環境管理画面はGMOペイメントゲートウェイ株式会社より発行された「クライアント証明書」がインストールされたブラウザでアクセスする必要があります。</span>
    </td>
  </tr>
  <tr>
    <th>ショップ管理</th>
    <td>
      <a href="javascript:shop_win()">＞＞ショップ管理画面</a><br><br><br>
      <span class="info">※本番環境管理画面はGMOペイメントゲートウェイ株式会社より発行された「クライアント証明書」がインストールされたブラウザでアクセスする必要があります。</span>
    </td>
  </tr>

  <!--{if $extensionAvailable}-->
  <tr>
    <th>決済状況変更機能</th>
    <td>
      <!--{assign var=key value=credit_CardStatusChangeFunction}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->"
	     <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info attention">
	<ul>
	  <li>※一部ファイルを上書きします。カスタマイズしているシステムではご注意下さい。
	    上書きされたくない場合は、次の確認画面で「変更を手動で反映」にチェックを入れて下さい。
	  </li>
	</ul>
      </span>
    </td>
  </tr>
  <!--{/if}-->

  <tr>
    <th>2クリック決済</th>
    <td>
      <!--{assign var=key value="2click_LicenseKey"}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="password"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value|escape}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" /> 使用する場合はライセンスキーを入力してください<br /><br /><br />
      <span class="info attention">
	<ul>
	  <li>※2クリック決済機能のご利用には、ライセンスキーが必要です。</li>
	  <li>※機能を利用しない場合、入力は不要です。</li>
	  <li>※クレジット決済設定の「会員ID登録」は、連動して有効になります。</li>
	  <li>※一部ファイルを上書きします。カスタマイズしているシステムではご注意下さい。
	    上書きされたくない場合は、次の確認画面で「変更を手動で反映」にチェックを入れて下さい。</li>
	</ul>
      </span>
    </td>
  </tr>

  <tr>
    <th colspan="2">▼クレジット決済設定</th>
  </tr>
  <tr>
    <th>処理区分<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=jobcd}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="radio"
	     name="<!--{$key}-->"
	     value="0" <!--{if $arrForm[$key].value == '0'}-->checked=checked<!--{/if}--> >AUTH(仮売上)<br />
      <input type="radio"
	     name="<!--{$key}-->"
	     value="1" <!--{if $arrForm[$key].value == '1'}-->checked=checked<!--{/if}--> >CHECK(有効性チェック)<br />
      <input type="radio"
	     name="<!--{$key}-->"
	     value="2" <!--{if $arrForm[$key].value == '2'}-->checked=checked<!--{/if}--> >CAPTURE(即時売上)<br />
      <br /><br />
      <span class="info">仮売上(AUTH)<br />
	　　・・・カードの与信枠を確保し承認番号を得ること。※仮売上のデータ保持期間は90日です。実売上処理を行わないとカード会社への売上データが作成されません。<br /><br />
	有効性チェック(CHECK)<br />
	　　・・・カードの有効性（試用できるか否か）のチェックのみを行います。利用金額を設定してもカード会社へは送信されません。
	※カード会社によっては承認番号を返却しない場合もございます。この場合はエラーコードを返却しないことが許可（OK）になります。<br /><br />
	即時売上(CAPTURE)<br />
	　　・・・カードの与信枠を確保し承認番号を得て、カード会社への売上データの作成依頼をすること。（仮売上+実売上の処理になります。）
      </span>
    </td>
  </tr>

  <tr>
    <th colspan="2">▼クレジット決済設定</th>
  </tr>
    <th>支払方法／回数<span class="attention">※</span></th>
    <td>
    <!--{assign var=key value="method_paytimes"}-->
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <!--{html_checkboxes name="$key" options=$arrMethodPaytimes selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor separator='<br />'}-->
    <br />
    <span class="attention">
      <p>※ご契約にない支払方法／回数で決済されるとエラーになります。</p>
　    <p>※PGマルチペイメントサービスのショップ管理画面にてカード会社契約状況を<br />
確認のうえ、ご設定いただきますようお願いします。</p>
    </span>
    </td>
  </tr>

  <tr>
    <th>本人認証サービス</th>
    <td>
      <!--{assign var=key value="use3d"}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info">
	<p>※本人認証サービスを使用するにはSSL環境が必要です</p>
	<p>※モバイルには対応しておりません(通常の決済が実行されます)</p>
      </span>
    </td>
  </tr>
  <tr>
    <th>3Dセキュア表示店舗名</th>
    <td>
      <!--{assign var="key" value="3d_shop_name"}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" /><br /><br /><br />
      <span class="info">
	<p>本人認証サービスを利用しない場合、入力は不要です。</p>
	<p>また設定した店舗名は、本人認証サービスのパスワード入力画面に表示する店舗名になります。</p>
	<p>日本語を設定された場合（特に全角）、文字の組み合わせによっては文字化けを起こす、
	もしくはエラーとなり決済できないことがございます。3Dセキュア表示店舗名には、
	可能でしたら半角にて設定いただき、十分な検証をおこなっていただくことを推奨いたします。</p>
	<p>なお、3Dセキュア表示店舗名には、18Byte以内の文字列を設定ください。</p>
      </span>
    </td>
  </tr>
  <tr>
    <th>セキュリティコード</th>
    <td>
      <!--{assign var=key value="use_securitycd"}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br>
    </td>
  </tr>
  <tr>
    <th>会員ID登録</th>
    <td>
      <!--{assign var=key value="use_customer_reg"}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=credit_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length}-->">
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=credit_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length}-->">
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th colspan="2">▼コンビニ決済設定</th>
  </tr>
  <tr>
    <th>コンビニ決済</th>
    <td>
      <!--{assign var=key value="use_conveni"}-->
      <span class="attention"><!--{if $arrErr[$key].value != ''}--><p><!--{$arrErr[$key]}--><!--{/if}--></p></span>
      <!--{if $arrForm[$key].value == ''}--><!--{assign var=conveni_disable value='disabled="disabled"'}--><!--{/if}-->
      <input onclick="toggleConveniBox();toggleBox(document.form1.use_conveni,'conveni_PaymentTermDay','conveni_RegisterDisp1','conveni_RegisterDisp2','conveni_RegisterDisp3','conveni_RegisterDisp4','conveni_RegisterDisp5','conveni_RegisterDisp6','conveni_RegisterDisp7','conveni_RegisterDisp8','conveni_ReceiptsDisp1','conveni_ReceiptsDisp2','conveni_ReceiptsDisp3','conveni_ReceiptsDisp4','conveni_ReceiptsDisp5','conveni_ReceiptsDisp6','conveni_ReceiptsDisp7','conveni_ReceiptsDisp8','conveni_ReceiptsDisp9','conveni_ReceiptsDisp10','conveni_ReceiptsDisp11','conveni_ReceiptsDisp12_1','conveni_ReceiptsDisp12_2','conveni_ReceiptsDisp12_3','conveni_ReceiptsDisp13_1','conveni_ReceiptsDisp13_2','conveni_ReceiptsDisp13_3','conveni_ReceiptsDisp13_4','conveni_ClientField1','conveni_ClientField2');"
	     type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info">※ご利用頂く為には、お申し込みが必要です。お申し込みは<a href="http://www.gmo-pg.com/" target="_blank">こちら</a></span>
    </td>
  </tr>
  <tr>
    <th>コンビニ選択<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value="conveni"}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <!--{html_checkboxes name="$key" options=$arrCONVENI|escape selected=$arrForm[$key].value}-->
    </td>
  </tr>
  <tr>
    <th>支払期限</th>
    <td>
      <!--{assign var=key value=conveni_PaymentTermDay}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box6"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>日<br><br><br>
      <span class="info">※省略時は、ショップ管理画面のショップ情報に設定された支払期限で処理されます。</span>
    </td>
  </tr>
  <tr>
    <th>POSレジ表示欄1（店名）<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=conveni_RegisterDisp1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>POSレジ表示欄2</th>
    <td>
      <!--{assign var=key value=conveni_RegisterDisp2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>POSレジ表示欄3</th>
    <td>
      <!--{assign var=key value=conveni_RegisterDisp3}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>POSレジ表示欄4</th>
    <td>
      <!--{assign var=key value=conveni_RegisterDisp4}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>POSレジ表示欄5</th>
    <td>
      <!--{assign var=key value=conveni_RegisterDisp5}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>POSレジ表示欄6</th>
    <td>
      <!--{assign var=key value=conveni_RegisterDisp6}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>POSレジ表示欄7</th>
    <td>
      <!--{assign var=key value=conveni_RegisterDisp7}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>POSレジ表示欄8</th>
    <td>
      <!--{assign var=key value=conveni_RegisterDisp8}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄1</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄2</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄3</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp3}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄4</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp4}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄5</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp5}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄6</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp6}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄7</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp7}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄8</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp8}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄9</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp9}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>レシート表示欄10</th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp10}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>お問合せ先<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=conveni_ReceiptsDisp11}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>お問合せ先電話番号<span class="attention">※</span></th>
    <td>
      <!--{assign var=key1 value=conveni_ReceiptsDisp12_1}-->
      <!--{assign var=key2 value=conveni_ReceiptsDisp12_2}-->
      <!--{assign var=key3 value=conveni_ReceiptsDisp12_3}-->
      <!--{assign var=key4 value=conveni_ReceiptsDisp12}-->
      <span class="attention"><!--{$arrErr[$key1]}--></span>
      <span class="attention"><!--{$arrErr[$key2]}--></span>
      <span class="attention"><!--{$arrErr[$key3]}--></span>
      <span class="attention"><!--{$arrErr[$key4]}--></span>
      <input id="<!--{$key1}-->"
	     type="text"
	     name="<!--{$key1}-->"
	     class="box6"
	     value="<!--{$arrForm[$key1].value|escape}-->"
	     maxlength="<!--{$arrForm[$key1].length|escape}-->"
	     ime-mode: disabled; <!--{$conveni_disable}-->> - 
      <input id="<!--{$key2}-->"
	     type="text"
	     name="<!--{$key2}-->"
	     class="box6"
	     value="<!--{$arrForm[$key2].value|escape}-->"
	     maxlength="<!--{$arrForm[$key2].length|escape}-->"
	     ime-mode: disabled; <!--{$conveni_disable}-->> - 
      <input id="<!--{$key3}-->"
	     type="text"
	     name="<!--{$key3}-->"
	     class="box6"
	     value="<!--{$arrForm[$key3].value|escape}-->"
	     maxlength="<!--{$arrForm[$key3].length|escape}-->"
	     ime-mode: disabled; <!--{$conveni_disable}-->>
    </td>
  </tr>
  <tr>
    <th>お問合せ先受付時間<span class="attention">※</span></th>
    <td>
      <!--{assign var=key1 value=conveni_ReceiptsDisp13_1}-->
      <!--{assign var=key2 value=conveni_ReceiptsDisp13_2}-->
      <!--{assign var=key3 value=conveni_ReceiptsDisp13_3}-->
      <!--{assign var=key4 value=conveni_ReceiptsDisp13_4}-->
      <span class="attention"><!--{$arrErr[$key1]}--></span>
      <span class="attention"><!--{$arrErr[$key2]}--></span>
      <span class="attention"><!--{$arrErr[$key3]}--></span>
      <span class="attention"><!--{$arrErr[$key4]}--></span>
      <select name="<!--{$key1}-->" id="<!--{$key1}-->" <!--{$conveni_disable}-->>
	<option value="" selected="selected"></option>
	<!--{html_options options=$arrHour selected=$arrForm[$key1].value}-->
      </select>
      ： 
      <select name="<!--{$key2}-->" id="<!--{$key2}-->" <!--{$conveni_disable}-->>
	<option value="" selected="selected"></option>
	<!--{html_options options=$arrMinutes selected=$arrForm[$key2].value}-->
      </select>
      - 
      <select name="<!--{$key3}-->" id="<!--{$key3}-->" <!--{$conveni_disable}-->>
	<option value="" selected="selected"></option>
	<!--{html_options options=$arrHour selected=$arrForm[$key3].value}-->
      </select>
      ： 
      <select name="<!--{$key4}-->" id="<!--{$key4}-->" <!--{$conveni_disable}-->>
	<option value="" selected="selected"></option>
	<!--{html_options options=$arrMinutes selected=$arrForm[$key4].value}-->
      </select>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=conveni_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=conveni_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th colspan="2">▼モバイルSuica設定</th>
  </tr>
  <tr>
    <th>モバイルSuica</th>
    <td>
      <!--{assign var=key value="use_suica"}-->
      <!--{if $arrForm[$key].value == ''}--><!--{assign var=suica_disable value='disabled="disabled"'}--><!--{/if}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input onclick="toggleBox(document.form1.use_suica,'suica_PaymentTermDay','suica_PaymentTermSec','suicaAddInfo1','suicaAddInfo2','suicaAddInfo3','suicaAddInfo4','suica_ClientField1','suica_ClientField2');"
	     type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info">※ご利用頂く為には、お申し込みが必要です。お申し込みは<a href="http://www.gmo-pg.com/" target="_blank">こちら</a></span>
    </td>
  </tr>
  <tr>
    <th>支払期限</th>
    <td>
      <!--{assign var=key1 value=suica_PaymentTermDay}-->
      <!--{assign var=key2 value=suica_PaymentTermSec}-->
      <!--{assign var=key3 value=suica_PaymentTerm}-->
      <span class="attention"><!--{$arrErr[$key1]}--></span>
      <span class="attention"><!--{$arrErr[$key2]}--></span>
      <span class="attention"><!--{$arrErr[$key3]}--></span>
      <input id="<!--{$key1}-->"
	     type="text"
	     name="<!--{$key1}-->"
	     class="box6"
	     value="<!--{$arrForm[$key1].value|escape}-->"
	     maxlength="<!--{$arrForm[$key1].length|escape}-->" <!--{$suica_disable}-->>日&nbsp;
      <input id="<!--{$key2}-->"
	     type="text"
	     name="<!--{$key2}-->"
	     class="box6"
	     value="<!--{$arrForm[$key2].value|escape}-->"
	     maxlength="<!--{$arrForm[$key2].length|escape}-->" <!--{$suica_disable}-->>秒<br><br><br>
      <span class="info">※省略時は、ショップ管理画面のショップ情報に設定された支払期限で処理されます。</span>
    </td>
  </tr>
  <tr>
    <th>決済開始メール付加情報</th>
    <td>
      <!--{assign var=key value=suicaAddInfo1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <textarea id="<!--{$key}-->"
		name="<!--{$key}-->"
		class="area61"
		<!--{$suica_disable}-->><!--{$arrForm[$key].value|escape}--></textarea>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>決済完了メール付加情報</th>
    <td>
      <!--{assign var=key value=suicaAddInfo2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <textarea id="<!--{$key}-->"
		name="<!--{$key}-->"
		class="area61"
		<!--{$suica_disable}-->><!--{$arrForm[$key].value|escape}--></textarea>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>決済内容確認画面付加情報</th>
    <td>
      <!--{assign var=key value=suicaAddInfo3}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <textarea id="<!--{$key}-->"
		name="<!--{$key}-->"
		class="area61"
		<!--{$suica_disable}-->><!--{$arrForm[$key].value|escape}--></textarea>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>決済完了画面付加情報</th>
    <td>
      <!--{assign var=key value=suicaAddInfo4}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <textarea id="<!--{$key}-->"
		name="<!--{$key}-->"
		class="area61"
		<!--{$suica_disable}-->><!--{$arrForm[$key].value|escape}--></textarea>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=suica_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$suica_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=suica_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$suica_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th colspan="2">▼Mobile Edy設定</th>
  </tr>
  <tr>
    <th>Mobile Edy</th>
    <td>
      <!--{assign var=key value="use_edy"}-->
      <!--{if $arrForm[$key].value == ''}--><!--{assign var=edy_disable value='disabled="disabled"'}--><!--{/if}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input onclick="toggleBox(document.form1.use_edy,'edy_PaymentTermDay','edy_PaymentTermSec','edyAddInfo1','edyAddInfo2','edy_ClientField1','edy_ClientField2');"
	     type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info">※ご利用頂く為には、お申し込みが必要です。お申し込みは<a href="http://www.gmo-pg.com/" target="_blank">こちら</a></span>
    </td>
  </tr>
  <tr>
    <th>支払期限</th>
    <td>
      <!--{assign var=key1 value=edy_PaymentTermDay}-->
      <!--{assign var=key2 value=edy_PaymentTermSec}-->
      <!--{assign var=key3 value=edy_PaymentTerm}-->
      <span class="attention"><!--{$arrErr[$key1]}--></span>
      <span class="attention"><!--{$arrErr[$key2]}--></span>
      <span class="attention"><!--{$arrErr[$key3]}--></span>
      <input id="<!--{$key1}-->"
	     type="text"
	     name="<!--{$key1}-->"
	     class="box6"
	     value="<!--{$arrForm[$key1].value|escape}-->"
	     maxlength="<!--{$arrForm[$key1].length|escape}-->" <!--{$edy_disable}-->>日&nbsp;
      <input id="<!--{$key2}-->"
	     type="text"
	     name="<!--{$key2}-->"
	     class="box6"
	     value="<!--{$arrForm[$key2].value|escape}-->"
	     maxlength="<!--{$arrForm[$key2].length|escape}-->" <!--{$edy_disable}-->>秒<br><br><br>
      <span class="info">※省略時は、ショップ管理画面のショップ情報に設定された支払期限で処理されます。</span>
    </td>
  </tr>
  <tr>
    <th>決済開始メール付加情報</th>
    <td>
      <!--{assign var=key value=edyAddInfo1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <textarea id="<!--{$key}-->"
		name="<!--{$key}-->"
		class="area61"
		<!--{$edy_disable}-->><!--{$arrForm[$key].value|escape}--></textarea>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>決済完了メール付加情報</th>
    <td>
      <!--{assign var=key value=edyAddInfo2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <textarea id="<!--{$key}-->"
		name="<!--{$key}-->"
		class="area60"
		<!--{$edy_disable}-->><!--{$arrForm[$key].value|escape}--></textarea>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=edy_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$edy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=edy_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$edy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th colspan="2">▼Pay-easy決済設定</th>
  </tr>
  <tr>
    <th>Pay-easy</th>
    <td>
      <!--{assign var=key value="use_payeasy"}-->
      <!--{if $arrForm[$key].value == ''}--><!--{assign var=payeasy_disable value='disabled="disabled"'}--><!--{/if}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input onclick="toggleBox(document.form1.use_payeasy,'atm_PaymentTermDay','atm_RegisterDisp1','atm_ReceiptsDisp1','atm_ClientField1','atm_ClientField2','netbank_PaymentTermDay','netbank_ClientField1','netbank_ClientField2');"
	     type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info">※ご利用頂く為には、お申し込みが必要です。お申し込みは<a href="http://www.gmo-pg.com/" target="_blank">こちら</a></span>
    </td>
  </tr>
  <tr>
    <th colspan="2">▽ATM決済</th>
  </tr>
  <tr>
    <th>支払期限</th>
    <td>
      <!--{assign var=key value=atm_PaymentTermDay}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box6"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>日<br><br><br>
      <span class="info">※省略時は、ショップ管理画面のショップ情報に設定された支払期限で処理されます。</span>
    </td>
  </tr>
  <tr>
    <th>ATM表示欄1（店名）<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=atm_RegisterDisp1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box20"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>利用明細表示欄1（店名）<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=atm_ReceiptsDisp1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=atm_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=atm_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th colspan="2">▽ネットバンキング決済</th>
  </tr>
  <tr>
    <th>支払期限</th>
    <td>
      <!--{assign var=key value=netbank_PaymentTermDay}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box6"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>日<br><br><br>
      <span class="info">※省略時は、ショップ管理画面のショップ情報に設定された支払期限で処理されます。</span>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=netbank_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=netbank_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th colspan="2">▼PayPal設定</th>
  </tr>

  <tr>
    <th>PayPal</th>
    <td>
      <!--{assign var=key value="use_paypal"}-->
      <!--{if $arrForm[$key].value == ''}--><!--{assign var=paypal_disable value='disabled="disabled"'}--><!--{/if}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input onclick="toggleBox(document.form1.use_paypal,'paypal_ClientField1','paypal_ClientField2');"
	     type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info">※ご利用頂く為には、お申し込みが必要です。お申し込みは<a href="http://www.gmo-pg.com/" target="_blank">こちら</a><br>
	※モバイルには対応しておりません<br>
      </span>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=paypal_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=paypal_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$payeasy_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>

  <tr>
    <th colspan="2">▼iD決済設定</th>
  </tr>
  <tr>
    <th>iD</th>
    <td>
      <!--{assign var=key value="use_netid"}-->
      <!--{if $arrForm[$key].value == ''}--><!--{assign var=netid_disable value='disabled="disabled"'}--><!--{/if}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input onclick="toggleNetidBox();toggleBox(document.form1.use_netid,'netid_PaymentTermDay','netid_ClientField1','netid_ClientField2');"
	     type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info">※ご利用頂く為には、お申し込みが必要です。お申し込みは<a href="http://www.gmo-pg.com/" target="_blank">こちら</a>
      </span>
    </td>
  </tr>
  <tr>
    <th>処理区分<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=netid_jobcd}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="radio"
	     name="<!--{$key}-->"
	     value="0" <!--{if $arrForm[$key].value == '0'}-->checked=checked<!--{/if}--> <!--{$netid_disable}-->>AUTH(仮売上)<br />
      <input type="radio"
	     name="<!--{$key}-->"
	     value="2" <!--{if $arrForm[$key].value == '2'}-->checked=checked<!--{/if}--> <!--{$netid_disable}-->>CAPTURE(即時売上)<br />
      <br /><br />
  </tr>
  <tr>
    <th>支払期限</th>
    <td>
      <!--{assign var=key value=netid_PaymentTermDay}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box6"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$netid_disable}-->>日<br><br><br>
      <span class="info">※省略時は、ショップ管理画面のショップ情報に設定された支払期限で処理されます。</span>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=netid_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$netid_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=netid_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$netid_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>

  <tr>
    <th colspan="2">▼WebMoney決済設定</th>
  </tr>
  <tr>
    <th>WebMoney</th>
    <td>
      <!--{assign var=key value="use_webmoney"}-->
      <!--{if $arrForm[$key].value == ''}--><!--{assign var=webmoney_disable value='disabled="disabled"'}--><!--{/if}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input onclick="toggleBox(document.form1.use_webmoney,'webmoney_PaymentTermDay','webmoney_ClientField1','webmoney_ClientField2');"
	     type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info">※ご利用頂く為には、お申し込みが必要です。お申し込みは<a href="http://www.gmo-pg.com/" target="_blank">こちら</a>
      </span>
    </td>
  </tr>
  <tr>
    <th>支払期限</th>
    <td>
      <!--{assign var=key value=webmoney_PaymentTermDay}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box6"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$webmoney_disable}-->>日<br><br><br>
      <span class="info">※省略時は、ショップ管理画面のショップ情報に設定された支払期限で処理されます。</span>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=webmoney_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$webmoney_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=webmoney_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$webmoney_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>

  <tr>
    <th colspan="2">▼auかんたん決済設定</th>
  </tr>
  <tr>
    <th>auかんたん決済</th>
    <td>
      <!--{assign var=key value="use_au"}-->
      <!--{if $arrForm[$key].value == ''}--><!--{assign var=au_disable value='disabled="disabled"'}--><!--{/if}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input onclick="toggleAuBox();toggleBox(document.form1.use_au,'au_ServiceName','au_ServiceTel_1','au_ServiceTel_2','au_ServiceTel_3','au_PaymentTermSec','au_ClientField1','au_ClientField2');"
	     type="checkbox"
	     name="<!--{$key}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     value="1"
	     maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェックを入れてください<br><br><br>
      <span class="info">※ご利用頂く為には、お申し込みが必要です。お申し込みは<a href="http://www.gmo-pg.com/" target="_blank">こちら</a>
      </span>
    </td>
  </tr>
  <tr>
    <th>処理区分<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=au_jobcd}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="radio"
	     name="<!--{$key}-->"
	     value="0" <!--{if $arrForm[$key].value == '0'}-->checked=checked<!--{/if}--> <!--{$au_disable}-->>AUTH(仮売上)<br />
      <input type="radio"
	     name="<!--{$key}-->"
	     value="2" <!--{if $arrForm[$key].value == '2'}-->checked=checked<!--{/if}--> <!--{$au_disable}-->>CAPTURE(即時売上)<br />
      <br /><br />
  </tr>
  <tr>
    <th>表示サービス名<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=au_ServiceName}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box40"
	     value="<!--{$arrForm[$key].value}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>表示電話番号<span class="attention">※</span></th>
    <td>
      <!--{assign var=key1 value=au_ServiceTel_1}-->
      <!--{assign var=key2 value=au_ServiceTel_2}-->
      <!--{assign var=key3 value=au_ServiceTel_3}-->
      <!--{assign var=key4 value=au_ServiceTel}-->
      <span class="attention"><!--{$arrErr[$key1]}--></span>
      <span class="attention"><!--{$arrErr[$key2]}--></span>
      <span class="attention"><!--{$arrErr[$key3]}--></span>
      <span class="attention"><!--{$arrErr[$key4]}--></span>
      <input id="<!--{$key1}-->"
	     type="text"
	     name="<!--{$key1}-->"
	     class="box6"
	     value="<!--{$arrForm[$key1].value|escape}-->"
	     maxlength="<!--{$arrForm[$key1].length|escape}-->"
	     style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"
	     ime-mode: disabled; <!--{$au_disable}-->> - 
      <input id="<!--{$key2}-->"
	     type="text"
	     name="<!--{$key2}-->"
	     class="box6"
	     value="<!--{$arrForm[$key2].value|escape}-->"
	     maxlength="<!--{$arrForm[$key2].length|escape}-->"
	     style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"
	     ime-mode: disabled; <!--{$au_disable}-->> - 
      <input id="<!--{$key3}-->"
	     type="text"
	     name="<!--{$key3}-->"
	     class="box6"
	     value="<!--{$arrForm[$key3].value|escape}-->"
	     maxlength="<!--{$arrForm[$key3].length|escape}-->"
	     style="<!--{$arrErr[$key3]|sfGetErrorColor}-->"
	     ime-mode: disabled; <!--{$au_disable}-->>
    </td>
  </tr>
  <tr>
    <th>支払開始期限</th>
    <td>
      <!--{assign var=key value=au_PaymentTermSec}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box6"
	     value="<!--{$arrForm[$key].value}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$au_disable}-->>秒<br><br><br>
      <span class="info">※省略時は、120秒で処理されます。</span>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=au_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$au_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=au_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
	     type="text"
	     name="<!--{$key}-->"
	     class="box60"
	     value="<!--{$arrForm[$key].value}-->"
	     style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	     maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$au_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th colspan="2">▼ドコモケータイ払い設定</th>
  </tr>
  <tr>
    <th>ドコモケータイ払い</th>
    <td>
      <!--{assign var=key value="use_docomo"}-->
      <!--{if $arrForm[$key].value == ''}--><!--{assign var=au_disable value='disabled="disabled"'}--><!--{/if}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input onclick="toggleDocomoBox();toggleBox(document.form1.use_docomo,'DocomoDisp1','DocomoDisp2','docomo_PaymentTermSec','docomo_ClientField1','docomo_ClientField2');"
         type="checkbox"
         name="<!--{$key}-->"
         style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
         value="1"
         maxlength="<!--{$arrForm[$key].length}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 使用する場合はチェ
ックを入れてください<br><br><br>
      <span class="info">※ご利用頂く為には、お申し込みが必要です。お申し込みは<a href="http://www.gmo-pg.com/" target="_blank">こちら</a>
      </span>
    </td>
  </tr>
  <tr>
    <th>処理区分<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=docomo_jobcd}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="radio"
         name="<!--{$key}-->"
         value="0" <!--{if $arrForm[$key].value == '0'}-->checked=checked<!--{/if}--> <!--{$au_disable}-->>AUTH(仮売上)<br />
      <input type="radio"
         name="<!--{$key}-->"
         value="2" <!--{if $arrForm[$key].value == '2'}-->checked=checked<!--{/if}--> <!--{$au_disable}-->>CAPTURE(即時売上)<br />
      <br /><br />
  </tr>
  <tr>
    <th>ドコモ表示項目1<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=DocomoDisp1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
         type="text"
         name="<!--{$key}-->"
         class="box40"
         value="<!--{$arrForm[$key].value}-->"
         maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->
         style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>ドコモ表示項目2<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value=DocomoDisp2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
         type="text"
         name="<!--{$key}-->"
         class="box40"
         value="<!--{$arrForm[$key].value}-->"
         maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$conveni_disable}-->
         style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>支払開始期限</th>
    <td>
      <!--{assign var=key value=docomo_PaymentTermSec}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
         type="text"
         name="<!--{$key}-->"
         class="box6"
         value="<!--{$arrForm[$key].value}-->"
         style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
         maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$docomo_disable}-->>秒<br><br><br>
      <span class="info">※省略時は、120秒で処理されます。</span>
    </td>
  </tr>
  <tr>
    <th>自由項目1</th>
    <td>
      <!--{assign var=key value=docomo_ClientField1}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
         type="text"
         name="<!--{$key}-->"
         class="box60"
         value="<!--{$arrForm[$key].value}-->"
         style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
         maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$au_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
  <tr>
    <th>自由項目2</th>
    <td>
      <!--{assign var=key value=docomo_ClientField2}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input id="<!--{$key}-->"
         type="text"
         name="<!--{$key}-->"
         class="box60"
         value="<!--{$arrForm[$key].value}-->"
         style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
         maxlength="<!--{$arrForm[$key].length|escape}-->" <!--{$au_disable}-->>
      <span class="attention"> （上限<!--{$arrForm[$key].length|escape}-->文字）</span>
    </td>
  </tr>
</table>
<div class="btn-area">
  <ul>
    <li><a class="btn-action" href="javascript:;" onclick="fnModeSubmit('confirm_overwrite', '', ''); return false;"><span class="btn-next">確認画面へ</span></a></li>
  </ul>
</div>
</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
