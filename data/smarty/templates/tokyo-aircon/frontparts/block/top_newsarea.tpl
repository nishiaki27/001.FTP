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
 
<div class="mb15">
  <div class="box_w49_L" id="news_voice">
    <h3 class="style02">お客様の声<span><a href="#">一覧はこちら</a></span></h3>
    <ul>
      <li class="clear"><span>2014年04月01日</span><div class="infotxt">テキストテキストテキスト</div></li>
      <li class="clear"><span>2014年04月01日</span><div class="infotxt">テキストテキストテキスト</div></li>
      <li class="clear"><span>2014年04月01日</span><div class="infotxt">テキストテキストテキスト</div></li>
      <li class="clear"><span>2014年04月01日</span><div class="infotxt">テキストテキストテキスト</div></li>
      <li class="clear"><span>2014年04月01日</span><div class="infotxt">テキストテキストテキスト</div></li>
      <br class="clear">
    </ul>
  </div>
  <!-- ▲ここまでお客様の声 -->
  <div class="box_w49_R" id="news_topics">
    <h3 class="style01">最新ニュース<span><a href="#">ニュース一覧はこちら</a></span></h3>
    <ul> 
            <!--{section name=data loop=$arrNews max=6}-->
            <!--{assign var="date_array" value="-"|explode:$arrNews[data].news_date_disp}-->
                <li class="clear"><span><!--{$date_array[0]}-->年<!--{$date_array[1]}-->月<!--{$date_array[2]}-->日</span><div class="infotxt"><a href="https://www.tokyo-aircon.net/news_all.php?news_id=<!--{$arrNews[data].news_id}-->" target="_self"><!--{$arrNews[data].news_title|h|nl2br|mb_truncate:56:'...':'UTF-8'}--></a></div></li>
            <!--{/section}-->
      
    </ul>
  </div>
<br class="clr">
  <!-- ▲ここまで新着情報 -->
</div>