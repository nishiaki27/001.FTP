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
<!--{if $arrOrderPaygent.memo01 == $smarty.const.MDL_PAYGENT_CODE && ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CREDIT
	|| $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_D || $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_A
	|| $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_S || $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_EMONEY_W
	|| $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_YAHOOWALLET
	|| $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_PAIDY)}-->

    <!--{if $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CREDIT}-->
        <!--{assign var=type value='カード'}-->
    <!--{elseif ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_D || $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_A || $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_S)}-->
        <!--{assign var=type value='携帯キャリア'}-->
    <!--{elseif ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_EMONEY_W)}-->
        <!--{assign var=type value='電子マネー'}-->
    <!--{elseif ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_YAHOOWALLET)}-->
        <!--{assign var=type value='Yahoo!ウォレット'}-->
    <!--{elseif ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_PAIDY)}-->
        <!--{assign var=type value='Paidy'}-->
        <!--{assign var="memo02" value=$arrOrderPaygent.memo02|unserialize}-->
    <!--{/if}-->
<input type="hidden" name="paygent_type" value="">
<table class="form">
    <tr>
        <th colspan="4">▼<!--{$type}--></td>
    </tr>
    <tr>
        <th><!--{$type}-->ステータス</td>
        <!--{if $paygent_return != "" && $paygent_return.revice_price_error == ""}-->
            <!--{if $paygent_return.return == true}-->
            	<!--{assign var=message value=`$arrDispKind[$paygent_return.kind]`に成功しました。}-->
            <!--{else}-->
            	<!--{assign var=message value=`$arrDispKind[$paygent_return.kind]`に失敗しました。`$paygent_return.response`}-->
            <!--{/if}-->
        <!--{else}-->
        <!--{assign var=message value=`$arrDispKind[$arrOrderPaygent.memo09]`}-->
        <!--{/if}-->
        <td><!--{$message|default:"(未処理)"}--></td>
    </tr>

    <!--{if (strlen($arrOrderPaygent.memo05) !== 0 && $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CREDIT) }-->
    <tr>
        <th><!--{$type}-->エラーメッセージ</td>
        <td>
            <span class="attention"><!--{$arrOrderPaygent.memo05}--></span>
        </td>
    </tr>
    <!--{/if}-->
	<!--{* クレジット決済 オーソリ変更ボタン画面表示条件 *}-->
    <!--{if ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CREDIT && strlen($arrOrderPaygent.memo06) != 0 &&
         ($arrOrderPaygent.memo07 == '20' || strlen($arrOrderPaygent.memo07) == 0) && (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CREDIT) )}-->
    <tr>
        <th><!--{$type}-->オーソリ変更</td>
        <td>
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','change_auth'); return false;" value="オーソリ変更">
            <span class="attention">※ 金額変更時には、オーソリ変更ボタンをクリックしてください。</span>
        </td>
    </tr>
    <!--{elseif ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CREDIT && strlen($arrOrderPaygent.memo06) != 0 &&
          ($arrOrderPaygent.memo07 == '40' || $arrOrderPaygent.memo07 == '20' || strlen($arrOrderPaygent.memo07) == 0) && ($arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CARD_COMMIT || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CARD_COMMIT_REVICE))}-->
    <tr>
        <th><!--{$type}-->売上変更</td>
        <td>
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','change_commit'); return false;" value="売上変更">
            <span class="attention">※ 金額変更時には、売上変更ボタンをクリックしてください。</span>
        </td>
    </tr>
	<!--{/if}-->

	<!--{* 携帯キャリア決済 売上変更ボタン画面表示条件 *}-->
    <!--{if (($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_D && strlen($arrOrderPaygent.memo06) != 0
    	&& ($arrOrderPaygent.memo07 == '20' || $arrOrderPaygent.memo07 == '21')
    	&& strlen($arrOrderPaygent.memo09) == 0)
    	|| ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_D && strlen($arrOrderPaygent.memo06) != 0
    	&& $arrOrderPaygent.memo07 == '44'
    	&& ($arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CAREER_COMMIT || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CAREER_COMMIT_REVICE))) }-->
    <tr>
        <th><!--{$type}-->売上変更</td>
        <td>
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','change_carrer_auth'); return false;" value="売上変更">
            <span class="attention">※ 金額変更時には、売上変更ボタンをクリックしてください。</span>
        </td>
    </tr>
    <!--{/if}-->

    <!--{if ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_A && strlen($arrOrderPaygent.memo06) != 0 &&
         $arrOrderPaygent.memo07 == '20' && strlen($arrOrderPaygent.memo09) == 0 )}-->
    <tr>
        <th><!--{$type}-->売上変更</td>
        <td>
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','change_carrer_auth'); return false;" value="売上変更">
            <span class="attention">※ 金額変更時には、売上変更ボタンをクリックしてください。</span>
        </td>
    </tr>
    <!--{/if}-->

    <!--{* 電子マネー決済 売上変更ボタン画面表示条件 *}-->
    <!--{if ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_EMONEY_W && strlen($arrOrderPaygent.memo06) != 0 &&
         $arrOrderPaygent.memo07 == '40' && (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_EMONEY_COMMIT_REVICE))}-->
    <tr>
        <th><!--{$type}-->売上変更</td>
        <td>
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','change_emoney'); return false;" value="売上変更">
            <span class="attention">※ 金額変更時には、売上変更ボタンをクリックしてください。</span>
        </td>
    </tr>
    <!--{/if}-->

    <!--{* Yahoo!ウォレット決済 金額変更ボタン画面表示条件 *}-->
    <!--{if ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_YAHOOWALLET && strlen($arrOrderPaygent.memo06) != 0 &&
         $arrOrderPaygent.memo07 == '20' && (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_YAHOOWALLET_COMMIT_REVICE))}-->
    <tr>
        <th><!--{$type}-->金額変更</td>
        <td>
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','change_yahoowallet'); return false;" value="金額変更">
            <span class="attention">※ 金額変更時には、金額変更ボタンをクリックしてください。</span>
        </td>
    </tr>
    <!--{/if}-->
    
    <!--{* Paidy決済 売上変更ボタン画面表示条件 *}-->
    <!--{if ($arrOrderPaygent.memo09 == $smarty.const.PAYGENT_PAIDY_COMMIT)}-->
    <tr>
        <th><!--{$type}-->売上変更</td>
        <td>
            <!--{if $paygent_return.paidy_error}-->
                <div class="attention"><!--{$paygent_return.paidy_error}--></div>
            <!--{/if}-->
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','change_paidy'); return false;" value="売上変更">
            <span class="attention">
                ※&nbsp;決済金額変更時には、売上変更ボタンをクリックしてください。決済金額が増加する変更には対応していません。
            </span>
        </td>
    </tr>
	<!--{/if}-->

    <tr>
        <th><!--{$type}-->電文送信</td>
        <td>
        	<!--{* クレジット決済 売上ボタン画面表示条件 *}-->
            <!--{if ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CREDIT && strlen($arrOrderPaygent.memo06) != 0 &&
                     ($arrOrderPaygent.memo07 == '20' || strlen($arrOrderPaygent.memo07) == 0) && (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CREDIT) )}-->
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','card_commit'); return false;" value="売上">

            <!--{* 携帯キャリア決済 売上ボタン画面表示条件 *}-->
            <!--{elseif
                    (
                    ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_D && ($arrOrderPaygent.memo07 == '20' || $arrOrderPaygent.memo07 == '21') && strlen($arrOrderPaygent.memo09) == 0)
                    || ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_A && $arrOrderPaygent.memo07 == '20' && strlen($arrOrderPaygent.memo09) == 0)
                    || ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_S && ($arrOrderPaygent.memo07 == '20' || $arrOrderPaygent.memo07 == '21') && strlen($arrOrderPaygent.memo09) == 0)
                    ) }-->
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','career_commit'); return false;" value="売上">
            <!--{* Yahoo!ウォレット決済 売上ボタン画面表示条件 *}-->
            <!--{elseif
                    ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_YAHOOWALLET && $arrOrderPaygent.memo07 == '20' && (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_YAHOOWALLET_COMMIT_REVICE))}-->
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','yahoowallet_commit'); return false;" value="売上">
            <!--{* Paidy決済 売上ボタン画面表示条件 *}-->
            <!--{elseif $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_PAIDY_AUTHORIZED}-->
                <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','paidy_commit'); return false;" value="売上">
            <!--{else}-->
            <input type="button" value="売上" disabled="true">
            <!--{/if}-->

			<!--{* クレジット決済 取消ボタン画面表示条件 *}-->
            <!--{if $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CREDIT && strlen($arrOrderPaygent.memo06) != 0 &&
                  (($arrOrderPaygent.memo07 != '33'   &&  (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CREDIT)) ||
                   ($arrOrderPaygent.memo07 != '41'   &&  $arrOrderPaygent.memo09 == '022') ||
                   ($arrOrderPaygent.memo09 == '029') || ($arrOrderPaygent.memo09 == '022'))}-->
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','<!--{if (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CREDIT)}-->auth_cancel<!--{else}-->card_commit_cancel<!--{/if}-->'); return false;" value="取消">
            <!--{* 携帯キャリア決済 取消ボタン画面表示条件 *}-->
            <!--{elseif (
                    ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_D && ($arrOrderPaygent.memo07 == '20' || $arrOrderPaygent.memo07 == '21') && strlen($arrOrderPaygent.memo09) == 0)
                    || ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_D && $arrOrderPaygent.memo07 == '44' && ($arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CAREER_COMMIT || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CAREER_COMMIT_REVICE))
                    || ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_A && $arrOrderPaygent.memo07 == '20' && (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CAREER_COMMIT || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CAREER_COMMIT_REVICE))
                    || ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_A && $arrOrderPaygent.memo07 == '40' && ($arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CAREER_COMMIT || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CAREER_COMMIT_REVICE))
                    || ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_CAREER_S && ($arrOrderPaygent.memo07 == '20' || $arrOrderPaygent.memo07 == '21' || $arrOrderPaygent.memo07 == '40') && (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_CAREER_COMMIT))
                    ) }-->
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','career_commit_cancel'); return false;" value="取消">
            <!--{* 電子マネー決済 取消ボタン画面表示条件 *}-->
            <!--{elseif ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_EMONEY_W && $arrOrderPaygent.memo07 == '40' && (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_EMONEY_COMMIT_REVICE))}-->
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','emoney_cancel'); return false;" value="取消">
            <!--{* Yahoo!ウォレット決済 取消ボタン画面表示条件 *}-->
            <!--{elseif (($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_YAHOOWALLET && $arrOrderPaygent.memo07 == '20' && (strlen($arrOrderPaygent.memo09) == 0 || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_YAHOOWALLET_COMMIT || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_YAHOOWALLET_COMMIT_REVICE))
                    || ($arrOrderPaygent.memo08 == $smarty.const.PAYGENT_YAHOOWALLET && $arrOrderPaygent.memo07 == '40' && $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_YAHOOWALLET_COMMIT))}-->
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','yahoowallet_cancel'); return false;" value="取消">
            <!--{* Paidy決済 取消ボタン画面表示条件 *}-->
            <!--{elseif $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_PAIDY_AUTHORIZED || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_PAIDY_COMMIT}-->
            <input type="button" onclick="fnModeSubmit('paygent_order','paygent_type','paidy_cancel'); return false;" value="取消">
            <!--{else}-->
            <input type="button" value="取消" disabled="true">
            <!--{/if}-->
        </td>
    </tr>
