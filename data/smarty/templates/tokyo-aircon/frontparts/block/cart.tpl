<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
<!--<div style="margin-bottom:8px;margin-top:0px;padding: 5px 5px 11px 5px;text-align: center;font-size: 10px; width: 168px;border:1px #B5B6B6 solid; font-size:12px; background-color:#efefef;"><p style="background:#000; color:#fff; padding:5px; font-size:14px; font-weight:bold; margin-bottom:7px; ">営業時間変更のお知らせ</p>
夏季期間中において、<br />営業時間を変更いたします。<br>
<p style="font-size:12px; font-weight:bold; padding-top:5px; border-top:solid 2px #898989; margin-top:5px; margin-left:5px; margin-right:5px;">夏季期間中の営業時間</p>
<p style="font-size:16px;color:red; font-weight:bold; border-bottom:solid 2px #898989; margin-left:5px; margin-right:5px;">9：00～17：00</p>
</div>-->
<div id="side_cart">
<div class="cartTitle">現在のカートの中</div>
<!--{* カゴ商品詳細表示件数無制限ここから *}-->
<!--{if $arrCartList != ''}-->
<table>
  <!--{section name=cnt loop=$arrCartList}-->
    <!--{if $arrCartList[cnt].productsClass.name != ''}-->
      <tr>
        <td class="pl10"><img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$arrCartList[cnt].productsClass.main_list_image|sfNoImageMainList|h}-->&width=38&height=38" /></td>
        <td style="padding:8px;"><!--{$arrCartList[cnt].productsClass.product_code_min}--><br /><!--{$arrCartList[cnt].productsClass.name}--></td>
      </tr>
    <!--{/if}-->
  <!--{/section}-->
</table>
<!--{/if}-->
<!--{* カゴ商品詳細表示件数無制限ここまで *}-->

            <div class="pl10">
                <p>合計数量：<span class="txt_red"><!--{$arrCartList.0.TotalQuantity|number_format|default:0}--></span></p>
                <p>商品金額：<span class="txt_red"><!--{$arrCartList.0.ProductsTotal|number_format|default:0}-->円</span></p>
                <!--{*************************************
                     * カゴの中に商品がある場合にのみ表示
                     * 複数の商品種別が存在する場合は非表示
                     *************************************}-->
                <!--{if $arrCartList.0.TotalQuantity > 0 and $arrCartList.0.free_rule > 0 and !$isMultiple and !$hasDownload}-->
  <p class="postage" >
                    <!--{if $arrCartList.0.deliv_free > 0}-->
                        <span class="point_announce">送料手数料無料まで</span>あと<span class="price"><!--{$arrCartList.0.deliv_free|number_format|default:0}-->円（税込）</span>です。
                    <!--{else}-->
                        現在、送料は「<span class="price">無料</span>」です。
                    <!--{/if}-->
                </p>
                <!--{/if}-->
          </div>
            <div class="txt_C">
                <a href="https://www.tokyo-aircon.net/cart/" title="かごの中を見る"><img src="/images/common/side-cart-bt01.png" alt="かごの中を見る" title="かごの中を見る"></a>
  </div>

</div>
