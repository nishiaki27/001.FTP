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
<script>//<![CDATA[
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

//ご注文内容エリアの表示/非表示
var speed = 1000; //表示アニメのスピード（ミリ秒）
var stateCartconfirm = 0;
function fnCartconfirmToggle(areaEl, imgEl) {
    areaEl.toggle(speed);
    if (stateCartconfirm == 0) {
        $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/common/btn_plus.png");
        stateCartconfirm = 1;
    } else {
        $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/common/btn_minus.png");
        stateCartconfirm = 0
    }
}
//お届け先エリアの表示/非表示
var stateDelivconfirm = 0;
function fnDelivconfirmToggle(areaEl, imgEl) {
    areaEl.toggle(speed);
    if (stateDelivconfirm == 0) {
        $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/common/btn_plus.png");
        stateDelivconfirm = 1;
    } else {
        $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/common/btn_minus.png");
        stateDelivconfirm = 0
    }
}
//配送方法エリアの表示/非表示
var stateOtherconfirm = 0;
function fnOtherconfirmToggle(areaEl, imgEl) {
    areaEl.toggle(speed);
    if (stateOtherconfirm == 0) {
        $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/common/btn_plus.png");
        stateOtherconfirm = 1;
    } else {
        $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/common/btn_minus.png");
        stateOtherconfirm = 0
    }
}
//]]></script>

<!--▼コンテンツここから -->
<section id="undercolumn">

<h2 class="title"><!--{$tpl_title|h}--></h2>

<!--★インフォメーション★-->
<div class="information end">
 <p>下記ご注文内容でよろしければ、「ご注文完了ページへ」ボタンをクリックしてください。</p>
</div>

<form name="form1" id="form1" method="post" action="<!--{$smarty.const.HTTP_URL}-->twoClick/confirm.php">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="confirm" />
            <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />
            
  <h3 class="subtitle">ご注文内容</h3>
 
<section class="cartconfirm_area">
<div class="form_area">
<!--▼フォームボックスここから -->
   <div class="formBox">
      <!--▼カートの中の商品一覧 -->
      <div class="cartcartconfirmarea">
     
         <!--{foreach from=$arrCartItems item=item}-->
           <!--▼商品 -->
             <div class="cartconfirmBox">
               <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=80&amp;height=80" alt="<!--{$item.productsClass.name|h}-->" width="80" height="80" class="photoL" />
                 <div class="cartconfirmContents">
                                                                    <div>
                  <p><em><!--{$item.productsClass.name|h}--></em><br />
                  <!--{if $item.productsClass.classcategory_name1 != ""}-->
                      <span class="mini"><!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--></span><br />
                  <!--{/if}-->
                  <!--{if $item.productsClass.classcategory_name2 != ""}-->
                      <span class="mini"><!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}--></span>
                  <!--{/if}-->
                  </p>
                                                                    </div>
               <ul>
                 <li><span class="mini">数量：</span><!--{$item.quantity|number_format}--></li>
                  <li class="result"><span class="mini">小計：</span><!--{$item.total_inctax|number_format}-->円</li>
                   </ul>
               </div>
            </div>
           <!--▲商品 -->
         <!--{/foreach}-->
    </div>
    <!--▲カートの中の商品一覧ここまで -->

    <!--★合計内訳★-->
    <div class="result_area">
        <ul>
        <li><span class="mini">小計 ：</span><!--{$tpl_total_inctax[$cartKey]|number_format}--> 円</li>
        <!--{if $smarty.const.USE_POINT !== false}-->
        <li><span class="mini">値引き（ポイントご使用時）： </span><!--{assign var=discount value=`$arrForm.use_point*$smarty.const.POINT_VALUE`}-->
        -<!--{$discount|number_format|default:0}--> 円</li>
        <!--{/if}-->
        <li><span class="mini">送料 ：</span><!--{$arrForm.deliv_fee|number_format}--> 円</li>
        <li><span class="mini">手数料 ：</span><!--{$arrForm.charge|number_format}--> 円</li>
        </ul>
    </div>

    <!--★合計★-->
    <div class="total_area">
        <span class="mini">合計：</span><span class="price fb"><!--{$arrForm.payment_total|number_format}--> 円</span>
    </div>   
   </div><!--▲formBox -->
  </div><!--▲form_area -->
</section>

    <!--{* ログイン済みの会員のみ *}-->
    <!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
