<?php
require_once 'common.php';

$player1_id = (int)$_POST['player1_id'];
$player2_id = (int)$_POST['player2_id'];

$player1 = null;
$player2 = null;
$player1_item = null;
$player2_item = null;
$winner = null;

try
{
    // プレイヤー情報を取得する
    function get_user_info($pdo, $user_id) {
        $stmt = $pdo->prepare("SELECT user_id, user_name FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // プレイヤーの所持アイテムからランダムに1つ取得する
    function get_random_item($pdo, $user_id) {
        $stmt = $pdo->prepare(
            "SELECT item_id, item_name, attack, defense
             FROM items_user_$user_id
             ORDER BY RAND()
             LIMIT 1"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        // プレイヤー情報を取得
    $player1 = get_user_info($pdo, $player1_id);
    $player2 = get_user_info($pdo, $player2_id);

    if (!$player1 || !$player2) {
        throw new Exception("プレイヤー情報が見つかりません。");
    }

    // 各プレイヤーのアイテムをランダムに抽選
    $player1_item = get_random_item($pdo, $player1_id);
    $player2_item = get_random_item($pdo, $player2_id);

    $error_message = "";

    // アイテムがない場合の処理
    if (!$player1_item || !$player2_item) {
        $error_message = "両プレイヤーがアイテムを1つ以上所持している必要があります。";
    } else {
        // プレイヤー1のAttackとプレイヤー2のDefenseで勝敗を決定
        if ($player1_item['attack'] > $player2_item['defense']) {
            $winner = $player1;
        } elseif ($player1_item['attack'] < $player2_item['defense']) {
            $winner = $player2;
        } else {
            $winner = null;
        }

        // --- バトル履歴保存処理 ---
        if ($winner === null) {
            $player1_result = 'draw';
            $player2_result = 'draw';
        } elseif ($winner['user_id'] === $player1['user_id']) {
            $player1_result = 'win';
            $player2_result = 'lose';
        } else {
            $player1_result = 'lose';
            $player2_result = 'win';
        }

        $pdo->beginTransaction();
    }
}
catch(PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
        $error_message = "データベースエラー: " . $e->getMessage();
} catch (Exception $e) {
    $error_message = "エラー: " . $e->getMessage();
}

$title = "対戦画面";
require_once 'header.php';
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <meta charset="UTF-8">
    <h1 style="padding: 10px;">バトル結果</h1>

    <?php if ($error_message): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php elseif ($player1 && $player2 && $player1_item && $player2_item): ?>
        <div style="display: flex; justify-content: space-around; text-align: center;">
            <div>
                <h2><?php echo htmlspecialchars($player1['user_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p>アイテム: <?php echo htmlspecialchars($player1_item['item_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>Attack: <?php echo htmlspecialchars($player1_item['attack'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div>
                <h2>VS</h2>
            </div>
            <div>
                <h2><?php echo htmlspecialchars($player2['user_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p>アイテム: <?php echo htmlspecialchars($player2_item['item_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>Defense: <?php echo htmlspecialchars($player2_item['defense'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>

        <hr>

        <div style="text-align: center; margin-top: 20px;">
            <h2>勝者</h2>
            <?php if ($winner): ?>
                <p style="font-size: 2em; color: red; font-weight: bold;"><?php echo htmlspecialchars($winner['user_name'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php else: ?>
                <p style="font-size: 2em; font-weight: bold;">引き分け</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <p style="text-align: center; margin-top: 30px;">
    <?php if ($player1 && $player2): // プレイヤー情報がある場合のみ表示 ?>
        <form action="battle.php" method="post" style="display: inline-block; margin-bottom: 10px;">
            <input type="hidden" name="player1_id" value="<?php echo htmlspecialchars($player1_id, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="player2_id" value="<?php echo htmlspecialchars($player2_id, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" style="width: 200px; padding: 5px">もう一度同じ相手と戦う</button>
        </form><br>
    <?php endif; ?>
    </p>

    <form action="menu.php" method="post">
        <button type="submit" style="width: 200px; padding: 5px" name="gacha_start">メニュー画面</button>
    </form>
  </body>
</html>
