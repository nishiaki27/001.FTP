<!--最近チェックした商品-->
		<!--{section name=cnt loop=$arrHistoryProducts step=5}-->
<div class="recomendarea-wrap">
	<div class="recomendarea clearfix">
		<div id="recentproducts"><span class="itemtitle clearfix">最近チェックした商品</span></div>
		<div class="listarea clearfix" id="recentbox">
			<!--{section name=cnt2 loop=$arrHistoryProducts start=$smarty.section.cnt.index max=5}-->
			<!--{assign var=id value=$arrHistoryProducts[cnt2].product_id}-->
			<div class="<!--{if $smarty.section.cnt2.iteration ne 5}-->listblock01<!--{else}-->listblock02<!--{/if}-->">
					<div class="productimage">
						<div class="imagearea">
							<a href="https://www.tokyo-aircon.net/products/<!--{$arrHistoryProducts[cnt2].product_code_min|h}-->.html"><!--商品写真--><img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$arrHistoryProducts[cnt2].main_list_image}-->" alt="<!--{$arrHistoryProducts[cnt2].product_code_min|h}--> <!--{$arrHistoryProducts[cnt2].name|escape}-->" class="picture" width="100"/></a>
						</div>
					</div>
					<div class="productname">
					<p class="productitle"><a href="https://www.tokyo-aircon.net/products/<!--{$arrHistoryProducts[cnt2].product_code_min|h}-->.html"><!--{$arrHistoryProducts[cnt2].product_code_min|h}--> <!--{$arrHistoryProducts[cnt2].name|escape|truncate:60:"..."}--></a></p>
					</div>
					<div class="listcomment">
						<div class="priceblock">
							<a href="https://www.tokyo-aircon.net/products/<!--{$arrHistoryProducts[cnt2].product_code_min|h}-->.html"><span class="pricetx">
							<!--{if $arrHistoryProducts[cnt2].price02_min == $arrHistoryProducts[cnt2].price02_max}--><!--{$arrHistoryProducts[cnt2].price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
							<!--{else}--><!--{$arrHistoryProducts[cnt2].price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->〜<!--{$arrHistoryProducts[cnt2].price02_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
							<!--{/if}-->円</span></a>
						</div>
						
					</div>
				</div>
			<!--{/section}-->
		</div>
	</div>
</div>
		<!--{/section}-->
<!--最近チェックした商品-->
