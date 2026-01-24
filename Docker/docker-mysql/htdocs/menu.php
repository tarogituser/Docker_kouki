<?php
  require_once 'common.php';

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

/* ユーザー選択 */
if (isset($_POST['select_user'])){
  $user_id = $_POST['user_id'];
  $user_name = $_POST['user_name'];
  $_SESSION['user_id'] = $user_id;
  $_SESSION['user_name'] = $user_name;

  $message = "ユーザー「{$user_name}」を選択しました";
}

/* ユーザー削除 */
if (isset($_POST['delete_user'])){
  $user_id = $_POST['user_id'];
  $user_name = $_POST['user_name'];
  $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = $user_id");
  $stmt->execute();

  $message = "ユーザー「{$user_name}」を削除しました";
}

/* 終了 */
if (isset($_POST['exit'])) {
  unset($_SESSION['user_id']);
  unset($_SESSION['user_name']);
}

//ユーザー取得
$stmt = $pdo->query("SELECT user_id, user_name FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <h1 style="padding: 10px;">メニュー</h1>
    <p style="color: gray;">以下から選択してください</p>
    <ul>
      <li><a href="forge_entrance.php">アイテム強化</a></li>
      <li><a href="gacha.php">10連ガチャ</a></li>
      <li><a href="disp_items.php">所持アイテム確認</a></li>
      <li><a href="battle_entrance.php">対戦</a></li>
    </ul>
  </body>

<?php else: ?>
  <table border="1" style="border-collapse: collapse width: 100%; max-width: 500px;">
      <thead>
          <th>ユーザーID</th>
          <th>ユーザー名</th>
          <th>操作</th>
      </thead>

      <tbody>
        <?php foreach($users as $user): ?>
            <tr>
                <td style="text-align: right;"><?php echo htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="text-align: center;">
                    <form action="menu.php" method="post" style="margin: 0;">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button style="color: gray" type="submit" name="select_user">このユーザーを選択</button>
                    </form>
                    <form action="menu.php" method="post" style="margin: 0;">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button style="color: gray" type="submit" name="delete_user">このユーザーを削除</button>
                    </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
  </table>

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
