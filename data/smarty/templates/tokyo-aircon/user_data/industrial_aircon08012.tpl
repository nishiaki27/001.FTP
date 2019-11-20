<div id="h2_Main-ttl"><h2 class="ttl_<!--{$keijyo}-->"><!--{$title_parts2}--></h2></div>

<div style="margin:-20px 0 0 0;"><!--{$htmlData}--></div>

<!--▼list_detailSerchbox-->
<div id="list_detailSerchbox">
    <form method="get" action="" style="margin:0; padding: 0;">
        <h3 class="title1"><span>商品詳細検索<span class="subtxt">ご希望の条件を選択して[絞り込む]をクリックして下さい。</span></span></h3>

            <!--<input type="hidden" name="category_id" value="<!--{$type}-->" />-->
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th scope="row">メーカー</th>
                    <td>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="maker_id" value="1" id="mi_1"<!--{if $maker_id==1}--> checked<!--{/if}--> /><label for="mi_1"<!--{if $maker_id==1}--> style="font-weight: bold;"<!--{/if}-->>ダイキン</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="maker_id" value="2" id="mi_2"<!--{if $maker_id==2}--> checked<!--{/if}--> /><label for="mi_2"<!--{if $maker_id==2}--> style="font-weight: bold;"<!--{/if}-->>東芝</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="maker_id" value="3" id="mi_3"<!--{if $maker_id==3}--> checked<!--{/if}--> /><label for="mi_3"<!--{if $maker_id==3}--> style="font-weight: bold;"<!--{/if}-->>三菱電機</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="maker_id" value="4" id="mi_4"<!--{if $maker_id==4}--> checked<!--{/if}--> /><label for="mi_4"<!--{if $maker_id==4}--> style="font-weight: bold;"<!--{/if}-->>日立</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="maker_id" value="5" id="mi_5"<!--{if $maker_id==5}--> checked<!--{/if}--> /><label for="mi_5"<!--{if $maker_id==5}--> style="font-weight: bold;"<!--{/if}-->>三菱重工</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="maker_id" value="6" id="mi_6"<!--{if $maker_id==6}--> checked<!--{/if}--> /><label for="mi_6"<!--{if $maker_id==6}--> style="font-weight: bold;"<!--{/if}-->>パナソニック</label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">省エネタイプ<span class="helpTips" title="超省エネ：省エネ力に優れた最も電気代がお安くなるタイプ<br>標準省エネ：省エネ機能を備えたお値打ちなタイプ<br>冷房専用：冷房機能のみに機能を絞ったタイプ"><img src="<!--{$smarty.const.HTTP_URL}-->images/tooltips.png" border="0" style="vertical-align: -2px; margin-left: 2px;" /></span></th>
                    <td>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="op_4" value="1" id="op_16"<!--{if $op4==1}--> checked<!--{/if}--> /><label for="op_16"<!--{if $op4==1}--> style="font-weight: bold;"<!--{/if}-->>価格重視(標準省エネ)</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="op_4" value="2" id="op_15"<!--{if $op4==2}--> checked<!--{/if}--> /><label for="op_15"<!--{if $op4==2}--> style="font-weight: bold;"<!--{/if}-->>省エネ重視(超省エネ)</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="op_4" value="3" id="op_17"<!--{if $op4==3}--> checked<!--{/if}--> /><label for="op_17"<!--{if $op4==3}--> style="font-weight: bold;"<!--{/if}-->>冷房専用</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="op_4" value="4" id="op_18"<!--{if $op4==4}--> checked<!--{/if}--> /><label for="op_18"<!--{if $op4==4}--> style="font-weight: bold;"<!--{/if}-->>寒冷地用</label>
                        </span>

                    </td>
                </tr>
                <tr>
                    <th>リモコンタイプ<span class="helpTips" title="ワイヤード：室内機からケーブルで繋がっているタイプ（床置型は本体に内蔵）<br>ワイヤレス：リモコンで操作するタイプ"><img src="<!--{$smarty.const.HTTP_URL}-->images/tooltips.png" border="0" style="vertical-align: -2px; margin-left: 2px;" /></span></th>
                    <td>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="op_6" value="1" id="op_1"<!--{if $op6==1}--> checked<!--{/if}--> /><label for="op_1"<!--{if $op6==1}--> style="font-weight: bold;"<!--{/if}-->>ワイヤード</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="op_6" value="2" id="op_2"<!--{if $op6==2}--> checked<!--{/if}--> /><label for="op_2"<!--{if $op6==2}--> style="font-weight: bold;"<!--{/if}-->>ワイヤレス</label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>電源タイプ<span class="helpTips" title="三相200V：動力を多く必要とする場所に配線されている電源<br>単相200V：通常一般家庭に配線されている電源<br>"><img src="<!--{$smarty.const.HTTP_URL}-->images/tooltips.png" border="0" style="vertical-align: -2px; margin-left: 2px;" /></span></th>
                    <td>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="op_5" value="1" id="op_26"<!--{if $op5==1}--> checked<!--{/if}--> /><label for="op_26"<!--{if $op5==1}--> style="font-weight: bold;"<!--{/if}-->>三相200V</label>
                        </span>
                        <span>
                            <input type="radio" class="crirHiddenJS" name="op_5" value="2" id="op_25"<!--{if $op5==2}--> checked<!--{/if}--> /><label for="op_25"<!--{if $op5==2}--> style="font-weight: bold;"<!--{/if}-->>単相200V</label>
                        </span>
                    </td>
                </tr>
            </table>

