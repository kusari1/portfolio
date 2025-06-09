<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品管理</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>product_management.css">
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const productName = form.querySelector('input[name="product_name"]');
        const price = form.querySelector('input[name="price"]');
        const stockQty = form.querySelector('input[name="stock_qty"]');
        const image = form.querySelector('input[name="product_image"]');
        
        const errorContainer = document.createElement('ul');
        errorContainer.style.color = 'red';
        form.insertBefore(errorContainer, form.firstChild);

        form.addEventListener('submit', function (e) {
            errorContainer.innerHTML = '';
            let errors = [];

            if (productName.value.trim() === '') errors.push('商品名を入力してください。');
            if (price.value === '' || !/^\d+$/.test(price.value)) errors.push('価格は0以上の整数で入力してください。');
            if (stockQty.value === '' || !/^\d+$/.test(stockQty.value)) errors.push('在庫数は0以上の整数で入力してください。');
            if (image.files.length === 0) errors.push('商品画像を選択してください。');

            if (errors.length > 0) {
                e.preventDefault();
                errors.forEach(err => {
                    const li = document.createElement('li');
                    li.textContent = err;
                    errorContainer.appendChild(li);
                });
            }
        });
    });
    </script>
</head>
<body>
    <div class="container">
        <h2>商品管理ページ</h2>

        <?php foreach ($messages as $msg): ?>
            <p style="color:green;"><?php echo htmlspecialchars($msg); ?></p>
        <?php endforeach; ?>
        <?php foreach ($errors as $err): ?>
            <p style="color:red;"><?php echo htmlspecialchars($err); ?></p>
        <?php endforeach; ?>

        <form method="POST" enctype="multipart/form-data">
            <h3>商品追加フォーム</h3>
            <p>商品名: <input type="text" name="product_name"></p>
            <p>値段: <input type="number" name="price" min="0"></p>
            <p>在庫数: <input type="number" name="stock_qty" min="0"></p>
            <p>公開: <input type="checkbox" name="public_flg" value="1"></p>
            <p>画像: <input type="file" name="product_image" accept="image/jpeg, image/png"></p>
            <p><button type="submit" name="add_product">商品追加</button></p>
        </form>

        <h3>商品一覧</h3>
        <table>
            <tr>
                <th>画像</th>
                <th>商品名</th>
                <th>値段</th>
                <th>在庫数</th>
                <th>公開</th>
                <th>操作</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><img src="images/<?php echo htmlspecialchars($product['image_name']); ?>" width="100"></td>
                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?>円</td>
                <td>
                    <form method="POST">
                        <input type="number" name="stock_qty" value="<?php echo $product['stock_qty']; ?>" min="0">
                        <input type="hidden" name="stock_id" value="<?php echo $product['stock_id']; ?>">
                        <button type="submit" name="update_stock">変更</button>
                    </form>
                </td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <select name="public_flg">
                            <option value="1" <?php if ($product['public_flg']) echo 'selected'; ?>>公開</option>
                            <option value="0" <?php if (!$product['public_flg']) echo 'selected'; ?>>非公開</option>
                        </select>
                        <button type="submit" name="toggle_public">変更</button>
                    </form>
                </td>
                <td>
                    <form method="POST" onsubmit="return confirm('本当に削除しますか？')">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <button type="submit" name="delete_product">削除</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <p><a href="login.php?logout=1">🚪 ログアウト</a></p>
    </div>
</body>
</html>
