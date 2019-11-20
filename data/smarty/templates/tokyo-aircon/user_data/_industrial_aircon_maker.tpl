<h2 class="title1"><span><!--{$title_h2}--></span></h2>
<div id="Cate_deescription">
    <div><img src="../images/categories/Mainimage_<!--{$main_img}-->.png" alt="<!--{$title_h2}-->" border="0" title="<!--{$title_h2}-->"></div>
</div>

<!--▼list_detailSerchbox-->
<div id="list_detailSerchbox">
    <form method="get" action="" style="margin:0; padding: 0;">
        <h3 class="title1"><span>商品詳細検索<span class="subtxt">ご希望の条件を選択して[絞り込む]をクリックして下さい。</span></span></h3>

            <!--<input type="hidden" name="category_id" value="<!--{$type}-->" />-->
            <table border="0" cellspacing="0" cellpadding="0">
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
    <input type="button" value="絞り込み解除" class="" onclick="location.href='<!--{$smarty.const.HTTP_URL}-->maker_<!--{$ma}-->_<!--{$keijyo}-->.html'" />
<!--{/if}-->
</div>

<p class="txt_R"><span class="att3">※絞込の検索結果は下記に表示※</span></p>

</div>
<!--▲list_detailSerchbox-->

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
            <td class="ph_td"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_S.jpg" alt="シングル" width="150" height="140" title="シングル"><br></td>
            <td class="ph_td"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_Tw.jpg" alt="同時ツイン" width="150" height="140" title="同時ツイン"></td>
            <td class="ph_td"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_Tr.jpg" alt="同時トリプル" width="150" height="140" title="同時トリプル"></td>
            <td class="ph_td"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_F.jpg" alt="同時フォー" width="150" height="140" title="同時フォー"></td>
        </tr>
        <tr>

  <!--{if $arrNormalData_line1[1]|@count > 0 }-->

  <!--{foreach from=$arrNormalData_line1 key=nums item=arrData}-->
            <td>
                <ul class="style_pw">
    <!--{foreach from=$arrData key=id item=value}-->
                    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$mi}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
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
                    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$mi}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
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
            <td class="ph_td"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_Tw.jpg" alt="個別ツイン" width="150" height="140" title="個別ツイン"></td>
            <td class="ph_td"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_Tr.jpg" alt="個別トリプル" width="150" height="140" title="個別トリプル"></td>
            <td class="ph_td"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_F.jpg" alt="個別フォー" width="150" height="140" title="個別フォー"></td>
            <td rowspan="2"></td>
        </tr>
        <tr>


<!--{if $arrSuperData_line2[1]|@count == 0 }-->

  <!--{foreach from=$arrNormalData_line2 key=nums item=arrData}-->
            <td>
                <ul class="style_pw">
    <!--{foreach from=$arrData key=id item=value}-->
                    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$mi}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
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
                    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$id}-->&maker_id=<!--{$mi}-->&name=&mode=power&orderby=price&op_4=<!--{$op4}-->&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
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
            <td class="ph_td"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_S.jpg" alt="冷房専用" width="150" height="140" title="冷房専用"></td>
            <td class="ph_td"><img src="/images/ind_aircon/ecoitem_<!--{$maker_img}-->_<!--{$aircon_img}-->_S.jpg" alt="寒冷地用" width="150" height="140" title="冷房専用"></td>
            <td rowspan="2"></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
      <!--{foreach from=$arrNormalData_line3 key=nums item=arrData}-->
            <td>
                <ul class="style_pw">
        <!--{foreach from=$arrData key=key item=value}-->
            <!--{if $nums==1}-->
                    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$value.id}-->&maker_id=<!--{$mi}-->&name=<!--{$value.name}-->&mode=power&orderby=price&op_4=3&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
            <!--{else if $nums==2}-->
                    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=<!--{$value.id}-->&maker_id=<!--{$mi}-->&name=<!--{$value.name}-->&mode=power&orderby=price&op_4=4&op_5=<!--{$op5}-->&op_6=<!--{$op6}-->"><!--{$value.value}--></a></li>
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

