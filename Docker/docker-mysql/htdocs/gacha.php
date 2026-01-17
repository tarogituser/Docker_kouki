<?php
require_once 'common.php';
$title = "10連ガチャ";
require_once 'header.php';
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>10連ガチャ</title>
  </head>
  <body>
    <meta charset="UTF-8">
    <h1>ガチャシミュレーション</h1>

    <form action="gacha_result.php" method="post">
        <button type="submit" style="width: 200px; padding: 5px" name="gacha_start">10連ガチャを開始</button>
    </form><br>

    <form action="menu.php" method="post">
        <button type="submit" style="width: 200px; padding: 5px" name="gacha_start">メニュー画面</button>
    </form>
  </body>
</html>