<?php
require 'common.php';

try
{
    // アイテム情報の取得
    $stmt = $pdo->query("SELECT item_id, item_name, weight FROM items");
    // 取得したデータをセッションに保存
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e)
{
    echo "データベースエラー: " . $e->getMessage();
    exit();
}

#重み合計
$totalWeight = array_sum(array_column($items, 'weight'));

#アイテムの抽選
function drawItem($items, $totalWeight)
{
    $hit = mt_rand(1, $totalWeight);
    $currentWeight = 0;
    foreach ($items as $item) {
        $currentWeight += $item['weight'];
        if ($hit <= $currentWeight)
            return $item;
    }

    return $items[array_key_last($items)];
}

//10連ガチャ
$results = [];

$pdo->beginTransaction();

for ($i = 0; $i < 10; $i++){
    $item = drawItem($items, $totalWeight);
    $results[] = $item['item_name'];

    // 所持数を増やす
    $update = $pdo->prepare(
        "UPDATE items SET item_count = item_count + 1 WHERE item_id = ?"
    );
    $update->execute([$item['item_id']]);
}

$pdo->commit();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>10連ガチャ結果</title>
</head>
<body>
    <h1>🎰 10連ガチャ結果 🎰</h1>
    <?php foreach ($results as $i => $name): ?>
            <div class="result">
                <?= $i + 1 ?>回目：<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
            </div>
    <?php endforeach; ?>
    <a href="gacha.html">もう一度ガチャを引く</a>
</body>
</html>
