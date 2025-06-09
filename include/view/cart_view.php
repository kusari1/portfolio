<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ショッピングカート</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>cart.css">
</head>
<body>
<header>
    <h1>ショッピングカート</h1>
    <nav>
        <h2 class="ec_logo">&nbsp;EC&nbsp;SITE</h2>
        <div class="link-container">
            <a href="user_item_list.php">🛍️商品一覧へ戻る</a>
            <a href="login.php?logout=1">🚪 ログアウト</a>
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
