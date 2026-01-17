<?php
require_once 'common.php';

$player1_id = $_SESSION['user_id'];
$player1_name = $_SESSION['user_name'];
$opponents = [];

$title = "対戦画面";
require_once 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body>
    <h1 style="color: gold; padding: 20px;">対戦画面</h1>

    <form action="battle_select_opponent.php" method="post">
        <button type="submit" style="width: 200px; padding: 5px" name="gacha_start">対戦相手選択</button>
    </form><br>

    <form action="menu.php" method="post">
        <button type="submit" style="width: 200px; padding: 5px" name="back">メニュー画面</button>
    </form>
</body>
</html>