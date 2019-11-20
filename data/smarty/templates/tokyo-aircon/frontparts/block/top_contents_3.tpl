<!--{php}-->

$n = array(date("j日"));
 
for($i = 0; $i <= count($n)-1; $i++){
    if($n[$i] % 2 == 0) {
        // 偶数の場合の処理
        echo $n[$i] .'<div style="font-size:35px;">い</div><br />';
    }else{
        // 奇数の場合の処理
        echo $n[$i] .'<div style="font-size:35px;">あ</div><br />';
    }
}


<!--{/php}-->