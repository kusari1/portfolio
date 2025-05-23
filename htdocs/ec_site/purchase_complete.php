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

$purchased_items = $_SESSION['purchased_items'] ?? [];
unset($_SESSION['purchased_items']); // 表示後は破棄

$db = get_db_connection();
$user = get_login_user($db);

$cart_items = $purchased_items;
$total_price = calculate_total_price($cart_items);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>購入完了</title>
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

        .EC_logo{
            margin: 0;
            text-align: left;
            display: inline-block;
            color: #eee;
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
            text-align: center;
            width: 700px;
            background-color: #fff352;
            font-size: 30px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<header>
    <h1>購入完了</h1>
    <nav>
        <h2 class="EC_logo">&nbsp;EC&nbsp;SITE</h2>
        <div class = "link-container">
            <a href="user_item_list.php">🛍️商品一覧へ戻る</a>
            <a href="login.php">🚪ログアウト</a>
        </div>
    </nav>
</header>

<?php if (isset($error_message)): ?>
    <p class="error"><?php print h($error_message); ?></p>
<?php else: ?>
    <p class="message">購入が完了しました、ありがとうございました！</p>

    <section class="complete">
        <?php foreach ($cart_items as $item): ?>
            <div class="item">
                <img src="images/<?php print h($item['image_name']); ?>" alt="">
                <h2><?php print h($item['product_name']); ?></h2>
                <p>価格: <?php print h($item['price']); ?>円</p>
                <p>数量: <?php print h($item['product_qty']); ?></p>
                <p>小計: <?php print h($item['price'] * $item['product_qty']); ?>円</p>
            </div>
        <?php endforeach; ?>
        <div class="actions">
            <p>合計金額: <?php print h($total_price); ?>円</p>
        </div>
    </section>
<?php endif; ?>

</body>
</html>
