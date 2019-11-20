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
<section id="undercolumn">
        <h2 class="title"><!--{$tpl_title|h}--></h2>

        <form name="form1" id="form1" method="post" action="<!--{$smarty.const.HTTP_URL}-->twoClick/point.php">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="confirm" />
            <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />


<!--★ポイント使用の指定★-->
<!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
<section class="point_area">
<h3 class="subtitle">ポイント使用の指定</h3>

 <div class="form_area">
   <p class="fb"><span class="point">1ポイントを1円</span>として使用する事ができます。</p>
     <div class="point_announce">
       <p>現在の所持ポイントは「<span class="price"><!--{$tpl_user_point|default:0|number_format}-->Pt</span>」です。<br />
            今回ご購入合計金額：<span class="price"><!--{$arrPrices.subtotal|number_format}-->円</span> (送料、手数料を含みません。)</p>
             </div>

  <!--▼ポイントフォームボックスここから -->
     <div class="formBox">
        <div class="innerBox fb">
           <p><input type="radio" id="point_on" name="point_check" value="1" <!--{$arrForm.point_check.value|sfGetChecked:1}--> onchange="fnCheckInputPoint();" class="data-role-none" />
               <label for="point_on">ポイントを使用する</label></p>
           <!--{assign var=key value="use_point"}-->
           
           <p class="check_point"><input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|default:$tpl_user_point}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="box_point data-role-none" />ポイントを使用する。<span class="attention"><!--{$arrErr[$key]}--></span></p>
            </div>
       <div class="innerBox fb">
         <input type="radio" id="point_off" name="point_check" value="2" <!--{$arrForm.point_check.value|sfGetChecked:2}--> onchange="fnCheckInputPoint();" class="data-role-none" />
           <label for="point_off">ポイントを使用しない</label>
         </div>
             </div><!--▲formBox -->
               </div><!--▲form_area -->
                 </section>
<!--{/if}-->

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
