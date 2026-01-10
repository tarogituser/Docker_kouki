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
    }
    catch(PDOException $e)
    {
      echo "データベースエラー: " . $e->getMessage();
      exit();
    }

$title = "アイテム強化画面";
require_once 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>選択画面</title>
</head>
<body>
  <h1>強化アイテムを選択</h1>

  <p style="color: gray;">強化に使用するアイテムを選択してください</p>

  <ul>
    <?php foreach($items as $item): ?>
      <li>
          <a href="forge_select.php?target_item_id=<?= $item['item_id'] ?>">
              <?= htmlspecialchars($item['item_name'], ENT_QUOTES) ?>
              （所持数：<?= $item['item_count'] ?>）
          </a>
      </li>
    <?php endforeach; ?>
  </ul><br>

  <form action="menu.php" method="post">
        <button type="submit" name="gacha_start">メニュー画面</button>
  </form>

</body>
</html>