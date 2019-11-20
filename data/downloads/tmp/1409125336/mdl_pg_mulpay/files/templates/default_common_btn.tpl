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
<!--{include file="`$smarty.const.MDL_PG_MULPAY_TEMPLATE_PATH`common_attention.tpl"}-->

<div class="btn_area">
  <ul>
    <li>
      <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg','back03')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg','back03')" onclick="fnModeSubmit('return','',''); return false;">
        <img src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back03" id="back03" /></a>
    </li>
    <li>
      <!--{if $is2clickFlow}-->
      <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_determine_on.jpg','next')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_determine.jpg','next')" onclick="fnCheckSubmit('tran', '', '');return false;">
        <img src="<!--{$TPL_URLPATH}-->img/button/btn_determine.jpg" alt="決定する" name="next" id="next" /></a>
      <!--{else}-->
      <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_order_complete_on.jpg','next')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg','next')" onclick="fnCheckSubmit('tran', '', '');return false;">
        <img src="<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg" alt="ご注文完了ページへ" name="next" id="next" /></a>
      <!--{/if}-->
    </li>
  </ul>
</div>
