<?php

require_once 'include/config/const.php';
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

// カート更新処理
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

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ショッピングカート</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        header, .cart, .actions {
            margin-bottom: 20px;
        }
        .item {
            border: 1px solid #ddd;
            background: #fff;
            padding: 10px;
            margin-bottom: 10px;
        }
        .item img {
            height: 100px;
        }
        .error {
            color: red;
        }
        .message {
            color: green;
        }
    </style>
</head>
<body>

<header>
    <h1>ショッピングカート</h1>
    <nav>
        <a href="user_item_list.php">商品一覧へ戻る</a> |
        <a href="login.php">ログアウト</a>
    </nav>
</header>

<?php foreach ($errors as $error): ?>
    <p class="error"><?php print h($error); ?></p>
<?php endforeach; ?>

<?php if ($message !== ''): ?>
    <p class="message"><?php print h($message); ?></p>
<?php endif; ?>

<section class="cart">
    <?php if (count($cart_items) > 0): ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="item">
                <img src="images/<?php print h($item['image_name']); ?>" alt="">
                <h2><?php print h($item['product_name']); ?></h2>
                <p>価格: <?php print h($item['price']); ?>円</p>
                <p>小計: <?php print h($item['price'] * $item['product_qty']); ?>円</p>
                <form method="post">
                    <input type="hidden" name="product_id" value="<?php print h($item['product_id']); ?>">
                    <input type="number" name="new_qty" value="<?php print h($item['product_qty']); ?>" min="1">
                    <button type="submit" name="update_qty">数量変更</button>
                    <button type="submit" name="delete_item">削除</button>
                </form>
            </div>
        <?php endforeach; ?>
        <div class="actions">
            <p>合計金額: <?php print h($total_price); ?>円</p>
            <form method="post">
                <button type="submit" name="purchase">購入する</button>
            </form>
        </div>
    <?php else: ?>
        <p>カートに商品がありません。</p>
    <?php endif; ?>
</section>

</body>
</html>
