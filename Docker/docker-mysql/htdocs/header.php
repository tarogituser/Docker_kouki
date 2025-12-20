<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' | ゲーム管理画面' : 'ゲーム管理画面'; ?></title>
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
        .container {
            width: 90%;
            max-width: 960px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header-info">
        <?php if (isset($_SESSION['user_id'])): ?>
            ログイン中: <strong><?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?></strong> (ID: <?php echo htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8'); ?>)
            <a href="/menu.php" style="margin-left: 15px;">メニューへ戻る</a>
            <form action="/select_user.php" method="post" style="display: inline; margin-left: 15px;">
                <button type="submit" name="logout">ログアウト</button>
            </form>
        <?php else: ?>
            ログインしていません</a>
        <?php endif; ?>
    </div>
    <div class="container"></div>
