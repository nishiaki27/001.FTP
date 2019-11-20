<div id="voicebox">
<!--{if $smarty.get.news_id == ""}-->

  	<!--{section name=data loop=$arrNews max=20}-->
    <!--{if $arrNews[data].voice_flg == 1}-->
    <!--{assign var="date_array" value="-"|explode:$arrNews[data].news_date_disp}-->
	<div class="comment">
    <div class="title">
    	<a href="https://www.tokyo-aircon.net/voice_list.php?news_id=<!--{$arrNews[data].news_id}-->" target="_self">
      	<!--{$arrNews[data].news_title|h|nl2br}-->
      </a>
      
    </div>
    <div class="txt"><!--{$arrNews[data].news_comment|h|nl2br}--></div>
  </div>
  <div class="boder02 mb15"></div>
  <!--{/if}-->
	<!--{/section}-->
</div>

<!--{else}-->

	<!--{section name=data loop=$arrNews}-->
  <!--{if $arrNews[data].news_id == $smarty.get.news_id}-->
  <!--{assign var="date_array" value="-"|explode:$arrNews[data].news_date_disp}-->

	<div class="comment">
  <div class="title">
	  <!--{$arrNews[data].news_title|h|nl2br}-->
	</div> 
	<div class="txt"><!--{$arrNews[data].news_comment}--></div>
	</div>
  <!--{/if}-->
  <!--{/section}-->
</div>
<!--{/if}-->

<!--{$tpl_strnavi}-->