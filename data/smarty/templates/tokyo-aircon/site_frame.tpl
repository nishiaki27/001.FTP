<!--{printXMLDeclaration}--><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<html xmlns="//www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<title><!--{if $smarty.get.maker_name == "daikin" }-->ダイキン <!--{elseif $smarty.get.maker_name == "toshiba" }-->東芝 <!--{elseif $smarty.get.maker_name == "mitsubishidenki" }-->三菱電機 <!--{elseif $smarty.get.maker_name == "hitachi" }-->日立 <!--{elseif $smarty.get.maker_name == "jyuko" }--> 三菱重工 <!--{elseif $smarty.get.maker_name == "panasonic" }-->パナソニック <!--{/if}-->
<!--{if $tpl_title==="業務用エアコン(馬力別)"}-->業務用エアコン　<!--{/if}-->
<!--{strip}-->
<!--{if $arrSearchData.name|strlen >= 1}-->
<!--{$arrSearchData.name}-->
<!--{foreach from=$arrTitlePath item=Title}-->
<!--{$Title}-->
<!--{/foreach}-->
<!--{elseif $tpl_subtitle|strlen >= 1}-->
<!--{if $arrTitlePath}-->
<!--{foreach from=$arrTitlePath item=Title}-->
<!--{$Title}-->
<!--{/foreach}-->
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
            <!--{$tpl_middleTitle}-->
    <!--{/if}-->
    
    <!--{else}-->
    <!--{if $title_parts|strlen >= 1}-->
    <!--{$title_parts|h}-->
    <!--{else}-->
    <!--{if $tpl_title|strlen >= 1}-->
    <!--{$tpl_title}-->
    <!--{else}-->
    最大81％OFF！日本全国送料無料！
    <!--{/if}-->
    <!--{/if}-->
    <!--{/if}-->｜業務用エアコン専門店 <!--{$arrSiteInfo.shop_name}-->
<!--{/strip}--></title>

<!--160818追記ここから-->
<!--<meta name="viewport" content="width=device-width">
<link rel="stylesheet" media="only screen and (max-device-width:480px)" href="//www.tokyo-aircon.net/user_data/packages/tokyo-aircon/css/main-SP.css">-->
<!--160818追記ここまで-->

<!--canonical161209追記ここから-->
<link rel="canonical" href="https://www.tokyo-aircon.net<!--{$smarty.server.REQUEST_URI|h}-->" />
<!--canonical161209追記ここまで-->

<!--モバイルリンクディスカバリー161209追記ここから-->
<link rel="alternate" media="handheld" href="https://www.tokyo-aircon.net<!--{$smarty.server.REQUEST_URI|h}-->" />
<!--モバイルリンクディスカバリー161209追記ここまで-->
<meta name="viewport" content="width=device-width, initial-scale=1">
 <script type="text/javascript">
