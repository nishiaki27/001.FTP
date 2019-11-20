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
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_paygent/paygent_order_index.php}-->
<!--{include_php file=$path}-->

<style>
.css-balloon_comment {
    position: relative;
    padding: 0;
    margin-left: 17px;
    margin-top: 10px;
}
.balloon_text {
    width: 10px;
    padding: 0px;
    border: 0px;
    color: #39C;
}
.balloon_text:before {
    content: '!';
    height: 0px;
    width: 0px;
    position: absolute;
    top: 0px;
    left: 0px;
    border: transparent solid;
    border-bottom-color: #F00;
    border-width: 15px 10px 15px 10px;
    color: #ffffff;
    font-family: Verdana;
    font-weight: bold;
    font-size: 12px;
    line-height: 17px;
    text-indent: -2.5px;
    margin-top: -30px;
}
.balloon_comment {
    display: none;
    width: 300px;
    position: absolute;
    padding: 16px;
    border-radius: 5px;
    color: #fff;
    border-style: solid;
    border-width: 1px;
    border-color: #000;
    font-weight: bold;
    text-align: left;
    margin-top: -115px;
    margin-left: -8px;
    background: #787878;
}

.balloon_comment:after, .balloon_comment:before {
    top: 100%;
    left: 5%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

.balloon_comment:after {
    border-color: rgba(136, 183, 213, 0);
    border-style: solid;
    border-top-color: #787878;
    border-top-width: 1px;
    border-width: 7px;
    margin-left: -7px;
}
.balloon_comment:before {
    border-color: rgba(194, 225, 245, 0);
    border-style: solid;
    border-top-color: #787878;
    border-top-width: 1px;
    border-width: 8px;
    margin-left: -8px;
}

.balloon_text:hover + .balloon_comment {
    display: block;
}
</style>

<!--{if $arrPaygentConfig.settlement_division == $smarty.const.SETTLEMENT_MODULE}-->
<script type="text/javascript">
<!--
    function fnPaygentChecked(type) {
        var fm = document.form1;
        var check_type = type + '[]';
        var max = fm.elements.length;

        for (var i = 0; i < max; i++) {
            if (fm.elements[i].name == check_type) {
                if (fm[type].checked === true) {
                    fm.elements[i].checked = true;
                } else {
                    fm.elements[i].checked = false;
                }
            }
        }
    }

    function fnPaygentSubmit(type) {
        var fm = document.form1;
        var check_type = type + '[]';
        var max = fm.elements.length;

        for (var i = 0; i < max; i++) {
            if (fm.elements[i].name == check_type && fm.elements[i].checked === true) {
                break;
            }
            if (i == max - 1) {
                alert('1件も選択されていません。');
                return false;
            }
        }

        switch (type) {
        case 'paygent_commit':
            var submit_flg = confirm('一括売上処理を実行します。よろしいですか？');
            break;
        case 'paygent_cancel':
            var submit_flg = confirm('一括取消処理を実行します。よろしいですか？');
            break;
        }
        if (submit_flg === true) {
            fm.action = '<!--{$smarty.const.USER_URL|cat:$smarty.const.MODULE_DIR|cat:$smarty.const.MDL_PAYGENT_CODE|cat:"/paygent_order_commit.php"}-->';
            fm['mode'].value = type;
            fm['<!--{$smarty.const.TRANSACTION_ID_NAME}-->'].value = '<!--{$transactionid}-->';
            window.name='main';
            winSubmit('', 'form1', type, 500, 400);
            fm.submit();
            fm.action = '?';
            fm.target = '';
            fm['mode'].value = '';
            return false;
        }
    }
//-->
</script>
        <colgroup width="8%">
        <colgroup width="7%">
        <colgroup width="10%">
        <colgroup width="8%">
        <colgroup width="8%">
        <colgroup width="8%">
        <colgroup width="8%">
        <colgroup width="9%">
        <colgroup width="7%">
        <colgroup width="7%">
        <colgroup width="7%">
        <colgroup width="3%">
        <colgroup width="7%">
        <colgroup width="3%">
    <tr>
        <th>受注日</th>
        <th>注文番号</th>
        <th>顧客名</th>
        <th>支払方法</th>
        <th>購入金額(円)</th>
        <th>発送日</th>
        <th>対応状況</th>
        <th>ペイジェント<br>状況</th>
        <th>一括売上<br><input type="button" value="実行" onclick="return fnPaygentSubmit('paygent_commit');"><br><input type="checkbox" name="paygent_commit" onclick="fnPaygentChecked('paygent_commit');"></th>
        <th>一括取消<br><input type="button" value="実行" onclick="return fnPaygentSubmit('paygent_cancel');"><br><input type="checkbox" name="paygent_cancel" onclick="fnPaygentChecked('paygent_cancel');"></th>
        <th><label for="pdf_check">帳票</label><br><input type="checkbox" name="pdf_check" id="pdf_check" onclick="fnAllCheck(this, 'input[name=pdf_order_id[]]')" /></th>
        <th>編集</th>
        <!--{if strpos($smarty.const.ECCUBE_VERSION, '2.11.') === FALSE}-->
            <th>メール<br><input type="checkbox" name="mail_check" id="mail_check" onclick="fnAllCheck(this, 'input[name=mail_order_id[]]')" /></th>
        <!--{else}-->
            <th>メール</th>
        <!--{/if}-->
        <th>削除</th>
    </tr>

    <!--{section name=cnt loop=$arrResults}-->
    <!--{assign var=status value="`$arrResults[cnt].status`"}-->
    <tr style="background:<!--{$arrORDERSTATUS_COLOR[$status]}-->;">
        <td class="center"><!--{$arrResults[cnt].create_date|sfDispDBDate}--></td>
        <td class="center"><!--{$arrResults[cnt].order_id}--></td>
        <td><!--{$arrResults[cnt].order_name01|h}--> <!--{$arrResults[cnt].order_name02|h}--></td>
        <!--{assign var=payment_id value="`$arrResults[cnt].payment_id`"}-->
        <td class="center"><!--{$arrPayments[$payment_id]}--></td>
        <td class="right"><!--{$arrResults[cnt].total|number_format}--></td>
        <td class="center"><!--{$arrResults[cnt].commit_date|sfDispDBDate|default:"未発送"}--></td>
        <td class="center"><!--{$arrORDERSTATUS[$status]}--></td>
        <!--{assign var=memo09 value=`$arrResults[cnt].memo09`}-->
        <td class="center"><!--{$arrDispKind[$memo09]}--></td>
        <td class="center">
        	<!--{* クレジット決済売上表示条件 *}-->
            <!--{if ($arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE &&
                    $arrResults[cnt].memo08 == $smarty.const.PAYGENT_CREDIT &&
                    $arrResults[cnt].memo07 != '33' && (strlen($arrResults[cnt].memo09) == 0 || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_CREDIT) &&
                    strlen($arrResults[cnt].memo06) != 0)}-->
            <input type="checkbox" name="paygent_commit[]" value="card_commit,<!--{$arrResults[cnt].order_id}-->">売上
            <!--{* 携帯キャリア決済売上表示条件 *}-->
            <!--{elseif $arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE
                && (
                ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_CAREER_D && ($arrResults[cnt].memo07 == '20' || $arrResults[cnt].memo07 == '21') && strlen($arrResults[cnt].memo09) == 0)
                || ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_CAREER_A && $arrResults[cnt].memo07 == '20' && strlen($arrResults[cnt].memo09) == 0)
                || ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_CAREER_S && ($arrResults[cnt].memo07 == '20' || $arrResults[cnt].memo07 == '21') && strlen($arrResults[cnt].memo09) == 0)
                ) }-->
            <input type="checkbox" name="paygent_commit[]" value="career_commit,<!--{$arrResults[cnt].order_id}-->">売上
            <!--{* Yahoo!ウォレット決済売上表示条件 *}-->
            <!--{elseif $arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE &&
                    ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_YAHOOWALLET && ($arrResults[cnt].memo07 == '20')
                    	&& (strlen($arrResults[cnt].memo09) == 0 || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_YAHOOWALLET_COMMIT_REVICE))}-->
            <input type="checkbox" name="paygent_commit[]" value="yahoowallet_commit,<!--{$arrResults[cnt].order_id}-->">売上
            <!--{* Paidy売上表示条件 *}-->
            <!--{elseif $arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE &&
                    $arrResults[cnt].memo08 == $smarty.const.PAYGENT_PAIDY}-->
                <!--{assign var="memo02" value=$arrResults[cnt].memo02|unserialize}-->
                <!--{if $memo02.ecOrderData.payment_total_check_status == $smarty.const.PAYMENT_AMOUNT_UNMATCH}-->
                    <div class="css-balloon_comment">
                        <p class="balloon_text"></p>
                        <p class="balloon_comment">Paidyへ送信された購入金額と受注情報の購入金額が一致していません。<br/>ペイジェントオンラインで売上処理をしている場合は、返金処理をお願い致します。</p>
                    </div>
                <!--{/if}-->
                <!--{if $arrResults[cnt].memo09 == $smarty.const.PAYGENT_PAIDY_AUTHORIZED}-->
                    <input type="checkbox" name="paygent_commit[]" value="paidy_commit,<!--{$arrResults[cnt].order_id}-->">売上
                <!--{/if}-->
            <!--{/if}-->
        </td>
        <td class="center">
        	<!--{* クレジット決済取消表示条件 *}-->
            <!--{if $arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE &&
                    $arrResults[cnt].memo08 == $smarty.const.PAYGENT_CREDIT &&
                    strlen($arrResults[cnt].memo06) != 0 &&
                    (($arrResults[cnt].memo07 != '33' && (strlen($arrResults[cnt].memo09) == 0 || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_CREDIT)) ||
                     ($arrResults[cnt].memo07 != '41' && $arrResults[cnt].memo09 == $smarty.const.PAYGENT_CARD_COMMIT)) || ($arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE &&
                    $arrResults[cnt].memo08 == $smarty.const.PAYGENT_CREDIT && $arrResults[cnt].memo09 == '029') || ($arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE &&
                    $arrResults[cnt].memo08 == $smarty.const.PAYGENT_CREDIT && $arrResults[cnt].memo09 == '022')}-->
            <input type="checkbox" name="paygent_cancel[]" value="<!--{if strlen($arrResults[cnt].memo09) == 0 || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_CREDIT}-->auth_cancel<!--{else}-->card_commit_cancel<!--{/if}-->,<!--{$arrResults[cnt].order_id}-->">取消
            <!--{* 携帯キャリア決済取消表示条件 *}-->
            <!--{elseif $arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE
                && (
                ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_CAREER_D && ($arrResults[cnt].memo07 == '20' || $arrResults[cnt].memo07 == '21') && strlen($arrResults[cnt].memo09) == 0)
                || ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_CAREER_D && $arrResults[cnt].memo07 == '44' && ($arrResults[cnt].memo09 == $smarty.const.PAYGENT_CAREER_COMMIT || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_CAREER_COMMIT_REVICE))
                || ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_CAREER_A && $arrResults[cnt].memo07 == '20' && (strlen($arrResults[cnt].memo09) == 0 || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_CAREER_COMMIT || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_CAREER_COMMIT_REVICE))
                || ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_CAREER_A && $arrResults[cnt].memo07 == '40' && ($arrResults[cnt].memo09 == $smarty.const.PAYGENT_CAREER_COMMIT || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_CAREER_COMMIT_REVICE))
                || ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_CAREER_S && ($arrResults[cnt].memo07 == '20' || $arrResults[cnt].memo07 == '21' || $arrResults[cnt].memo07 == '40') && (strlen($arrResults[cnt].memo09) == 0 || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_CAREER_COMMIT))
                ) }-->
            <input type="checkbox" name="paygent_cancel[]" value="career_commit_cancel,<!--{$arrResults[cnt].order_id}-->">取消
            <!--{* 電子マネー決済取消表示条件 *}-->
            <!--{elseif $arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE &&
                    (($arrResults[cnt].memo08 == $smarty.const.PAYGENT_EMONEY_W && $arrResults[cnt].memo07 == '40' && (strlen($arrResults[cnt].memo09) == 0) || $arrResults[cnt].memo09 == '153'))}-->
            <input type="checkbox" name="paygent_cancel[]" value="emoney_cancel,<!--{$arrResults[cnt].order_id}-->">取消
            <!--{* Yahoo!ウォレット決済取消表示条件 *}-->
            <!--{elseif $arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE &&
                    (( $arrResults[cnt].memo08 == $smarty.const.PAYGENT_YAHOOWALLET && $arrResults[cnt].memo07 == '20' && (strlen($arrResults[cnt].memo09) == 0 || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_YAHOOWALLET_COMMIT || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_YAHOOWALLET_COMMIT_REVICE))
                    || ($arrResults[cnt].memo08 == $smarty.const.PAYGENT_YAHOOWALLET && $arrResults[cnt].memo07 == '40' && $arrResults[cnt].memo09 == $smarty.const.PAYGENT_YAHOOWALLET_COMMIT))}-->
            <input type="checkbox" name="paygent_cancel[]" value="yahoowallet_cancel,<!--{$arrResults[cnt].order_id}-->">取消
            <!--{elseif $arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE
                && $arrResults[cnt].memo08 == $smarty.const.PAYGENT_LATER_PAYMENT
                && ($arrResults[cnt].memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_AUTHORIZED_BEFORE_PRINT
                    || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_AUTHORIZED
                    || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_CLEAR_REQ_FIN
                    || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_SALES_RESERVE
                    || $arrResults[cnt].memo09 == $smarty.const.PAYGENT_LATER_PAYMENT_ST_CLEAR)}-->
            <input type="checkbox" name="paygent_cancel[]" value="later_payment_cancel,<!--{$arrResults[cnt].order_id}-->">取消
            <!--{* Paidy売上取消表示条件 *}-->
            <!--{elseif $arrResults[cnt].memo01 == $smarty.const.MDL_PAYGENT_CODE &&
                    ($arrResults[cnt].memo09 == $smarty.const.PAYGENT_PAIDY_AUTHORIZED) ||
                    ($arrResults[cnt].memo09 == $smarty.const.PAYGENT_PAIDY_COMMIT) }-->
            <input type="checkbox" name="paygent_cancel[]" value="paidy_cancel,<!--{$arrResults[cnt].order_id}-->">取消
            <!--{/if}-->
        </td>
        <td class="center">
            <input type="checkbox" name="pdf_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="pdf_order_id_<!--{$arrResults[cnt].order_id}-->"/><br><label for="pdf_order_id_<!--{$arrResults[cnt].order_id}-->">一括出力</label><br>
            <a href="./" onClick="win02('pdf.php?order_id=<!--{$arrResults[cnt].order_id}-->','pdf_input','620','650'); return false;"><span class="icon_class">個別出力</span></a>
        </td>
        <td class="center"><a href="?" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_EDIT_URLPATH}-->'); fnModeSubmit('pre_edit', 'order_id', '<!--{$arrResults[cnt].order_id}-->'); return false;"><span class="icon_edit">編集</span></a></td>
        <td class="center">
            <!--{if $arrResults[cnt].order_email|strlen >= 1}-->
                <!--{if strpos($smarty.const.ECCUBE_VERSION, '2.11.') === FALSE}-->
                <input type="checkbox" name="mail_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="mail_order_id_<!--{$arrResults[cnt].order_id}-->"/><br><label for="mail_order_id_<!--{$arrResults[cnt].order_id}-->">一括通知</label><br>
                <!--{/if}-->
                <a href="?" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_MAIL_URLPATH}-->'); fnModeSubmit('pre_edit', 'order_id', '<!--{$arrResults[cnt].order_id}-->'); return false;"><span class="icon_mail">個別通知</span></a>
            <!--{/if}-->
        </td>
        <td class="center"><a href="?" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_URLPATH}-->'); fnModeSubmit('delete_order', 'order_id', <!--{$arrResults[cnt].order_id}-->); return false;"><span class="icon_delete">削除</span></a></td>
    </tr>
    <!--{/section}-->
