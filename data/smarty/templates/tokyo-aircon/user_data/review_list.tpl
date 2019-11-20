<h2 class="title1"><span>お客様の声</span></h2>
<div id="customer_voice">
<img title="お客様の声" alt="お客様の声" src="<!--{$TPL_URLPATH}-->img/common/customer_voice2.jpg" data-popupalt-original-title="null" title="お客様の声">
</div>
<div class="main_column" id="two_maincolumn_right">

<div class="voice_page">
<!--{if count($arrReview) > 0}-->

<!--{if $smarty.get.review_page != "" && $smarty.get.review_page != 1}-->
<a href="?review_page=<!--{$smarty.get.review_page-1}-->"><<- 前  </a>  
<!--{else}-->
<!--{/if}-->
<!--{$smarty.get.review_page}--> / 34
<!--{if $smarty.get.review_page != "" && $smarty.get.review_page < 21}-->  <a href="?review_page=<!--{$smarty.get.review_page+1}-->">次 ->></a> <!--{else}--><!--{/if}--> 

<!--{/if}-->
</div>

<!--{if count($arrReview) > 0}-->
<!--{if $smarty.get.review_page != "" }-->

	<!--{section name=cnt loop=$arrReview start=$smarty.get.review_page*30-30 step=1 max=50}-->
<div class="voice_area">

<h3 class="sub_title"><!--{$arrReview[cnt].title|h}--> 投稿者：<!--{$arrReview[cnt].reviewer_name|h}--></h3>

<!--{if $arrReview[cnt].comment4|regex_replace:'/^XCS-/':'S' ne $arrReview[cnt].comment4}-->
		<a href="https://www.tokyo-aircon.net/products/<!--{$arrReview[cnt].comment4|replace:'/S':'-S'}-->.html" class="voice_img"><img src="https://www.tokyo-aircon.net//upload/save_image/<!--{$arrReview[cnt].main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrReview[cnt].name|h}-->"/></a>
<!--{else}-->
		<a href="https://www.tokyo-aircon.net/products/<!--{$arrReview[cnt].comment4}-->.html" class="voice_img"><img src="https://www.tokyo-aircon.net//upload/save_image/<!--{$arrReview[cnt].main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrReview[cnt].name|h}-->" /></a>
<!--{/if}-->


        <div class="voice_left">
            <header>
            <span class="voice_recommend_level"><!--{assign var=level value=$arrReview[cnt].recommend_level}--><!--{$arrRECOMMEND[$level]|h}--></span>
            <span class="voice_date"><!--{$arrReview[cnt].create_date|sfDispDBDate:false}--></span>
            </header>


<!--{if $arrReview[cnt].comment4|regex_replace:'/^XCS-/':'S' ne $arrReview[cnt].comment4}-->
 	<div class="voice_pro">ご購入商品<a href="https://www.tokyo-aircon.net/products/<!--{$arrReview[cnt].comment4|replace:'/S':'-S'}-->.html"><!--{$arrReview[cnt].name|h}--></a></div>
 	<div class="voice_pro">商品型番<a href="https://www.tokyo-aircon.net/products/<!--{$arrReview[cnt].comment4|replace:'/S':'-S'}-->.html"><!--{$arrReview[cnt].comment4}--></a></div>
<!--{else}-->
	<div class="voice_pro">ご購入商品<a href="https://www.tokyo-aircon.net/products/<!--{$arrReview[cnt].comment4}-->.html"><!--{$arrReview[cnt].name|h}--></a></div>
	<div class="voice_pro">商品型番<a href="https://www.tokyo-aircon.net/products/<!--{$arrReview[cnt].comment4}-->.html"><!--{$arrReview[cnt].comment4}--></a></div>
<!--{/if}-->

<p><!--{$arrReview[cnt].comment|h|nl2br}--></p>

        </div>

    </div>
            <!--{/section}-->

<!--{else}-->
            <!--{section name=cnt loop=$arrReview start=0 step=1 max=20}-->
		<div class="item clear">
<p class="voice_left">
                    <a href="https://www.tokyo-aircon.net/products/<!--{$arrReview[cnt].comment4}-->.html"><img src="https://www.tokyo-aircon.net//upload/save_image/<!--{$arrReview[cnt].main_list_image|sfNoImageMainList|h}-->&amp;width=120&amp;height=120" alt="<!--{$arrReview[cnt].name|h}-->" border-style="groove" border="2px"/></a></p>


                    <div class="voice_right">
<div class="voice_inner">
<div class="voice_title">
      <div class="profile"><p id="voice_title_txt"><!--{$arrReview[cnt].title|h}--> 投稿者：<!--{$arrReview[cnt].reviewer_name|h}--> <!--{$arrReview[cnt].reviewer_url}--><br><!--{$arrReview[cnt].create_date|sfDispDBDate:false}-->&nbsp;<b>おすすめレベル：</b><span class="recommend_level"><!--{assign var=level value=$arrReview[cnt].recommend_level}--><!--{$arrRECOMMEND[$level]|h}--></span></p>
</div>          
                
                    </div>
<div class="voice_inner2"><div class="profile">
      <a href="https://www.tokyo-aircon.net/products/<!--{$arrReview[cnt].comment4}-->.html"><!--{$arrReview[cnt].name|h}--></a></div>
                  


<p class="voice_answer">  <!--{$arrReview[cnt].comment|h|nl2br}-->
                </div></div></div>
            <!--{/section}-->
<!--{/if}-->
    <!--{/if}-->



<div class="voice_page">
<!--{if count($arrReview) > 0}-->

<!--{if $smarty.get.review_page != "" && $smarty.get.review_page != 1}-->
<a href="?review_page=<!--{$smarty.get.review_page-1}-->"><<- 前  </a>  
<!--{else}-->
<!--{/if}-->
<!--{$smarty.get.review_page}--> / 34
<!--{if $smarty.get.review_page != "" && $smarty.get.review_page < 21}-->  <a href="?review_page=<!--{$smarty.get.review_page+1}-->">次 ->></a> <!--{else}--><!--{/if}--> 

<!--{/if}-->
</div>

</div>