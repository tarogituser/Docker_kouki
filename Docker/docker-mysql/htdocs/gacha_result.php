<?php
$host = 'mysql';
// mysql接続用のユーザー
$username = 'data_user';
$password = 'data';
$database = 'sosyage';

try
{
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8");  // UTF-8エンコーディングを設定
    // アイテム情報の取得
    $stmt = $pdo->query("SELECT item_id, item_name, weight FROM items JOIN gacha_lineup ON items.item_id = gacha_lineup.item_id");
    // 取得したデータをセッションに保存
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e)
{
    echo "データベースエラー: " . $e->getMessage();
    exit();
}

#アイテムの抽選
function drawItem($items)
{
    #ガチャの重みの合計
    $totalWeight = 0;
    foreach ($items as $item){
        $totalWeight += $item['weight'];
    }

    $hit = rand(1, $totalWeight);
    $currentWeight = 0;
    foreach ($items as $item) {
        $currentWeight += $item['weight'];
        if ($hit <= $currentWeight)
            return $item;
    }
}

$results = [];
for ($i = 0; $i < 10; $i++){
    $results[] = drawItem($items);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ガチャ結果</title>
</head>
<body>
    <h1>ガチャ結果</h1>
    <ul>
        <?php foreach ($results as $result): ?>
            <li>アイテムID: <?= htmlspecialchars($result['item_id']); ?>, アイテム名: <?= htmlspecialchars($result['item_name']); ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="gacha.html">もう一度ガチャを引く</a>
</body>
</html>
