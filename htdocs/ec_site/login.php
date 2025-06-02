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

// 初期化
$error_message = '';

// 既にログイン済みならリダイレクト
if (isset($_SESSION['user_id'])) {
    header('Location: user_item_list.php');
    exit();
}

// フォーム送信時
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // DB接続
    $db = new DB();
    $conn = $db->connect();

    $sql = "SELECT * FROM user_table WHERE user_name = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['password'] === $password) {
            $_SESSION['user_id'] = $user['user_id'];

            if ($user['user_name'] === 'ec_admin') {
                header('Location: product_management.php');
            } else {
                header('Location: user_item_list.php');
            }
            exit();
        } else {
            $error_message = "ユーザー名またはパスワードが間違っています。";
        }
    } else {
        $error_message = "ユーザー名またはパスワードが間違っています。";
    }
}

// ビュー読み込み
include_once VIEW_PATH . 'login_view.php';
