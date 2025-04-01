<?php
session_start();
require_once('include/config/const.php');  // const.php のインクルード
require_once('include/model/db.php');  // DBクラスをインクルード
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
    $sql = "SELECT * FROM user_table WHERE user_name = :username AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password); // パスワードは通常ハッシュ化して保存するべきですが、仮の処理です
    $stmt->execute();

    // ユーザーが見つかった場合
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 管理者かどうかチェック
        if ($user['user_name'] === 'ec_admin') {
            // 管理者の場合、商品管理ページに遷移
            header('Location: product_management.php');
            exit();
        } else {
            // 一般ユーザーの場合、商品一覧ページに遷移
            $_SESSION['user_id'] = $user['user_id']; // ユーザーIDをセッションに保存
            header('Location: product_list.php');
            exit();
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
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
            width: 100%;
            box-sizing: border-box;
        }
        .form-group label {
            display: block;
            font-weight: bold;
        }
        .form-group input {
            width: calc(100% - 16px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        p {
            margin-top: 10px;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>ログイン</h2>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">ログイン</button>
        </form>
        <p>アカウントをお持ちでない方は <a href="register.php">こちら</a> から登録</p>
    </div>
</body>
</html>
