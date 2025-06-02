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
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = (int)get_post('item_id');
    $quantity = (int)get_post('quantity');

    if ($item_id > 0 && $quantity > 0) {
        $message = add_to_cart($db, $user['user_id'], $item_id, $quantity);
    } else {
        $message = '商品または数量が不正です。';
    }
}

// 公開商品取得
$items = get_open_items($db);

// Viewファイル読み込み
include_once VIEW_PATH . 'user_item_list_view.php';
