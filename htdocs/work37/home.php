<?php
// データベース接続設定
$host = 'localhost';
$login_user = 'xb513874_vfrg6';
$password = '7mumpav176';
$database = 'xb513874_t8tcu';

$db = new mysqli($host, $login_user, $password, $database);
if ($db->connect_error) {
    die("接続エラー: " . $db->connect_error);
}

// POSTされたフォームの値を取得
$user_id = $_POST['user_id'] ?? '';
$password = $_POST['password'] ?? '';
$user_name = $_POST['user_name'] ?? '';
$cookie_confirmation = $_POST['cookie_confirmation'] ?? '';

// SQL文を実行してユーザー情報を取得
$query = "SELECT * FROM user_table WHERE user_id = ? AND password = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('is', $user_id, $password);
$stmt->execute();
$result = $stmt->get_result();

// ログイン認証
if ($result->num_rows > 0) {
    // ログイン成功
    echo "ログイン(疑似的)に成功しました！";
    echo $user_name.'さんようこそ！';
    $user = $result->fetch_assoc();

    // クッキーの保存期間を1年（365日）に設定
    define('EXPIRATION_PERIOD', 365 * 24 * 60 * 60);
    $cookie_expiration = time() + EXPIRATION_PERIOD;

    // クッキー保存処理
    if ($cookie_confirmation === 'checked') {
        setcookie('cookie_confirmation', 'checked', $cookie_expiration);
        setcookie('user_id', $user_id, $cookie_expiration);
        setcookie('user_name', $user['user_name'], $cookie_expiration);
    } else {
        // クッキー削除
        setcookie('cookie_confirmation', '', time() - 3600);
        setcookie('user_id', '', time() - 3600);
        setcookie('user_name', '', time() - 3600);
    }

    // home.phpにリダイレクト
    // header("Location: home.php");
    exit();
} else {
    // ログイン失敗
    echo "ログインに失敗しました。";
}

$db->close();
?>
