
<div id="h2_Main-ttl"><h2 class="ttl_<!--{$keijyo}-->"><p><!--{$title_parts2}--></p></h2></div>

<div style="margin:-20px 0 0 0;"><!--{$htmlData}--></div>
<!--▼list_detailSerchbox-->
 
</form>
<div id="list_detailSerchbox" class="radio_box">
    <form method="get" action="" style="margin:0; padding: 0;">
        <h3 class="title1"><span>商品詳細検索<span class="subtxt">ご希望の条件を選択して[絞り込む]をクリックして下さい。</span></span></h3>

            <!--<input type="hidden" name="category_id" value="<!--{$type}-->" />-->
            <!--↓PC用-->
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th scope="row">メーカー</th>
                    <td>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="maker_id" value="1" id="mi_1"<!--{if $maker_id==1}--> checked<!--{/if}--> /><label for="mi_1"<!--{if $maker_id==1}--> style="font-weight: bold;"<!--{/if}--> class="radio">ダイキン</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="maker_id" value="2" id="mi_2"<!--{if $maker_id==2}--> checked<!--{/if}--> /><label for="mi_2"<!--{if $maker_id==2}--> style="font-weight: bold;"<!--{/if}--> class="radio">東芝</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="maker_id" value="3" id="mi_3"<!--{if $maker_id==3}--> checked<!--{/if}--> /><label for="mi_3"<!--{if $maker_id==3}--> style="font-weight: bold;"<!--{/if}--> class="radio">三菱電機</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="maker_id" value="4" id="mi_4"<!--{if $maker_id==4}--> checked<!--{/if}--> /><label for="mi_4"<!--{if $maker_id==4}--> style="font-weight: bold;"<!--{/if}--> class="radio">日立</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="maker_id" value="5" id="mi_5"<!--{if $maker_id==5}--> checked<!--{/if}--> /><label for="mi_5"<!--{if $maker_id==5}--> style="font-weight: bold;"<!--{/if}--> class="radio">三菱重工</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS radio" name="maker_id" value="6" id="mi_6"<!--{if $maker_id==6}--> checked<!--{/if}--> /><label for="mi_6"<!--{if $maker_id==6}--> style="font-weight: bold;"<!--{/if}--> class="radio last">パナソニック</label>
                        </span>
                    </td>
                </tr>
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
    <input type="button" value="絞り込み解除" class="" onclick="location.href='<!--{$smarty.const.HTTP_URL}-->keijyo_<!--{$keijyo}-->.html'" />
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
    <input type="button" value="絞り込み解除" class="" onclick="location.href='<!--{$smarty.const.HTTP_URL}-->keijyo_<!--{$keijyo}-->.html'" />
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
<p class="center"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_S.jpg" alt="シングル" width="150" height="140" title="シングル"></p>
<!--{elseif $smarty.foreach.loopname.index == 1}-->
<h3 class="title6">同時ツイン</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_Tw.jpg" alt="同時ツイン" width="150" height="140" title="同時ツイン"></p>
<!--{elseif $smarty.foreach.loopname.index == 2}-->
<h3 class="title6">同時トリプル</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_Tr.jpg" alt="同時トリプル" width="150" height="140" title="同時トリプル"></p>
<!--{elseif $smarty.foreach.loopname.index == 3}-->
<h3 class="title6">同時フォー</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_F.jpg" alt="同時フォー" width="150" height="140" title="同時フォー"></p>
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
</div>

<!-- 個別運転 -->
<div class="swiper-slide">

<!--{foreach from=$arrNormalData_line2 key=nums item=arrData name="loopname"}-->

<section class="boxSize">

<!--{if $smarty.foreach.loopname.index == 0}-->
<h3 class="title7">個別ツイン</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_Tw.jpg" alt="個別ツイン" width="150" height="140" title="個別ツイン"></p>

<!--{elseif $smarty.foreach.loopname.index == 1}-->
<h3 class="title7">個別トリプル</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_Tr.jpg" alt="個別トリプル" width="150" height="140" title="個別トリプル"></p>

