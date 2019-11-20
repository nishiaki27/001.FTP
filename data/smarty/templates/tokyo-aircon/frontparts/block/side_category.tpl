<!-- ▼寒冷地エアコン▼ -->
<!--{if $smarty.server.PHP_SELF|mb_strpos:'/user_data/kanrei_aircon' !== FALSE}-->
<div class="side_cate">
<div class="cateTitle">エアコンを選ぶ</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->kanrei_aircon.php" title="寒冷地用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate07-on4.jpg" alt="寒冷地用エアコン TOPページへ"></a></h4>

<h5>メーカー別</h5>
  <ul>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?transactionid=c25eeb42f010e26a387fb72e9a2a8c16cecc6eb4&orderby=price&name=%E3%83%80%E3%82%A4%E3%82%AD%E3%83%B3%E3%80%80%E5%AF%92%E5%86%B7%E5%9C%B0%E7%94%A8&search.x=0&search.y=0" title="ダイキン">ダイキン</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?transactionid=c25eeb42f010e26a387fb72e9a2a8c16cecc6eb4&orderby=price&name=%E6%9D%B1%E8%8A%9D%E3%80%80%E5%AF%92%E5%86%B7%E5%9C%B0%E7%94%A8&search.x=0&search.y=0" title="東芝">東芝</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?transactionid=c25eeb42f010e26a387fb72e9a2a8c16cecc6eb4&orderby=price&name=%E4%B8%89%E8%8F%B1%E9%9B%BB%E6%A9%9F%E3%80%80%E5%AF%92%E5%86%B7%E5%9C%B0%E7%94%A8&search.x=42&search.y=24" title="三菱電機">三菱電機</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?transactionid=c25eeb42f010e26a387fb72e9a2a8c16cecc6eb4&orderby=price&name=%E6%97%A5%E7%AB%8B%E3%80%80%E5%AF%92%E5%86%B7%E5%9C%B0&search.x=0&search.y=0" title="日立">日立</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?transactionid=c25eeb42f010e26a387fb72e9a2a8c16cecc6eb4&orderby=price&name=%E3%83%91%E3%83%8A%E3%82%BD%E3%83%8B%E3%83%83%E3%82%AF%E3%80%80%E5%AF%92%E5%86%B7%E5%9C%B0%E7%94%A8&search.x=0&search.y=0" title="パナソニック">パナソニック</a></li>
  </ul>

</div>

<div class="side_cate">
<div class="cateTitle">その他商品を探す</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_maker.php" title="業務用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate01-off4.jpg" alt="業務用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->housing/" title="ハウジングエアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate02-off4.jpg" alt="ハウジングエアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->joshitsuki/" title="産業用除湿機 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate03-off4.jpg" alt="産業用除湿機 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->buzai/" title="エアコン部材 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate04-off4.jpg" alt="エアコン部材 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon.php" title="設備用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate05-off4.jpg" alt="設備用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->building.php" title="ビル用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate06-off4.jpg" alt="ビル用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=999998&orderby=price" title="お見積り商品 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate08-off4.jpg" alt="お見積り商品 TOPページへ"></a></h4>


</div>

<!-- ▲寒冷地エアコン▲ -->

<!-- ▼ビル用エアコン▼ -->
<!--{elseif $smarty.server.PHP_SELF == "/user_data/building.php" || $smarty.server.PHP_SELF|mb_strpos:'/user_data/buil_aircon' !== FALSE}-->
<div class="side_cate">
<div class="cateTitle">エアコンを選ぶ</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->building.php" title="ビル用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate06-off4.jpg" alt="ビル用エアコン TOPページへ"></a></h4>

<h5>メーカー別</h5>
  <ul>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->buil_aircon_daikin.php" title="ダイキン">ダイキン</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->buil_aircon_toshiba.php" title="東芝">東芝</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->buil_aircon_mitsubishi.php" title="三菱電機">三菱電機</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->buil_aircon_hitachi.php" title="日立">日立</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->buil_aircon_jyuko.php" title="三菱重工">三菱重工</a></li>
  </ul>

