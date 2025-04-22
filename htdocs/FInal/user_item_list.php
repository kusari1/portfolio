<?php

require_once 'include/config/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// ログインしていない場合はログインページへ
if (!is_logined()) {
    header('Location: login.php');
    exit;
}

require_once MODEL_PATH . 'db.php';
$db_instance = new DB();
$db = $db_instance->connect();

$user = get_login_user($db);

// カート追加処理
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = get_post('item_id');
    
    if (add_to_cart($db, $user['user_id'], $item_id)) {
        $message = 'カートに商品を追加しました。';
    } else {
        $message = 'カートの更新に失敗しました。';
    }    
}

// 商品情報を取得
$items = get_open_items($db);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            height: 100vh;
            margin: 0;
        }

        header{
            background: #67cf7e;
        }

        header h1{
            background: #a7af7e;
            text-align: center;
            color: #f4f4f4;
            margin-top: 10px;
            margin-bottom: 0px;
            border-bottom: 2px solid black;
            height: 60px;
            line-height: 60px;
        }

        .EC_logo{
            background-color: #67cf7e;
            margin: 0;
            text-align: left;
            display: inline-block;
            color: #eee;
            margin-left: 20px;
        }

        .link-container{
            margin-right: 20px;
        }

        nav{

            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            height: 80px;
            line-height: 80px;
        }

        nav a{
            text-align: right;
        }

        section {
            margin-top: 20px; /* 上に余白を追加 */
            display: flex;
        }

        section .item {
            margin: 0 10px;
        }

        section .item img{
            width: 400px;
            height: 400px;
            display: block;
            border: 2px solid gray;
            box-sizing: border-box;
        }

        section .item h2,p{
            display: inline-block;
            margin-left: 100px;
        }

        form{
            margin: 0 auto;
            width: 120px;
        }

    </style>
</head>
<body>

    <header>
        <h1>商品一覧</h1>
        <nav>
            <h2 class="EC_logo">&nbsp;EC&nbsp;SITE</h2>
            <div class = "link-container">
                <a href="cart.php">🛒 ショッピングカート</a>
                <a href="login.php">🚪 ログアウト</a>
            </div>
        </nav>
    </header>

    <?php if ($message !== ''): ?>
        <p class="message"><?php print h($message); ?></p>
    <?php endif; ?>

    <section class="item-list">
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
                <div class="item">
                <img src="images/<?php echo htmlspecialchars($item['image_name']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                    <h2><?php print h($item['product_name']); ?></h2>
                    <p><?php print h($item['price']); ?>円</p>
                    <?php if ((int)$item['stock_qty'] > 0): ?>
                        <form method="post">
                            <input type="hidden" name="item_id" value="<?php print h($item['product_id']); ?>">
                            <button type="submit">カートに入れる</button>
                        </form>
                    <?php else: ?>
                        <p class="sold-out">売り切れ</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>公開されている商品はありません。</p>
        <?php endif; ?>
    </section>

</body>
</html>
