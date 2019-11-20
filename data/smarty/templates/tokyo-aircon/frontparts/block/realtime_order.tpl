
<!--{php}-->

$conn = "host=localhost port=5432 dbname=fs_eccube user=tokyo_aircon password=7dgaCBAhptyrZaDT";
$link = pg_connect($conn);
if (!$link) {
    die('接続失敗です。'.pg_last_error());
}

//print('接続に成功しました。<br>');

pg_set_client_encoding("utf8");

$result = pg_query('SELECT "id","item_id","area","date" FROM "public"."dtb_timeorder" WHERE "date" < \''.date("Y-m-d H:i:s").'\' ORDER BY "date" DESC LIMIT 30');
if (!$result) {
    die('クエリーが失敗しました。'.pg_last_error());
}

print('<h2 class="ttl_search style01"><span>現在のご注文状況</span></h2>
<!--<ul class="list_table realtime_order">
<li><span class="li_order" style="background-color:#f2f2f2; font-weight:bold">商品画像</span><span class="li_order2" style="background-color:#f2f2f2; font-weight:bold">お買い上げ商品名</span><span class="li_order3" style="background-color:#f2f2f2; font-weight:bold">ご住所</span><span class="li_order4" style="background-color:#f2f2f2; font-weight:bold">ご購入日</span></li></ul>-->
<div id="list_box">');

for ($i = 0 ; $i < pg_num_rows($result) ; $i++){
    $row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
    $results = pg_query('SELECT "name","main_image","comment4" FROM "public"."dtb_products" WHERE "product_id" = \''.$row['item_id'].'\'');
    for ($j = 0 ; $j < pg_num_rows($results) ; $j++){
    	$rows = pg_fetch_array($results, NULL, PGSQL_ASSOC);
    }
/*    print(',id='.$row['id'].'<br>');
    print(',name='.$row['item_id'].'<br>');
    print(',area='.$row['area'].'<br>');
    print(',time='.$row['date'].'<br>');
*/

$date = $row['date'];
$date2 = date('Y-m-d H:i', strtotime($date));

    print('<ul class="list_table realtime_order"><li><span class="li_order01">
<!--商品写真--><a href="https://www.tokyo-aircon.net/products/'.$rows['comment4'].'.html"><img src="https://www.tokyo-aircon.net/upload/save_image/'.$rows['main_image'].'" alt="'.$rows['comment4'].' '.$rows['name'].'" width="60"/></a></span><span class="li_order02"><a href="https://www.tokyo-aircon.net/products/'.$rows['comment4'].'.html">'.$rows['name'].'</a></span><span class="li_order03">'.$row['area'].'</span><span class="li_order04">'.$date2.'</span></li></ul>');
}

print('</ul></div>');

$close_flag = pg_close($link);

if ($close_flag){
//    print('切断に成功しました。<br>');
}

<!--{/php}-->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script>
jQuery( function() {
	autoScroll();
} );
var $scrollY = 0;
function autoScroll() {
	var $sampleBox = jQuery( '#list_box' );
	$sampleBox.scrollTop( ++$scrollY );
	if( $scrollY < $sampleBox[0].scrollHeight - $sampleBox[0].clientHeight ){
	setTimeout( "autoScroll()", 30 );
	}else{
	$scrollY = 0;
	$sampleBox.scrollTop( 0 );
	setTimeout( "autoScroll()", 30 );
}}
</script>