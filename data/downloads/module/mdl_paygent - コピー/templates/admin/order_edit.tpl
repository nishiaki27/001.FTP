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
<script type="text/javascript">
<!--
    function fnEdit(customer_id) {
        document.form1.action = '<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->customer/edit.php';
        document.form1.mode.value = "edit"
        document.form1['customer_id'].value = customer_id;
        document.form1.submit();
        return false;
    }

    function fnCopyFromOrderData() {
        df = document.form1;

        // お届け先名のinputタグのnameを取得
        var shipping_data = $('input[name^=shipping_name01]').attr('name');
        var shipping_slt  = shipping_data.split("shipping_name01");

        var shipping_key = "[0]";
        if(shipping_slt.length > 1) {
            shipping_key = shipping_slt[1];
        }

        df['shipping_name01'+shipping_key].value = df.order_name01.value;
        df['shipping_name02'+shipping_key].value = df.order_name02.value;
        df['shipping_kana01'+shipping_key].value = df.order_kana01.value;
        df['shipping_kana02'+shipping_key].value = df.order_kana02.value;
        df['shipping_zip01'+shipping_key].value = df.order_zip01.value;
        df['shipping_zip02'+shipping_key].value = df.order_zip02.value;
        df['shipping_tel01'+shipping_key].value = df.order_tel01.value;
        df['shipping_tel02'+shipping_key].value = df.order_tel02.value;
        df['shipping_tel03'+shipping_key].value = df.order_tel03.value;
        df['shipping_pref[0]'].value = df.order_pref.value;
        if (df.order_fax01 != undefined && df['shipping_fax01'+shipping_key] != undefined) {
            df['shipping_fax01'+shipping_key].value = df.order_fax01.value;
            df['shipping_fax02'+shipping_key].value = df.order_fax02.value;
            df['shipping_fax03'+shipping_key].value = df.order_fax03.value;
        }
        df['shipping_addr01'+shipping_key].value = df.order_addr01.value;
        df['shipping_addr02'+shipping_key].value = df.order_addr02.value;
    }

    function fnFormConfirm() {
        if (fnConfirm()) {
            document.form1.submit();
        }
    }

    function fnMultiple() {
        win03('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/multiple.php', 'multiple', '600', '500');
        document.form1.anchor_key.value = "shipping";
        document.form1.mode.value = "multiple";
        document.form1.submit();
        return false;
    }

    function fnAppendShipping() {
        document.form1.anchor_key.value = "shipping";
        document.form1.mode.value = "append_shipping";
        document.form1.submit();
        return false;
    }

//-->
</script>
<form name="form1" id="form1" method="post" action="?">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="<!--{$tpl_mode|default:"edit"|h}-->" />
<input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|h}-->" />
<input type="hidden" name="edit_customer_id" value="" />
<input type="hidden" name="anchor_key" value="" />
<input type="hidden" id="add_product_id" name="add_product_id" value="" />
<input type="hidden" id="add_product_class_id" name="add_product_class_id" value="" />
<input type="hidden" id="edit_product_id" name="edit_product_id" value="" />
<input type="hidden" id="edit_product_class_id" name="edit_product_class_id" value="" />
<input type="hidden" id="no" name="no" value="" />
<input type="hidden" id="delete_no" name="delete_no" value="" />
<!--{foreach key=key item=item from=$arrSearchHidden}-->
    <!--{if is_array($item)}-->
        <!--{foreach item=c_item from=$item}-->
        <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
        <!--{/foreach}-->
    <!--{else}-->
        <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
    <!--{/if}-->
<!--{/foreach}-->

