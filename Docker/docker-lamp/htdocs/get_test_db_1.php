<?php
// データベースへ接続するために必要な情報
// ホストはDBコンテナ
$host = 'mysql';
// mysql接続用のユーザー
$username = 'data_user';
$password = 'data';
$database = 'test_db';

// データベースへ接続するためのクラス生成
$mysql = new mysqli($host, $username, $password, $database);
if ($mysql->connect_error){
    die("データベース接続エラー:" .$mysql->connect_error);
}

//URLパラメータ
$id = 0;
if (isset($_GET['id'])){
    $id = $_GET['id'];
}

//SQLクエリ
if ($id){
    $sql = "SELECT * FROM test_table where id = " . $id;
}else{
    $sql = "SELECT * FROM test_table";
}

//クエリの実行
$result = $mysql->query($sql);

if ($result){
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            //各行のデータ表示
            echo "id:". $row["id"], "name:". $row["name"], "number:". $row["example_number"], "message:". $row["example_message"]."<br>";
        }
    }else{
        echo "該当データなし";
    }
    // 結果セットを解放
    $result->free();
}else{
    echo "クエリの実行に失敗しました:". $mysql->error;
}

// データベース接続を閉じる
$mysql->close();

?>
