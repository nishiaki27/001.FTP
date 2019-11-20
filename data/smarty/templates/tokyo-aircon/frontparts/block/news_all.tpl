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

<h2 class="title1"><span>新着情報一覧</span></h2>
<div id="news_Alltopics">
<!--{if $smarty.get.news_id == ""}-->
<ul>
<!--{section name=data loop=$arrNews}-->
<!--{if $arrNews[data].voice_flg == 0}-->
	<!--{assign var="date_array" value="-"|explode:$arrNews[data].news_date_disp}-->
<li class="clr">
<span><!--{$date_array[0]}-->年<!--{$date_array[1]}-->月<!--{$date_array[2]}-->日</span>
<div class="infotxt">
<a href="https://www.tokyo-aircon.net/news_all.php?news_id=<!--{$arrNews[data].news_id}-->" target="_self"><!--{$arrNews[data].news_title|h|nl2br}--></a>
</div>
</li><br class="clr">
<!--{/if}-->
<!--{/section}-->
</ul>
</div>       
	<!--{else}-->

            <!--{section name=data loop=$arrNews}-->
            <!--{if $arrNews[data].news_id == $smarty.get.news_id}-->
            <!--{assign var="date_array" value="-"|explode:$arrNews[data].news_date_disp}-->

<div class="clr mb50">
<h4><span><!--{$date_array[0]}-->年<!--{$date_array[1]}-->月<!--{$date_array[2]}-->日</span>
                
                        <!--{if $arrNews[data].news_url}--> href="<!--{$arrNews[data].news_url}-->" <!--{if $arrNews[data].link_method eq "2"}--> target="_blank"
                            <!--{/if}-->
                        <!--{/if}-->                   
                        <!--{$arrNews[data].news_title|h|nl2br}--></h4>
</div> 
<p class="newsarea_txt_last"><!--{$arrNews[data].news_comment}--></p>

</ul><br class="clr">
</div

            ><!--{/if}-->
            <!--{/section}-->

	<!--{/if}-->