<div id="order" class="contents-main">

    <!--{* ペイジェントモジュール連携用 *}-->
    <!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_paygent/templates/admin/paygent_order.tpl}-->
    <!--{if file_exists($path)}-->
        <!--{include file=$path}-->
    <!--{/if}-->

    <!--▼お客様情報ここから-->
    <table class="form">
        <!--{if $tpl_mode != 'add'}-->
        <tr>
            <th>帳票出力</th>
            <td><a class="btn-normal" href="javascript:;" onclick="win02('pdf.php?order_id=<!--{$arrForm.order_id.value|h}-->','pdf','615','650'); return false;">帳票出力</a>
                <a class="btn-normal" href="javascript:;" onclick="win02('Faxpdf.php?order_id=<!--{$arrForm.order_id.value|h}-->&pay_date=<!--{$arrForm.payment_date.value|default:""}-->&pay_id=<!--{$arrForm.payment_id.value|h}-->','pdf','625','660'); return false;">FAX注文書出力</a>
                <a class="btn-normal" href="javascript:;" onclick="win02('SouhuFaxpdf.php?order_id=<!--{$arrForm.order_id.value|h}-->&pay_date=<!--{$arrForm.payment_date.value|default:""}-->','pdf','615','650'); return false;">送付状出力</a>
            </td>
        </tr>
        <!--{/if}-->
        <tr>
            <th>注文番号</th>
            <td><!--{$arrForm.order_id.value|h}--></td>
        </tr>
        <tr>
            <th>受注日</th>
            <td><!--{$arrForm.create_date.value|sfDispDBDate|h}--><input type="hidden" name="create_date" value="<!--{$arrForm.create_date.value|h}-->" /></td>
        </tr>
        <tr>
            <th>対応状況</th>
            <td>
                <!--{assign var=key value="status"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrORDERSTATUS selected=$arrForm[$key].value}-->
                </select><br />
                <!--{if $smarty.get.mode != 'add'}-->
                    <span class="attention">※ <!--{$arrORDERSTATUS[$smarty.const.ORDER_CANCEL]}-->に変更時には、在庫数を手動で戻してください。</span>
                <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>入金日</th>
            <td><!--{$arrForm.payment_date.value|sfDispDBDate|default:"未入金"|h}--></td>
        </tr>
        <tr>
            <th>発送日</th>
            <td><!--{$arrForm.commit_date.value|sfDispDBDate|default:"未発送"|h}--></td>
        </tr>
    </table>

    <h2>お客様情報
        <!--{if $tpl_mode == 'add'}-->
            <a class="btn-normal" href="javascript:;" name="address_input" onclick="fnOpenWindow('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->customer/search_customer.php','search','600','650'); return false;">顧客検索</a>
        <!--{/if}-->
    </h2>
    <table class="form">
        <tr>
            <th>顧客ID</th>
            <td>
                <!--{if $arrForm.customer_id.value > 0}-->
                    <!--{$arrForm.customer_id.value|h}-->
                    <input type="hidden" name="customer_id" value="<!--{$arrForm.customer_id.value|h}-->" />
                <!--{else}-->
                    (非会員)
                <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>顧客名</th>
            <td>
                <!--{assign var=key1 value="order_name01"}-->
                <!--{assign var=key2 value="order_name02"}-->
                <span class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></span>
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="15" class="box15" />
                <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="15" class="box15" />
            </td>
        </tr>
        <tr>
            <th>顧客名(カナ)</th>
            <td>
                <!--{assign var=key1 value="order_kana01"}-->
                <!--{assign var=key2 value="order_kana02"}-->
                <span class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></span>
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="15" class="box15" />
                <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="15" class="box15" />
            </td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td>
                <!--{assign var=key1 value="order_email"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="30" class="box30" />
            </td>
        </tr>
        <tr>
            <th>TEL</th>
            <td>
                <!--{assign var=key1 value="order_tel01"}-->
                <!--{assign var=key2 value="order_tel02"}-->
                <!--{assign var=key3 value="order_tel03"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <span class="attention"><!--{$arrErr[$key2]}--></span>
                <span class="attention"><!--{$arrErr[$key3]}--></span>
                <input type="text" name="<!--{$arrForm[$key1].keyname}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" /> -
                <input type="text" name="<!--{$arrForm[$key2].keyname}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" /> -
                <input type="text" name="<!--{$arrForm[$key3].keyname}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" size="6" class="box6" />
            </td>
        </tr>
        <tr>
            <th>FAX</th>
            <td>
                <!--{assign var=key1 value="order_fax01"}-->
                <!--{assign var=key2 value="order_fax02"}-->
                <!--{assign var=key3 value="order_fax03"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <span class="attention"><!--{$arrErr[$key2]}--></span>
                <span class="attention"><!--{$arrErr[$key3]}--></span>
                <input type="text" name="<!--{$arrForm[$key1].keyname}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" /> -
                <input type="text" name="<!--{$arrForm[$key2].keyname}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" /> -
                <input type="text" name="<!--{$arrForm[$key3].keyname}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" size="6" class="box6" />
            </td>
        </tr>
        <tr>
            <th>住所</th>
            <td>
                <!--{assign var=key1 value="order_zip01"}-->
                <!--{assign var=key2 value="order_zip02"}-->
                <span class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></span>
                〒
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" />
                -
                <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" />
                <a class="btn-normal" href="javascript:;" name="address_input" onclick="fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', 'order_zip01', 'order_zip02', 'order_pref', 'order_addr01'); return false;">住所入力</a><br />
                <!--{assign var=key value="order_pref"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <select class="top" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                    <option value="" selected="">都道府県を選択</option>
                    <!--{html_options options=$arrPref selected=$arrForm[$key].value}-->
                </select><br />
                <!--{assign var=key value="order_addr01"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" size="60" class="box60 top" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" /><br />
                <!--{assign var=key value="order_addr02"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" size="60" class="box60" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
            </td>
        </tr>
        <tr>
            <th>備考</th>
            <td><!--{$arrForm.message.value|h|nl2br}--></td>
        </tr>
        <tr>
            <th>現在ポイント</th>
            <td>
                <!--{if $arrForm.customer_id > 0}-->
                    <!--{$arrForm.customer_point.value|number_format}-->
                    pt
                <!--{else}-->
                    (非会員)
            <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>アクセス端末</th>
            <td><!--{$arrDeviceType[$arrForm.device_type_id.value]|h}--></td>
        </tr>

    </table>
    <!--▲お客様情報ここまで-->

    <!--▼受注商品情報ここから-->
    <a name="order_products"></a>
    <h2 id="order_products">
        受注商品情報
        <a class="btn-normal" href="javascript:;" name="recalculate" onclick="fnModeSubmit('recalculate','anchor_key','order_products');">計算結果の確認</a>
        <a class="btn-normal" href="javascript:;" name="add_product" onclick="win03('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/product_select.php?order_id=<!--{$arrForm.order_id.value|h}-->', 'search', '615', '500'); return false;">商品の追加</a>
    </h2>

    <!--{if $arrErr.product_id}-->
        <span class="attention">※ 商品が選択されていません。</span>
    <!--{/if}-->

    <table class="list" id="order-edit-products">
        <tr>
            <th class="id">商品コード</th>
            <th class="name">商品名/規格1/規格2</th>
            <th class="price">単価</th>
            <th class="qty">数量</th>
            <!--2019/08/06 変更-->
            <!--{assign var=tax_change value=$arrForm.memo10.value|default:8}-->
             <input type="hidden" id="tax_change_memo" name="memo10" value="<!--{$tax_change}-->">
             
            <!--{if $arrForm.memo10.value ==5}-->
                <th class="price">税込み価格 ( 5% ) </th>
            <!--{else}-->
	            <!--{if $arrForm.memo10.value ==8 || $arrForm.memo10.value ==""}-->
	                <!--{assign var=tax_1 value="checked"}-->
	            <!--{else}-->
	                <!--{assign var=tax_2 value="checked"}-->
	            <!--{/if}-->
                <th class="price">税込み価格 (<input type="radio" value="8" name="tax_check" id="tax_1" onclick="tax_change(this);" <!--{$tax_1}-->>8% <input type="radio" value="10" name="tax_check" id="tax_2" onclick="tax_change(this);"<!--{$tax_2}-->>10%) </th>
            <!--{/if}-->
<script type="text/javascript">
    function tax_change(obj){
        var memo=document.getElementById('tax_change_memo').value;

        if(memo!=obj.value){
            var index_max=document.getElementById('index_max').value;
            var price=document.getElementById('price_0').value;

            var now_tax = 1+(memo/100);
            var change_tax=1+(obj.value/100);
            for(var i=0;i<=index_max;i++){
                price=document.getElementById('price_'+i).value;
                price=price/now_tax;
                document.getElementById('price_'+i).value=Math.round(price*change_tax);
            }
            document.getElementById('tax_change_memo').value=obj.value;
            document.form1['mode'].value = 'recalculate';
            document.form1.submit();
            alert("消費税を"+obj.value+"％に変更しました。");
            document.getElement.ById(obj.id).checked=true;
        }
        return false;
    }
</script>

            <th class="price">小計</th>
        </tr>
        <!--{section name=cnt loop=$arrForm.quantity.value}-->
            <!--{assign var=product_index value="`$smarty.section.cnt.index`"}-->
            <!--{assign var=product_id value=$arrForm.product_id.value[$product_index]}-->
            <tr>
                <td>
                <!--{if $arrForm.product_id.value[$product_index] >900000}-->
                    <input type="text" name="product_code[<!--{$product_index}-->]" value="<!--{$arrForm.product_code.value[$product_index]|h}-->" id="product_code_<!--{$product_index}-->" />
                <!--{else}-->
                    <!--{$arrForm.product_code.value[$product_index]|h}-->
                    <input type="hidden" name="product_code[<!--{$product_index}-->]" value="<!--{$arrForm.product_code.value[$product_index]|h}-->" id="product_code_<!--{$product_index}-->" />
                <!--{/if}-->
                <!--{if $arrForm.attribute.value[$product_index] <>"" }-->
                      <br><br>パネル：<!--{$arrForm.attribute.value[$product_index]}-->
                <!--{/if}-->
                     <input type="hidden" name="attribute[<!--{$product_index}-->]" value="<!--{$arrForm.attribute.value[$product_index]|h}-->" id="attribute_<!--{$product_index}-->" />
                <!--{if $arrForm.attribute2.value[$product_index] <>"" }-->
                     <br><br>電源タイプ：<!--{$arrForm.attribute2.value[$product_index]}-->
                <!--{/if}-->
                     <input type="hidden" name="attribute2[<!--{$product_index}-->]" value="<!--{$arrForm.attribute2.value[$product_index]|h}-->" id="attribute2_<!--{$product_index}-->" />
                </td>
                <td>
                <!--{if $arrForm.product_id.value[$product_index] >900000}-->
                    <input type="text" name="product_name[<!--{$product_index}-->]" value="<!--{$arrForm.product_name.value[$product_index]|h}-->" id="product_name_<!--{$product_index}-->" />
                <!--{else}-->
                    <!--{$arrForm.product_name.value[$product_index]|h}-->
                    <input type="hidden" name="product_name[<!--{$product_index}-->]" value="<!--{$arrForm.product_name.value[$product_index]|h}-->" id="product_name_<!--{$product_index}-->" />
                <!--{/if}-->

                    <!--↓2014/02/13　変更↓-->
                    <!--{if $arrForm.classcategory_name1.value[$product_index]}-->/<!--{$arrForm.classcategory_name1.value[$product_index]}--><!--{/if}-->
                    <!--{if $arrForm.classcategory_name2.value[$product_index]}-->/<!--{$arrForm.classcategory_name2.value[$product_index]}--><!--{/if}--><br />
                    <input type="hidden" name="classcategory_name1[<!--{$product_index}-->]" value="<!--{$arrForm.classcategory_name1.value[$product_index]|h}-->" id="classcategory_name1_<!--{$product_index}-->" />
                    <input type="hidden" name="classcategory_name2[<!--{$product_index}-->]" value="<!--{$arrForm.classcategory_name2.value[$product_index]|h}-->" id="classcategory_name2_<!--{$product_index}-->" />
                    <br />
                    <a class="btn-normal" href="javascript:;" name="change" onclick="win03('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/product_select.php?no=<!--{$product_index}-->&amp;order_id=<!--{$arrForm.order_id.value|h}-->', 'search', '615', '500'); return false;">変更</a>
                <!--{if count($arrForm.quantity.value) > 1}-->
                    <a class="btn-normal" href="javascript:;" name="delete" onclick="fnSetFormVal('form1', 'delete_no', <!--{$product_index}-->); fnModeSubmit('delete_product','anchor_key','order_products'); return false;">削除</a>
                <!--{/if}-->
                    <input type="hidden" name="product_type_id[<!--{$product_index}-->]" value="<!--{$arrForm.product_type_id.value[$product_index]|h}-->" id="product_type_id_<!--{$product_index}-->" />
                    <input type="hidden" name="product_id[<!--{$product_index}-->]" value="<!--{$arrForm.product_id.value[$product_index]|h}-->" id="product_id_<!--{$product_index}-->" />
                    <input type="hidden" name="product_class_id[<!--{$product_index}-->]" value="<!--{$arrForm.product_class_id.value[$product_index]|h}-->" id="product_class_id_<!--{$product_index}-->" />
                    <input type="hidden" name="point_rate[<!--{$product_index}-->]" value="<!--{$arrForm.point_rate.value[$product_index]|h}-->" id="point_rate_<!--{$product_index}-->" />
                </td>
                <td align="center">
                        <!--{assign var=key value="price"}-->
                        <span class="attention"><!--{$arrErr[$key][$product_index]}--></span>
                        <input type="text" name="<!--{$key}-->[<!--{$product_index}-->]" value="<!--{$arrForm[$key].value[$product_index]|h}-->" size="6" class="box6" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key][$product_index]|sfGetErrorColor}-->" id="<!--{$key}-->_<!--{$product_index}-->"/> 円
                </td>
                <td align="center">
                        <!--{assign var=key value="quantity"}-->
                        <span class="attention"><!--{$arrErr[$key][$product_index]}--></span>
                        <input type="text" name="<!--{$key}-->[<!--{$product_index}-->]" value="<!--{$arrForm[$key].value[$product_index]|h}-->" size="3" class="box3" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key][$product_index]|sfGetErrorColor}-->" id="<!--{$key}-->_<!--{$product_index}-->" />
                </td>
                <!--{assign var=price value=`$arrForm.price.value[$product_index]`}-->
                <!--{assign var=quantity value=`$arrForm.quantity.value[$product_index]`}-->
                <td class="right"><!--{$price|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule|number_format}--> 円
                <!--*<input type="text" id="tax_set_price_<!--{$product_index}-->" value="<!--{$price|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule}-->"></td>*-->
                <td class="right"><!--{$price|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule|sfMultiply:$quantity|number_format}-->円 </td>
            </tr>
        <!--{/section}-->
            <input type="hidden" id="index_max" value="<!--{$product_index}-->"><!-- ←2014/02/21 追加-->
            <!--↓消費税追加↓-->
        <!--{assign var=subtotal value=`$arrForm.subtotal.value`}-->
            <tr>
                <th colspan="5" class="column right">消費税( <!--{$tax_change}-->% )</th>
            <!--{if $arrForm.memo10.value ==5}-->
              <td class="right">(　<!--{$subtotal-$subtotal/1.05|number_format}-->円)</td>
            <!--{else}-->
	            <!--{if $arrForm.memo10.value ==8 || $arrForm.memo10.value ==""}-->
	                <td class="right">(　<!--{$subtotal-$subtotal/1.08|number_format}-->円)</td>
	            <!--{else}-->
	                <td class="right">(　<!--{$subtotal-$subtotal/1.10|number_format}-->円)</td>
	            <!--{/if}-->
						<!--{/if}-->

        <tr>
            <th colspan="5" class="column right">小計</th>
            <td class="right"><!--{$arrForm.subtotal.value|number_format}-->円</td>
        </tr>
        <tr>
            <th colspan="5" class="column right">値引き</th>
            <td class="right">
                <!--{assign var=key value="discount"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="5" class="box6" />
                円
            </td>
        </tr>
        <tr>
            <th colspan="5" class="column right">送料</th>
            <td class="right">
                <!--{assign var=key value="deliv_fee"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="5" class="box6" />
                円
            </td>
        </tr>
        <tr>
            <th colspan="5" class="column right">手数料</th>
            <td class="right">
                <!--{assign var=key value="charge"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="5" class="box6" />
                円
            </td>
        </tr>
        <tr>
            <th colspan="5" class="column right">合計</th>
            <td class="right">
                <span class="attention"><!--{$arrErr.total}--></span>
                <!--{$arrForm.total.value|number_format}--> 円
            </td>
        </tr>
        <tr>
            <th colspan="5" class="column right">お支払い合計</th>
            <td class="right">
                <span class="attention"><!--{$arrErr.payment_total}--></span>
                <!--{$arrForm.payment_total.value|number_format}-->
                円
            </td>
        </tr>
        <!--{if $smarty.const.USE_POINT !== false}-->
            <tr>
                <th colspan="5" class="column right">使用ポイント</th>
                <td class="right">
                    <!--{assign var=key value="use_point"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|default:0|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="5" class="box6" />
                    pt
                </td>
            </tr>
            <!--{if $arrForm.birth_point.value > 0}-->
            <tr>
                <th colspan="5" class="column right">お誕生日ポイント</th>
                <td class="right">
                    <!--{$arrForm.birth_point.value|number_format}-->
                    pt
                </td>
            </tr>
            <!--{/if}-->
            <tr>
                <th colspan="5" class="column right">加算ポイント</th>
                <td class="right">
                    <!--{$arrForm.add_point.value|number_format|default:0}-->
                    pt
                </td>
            </tr>
        <!--{/if}-->
    </table>
    <!--{assign var=key value="shipping_quantity"}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" />
    <!--▼お届け先情報ここから-->
    <a name="shipping"></a>
    <h2>お届け先情報
    <!--{if $arrForm.shipping_quantity.value <= 1}-->
        <a class="btn-normal" href="javascript:;" onclick="fnCopyFromOrderData();">お客様情報へお届けする</a>
    <!--{/if}-->
    <!--{if $smarty.const.USE_MULTIPLE_SHIPPING !== false}-->
        <a class="btn-normal" href="javascript:;"  onclick="fnAppendShipping();">お届け先を新規追加</a>
        <a class="btn-normal" href="javascript:;" onclick="fnMultiple();">複数のお届け先を指定する</a>
    <!--{/if}-->
    </h2>

    <!--{*** EC-CUBE 2.11.1以前用と2.11.2以降用の2種類のテンプレートを定義しています。 ***}-->
    <!--{if preg_match('/^2\.11\.[0-1]$/', $smarty.const.ECCUBE_VERSION)}-->
    <!--{section name=shipping loop=$arrForm.shipping_quantity.value}-->
        <!--{assign var=shipping_index value="`$smarty.section.shipping.index`"}-->

        <!--{if $arrForm.shipping_quantity.value > 1}-->
            <h3>お届け先<!--{$smarty.section.shipping.iteration}--></h3>
        <!--{/if}-->
        <!--{assign var=key value="shipping_id"}-->
        <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key].value[$shipping_index]|default:"0"|h}-->" id="<!--{$key}-->_<!--{$shipping_index}-->" />
        <!--{if $arrForm.shipping_quantity.value > 1}-->
            <!--{assign var=product_quantity value="shipping_product_quantity"}-->
            <input type="hidden" name="<!--{$product_quantity}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$product_quantity].value[$shipping_index]|h}-->" />

            <!--{if $arrForm[$product_quantity].value[$shipping_index] > 0}-->
                <table class="list" id="order-edit-products">
                    <tr>
                        <th class="id">商品コード</th>
                        <th class="name">商品名/規格1/規格2</th>
                        <th class="price">単価</th>
                        <th class="qty">数量</th>
                    </tr>
                    <!--{section name=item loop=$arrForm[$product_quantity].value[$shipping_index]}-->
                        <!--{assign var=item_index value="`$smarty.section.item.index`"}-->

                        <tr>
                            <td>
                                <!--{assign var=key value="shipment_product_class_id"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrForm[$key].value[$shipping_index][$item_index]|h}-->" />
                                <!--{assign var=key value="shipment_product_code"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrForm[$key].value[$shipping_index][$item_index]|h}-->" />
                                <!--{$arrForm[$key].value[$shipping_index][$item_index]|h}-->
                            </td>
                            <td>
                                <!--{assign var=key1 value="shipment_product_name"}-->
                                <!--{assign var=key2 value="shipment_classcategory_name1"}-->
                                <!--{assign var=key3 value="shipment_classcategory_name2"}-->
                                <input type="hidden" name="<!--{$key1}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrForm[$key1].value[$shipping_index][$item_index]|h}-->" />
                                <input type="hidden" name="<!--{$key2}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrForm[$key2].value[$shipping_index][$item_index]|h}-->" />
                                <input type="hidden" name="<!--{$key3}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrForm[$key3].value[$shipping_index][$item_index]|h}-->" />
                                <!--{$arrForm[$key1].value[$shipping_index][$item_index]|h}-->/<!--{$arrForm[$key2].value[$shipping_index][$item_index]|default:"(なし)"|h}-->/<!--{$arrForm[$key3].value[$shipping_index][$item_index]|default:"(なし)"|h}-->
                            </td>
                            <td class="right">
                                <!--{assign var=key value="shipment_price"}-->
                                <!--{$arrForm[$key].value[$shipping_index][$item_index]|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule|number_format}-->円
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrForm[$key].value[$shipping_index][$item_index]|h}-->" />
                            </td>
                            <td class="right">
                                <!--{assign var=key value="shipment_quantity"}-->
                                <!--{$arrForm[$key].value[$shipping_index][$item_index]|h}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrForm[$key].value[$shipping_index][$item_index]|h}-->" />
                            </td>
                        </tr>
                    <!--{/section}-->
                </table>
            <!--{/if}-->
        <!--{/if}-->

        <table class="form">
            <tr>
                <th>お名前</th>
                <td>
                    <!--{assign var=key1 value="shipping_name01"}-->
                    <!--{assign var=key2 value="shipping_name02"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--><!--{$arrErr[$key2][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key1].value[$shipping_index]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                    <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key2].value[$shipping_index]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                </td>
            </tr>
            <tr>
                <th>お名前(カナ)</th>
                <td>
                    <!--{assign var=key1 value="shipping_kana01"}-->
                    <!--{assign var=key2 value="shipping_kana02"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--><!--{$arrErr[$key2][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key1].value[$shipping_index]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                    <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key2].value[$shipping_index]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                </td>
            </tr>
            <tr>
                <th>TEL</th>
                <td>
                    <!--{assign var=key1 value="shipping_tel01"}-->
                    <!--{assign var=key2 value="shipping_tel02"}-->
                    <!--{assign var=key3 value="shipping_tel03"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--></span>
                    <span class="attention"><!--{$arrErr[$key2][$shipping_index]}--></span>
                    <span class="attention"><!--{$arrErr[$key3][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key1].value[$shipping_index]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key2].value[$shipping_index]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$key3}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key3].value[$shipping_index]|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" size="6" class="box6" />
                </td>
            </tr>
            <tr>
                <th>住所</th>
                <td>
                    <!--{assign var=key1 value="shipping_zip01"}-->
                    <!--{assign var=key2 value="shipping_zip02"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--><!--{$arrErr[$key2][$shipping_index]}--></span>
                    〒
                    <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key1].value[$shipping_index]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                    -
                    <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key2].value[$shipping_index]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                    <a class="btn-normal" href="javascript:;" name="address_input" onclick="fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', 'shipping_zip01[<!--{$shipping_index}-->]', 'shipping_zip02[<!--{$shipping_index}-->]', 'shipping_pref[<!--{$shipping_index}-->]', 'shipping_addr01[<!--{$shipping_index}-->]'); return false;">住所入力</a><br />
                    <!--{assign var=key value="shipping_pref"}-->
                    <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                    <select class="top" name="<!--{$key}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                        <option value="" selected="">都道府県を選択</option>
                        <!--{html_options options=$arrPref selected=$arrForm[$key].value[$shipping_index]}-->
                    </select><br />
                    <!--{assign var=key value="shipping_addr01"}-->
                    <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key].value[$shipping_index]|h}-->" size="60" class="box60 top" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->" /><br />
                    <!--{assign var=key value="shipping_addr02"}-->
                    <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key}-->[<!--{$shipping_index}-->]" value="<!--{$arrForm[$key].value[$shipping_index]|h}-->" size="60" class="box60" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->" />
                </td>
            </tr>
            <tr>
                <th>お届け時間</th>
                <td>
                    <!--{assign var=key value="time_id"}-->
                    <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                    <select name="<!--{$key}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                        <option value="" selected="0">指定無し</option>
                        <!--{html_options options=$arrDelivTime selected=$arrForm[$key].value[$shipping_index]}-->
                    </select>
                </td>
            </tr>
            <tr>
                <th>お届け日</th>
                <td>
                    <!--{assign var=key1 value="shipping_date_year"}-->
                    <!--{assign var=key2 value="shipping_date_month"}-->
                    <!--{assign var=key3 value="shipping_date_day"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <span class="attention"><!--{$arrErr[$key3]}--></span>
                    <select name="<!--{$key1}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                        <!--{html_options options=$arrYearShippingDate selected=$arrForm[$key1].value[$shipping_index]|default:""}-->
                    </select>年
                    <select name="<!--{$key2}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->">
                        <!--{html_options options=$arrMonthShippingDate selected=$arrForm[$key2].value[$shipping_index]|default:""}-->
                    </select>月
                    <select name="<!--{$key3}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->">
                        <!--{html_options options=$arrDayShippingDate selected=$arrForm[$key3].value[$shipping_index]|default:""}-->
                    </select>日
                </td>
            </tr>

        </table>
    <!--{/section}-->
    <!--▲お届け先情報ここまで-->
    <!--{else}-->
    <!--{foreach name=shipping from=$arrAllShipping item=arrShipping key=shipping_index}-->
        <!--{if $arrForm.shipping_quantity.value > 1}-->
            <h3>お届け先<!--{$smarty.foreach.shipping.iteration}--></h3>
        <!--{/if}-->
        <!--{assign var=key value="shipping_id"}-->
        <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key]|default:"0"|h}-->" id="<!--{$key}-->_<!--{$shipping_index}-->" />
        <!--{if $arrForm.shipping_quantity.value > 1}-->
            <!--{assign var=product_quantity value="shipping_product_quantity"}-->
            <input type="hidden" name="<!--{$product_quantity}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$product_quantity]|h}-->" />

            <!--{if count($arrShipping.shipment_product_class_id) > 0}-->
                <table class="list" id="order-edit-products">
                    <tr>
                        <th class="id">商品コード</th>
                        <th class="name">商品名/規格1/規格2</th>
                        <th class="price">単価</th>
                        <th class="qty">数量</th>
                    </tr>
                    <!--{section name=item loop=$arrShipping.shipment_product_class_id|@count}-->
                        <!--{assign var=item_index value="`$smarty.section.item.index`"}-->

                        <tr>
                            <td>
                                <!--{assign var=key value="shipment_product_class_id"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key][$item_index]|h}-->" />
                                <!--{assign var=key value="shipment_product_code"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key][$item_index]|h}-->" />
                                <!--{$arrShipping[$key][$item_index]|h}-->
                            </td>
                            <td>
                                <!--{assign var=key1 value="shipment_product_name"}-->
                                <!--{assign var=key2 value="shipment_classcategory_name1"}-->
                                <!--{assign var=key3 value="shipment_classcategory_name2"}-->
                                <input type="hidden" name="<!--{$key1}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key1][$item_index]|h}-->" />
                                <input type="hidden" name="<!--{$key2}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key2][$item_index]|h}-->" />
                                <input type="hidden" name="<!--{$key3}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key3][$item_index]|h}-->" />
                                <!--{$arrShipping[$key1][$item_index]|h}-->/<!--{$arrShipping[$key2][$item_index]|default:"(なし)"|h}-->/<!--{$arrShipping[$key3][$item_index]|default:"(なし)"|h}-->
                            </td>
                            <td class="right">
                                <!--{assign var=key value="shipment_price"}-->
                                <!--{$arrShipping[$key][$item_index]|sfCalcIncTax|number_format}-->円
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key][$item_index]|h}-->" />
                            </td>
                            <td class="right">
                                <!--{assign var=key value="shipment_quantity"}-->
                                <!--{$arrShipping[$key][$item_index]|h}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key][$item_index]|h}-->" />
                            </td>
                        </tr>
                    <!--{/section}-->
                </table>
            <!--{/if}-->
        <!--{/if}-->

        <table class="form">
            <tr>
                <th>お名前</th>
                <td>
                    <!--{assign var=key1 value="shipping_name01"}-->
                    <!--{assign var=key2 value="shipping_name02"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--><!--{$arrErr[$key2][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                    <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                </td>
            </tr>
            <tr>
                <th>お名前(カナ)</th>
                <td>
                    <!--{assign var=key1 value="shipping_kana01"}-->
                    <!--{assign var=key2 value="shipping_kana02"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--><!--{$arrErr[$key2][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                    <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                </td>
            </tr>
            <tr>
                <th>TEL</th>
                <td>
                    <!--{assign var=key1 value="shipping_tel01"}-->
                    <!--{assign var=key2 value="shipping_tel02"}-->
                    <!--{assign var=key3 value="shipping_tel03"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--></span>
                    <span class="attention"><!--{$arrErr[$key2][$shipping_index]}--></span>
                    <span class="attention"><!--{$arrErr[$key3][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$key3}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key3]|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                </td>
            </tr>
            <tr>
                <th>FAX</th>
                <td>
                    <!--{assign var=key1 value="shipping_fax01"}-->
                    <!--{assign var=key2 value="shipping_fax02"}-->
                    <!--{assign var=key3 value="shipping_fax03"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--></span>
                    <span class="attention"><!--{$arrErr[$key2][$shipping_index]}--></span>
                    <span class="attention"><!--{$arrErr[$key3][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$key3}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key3]|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                </td>
            </tr>
            <tr>
                <th>住所</th>
                <td>
                    <!--{assign var=key1 value="shipping_zip01"}-->
                    <!--{assign var=key2 value="shipping_zip02"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--><!--{$arrErr[$key2][$shipping_index]}--></span>
                    〒
                    <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                    -
                    <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                    <a class="btn-normal" href="javascript:;" name="address_input" onclick="fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', 'shipping_zip01[<!--{$shipping_index}-->]', 'shipping_zip02[<!--{$shipping_index}-->]', 'shipping_pref[<!--{$shipping_index}-->]', 'shipping_addr01[<!--{$shipping_index}-->]'); return false;">住所入力</a><br />
                    <!--{assign var=key value="shipping_pref"}-->
                    <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                    <select class="top" name="<!--{$key}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->">
                        <option value="" selected="">都道府県を選択</option>
                        <!--{html_options options=$arrPref selected=$arrShipping[$key]}-->
                    </select><br />
                    <!--{assign var=key value="shipping_addr01"}-->
                    <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key]|h}-->" size="60" class="box60 top" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->" /><br />
                    <!--{assign var=key value="shipping_addr02"}-->
                    <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                    <input type="text" name="<!--{$key}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key]|h}-->" size="60" class="box60" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->" />
                </td>
            </tr>
            <tr>
                <th>お届け時間</th>
                <td>
                    <!--{assign var=key value="time_id"}-->
                    <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                    <select name="<!--{$key}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->">
                        <option value="" selected="0">指定無し</option>
                        <!--{html_options options=$arrDelivTime selected=$arrShipping[$key]}-->
                    </select>
                </td>
            </tr>
            <tr>
                <th>お届け日</th>
                <td>
                    <!--{assign var=key1 value="shipping_date_year"}-->
                    <!--{assign var=key2 value="shipping_date_month"}-->
                    <!--{assign var=key3 value="shipping_date_day"}-->
                    <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--></span>
                    <span class="attention"><!--{$arrErr[$key2][$shipping_index]}--></span>
                    <span class="attention"><!--{$arrErr[$key3][$shipping_index]}--></span>
                    <select name="<!--{$key1}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->">
                        <!--{html_options options=$arrYearShippingDate selected=$arrShipping[$key1]|default:""}-->
                    </select>年
                    <select name="<!--{$key2}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->">
                        <!--{html_options options=$arrMonthShippingDate selected=$arrShipping[$key2]|default:""}-->
                    </select>月
                    <select name="<!--{$key3}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key3][$shipping_index]|sfGetErrorColor}-->">
                        <!--{html_options options=$arrDayShippingDate selected=$arrShipping[$key3]|default:""}-->
                    </select>日
                </td>
            </tr>

        </table>
    <!--{/foreach}-->
    <!--▲お届け先情報ここまで-->
    <!--{/if}-->

    <a name="deliv"></a>
    <table class="form">
        <tr>
            <th>配送業者<br /><span class="attention">(配送業者の変更に伴う送料の変更は手動にてお願いします。)</span></th>
            <td>
                <!--{assign var=key value="deliv_id"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" onchange="fnModeSubmit('deliv','anchor_key','deliv');">
                    <option value="" selected="">選択してください</option>
                    <!--{html_options options=$arrDeliv selected=$arrForm[$key].value}-->
                </select>
            </td>
        </tr>
        <tr>
            <th>お支払方法<br /><span class="attention">(お支払方法の変更に伴う手数料の変更は手動にてお願いします。)</span></th>
            <td>
                <!--{assign var=key value="payment_id"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" onchange="fnModeSubmit('payment','anchor_key','deliv');">
                    <option value="" selected="">選択してください</option>
                    <!--{html_options options=$arrPayment selected=$arrForm[$key].value}-->
                </select>
            </td>
        </tr>

        <!--{if $arrForm.payment_info|@count > 0}-->
        <tr>
            <th><!--{$arrForm.payment_type}-->情報</th>
            <td>
                <!--{foreach key=key item=item from=$arrForm.payment_info}-->
                <!--{if $key != "title"}--><!--{if $item.name != ""}--><!--{$item.name}-->：<!--{/if}--><!--{$item.value}--><br/><!--{/if}-->
                <!--{/foreach}-->
            </td>
        </tr>
        <!--{/if}-->

        <tr>
            <th>メモ</th>
            <td>
                <!--{assign var=key value="note"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <textarea name="<!--{$key}-->" maxlength="<!--{$arrForm[$key].length}-->" cols="80" rows="6" class="area80" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
            </td>
        </tr>
    </table>
    <!--▲受注商品情報ここまで-->

    <div class="btn-area">
        <ul>
            <!--{if count($arrSearchHidden) > 0}-->
            <li><a class="btn-action" href="javascript:;" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_URLPATH}-->'); fnModeSubmit('search','',''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
            <!--{/if}-->
            <li><a class="btn-action" href="javascript:;" onclick="return fnFormConfirm(); return false;"><span class="btn-next">この内容で登録する</span></a></li>
        </ul>
    </div>
</div>
<div id="multiple"></div>
</form>