if ((navigator.userAgent.indexOf('iPhone') > 0) || navigator.userAgent.indexOf('iPod') > 0 || navigator.userAgent.indexOf('Android') > 0) {
  document.write('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
} else {
  document.write('<meta name="viewport" content="width=980">');
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$smarty.const.CHAR_CODE}-->" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link rel="stylesheet" media="only screen and (max-device-width:480px)" href="<!--{$TPL_URLPATH}-->css/swiper.min.css" />
<link rel="stylesheet" href="<!--{$TPL_URLPATH}-->css/import.css" type="text/css" media="all" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="<!--{$smarty.const.HTTP_URL}-->rss/<!--{$smarty.const.DIR_INDEX_PATH}-->" />
<!--<script type="text/javascript">
jQuery(document).ready(function($) {
    if (window.matchMedia( '(max-width: 484px)' ).matches) {
        $.ajax({
            url: 'path/to/swiper.min.js',
            dataType: 'script',
            cache: false
       });
   
    };
});
</script>-->


<!--{if $tpl_page_category == "abouts"}-->
<!--{if ($smarty.server.HTTPS != "") && ($smarty.server.HTTPS != "off")}-->
<script type="text/javascript" src="https://maps-api-ssl.google.com/maps/api/js?sensor=false"></script>
<!--{else}-->
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
<!--{/if}-->
<!--{/if}-->

<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/css.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/navi.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/win_op.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/site.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/swiper.min2.js" defer></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.cookie.js"></script>
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>


<!--{php}-->
	switch ($_GET[op_4]) {
		case 1:		$op4_query ="標準省エネ";	break;
		case 2:		$op4_query ="超省エネ";		break;
		case 3:		$op4_query ="冷房専用";		break;
    default:
	}

	switch ($_GET[op_6]) {
	    case 1:
	      if($type == 78){
	      $op6_query ="";
	      }elseif($type > 679 && $type < 764){
	      $op6_query ="";
	      }else{
	      $op6_query ="ワイヤード";
	      }
	      break;
	    case 2:
	      $op6_query ="ワイヤレス";
	      break;
	    default:
	}


<!--{/php}-->
<!--{assign var=key value= $key}-->

<!--{if $arrPageLayout.author|strlen >= 1}-->
    <meta name="author" content="<!--{$arrPageLayout.author|h}-->" />
<!--{/if}-->

<!-- description_bak：<!--{if $arrPageLayout.description|strlen >= 1}--><meta name="description" content="<!--{$arrPageLayout.description|h}-->" />	<!--{else}--><meta name="description" content="<!--{$arrProduct.comment2|replace:"<br>":" "|replace:"  ":" "}-->" /><!--{/if}-->-->

<!--{if $arrProduct.comment4|strlen >= 1}-->
	<meta name="description" content="<!--{$arrProduct.comment4}--><!--{$tpl_middleTitle}-->|<!--{$arrProduct.name|replace:"東芝 ":"東芝 業務用エアコン "}-->|販売価格:<!--{$arrProduct.price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->|業務用エアコン専門通販サイトの空調センターです。業務用エアコンが最大81%OFF！ハウジングエアコン、産業用除湿機、ビル用エアコン、設備用エアコン、寒冷地用エアコン、業務用エアコン部材も取り扱っております。実績多数、お見積りも無料で迅速対応！日本全国送料無料で、アフターサービスも万全！" />
<!--{elseif $tpl_title==="業務用エアコン(形状別)"}-->
    <meta name="description" content="><!--{$title_parts}-->｜業務用エアコン専門通販サイトの空調センターです。業務用エアコンが最大81%OFF！ハウジングエアコン、産業用除湿機、ビル用エアコン、設備用エアコン、寒冷地用エアコン、業務用エアコン部材も取り扱っております。実績多数、お見積りも無料で迅速対応！日本全国送料無料で、アフターサービスも万全！" />
    <!--{elseif $tpl_title==="業務用エアコン(設置場所)"}-->
    <meta name="description" content="><!--{$title_parts}-->｜業務用エアコン専門通販サイトの空調センターです。業務用エアコンが最大81%OFF！ハウジングエアコン、産業用除湿機、ビル用エアコン、設備用エアコン、寒冷地用エアコン、業務用エアコン部材も取り扱っております。実績多数、お見積りも無料で迅速対応！日本全国送料無料で、アフターサービスも万全！" />
    <!--{elseif $tpl_title==="業務用エアコン(馬力別)"}-->
    <meta name="description" content="><!--{$title_parts}-->｜業務用エアコン専門通販サイトの空調センターです。業務用エアコンが最大81%OFF！ハウジングエアコン、産業用除湿機、ビル用エアコン、設備用エアコン、寒冷地用エアコン、業務用エアコン部材も取り扱っております。実績多数、お見積りも無料で迅速対応！日本全国送料無料で、アフターサービスも万全！" />
<!--{elseif $arrPageLayout.description|strlen >= 1}-->
<meta name="description" content="<!--{$arrPageLayout.description|h}-->｜業務用エアコン専門通販サイトの空調センターです。業務用エアコンが最大81%OFF！ハウジングエアコン、産業用除湿機、ビル用エアコン、設備用エアコン、寒冷地用エアコン、業務用エアコン部材も取り扱っております。実績多数、お見積りも無料で迅速対応！日本全国送料無料で、アフターサービスも万全！" />
<!--{elseif $tpl_title==="商品一覧ページ"}-->
<meta name="description" content="商品一覧｜業務用エアコン専門通販サイトの空調センターです。業務用エアコンが最大81%OFF！ハウジングエアコン、産業用除湿機、ビル用エアコン、設備用エアコン、寒冷地用エアコン、業務用エアコン部材も取り扱っております。実績多数、お見積りも無料で迅速対応！日本全国送料無料で、アフターサービスも万全！" />
<!--{else}-->
<meta name="description" content="<!--{if $smarty.get.maker_name == "daikin" }-->ダイキン <!--{elseif $smarty.get.maker_name == "toshiba" }-->東芝 <!--{elseif $smarty.get.maker_name == "mitsubishidenki" }-->三菱電機 <!--{elseif $smarty.get.maker_name == "hitachi" }-->日立 <!--{elseif $smarty.get.maker_name == "jyuko" }--> 三菱重工 <!--{elseif $smarty.get.maker_name == "panasonic" }-->パナソニック <!--{/if}--><!--{if $title_parts|strlen >= 1}--><!--{$title_parts}--><!--{else}--><!--{if $tpl_title|strlen >= 1}-->
<!--{$tpl_title}-->｜<!--{else}--><!--{/if}--><!--{/if}-->業務用エアコン専門通販サイトの空調センターです。業務用エアコンが最大81%OFF！ハウジングエアコン、産業用除湿機、ビル用エアコン、設備用エアコン、寒冷地用エアコン、業務用エアコン部材も取り扱っております。実績多数、お見積りも無料で迅速対応！日本全国送料無料で、アフターサービスも万全！" />
<!--{/if}-->

<!--{if $tpl_title==="業務用エアコン(形状別)"}-->
<meta name="keywords" content="業務用エアコン<!--{$title_parts|replace:"形状別":""}-->,<!--{$arrPageLayout.keyword|h}-->" />
<!--{elseif $tpl_title==="業務用エアコン(馬力別)"}-->
<meta name="keywords" content="業務用エアコン<!--{$title_parts|replace:"馬力別":""}-->,<!--{$arrPageLayout.keyword|h}-->" />
<!--{elseif $tpl_title==="業務用エアコン(設置場所)"}-->
<meta name="keywords" content="<!--{$title_parts}-->,<!--{$arrPageLayout.keyword|h}-->" />
<!--{elseif $tpl_title==="業務用エアコンの形状について"}-->
<meta name="keywords" content="形状のご紹介,天井埋込カセット形4方向,天井埋込カセット形コンパクト,天井埋込カセット形2方向,天井埋込カセット形1方向,天吊形,壁掛形,床置形,厨房用,ビルトイン形,ダクト形,大型店舗用,天吊自在形,空調センター" />
<!--{elseif $tpl_title==="商品一覧ページ"}-->
<meta name="keywords" content="<!--{$arrPageLayout.keyword|h}-->" />
<!--{elseif $tpl_title==="商品詳細ページ"}-->
<meta name="keywords" content="<!--{$arrProduct.comment4}-->,<!--{$arrProduct.name|strip_tags:false|replace:" ":","|replace:"【":","|replace:"】":","}-->,<!--{$arrPageLayout.keyword|h}-->" />
<!--{elseif $tpl_title==="ハウジングエアコン"}-->
<meta name="keywords" content="ハウジングエアコン,<!--{if $smarty.get.maker_name == "daikin" }-->ダイキン<!--{elseif $smarty.get.maker_name == "toshiba" }-->東芝<!--{elseif $smarty.get.maker_name == "mitsubishidenki" }-->三菱電機<!--{elseif $smarty.get.maker_name == "hitachi" }-->日立<!--{elseif $smarty.get.maker_name == "jyuko" }--> 三菱重工 <!--{elseif $smarty.get.maker_name == "panasonic" }-->パナソニック<!--{else}-->ダイキン,三菱電機,日立,パナソニック,三菱重工<!--{/if}--><!--{$arrProduct.name|strip_tags:false|replace:" ":","|replace:"【":","|replace:"】":","}-->,<!--{$arrPageLayout.keyword|h}-->" />
<!--{elseif $arrPageLayout.keyword|strlen >= 1}-->
<meta name="keywords" content="<!--{$arrPageLayout.keyword|h}-->" />
<!--{else}-->
<meta name="keywords" content="<!--{$arrProduct.comment4}-->,<!--{$arrProduct.name|strip_tags:false|replace:" ":","|replace:"【":","|replace:"】":","}-->,<!--{$arrPageLayout.keyword|h}-->" />

<!--↓comment3の内容を表示※半角スペースを,に変換-->
<!--<meta name="keywords" content="<!--{$arrProduct.comment4}-->,<!--{$arrProduct.comment3|strip_tags:false|replace:" ":","|replace:"【":","|replace:"】":","}-->" />-->
<!--{/if}-->

<script type="text/javascript">//<![CDATA[
    <!--{$tpl_javascript}-->
    $(function(){
        <!--{$tpl_onload}-->
    });
//]]>
</script>
<!--▼ナビ固定用160421-->
<!--<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>-->
<script type="text/javascript">
$(function(){
  $('.toggle_switch').on('click',function(){
    $(this).toggleClass('open');
    $(this).next('.toggle_contents').slideToggle();
  });
});
</script>
<script type="text/javascript">
jQuery(function($) {
	var nav = $('#gnavi'),
	offset = nav.offset();
	$(window).scroll(function () {
	  if($(window).scrollTop() > offset.top) {
	    nav.addClass('fixed');
	  } else {
	    nav.removeClass('fixed');
	  }
	});
});
</script>
<!--▲ナビ固定用160421-->

<!--▼ページトップ-->
<script type="text/javascript">
$(function() {
    var showFlag = false;
    var topBtn = $('.pagetop');    
    topBtn.css('bottom', '-100px');
    var showFlag = false;
    //スクロールが100に達したらボタン表示
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            if (showFlag == false) {
                showFlag = true;
                topBtn.stop().animate({'bottom' : '20px'}, 200); 
            }
        } else {
            if (showFlag) {
                showFlag = false;
                topBtn.stop().animate({'bottom' : '-100px'}, 200); 
            }
        }
    });
    //スクロールしてトップ
    topBtn.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});
