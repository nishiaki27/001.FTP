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
<!--▼CONTENTS-->
<script type="text/javascript">//<![CDATA[
    $(function() {
        if ($('input[name=deliv_id]:checked').val()
            || $('#deliv_id').val()) {
            showForm(true);
        } else {
            showForm(false);
        }
        $('input[id^=deliv_]').click(function() {
            showForm(true);
            var data = {};
            data.mode = 'select_deliv';
            data.deliv_id = $(this).val();
            data['<!--{$smarty.const.TRANSACTION_ID_NAME}-->'] = '<!--{$transactionid}-->';
            $.ajax({
                type : 'POST',
                url : location.pathname,
                data: data,
                cache : false,
                dataType : 'json',
                error : remoteException,
                success : function(data, dataType) {
                    if (data.error) {
                        remoteException();
                    } else {
                        // 支払い方法の行を生成
                        var payment = $('#payment');
                        payment.empty();
                        for (var i in data.arrPayment) {
                            // ラジオボタン
                            var radio = $('<input type="radio" />')
                                .attr('name', 'payment_id')
                                .attr('id', 'pay_' + i)
                                .val(data.arrPayment[i].payment_id);
                            // ラベル
                            var label = $('<label />')
                                .attr('for', 'pay_' + i)
                                .text(data.arrPayment[i].payment_method);
                            // 行
                            var li = $('<li />')
                                .append($('<td />')
                                        .addClass('centertd')
                                        .append(radio)
                                        .append(label));

                            li.appendTo(payment);
                        }
                        // お届け時間を生成
                        var deliv_time_id_select = $('select[id^=deliv_time_id]');
                        deliv_time_id_select.empty();
                        deliv_time_id_select.append($('<option />').text('指定なし').val(''));
                        for (var i in data.arrDelivTime) {
                            var option = $('<option />')
                                .val(i)
                                .text(data.arrDelivTime[i])
                                .appendTo(deliv_time_id_select);
                        }
                    }
                }
            });
        });

        /**
         * 通信エラー表示.
         */
        function remoteException(XMLHttpRequest, textStatus, errorThrown) {
            alert('通信中にエラーが発生しました。カート画面に移動します。');
            location.href = '<!--{$smarty.const.CART_URLPATH}-->';
        }

        /**
         * 配送方法の選択状態により表示を切り替える
         */
        function showForm(show) {
            if (show) {
                $('#payment, div.delivdate, .select-msg').show();
                $('.non-select-msg').hide();
            } else {
                $('#payment, div.delivdate, .select-msg').hide();
                $('.non-select-msg').show();
            }
        }

        $('#etc')
          .css('font-size', '100%')
          .autoResizeTextAreaQ({
              'max_rows': 50,
              'extra_rows': 0
          });
    });
//]]>
</script>

<!--▼コンテンツここから -->
<section id="undercolumn">

<h2 class="title"><!--{$tpl_title|h}--></h2>

<form name="form1" id="form1" method="post" action="<!--{$smarty.const.HTTP_URL}-->twoClick/payment.php">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="confirm" />
            <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />

<!--★配送方法の指定★-->
<!--{assign var=key value="deliv_id"}-->
 <!--{if $is_single_deliv}-->
 <input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->" id="deliv_id" />
  <!--{else}-->
<section class="pay_area">
 <h3 class="subtitle">配送方法の選択</h3>
  <!--{if $arrErr[$key] != ""}-->
  <p class="attention"><!--{$arrErr[$key]}--></p>
  <!--{/if}-->
  <ul>
  <!--{section name=cnt loop=$arrDeliv}-->
   <li>
     <input type="radio" id="deliv_<!--{$smarty.section.cnt.iteration}-->" name="<!--{$key}-->"  value="<!--{$arrDeliv[cnt].deliv_id}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" <!--{$arrDeliv[cnt].deliv_id|sfGetChecked:$arrForm[$key].value}--> class="data-role-none" />
     <label for="deliv_<!--{$smarty.section.cnt.iteration}-->"><!--{$arrDeliv[cnt].name|h}--><!--{if $arrDeliv[cnt].remark != ""}--><p><!--{$arrDeliv[cnt].remark|h}--></p><!--{/if}--></label>
   </li>
  <!--{/section}-->
  </ul>
</section>
<!--{/if}-->

<!--★インフォメーション★-->
<section class="pay_area">
 <h3 class="subtitle">お支払方法の指定</h3>
  <!--{assign var=key value="payment_id"}-->
  <!--{if $arrErr[$key] != ""}-->
    <p class="attention"><!--{$arrErr[$key]}--></p>
  <!--{/if}-->
  <p class="non-select-msg information">まずはじめに、配送方法を選択ください。</p>
  <ul id="payment">
  <!--{section name=cnt loop=$arrPayment}-->
   <li>
     <input type="radio" id="pay_<!--{$smarty.section.cnt.iteration}-->" name="<!--{$key}-->" value="<!--{$arrPayment[cnt].payment_id}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" <!--{$arrPayment[cnt].payment_id|sfGetChecked:$arrForm[$key].value}--> class="data-role-none" />
     <label for="pay_<!--{$smarty.section.cnt.iteration}-->"><!--{$arrPayment[cnt].payment_method|h}--><!--{if $arrPayment[cnt].note != ""}--><!--{/if}--></label>
     <!--{if $img_show}-->
         <!--{if $arrPayment[cnt].payment_image != ""}-->
             <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrPayment[cnt].payment_image}-->" />
         <!--{/if}-->
     <!--{/if}-->
   </li>
  <!--{/section}-->
  </ul>
</section>

<div class="btn_area">
  <ul class="btn_btm">
    <li><a rel="external" href="javascript:void(document.form1.submit());" class="btn">決定する</a></li>
    <li><a rel="external" href="./confirm.php" class="btn_back">戻る</a></li>
  </ul>
         </div>
        </form>
</section>
<!--▼検索バー -->
<section id="search_area">
<form method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="search" name="name" id="search" value="" placeholder="キーワードを入力" class="searchbox" >
</form>
</section>
<!--▲検索バー -->
<!--▲コンテンツここまで -->

