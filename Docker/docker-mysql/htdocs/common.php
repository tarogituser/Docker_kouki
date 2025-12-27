<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//日本語対応
$pdo->exec("set names utf8"); // UTF-8エンコーディングを設定
?>