</script>
<!--▲ページトップ-->
<script type="text/javascript">
$(function() {
    $('.navToggle').click(function() {
        $(this).toggleClass('active');
 
        if ($(this).hasClass('active')) {
            $('.globalMenuSp').addClass('active');
        } else {
            $('.globalMenuSp').removeClass('active');
        }
    });
});
</script>

<!--{* ▼Head COLUMN*}-->
<!--{if $arrPageLayout.HeadNavi|@count > 0}-->
    <!--{* ▼上ナビ *}-->
    <!--{foreach key=HeadNaviKey item=HeadNaviItem from=$arrPageLayout.HeadNavi}-->
        <!--{* ▼<!--{$HeadNaviItem.bloc_name}--> ここから*}-->
        <!--{if $HeadNaviItem.php_path != ""}-->
            <!--{include_php file=$HeadNaviItem.php_path}-->
        <!--{else}-->
            <!--{include file=$HeadNaviItem.tpl_path}-->
        <!--{/if}-->
        <!--{* ▲<!--{$HeadNaviItem.bloc_name}--> ここまで*}-->
    <!--{/foreach}-->
    <!--{* ▲上ナビ *}-->
<!--{/if}-->
<!--{* ▲Head COLUMN*}-->

</head>

<!-- ▼BODY部 スタート -->
<!--{include file='./site_main.tpl'}-->
<!-- ▲BODY部 エンド -->

</html>
