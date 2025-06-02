<?php

require_once __DIR__ . '/../../include/config/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if (!is_logined()) {
    header('Location: login.php');
    exit;
}

$db = get_db_connection();
$user = get_login_user($db);
$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update_qty'])) {
        $product_id = get_post('product_id');
        $new_qty = get_post('new_qty');

        if (!is_positive_integer($new_qty)) {
            $errors[] = '数量は正の整数で入力してください。';
        } elseif (check_stock($db, $product_id, $new_qty)) {
            update_cart_quantity($db, $user['user_id'], $product_id, $new_qty);
            $message = '数量を更新しました。';
        } else {
            $errors[] = '在庫が足りません。';
        }

    } elseif (isset($_POST['delete_item'])) {
        $product_id = get_post('product_id');
        delete_cart_item($db, $user['user_id'], $product_id);
        $message = '商品を削除しました。';

    } elseif (isset($_POST['purchase'])) {
        $cart_items = get_user_cart($db, $user['user_id']);
        $db->beginTransaction();

        try {
            foreach ($cart_items as $item) {
                if ((int)$item['stock_qty'] < (int)$item['product_qty']) {
                    throw new Exception(h($item['product_name']) . 'の在庫が足りません。');
                }
            }

            foreach ($cart_items as $item) {
                update_item_stock($db, $item['product_id'], $item['product_qty']);
            }

            $_SESSION['purchased_items'] = $cart_items;
            clear_user_cart($db, $user['user_id']);
            $db->commit();
            header('Location: purchase_complete.php');
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = $e->getMessage();
        }
    }
}

$cart_items = get_user_cart($db, $user['user_id']);
$total_price = calculate_total_price($cart_items);

// ビューの読み込み
include_once VIEW_PATH . 'cart_view.php';
