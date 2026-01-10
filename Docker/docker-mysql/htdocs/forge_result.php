<?php
require 'common.php';
require_once 'header.php';

// 作成された user_id を取得
$userId = $_SESSION['user_id'];
// ユーザー専用 items テーブル名
$tableName = "items_user_" . intval($userId);

$target_id = $_GET['target_item_id'];
$base_id = $_GET['base_item_id'];
$material_id = $_GET['material_item_id'];

$pdo->beginTransaction();

//所持数チェック
$stmt = $pdo->prepare("SELECT * FROM $tableName WHERE item_id = ?");
$stmt->execute([$material_id]);
$material = $stmt->fetch();

if ($material['item_count'] == 0) {
  $pdo->rollBack();
  die('素材が足りません！');
}

$stmt->execute([$base_id]);
$base = $stmt->fetch();

//強化の成功率
$success_rate;
switch ($material_id)
{
  case 1: $success_rate = 10;  //神アイテム
  case 2: $success_rate = 30;  //激レアアイテム
  case 3: $success_rate = 50;  //レアアイテム
  case 4: $success_rate = 70;  //普通のアイテム
  default: $success_rate = 90; //ゴミアイテム
};

$rand = random_int(1, 100);
$success = $rand <= $success_rate;

// 素材消費
$pdo->prepare("UPDATE $tableName SET item_count = item_count - 1 WHERE item_id = ?")
->execute([$material_id]);

if ($success) {
    // ベース強化（名前に +1）
    $pdo->prepare(
        "UPDATE $tableName SET item_name = CONCAT(item_name, '+1') WHERE item_id = ?"
    )->execute([$base_id]);
}

$pdo->commit();

?>

<!DOCTYPE html>
<html>
    <head>
  <meta charset="UTF-8">
  <title>結果画面</title>
</head>
<body>
  <h1 style="color: cyan">強化結果</h1>

  <p>
    <?php if ($success): ?>
      <div class="success">
            <h1>🎉 強化成功！ 🎉</h1>
      </div>

      <p>
        消費アイテム
        <ul>
            ベース: <?= htmlspecialchars($base['item_name']) ?> <br>
            素材: <?= htmlspecialchars($material['item_name']) ?>
        </ul>
      </p>
     
    <?php else: ?>
      <div class="failure">
            <h1>❌ 強化失敗… ❌</h1>
      </div>
    <?php endif; ?>
  </p>
 
  <a href="forge_entrance.php">戻る</a>

</body>
</html>