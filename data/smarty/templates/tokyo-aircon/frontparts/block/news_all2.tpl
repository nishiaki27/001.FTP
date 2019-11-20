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
<div class="boxArea">
<div class="wrap">
<div class="section">
<div class="titlept01">
<div class="bluept01">

<div class="clear">
<p class="title20">設備.comからのお知らせ</p>
</div><!-- /.clear -->

</div>
</div>
<p class="txt">当ウェブサイトの新着・更新情報などをご案内します。</p>
<ul class="newsArea txt"> 
            <!--{section name=data loop=$arrNews max=6}-->
            <!--{assign var="date_array" value="-"|explode:$arrNews[data].news_date_disp}-->
                <li class="clear"><span class="date"><!--{$date_array[0]}-->年<!--{$date_array[1]}-->月<!--{$date_array[2]}-->日</span>  <div class="infoArea">    <span class="blet-link-info">&nbsp;</span><span class="info"><a href="https://www.tokyo-aircon.net/news_all.php?news_id=<!--{$arrNews[data].news_id}-->" target="_self"><!--{$arrNews[data].news_title|h|nl2br|mb_truncate:20:'...':'UTF-8'}--></a>
               </span>  </div></li>
            <!--{/section}-->
            </ul>
<div class="clear">
<p class="rss"><a href="https://www.tokyo-aircon.net/news_all.php">新着情報一覧へ</a></p>
</div><!-- /.clear -->
</div><!-- /.section -->
</div><!-- /.wrap -->
</div><!-- /.boxArea -->