<!--{else}-->
        <colgroup width="10%">
        <colgroup width="7%">
        <colgroup width="15%">
        <colgroup width="10%">
        <colgroup width="10%">
        <colgroup width="10%">
        <colgroup width="8%">
        <colgroup width="10%">
        <colgroup width="5%">
        <colgroup width="10%">
        <colgroup width="5%">
    <tr>
        <th>受注日</th>
        <th>注文番号</th>
        <th>顧客名</th>
        <th>支払方法</th>
        <th>購入金額(円)</th>
        <th>全商品発送日</th>
        <th>対応状況</th>
        <th><label for="pdf_check">帳票</label> <input type="checkbox" name="pdf_check" id="pdf_check" onclick="fnAllCheck(this, 'input[name=pdf_order_id[]]')" /></th>
        <th>編集</th>
        <!--{if strpos($smarty.const.ECCUBE_VERSION, '2.11.') === FALSE}-->
        <th>メール <input type="checkbox" name="mail_check" id="mail_check" onclick="fnAllCheck(this, 'input[name=mail_order_id[]]')" /></th>
        <!--{else}-->
        <th>メール</th>
        <!--{/if}-->
        <th>削除</th>
    </tr>

    <!--{section name=cnt loop=$arrResults}-->
    <!--{assign var=status value="`$arrResults[cnt].status`"}-->
    <tr style="background:<!--{$arrORDERSTATUS_COLOR[$status]}-->;">
        <td class="center"><!--{$arrResults[cnt].create_date|sfDispDBDate}--></td>
        <td class="center"><!--{$arrResults[cnt].order_id}--></td>
        <td><!--{$arrResults[cnt].order_name01|h}--> <!--{$arrResults[cnt].order_name02|h}--></td>
        <!--{assign var=payment_id value="`$arrResults[cnt].payment_id`"}-->
        <td class="center"><!--{$arrPayments[$payment_id]}--></td>
        <td class="right"><!--{$arrResults[cnt].total|number_format}--></td>
        <td class="center"><!--{$arrResults[cnt].commit_date|sfDispDBDate|default:"未発送"}--></td>
        <td class="center"><!--{$arrORDERSTATUS[$status]}--></td>
        <td class="center">
            <input type="checkbox" name="pdf_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="pdf_order_id_<!--{$arrResults[cnt].order_id}-->"/><label for="pdf_order_id_<!--{$arrResults[cnt].order_id}-->">一括出力</label><br>
            <a href="./" onClick="win02('pdf.php?order_id=<!--{$arrResults[cnt].order_id}-->','pdf_input','620','650'); return false;"><span class="icon_class">個別出力</span></a>
        </td>
        <td class="center"><a href="?" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_EDIT_URLPATH}-->'); fnModeSubmit('pre_edit', 'order_id', '<!--{$arrResults[cnt].order_id}-->'); return false;"><span class="icon_edit">編集</span></a></td>
        <td class="center">
            <!--{if $arrResults[cnt].order_email|strlen >= 1}-->
                <!--{if strpos($smarty.const.ECCUBE_VERSION, '2.11.') === FALSE}-->
                <input type="checkbox" name="mail_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="mail_order_id_<!--{$arrResults[cnt].order_id}-->"/><label for="mail_order_id_<!--{$arrResults[cnt].order_id}-->">一括通知</label><br>
                <!--{/if}-->
                <a href="?" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_MAIL_URLPATH}-->'); fnModeSubmit('pre_edit', 'order_id', '<!--{$arrResults[cnt].order_id}-->'); return false;"><span class="icon_mail">個別通知</span></a>
            <!--{/if}-->
        </td>
        <td class="center"><a href="?" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_URLPATH}-->'); fnModeSubmit('delete_order', 'order_id', <!--{$arrResults[cnt].order_id}-->); return false;"><span class="icon_delete">削除</span></a></td>
    </tr>
    <!--{/section}-->
<!--{/if}-->