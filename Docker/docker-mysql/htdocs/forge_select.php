<?php
require 'common.php';
    try
    {
      $target_id = $_GET['target_item_id'];

      // データの取得
      $items = $pdo->prepare("SELECT * FROM items WHERE item_count > 0 AND item_id != ?");
      $items->execute([$target_id]);
    }
    catch(PDOException $e)
    {
      echo "データベースエラー: " . $e->getMessage();
      exit();
    }

$title = "ベースアイテム選択";
require_once 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>ベースアイテム選択画面</title>
</head>
<body>
  <h1 style="color: gold">ベースにするアイテムを選択</h1>

  <p style="color: gray;">強化のベースとして使用するアイテムを選択してください</p>
  <p1 style="color: red;">(強化アイテムID : <?= $target_id ?>)</p1>

  <ul>
    <?php foreach($items as $item): ?>
        <li>
            <?= htmlspecialchars($item['item_name'], ENT_QUOTES) ?>
            <a href="forge_select_material.php?
              target_item_id=<?= $target_id ?>&
              base_item_id=<?= $item['item_id'] ?>"> 選択            
                （所持数：<?= $item['item_count'] ?>）
            </a>
        </li>
    <?php endforeach; ?>
  </ul><br>

  <form action="forge_entrance.php" method="post">
        <button type="submit" name="back">戻る</button>
  </form>

</body>
</html>
