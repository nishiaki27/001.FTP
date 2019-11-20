<?php

//$server = "setsubi_test";//設備コム
//$server = "esetsubi_test";//イーセツビ
$server = "o4043-481.kagoya.net";//空調センター
$server_host = "localhost";
$server_db = "fs_eccube";
$server_user = "tokyo_aircon";
$server_pass = "7dgaCBAhptyrZaDT";
$server_port = "5432";

$dsn2  = 'mysql:dbname=kk_data;host=mysqls51-16.kagoya.net;charset=utf8';
$user  = 'kir471336';
$pass  = 'mitaden123';

$tablename = "dtb_products_class";
$csv_filename = dirname(__FILE__)."/".$tablename.".csv";


/* HTML特殊文字をエスケープする関数 */
function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//接続
$conect = pg_connect("host=".$server_host." dbname=".$server_db." port=".$server_port." user=".$server_user." password=".$server_pass);
if(!$conect){
	echo '接続に失敗しました';
	die();
}
//ファイルが存在している場合、削除
if(file_exists($csv_filename)){
	unlink($csv_filename);
}
$command = "COPY ".$tablename." TO '".$csv_filename."' WITH CSV DELIMITER ','";
$result = pg_query($conect, $command);
pg_close($conect);
if (!$result) {
	echo 'CSVファイル作成に失敗しました';
	die();
}

try {
	$tmp_name = $csv_filename;
	$detect_order = 'ASCII,JIS,UTF-8,CP51932,SJIS-win';
	setlocale(LC_ALL, 'ja_JP.UTF-8');

	/* 文字コードを変換してファイルを置換 */
	$buffer = file_get_contents($tmp_name);
	if (!$encoding = mb_detect_encoding($buffer, $detect_order, true)) {
		// 文字コードの自動判定に失敗
		unset($buffer);
		throw new RuntimeException('Character set detection failed');
	}
	file_put_contents($tmp_name, mb_convert_encoding($buffer, 'UTF-8', $encoding));
	unset($buffer);

	/* データベースに接続 */
	$pdo = new PDO(
		$dsn2,
		$user,
		$pass,
		array(
			// カラム型に合わない値がINSERTされようとしたときSQLエラーとする
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode='TRADITIONAL'",
			// SQLエラー発生時にPDOExceptionをスローさせる
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			// プリペアドステートメントのエミュレーションを無効化する
			PDO::ATTR_EMULATE_PREPARES => false,
		)
	);

	$stmt = $pdo->prepare('INSERT INTO `'.$server."_temp".'` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

	/* トランザクション処理 */
	$pdo->beginTransaction();
	try {
		$del_stmt = $pdo->prepare('DELETE FROM `'.$server."_temp".'`');
		$del_stmt -> execute();

		$fp = fopen($tmp_name, 'rb');
		while ($row = fgetcsv($fp)) {
			foreach ($row as &$rows) {
				if(!$rows) {
					$rows = 0;
				}
			}
			unset($rows);
			if ($row === array(null)) {
				// 空行はスキップ
				continue;
			}
			if (count($row) !== 18) {
				// カラム数が異なる無効なフォーマット
				throw new RuntimeException('Invalid column detected');
			}
			$executed = $stmt->execute($row);
		}
		if (!feof($fp)) {
			// ファイルポインタが終端に達していなければエラー
			throw new RuntimeException('CSV parsing error');
		}
		fclose($fp);

		//テーブル名を入れ替え
		$sql = "ALTER TABLE ".$server." RENAME ".$server."_temp2";
		$stmt = $pdo -> prepare($sql);
		$stmt -> execute();
		$sql = "ALTER TABLE ".$server."_temp RENAME ".$server;
		$stmt = $pdo -> prepare($sql);
		$stmt -> execute();
		$sql = "ALTER TABLE ".$server."_temp2 RENAME ".$server."_temp";
		$stmt = $pdo -> prepare($sql);
		$stmt -> execute();
		$pdo->commit();
	} catch (Exception $e) {
		fclose($fp);
		$pdo->rollBack();
		throw $e;
	}

	/* 結果メッセージをセット */
	if (isset($executed)) {
		// 1回以上実行された
		$msg = array('green', 'Import successful');
	} else {
		// 1回も実行されなかった
		$msg = array('black', 'There were nothing to import');
	}
} catch (Exception $e) {

	/* エラーメッセージをセット */
	$msg = array('red', $e->getMessage());

}

// XHTMLとしてブラウザに認識させる
// (IE8以下はサポート対象外ｗ)
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
