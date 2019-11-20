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
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<script type="text/javascript">//<![CDATA[
var send = true;

function fnCheckSubmit() {
    if(send) {
        send = false;
        return true;
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    }
}

$(document).ready(function() {
    $('a.expansion').fancybox();
});
//]]></script>

<!--▼CONTENTS-->
<div id="under02column">
    <div id="under02column_shopping">
         <h2 class="title"><!--{$tpl_title|h}--></h2>

        <p>下記ご注文内容で送信してもよろしいでしょうか？<br />
            よろしければ、「ご注文完了ページへ」ボタンをクリックしてください。</p>

        <form name="form1" id="form1" method="post" action="?">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="confirm" />
            <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />
            <table summary="ご注文内容確認" class="entryform">
                <tr>
                    <th class="alignC confirm_ph valignM">商品写真</th>
                    <th class="alignC valignM">商品名</th>
                    <th class="alignC valignM">数量</th>
                    <th class="alignC valignM">小計</th>
                </tr>
                <!--{foreach from=$arrCartItems item=item}-->
                <tr>
                    <td class="phototd">
                        <a
                            <!--{if $item.productsClass.main_image|strlen >= 1}-->
                                href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$item.productsClass.main_image|sfNoImageMainList|h}-->"
                                class="expansion"
                                target="_blank"
                            <!--{/if}-->
                        >
                            <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=40&amp;height=40" alt="<!--{$item.productsClass.name|h}-->" /></a>
                    </td>
                    <td class="detailtdName"><strong><!--{$item.productsClass.name|h}--></strong>
                            <!--{if $item.productsClass.classcategory_name1 != ""}-->
                            <!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--><br />
                            <!--{/if}-->
                            <!--{if $item.productsClass.classcategory_name2 != ""}-->
                            <!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}-->
                            <!--{/if}--><br />
