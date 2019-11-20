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
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<script type="text/javascript">
<!--
self.moveTo(20,20);self.focus();

function lfnCheckPayment(){
    var fm = document.form1;
    var val = 0;

    var payment = new Array('payment[]');

    for(pi = 0; pi < payment.length; pi++) {
        // クレジットの場合
        list = new Array('credit[]', 'security_code', 'credit_3d', 'stock_card', 'payment_division[]', 'quick_pay', 'token_pay', 'token_env', 'token_key');
        if(fm[payment[pi]][0].checked){
            fnChangeDisabled(list, false);
        }else{
            fnChangeDisabled(list);
        }

        // コンビニ(番号方式)の場合
        list = new Array('convenience_num[]','conveni_limit_date_num');
        if(fm[payment[pi]][1].checked){
            fnChangeDisabled(list, false);
        }else{
            fnChangeDisabled(list);
        }
<!--{*
/*
 *        // コンビニ(払込票方式)の場合
 *        list = new Array('convenience_call[]','conveni_limit_date_call', 'conveni_valid_limit_date_call', 'conveni_free_memo_call');
 *        if(fm[payment[pi]][2].checked){
 *            fnChangeDisabled(list, false);
 *        }else{
 *            fnChangeDisabled(list);
 *        }
 */
*}-->
        // ATM決済の場合
        list = new Array('atm_limit_date', 'payment_detail');
        if(fm[payment[pi]][2].checked){
            fnChangeDisabled(list, false);
        }else{
            fnChangeDisabled(list);
        }

        // 銀行ネットの場合
        list = new Array('claim_kanji', 'claim_kana', 'asp_payment_term', 'copy_right', 'free_memo');
        if(fm[payment[pi]][3].checked){
            fnChangeDisabled(list, false);
        }else{
            fnChangeDisabled(list);
        }

        // 携帯キャリアの場合
        list = new Array('career_division[]');
        if (fm[payment[pi]][4].checked){
            fnChangeDisabled(list, false);
        } else {
            fnChangeDisabled(list);
        }

        // 電子マネーの場合
        list = new Array('emoney_division[]');
        if (fm[payment[pi]][5].checked){
            fnChangeDisabled(list, false);
        } else {
            fnChangeDisabled(list);
        }

        // 仮想口座の場合
        list = new Array('numbering_type', 'virtual_account_limit_date');
        if (fm[payment[pi]][6].checked){
            fnChangeDisabled(list, false);
        } else {
            fnChangeDisabled(list);
        }

        // 後払いの場合
        list = new Array('result_get_type', 'exam_result_notification_type', 'invoice_include[]');
        if (fm[payment[pi]][7].checked){
            fnChangeDisabled(list, false);
        } else {
            fnChangeDisabled(list);
        }
        
        // Paidyの場合
        list = new Array('api_key', 'logo_url', 'paidy_store_name');
        if (fm[payment[pi]][8].checked){
            fnChangeDisabled(list, false);
        } else {
            fnChangeDisabled(list);
        }
    }
}

function lfnCheckLinkPayment(){
    var fm = document.form1;
    var val = 0;

    var link_payment = new Array('link_payment[]');

    for(pi = 0; pi < link_payment.length; pi++) {
        // 後払いの場合
        list = new Array('link_result_get_type', 'link_auto_cancel_type', 'link_invoice_include[]');
        if (fm[link_payment[pi]].checked){
            fnChangeDisabled(list, false);
        } else {
            fnChangeDisabled(list);
        }
    }
}

function fnChangeDisabled(list, disable) {
    len = list.length;

    if(disable == null) { disable = true; }

    for(i = 0; i < len; i++) {
        if(document.form1[list[i]]) {
            // ラジオボタン、チェックボックス等の配列に対応
            max = document.form1[list[i]].length
            if(max > 1) {
                for(j = 0; j < max; j++) {
                    // 有効、無効の切り替え
                    document.form1[list[i]][j].disabled = disable;
                }
            } else {
                // 有効、無効の切り替え
                document.form1[list[i]].disabled = disable;
            }
        }
    }
}

