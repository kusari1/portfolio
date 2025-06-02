<?php
session_start();
require_once('../../include/config/const.php');
require_once('../../include/model/db.php');

$error_message = '';
$success_message = '';

// POSTリクエスト時
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // バリデーション
    if (!preg_match('/^[a-zA-Z0-9_]{5,}$/', $username)) {
        $_SESSION['error_message'] = "ユーザー名は5文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{8,}$/', $password)) {
        $_SESSION['error_message'] = "パスワードは8文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
    } else {
        $db = new DB();
        $conn = $db->connect();

        $sql = "SELECT COUNT(*) FROM user_table WHERE user_name = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['error_message'] = "このユーザー名は既に使用されています。";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user_table (user_name, password, create_date, update_date) VALUES (:username, :password, NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hash);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "登録が完了しました。ログインページからログインしてください。";
            } else {
                $_SESSION['error_message'] = "登録に失敗しました。";
            }
        }
    }

    // POST/Redirect/GET パターンでリダイレクト
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// GETアクセス時はセッションからメッセージを取得して破棄
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// view を読み込む
include_once VIEW_PATH . 'register_view.php';
