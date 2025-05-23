<?php
session_start();
require_once('../../include/config/const.php');
require_once('../../include/model/db.php');
  // DB接続ファイルのインクルード

// フォームが送信されたときの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ユーザー名とパスワードの取得
    $username = $_POST['username'];
    $password = $_POST['password'];

    // データベース接続
    $db = new DB();
    $conn = $db->connect();

    // ユーザー情報をデータベースから取得
    $sql = "SELECT * FROM user_table WHERE user_name = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // ユーザーが見つかった場合
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // パスワードの一致を確認（ここではプレーンテキストで比較しています）
        if ($user['password'] === $password) {
            // 管理者かどうかチェック
            if ($user['user_name'] === 'ec_admin') {
                // 管理者の場合、商品管理ページに遷移
                $_SESSION['user_id'] = $user['user_id'];
                header('Location: product_management.php');
                exit();
            } else {
                // 一般ユーザーの場合、商品一覧ページに遷移
                $_SESSION['user_id'] = $user['user_id']; // ユーザーIDをセッションに保存
                header('Location: user_item_list.php');
                exit();
            }
        } else {
            // パスワードが間違っている場合
            $error_message = "ユーザー名またはパスワードが間違っています。";
        }
    } else {
        // ユーザーが見つからない場合、エラーメッセージを表示
        $error_message = "ユーザー名またはパスワードが間違っています。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - ECサイト</title>
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
        .login-container {
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

        h2{
            margin: 20px 0;
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
        .error {
            color: red;
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
        .error-message{
            width: 50%;
            background-color: pink;
            margin: 20px auto;
            padding: 20px 0;
            font-weight: bold;
            color: #D34C2C;
        }

        h2{
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="EC_logo">&nbsp;EC&nbsp;SITE</h1>
        <h2>ログイン</h2>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
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
        <p><a href="register.php">新規登録ページへ</a> </p>
    </div>
</body>
</html>
