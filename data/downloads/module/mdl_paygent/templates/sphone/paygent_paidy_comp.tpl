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
<!--{*** EC-CUBE 2.11.1以前用と2.11.2以降用の2種類のテンプレートを定義しています。 ***}-->
<!--{if preg_match('/^2\.11\.[0-1]$/', $smarty.const.ECCUBE_VERSION)}-->
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<!--▼CONTENTS-->
<div id="under02column">
    <div id="under02column_shopping">
        <h2 class="title"><!--{$tpl_title|h}--></h2>
        <div id="completetext">
            <em><!--{$arrInfo.shop_name|h}-->の商品をご購入いただき、ありがとうございました。</em>
            <p>Paidyからの通知受付後に注文完了メールを送信致します。</p>
            <p>注文完了メールの送信をもって注文確定となります。</p>
            <br>
            <p>今後ともご愛顧賜りますようよろしくお願い申し上げます。</p>
            <p><!--{$arrInfo.shop_name|h}--><br />
                TEL：<!--{$arrInfo.tel01}-->-<!--{$arrInfo.tel02}-->-<!--{$arrInfo.tel03}--> <!--{if $arrInfo.business_hour != ""}-->（受付時間/<!--{$arrInfo.business_hour}-->）<!--{/if}--><br />
                E-mail：<a href="mailto:<!--{$arrInfo.email02|escape:'hex'}-->"><!--{$arrInfo.email02|escape:'hexentity'}--></a>
            </p>
        </div>
        <div class="tblareabtn"><a class="spbtn spbtn-medeum" href="<!--{$smarty.const.TOP_URLPATH}-->">トップページへ</a></div>
    </div>
</div>
<!--▲CONTENTS-->
<!--{else}-->
<!--▼CONTENTS-->
<section id="undercolumn">
    <h2 class="title"><!--{$tpl_title|h}--></h2>

    <div class="thankstext">
        <p>ご注文ありがとうございました。</p>
    </div>
    <hr>
    <div id="completetext">
        <p>Paidyからの通知受付後に注文完了メールを送信致します。</p>
        <p>注文完了メールの送信をもって注文確定となります。</p>
        <br>
        <p>今後ともご愛顧賜りますようよろしくお願い申し上げます。</p>
        <div class="btn_area">
            <a href="<!--{$smarty.const.TOP_URLPATH}-->" class="btn_toppage btn_sub" rel="external">トップページへ</a>
        </div>
    </div>
    <hr>
    <div class="shopInformation">
        <p><!--{$arrInfo.shop_name|h}--></p>
        <p>TEL：<!--{$arrInfo.tel01}-->-<!--{$arrInfo.tel02}-->-<!--{$arrInfo.tel03}--><br />
            E-mail：<a href="mailto:<!--{$arrInfo.email02|escape:'hex'}-->" rel="external"><!--{$arrInfo.email02|escape:'hexentity'}--></a></p>
    </div>
</section>
<!--{include file= 'frontparts/search_area.tpl'}-->
<!--{/if}-->