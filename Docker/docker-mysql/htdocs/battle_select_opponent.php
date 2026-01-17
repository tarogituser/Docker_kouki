<?php
require_once 'common.php';

$player1_id = $_SESSION['user_id'];
$player1_name = $_SESSION['user_name'];
$opponents = [];

try
{
    //自分以外のユーザー
    $stmt = $pdo->prepare("SELECT user_id, user_name FROM users WHERE user_id != :current_user_id ORDER BY user_id");
    $stmt->bindParam(':current_user_id', $player1_id, PDO::PARAM_INT);
    $stmt->execute();
    $opponents = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    $error_message = "データベースエラー: " . $e->getMessage();
}

$message = "対戦相手選択";
require_once 'header.php';
?>