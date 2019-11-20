<div class="bloc_outer">
<!--<p class="leftBoxLower" style="margin-bottom:8px;margin-top:0px;padding: 2px 2px 11px 2px;text-align: center;font-size: 10px; width: 174px;border:1px navy solid; font-size:11px;"><img src="https://www.tokyo-aircon.net/images/info_n.jpg"><br><br>誠に勝手ながら、下記の日程において年末年始休業とさせていただきます。 <br><br><b style="font-size:12px;">◎休業期間◎<br>12月27日(金)～1月5日(日)</b><br><br><a href="https://www.tokyo-aircon.net/news_all.php?news_id=10">詳しくはこちら</a></p>!-->
<h3 class="leftBoxHeading" id="searchHeading"></h3>
<div class="bloc_body_serch">
    <div id="searchContent" class="sideBoxContent">
            <!--検索フォーム-->
            <form name="search_form" id="search_form" method="get" action="https://www.tokyo-aircon.net/products/list.php">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="search" />
            <input type="hidden" name="orderby" value="price" />
            <input type="text" name="name" class="box160" maxlength="50" value="<!--{if $smarty.get.mode == search}--><!--{$smarty.get.name|h}--><!--{/if}-->" /><input value="検索" style="width: 168px;font-size: 14px;margin-top: 5px;padding: 2px 0 0;text-align: center; height:28px;" type="submit">
            </form>
    </div>
</div>
</div>