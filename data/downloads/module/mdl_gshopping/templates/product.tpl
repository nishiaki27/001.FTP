<?xml version='1.0' encoding='utf-8'?>
<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2010 LOCKON CO.,LTD. All Rights Reserved.
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
<rss version='2.0' xmlns:g='http://base.google.com/ns/1.0'>

<!--{* channel要素 *}-->
<channel>
<title><!--{$arrInfo.shop_name|h}--></title>
<link><!--{$smarty.const.HTTP_URL|h}--></link>
<description><!--{$arrInfo.good_traded|h}--></description>
<!--{section name=cnt loop=$arrProduct}-->
<item>
  <g:id><!--{$arrConfig.gs_prefix|h}--><!--{$arrProduct[cnt].product_id|h}--></g:id>
  <title><!--{$arrProduct[cnt].comment4|strip_tags:false|h}-->｜<!--{$arrProduct[cnt].comment3|replace:"<br>":" "|replace:"   ":" "|h}--></title>
  <description><!--{$arrProduct[cnt].name|strip_tags:false|h}--></description>
  <link><!--{$smarty.const.HTTP_URL|h}-->products/<!--{$arrProduct[cnt].comment4|h}-->.html<!--{if $arrConfig.tr_flg}-->&amp;source=googleps<!--{/if}--></link>
  <g:image_link><!--{$smarty.const.HTTP_URL|h}-->upload/save_image/<!--{$arrProduct[cnt].main_list_image|h}--></g:image_link>
  <g:price><!--{$arrProduct[cnt].price02_min|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule|h}--> JPY</g:price>
  <!--{*  商品の状態： 新品 [new] / 中古品 [used] / 再生品 [refurbished] *}--><g:condition>new</g:condition>
  <!--{if $arrProduct[cnt].stock eq "0"}-->
  <g:quantity>0</g:quantity>
  <!--{/if}-->
  <g:product_type><!--{$arrProduct[cnt].category_name|replace:",":" &gt; "|h}--></g:product_type>
  <g:google_product_category>日用品、ガーデン &gt; 家電 &gt; 冷暖房空調設備</g:google_product_category>
  <g:availability>in stock</g:availability>
  <g:brand><!--{if $arrProduct[cnt].maker_id eq "1"}-->東芝<!--{/if}--><!--{if $arrProduct[cnt].maker_id eq "2"}-->ダイキン<!--{/if}--><!--{if $arrProduct[cnt].maker_id eq "4"}-->日立<!--{/if}--><!--{if $arrProduct[cnt].maker_id eq "3"}-->三菱電機<!--{/if}--><!--{if $arrProduct[cnt].maker_id eq "5"}-->三菱重工<!--{/if}--></g:brand>
  <g:mpn><!--{$arrProduct[cnt].comment4|h}--></g:mpn>
  <g:shipping>
   <g:country>JP</g:country>
   <g:region></g:region>
   <g:service></g:service>
   <g:price>0 JPY</g:price>
  </g:shipping>
</item>
<!--{/section}-->
</channel>
</rss>