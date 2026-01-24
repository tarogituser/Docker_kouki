<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? 
    htmlspecialchars($title) : 
    'ゲーム管理画面'; ?>
    </title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            background-color: #a0edadff;
            margin: 0;
            padding: 0;
        }
        .header-info {
            background-color: #4a8acaff;
            padding: 10px 20px;
            text-align: right;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>

<body>
    <div class="header-info">
    <?php if (isset($_SESSION['user_name'])): ?>
      ユーザー名：<?= htmlspecialchars($_SESSION['user_name'])?>
      <form action="menu.php" method="post" style="display:inline;">
        <button type="submit" name="exit">終了</button>
      </form>
    <?php else: ?>
      ユーザーが設定されていません
    <?php endif; ?>
  </div>
