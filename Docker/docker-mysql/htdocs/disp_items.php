<?php
require 'common.php';

try
{
    // 作成された user_id を取得
    $userId = $_SESSION['user_id'];
    // ユーザー専用 items テーブル名
    $tableName = "items_user_" . intval($userId);

    // データの取得
    $stmt = $pdo->query("SELECT * FROM $tableName WHERE item_count > 0");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM users WHERE user_id = $userId");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
catch(PDOException $e)
{
    echo "データベースエラー: " . $e->getMessage();
    exit();
}

$title = "所持アイテム一覧";
require_once 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body>
  <h1>所持アイテム一覧</h1>

  <?php if (count($items) > 0): ?>
    <p style="color: gray;"><?=$user['user_name']?>が所持しているアイテムは以下の通りです。</p>

    <ul>
      <?php foreach($items as $item): ?>
        <li>
            <a>
                <?= htmlspecialchars($item['item_name'], ENT_QUOTES) ?>
                （所持数：<?= $item['item_count'] ?> 攻撃: <?= $item['attack'] ?> 防御 <?= $item['defense'] ?>）
            </a>
        </li>
      <?php endforeach; ?>
    </ul><br>

    <form action="menu.php" method="post">
          <button type="submit" name="back">メニュー画面</button>
    </form>
  
  <?php else: ?>
    <p style="color: gray;">アイテムがありません。</p><br>

    <form action="menu.php" method="post">
          <button type="submit" name="back">メニュー画面</button>
    </form>

  <?php endif; ?>

</body>
</html>