<div id="button">
            <input type="submit" value="絞り込む" class="mr15" />
    </form>

<!--{if $op4 || $op5 || $op6 || $maker_id}-->
    <input type="button" value="絞り込み解除" class="" onclick="location.href='<!--{$smarty.const.HTTP_URL}-->keijyo_<!--{$keijyo}-->.html'" />
<!--{/if}-->
</div>

<p class="txt_R"><span class="att3">※絞込の検索結果は下記に表示※</span></p>

</div>
<!--▲list_detailSerchbox-->

<div id="productListing">
    <a name="title_ecoitem01" id="title_ecoitem01"></a>
<h3 class="title2"><span><!--{$title_parts}--> 業務用エアコン<!--{if $smarty.server.REQUEST_URI|mb_strpos:'.html?' !== FALSE}--><span class="subtxt"><!--{$op4_query}--></span></span><!--{/if}--></h3>
    <div id="ecoitem_normal">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th scope="col" class="bgcolor01">シングル</th>
                <th scope="col" class="bgcolor01">同時ツイン</th>
                <th scope="col" class="bgcolor01">同時トリプル</th>
                <th scope="col" class="bgcolor01">同時フォー</th>
            </tr>
            <tr>
                <td class="ph_td"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_S.jpg" alt="シングル" width="150" height="140" title="シングル"><br></td>
                <td class="ph_td"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_Tw.jpg" alt="同時ツイン" width="150" height="140" title="同時ツイン"></td>
                <td class="ph_td"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_Tr.jpg" alt="同時トリプル" width="150" height="140" title="同時トリプル"></td>
                <td class="ph_td"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_F.jpg" alt="同時フォー" width="150" height="140" title="同時フォー"></td>
            </tr>
            <tr>

  <!--{if $arrSuperData_line1[1]|@count == 0 }-->

  <!--{foreach from=$arrNormalData_line1 key=nums item=arrData}-->
                <td>
                    <ul class="style_pw">
    <!--{foreach from=$arrData key=id item=value}-->
                        <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$maker_id}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
    <!--{foreachelse}-->
該当はありません
    <!--{/foreach}-->
                    </ul>
                </td>
  <!--{/foreach}-->


  <!--{else}-->  

  <!--{foreach from=$arrSuperData_line1 key=nums item=arrData}-->
                <td>
                    <ul class="style_pw">
    <!--{foreach from=$arrData key=id item=value}-->
                        <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$maker_id}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
    <!--{foreachelse}-->
