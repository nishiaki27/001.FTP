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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA    02111-1307, USA.
 *}-->
<!--▼HEADER-->
<div id="head_box">

<!--▼head-->
<div id="head">
<!--▼wrap-->
<div class="wrap">

<!--▼h1・サブメニュー-->
<div class="clear pb10">
<h1>
<!--{if $smarty.get.maker_name == "daikin" }-->ダイキン <!--{elseif $smarty.get.maker_name == "toshiba" }-->東芝 <!--{elseif $smarty.get.maker_name == "mitsubishidenki" }-->三菱電機 <!--{elseif $smarty.get.maker_name == "hitachi" }-->日立 <!--{elseif $smarty.get.maker_name == "jyuko" }--> 三菱重工 <!--{elseif $smarty.get.maker_name == "panasonic" }-->パナソニック <!--{/if}-->
<!--{if $tpl_title==="業務用エアコン(馬力別)"}-->業務用エアコン　<!--{/if}-->
<!--{strip}-->
  <!--{if $arrSearchData.name|strlen >= 1}-->
<!--{$arrSearchData.name}-->　
<!--{foreach from=$arrTitlePath item=Title}-->
<!--{$Title}-->
<!--{/foreach}-->｜
<!--{elseif $tpl_subtitle|strlen >= 1}-->
<!--{if $arrTitlePath}-->
<!--{foreach from=$arrTitlePath item=Title}-->
<!--{$Title}-->
<!--{/foreach}-->｜
<!--{elseif $arrProduct.comment4|strlen >= 1}-->
<!--{foreach from=$arrTitlePath item=Title}-->
 <!--{$Title}-->
    <!--{/foreach}-->
    <!--{$arrProduct.comment4}--> <!--{$arrProduct.name|replace:"東芝 ":"東芝 業務用エアコン "}-->
            <!--{if $arrSearchData.category_id >=1 && $arrSearch.name == "部材" || $arrProduct.product_id >= 1000000}-->
            　部材　
            <!--{elseif $arrSearchData.category_id >=1100000 || $arrProduct.product_id >= 900000}-->
            　産業用除湿機　
            <!--{elseif $arrSearchData.category_id >=200000 || $arrProduct.product_id >= 500000}-->
            　ハウジングエアコン　<!--{else}-->
            <!--{/if}-->
            <!--{$tpl_middleTitle}-->｜
    <!--{/if}-->
    
    <!--{else}-->
    <!--{if $title_parts|strlen >= 1}-->
    <!--{$title_parts|h}-->｜
    <!--{else}-->
    <!--{if $tpl_title|strlen >= 1}-->
    <!--{$tpl_title}-->｜
    <!--{else}-->
    業務用エアコンのことなら、
    <!--{/if}-->
    <!--{/if}-->
    <!--{/if}--><!--{$arrSiteInfo.shop_name}-->
<!--{/strip}--></h1>

<div id="lnavi">
<form name="login_form" id="login_form" method="post" action="<!--{$smarty.const.HTTPS_URL}-->frontparts/login_check.php" onSubmit="return fnCheckLogin('login_form')">
<input type="hidden" name="mode" value="login" />
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" /> <input type="hidden" name="url" value="<!--{$smarty.server.PHP_SELF|h}-->" />
<ul>
<li id="h_privacy"><a href="<!--{$smarty.const.HTTP_URL}-->privacy.php" title="プライバシーポリシー">プライバシーポリシー</a></li>
<!--{if $smarty.session.customer|@count > 0}-->
<li id="h_newentry"><a href="<!--{$smarty.const.HTTPS_URL}-->mypage/login.php">MYページ</a></li>
<li id="h_login"><a href="<!--{$smarty.server.PHP_SELF|escape}-->" onClick="fnFormModeSubmit('login_form', 'logout', '', ''); return false;">ログアウト</a></li>
<!--{else}-->
<li id="h_login"><a href="<!--{$smarty.const.URL_MYPAGE_TOP}-->" title="ログイン">ログイン</a></li>
<li id="h_newentry"><a href="<!--{$smarty.const.HTTPS_URL}-->entry/" title="新規会員登録">新規会員登録</a></li>
<!--{/if}-->
</ul>
</form>
</div>

