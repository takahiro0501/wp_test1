<?php

//csvファイル読み込み
//配列格納
//ファイルクローズ
//DB接続
//CSVデータごとにループ処理
////SQL：社員番号が一致確認
//////もし一致していたら,update処理
//////もし一致していなかったら,insert処理
//DB解除

//DB接続
$dsn = 'mysql:dbname=udemy_db;host=db;charset=utf8';
$user = 'root';
$password = 'udemy';
try{
    $dbh = new PDO($dsn, $user, $password);
}catch (PDOException $e){
    var_dump('Error:'.$e->getMessage());
}

//ファイルオープン
$fp = fopen('./import_users.csv', 'r');

//sql定義
$searchSql = 'select * from users where id = :id';
$insertSql = 'insert into users values (:id,:name,:name_kana,:birthday,:gender,:organization,:post,:start_date,:tel,:mail_address,:created,:updated)';
$updateSql = 'update users set id=:id,name=:name,name_kana=:name_kana,birthday=:birthday,gender=:gender,organization=:organization,post=:post,start_date=:start_date,tel=:tel,mail_address=:mail_address,created=:created,updated=:updated)';


//トランザクション開始
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->beginTransaction();
try{
    //1行ずつ読み込み
    while($staff = fgetcsv($fp)){
        $sth = $dbh->prepare($searchSql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $sth->execute(['id' => $staff[0]]);
        $result = $sth->fetch();
        if($result){   
            $stm = $dbh->prepare($updateSql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $stm->execute(['id' => $staff[0],
                            'name' => $staff[1],
                            'name_kana' => $staff[2],
                            'birthday' => $staff[3],
                            'gender' => $staff[4],
                            'organization' => $staff[5],
                            'post' => $staff[6],
                            'start_date' => $staff[7],
                            'tel' => $staff[8],
                            'mail_address' => $staff[9],
                            'created' => date("Y-m-d H:i:s"),
                            'updated' => date("Y-m-d H:i:s")
            ]);
        } else {
            $stm = $dbh->prepare($insertSql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $stm->execute(['id' => $staff[0],
                            'name' => $staff[1],
                            'name_kana' => $staff[2],
                            'birthday' => $staff[3],
                            'gender' => $staff[4],
                            'organization' => $staff[5],
                            'post' => $staff[6],
                            'start_date' => $staff[7],
                            'tel' => $staff[8],
                            'mail_address' => $staff[9],
                            'created' => date("Y-m-d H:i:s"),
                            'updated' => date("Y-m-d H:i:s")
            ]);
        }
    } 
}catch (Exception $e) {
    $dbh->rollBack();
}

$dbh->commit();
$dbh = null;
fclose($fp);

/*
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
*/



?>