</div>

<div class="side_cate">
<div class="cateTitle">その他商品を探す</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_maker.php" title="業務用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate01-off4.jpg" alt="業務用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->housing/" title="ハウジングエアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate02-off4.jpg" alt="ハウジングエアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->joshitsuki/" title="産業用除湿機 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate03-off4.jpg" alt="産業用除湿機 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->buzai/" title="エアコン部材 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate04-off4.jpg" alt="エアコン部材 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon.php" title="設備用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate05-off4.jpg" alt="設備用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->kanrei_aircon.php" title="寒冷地用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate07-off4.jpg" alt="寒冷地用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=999998&orderby=price" title="お見積り商品 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate08-off4.jpg" alt="お見積り商品 TOPページへ"></a></h4>
</div>

<!-- ▲ビル用エアコン▲ -->

<!-- ▼設備用エアコン▼ -->
<!--{elseif $smarty.server.PHP_SELF|mb_strpos:'/user_data/setsubi_aircon' !== FALSE}-->
<div class="side_cate">
<div class="cateTitle">エアコンを選ぶ</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon.php" title="設備用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate05-on4.jpg" alt="設備用エアコン TOPページへ"></a></h4>

<h5>メーカー別</h5>
  <ul>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon_daikin.php" title="ダイキン">ダイキン</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon_toshiba.php" title="東芝">東芝</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon_mitsubishi.php" title="三菱電機">三菱電機</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon_hitachi.php" title="日立">日立</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon_jyuko.php" title="三菱重工">三菱重工</a></li>
  </ul>

</div>

<div class="side_cate">
<div class="cateTitle">その他商品を探す</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_maker.php" title="業務用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate01-off4.jpg" alt="業務用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->housing/" title="ハウジングエアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate02-off4.jpg" alt="ハウジングエアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->joshitsuki/" title="産業用除湿機 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate03-off4.jpg" alt="産業用除湿機 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->buzai/" title="エアコン部材 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate04-off4.jpg" alt="エアコン部材 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->building.php" title="ビル用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate06-off4.jpg" alt="ビル用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->kanrei_aircon.php" title="寒冷地用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate07-off4.jpg" alt="寒冷地用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=999998&orderby=price" title="お見積り商品 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate08-off4.jpg" alt="お見積り商品 TOPページへ"></a></h4>
</div>

<!-- ▲設備用エアコン▲ -->

<!-- ▼部材▼ -->
<!--{elseif $arrSearch.category == "部材" || $smarty.server.PHP_SELF == "/buzai/index.php"  || $arrProduct.product_id >= 1000001}-->
<div class="side_cate">
<div class="cateTitle">エアコンを選ぶ</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->buzai/" title="エアコン部材 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate04-on4.jpg" alt="エアコン部材 TOPページへ"></a></h4>

<h5>形状別</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100100&orderby=price&name=ワイドパネル" title="ワイドパネル">ワイドパネル</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100100&orderby=price&name=風向" title="風向ガイド">風向ガイド</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100100&orderby=price&name=吹出ガイド" title="吹出ガイド">吹出ガイド</a></li> 
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100100&orderby=price&name=置台" title="置台">置台</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100100&orderby=price&name=木台" title="木台">木台</a></li>
</ul>

<h5>メーカー別</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100120&orderby=price&maker_id=1" title="ダイキン">ダイキン</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100140&orderby=price&maker_id=2" title="東芝">東芝</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100160&orderby=price&maker_id=3" title="三菱電機">三菱電機</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100180&orderby=price&maker_id=4" title="日立">日立</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100200&orderby=price&maker_id=5" title="三菱重工">三菱重工</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=100220&orderby=price&maker_id=6" title="パナソニック">パナソニック</a></li>
</ul>

</div>