<h3 class="subtitle">ポイント</h3>

 <input type="button" alt="変更する" value="変更する" onclick='document.location.href="./point.php"; return false;' />

<section class="cartconfirm_area">
<div class="form_area">

        <!--★ポイント情報★-->
        <div class="formBox point_confifrm">
          <dl>
            <dt>ご注文前のポイント</dt><dd><!--{$tpl_user_point|number_format|default:0}-->Pt</dd>
          </dl>
          <dl>
            <dt>ご使用ポイント</dt><dd>-<!--{$arrForm.use_point|number_format|default:0}-->Pt</dd>
          </dl>
          <!--{if $arrForm.birth_point > 0}-->
          <dl>
            <dt>お誕生月ポイント</dt><dd>+<!--{$arrForm.birth_point|number_format|default:0}-->Pt</dd>
          </dl>
          <!--{/if}-->
          <dl>
            <dt>今回加算予定のポイント</dt><dd>+<!--{$arrForm.add_point|number_format|default:0}-->Pt</dd>
          </dl>
          <dl>
              <!--{assign var=total_point value=`$tpl_user_point-$arrForm.use_point+$arrForm.add_point`}-->
               <dt>加算後のポイント</dt><dd><!--{$total_point|number_format}-->Pt</dd>
           </dl>
        </div><!--▲formBox -->
  </div><!--▲form_area -->
</section>
    <!--{/if}-->



<!--★お届け先の確認★-->
<!--{* 販売方法判定（ダウンロード販売のみの場合はお届け先を表示しない） *}-->
<!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
<section class="delivconfirm_area">
   <h3 class="subtitle">お届け先</h3>

<input type="button" alt="変更する" value="変更する" onclick='document.location.href="./deliv.php"; return false;' />

<div class="form_area">

<!--{foreach item=shippingItem from=$arrShipping name=shippingItem}-->
<!--▼フォームボックスここから -->
  <div class="formBox">
    <dl class="deliv_confirm">
     <dt>お届け先<!--{if $is_multiple}--><!--{$smarty.foreach.shippingItem.iteration}--><!--{/if}--></dt>   
      <dd>
        <p>〒<!--{$shippingItem.shipping_zip01|h}-->-<!--{$shippingItem.shipping_zip02|h}--><br />
          <!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01|h}--><!--{$shippingItem.shipping_addr02|h}--></p>
        <p class="deliv_name"><!--{$shippingItem.shipping_name01|h}--> <!--{$shippingItem.shipping_name02|h}--></p>
         <p><!--{$shippingItem.shipping_tel01}-->-<!--{$shippingItem.shipping_tel02}-->-<!--{$shippingItem.shipping_tel03}--></p>
          </dd>
          </dl>
  <!--{if $is_multiple}-->
  <!--▼カートの中の商品一覧 -->
     <div class="cartcartconfirmarea">
  <!--{foreach item=item from=$shippingItem.shipment_item}-->
   <!--▼商品 -->
   <div class="cartconfirmBox">
     <!--{if $item.productsClass.main_image|strlen >= 1}-->
       <a href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$item.productsClass.main_image|sfNoImageMainList|h}-->" target="_blank">
       <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=80&amp;height=80" alt="<!--{$item.productsClass.name|h}-->" width="80" height="80" class="photoL" /></a>
     <!--{else}-->
       <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=80&amp;height=80" alt="<!--{$item.productsClass.name|h}-->" width="80" height="80" class="photoL" />
     <!--{/if}-->
     <div class="cartconfirmContents">
      <p><em><!--{$item.productsClass.name|h}--></em><br />
      <!--{if $item.productsClass.classcategory_name1 != ""}-->
          <span class="mini"><!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--></span><br />
      <!--{/if}-->
      <!--{if $item.productsClass.classcategory_name2 != ""}-->
          <span class="mini"><!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}--></span>
      <!--{/if}-->
      </p>
      <ul>
       <li><span class="mini">数量：</span><!--{$item.quantity}--></li>
       <!--{* XXX デフォルトでは購入小計と誤差が出るためコメントアウト*}-->
        <li class="result"><span class="mini">小計：</span><!--{$item.total_inctax|number_format}-->円</li>
         </ul>
      </div>
   </div>
  <!--▲商品 -->
  <!--{/foreach}-->
</div>
<!--▲カートの中の商品一覧ここまで -->
<!--{/if}-->

</div><!--▲formBox -->
<!--{/foreach}-->

