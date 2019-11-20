<?php

require_once '../../data/config/config.php';

$url = HTTP_URL;
$server = "";
//設備コム
if(strpos($url, "setsu-bi") !== false){
	$server = "setsubi";
//イーセツビ
}elseif(strpos($url, "e-setsubi") !== false || strpos($url, "e-kaizen") !== false){
	$server = "esetsubi";
//空調センター
}elseif(strpos($url, "tokyo-aircon") !== false){
	$server = "kutyo";
}
if(!$server){
	echo 'config data is not found '.$url;
	exit();
}


$postg_dsn = 'pgsql:dbname='.DB_NAME.';host='.DB_SERVER;
if(DB_PORT){
	$postg_dsn .= ';port='.DB_PORT;
}
$postg_user = DB_USER;
$postg_pass = DB_PASSWORD;

$mysql_dsn = 'mysql:dbname=kk_data;host=mysqls51-16.kagoya.net;charset=utf8';
$mysql_user = 'kir471336';
$mysql_pass = 'mitaden123';

$table_list = array();
$table_list[] = array(
	'select_table_name' => "dtb_products_class",
	'select_table_sql' => "SELECT * FROM dtb_products_class",
	'update_table_name' => $server,
);
if($server == "esetsubi"){
	$table_list[] = array(
		'select_table_name' => "dtb_products",
		'select_table_sql' => "SELECT product_id,comment1,comment4 FROM dtb_products",
		'update_table_name' => "kakaku_prod",
	);
	$table_list[] = array(
		'select_table_name' => "dtb_products_spec",
		'select_table_sql' => "SELECT product_id,product_code,series,inside_size1,inside_weight1,cool_capability,heating_capacity,outside_size1,outside_weight1,breaker,power_distribution_line,pipe_size1,chargeless_size,max_piping_size,maximum_vertical_interval,drain_pipe_size FROM dtb_products_spec where inside_size1 != '' AND inside_size1 IS NOT NULL",
		'update_table_name' => "kakaku_spec",
	);
}


//HTML特殊文字をエスケープする関数
function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

set_time_limit(0);
//データベースに接続
try{
	$pdo = new PDO(
		$postg_dsn,
		$postg_user,
		$postg_pass,
		array(
			//SQLエラー発生時にPDOExceptionをスローさせる
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		)
	);
}catch (PDOException $e){
	print('Error:'.$e->getMessage());
	echo $select_table_sql;
	die();
}


try{
	$pdo2 = new PDO(
		$mysql_dsn,
		$mysql_user,
		$mysql_pass,
		array(
			//カラム型に合わない値がINSERTされようとしたときSQLエラーとする
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode='TRADITIONAL'",
			//SQLエラー発生時にPDOExceptionをスローさせる
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			//プリペアドステートメントのエミュレーションを無効化する
			PDO::ATTR_EMULATE_PREPARES => false,
		)
	);
}catch (PDOException $e){
	print('Error:'.$e->getMessage());
	die();
}

$count = 0;

foreach($table_list as $update_table){
	$update_tablename = $update_table["update_table_name"];
	$select_tablename = $update_table["select_table_name"];
	$select_tablesql = $update_table["select_table_sql"];
	$csv_filename = dirname(__FILE__)."/".$select_tablename.".csv";

	//ファイルが存在している場合、削除
	if(file_exists($csv_filename)){
		unlink($csv_filename);
	}



	try {
    /*
      define csv file information
    */
    $file_path = $csv_filename;
    $export_sql = $select_tablesql;

    /*
        Make CSV content
     */
    if(touch($file_path)){
        $file = new SplFileObject($file_path, "w");

        // query database
        $stmt = $pdo->query($export_sql);

        // create csv sentences
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $file->fputcsv($row);

        }

    }




		//トランザクション処理
		$pdo2->beginTransaction();
		try {
			$fp = fopen($csv_filename, 'rb');
			//ヘッダー取得
			$row = fgetcsv($fp);
			$column_count = count($row);

			$del_stmt = $pdo2->prepare('DELETE FROM `'.$update_tablename."_temp".'`');
			$del_stmt -> execute();

			$sql = 'INSERT INTO `'.$update_tablename."_temp".'` VALUES (';
			for($i=0; $i<count($row); $i++){
				$sql .= '?';
				if($i < count($row)-1){
					$sql .= ', ';
				}
			}
			$sql .= ')';
			$stmt = $pdo2->prepare($sql);

			while ($row = fgetcsv($fp)) {
				foreach ($row as &$rows) {
					if(!$rows) {
						$rows = 0;
					}
				}
				unset($rows);
				if ($row === array(null)) {
					//空行はスキップ
					continue;
				}
				if (count($row) !== $column_count) {
					//カラム数が異なる無効なフォーマット
					throw new RuntimeException('Invalid column detected');
				}
				$executed = $stmt->execute($row);
			}
			if (!feof($fp)) {
				//ファイルポインタが終端に達していなければエラー
				throw new RuntimeException('CSV parsing error');
			}

			fclose($fp);

			//テーブル名を入れ替え
			$sql = "ALTER TABLE ".$update_tablename." RENAME ".$update_tablename."_temp2";
			$stmt = $pdo2 -> prepare($sql);
			$stmt -> execute();
			$sql = "ALTER TABLE ".$update_tablename."_temp RENAME ".$update_tablename;
			$stmt = $pdo2 -> prepare($sql);
			$stmt -> execute();
			$sql = "ALTER TABLE ".$update_tablename."_temp2 RENAME ".$update_tablename."_temp";
			$stmt = $pdo2 -> prepare($sql);
			$stmt -> execute();
			$pdo2->commit();
		} catch (Exception $e) {
			fclose($fp);
			$pdo2->rollBack();
			throw $e;
		}

		//結果メッセージをセット
		if (isset($executed)) {
			//1回以上実行された
			$msg = array('green', 'Import successful');
		} else {
			//1回も実行されなかった
			$msg = array('black', 'There were nothing to import');
		}

	} catch (Exception $e) {

		//エラーメッセージをセット
		$msg = array('red', $e->getMessage());

	}

	//ファイルが存在している場合、削除
	if(file_exists($csv_filename)){
		unlink($csv_filename);
	}
	$count++;
}

//XHTMLとしてブラウザに認識させる
//(IE8以下はサポート対象外ｗ)
header('Content-Type: application/xhtml+xml; charset=utf-8');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>CSV to MySQL importation test</title>
</head>
<body>
<?php if (isset($msg)): ?>
	<fieldset>
		<legend>Result</legend>
		<span style="color:<?php echo h($msg[0]); ?>;"><?php echo h($msg[1]); ?></span>
	</fieldset>
<?php endif; ?>
</body>
</html>
