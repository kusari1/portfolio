<?php
session_start();
require_once('../../include/config/const.php');
require_once('../../include/model/db.php');

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // バリデーション
    if (!preg_match('/^[a-zA-Z0-9_]{5,}$/', $username)) {
        $error_message = "ユーザー名は5文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{8,}$/', $password)) {
        $error_message = "パスワードは8文字以上の半角英数字とアンダースコア(_)のみ使用できます。";
    } else {
        // DB接続
        $db = new DB();
        $conn = $db->connect();

        // ユーザー名重複チェック
        $sql = "SELECT COUNT(*) FROM user_table WHERE user_name = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $error_message = "このユーザー名は既に使用されています。";
        } else {
            // ユーザー登録処理
            $sql = "INSERT INTO user_table (user_name, password, create_date, update_date) VALUES (:username, :password, NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);  // ハッシュ化する場合は password_hash() を使用

            if ($stmt->execute()) {
                $success_message = "登録が完了しました。ログインページからログインしてください。";
            } else {
                $error_message = "登録に失敗しました。";
            }
        }
    }
}

// view を読み込む
include_once VIEW_PATH . 'register_view.php';