function win_open(URL){
    var WIN;
    WIN = window.open(URL);
    WIN.focus();
}

// 決済種別ごとに表示させる項目を切り替える
function lfCheckSettlement(value){
    if(value == 1){
        lfDispSwitch("connect_id",false);
        lfDispSwitch("connect_password",false);
        lfDispSwitch("link_url",true);
        lfDispSwitch("hash_key",true);
        lfDispSwitch("payment",false);
        lfDispSwitch("link_payment",true);
        lfDispSwitch("credit",false);
    lfDispSwitch("security_code",false);
        lfDispSwitch("credit_3d",false);
        lfDispSwitch("stock_card",false);
        lfDispSwitch("payment_division",false);
    lfDispSwitch("quick_pay",false);
        lfDispSwitch("token_pay",false);
        lfDispSwitch("token_env",false);
        lfDispSwitch("token_key",false);
        lfDispSwitch("conveni_num",false);
        lfDispSwitch("conveni_limit_date_num",false);
//        lfDispSwitch("conveni_call",false);
//        lfDispSwitch("conveni_limit_date_call",false);
//        lfDispSwitch("conveni_valid_limit_date_call",false);
//        lfDispSwitch("conveni_free_memo_call",false);
        lfDispSwitch("atm",false);
        lfDispSwitch("atm_limit_date",false);
        lfDispSwitch("payment_detail",false);
        lfDispSwitch("bank",false);
        lfDispSwitch("asp_payment_term",false);
        lfDispSwitch("claim_kanji",false);
        lfDispSwitch("claim_kana",false);
        lfDispSwitch("copy_right",false);
        lfDispSwitch("free_memo",false);
        lfDispSwitch("career",false);
        lfDispSwitch("career_division",false);
        lfDispSwitch("emoney",false);
        lfDispSwitch("emoney_division",false);
        lfDispSwitch("link",true);
        lfDispSwitch("card_class",true);
        lfDispSwitch("card_conf",true);
        lfDispSwitch("link_payment_term",true);
        lfDispSwitch("merchant_name",true);
        lfDispSwitch("link_copy_right",true);
        lfDispSwitch("link_free_memo",true);
        lfDispSwitch("virtual_account",false);
        lfDispSwitch("numbering_type",false);
        lfDispSwitch("virtual_account_limit_date",false);
        lfDispSwitch("later_payment",false);
        lfDispSwitch("result_get_type",false);
        lfDispSwitch("exam_result_notification_type",false);
        lfDispSwitch("invoice_include",false);
        lfDispSwitch("link_result_get_type",true);
        lfDispSwitch("link_auto_cancel_type",true);
        lfDispSwitch("link_invoice_include",true);
        lfDispSwitch("paidy_payment",false);
        lfDispSwitch("api_key",false);
        lfDispSwitch("logo_url",false);
        lfDispSwitch("paidy_store_name",false);
    }
    if(value == 2){
        lfDispSwitch("connect_id",true);
        lfDispSwitch("connect_password",true);
        lfDispSwitch("link_url",false);
        lfDispSwitch("hash_key",false);
        lfDispSwitch("payment",true);
        lfDispSwitch("link_payment",false);
        lfDispSwitch("credit",true);
    lfDispSwitch("security_code",true);
        lfDispSwitch("credit_3d",true);
        lfDispSwitch("stock_card",true);
        lfDispSwitch("payment_division",true);
    lfDispSwitch("quick_pay",true);
        lfDispSwitch("token_pay",true);
        lfDispSwitch("token_env",true);
        lfDispSwitch("token_key",true);
        lfDispSwitch("conveni_num",true);
        lfDispSwitch("conveni_limit_date_num",true);
//        lfDispSwitch("conveni_call",true);
//        lfDispSwitch("conveni_limit_date_call",true);
//        lfDispSwitch("conveni_valid_limit_date_call",true);
//        lfDispSwitch("conveni_free_memo_call",true);
        lfDispSwitch("atm",true);
        lfDispSwitch("atm_limit_date",true);
        lfDispSwitch("payment_detail",true);
        lfDispSwitch("bank",true);
        lfDispSwitch("asp_payment_term",true);
        lfDispSwitch("claim_kanji",true);
        lfDispSwitch("claim_kana",true);
        lfDispSwitch("copy_right",true);
        lfDispSwitch("free_memo",true);
        lfDispSwitch("career",true);
        lfDispSwitch("career_division",true);
        lfDispSwitch("emoney",true);
        lfDispSwitch("emoney_division",true);
        lfDispSwitch("link",false);
        lfDispSwitch("card_class",false);
        lfDispSwitch("card_conf",false);
        lfDispSwitch("link_payment_term",false);
        lfDispSwitch("merchant_name",false);
        lfDispSwitch("link_copy_right",false);
        lfDispSwitch("link_free_memo",false);
        lfDispSwitch("virtual_account",true);
        lfDispSwitch("numbering_type",true);
        lfDispSwitch("virtual_account_limit_date",true);
        lfDispSwitch("later_payment",true);
        lfDispSwitch("result_get_type",true);
        lfDispSwitch("exam_result_notification_type",true);
        lfDispSwitch("invoice_include",true);
        lfDispSwitch("link_result_get_type",false);
        lfDispSwitch("link_auto_cancel_type",false);
        lfDispSwitch("link_invoice_include",false);
        lfDispSwitch("paidy_payment",true);
        lfDispSwitch("api_key",true);
        lfDispSwitch("logo_url",true);
        lfDispSwitch("paidy_store_name",true);
    }
    if(value == 3){
        lfDispSwitch("connect_id",true);
        lfDispSwitch("connect_password",true);
        lfDispSwitch("link_url",true);
        lfDispSwitch("hash_key",true);
        lfDispSwitch("payment",false);
        lfDispSwitch("link_payment",true);
        lfDispSwitch("credit",false);
    lfDispSwitch("security_code",false);
        lfDispSwitch("credit_3d",false);
        lfDispSwitch("stock_card",false);
        lfDispSwitch("payment_division",false);
    lfDispSwitch("quick_pay",false);
        lfDispSwitch("token_pay",false);
        lfDispSwitch("token_env",false);
        lfDispSwitch("token_key",false);
        lfDispSwitch("conveni_num",false);
        lfDispSwitch("conveni_limit_date_num",false);
//        lfDispSwitch("conveni_call",false);
//        lfDispSwitch("conveni_limit_date_call",false);
//        lfDispSwitch("conveni_valid_limit_date_call",false);
//        lfDispSwitch("conveni_free_memo_call",false);
        lfDispSwitch("atm",false);
        lfDispSwitch("atm_limit_date",false);
        lfDispSwitch("payment_detail",false);
        lfDispSwitch("bank",false);
        lfDispSwitch("asp_payment_term",false);
        lfDispSwitch("claim_kanji",false);
        lfDispSwitch("claim_kana",false);
        lfDispSwitch("copy_right",false);
        lfDispSwitch("free_memo",false);
        lfDispSwitch("career",false);
        lfDispSwitch("career_division",false);
        lfDispSwitch("emoney",false);
        lfDispSwitch("emoney_division",false);
        lfDispSwitch("link",true);
        lfDispSwitch("card_class",true);
        lfDispSwitch("card_conf",true);
        lfDispSwitch("link_payment_term",true);
        lfDispSwitch("merchant_name",true);
        lfDispSwitch("link_copy_right",true);
        lfDispSwitch("link_free_memo",true);
        lfDispSwitch("virtual_account",false);
        lfDispSwitch("numbering_type",false);
        lfDispSwitch("virtual_account_limit_date",false);
        lfDispSwitch("later_payment",false);
        lfDispSwitch("result_get_type",false);
        lfDispSwitch("exam_result_notification_type",false);
        lfDispSwitch("invoice_include",false);
        lfDispSwitch("link_result_get_type",true);
        lfDispSwitch("link_auto_cancel_type",true);
        lfDispSwitch("link_invoice_include",true);
        lfDispSwitch("paidy_payment",false);
        lfDispSwitch("api_key",false);
        lfDispSwitch("logo_url",false);
        lfDispSwitch("paidy_store_name",false);
    }
}

