<?php
session_start();
require_once('include/config/const.php');  // 設定ファイル
require_once('include/model/db.php');  // DB接続ファイル

$error_message = "";

// フォームが送信されたときの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // バリデーションチェック
    if (!preg_match('/^[a-zA-Z0-9_]{5,}$/', $username)) {
        $error_message = "ユーザー名は5文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{8,}$/', $password)) {
        $error_message = "パスワードは8文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
    } else {
        // データベース接続
        $db = new DB();
        $conn = $db->connect();

        // ユーザー名の重複チェック
        $sql = "SELECT COUNT(*) FROM user_table WHERE user_name = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $error_message = "このユーザー名は既に使用されています。";
        } else {
            // 新しいユーザーを登録
            $sql = "INSERT INTO user_table (user_name, password, create_date, update_date) VALUES (:username, :password, NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            if ($stmt->execute()) {
                $success_message = "登録が完了しました。ログインページからログインしてください。";
            } else {
                $error_message = "登録に失敗しました。";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録 - ECサイト</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: #fff;
            padding:0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 1024px;
            text-align: center;
            position: relative;
            padding-top: 120px;
            padding-bottom: 120px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group label {
             /* display: block; */
             font-weight: bold;
        }
        .form-group input {
            width: 300px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 250px;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 24px;
            margin-top: 5px;
        }
        button:hover {
            background-color: #218838;
        }
        p {
            margin: 16px 0;
        }
        .message {
            color: red;
            font-size: 14px;
            /* margin-bottom: 10px; */
        }
        .success {
            color: green;
            font-size: 14px;
        }
        .EC_logo{
            background-color: #67cf7e;
            width: 100%;
            margin: 0;
            text-align: left;
            position: absolute;
            top: 0;
            height: 60px;
            line-height: 60px;
            color: #eee;
        }

        h2,h3{
            margin: 5px 0;
        }

    </style>
</head>
<body>
    <div class="register-container">
    <h1 class="EC_logo">&nbsp;EC&nbsp;SITE</h1>
        <h2>ユーザー登録</h2>
        <?php if ($error_message): ?>
            <div class="message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <script>
                // ページが読み込まれたときに処理を開始
                document.addEventListener('DOMContentLoaded', function() {
                // フォーム要素を取得
                const form = document.querySelector('form');
                // 入力欄（ユーザー名とパスワード）を取得
                const usernameInput = document.getElementById('username');
                const passwordInput = document.getElementById('password');

                // フォームが送信されるタイミングで処理を実行
                form.addEventListener('submit', function(event) {
                // 入力値を取得し、前後の空白を除去
                let username = usernameInput.value.trim();
                let password = passwordInput.value.trim();
                let error = ''; // エラーメッセージ用の変数

                // ユーザー名の形式チェック（5文字以上、半角英数字とアンダースコアのみ）
                if (!/^[a-zA-Z0-9_]{5,}$/.test(username)) {
                    error = "ユーザー名は5文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
                } 
                // パスワードの形式チェック（8文字以上、半角英数字とアンダースコアのみ）
                else if (!/^[a-zA-Z0-9_]{8,}$/.test(password)) {
                        error = "パスワードは8文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
                }

                // エラーがある場合は送信を中止し、エラーメッセージを表示
                if (error !== '') {
                    event.preventDefault(); // フォーム送信をキャンセル
                    alert(error); // アラートでユーザーにエラーを通知
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
