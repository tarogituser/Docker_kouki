<?php
require_once 'common.php';

try
{
    // アイテム情報の取得
    $stmt = $pdo->query("SELECT item_id, item_name, attack, defense, weight FROM items");
    // 取得したデータをセッションに保存
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e)
{
    echo "データベースエラー: " . $e->getMessage();
    exit();
}

$title = "10連ガチャ結果";
require_once 'header.php';

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

// 作成された user_id を取得
$userId = $_SESSION['user_id'];
// ユーザー専用 items テーブル名
$tableName = "items_user_" . intval($userId);

$pdo->beginTransaction();

for ($i = 0; $i < 10; $i++){
    $item = drawItem($items, $totalWeight);
    // レア度判定
    if (strpos($item['item_name'], '神') !== false || strpos($item['item_name'], '激レア') !== false) {
        $rarity = 'super-rare';
    } elseif (strpos($item['item_name'], 'レア') !== false) {
        $rarity = 'rare';
    } else {
        $rarity = 'normal';
    }

    $results[] = [
        'name' => $item['item_name'],
        'rarity' => $rarity
    ];

    // すでに同じ item が存在するかチェック
    $check = $pdo->prepare("SELECT item_id, item_name FROM $tableName WHERE item_name = :name");
    $check->execute([':name' => $item['item_name']]);
    $existingItem = $check->fetch(PDO::FETCH_ASSOC);

    if (!$existingItem){
        // テーブルにアイテム追加
        $stmt = $pdo->prepare("INSERT INTO $tableName (item_id, item_name, attack, defense) 
        VALUES (:id, :name, :attack, :defense)");
        $stmt->execute([':id' => $item['item_id'], ':name' => $item['item_name'],
        ':attack' => $item['attack'], ':defense' => $item['defense']]);
    }

    // 所持数を増やす
    $update = $pdo->prepare(
        "UPDATE $tableName SET item_count = item_count + 1 WHERE item_id = ?"
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
    <style>
        body {
            font-family: sans-serif;
            background: #111;
            color: #fff;
        }

        .result {
            font-size: 20px;
            margin: 8px 0;
            padding: 10px;
            border-radius: 6px;
        }

        /* 通常 */
        .normal {
            background: #333;
        }

        /* レア */
        .rare {
            background: linear-gradient(45deg, gold, orange);
            color: #000;
            box-shadow: 0 0 15px gold;
            animation: glow 1.5s infinite alternate;
        }

        /* 激レア・神 */
        .super-rare {
            background: linear-gradient(
                90deg,
                red, orange, yellow, green, cyan, blue, violet
            );
            color: #000;
            font-weight: bold;
            box-shadow: 0 0 30px white;
            animation: rainbow 1s infinite linear, shake 0.2s infinite;
        }

        @keyframes glow {
            from { box-shadow: 0 0 5px gold; }
            to   { box-shadow: 0 0 25px gold; }
        }

        @keyframes rainbow {
            from { filter: hue-rotate(0deg); }
            to   { filter: hue-rotate(360deg); }
        }

        @keyframes shake {
            0% { transform: translate(0, 0); }
            25% { transform: translate(2px, 0); }
            50% { transform: translate(-2px, 0); }
            75% { transform: translate(0, 2px); }
            100% { transform: translate(0, 0); }
        }
    </style>

    <h1>🎰 10連ガチャ結果 🎰</h1>
    <?php foreach ($results as $i => $item): ?>
        <div class="result <?= $item['rarity'] ?>">
            <?= $i + 1 ?>回目：<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endforeach; ?><br>
    <a href="gacha.php">もう一度ガチャを引く</a><br>
    <a href="menu.php">メニュー画面</a>
</body>
</html>