該当はありません
    <!--{/foreach}-->
                    </ul>
                </td>
  <!--{/foreach}-->

  <!--{/if}--> 

            </tr>


            <tr>
                <th scope="col" class="bgcolor02">個別ツイン</th>
                <th scope="col" class="bgcolor02">個別トリプル</th>
                <th scope="col" class="bgcolor02">個別フォー</th>
                <th scope="col" class="bgcolor02">&nbsp;</th>
            </tr>
            <tr>
                <td class="ph_td"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_Tw.jpg" alt="個別ツイン" width="150" height="140" title="個別ツイン"></td>
                <td class="ph_td"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_Tr.jpg" alt="個別トリプル" width="150" height="140" title="個別トリプル"></td>
                <td class="ph_td"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_F.jpg" alt="個別フォー" width="150" height="140" title="個別フォー"></td>
                <td rowspan="2"></td>
            </tr>
            <tr>


  <!--{if $arrSuperData_line2[1]|@count == 0 }-->

  <!--{foreach from=$arrNormalData_line2 key=nums item=arrData}-->
                <td>
                    <ul class="style_pw">
    <!--{foreach from=$arrData key=id item=value}-->
                        <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$maker_id}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
    <!--{foreachelse}-->
該当はありません
    <!--{/foreach}-->
                    </ul>
                </td>
  <!--{/foreach}-->

  <!--{else}-->  

  <!--{foreach from=$arrSuperData_line2 key=nums item=arrData}-->
                <td>
                    <ul class="style_pw">
    <!--{foreach from=$arrData key=id item=value}-->
                        <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$maker_id}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
    <!--{foreachelse}-->
該当はありません
    <!--{/foreach}-->
                    </ul>
                </td>
  <!--{/foreach}-->



  <!--{/if}-->  


            </tr>
  <!--{if $op4==''}-->
            <tr>
                <th scope="col" class="bgcolor03">冷房専用</th>
                <th scope="col" class="bgcolor03">寒冷地用</th>
                <th scope="col" class="bgcolor03">&nbsp;</th>
                <th scope="col" class="bgcolor03">&nbsp;</th>
             </tr>
             <tr>
                 <td class="ph_td"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_S.jpg" alt="冷房専用" width="150" height="140" title="冷房専用"></td>
                 <td class="ph_td"><img src="/images/ind_aircon/ecoitem_keijyou_<!--{$aircon_img}-->_S.jpg" alt="冷房専用" width="150" height="140" title="冷房専用"></td>
                 <td rowspan="2"></td>
                 <td rowspan="2"></td>
             </tr>
             <tr>
      <!--{foreach from=$arrNormalData_line3 key=nums item=arrData}-->
                <td>
                    <ul class="style_pw">
        <!--{foreach from=$arrData key=key item=value}-->
            <!--{if $nums==1}-->
                        <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$value.id}-->&maker_id=<!--{$maker_id}-->&name=<!--{$value.name}-->&mode=power&orderby=price&op_4=3&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
            <!--{else if $nums==2}-->
                        <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$value.id}-->&maker_id=<!--{$maker_id}-->&name=<!--{$value.name}-->&mode=power&orderby=price&op_4=4&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
            <!--{/if}-->
        <!--{foreachelse}-->
該当はありません
        <!--{/foreach}-->
                    </ul>
                </td>
      <!--{/foreach}-->
             </tr>
  <!--{/if}-->
        </table>
    </div>

</div>

<h2 class="title1"><span><!--{$title_parts_sub}-->をメーカーから探す</span></h2>
<div id="mb15 txt_C" style="margin-bottom:185px;">
<!--{if $arrMakerInProducts.1}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_daikin_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="ダイキン"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_daikin.jpg" alt="ダイキン" width="121" height="24" title="ダイキン" /></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.2}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_toshiba_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="東芝"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_toshiba.jpg" alt="東芝" width="121" height="24" title="東芝" /></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.3}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishidenki_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="三菱電機"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_mitsudenki.jpg" alt="三菱電機" width="121" height="24" border="0" title="三菱電機" /></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.4}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_hitachi_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="日立"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_hitachi.jpg" alt="日立" width="121" height="24" title="日立" /></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.5}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishijyuko_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="三菱重工"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_mitsuJ.jpg" alt="三菱重工" width="121" height="24" title="三菱重工" /></a>
        </div>
    </div>
<!--{/if}-->
<!--{if $arrMakerInProducts.6}-->
    <div class="housing_maker2">
        <div class="ph">
            <a href="<!--{$smarty.const.HTTP_URL}-->maker_panasonic_<!--{$keijyo}-->.html?op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->" title="パナソニック"><img src="<!--{$smarty.const.HTTP_URL}-->images/ind_maker_panasonic.jpg" alt="パナソニック" width="121" height="24" title="パナソニック" /></a>
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
