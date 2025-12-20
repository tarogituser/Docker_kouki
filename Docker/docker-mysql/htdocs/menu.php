<?php
  require 'common.php';

  $message = '';

  // ユーザー作成フォームが送信された場合の処理
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user']) && !empty($_POST['user_name'])) {
      $userName = $_POST['user_name'];
      try 
      {
        $stmt = $pdo->prepare("INSERT INTO users (user_name) VALUES (:user_name)");
        $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
        $stmt->execute();

        $message = "新しいユーザー「" . htmlspecialchars($userName) . "」を作成しました。";

      } catch (PDOException $e) 
      {
        $message = "エラー: " . $e->getMessage();
      }
  }
  
  $title = "メニュー画面";
  require_once 'header.php';
?>

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

<?php if (true): ?>
  <head>
    <meta charset="UTF-8">
  </head>
  <body>
    <h1>メニュー</h1>
    <p style="color: gray;">以下から選択してください</p>
    <ul>
      <li><a href="forge_entrance.php">アイテム強化</a></li>
      <li><a href="gacha.html">10連ガチャ</a></li>
    </ul>

  </body>
<?php endif; ?>
