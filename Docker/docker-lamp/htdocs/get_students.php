<?php
// データベースへ接続するために必要な情報
// ホストはDBコンテナ
$host = 'mysql';
// mysql接続用のユーザー
$username = 'data_user';
$password = 'data';
$database = 'data_master';

try{
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sdmt = $pdo->query("SELECT * FROM students");
    $results = $sdmt->fetchAll(PDO::FETCH_ASSOC);

    //セッションに保存
    session_start();
    $_SESSION['data'] = $results;

    //リダイレクト
    header("Location: display_students.php");
    exit();
}
catch(PDOException $e){
    echo "データベースエラー". $e->getMessage();
}
?>