<?php
require 'common.php';
    try
    {
      $target_id = $_GET['target_item_id'];
      $base_id = $_GET['base_item_id'];

      // データの取得
      $items = $pdo->prepare("SELECT * FROM items WHERE item_count > 0 AND item_id NOT IN (?, ?)");
      $items->execute([$target_id, $base_id]);
    }
    catch(PDOException $e)
    {
      echo "データベースエラー: " . $e->getMessage();
      exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
  <meta charset="UTF-8">
  <title>素材アイテム選択画面</title>
</head>
<body>
  <h1 style="color: gold">素材アイテムを選択</h1>

  <p style="color: gray;">強化の素材として使用するアイテムを選択してください</p>
  <p1 style="color: red;">(強化アイテムID : <?= $target_id ?>)</p1>

  <ul>
    <?php foreach($items as $item): ?>
        <li>
            <?= htmlspecialchars($item['item_name'], ENT_QUOTES) ?>
            <a href="forge_result.php?target_item_id=<?= $target_id ?>
            &base_item_id=<?= $base_id ?>
            &material_item_id=<?= $item['item_id'] ?>">強化開始
           （所持数：<?= $item['item_count'] ?>）
            </a>
        </li>
    <?php endforeach; ?>
  </ul>

</body>
</html>