// URLの表示非表示切り替え
function lfDispSwitch(id, disp_flg){
    var obj = document.getElementById(id);
    if (disp_flg == true) {
        obj.style.display = '';
    } else {
        obj.style.display = 'none';
    }
}

//-->
</script>

<div onload='lfnCheckPayment(); '>
<h2><!--{$tpl_subtitle}--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit">
<p>ペイジェント決済モジュールをご利用頂く為には、
ユーザ様ご自身で株式会社ペイジェントとご契約頂く必要があります。
お申し込みにつきましては、下記のページよりお問い合せ下さい。<br/><br/>
<a href="#" onClick="win03('http://www.paygent.co.jp/service/')" > ＞＞ ペイジェント決済代行サービスについて</a>
</p>


<!--{if $arrErr.err != ""}-->
<table border="0" cellspacing="0" cellpadding="0" summary=" ">
    <tr>
        <td><span class="red"><!--{$arrErr.err}--></span><td>
    </tr>
</table>
<!--{/if}-->

<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td width="100" bgcolor="#f3f3f3">システム種別<span class="red">※</span></td>
        <td width="300" >
        <!--{assign var=key value="settlement_division"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{*html_radios name="$key" options=$arrSettlement selected=$smarty.const.SETTLEMENT_MODULE style=$arrErr[$key]|sfGetErrorColor onclick="lfCheckSettlement(value);"*}-->
        <!--{html_radios name="$key" options=$arrSettlement selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor onclick="lfCheckSettlement(value);"}-->
        </td>
    </tr>
    <tr>
        <td width="100" bgcolor="#f3f3f3">マーチャントID<span class="red">※</span></td>
        <td width="300" >
        <!--{assign var=key value="merchant_id"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box20" maxlength="<!--{$arrForm[$key].length}-->">
        </td>
    </tr>
    <tr id="connect_id" >
        <td bgcolor="#f3f3f3">接続ID<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="connect_id"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box20" maxlength="<!--{$arrForm[$key].length}-->">
        </td>
    </tr>
    <tr id="connect_password" >
        <td bgcolor="#f3f3f3">接続パスワード<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="connect_password"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="password" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box20" maxlength="<!--{$arrForm[$key].length}-->">
        </td>
    </tr>
    <tr id="link_url" >
        <td bgcolor="#f3f3f3">リンクタイプ<br />リクエスト先URL<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="link_url"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box40" maxlength="<!--{$arrForm[$key].length}-->"><br />
        <span class="fs14n">※ペイジェントとの契約時に通知される決済用URLです。<br />　[charset=UTF-8]のURLを入力してください。</span>
        </td>
    </tr>
    <tr id="hash_key" >
        <td bgcolor="#f3f3f3">ハッシュ値生成キー</td>
        <td>
        <!--{assign var=key value="hash_key"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box20" maxlength="<!--{$arrForm[$key].length}-->">
        </td>
    </tr>
    <tr id="payment" >
        <td bgcolor="#f3f3f3">利用決済<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="payment"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_checkboxes_ex name="$key" options=$arrPayment selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor onclick="lfnCheckPayment();"}-->
        </td>
    </tr>

    <tr id="link_payment" >
        <td bgcolor="#f3f3f3">利用決済</td>
        <td>
        <!--{assign var=key value="link_payment"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_checkboxes_ex name="$key" options=$arrLinkPayment selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor onclick="lfnCheckLinkPayment();"}-->
        </td>
    </tr>
    <tr id="credit" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼クレジット決済設定</td>
    </tr>
    <tr id="payment_division" >
        <td bgcolor="#f3f3f3">支払回数<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="payment_division"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_checkboxes_ex name="$key" options=$arrCartPaymentCategory selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="security_code">
        <td bgcolor="#f3f3f3">セキュリティコード</td>
    <td>
        <!--{assign var=key value="security_code"}-->
    <span class="red"><!--{$arrErr[$key]}--></span>
    <!--{html_radios name="$key" options=$arrActive selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
    </td>
    </tr>
    <tr id="credit_3d" >
        <td bgcolor="#f3f3f3">3Dセキュア</td>
        <td>
        <!--{assign var=key value="credit_3d"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_radios name="$key" options=$arrActive selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="stock_card" >
        <td bgcolor="#f3f3f3">カード情報お預かり機能</td>
        <td>
        <!--{assign var=key value="stock_card"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_radios name="$key" options=$arrActive selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="quick_pay" >
        <td bgcolor="#f3f3f3">クイック決済</td>
        <td>
        <!--{assign var=key value="quick_pay"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_radios name="$key" options=$arrActive selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="token_pay" >
        <td bgcolor="#f3f3f3">トークン決済</td>
        <td>
        <!--{assign var=key value="token_pay"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_radios name="$key" options=$arrActive selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="token_env" >
        <td bgcolor="#f3f3f3">トークン接続先</td>
        <td>
        <!--{assign var=key value="token_env"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_radios name="$key" options=$arrTokenEnv selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="token_key" >
        <td bgcolor="#f3f3f3">トークン生成鍵</td>
        <td>
        <!--{assign var=key value="token_key"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->">
        </td>
    </tr>

    <tr id="conveni_num" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼コンビニ決済(番号方式)設定</td>
    </tr>
    <tr id="conveni_limit_date_num" >
        <td bgcolor="#f3f3f3">支払期限日<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="conveni_limit_date_num"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        購入日より<input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" size="2" maxlength="<!--{$arrForm[$key].length}-->">日
        </td>
    </tr>
<!--{*
/*
 *    <tr id="conveni_call" >
 *        <td colspan="2" width="90" bgcolor="#f3f3f3">▼コンビニ決済(払込票方式)設定</td>
 *    </tr>
 *    <tr id="conveni_limit_date_call" >
 *        <td bgcolor="#f3f3f3">支払期限日</td>
 *        <td>
 *        <!--{assign var=key value="conveni_limit_date_call"}-->
 *        <span class="red"><!--{$arrErr[$key]}--></span>
 *        購入日より<input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" size="3" maxlength="<!--{$arrForm[$key].length}-->">日
 *        </td>
 *    </tr>
 *    <tr id="conveni_valid_limit_date_call" >
 *        <td bgcolor="#f3f3f3">有効期限日</td>
 *        <td>
 *        <!--{assign var=key value="conveni_valid_limit_date_call"}-->
 *        <span class="red"><!--{$arrErr[$key]}--></span>
 *        購入日より<input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" size="3" maxlength="<!--{$arrForm[$key].length}-->">日
 *        </td>
 *    </tr>
 *        <!--{assign var=key value="conveni_free_memo_call"}-->
 *    <tr id="conveni_free_memo_call" >
 *        <td bgcolor="#f3f3f3">支払情報(全角)<span class="red">※</span></td>
 *        <td>
 *        <span class="red"><!--{$arrErr[$key]}--></span>
 *        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br />
 *        <span class="fs14n">※ 払込票に表示される備考内容</span>
 *        </td>
 *    </tr>
 */
*}-->
    <tr id="atm" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼ATM決済設定</td>
    </tr>
    <tr id="atm_limit_date" >
        <td bgcolor="#f3f3f3">支払期限日<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="atm_limit_date"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        購入日より<input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" size="2" maxlength="<!--{$arrForm[$key].length}-->">日
        </td>
    </tr>
    <tr id="payment_detail" >
        <td bgcolor="#f3f3f3">店舗名（カナ）<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="payment_detail"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br>
        <span class="fs14n">※ 入金時に画面表示される店舗名「○○○オンラインショップ」等</span>
        </td>
    </tr>

    <tr id="bank" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼銀行ネット決済設定</td>
    </tr>
    <tr id="asp_payment_term" >
        <td bgcolor="#f3f3f3">支払期限日<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="asp_payment_term"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        購入日より<input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" size="2" maxlength="<!--{$arrForm[$key].length}-->">日
        </td>
    </tr>
    <tr id="claim_kanji" >
        <td bgcolor="#f3f3f3">店舗名（全角）<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="claim_kanji"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br>
        <span class="fs14n">※ 決済画面に表示される店舗名「○○○オンラインショップ」等</span>
        </td>
    </tr>
    <tr id="claim_kana" >
        <td bgcolor="#f3f3f3">店舗名（カナ）<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="claim_kana"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br>
        <span class="fs14n">※ 決済画面に表示される店舗名「○○○オンラインショップ」等</span>
        </td>
    </tr>
    <tr id="copy_right" >
        <td bgcolor="#f3f3f3">コピーライト(半角英数)</td>
        <td>
        <!--{assign var=key value="copy_right"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br>
        <span class="fs14n">※ 決済画面に表示されるコピーライト「Copyright (c) ・・・」等</span>
        </td>
    </tr>
    <tr id="free_memo" >
        <td bgcolor="#f3f3f3">自由メモ欄(全角)</td>
        <td>
        <!--{assign var=key value="free_memo"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->" /><br>
        <span class="fs14n">※ 決済画面に表示されるメッセージ「ありがとうございます」等</span>
        </td>
    </tr>
    <tr id="career" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼携帯キャリア決済設定</td>
    </tr>
    <tr id="career_division" >
        <td bgcolor="#f3f3f3">利用決済<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="career_division"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_checkboxes_ex name="$key" options=$arrCareerPaymentCategory selected=$arrForm[$key].value separator="<br>" style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="emoney" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼電子マネー決済設定</td>
    </tr>
    <tr id="emoney_division" >
        <td bgcolor="#f3f3f3">利用決済<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="emoney_division"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_checkboxes_ex name="$key" options=$arrEmoneyPaymentCategory selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="virtual_account" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼仮想口座決済設定</td>
    </tr>
    <tr id="numbering_type" >
        <td bgcolor="#f3f3f3">付番区分</td>
        <td>
            <!--{assign var=key value="numbering_type"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <!--{html_radios name="$key" options=$arrNumberingType selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="virtual_account_limit_date" >
        <td bgcolor="#f3f3f3">支払期限日<span class="red">※</span></td>
        <td>
            <!--{assign var=key value="virtual_account_limit_date"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            購入日より<input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" size="2" maxlength="<!--{$arrForm[$key].length}-->">日
        </td>
    </tr>
    <tr id="later_payment" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼後払い決済設定</td>
    </tr>
    <tr id="result_get_type" >
        <td bgcolor="#f3f3f3">結果取得区分</td>
        <td>
            <!--{assign var=key value="result_get_type"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <!--{html_radios name="$key" options=$arrResultGetType selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="exam_result_notification_type" >
        <td bgcolor="#f3f3f3">審査結果通知メール</td>
        <td>
            <!--{assign var=key value="exam_result_notification_type"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <!--{html_radios name="$key" options=$arrExamResultNotificationType selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="invoice_include" >
        <td bgcolor="#f3f3f3">請求書の同梱</td>
        <td>
        <!--{assign var=key value="invoice_include"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_checkboxes_ex name="$key" options=$arrInvoiceIncludeOption selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        <div style="margin-top:10px;">
            <span class="fs14n">※ 購入者情報と配送先情報が同一の場合、「同梱」として登録します。</span><br />
            <span class="fs14n">※ 同梱のご利用には株式会社JACCSによる審査が必要となります。</span><br />
        </div>
        </td>
    </tr>
    <tr id="paidy_payment" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼Paidy</td>
    </tr>
    <tr id="api_key" >
        <td bgcolor="#f3f3f3">パブリックキー<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="api_key"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br>
        <span class="fs14n">※ 本番用は「pk_live～」、試験用は「pk_test～」となります。入れ間違いにご注意ください。</span>
        </td>
    </tr>
    <tr id="logo_url" >
        <td bgcolor="#f3f3f3">ロゴURL</td>
        <td>
        <!--{assign var=key value="logo_url"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br>
        <span class="fs14n">※ Checkout画面に表示するロゴの参照先ＵＲＬです。</span><br>
        <span class="fs14n">※ 未設定の場合は、Paidyのロゴが表示されます。</span>
        </td>
    </tr>
    <tr id="paidy_store_name" >
        <td bgcolor="#f3f3f3">店舗名</td>
        <td>
        <!--{assign var=key value="paidy_store_name"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br>
        <span class="fs14n">※ 決済画面に表示される店舗名「○○○オンラインショップ」等</span>
        </td>
    </tr>
    <tr id="link" >
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼リンクタイプ決済設定</td>
    </tr>
    <tr id="card_class" >
        <td bgcolor="#f3f3f3">カード支払区分</td>
        <td>
        <!--{assign var=key value="card_class"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_radios name="$key" options=$arrCardClass selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="card_conf" >
        <td bgcolor="#f3f3f3">カード確認番号</td>
        <td>
        <!--{assign var=key value="card_conf"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_radios name="$key" options=$arrActive selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="link_payment_term" >
        <td bgcolor="#f3f3f3">支払期限日</td>
        <td>
        <!--{assign var=key value="link_payment_term"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        購入日より<input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" size="2" maxlength="<!--{$arrForm[$key].length}-->">日
        </td>
    </tr>
    <tr id="link_result_get_type" >
        <td bgcolor="#f3f3f3">後払い結果取得区分</td>
        <td>
            <!--{assign var=key value="link_result_get_type"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <!--{html_radios name="$key" options=$arrResultGetType selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="link_auto_cancel_type" >
        <td bgcolor="#f3f3f3">後払い自動キャンセル区分</td>
        <td>
            <!--{assign var=key value="link_auto_cancel_type"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <!--{html_radios name="$key" options=$arrAutoCancelType selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
    </tr>
    <tr id="link_invoice_include" >
        <td bgcolor="#f3f3f3">後払い請求書の同梱</td>
        <td>
        <!--{assign var=key value="link_invoice_include"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <!--{html_checkboxes_ex name="$key" options=$arrInvoiceIncludeOption selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        <div style="margin-top:10px;">
            <span class="fs14n">※ 購入者情報と配送先情報が同一の場合、「同梱」として登録します。</span><br />
            <span class="fs14n">※ 同梱のご利用には株式会社JACCSによる審査が必要となります。</span><br />
        </div>
        </td>
    </tr>
    <tr id="merchant_name" >
        <td bgcolor="#f3f3f3">店舗名(全角)</td>
        <td>
        <!--{assign var=key value="merchant_name"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br>
        <span class="fs14n">※ 決済画面に表示される店舗名「○○○オンラインショップ」等</span>
        </td>
    </tr>
    <tr id="link_copy_right" >
        <td bgcolor="#f3f3f3">コピーライト(半角英数)</td>
        <td>
        <!--{assign var=key value="link_copy_right"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->"><br>
        <span class="fs14n">※ 決済画面に表示されるコピーライト「Copyright (c) ・・・」等</span>
        </td>
    </tr>
    <tr id="link_free_memo" >
        <td bgcolor="#f3f3f3">自由メモ欄(全角)</td>
        <td>
        <!--{assign var=key value="link_free_memo"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->" /><br />
        <span class="fs14n">※ 決済画面に表示されるメッセージ「ありがとうございます」等</span>
        </td>
    </tr>

    <tr>
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼カスタマイズの適用</td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">ファイルのコピー</td>
        <td>
        <span class="red">※設定内容の登録時に下記のファイルが上書きされます。<br />下記ファイルのバックアップを推奨します。
        <br />すでにカスタマイズを行っている場合はシステム会社にご相談ください。<br />
        <!--{foreach from=$arrUpdateFile item="arrSrc"}-->
            <br /><!--{$arrSrc.disp}-->
        <!--{/foreach}-->
        </span>
        </td>
    </tr>

</table>

<div class="btn-area">
    <ul>
        <li>
            <a class="btn-action" href="javascript:;" onclick="document.body.style.cursor = 'wait';document.form1.submit();return false;"><span class="btn-next">この内容で登録する</span></a>
        </li>
    </ul>
</div>

</form>
</div>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
