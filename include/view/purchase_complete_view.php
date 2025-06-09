<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>購入完了</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>purchase_complete.css">
</head>
<body>

<header>
    <h1>購入完了</h1>
    <nav>
        <h2 class="EC_logo">&nbsp;EC&nbsp;SITE</h2>
        <div class="link-container">
            <a href="user_item_list.php">🛍️商品一覧へ戻る</a>
            <a href="login.php?logout=1">🚪 ログアウト</a>
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
