<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - ECサイト</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>login.css">
</head>
<body>
    <div class="login-container">
        <h1 class="EC_logo">&nbsp;EC&nbsp;SITE</h1>
        <h2>ログイン</h2>

        <?php if (!empty($error_message)) : ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">ユーザー名：</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード：</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">ログイン</button>
        </form>

        <p><a href="register.php">新規登録ページへ</a></p>
        <p><a href="https://portfolio02.dc-itex.com/nakano/0005/js/index.html" target="_blank">JavaScript:ピアノアプリ</p></a>
        <p><a href="https://portfolio02.dc-itex.com/nakano/0005/wp/" target="_blank">WordPress:架空Webサイト</p></a>
        <p><a href="https://portfolio02.dc-itex.com/nakano/0005/index.html#" target="_blank">自己紹介サイト</a></p>
    </div>
</body>
</html>