</table>
<!--{/if}-->
<!--{* 後払い決済はテーブルレイアウトが異なるので別途作成する *}-->
<!--{if $arrOrderPaygent.memo01 == $smarty.const.MDL_PAYGENT_CODE && $arrOrderPaygent.memo08 == $smarty.const.PAYGENT_LATER_PAYMENT }-->
<!--{assign var=type value='後払い'}-->
<!--{* 請求書印字データ出力 表示制御 *}-->
<!--{if $arrOrderPaygent.invoice_send_type == $smarty.const.INVOICE_SEND_TYPE_INCLUDE && ($arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_AUTHORIZED_BEFORE_PRINT || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_AUTHORIZED)}-->
<!--{assign var=disable_later_payment_print value='false'}-->
<!--{else}-->
<!--{assign var=disable_later_payment_print value='true'}-->
<!--{/if}-->
<!--{* オーソリ変更 表示制御 *}-->
<!--{if $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_AUTHORIZED_BEFORE_PRINT
|| $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_AUTHORIZED}-->
<!--{assign var=disable_later_payment_reduction value='false'}-->
<!--{else}-->
<!--{assign var=disable_later_payment_reduction value='true'}-->
<!--{/if}-->
<!--{* 請求書再発行 表示制御 *}-->
<!--{if $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_CLEAR_REQ_FIN
|| $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_SALES_RESERVE
|| $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_CLEAR}-->
<!--{assign var=disable_later_payment_bill_reissue value='false'}-->
<!--{else}-->
<!--{assign var=disable_later_payment_bill_reissue value='true'}-->
<!--{/if}-->
<!--{* 売上 表示制御 *}-->
<!--{if $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_AUTHORIZED
        || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_SALES_RESERVE}-->