</div>
<!--▲h1・サブメニュー-->

<!--▼ロゴ・お問合せ-->
<div class="clear">

<div class="logo"><a href="https://www.tokyo-aircon.net/" title="空調センター"><img src="/images/h_logo03.png" alt="空調センター" title="空調センター"></a></div>
<!--▼ スマホメニューボタン -->
<div class="navToggle">
    <span></span><span></span><span></span><span>menu</span>
</div>
<!--▲ スマホメニューボタン -->

<div class="box_R">
      
<div class="tel"><a href="tel:0120666273"><img src="/images/h_tel.png" alt="フリーダイヤル　0120-666-273" width="240" height="48" title="フリーダイヤル　0120-666-273 業務用エアコンのプロが全力でサポート"><br><div class="h_time">受付時間　9:00～18:00（土日祝は除く）</div></a>
</div>
<div class="btnarea">
<a href="<!--{$smarty.const.HTTPS_URL}-->contact/" title="お問い合わせ"><img src="/images/common/top-inq-bt01.png" alt="お問い合わせ" width="180" height="44" class="mb5"></a>
</div>
</div>

</div>
<!--▲ロゴ・お問合せ-->
<!-- ▼sp nav -->
<nav class="globalMenuSp">
    <ul>
        <li><a href="<!--{$smarty.const.HTTP_URL}-->tenpo_faq.php">ご利用ガイド</a></li>
        <li><a href="<!--{$smarty.const.HTTP_URL}-->tenpo_faq3.php">お支払い方法</a></li>
        <li><a href="<!--{$smarty.const.HTTP_URL}-->tenpo_faq2.php">配送方法</a></li>
        <li><a href="<!--{$smarty.const.HTTP_URL}-->law.php">特定商取引法</a></li>
        <li><a href="<!--{$smarty.const.HTTP_URL}-->review_list.php?review_page=1">お客様の声</a></li>
        <li><a href="<!--{$smarty.const.HTTP_URL}-->faq.php">よくある質問</a></li>
        <li><a href="<!--{$smarty.const.HTTP_URL}-->about_us.php">会社概要</a></li>
        <li><a href="<!--{$smarty.const.HTTP_URL}-->privacy.php">プライバシーポリシー</a></li>
    </ul>
</nav>
<!-- ▲sp nav -->

<!--▼検索-->
<div id="top_search">
<p>商品簡単検索</p>
<!--検索フォーム-->
<form action="/products/list.php" method="get" id="search_form" name="search_form">

<input type="hidden" value="c25eeb42f010e26a387fb72e9a2a8c16cecc6eb4" name="transactionid">

<input type="hidden" value="price" name="orderby">

<input type="text" name="name" value="<!--{$smarty.get.name|h}-->" placeholder="型番、メーカーなど">

<input type="image" onmouseover="chgImgImageSubmit('/images/common/top-search-bt01.png',this)" onmouseout="chgImgImageSubmit('/images/common/top-search-bt01.png',this)" src="/images/common/top-search-bt01.png" alt="検索する" name="search" data-popupalt-original-title="null" title="検索する">
</form>
</div>
<!--▲検索-->

</div>
<!--▲wrap-->
</div>
<!--▲head-->