<!--{$item.productsClass.price02|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule|number_format}-->円
                 </td>
                 <td class="detailtdNumber"><!--{$item.quantity|number_format}--></td>
                 <td class="alignR"><!--{$item.total_inctax|number_format}-->円</td>
             </tr>
             <!--{/foreach}-->
                <tr>
                    <th colspan="3" class="resulttd">小計</th>
                    <td class="alignR"><!--{$tpl_total_inctax[$cartKey]|number_format}-->円</td>
                </tr>
                <!--{if $smarty.const.USE_POINT !== false}-->
                    <tr>
                        <th colspan="3" class="resulttd">値引き（ポイントご使用時）</th>
                        <td class="alignR">
                        <!--{assign var=discount value=`$arrForm.use_point*$smarty.const.POINT_VALUE`}-->
                         -<!--{$discount|number_format|default:0}-->円</td>
                    </tr>
                <!--{/if}-->
                <tr>
                    <th colspan="3" class="resulttd">送料</th>
                    <td class="pricetd"><!--{$arrForm.deliv_fee|number_format}-->円</td>
                </tr>
                <tr>
                    <th colspan="3" class="resulttd">手数料</th>
                    <td class="pricetd"><!--{$arrForm.charge|number_format}-->円</td>
                </tr>
                <tr>
                    <th colspan="3" class="resulttd">合計</th>
                    <td class="pricetd"><em><!--{$arrForm.payment_total|number_format}-->円</em></td>
                </tr>
            </table>

            <!--{* ログイン済みの会員のみ *}-->
            <!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
	        <h2>ポイント</h2>
		<input type="button" alt="変更する" value="変更する" onclick='document.location.href="./point.php"; return false;' />
                <table summary="ポイント確認" class="entryform">
                    <tr>
                        <th class="trpoint">ご注文前のポイント</th>
                        <td class="alignR"><!--{$tpl_user_point|number_format|default:0}-->Pt</td>
                    </tr>
                    <tr>
                        <th class="trpoint">ご使用ポイント</th>
                        <td class="alignR">-<!--{$arrForm.use_point|number_format|default:0}-->Pt</td>
                    </tr>
                    <!--{if $arrForm.birth_point > 0}-->
                    <tr>
                        <th class="trpoint">お誕生月ポイント</th>
                        <td class="alignR">+<!--{$arrForm.birth_point|number_format|default:0}-->Pt</td>
                    </tr>
                    <!--{/if}-->
                    <tr>
                        <th class="trpoint">今回加算予定のポイント</th>
                        <td class="alignR">+<!--{$arrForm.add_point|number_format|default:0}-->Pt</td>
                    </tr>
                    <tr>
                    <!--{assign var=total_point value=`$tpl_user_point-$arrForm.use_point+$arrForm.add_point`}-->
                        <th class="trpoint">加算後のポイント</th>
                        <td class="alignR"><!--{$total_point|number_format}-->Pt</td>
                    </tr>
                </table>
            <!--{/if}-->
            <!--{* ログイン済みの会員のみ *}-->
            
            <!--お届け先ここから-->
            <!--{* 販売方法判定（ダウンロード販売のみの場合はお届け先を表示しない） *}-->
            <!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
            <!--{foreach item=shippingItem from=$arrShipping name=shippingItem}-->
            <h2>お届け先<!--{if $is_multiple}--><!--{$smarty.foreach.shippingItem.iteration}--><!--{/if}--></h2>
	    <input type="button" alt="変更する" value="変更する" onclick='document.location.href="./deliv.php"; return false;' />
           <!--{if $is_multiple}-->
            <table summary="ご注文内容確認" class="entryform">
              <tr>
                <th class="alignC valignM">商品写真</th>
                <th class="alignC valignM">商品名</th>
                <th class="alignC valignM">単価</th>
                <th class="alignC valignM">数量</th>
                <!--{* XXX 購入小計と誤差が出るためコメントアウト
                <th>小計</th>
                *}-->
              </tr>
              <!--{foreach item=item from=$shippingItem.shipment_item}-->
                  <tr>
                      <td class="phototd">
                        <a
                            <!--{if $item.productsClass.main_image|strlen >= 1}-->
                                href="<!--{$smarty.const.IMAGE_SAVE_URL}--><!--{$item.productsClass.main_image|sfNoImageMainList|h}-->"
                                class="expansion"
                                target="_blank"
                            <!--{/if}-->
                        >
                            <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=65&amp;height=65" alt="<!--{$item.productsClass.name|h}-->" /></a>
                      </td>
                      <td><!--{* 商品名 *}--><strong><!--{$item.productsClass.name|h}--></strong><br />
                          <!--{if $item.productsClass.classcategory_name1 != ""}-->
                              <!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--><br />
                          <!--{/if}-->
                          <!--{if $item.productsClass.classcategory_name2 != ""}-->
                              <!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}-->
                          <!--{/if}-->
                      </td>
                      <td class="alignR">
                          <!--{$item.productsClass.price02|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule|number_format}-->円
                      </td>
                      <td class="detailtdNumber"><!--{$item.quantity}--></td>
                      <!--{* XXX 購入小計と誤差が出るためコメントアウト
                      <td class="alignR"><!--{$item.total_inctax|number_format}-->円</td>
                      *}-->
                  </tr>
              <!--{/foreach}-->
            </table>
           <!--{/if}-->

            <table summary="お届け先確認" class="entryform">
                <tbody>
                    <tr>
                        <th>お名前</th>
                        <td><!--{$shippingItem.shipping_name01|h}--> <!--{$shippingItem.shipping_name02|h}--></td>
                    </tr>
                    <tr>
                        <th>お名前(フリガナ)</th>
                        <td><!--{$shippingItem.shipping_kana01|h}--> <!--{$shippingItem.shipping_kana02|h}--></td>
                    </tr>
                    <tr>
                        <th>郵便番号</th>
                        <td>〒<!--{$shippingItem.shipping_zip01|h}-->-<!--{$shippingItem.shipping_zip02|h}--></td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td><!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01|h}--><!--{$shippingItem.shipping_addr02|h}--></td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td><!--{$shippingItem.shipping_tel01}-->-<!--{$shippingItem.shipping_tel02}-->-<!--{$shippingItem.shipping_tel03}--></td>
                    </tr>
                </tbody>
            </table>
            <!--{/foreach}-->
            <!--{/if}-->
            <!--お届け先ここまで-->