<!--{assign var=disable_later_payment_clear value='false'}-->
<!--{else}-->
<!--{assign var=disable_later_payment_clear value='true'}-->
<!--{/if}-->
<!--{* 取消 表示制御 *}-->
<!--{if $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_AUTHORIZED_BEFORE_PRINT
        || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_AUTHORIZED
        || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_CLEAR_REQ_FIN
        || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_SALES_RESERVE
        || $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_CLEAR}-->
<!--{assign var=disable_later_payment_cancel value='false'}-->
<!--{else}-->
<!--{assign var=disable_later_payment_cancel value='true'}-->
<!--{/if}-->
<script>
$(function(){
    $('input[name=button_later_payment_print]').attr('disabled', <!--{$disable_later_payment_print}-->);
    $('select[name=invoice_send_type]').attr('disabled', <!--{$disable_later_payment_reduction}-->);
    $('input[name=button_later_payment_reduction]').attr('disabled', <!--{$disable_later_payment_reduction}-->);
    $('select[name=client_reason_code]').attr('disabled', <!--{$disable_later_payment_bill_reissue}-->);
    $('input[name=button_later_payment_bill_reissue]').attr('disabled', <!--{$disable_later_payment_bill_reissue}-->);
    $('select[name=carriers_company_code]').attr('disabled', <!--{$disable_later_payment_clear}-->);
    $('input[name=delivery_slip_number]').attr('disabled', <!--{$disable_later_payment_clear}-->);
    $('input[name=button_later_payment_clear]').attr('disabled', <!--{$disable_later_payment_clear}-->);
    $('input[name=button_later_payment_cancel]').attr('disabled', <!--{$disable_later_payment_cancel}-->);
});
</script>
<input type="hidden" name="paygent_type" value="">
<table class="form">
    <tr>
        <th colspan="5">▼<!--{$type}--></th>
    </tr>
    <tr>
        <th><!--{$type}-->ステータス</th>
        <!--{if $paygent_return != "" && $paygent_return.revice_price_error == ""}-->
        <!--{if $paygent_return.return == true}-->
        <!--{assign var=message value=`$arrDispKind[$paygent_return.kind]`に成功しました。}-->
        <!--{else}-->
        <!--{assign var=message value=`$arrDispKind[$paygent_return.kind]`に失敗しました。`$paygent_return.response`}-->
        <!--{/if}-->
        <!--{else}-->
        <!--{assign var=message value=`$arrDispKind[$arrOrderPaygent.memo09]`}-->
        <!--{/if}-->
        <td colspan="4"><!--{$message|default:"(未処理)"}--></td>
    </tr>
    <!--{if strlen($arrOrderPaygent.memo05) !== 0 }-->
    <tr>
        <th><!--{$type}-->エラーメッセージ</td>
        <td colspan="4">
            <span class="attention"><!--{$arrOrderPaygent.memo05}--></span>
        </td>
    </tr>
    <!--{/if}-->
    <tr>
        <th rowspan="6"><!--{$type}-->電文送信</th>
        <td colspan="4">
            <input type="button" name="button_later_payment_print" onclick="fnModeSubmit('paygent_order','paygent_type','later_payment_print'); return false;" value="請求書印字データ出力">
            <a href="?" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_EDIT_URLPATH}-->'); fnModeSubmit('pre_edit', 'order_id', '<!--{$arrOrderPaygent.order_id}-->'); return false;"><span class="icon_edit">画面再表示</span></a>
            <div style="margin-top:5px;">※請求書 印字データ出力後は画面を再表示して下さい。</div>
        </td>
    </tr>
    <tr>
        <td bgcolor="#efefef">オーソリ変更</td>
        <td bgcolor="#efefef">請求書送付方法</td>
        <td>
            <!--{assign var=key value="invoice_send_type"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <!--{html_options name="$key" options=$arrInvoiceSendType selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
        <td>
            <input type="button" name="button_later_payment_reduction" onclick="fnModeSubmit('paygent_order','paygent_type','later_payment_reduction'); return false;" value="オーソリ変更">
        </td>
    </tr>
    <tr>
        <td bgcolor="#efefef">請求書再発行</td>
        <td bgcolor="#efefef">依頼理由</td>
        <td>
            <!--{assign var=key value="client_reason_code"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <!--{if $arrOrderPaygent.memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_AUTHORIZED}-->
            <!--{else}-->
            <!--{/if}-->
            <!--{html_options name="$key" options=$arrClientReasonCode selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
        <td>
            <input type="button" name="button_later_payment_bill_reissue" onclick="fnModeSubmit('paygent_order','paygent_type','later_payment_bill_reissue'); return false;" value="請求書再発行">
        </td>
    </tr>
    <tr>
        <td bgcolor="#efefef" rowspan="2">売上</td>
        <td bgcolor="#efefef">運送会社コード</td>
        <td>
            <!--{assign var=key value="carriers_company_code"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <!--{html_options name="$key" options=$arrCarriersCompanyCode selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor}-->
        </td>
        <td rowspan="2">
            <input type="button" name="button_later_payment_clear" onclick="fnModeSubmit('paygent_order','paygent_type','later_payment_clear'); return false;" value="売上">
        </td>
    </tr>
    <tr>
        <td bgcolor="#efefef">配送伝票番号</td>
        <td>
            <!--{assign var=key value="delivery_slip_number"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" size="15" maxlength="<!--{$arrForm[$key].length}-->">
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <input type="button" name="button_later_payment_cancel" onclick="fnModeSubmit('paygent_order','paygent_type','later_payment_cancel'); return false;" value="取消">
        </td>
    </tr>
</table>
<!--{/if}-->