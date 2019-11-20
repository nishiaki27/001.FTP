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
 
 <div class="sp_rightbanner">
<div class="sp_midashi">空調センターの4つの強み</div>
<div id="staff">
<h4>
<a href="<!--{$smarty.const.HTTP_URL}-->staff.php">
<img src="/images/top_right/right_bnr01_sp.png" alt="私たちにお任せ">
<p><span>私達にお任せ</span><br />
プロのスタッフがサポートします</p>
</a>
</h4>
</div>

<div id="deli">
<h4>
<a href="<!--{$smarty.const.HTTP_URL}-->area.php">
<img src="/images/top_right/right_bnr02_sp.png" alt="全国送料0円">
<p><span>全国送料0円</span><br />
スピード発送・配送対応地域</p>
</a>
</h4>
</div>


<div id="voice">
<h4>
<a href="<!--{$smarty.const.HTTP_URL}-->review_list.php?review_page=1">
<img src="/images/top_right/right_bnr03_sp.png" alt="全国送料0円">
<p><span>お客様の声</span><br />
お客様からのご意見を紹介</p>
</a>
</h4>
</div>

<div id="idea">
<h4>
<a href="<!--{$smarty.const.HTTP_URL}-->tenpo_mission2.php">
<img src="/images/top_right/right_bnr04_sp.png" alt="当社の理念">
<p><span>当社の理念</span><br />
満足・安心 全てはお客様の為に</p>
</a>
</h4>
</div>

<div class="sp_midashi clearfix">空調センターおすすめの情報</div>
<p class="osusume"><img src="<!--{$smarty.const.HTTP_URL}-->images/top_right/right_title01.png" alt="空調センターおすすめの情報"></p>

<div id="right_bg1">

    <a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_power.php">
        <div class="sp_osusume1">
        <span class="txt_sub1">最適なエアコンを</span>
        <span class="txt16">能力対応面積表</span>
        <span class="txt_sub2">広さからエアコンを選択</span>
    </div>
    </a>

    <a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_type.php">
        <div class="sp_osusume2">
        <span class="txt_sub1">様々なエアコンの形</span>
        <span class="txt16">形状をご紹介</span>
        <span class="txt_sub2">豊富なラインナップ</span>
        </div>
    </a>

    <a href="<!--{$smarty.const.HTTP_URL}-->maker_catalog.php">
        <div class="sp_osusume3">
        <span class="txt_sub1">大手エアコンメーカー</span>
        <span class="txt16">カタログ情報</span>
        <span class="txt_sub2">最新カタログをご紹介</span>
        </div>
    </a>

    <a href="<!--{$smarty.const.HTTP_URL}-->search_modelnumber.php">
        <div class="sp_osusume4">
        <span class="txt_sub1">製品に関する詳しい情報</span>
        <span class="txt16">製品形式情報</span>
        <span class="txt_sub2">サポートサイトも充実</span>
        </div>
    </a>

    <a href="<!--{$smarty.const.HTTP_URL}-->after_service.php">
        <div class="end sp_osusume5">
        <span class="txt_sub1">サポート体制万全</span>
        <span class="txt16">アフターサービス</span>
        <span class="txt_sub2">お客様と末永くお付合いを</span>
        </div>
    </a>

</div>

</div>
 
<div class="mb15">
 
  <div id="news_topics">
    <h4 class="title5">新着情報<span><a href="<!--{$smarty.const.HTTP_URL}-->news_all.php">新着情報一覧はこちら</a></span></h4>
    <ul>
    				<!--{php}-->$news_c = 0;<!--{/php}--> 
            <!--{section name=data loop=$arrNews max=50}-->
            <!--{if $arrNews[data].voice_flg == 0}-->
            <!--{php}-->if( $news_c < 5 ) {<!--{/php}--> 
            <!--{assign var="date_array" value="-"|explode:$arrNews[data].news_date_disp}-->
                <li class="clear"><span><!--{$date_array[0]}-->年<!--{$date_array[1]}-->月<!--{$date_array[2]}-->日</span><div class="infotxt"><a href="<!--{$smarty.const.HTTP_URL}-->news_all.php?news_id=<!--{$arrNews[data].news_id}-->" target="_self"><!--{$arrNews[data].news_title|h|nl2br|mb_truncate:56:'...':'UTF-8'}--></a></div></li>
                <!--{php}-->$news_c = $news_c + 1;<!--{/php}-->
            <!--{php}-->}<!--{/php}--> 
            <!--{/if}-->
            <!--{/section}-->
      <br class="clr">
    </ul>
  </div>
  <!-- ▲ここまで新着情報 -->

</div>