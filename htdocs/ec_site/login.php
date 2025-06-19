<?php
session_start();
require_once('../../include/config/const.php');
require_once('../../include/model/db.php');
require_once('../../include/model/user.php');

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

    $user = get_user_by_name($conn, $username);

    if ($user === false) {
        error_log("ユーザーが見つかりません: $username");
    }

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];

        // 管理者かどうかでリダイレクト先を分ける
        if ($user['user_name'] === 'ec_admin') {
            header('Location: product_management.php');
        } else {
            header('Location: user_item_list.php');
        }
        exit();
    } else {
        $_SESSION['error_message'] = "ユーザー名またはパスワードが間違っています。";
        header('Location: login.php');
        exit();
    }
}

// POSTでないときは通常のログインページ表示
include_once VIEW_PATH . 'login_view.php';