<!--{elseif $smarty.foreach.loopname.index == 2}-->
<h3 class="title7">個別フォー</h3>
<p class="center"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_F.jpg" alt="個別フォー" width="150" height="140" title="個別フォー"></p>
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

<h2 class="title1"><span><!--{$title_parts_sub}-->をメーカーから探す</span></h2>
<div id="mb15"  class="maker_sarch maker_box">
<!--{if $arrMakerInProducts.1}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_daikin_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="ダイキン"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_daikin.jpg" alt="ダイキン" width="121" height="24" title="ダイキン" /><p>ダイキン</p></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.2}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_toshiba_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="東芝"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_toshiba.jpg" alt="東芝" width="121" height="24" title="東芝" /><p>東芝</p></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.3}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishidenki_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="三菱電機"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_mitsudenki.jpg" alt="三菱電機" width="121" height="24" border="0" title="三菱電機" /><p>三菱電機</p></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.4}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_hitachi_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="日立"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_hitachi.jpg" alt="日立" width="121" height="24" title="日立" /><p>日立</p></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.5}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishijyuko_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="三菱重工"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_mitsuJ.jpg" alt="三菱重工" width="121" height="24" title="三菱重工" /><p>三菱重工</p></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.6}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_panasonic_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="パナソニック"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_panasonic.jpg" alt="パナソニック" width="121" height="24" title="パナソニック" /><p>パナソニック</p></a>
        </div>
    </div>
<!--{/if}-->
</div>

<div>
<!--{if $type!=82 && $type!=83 && $type!=84}-->
    <!--{if $type!=73}-->
        <!--<div class="indexListBoxContents"><div class="indexListBoxInContents"><a href="<!--{$smarty.const.HTTP_URL}-->maker_toshiba_<!--{$keijyo}-->.html?op_4=1"><div><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_aircon/toshiba2_<!--{$maker_type}-->.jpg" alt="東芝 <!--{$title_parts_sub}-->" title="東芝 <!--{$title_parts_sub}-->" width="100" height="100" /><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_aircon/s_toshiba.gif" alt="東芝" /></div>東芝</a></div></div>-->
    <!--{/if}-->

    <!--<div class="indexListBoxContents"><div class="indexListBoxInContents"><a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishidenki_<!--{$keijyo}-->.html?op_4=1"><div><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_aircon/mdenki_<!--{$maker_type}-->.jpg" alt="三菱電機 <!--{$title_parts_sub}-->" title="三菱電機 <!--{$title_parts_sub}-->" width="100" height="100" /><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_aircon/s_mitsubishi.gif" alt="三菱電機" /></div>三菱電機</a></div></div>-->

    <!--<div class="indexListBoxContents"><div class="indexListBoxInContents"><a href="<!--{$smarty.const.HTTP_URL}-->maker_hitachi_<!--{$keijyo}-->.html?op_4=1"><div><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_aircon/hitachi_<!--{$maker_type}-->.jpg" alt="日立 <!--{$title_parts_sub}-->" title="日立 <!--{$title_parts_sub}-->" width="100" height="100" /><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_aircon/s_hitachi.gif" alt="日立" /></div>日立</a></div></div>-->

    <!--{if $type!=73}-->
        <!--<div class="indexListBoxContents"><div class="indexListBoxInContents"><a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishijyuko_<!--{$keijyo}-->.html?op_4=1"><div><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_aircon/mjyuko_<!--{$maker_type}-->.jpg" alt="三菱重工 <!--{$title_parts_sub}-->" title="三菱重工 <!--{$title_parts_sub}-->" width="100" height="100" /><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_aircon/s_jyuko.gif" alt="三菱重工" /></div>三菱重工</a></div></div>-->
    <!--{/if}-->
<!--{/if}-->

<!--{if $type!=73}-->
    <div></div>
<!--{/if}-->
</div>