</div><!--▲form_area -->
</section>
<!--{/if}-->


<!--★配送方法・お支払方法など★-->
<section class="otherconfirm_area">
  <h3 class="subtitle">配送方法・お支払方法</h3>

  <input type="button" alt="変更する" value="変更する" onclick='document.location.href="./payment.php"; return false;' />
<div class="form_area">
<!--▼フォームボックスここから -->
  <div class="formBox">
   <div class="innerBox">
     <em>配送方法</em>：<!--{if $arrForm.deliv_id}-->
  		            <!--{$arrDeliv[$arrForm.deliv_id]|h}-->
                            <input type="hidden" name="deliv_id" value="<!--{$arrForm.deliv_id|h}-->" />
			<!--{else}-->
                            <span class="attention">配送方法が指定されていません。変更してください。</span>
		        <!--{/if}-->
    </div>
   <div class="innerBox">
     <em>お支払方法：</em>		    <!--{if $arrForm.payment_method}-->
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
    </div>

  </div><!--▲formBox -->
</div><!--▲form_area -->
</section>

<!--★お届け時間の指定★-->
<!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
<section class="pay_area02">
 <h3 class="subtitle">お届け時間の指定</h3>

 <div class="form_area">
  <!--{foreach item=shippingItem name=shippingItem from=$arrShipping}-->
  <!--{assign var=index value=$shippingItem.shipping_id}-->
 
 <!--▼フォームボックスここから -->
 <!--{if $is_multiple}-->
  <div class="formBox">
    <div class="box_header">
      お届け先<!--{$smarty.foreach.shippingItem.iteration}-->
    </div>

     <div class="innerBox">
       <!--{$shippingItem.shipping_name01}--><!--{$shippingItem.shipping_name02}--><br />
        <span class="mini"><!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01}--><!--{$shippingItem.shipping_addr02}--></span>
     </div>
 <!--{else}-->
   <div class="time_select">
 <!--{/if}-->
       <div class="btn_area_btm">
       <!--★お届け日★-->
       <!--{assign var=key value="deliv_date`$index`"}-->
       <span class="attention"><!--{$arrErr[$key]}--></span>
        <!--{if !$arrDelivDate}-->
            ご指定頂けません。
        <!--{else}-->
             <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="boxLong top data-role-none">
                <option value="" selected="">お届け日：指定なし</option>
                <!--{html_options options=$arrDelivDate selected=$arrForm[$key].value}-->
             </select>
<!--{/if}-->

<!--★お届け時間★-->
<!--{assign var=key value="deliv_time_id`$index`"}-->
        <span class="attention"><!--{$arrErr[$key]}--></span>
           <select name="<!--{$key}-->" id="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="boxLong data-role-none">
                <option value="" selected="">お届け時間：指定なし</option>
                <!--{html_options options=$arrDelivTime selected=$arrForm[$key].value}-->
           </select>
                </div>
                  </div><!--▲formBox -->
<!--{/foreach}-->

         </div><!--▲form_area -->
     </section>
<!--{/if}-->

<!--★その他お問い合わせ★-->
<section class="contact_area">
 <h3 class="subtitle">その他お問い合わせ</h3>
  <div class="form_area">
    <p>その他お問い合わせ事項がございましたら、こちらにご入力ください。</p>
   
       <!--{assign var=key value="message"}-->
       <span class="attention"><!--{$arrErr[$key]}--></span>
       <textarea name="<!--{$key}-->" id="etc" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" cols="62" rows="8" class="textarea data-role-none" wrap="hard"><!--{$arrForm[$key].value|h}--></textarea><br />
        
         </div><!--▲form_area -->
          </section>

<div class="information end attention">
     <p>
以上の内容で間違いなければ、下記「ご注文完了ページへ」ボタンをクリックしてください。<br />
ご注文完了ページへ切り替わるまで、他の操作を行わずにそのままお待ち下さい。 <br />
ご注文完了ページが表示されず、ご注文完了メールも受信できない場合は、<br />
お手数ですが、ショップまでご連絡くださいませ。
     </p>
</div>

<!--★ボタン★-->
<div class="btn_area">
<ul class="btn_btm">
<li><a rel="external" href="javascript:void(document.form1.submit());" class="btn">ご注文完了ページへ</a></li>
<li><a rel="external" href="<!--{$smarty.const.CART_URLPATH}-->" class="btn_back">戻る</a></li>
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
