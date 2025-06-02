<?php
require_once __DIR__ . '/../../include/config/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// 未ログインならログインページへ
if (!is_logined()) {
    header('Location: login.php');
    exit;
}

$db = (new DB())->connect();
$user = get_login_user($db);

// POSTで商品追加
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = (int)get_post('item_id');
    $quantity = (int)get_post('quantity');

    if ($item_id > 0 && $quantity > 0) {
        // カート追加メッセージをセッションに保存
        $_SESSION['message'] = add_to_cart($db, $user['user_id'], $item_id, $quantity);
    } else {
        $_SESSION['message'] = '商品または数量が不正です。';
    }

    // PRG: 処理後にリダイレクト（GET）
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// GET時はメッセージをセッションから取得して消す
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

// 公開商品取得
$items = get_open_items($db);

// Viewファイル読み込み
include_once VIEW_PATH . 'user_item_list_view.php';