<div class="side_cate">
<div class="cateTitle">その他商品を探す</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_maker.php" title="業務用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate01-off4.jpg" alt="業務用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->housing/" title="ハウジングエアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate02-off4.jpg" alt="ハウジングエアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->joshitsuki/" title="産業用除湿機 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate03-off4.jpg" alt="産業用除湿機 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon.php" title="設備用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate05-off4.jpg" alt="設備用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->building.php" title="ビル用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate06-off4.jpg" alt="ビル用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->kanrei_aircon.php" title="寒冷地用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate07-off4.jpg" alt="寒冷地用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=999998&orderby=price" title="お見積り商品 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate08-off4.jpg" alt="お見積り商品 TOPページへ"></a></h4>
</div>

<!-- ▲部材▲ -->

<!-- ▼産業用除湿機▼ -->
<!--{elseif $arrSearchData.category_id >= 1100000 || $smarty.server.PHP_SELF == "/joshitsuki/index.php"  || $arrProduct.product_id >= 900000}-->
<div class="side_cate">
<div class="cateTitle">エアコンを選ぶ</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->joshitsuki/" title="産業用除湿機 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate03-on4.jpg" alt="産業用除湿機 TOPページへ"></a></h4>

<h5>メーカー別</h5>
  <ul>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1100001&orderby=price" title="ダイキン">ダイキン</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1100003&orderby=price" title="三菱電機">三菱電機</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1100004&orderby=price" title="日立">日立</a></li>
  </ul>

<h5>馬力別</h5>
  <ul>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1110001&orderby=price" title="0.8馬力">0.8馬力</a></li> 
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1110004&orderby=price" title="2馬力">2馬力</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1110007&orderby=price" title="3馬力">3馬力</a></li> 
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1110008&orderby=price" title="4馬力">4馬力</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1110009&orderby=price" title="5馬力">5馬力</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1110011&orderby=price" title="8馬力
    ">8馬力</a></li>
    <li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=1110012&orderby=price" title="10馬力
    ">10馬力</a></li>
  </ul>

</div>

<div class="side_cate">
<div class="cateTitle">その他商品を探す</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_maker.php" title="業務用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate01-off4.jpg" alt="業務用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->housing/" title="ハウジングエアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate02-off4.jpg" alt="ハウジングエアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->buzai/" title="エアコン部材 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate04-off4.jpg" alt="エアコン部材 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon.php" title="設備用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate05-off4.jpg" alt="設備用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->building.php" title="ビル用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate06-off4.jpg" alt="ビル用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->kanrei_aircon.php" title="寒冷地用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate07-off4.jpg" alt="寒冷地用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=999998&orderby=price" title="お見積り商品 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate08-off4.jpg" alt="お見積り商品 TOPページへ"></a></h4>
</div>
<!-- ▲産業用除湿機▲ -->

<!-- ▼お見積り商品▼ -->
<!--{elseif $arrSearchData.category_id == 999998}-->
<div class="side_cate">
<div class="cateTitle">価格を選ぶ</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=999998&orderby=price" title="お見積り商品 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate08-on4.jpg" alt="お見積り商品 TOPページへ"></a></h4>
<h5>価格</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/mitsumori-100.html" title="100円">100円</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/mitsumori-1000.html" title="1,000円">1,000円</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/mitsumori-10000.html" title="10,000円">10,000円</a></li> 
</ul>
</div>
<div class="side_cate">
<div class="cateTitle">その他商品を探す</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_maker.php" title="業務用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate01-off4.jpg" alt="業務用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->housing/" title="ハウジングエアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate02-off4.jpg" alt="ハウジングエアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->joshitsuki/" title="産業用除湿機 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate03-off4.jpg" alt="産業用除湿機 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->buzai/" title="エアコン部材 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate04-off4.jpg" alt="エアコン部材 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon.php" title="設備用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate05-off4.jpg" alt="設備用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->building.php" title="ビル用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate06-off4.jpg" alt="ビル用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->kanrei_aircon.php" title="寒冷地用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate07-off4.jpg" alt="寒冷地用エアコン TOPページへ"></a></h4>

