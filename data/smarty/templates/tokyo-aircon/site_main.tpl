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
<body>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KVV7GR"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KVV7GR');</script>
<!-- End Google Tag Manager -->

<!--{* A8タグ表示用 *}-->
<!--{if "sfPrintA8Tag"|function_exists === TRUE}-->
    <!--{include file=`$smarty.const.MODULE_REALDIR`mdl_a8/print_a8_tag.tpl}-->
<!--{/if}-->

<!--{$GLOBAL_ERR}-->
<noscript>
    <p>JavaScript を有効にしてご利用下さい.</p>
</noscript>

<div class="frame_outer">
    <a name="top" id="top"></a>

    <!--{* ▼HeaderHeaderTop COLUMN*}-->
    <!--{if $arrPageLayout.HeaderTopNavi|@count > 0}-->
        <div id="headertopcolumn">
            <!--{* ▼上ナビ *}-->
            <!--{foreach key=HeaderTopNaviKey item=HeaderTopNaviItem from=$arrPageLayout.HeaderTopNavi}-->
                <!-- ▼<!--{$HeaderTopNaviItem.bloc_name}--> -->
                <!--{if $HeaderTopNaviItem.php_path != ""}-->
                    <!--{include_php file=$HeaderTopNaviItem.php_path items=$HeaderTopNaviItem}-->
                <!--{else}-->
                    <!--{include file=$HeaderTopNaviItem.tpl_path items=$HeaderTopNaviItem}-->
                <!--{/if}-->
                <!-- ▲<!--{$HeaderTopNaviItem.bloc_name}--> -->
            <!--{/foreach}-->
            <!--{* ▲上ナビ *}-->
        </div>
    <!--{/if}-->
    <!--{* ▲HeaderHeaderTop COLUMN*}-->
    <!--{* ▼HEADER *}-->
    <!--{if $arrPageLayout.header_chk != 2}-->
        <!--{include file= $header_tpl}-->
    <!--{/if}-->
    <!--{* ▲HEADER *}-->



<!--{if $smarty.server.PHP_SELF|mb_strpos:"/user_data/lp" !== FALSE}-->
<!--{* ▼LP CONTENTS *}-->

<div class="clearfix">

<!--{* ▼メイン *}-->
<!--{include file=$tpl_mainpage}-->
<!--{* ▲メイン *}-->

</div>
<!--{* ▲LP CONTENTS *}-->

