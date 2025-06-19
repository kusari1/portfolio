<?php
session_start();

// ログアウト処理
if (isset($_POST['logout'])) {
    // セッション変数を削除
    $_SESSION = [];
    // セッションID（ユーザ側のCookieに保存されている）を削除
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    // セッションを破棄
    session_destroy();

    // ログアウト後、work38.phpにリダイレクト（ログインページに戻る）
    header("Location: work38.php");
    exit();
}

// ログインフォームが送信された場合
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id']) && isset($_POST['password']) && isset($_POST['user_name'])) {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $user_name = $_POST['user_name'];

    // データベース接続情報の設定
    $servername = "localhost"; // サーバー名
    $username = "xb513874_vfrg6"; // ユーザー名
    $password_db = "7mumpav176"; // パスワード
    $dbname = "xb513874_t8tcu"; // データベース名

    // データベース接続
    $db = new mysqli($servername, $username, $password_db, $dbname);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // SQL文でユーザー情報を照合
    $query = "SELECT * FROM user_table WHERE user_id = ? AND password = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('is', $user_id, $password); // user_idは整数、passwordは文字列
    $stmt->execute();
    $result = $stmt->get_result();

    // ユーザー情報が見つかった場合
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];  // セッションにユーザーIDを保存
        $_SESSION['user_name'] = $row['user_name'];  // セッションにユーザー名を保存

        // ログイン成功後、home.phpにリダイレクト
        header("Location: home.php");
        exit();
    } else {
        // ユーザー情報が見つからなければエラーメッセージを表示
        echo "ユーザーIDかパスワードが間違っています。";
    }

    $db->close();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>work38</title>
</head>
<body>
    <h2>ログイン</h2>
    <!-- ログインフォーム -->
    <form action="work38.php" method="POST">
        <label for="user_id">ユーザーID:</label>
        <input type="text" id="user_id" name="user_id" required><br>

        <label for="user_name">ユーザー名:</label>
        <input type="text" id="user_name" name="user_name" required><br>

        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="ログイン">
    </form>
</body>
</html>
