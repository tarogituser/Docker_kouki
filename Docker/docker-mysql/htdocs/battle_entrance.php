<?php
require_once 'common.php';

$player1_id = $_SESSION['user_id'];
$player1_name = $_SESSION['user_name'];
$opponents = [];

try
{
    //自分以外のユーザー
    $stmt = $pdo->prepare("SELECT user_id, user_name FROM users WHERE user_id != :current_user_id ORDER BY user_id");
    $stmt->bindParam(':current_user_id', $player1_id, PDO::PARAM_INT);
    $stmt->execute();
    $opponents = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    $error_message = "データベースエラー: " . $e->getMessage();
}

$title = "対戦相手選択";
require_once 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body>
    <h1 style="color: gold; padding: 20px;">対戦相手選択</h1>

    <p>あなたは <strong><?php echo htmlspecialchars($player1_name, ENT_QUOTES, 'UTF-8'); ?></strong> (ID: <?php echo htmlspecialchars($player1_id, ENT_QUOTES, 'UTF-8'); ?>) です。</p>

    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    
    <?php if (count($opponents) == 0): ?>
        <p style="color: blue">対戦相手がいませんでした。</p>
    <?php else: ?>
        <p style="color: blue">対戦相手を選択してください。</p><br>
        <table border="1" style="border-collapse: collapse width: 100%; max-width: 500px;">
            <thead>
                <th>ユーザーID</th>
                <th>ユーザー名</th>
                <th>操作</th>
            </thead>

            <tbody>
                <?php foreach($opponents as $opponent): ?>
                    <tr>
                        <td style="text-align: right;"><?php echo htmlspecialchars($opponent['user_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($opponent['user_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="text-align: center;">
                            <form action="battle.php" method="post" style="margin: 0;">
                                <input type="hidden" name="player1_id" value="<?php echo htmlspecialchars($player1_id, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="player2_id" value="<?php echo htmlspecialchars($opponent['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button style="color: gray" type="submit">このユーザーと対戦する</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?><br>

    <form action="menu.php" method="post">
        <button type="submit" style="width: 200px; padding: 5px" name="back">メニュー画面</button>
    </form>
</body>
</html>