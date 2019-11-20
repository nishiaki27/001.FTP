<!--最近チェックした商品-->
		<!--{section name=cnt loop=$arrHistoryProducts step=5}-->
	<div id="reco_checkbox" class="order3">
	  <h3>最近チェックした商品</h3>
	  <div class="checkItem_bgbox">
			<!--{section name=cnt2 loop=$arrHistoryProducts start=$smarty.section.cnt.index max=5}-->
			<!--{assign var=id value=$arrHistoryProducts[cnt2].product_id}-->
	  <div class="checkItembox">
					<div class="ph">
                    <!--{if $arrHistoryProducts[cnt2].product_code_min|regex_replace:'/^XCS-/':'S' ne $arrHistoryProducts[cnt2].product_code_min}-->
							<a href="https://www.tokyo-aircon.net/products/<!--{$arrHistoryProducts[cnt2].product_code_min|replace:'/S':'-S'}-->.html"><!--商品写真--><img src="https://www.tokyo-aircon.net/upload/save_image/<!--{$arrHistoryProducts[cnt2].main_list_image}-->" alt="<!--{$arrHistoryProducts[cnt2].product_code_min|h}--> <!--{$arrHistoryProducts[cnt2].name|escape}-->" class="picture" width="120"/></a>
                           	<!--{else}-->
							<a href="https://www.tokyo-aircon.net/products/<!--{$arrHistoryProducts[cnt2].product_code_min|h}-->.html"><!--商品写真--><img src="https://www.tokyo-aircon.net/upload/save_image/<!--{$arrHistoryProducts[cnt2].main_list_image}-->" alt="<!--{$arrHistoryProducts[cnt2].product_code_min|h}--> <!--{$arrHistoryProducts[cnt2].name|escape}-->" class="picture" width="120"/></a>
							<!--{/if}-->
					</div>
                    <!-- ▲画像 -->
					<p class="itemtitle">
                    
                    <!--{if $arrHistoryProducts[cnt2].product_code_min|regex_replace:'/^XCS-/':'S' ne $arrHistoryProducts[cnt2].product_code_min}-->
                    <a href="https://www.tokyo-aircon.net/products/<!--{$arrHistoryProducts[cnt2].product_code_min|replace:'/S':'-S'}-->.html"><!--{$arrHistoryProducts[cnt2].product_code_min|h}--> <!--{$arrHistoryProducts[cnt2].name|escape|truncate:60:"..."}--></a>
                    <!--{else}-->
                    <a href="https://www.tokyo-aircon.net/products/<!--{$arrHistoryProducts[cnt2].product_code_min|h}-->.html"><!--{$arrHistoryProducts[cnt2].product_code_min|h}--> <!--{$arrHistoryProducts[cnt2].name|escape|truncate:60:"..."}--></a>
                    <!--{/if}-->

                    </p>
                    <!-- ▲商品タイトル -->
						<div class="price">
                        
                        <!--{if $arrHistoryProducts[cnt2].product_code_min|regex_replace:'/^XCS-/':'S' ne $arrHistoryProducts[cnt2].product_code_min}-->                        
							<a href="https://www.tokyo-aircon.net/products/<!--{$arrHistoryProducts[cnt2].product_code_min|replace:'/S':'-S'}-->.html"><span class="price">
							<!--{if $arrHistoryProducts[cnt2].price02_min == $arrHistoryProducts[cnt2].price02_max}--><!--{$arrHistoryProducts[cnt2].price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
							<!--{else}--><!--{$arrHistoryProducts[cnt2].price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->〜<!--{$arrHistoryProducts[cnt2].price02_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
							<!--{/if}-->円</span></a>
                         <!--{else}-->
                         <a href="https://www.tokyo-aircon.net/products/<!--{$arrHistoryProducts[cnt2].product_code_min|replace:'/S':'-S'}-->.html"><span class="price">
							<!--{if $arrHistoryProducts[cnt2].price02_min == $arrHistoryProducts[cnt2].price02_max}--><!--{$arrHistoryProducts[cnt2].price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
							<!--{else}--><!--{$arrHistoryProducts[cnt2].price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->〜<!--{$arrHistoryProducts[cnt2].price02_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
							<!--{/if}-->円</span></a>
                         
                         <!--{/if}-->
                         
						</div>
                <!-- ▲価格表示 -->
				</div>
			<!--{/section}--><br class="clr">
      </div>
    </div>
		<!--{/section}-->
<!--最近チェックした商品-->