<h2>その他指定・お問い合わせ</h2>
            <input type="button" alt="変更する" value="変更する" onclick='document.location.href="./payment.php"; return false;' />
            <table summary="配送方法・お支払方法・お届け日時の指定・その他お問い合わせ" class="entryform">
                <tbody>
                <tr>
                    <th>配送方法</th>
		    <td>
		        <!--{if $arrForm.deliv_id}-->
  		            <!--{$arrDeliv[$arrForm.deliv_id]|h}-->
                            <input type="hidden" name="deliv_id" value="<!--{$arrForm.deliv_id|h}-->" />
			<!--{else}-->
                            <span class="attention">配送方法が指定されていません。変更してください。</span>
		        <!--{/if}-->
		    </td>
                </tr>
                <tr>
                    <th>お支払方法</th>
                    <td>
		    <!--{if $arrForm.payment_method}-->
		        <!--{$arrForm.payment_method|h}-->
			<input type="hidden" name="payment_id" value="<!--{$arrForm.payment_id|h}-->" />
		    <!--{else}-->
                        <span class="attention">お支払方法が指定されていません。変更してください。</span>
		    <!--{/if}-->
	            <!--{if $arrForm.user_confirm_CardNo}-->
                        <br/>&nbsp;&nbsp;カード番号：<!--{$arrForm.user_confirm_CardNo|h}-->
	  	        <br/>&nbsp;&nbsp;名義人：<!--{$arrForm.user_confirm_HolderName|h}-->
	  	        <br/>&nbsp;&nbsp;有効期限：<!--{$arrForm.user_confirm_Expire|h}-->
		        <br/>&nbsp;&nbsp;お支払い回数：<!--{$arrForm.user_confirm_paymethod|h}-->
	            <!--{else}-->
                        <!--{$arrForm.user_confirm|h}-->
	            <!--{/if}-->
		    </td>
                </tbody>
            </table>

            <!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
            <div class="payarea02">
                <h2>お届け時間の指定</h2>
                <p class="select-msg">ご希望の方は、お届け時間を選択してください。</p>
                <p class="non-select-msg">まずはじめに、配送方法を選択ください。</p>
                <!--{foreach item=shippingItem name=shippingItem from=$arrShipping}-->
                <!--{assign var=index value=$shippingItem.shipping_id}-->
                <!--{if $is_multiple}-->
                <div class="delivdate">
                        ▼<!--{$shippingItem.shipping_name01}--><!--{$shippingItem.shipping_name02}--><br />
                        <!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01}--><!--{$shippingItem.shipping_addr02}-->
                </div>
                <!--{/if}-->
                <div class="delivdate">
                    <!--★お届け日★-->
                    <!--{assign var=key value="deliv_date`$index`"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    お届け日：
                    <!--{if !$arrDelivDate}-->
                        ご指定頂けません。
                    <!--{else}-->
                        <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                            <option value="" selected="">指定なし</option>
                            <!--{html_options options=$arrDelivDate selected=$arrForm[$key].value}-->
                        </select>
                    <!--{/if}-->
                </div>
                <div class="delivdate02">
                    <!--★お届け時間★-->
                    <!--{assign var=key value="deliv_time_id`$index`"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    お届け時間：
                    <select name="<!--{$key}-->" id="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                        <option value="" selected="">指定なし</option>
                        <!--{html_options options=$arrDelivTime selected=$arrForm[$key].value}-->
                    </select>
                </div>
                <!--{/foreach}-->
            </div>
            <!--{/if}-->

            <div class="payarea02">
                <h2>その他お問い合わせ</h2>
                <p>その他お問い合わせ事項がございましたら、こちらにご入力ください。</p>
                <div>
                    <!--★その他お問い合わせ事項★-->
                    <!--{assign var=key value="message"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <textarea name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" cols="80" rows="8" class="area660" wrap="hard"><!--{$arrForm[$key].value|h}--></textarea>
                </div>
                <div>
                    <span class="attention"> (<!--{$smarty.const.LTEXT_LEN}-->文字まで)</span>
                </div>
            </div>

	    <div class="attention">
		以上の内容で間違いなければ、下記「ご注文完了ページへ」ボタンをクリックしてください。<br />
		ご注文完了ページへ切り替わるまで、他の操作を行わずにそのままお待ち下さい。 <br />
		ご注文完了ページが表示されず、ご注文完了メールも受信できない場合は、<br />
		お手数ですが、ショップまでご連絡くださいませ。
            </div>

            <div class="tblareabtn">
                <p>
                 <input type="submit" value="ご注文完了ページへ" class="spbtn spbtn-shopping" width="130" height="30" alt="ご注文完了ページへ" name="next" id="next" />
                </p>
                <p><a href="<!--{$smarty.const.CART_URLPATH}-->" class="spbtn spbtn-medeum">戻る</a></p>
            </div>
        </form>
    </div>
</div>
<!--▲CONTENTS-->
