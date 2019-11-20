<div id="h2_Main-loc-ttl">
    <h2 class="ttl_<!--{$strLocation}-->"><!--{$title_parts}--></h2>
</div>
<div style="margin:-20px 0 0 0;"><!--{$htmlData}--></div>
<h3 class="title1"><span>お部屋の広さを以下の表からお選びください　<span class="subtxt">※単位　㎡÷3.3＝坪　坪×２＝畳</span></span></h3>

<table border="0" cellspacing="0" cellpadding="0" class="arealisttable">
    <thead>
        <tr>
            <th>馬力</th>
            <th>m2</th>
            <th>坪</th>
            <th>畳</th>
            
        </tr>
    </thead>
    <tbody style="font-size:14px;">
<!--{foreach from=$arrLocationData key=num item=arrDetail}-->
    <!--{if $num % 2 == 1}-->
        <tr class="bg_white">
    <!--{else}-->
        <tr class="bg_paleblue">
    <!--{/if}-->
            <td><a href="<!--{$smarty.const.HTTP_URL}-->power_<!--{$arrDetail.url}-->.html"><!--{$arrDetail.hp}-->馬力</a></td>
            <td><a href="<!--{$smarty.const.HTTP_URL}-->power_<!--{$arrDetail.url}-->.html"><!--{$arrDetail.m_min}--> - <!--{$arrDetail.m_max}--> m2</a></td>
            <td><a href="<!--{$smarty.const.HTTP_URL}-->power_<!--{$arrDetail.url}-->.html"><!--{$arrDetail.t_min}--> - <!--{$arrDetail.t_max}--> 坪</a></td>
            <td><a href="<!--{$smarty.const.HTTP_URL}-->power_<!--{$arrDetail.url}-->.html"><!--{$arrDetail.j_min}--> - <!--{$arrDetail.j_max}--> 畳</a></td>
            
        </tr>
<!--{foreachelse}-->
データがありません。
<!--{/foreach}-->
    </tbody>
</table>
