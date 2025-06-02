<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>user_item_list.css">
</head>
<body>
    <header>
        <h1>商品一覧</h1>
        <nav>
            <h2 class="EC_logo">&nbsp;EC&nbsp;SITE</h2>
            <div class="link-container">
                <a href="cart.php">🛒 ショッピングカート</a>
                <a href="login.php?logout=1">🚪 ログアウト</a>
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
                    <img src="images/<?php echo h($item['image_name']); ?>" alt="<?php echo h($item['product_name']); ?>">
                    <nav class="item_container">
                        <h2><?php echo h($item['product_name']); ?></h2>
                        <p>1個：<?php echo h($item['price']); ?>円</p>
                        <?php if ((int)$item['stock_qty'] > 0): ?>
                    </nav>
                    <form method="post">
                        <input type="hidden" name="item_id" value="<?php echo h($item['product_id']); ?>">
                        <input type="number" name="quantity" min="1" value="1">
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
