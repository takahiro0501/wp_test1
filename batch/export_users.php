<?php

//DB接続
$dsn = 'mysql:dbname=udemy_db;host=db;charset=utf8';
$user = 'root';
$password = 'udemy';

try{
    $dbh = new PDO($dsn, $user, $password);

    $sql = "SELECT * FROM users order by id asc";
    $user_datas = $dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);

}catch (PDOException $e){
    var_dump('Error:'.$e->getMessage());
}

$dbh = null;

//ファイル書き込み
$fp = fopen('./export_users.csv', 'w');
//ヘッダー定義
fputs($fp,'id,name,name_kana,birthday,gender,organization,post,start_date,tes,mail,created,updated'."\r\n");
//1行ずつデータ書きだし（ループ）
foreach($user_datas as $data){
    $result = implode(',', $data);
    fputs($fp, $result."\r\n");
}

//ファイルクローズ
fclose($fp);

?>