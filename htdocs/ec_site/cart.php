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
            
            // ★ここで購入情報をセッションに保持！
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

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ショッピングカート</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            height: 100vh;
            margin: 0;
            box-sizing: border-box;
        }

        header{
            background: #a7af7e;
        }

        p{
          margin: 0;
        }

        header h1{
          margin: 0;
          height: 60px;
          line-height: 60px;
          margin-top: 10px;
          color: #f4f4f4;
          text-align: center;
        }

        nav{
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            height: 80px;
            line-height: 80px;
            background-color: #67cf7e;
            padding: 0 20px;
            border-top: 2px solid black;
        }

        nav a{
            text-align: right;
        }

        .ec_logo{
            margin: 0;
            text-align: left;
            display: inline-block;
            color: #eee;
        }

        header, .cart, .actions {
            margin-bottom: 20px;
        }
        .item {
            border: 1px solid #ddd;
            background: #fff;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 1.5em;
        }
        .item img {
            height: 100px;
        }

        .item_input{
            height: 34px;
            margin-top: 15px;
        }

        .item_input input {
            font-size: 20px;
            height: 100%;
            line-height: 34px;
            box-sizing: border-box;
        }

        .item_input button {
            font-size: 20px;
            height: 100%;
            box-sizing: border-box;
            padding: 0 10px;
            vertical-align: middle;
        }

        .cart{
          margin: 30px 30px;
        }

        .actions{
          display: flex;
          justify-content: space-between;
          height: 60px;
          line-height: 60px;
          font-size: 30px;
        }

        .actions form{
          width: 400px;
          background: #a7af7e;
          color: #f4f4f4;
        }

        .actions form button{
          width: 100%;
          line-height: 60px;
          padding: 0;
          background: #a7af7e;
          border: none;
          cursor: pointer;
          font-size: 30px;
        }

        .error {
            color: red;
            margin: 0 30px;
        }
        .message {
            color: green;
            margin: 0 30px;
        }
    </style>
</head>
<body>

<header>
    <h1>ショッピングカート</h1>
    <nav>
        <h2 class="ec_logo">&nbsp;EC&nbsp;SITE</h2>
          <div class = "link-container">
            <a href="user_item_list.php">🛍️商品一覧へ戻る</a>
            <a href="login.php">🚪ログアウト</a>
          </div>
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
                <form method="post" class="item_input">
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
