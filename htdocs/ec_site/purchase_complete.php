<?php

require_once __DIR__ . '/../../include/config/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// ログインチェック
if (!is_logined()) {
    header('Location: login.php');
    exit;
}

// 購入済み商品情報取得とセッション破棄
$purchased_items = $_SESSION['purchased_items'] ?? [];
unset($_SESSION['purchased_items']);

$db = get_db_connection();
$user = get_login_user($db);

$cart_items = $purchased_items;
$total_price = calculate_total_price($cart_items);

// viewへ渡す変数
include_once VIEW_PATH . 'purchase_complete_view.php';
