<?php
//共通データベース
$host = 'mysql';
// mysql接続用のユーザー
$username = 'data_user';
$password = 'data';
$database = 'sosyage';

$pdo = new PDO(
    "mysql:host=$host;dbname=$database;charset=utf8", 
    $username, 
    $password,
);

//日本語対応
$pdo->exec("set names utf8"); // UTF-8エンコーディングを設定
?>