<!--▼ヘッダーナビ-->
<div id="gnavi">
<div id="menu1">
<ul>
<li id="start"><a href="https://www.tokyo-aircon.net/" title="TOP">TOP</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->tenpo_faq.php" title="ご利用ガイド">ご利用ガイド</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->tenpo_faq3.php" title="お支払い方法 ">お支払い方法 </a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->tenpo_faq2.php" title="配送方法">配送方法</a></li>
<!--<li id="g-after"><a href="<!--{$smarty.const.HTTP_URL}-->tenpo_faq6.php" title="アフターサービス">アフターサービス</a></li>-->
<li><a href="<!--{$smarty.const.HTTP_URL}-->law.php" title="特定商取引法に関する表記">特定商取引法</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->review_list.php?review_page=1" title="お客様の声">お客様の声</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->faq.php" title="よくある質問">よくある質問</a></li>
<li id="end"><a href="<!--{$smarty.const.HTTP_URL}-->about_us.php" title="会社概要">会社概要</a></li>
</ul>
</div>
<!-- ▼メニュー2段目 -->
<div class="nav_low">
	<div class="nav_low_in clearfix">
		<ul class="menu_s">
				<p class="tit">業務用エアコン</p>
		    <li class="menu__mega">
		       形状から選ぶ
		        <ul class="menu__second-level keijyo">
								<li class="hp01"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_tenkase4.html?op_4=1">天井カセット形4方向</a></li>
								<li class="hp02"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_compact.html?op_4=1">天井カセット形コンパクト</a></li>
								<li class="hp03"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_tenturi.html?op_4=1">天井吊形</a></li>
								<li class="hp04"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_kabekake.html?op_4=1">壁掛形</a></li>
								<li class="hp05"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_yukaoki.html?op_4=1">床置形</a></li>
								<li class="hp06"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_builtin.html?op_4=1">ビルトイン形</a></li>
								<li class="hp07"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_duct.html?op_4=1">ダクト形</a></li>
								<li class="hp08"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_tenkase2.html?op_4=1">天井カセット形2方向</a></li>
								<li class="hp09"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_tenkase1.html?op_4=1">天井カセット形1方向</a></li>
								<li class="hp010"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_wonderful.html?op_4=1">天吊自在形</a></li>
		      <li class="hp011"><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_chubo.html?op_4=1">厨房用</a></li>
              <li class="hp012"><a href="<!--{$smarty.const.HTTP_URL}-->buzai/">エアコン部材</a></li>      
		        </ul>
		    </li>
		    <li class="menu__mega secchi">
		        設置場所から選ぶ
		        <ul class="menu__second-level secchi">
								<li class="hp013"><a href="<!--{$smarty.const.HTTP_URL}-->location_office.html">事務所系</a></li>
								<li class="hp014"><a href="<!--{$smarty.const.HTTP_URL}-->location_restaurant.html">飲食店</a></li>
								<li class="hp015"><a href="<!--{$smarty.const.HTTP_URL}-->location_shop.html">商店・店舗</a></li>
                                <li class="hp018"><a href="<!--{$smarty.const.HTTP_URL}-->location_beautysalon.html">理・美容室</a></li>
                                <li class="hp019"><a href="<!--{$smarty.const.HTTP_URL}-->location_hospital.html">病院・医院</a></li>
								<li class="hp016"><a href="<!--{$smarty.const.HTTP_URL}-->location_factory.html">工場</a></li>
								<li class="hp017"><a href="<!--{$smarty.const.HTTP_URL}-->location_workplace.html">倉庫・作業場</a></li>
								<li class="hp020"><a href="<!--{$smarty.const.HTTP_URL}-->location_school.html">学校関係</a></li>
								<li class="hp021"><a href="<!--{$smarty.const.HTTP_URL}-->location_hotel.html">宿泊施設</a></li>
								<li class="hp022"><a href="<!--{$smarty.const.HTTP_URL}-->location_other.html">その他</a></li>
		            
		        </ul>
		    </li>
		    <li class="menu__mega maker">
		        メーカーから選ぶ
		        <ul class="menu__second-level maker">
								<li class="hp023"><a href="<!--{$smarty.const.HTTP_URL}-->maker_daikin.html?op_4=1">ダイキン</a></li>
								<li class="hp024"><a href="<!--{$smarty.const.HTTP_URL}-->maker_toshiba.html?op_4=1">東芝</a></li>
								<li class="hp025"><a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishidenki.html?op_4=1">三菱電機</a></li>
								<li class="hp026"><a href="<!--{$smarty.const.HTTP_URL}-->maker_hitachi.html?op_4=1">日立</a></li>
								<li class="hp027"><a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishijyuko.html?op_4=1">三菱重工</a></li>
								<li class="hp028"><a href="<!--{$smarty.const.HTTP_URL}-->maker_panasonic.html?op_4=1">パナソニック</a></li>
		        </ul>
		    </li>    
		    <li class="menu__mega bariki">
		        馬力から選ぶ
		        <ul class="menu__second-level bariki">
								<li class="hp029"><a href="<!--{$smarty.const.HTTP_URL}-->power_1_5hp.html">1.5馬力</a></li>
								<li class="hp030"><a href="<!--{$smarty.const.HTTP_URL}-->power_1_8hp.html">1.8馬力</a></li>
								<li class="hp031"><a href="<!--{$smarty.const.HTTP_URL}-->power_2hp.html">2馬力</a></li>
								<li class="hp032"><a href="<!--{$smarty.const.HTTP_URL}-->power_2_3hp.html">2.3馬力</a></li>
								<li class="hp033"><a href="<!--{$smarty.const.HTTP_URL}-->power_2_5hp.html">2.5馬力</a></li>
								<li class="hp034"><a href="<!--{$smarty.const.HTTP_URL}-->power_3hp.html">3馬力</a></li>
								<li class="hp035"><a href="<!--{$smarty.const.HTTP_URL}-->power_4hp.html">4馬力</a></li>
                                <li class="hp036"><a href="<!--{$smarty.const.HTTP_URL}-->power_5hp.html">5馬力</a></li>
                                <li class="hp037"><a href="<!--{$smarty.const.HTTP_URL}-->power_6hp.html">6馬力</a></li>
                                <li class="hp038"><a href="<!--{$smarty.const.HTTP_URL}-->power_8hp.html">8馬力</a></li>
                                <li class="hp039"><a href="<!--{$smarty.const.HTTP_URL}-->power_10hp.html">10馬力</a></li>
                                <li class="hp040"><a href="<!--{$smarty.const.HTTP_URL}-->power_12hp.html">12馬力</a></li>
		        </ul>
		    </li> 
	    </ul>
        <ul class="menu_l">
				<p class="tit">その他商品</p>
		    <li class="menu__mega keijyo">
		        カテゴリから選ぶ
		        <ul class="menu__second-level other">
								<li class="hp041"><a href="<!--{$smarty.const.HTTP_URL}-->housing/">ハウジングエアコン</a></li>
								<li class="hp042"><a href="<!--{$smarty.const.HTTP_URL}-->joshitsuki/">産業用除湿機</a></li>
								<li class="hp043"><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon.php">設備用エアコン</a></li>
								<li class="hp044"><a href="<!--{$smarty.const.HTTP_URL}-->building.php">ビル用エアコン</a></li>
								<li class="hp045"><a href="<!--{$smarty.const.HTTP_URL}-->buzai/">エアコン用部材</a></li>
								<li class="hp046"><a href="<!--{$smarty.const.HTTP_URL}-->kanrei_aircon.php">寒冷地向けエアコン</a></li>
                                <li class="hp047"><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=999998&orderby=price">お見積り商品</a></li>
		            
		        </ul>
		    </li>
		    
	    </ul>
			<!-- //ここまでドロップダウンメニュー -->

		
	</div>
	<!-- //nav_low_in -->
</div>
<!-- ▲メニュー2段目 -->
</div>
<!--▲ヘッダーナビ-->

</div>
<!--▲HEADER-->