<!--{else}-->


    <!--{* ▼CONTENTS *}-->
    <div id="container" class="clearfix">

        <!--{* ▼TOP COLUMN*}-->
        <!--{if $arrPageLayout.TopNavi|@count > 0}-->
            <div id="topcolumn">
                <!--{* ▼上ナビ *}-->
                <!--{foreach key=TopNaviKey item=TopNaviItem from=$arrPageLayout.TopNavi}-->
                    <!-- ▼<!--{$TopNaviItem.bloc_name}--> -->
                    <!--{if $TopNaviItem.php_path != ""}-->
                        <!--{include_php file=$TopNaviItem.php_path items=$TopNaviItem}-->
                    <!--{else}-->
                        <!--{include file=$TopNaviItem.tpl_path items=$TopNaviItem}-->
                    <!--{/if}-->
                    <!-- ▲<!--{$TopNaviItem.bloc_name}--> -->
                <!--{/foreach}-->
                <!--{* ▲上ナビ *}-->
            </div>
        <!--{/if}-->
        <!--{* ▲TOP COLUMN*}-->

        <!--{* ▼LEFT COLUMN *}-->
        <!--{if $arrPageLayout.LeftNavi|@count > 0}-->
            <div id="leftcolumn" class="side_column order4">
                <!--{* ▼左ナビ *}-->
                <!--{foreach key=LeftNaviKey item=LeftNaviItem from=$arrPageLayout.LeftNavi}-->
                    <!-- ▼<!--{$LeftNaviItem.bloc_name}--> -->
                    <!--{if $LeftNaviItem.php_path != ""}-->
                        <!--{include_php file=$LeftNaviItem.php_path items=$LeftNaviItem}-->
                    <!--{else}-->
                        <!--{include file=$LeftNaviItem.tpl_path items=$LeftNaviItem}-->
                    <!--{/if}-->
                    <!-- ▲<!--{$LeftNaviItem.bloc_name}--> -->
                <!--{/foreach}-->
                <!--{* ▲左ナビ *}-->
                
            </div>
        <!--{/if}-->
        <!--{* ▲LEFT COLUMN *}-->

        <!--{* ▼CENTER COLUMN *}-->
        <div 
            <!--{if $tpl_column_num == 3}-->
                id="three_maincolumn"
            <!--{elseif $tpl_column_num == 2}-->
                <!--{if $arrPageLayout.LeftNavi|@count == 0}-->
                    id="two_maincolumn_left"
                <!--{else}-->
                    id="two_maincolumn_right"
                <!--{/if}-->
            <!--{elseif $tpl_column_num == 1}-->
                id="one_maincolumn"
            <!--{/if}-->
            class="main_column order1 1column"
        >
            <!--{* ▼メイン上部 *}-->
            <!--{if $arrPageLayout.MainHead|@count > 0}-->
                <!--{foreach key=MainHeadKey item=MainHeadItem from=$arrPageLayout.MainHead}-->
                    <!-- ▼<!--{$MainHeadItem.bloc_name}--> -->
                    <!--{if $MainHeadItem.php_path != ""}-->
                        <!--{include_php file=$MainHeadItem.php_path items=$MainHeadItem}-->
                    <!--{else}-->
                        <!--{include file=$MainHeadItem.tpl_path items=$MainHeadItem}-->
                    <!--{/if}-->
                    <!-- ▲<!--{$MainHeadItem.bloc_name}--> -->
                <!--{/foreach}-->
            <!--{/if}-->
            <!--{* ▲メイン上部 *}-->

            <!--{* ▼メイン *}-->
            <!--{include file=$tpl_mainpage}-->
            <!--{* ▲メイン *}-->

            <!--{* ▼メイン下部 *}-->
            <!--{if $arrPageLayout.MainFoot|@count > 0}-->
                <!--{foreach key=MainFootKey item=MainFootItem from=$arrPageLayout.MainFoot}-->
                    <!-- ▼<!--{$MainFootItem.bloc_name}--> -->
                    <!--{if $MainFootItem.php_path != ""}-->
                        <!--{include_php file=$MainFootItem.php_path items=$MainFootItem}-->
                    <!--{else}-->
                        <!--{include file=$MainFootItem.tpl_path items=$MainFootItem}-->
                    <!--{/if}-->
                    <!-- ▲<!--{$MainFootItem.bloc_name}--> -->
                <!--{/foreach}-->
            <!--{/if}-->
            <!--{* ▲メイン下部 *}-->
        </div>
        <!--{* ▲CENTER COLUMN *}-->

        <!--{* ▼RIGHT COLUMN *}-->
        <!--{if $arrPageLayout.RightNavi|@count > 0}-->
            <div id="rightcolumn" class="side_column">
                <!--{* ▼右ナビ *}-->
                <!--{foreach key=RightNaviKey item=RightNaviItem from=$arrPageLayout.RightNavi}-->
                    <!-- ▼<!--{$RightNaviItem.bloc_name}--> -->
                    <!--{if $RightNaviItem.php_path != ""}-->
                        <!--{include_php file=$RightNaviItem.php_path items=$RightNaviItem}-->
                    <!--{else}-->
                        <!--{include file=$RightNaviItem.tpl_path items=$RightNaviItem}-->
                    <!--{/if}-->
                    <!-- ▲<!--{$RightNaviItem.bloc_name}--> -->
                <!--{/foreach}-->
                <!--{* ▲右ナビ *}-->
            </div>
        <!--{/if}-->
        <!--{* ▲RIGHT COLUMN *}-->

        <!--{* ▼BOTTOM COLUMN*}-->
        <!--{if $arrPageLayout.BottomNavi|@count > 0}-->
            <div id="bottomcolumn">
                <!--{* ▼下ナビ *}-->
                <!--{foreach key=BottomNaviKey item=BottomNaviItem from=$arrPageLayout.BottomNavi}-->
                    <!-- ▼<!--{$BottomNaviItem.bloc_name}--> -->
                    <!--{if $BottomNaviItem.php_path != ""}-->
                        <!--{include_php file=$BottomNaviItem.php_path items=$BottomNaviItem}-->
                    <!--{else}-->
                        <!--{include file=$BottomNaviItem.tpl_path items=$BottomNaviItem}-->
                    <!--{/if}-->
                    <!-- ▲<!--{$BottomNaviItem.bloc_name}--> -->
                <!--{/foreach}-->
                <!--{* ▲下ナビ *}-->
            </div>
        <!--{/if}-->
        <!--{* ▲BOTTOM COLUMN*}-->

    </div></div>
    <!--{* ▲CONTENTS *}-->
<!--{/if}-->



    <!--{* ▼FOOTER *}-->
    <!--{if $arrPageLayout.footer_chk != 2}-->
        <!--{include file=$footer_tpl}-->
    <!--{/if}-->
    <!--{* ▲FOOTER *}-->
    <!--{* ▼FooterBottom COLUMN*}-->
    <!--{if $arrPageLayout.FooterBottomNavi|@count > 0}-->
        <div id="footerbottomcolumn">
            <!--{* ▼上ナビ *}-->
            <!--{foreach key=FooterBottomNaviKey item=FooterBottomNaviItem from=$arrPageLayout.FooterBottomNavi}-->
                <!-- ▼<!--{$FooterBottomNaviItem.bloc_name}--> -->
                <!--{if $FooterBottomNaviItem.php_path != ""}-->
                    <!--{include_php file=$FooterBottomNaviItem.php_path items=$FooterBottomNaviItem}-->
                <!--{else}-->
                    <!--{include file=$FooterBottomNaviItem.tpl_path items=$FooterBottomNaviItem}-->
                <!--{/if}-->
                <!-- ▲<!--{$FooterBottomNaviItem.bloc_name}--> -->
            <!--{/foreach}-->
            <!--{* ▲上ナビ *}-->
        </div>
    <!--{/if}-->
    <!--{* ▲FooterBottom COLUMN*}-->
</div>

<!--{if $smarty.server.PHP_SELF == "/index.php"}-->
<script type="text/javascript" src="<!--{$smarty.const.HTTP_URL}-->js/swiper.min.js"></script>
<script type="text/javascript">
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationBulletRender: function (swiper, index, className) {
            return '<span class="' + className + '">' + ["業務用エアコン購入特典キャンペーン","業務用エアコンが今だけ『１万円引き！』","東芝 業務用エアコンが激安価格！"][index] + '</span>';
		},
        autoplay: 6000,
		speed:1000,
        autoplayDisableOnInteraction: false
    });
</script> 

<!--{/if}-->

</body>