</div>
<!-- ▲お見積り商品▲ -->

<!-- ▼ハウジング▼ -->
<!--{elseif $arrSearchData.category_id >= 200000 || $arrProduct.product_id >= 500000 || $smarty.server.PHP_SELF == "/housing/index.php"}-->
<div class="side_cate">
<div class="cateTitle">エアコンを選ぶ</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->housing/" title="ハウジングエアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate02-on4.jpg" alt="ハウジングエアコン TOPページへ"></a></h4>


<h5>形状別</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200101&orderby=price" title="天井カセット1方向">天井カセット1方向</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200102&orderby=price" title="天井カセット2方向">天井カセット2方向</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200104&orderby=price" title="壁埋込形">壁埋込形</a></li> 
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200105&orderby=price" title="フリービルトイン形">フリービルトイン形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200106&orderby=price" title="床置形">床置形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200101&orderby=price&op_4=9" title="天井カセット小能力形">天井カセット小能力形</a></li>
</ul>

<h5>メーカー別</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->housing/index.php?maker_name=daikin" title="ダイキン">ダイキン</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->housing/index.php?maker_name=mitsubishidenki" title="三菱電機">三菱電機</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->housing/index.php?maker_name=hitachi" title="日立">日立</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->housing/index.php?maker_name=jyuko" title="三菱重工">三菱重工</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->housing/index.php?maker_name=panasonic" title="パナソニック">パナソニック</a></li>
</ul>

<h5>システムマルチ別</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200110&orderby=price" title="天井カセット1方向">天井カセット1方向</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200111&orderby=price" title="天井カセット2方向">天井カセット2方向</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200110&orderby=price&op_4=9" title="天井カセット小能力形">天井カセット小能力形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200112&orderby=price" title="壁掛形">壁掛形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200113&orderby=price" title="壁埋込形">壁埋込形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200114&orderby=price" title="フリービルトイン形">フリービルトイン形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200115&orderby=price" title="床置形">床置形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=200109&orderby=price" title="マルチ室外機形">マルチ室外機</a></li>
</ul>

</div>

<div class="side_cate">
<div class="cateTitle">その他商品を探す</div>
<h4><a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_maker.php" title="業務用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate01-off4.jpg" alt="業務用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->joshitsuki/" title="産業用除湿機 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate03-off4.jpg" alt="産業用除湿機 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->buzai/" title="エアコン部材 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate04-off4.jpg" alt="エアコン部材 TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon.php" title="設備用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate05-off4.jpg" alt="設備用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->building.php" title="ビル用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate06-off4.jpg" alt="ビル用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->kanrei_aircon.php" title="寒冷地用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate07-off4.jpg" alt="寒冷地用エアコン TOPページへ"></a></h4>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=999998&orderby=price" title="お見積り商品 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate08-off4.jpg" alt="お見積り商品 TOPページへ"></a></h4>
</div>
<!-- ▲ハウジングエアコン▲ -->

<!-- ▼業務用エアコン▼ -->
<!--{else}-->
<div class="side_cate">
<div class="cateTitle">エアコンを選ぶ</div>
<h3><a href="<!--{$smarty.const.HTTP_URL}-->ind_aircon_maker.php" title="業務用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate01-on4.jpg" alt="業務用エアコン TOPページへ"></a></h3>

<h5>形状別</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_tenkase4.html?op_4=1" title="天井カセット4方向">天井カセット形4方向</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_compact.html?op_4=1" title="天井カセットコンパクト">天井カセットコンパクト</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_tenturi.html?op_4=1" title="天井吊形">天井吊形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_kabekake.html?op_4=1" title="壁掛形">壁掛形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_yukaoki.html?op_4=1" title="床置形">床置形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_builtin.html?op_4=1" title="ビルトイン形">ビルトイン形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_duct.html?op_4=1" title="ダクト形">ダクト形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_tenkase2.html?op_4=1" title="天井カセット2方向">天井カセット形2方向</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_tenkase1.html?op_4=1" title="天井カセット1方向">天井カセット形1方向</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_wonderful.html?op_4=1" title="天吊自在形">天吊自在形</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->keijyo_chubo.html?op_4=1" title="厨房用">厨房用</a></li>
</ul>

