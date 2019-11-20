

<h2 class="title1"><span><!--{$title_h2}--></span></h2>
<div id="Cate_deescription">
    <div><img src="../images/categories/Mainimage_<!--{$main_img}-->.png" alt="<!--{$title_h2}-->" border="0" title="<!--{$title_h2}-->"></div>
</div>

<!--▼list_detailSerchbox-->
<div id="list_detailSerchbox" class="radio_box">
    <form method="get" action="" style="margin:0; padding: 0;">
        <h3 class="title1"><span>商品詳細検索<span class="subtxt">ご希望の条件を選択して[絞り込む]をクリックして下さい。</span></span></h3>

            <!--<input type="hidden" name="category_id" value="<!--{$type}-->" />-->
              <!--↓PC用-->
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th scope="row">省エネタイプ<span class="helpTips" title="超省エネ：省エネ力に優れた最も電気代がお安くなるタイプ<br>標準省エネ：省エネ機能を備えたお値打ちなタイプ<br>冷房専用：冷房機能のみに機能を絞ったタイプ"><img src="<!--{$smarty.const.HTTP_URL}-->images/tooltips.png" border="0" style="vertical-align: -2px; margin-left: 2px;" /></span></th>
                    <td>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="op_4" value="1" id="op_16"<!--{if $op4==1}--> checked<!--{/if}--> /><label for="op_16"<!--{if $op4==1}--> style="font-weight: bold;"<!--{/if}--> class="radio">価格重視(標準省エネ)</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="op_4" value="2" id="op_15"<!--{if $op4==2}--> checked<!--{/if}--> /><label for="op_15"<!--{if $op4==2}--> style="font-weight: bold;"<!--{/if}--> class="radio">省エネ重視(超省エネ)</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="op_4" value="3" id="op_17"<!--{if $op4==3}--> checked<!--{/if}--> /><label for="op_17"<!--{if $op4==3}--> style="font-weight: bold;"<!--{/if}--> class="radio">冷房専用</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="op_4" value="4" id="op_18"<!--{if $op4==4}--> checked<!--{/if}--> /><label for="op_18"<!--{if $op4==4}--> style="font-weight: bold;"<!--{/if}--> class="radio last">寒冷地用</label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>リモコンタイプ<span class="helpTips" title="ワイヤード：室内機からケーブルで繋がっているタイプ（床置型は本体に内蔵）<br>ワイヤレス：リモコンで操作するタイプ"><img src="<!--{$smarty.const.HTTP_URL}-->images/tooltips.png" border="0" style="vertical-align: -2px; margin-left: 2px;" /></span></th>
                    <td>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="op_6" value="1" id="op_1"<!--{if $op6==1}--> checked<!--{/if}--> /><label for="op_1"<!--{if $op6==1}--> style="font-weight: bold;"<!--{/if}--> class="radio">ワイヤード</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="op_6" value="2" id="op_2"<!--{if $op6==2}--> checked<!--{/if}--> /><label for="op_2"<!--{if $op6==2}--> style="font-weight: bold;"<!--{/if}--> class="radio last">ワイヤレス</label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>電源タイプ<span class="helpTips" title="三相200V：動力を多く必要とする場所に配線されている電源<br>単相200V：通常一般家庭に配線されている電源<br>"><img src="<!--{$smarty.const.HTTP_URL}-->images/tooltips.png" border="0" style="vertical-align: -2px; margin-left: 2px;" /></span></th>
                    <td>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="op_5" value="1" id="op_26"<!--{if $op5==1}--> checked<!--{/if}--> /><label for="op_26"<!--{if $op5==1}--> style="font-weight: bold;"<!--{/if}--> class="radio">三相200V</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="op_5" value="2" id="op_25"<!--{if $op5==2}--> checked<!--{/if}--> /><label for="op_25"<!--{if $op5==2}--> style="font-weight: bold;"<!--{/if}--> class="radio last">単相200V</label>
                        </span>
                    </td>
                </tr>
            </table>
<div id="button" class="pc_button">
            <input type="submit" value="絞り込む" class="mr15" />
    </form>

<!--{if $op4 || $op5 || $op6 || $maker_id}-->
    <input type="button" value="絞り込み解除" class="" onclick="location.href='<!--{$smarty.const.HTTP_URL}-->maker_<!--{$ma}-->_<!--{$keijyo}-->.html'" />
<!--{/if}-->
</div>
            <!--↑PC用-->
                 <!--↓SP用-->
<div class="details_search">

<form method="get" action="">
<div class="flexBox">
<p id="spec">メーカー</p>
<p class="selectBox">
<select name="maker_id">
<option value="">メーカー選択</option>
<option value="1"<!--{if $maker_id==1}--> selected<!--{/if}--> />ダイキン</option>
<option value="2"<!--{if $maker_id==2}--> selected<!--{/if}--> />東芝</option>
<option value="3"<!--{if $maker_id==3}--> selected<!--{/if}--> />三菱電機</option>
<option value="4"<!--{if $maker_id==4}--> selected<!--{/if}--> />日立</option>
<option value="5"<!--{if $maker_id==5}--> selected<!--{/if}--> />三菱重工</option>
<option value="6"<!--{if $maker_id==6}--> selected<!--{/if}--> />パナソニック</option>
</select>
</p>
</div>

