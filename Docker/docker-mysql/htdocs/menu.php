<?php
  require 'common.php';

  $title = "メニュー画面";
  $message = "";

  /* ユーザー作成 */
if (isset($_POST['create_user'])) {
    $user_name = trim($_POST['user_name']);

    if ($user_name !== "") {
      // すでに同じ user_name が存在するかチェック
      $check = $pdo->prepare("SELECT user_id FROM users WHERE user_name = :name");
      $check->execute([':name' => $user_name]);
      $existingUser = $check->fetch(PDO::FETCH_ASSOC);

      // 既存ユーザー
      if ($existingUser){
        $_SESSION['user_name'] = $user_name;
        $_SESSION['user_id'] = $existingUser['user_id'];
      }
      // 新規ユーザー
      else{
        // users テーブルにユーザー作成
        $stmt = $pdo->prepare("INSERT INTO users (user_name) VALUES (:name)");
        $stmt->execute([':name' => $user_name]);

        // 作成された user_id を取得
        $userId = $pdo->lastInsertId();

        $_SESSION['user_name'] = $user_name;
        $_SESSION['user_id'] = $userId;

        // ユーザー専用 items テーブル名
        $tableName = "items_user_" . intval($userId);

        // 空の items テーブルを作成
        $sql = "
        CREATE TABLE $tableName (
            item_id INT PRIMARY KEY,
            item_name VARCHAR(255) CHARACTER SET utf8mb4,
            attack INT DEFAULT 1,
            defense INT DEFAULT 1,
            item_count INT DEFAULT 0
        );
        ";
        $pdo->exec($sql);

        $message = "新規ユーザー「{$user_name}」を作成しました";
      }
    }
}

/* ユーザー削除 */
if (isset($_POST['delete_user'])) {
    unset($_SESSION['user_name']);
    $message = "ユーザーを削除しました";
}

require_once 'header.php';
?>

<?php if (isset($_SESSION['user_name'])): ?>
  <head>
    <meta charset="UTF-8">
  </head>

  <?php if ($message): ?>
        <p style="background-color: #f0f0f0; padding: 10px; border-left: 5px solid #007bff;"><?php echo $message; ?></p>
  <?php endif; ?>

  <body>
    <h1>メニュー</h1>
    <p style="color: gray;">以下から選択してください</p>
    <ul>
      <li><a href="forge_entrance.php">アイテム強化</a></li>
      <li><a href="gacha.php">10連ガチャ</a></li>
      <li><a href="disp_items.php">所持アイテム確認</a></li>
    </ul>
  </body>

<?php else: ?>

  <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
    <h2>ユーザー作成</h2>

    <?php if ($message): ?>
        <p style="background-color: #f0f0f0; padding: 10px; border-left: 5px solid #007bff;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="menu.php" method="post">
        <input type="text" name="user_name" placeholder="新しいユーザー名" required>
        <button type="submit" name="create_user">作成</button>
    </form>
  </div>

<?php endif; ?>
