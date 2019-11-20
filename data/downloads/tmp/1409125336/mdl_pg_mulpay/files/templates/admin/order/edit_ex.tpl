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
<!--{*
mdl_pg_mulpay連携カスタマイズ
*}-->

<script type="text/javascript">
<!--
	function gmoCardSelect(onChange) {
		var index = document.form1.payment_id.selectedIndex;
		if(document.form1.payment_id.options[index].value == "<!--{$gmoCreditPaymentId}-->") {
			document.getElementById("gmoCardText").style.display="";
			document.getElementById("gmoCardSelect").style.display="";
		} else {
			document.getElementById("gmoCardText").style.display = "none";
			document.getElementById("gmoCardText").style.height = "0";
			document.getElementById("gmoCardSelect").style.display = "none";
			document.getElementById("gmoCardSelect").style.height = "0";		
			document.getElementById("gmoChangeText").style.display = "none";
			document.getElementById("gmoChangeText").style.height = "0";			
			document.getElementById("gmoChangeSelect").style.display = "none";
			document.getElementById("gmoChangeSelect").style.height = "0";
		}

		<!--{php}-->
			// EC-CUBE 2.4.2から新規受注入力で「お届け指定」が編集可能になった。
			// http://svn.ec-cube.net/open_trac/ticket/506
			global $major, $minor, $patchlevel;
			list($major, $minor, $patchlevel) = split('\.', ECCUBE_VERSION);
			if ($major >= 2 && $minor >= 4 && $patchlevel >= 2) {
				$this->assign('editable_otodoke', true);
			}

			// ECCUBE_VERSION: <!--{$major}-->.<!--{$minor}-->.<!--{$patchlevel}-->
		<!--{/php}-->
		<!--{if $editable_otodoke}-->
		if (onChange)
                        fnModeSubmit('payment','anchor_key','deliv');
		<!--{/if}-->
	}
	function gmoChangeSelect() {
		var index = document.form1.gmo_credit_next_status.selectedIndex;
		if(document.form1.gmo_credit_next_status.options[index].value == "CHANGE") {
			document.getElementById("gmoChangeText").style.display="";
			document.getElementById("gmoChangeSelect").style.display="";
		} else {		
			document.getElementById("gmoChangeText").style.display = "none";
			document.getElementById("gmoChangeText").style.height = "0";			
			document.getElementById("gmoChangeSelect").style.display = "none";
			document.getElementById("gmoChangeSelect").style.height = "0";
		}
	}	
//-->
</script>

<!--{* onchangeを追加 *}-->
        <tr>
            <th>お支払方法<br /><span class="attention">(お支払方法の変更に伴う手数料の変更は手動にてお願いします。)</span></th>
            <td>
                <!--{assign var=key value="payment_id"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" onchange="gmoCardSelect(true);">
                    <option value="" selected="">選択してください</option>
                    <!--{html_options options=$arrPayment selected=$arrForm[$key].value}-->
                </select>
            </td>
        </tr>

<!--{* クレジット決済機能 *}-->
<tr>
  <th id='gmoCardText'>クレジット決済状況変更<br /><span class="red">(現在状況：<B><!--{$currCardStatus}-->)</B></span></th>
  <td id='gmoCardSelect'>
    <!--{assign var=key value="gmo_credit_next_status"}-->
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" id="cardStsSelect" onchange="gmoChangeSelect()">
      <option value="" selected="">選択してください</option>
      <!--{html_options options=$arrCardStatus}-->
    </select>
  </td>
</tr>	
<tr>
  <th id='gmoChangeText'>金額変更後のクレジット決済状況</th>
  <td id='gmoChangeSelect'>
    <!--{assign var=key value="gmo_credit_change_status"}-->
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
      <option value="" selected="">選択してください</option>
      <option label="仮売上" value="AUTH">仮売上</option>
      <option label="即時売上" value="CAPTURE">即時売上</option>
    </select>
  </td>
</tr>

<script type="text/javascript">
  <!--
      gmoCardSelect(false);gmoChangeSelect();
  //-->
</script>

<!--{* PayPal決済機能 *}-->
<!--{assign var=key value="payment_id"}-->
<!--{if $arrForm[$key].value eq $gmoPaypalPaymentId}-->
<tr id='gmoPaypalCancelButton'>
  <th>PayPal決済状況変更<span class="red">(現在状況：<B><!--{$currPaypalStatusString}-->)</B></span></th>
  <td id='gmoPaypalChangeSelect'>
    <!--{assign var=key value="gmo_paypal_next_status"}-->
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
      <option value="" selected="">選択してください</option>
      <!--{if $currPaypalStatus eq 'CAPTURE'}-->
      <option label="キャンセル" value="CANCEL">キャンセル</option>
      <!--{/if}-->
    </select>
  </td>
</tr>
<!--{/if}-->

<!--{* iD決済機能 *}-->
<!--{assign var=key value="payment_id"}-->
<!--{if $arrForm[$key].value eq $gmoNetidPaymentId}-->
<tr id='gmoNetidChangeButton'>
  <th>iD決済状況変更<span class="red">(現在状況：<B><!--{$currNetidStatusString}-->)</B></span></th>
  <td id='gmoNetidChangeSelect'>
    <!--{assign var=key value="gmo_netid_next_status"}-->
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
      <option value="" selected="">選択してください</option>
      <!--{html_options options=$arrNetidStatus selected=$arrForm[$key].value}-->
    </select>
  </td>
</tr>
<!--{/if}-->

<!--{* auかんたん決済機能 *}-->
<!--{assign var=key value="payment_id"}-->
<!--{if $arrForm[$key].value eq $gmoAuPaymentId && $arrForm.memo05.value ne '03'}-->
<tr id='gmoAuChangeButton'>
  <th>au決済状況変更<span class="red">(現在状況：<B><!--{$currAuStatusString}-->)</B></span></th>
  <td id='gmoAuChangeSelect'>
    <!--{assign var=key value="gmo_au_next_status"}-->
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
      <option value="" selected="">選択してください</option>
      <!--{html_options options=$arrAuStatus selected=$arrForm[$key].value}-->
    </select>
  </td>
</tr>
<!--{/if}-->


<!--{* ドコモケータイ払い機能 *}-->
<!--{assign var=key value="payment_id"}-->
<!--{if $arrForm[$key].value eq $gmoDocomoPaymentId}-->
<tr id='gmoDocomoChangeButton'>
  <th>ドコモケータイ払い状況変更<span class="red">(現在状況：<B><!--{$currDocomoStatusString}-->)</B></span></th>
  <td id='gmoDocomoChangeSelect'>
    <!--{assign var=key value="gmo_docomo_next_status"}-->
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
      <option value="" selected="">選択してください</option>
      <!--{html_options options=$arrDocomoStatus selected=$arrForm[$key].value}-->
    </select>
  </td>
</tr>
<!--{/if}-->
