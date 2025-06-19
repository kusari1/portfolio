<?php

// config/const.php を読み込む
require_once __DIR__ . '/../config/const.php'; // const.php のパスを指定

// データベース接続を取得する関数（DB接続設定をファイルから読み込む）
function get_db_connection() {
    try {
        $dsn = "mysql:host=localhost;dbname=" . DB_NAME;  // 正しいDSN
        $username = DB_USER;
        $password = DB_PASS;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        exit('データベース接続失敗: ' . $e->getMessage());
    }
}

// SQLを実行して結果を全て取得する関数
function fetch_all_query($db, $sql, $params = array()) {
    $stmt = $db->prepare($sql);
    $stmt->execute($params); // ← 第3引数で渡した値をバインド
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 1件の結果を取得する関数（SELECT文用）
function fetch_query($db, $sql, $params = array()) {
    $stmt = $db->prepare($sql);
    $stmt->execute($params); // パラメータをバインド
    return $stmt->fetch(PDO::FETCH_ASSOC); // 1件のデータを返す
}

// セッションの開始
function start_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// ログイン状態をチェック
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// ログインユーザーIDを取得
function get_logged_in_user_id() {
    return $_SESSION['user_id'] ?? null;
}

// POSTデータを取得する関数
function get_post($key) {
    return isset($_POST[$key]) ? $_POST[$key] : '';
}

// XSS対策で使うHTMLエスケープ関数
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// 公開状態の商品一覧を取得する関数（image, stock, product を JOIN）
function get_open_items($db) {
    $sql = '
        SELECT 
            p.product_id,
            p.product_name,
            p.price,
            p.public_flg,
            s.stock_qty,
            i.image_name
        FROM product_table AS p
        JOIN stock_table AS s ON p.product_id = s.product_id
        JOIN images_table AS i ON p.product_id = i.product_id
        WHERE p.public_flg = 1
    ';

    return fetch_all_query($db, $sql);
}

// 正の整数かどうかを判定する関数
function is_positive_integer($value) {
    return (preg_match('/^[1-9][0-9]*$/', $value) === 1);
}

// SQLを実行（INSERT / UPDATE / DELETE）用の関数
function execute_query($db, $sql, $params = array()) {
    try {
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        return false;
    }
}


?>
