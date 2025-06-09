<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録 - ECサイト</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>register.css">
</head>
<body>
    <div class="register-container">
        <h1 class="EC_logo">&nbsp;EC&nbsp;SITE</h1>
        <h2>ユーザー登録</h2>

        <?php if (!empty($error_message)): ?>
            <div class="message" style="color: red;"><?php echo htmlspecialchars($error_message, ENT_QUOTES); ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="message success" style="color: green;"><?php echo htmlspecialchars($success_message, ENT_QUOTES); ?></div>
        <?php endif; ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const usernameInput = document.getElementById('username');
                const passwordInput = document.getElementById('password');

                form.addEventListener('submit', function(event) {
                    let username = usernameInput.value.trim();
                    let password = passwordInput.value.trim();
                    let error = '';

                    if (!/^[a-zA-Z0-9_]{5,}$/.test(username)) {
                        error = "ユーザー名は5文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
                    } else if (!/^[a-zA-Z0-9_]{8,}$/.test(password)) {
                        error = "パスワードは8文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
                    }

                    if (error !== '') {
                        event.preventDefault();
                        alert(error);
                    }
                });
            });
        </script>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">ユーザー名：</label>
                <input type="text" id="username" name="username" required>
                <h3>[ユーザー名]は5文字以上の半角英数字とアンダースコア(_)のみ使用できます。</h3>
            </div>
            <div class="form-group">
                <label for="password">パスワード：</label>
                <input type="password" id="password" name="password" required>
                <h3>[パスワード]は8文字以上の半角英数字とアンダースコア(_)のみ使用できます。</h3>
            </div>
            <button type="submit">登録</button>
        </form>

        <p><a href="login.php">既にアカウントをお持ちの方はこちらからログイン</a></p>
    </div>
</body>
</html>
