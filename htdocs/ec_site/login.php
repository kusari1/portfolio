<?php
session_start();
require_once('../../include/config/const.php');
require_once('../../include/model/db.php');

// ログアウト処理: GETパラメータに logout=1 があればログアウト
if (isset($_GET['logout']) && $_GET['logout'] === '1') {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

// 既にログイン済みならリダイレクト
if (isset($_SESSION['user_id'])) {
    header('Location: user_item_list.php');
    exit();
}

// エラーメッセージの取得（あれば）
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['error_message']);

// フォーム送信時
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // DB接続
    $db = new DB();
    $conn = $db->connect();

    $sql = "SELECT * FROM user_table WHERE user_name = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // パスワードハッシュ検証に変更
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];

            // ログイン成功後にリダイレクト
            if ($user['user_name'] === 'ec_admin') {
                header('Location: product_management.php');
            } else {
                header('Location: user_item_list.php');
            }
            exit();
        } else {
            // 認証失敗はセッションにメッセージをセットしてリダイレクト（PRG）
            $_SESSION['error_message'] = "ユーザー名またはパスワードが間違っています。";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "ユーザー名またはパスワードが間違っています。";
        header('Location: login.php');
        exit();
    }
}

// GETリクエスト時はここでビューを表示
include_once VIEW_PATH . 'login_view.php';