<div class="flexBox">
<p id="spec">省エネタイプ</p>
<p class="selectBox">
<select name="op_4">
<option value="">省エネ選択</option>
<option value="1"<!--{if $op4==1}--> selected<!--{/if}--> />価格重視</option>
<option value="2"<!--{if $op4==2}--> selected<!--{/if}--> />省エネ重視</option>
<option value="3"<!--{if $op4==3}--> selected<!--{/if}--> />冷房専用</option>
<option value="4"<!--{if $op4==4}--> selected<!--{/if}--> />寒冷地用</option>
</select>
</p>
</div>

<div class="flexBox">
<p id="remote">リモコンタイプ</p>
<p class="selectBox">
<select name="op_6">
<option value="">リモコン選択</option>
<option value="1"<!--{if $op6==1}--> selected<!--{/if}--> />ワイヤード</option>
<option value="2"<!--{if $op6==2}--> selected<!--{/if}--> />ワイヤレス</option>
</select>
</p>
</div>

<div class="flexBox">
<p id="power">電源</p>
<p class="selectBox">
<select name="op_5">
<option value="">電源選択</option>
<option value="2"<!--{if $op5==2}--> selected<!--{/if}--> />単相200V</option>
<option value="1"<!--{if $op5==1}--> selected<!--{/if}--> />三相200V（動力）</option>
</select>
</p>
</div>
<div id="button">
            <input type="submit" value="絞り込む" class="mr15" />
    </form>

<!--{if $op4 || $op5 || $op6 || $maker_id}-->
    <input type="button" value="絞り込み解除" class="" onclick="location.href='<!--{$smarty.const.HTTP_URL}-->maker_<!--{$ma}-->_<!--{$keijyo}-->.html'" />
<!--{/if}-->
</div>
</div>
	<!--↑SP用-->



<p class="txt_R"><span class="att3">※絞込の検索結果は下記に表示※</span></p>

</div>
<!--▲list_detailSerchbox-->

<div id="productListing">
    <a name="title_ecoitem01" id="title_ecoitem01"></a>
<h3 class="title2"><span><!--{$title_parts}--> 業務用エアコン<!--{if $smarty.server.REQUEST_URI|mb_strpos:'.html?' !== FALSE}--><span class="subtxt"><!--{$op4_query}--></span></span><!--{/if}--></h3>
<div id="ecoitem_normal">
<!-- 同時運転 -->
<div class="swiper-slide">
<!--{foreach from=$arrNormalData_line1 key=nums item=arrData name="loopname"}-->
<section class="boxSize">
<!--{if $smarty.foreach.loopname.index == 0}-->
<h3 class="title6">シングル</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_S.jpg" alt="シングル" width="150" height="140" title="シングル"></p>
<!--{elseif $smarty.foreach.loopname.index == 1}-->
<h3 class="title6">同時ツイン</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_Tw.jpg" alt="同時ツイン" width="150" height="140" title="同時ツイン"></p>
<!--{elseif $smarty.foreach.loopname.index == 2}-->
<h3 class="title6">同時トリプル</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_Tr.jpg" alt="同時トリプル" width="150" height="140" title="同時トリプル"></p>
<!--{elseif $smarty.foreach.loopname.index == 3}-->
<h3 class="title6">同時フォー</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_F.jpg" alt="同時フォー" width="150" height="140" title="同時フォー"></p>
<!--{/if}-->

<ul class="style_pw">
<!--{foreach from=$arrData key=id item=value}-->
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$mi}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
<!--{foreachelse}-->
<li>該当なし</li>
<!--{/foreach}-->
</ul>
</section>
<!--{/foreach}-->
</div>


<!-- 個別運転 -->
<div class="swiper-slide">

<!--{foreach from=$arrNormalData_line2 key=nums item=arrData name="loopname"}-->

<section class="boxSize">

<!--{if $smarty.foreach.loopname.index == 0}-->
<h3 class="title7">個別ツイン</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_Tw.jpg" alt="個別ツイン" width="150" height="140" title="個別ツイン"></p>

<!--{elseif $smarty.foreach.loopname.index == 1}-->
<h3 class="title7">個別トリプル</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_Tr.jpg" alt="個別トリプル" width="150" height="140" title="個別トリプル"></p>

<!--{elseif $smarty.foreach.loopname.index == 2}-->
<h3 class="title7">個別フォー</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_F.jpg" alt="個別フォー" width="150" height="140" title="個別フォー"></p>
<!--{/if}-->

<ul class="style_pw">
<!--{foreach from=$arrData key=id item=value}-->
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$maker_id}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
<!--{foreachelse}-->
<li>該当なし</li>
<!--{/foreach}-->
</ul>
</section>
<!--{/foreach}-->
<section class="boxSize">
<h3 class="title7">　</h3>
<p class="center last"></p>
</section>
</div>
</div>
</div>
</div>