<h5>メーカー別</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->maker_daikin.html?op_4=1" title="ダイキン">ダイキン</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->maker_toshiba.html?op_4=1" title="東芝">東芝</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishidenki.html?op_4=1" title="三菱電機">三菱電機</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->maker_hitachi.html?op_4=1" title="日立">日立</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->maker_mitsubishijyuko.html?op_4=1" title="三菱重工">三菱重工</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->maker_panasonic.html?op_4=1" title="パナソニック">パナソニック</a></li>
</ul>

<h5>馬力別</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_1_5hp.html" title="1.5馬力">1.5馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_1_8hp.html" title="1.8馬力">1.8馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_2hp.html" title="2馬力">2馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_2_3hp.html" title="2.3馬力">2.3馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_2_5hp.html" title="2.5馬力">2.5馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_3hp.html" title="3馬力">3馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_4hp.html" title="4馬力">4馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_5hp.html" title="5馬力">5馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_6hp.html" title="6馬力">6馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_8hp.html" title="8馬力">8馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_10hp.html" title="10馬力">10馬力</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->power_12hp.html" title="12馬力">12馬力</a></li>
</ul>

<h5>設置場所別</h5>
<ul>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_office.html" title="事務所">事務所</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_restaurant.html" title="飲食店">飲食店</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_shop.html" title="商店・店舗">商店・店舗</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_beautysalon.html" title="理美容室">理美容室</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_hospital.html" title="病院・医院">病院・医院</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_factory.html" title="工場">工場</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_workplace.html" title="倉庫・作業場">倉庫・作業場</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_school.html" title="学校関係">学校関係</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_hotel.html" title="宿泊施設">宿泊施設</a></li>
<li><a href="<!--{$smarty.const.HTTP_URL}-->location_other.html" title="その他">その他</a></li>
</ul>
</div>

<div class="side_cate">
<div class="cateTitle">その他商品を探す</div>
<h3><a href="<!--{$smarty.const.HTTP_URL}-->housing/" title="ハウジングエアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate02-off4.jpg" alt="ハウジングエアコン TOPページへ"></a></h3>

<h3><a href="<!--{$smarty.const.HTTP_URL}-->joshitsuki/" title="産業用除湿機 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate03-off4.jpg" alt="産業用除湿機 TOPページへ"></a></h3>

<h3><a href="<!--{$smarty.const.HTTP_URL}-->buzai/" title="エアコン部材 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate04-off4.jpg" alt="エアコン部材 TOPページへ"></a></h3>

<h3><a href="<!--{$smarty.const.HTTP_URL}-->setsubi_aircon.php" title="設備用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate05-off4.jpg" alt="設備用エアコン TOPページへ"></a></h3>

<h3><a href="<!--{$smarty.const.HTTP_URL}-->building.php" title="ビル用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate06-off4.jpg" alt="ビル用エアコン TOPページへ"></a></h3>

<h3><a href="<!--{$smarty.const.HTTP_URL}-->kanrei_aircon.php" title="寒冷地用エアコン TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate07-off4.jpg" alt="寒冷地用エアコン TOPページへ"></a></h3>

<h4><a href="<!--{$smarty.const.HTTP_URL}-->products/list.php?category_id=999998&orderby=price" title="お見積り商品 TOPページへ"><img src="<!--{$smarty.const.HTTP_URL}-->images/common/side-cate08-off4.jpg" alt="お見積り商品 TOPページへ"></a></h4>
</div>
<!-- ▲業務用エアコン▲ -->
<!--{/if}-->