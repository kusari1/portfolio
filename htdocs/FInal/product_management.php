<?php
session_start();
require_once('include/config/const.php');
require_once('include/model/db.php');

// ログインしていない場合はログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// DB接続
$db = new DB();
$conn = $db->connect();

// メッセージ格納用
$errors = [];
$messages = [];

// 商品追加処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $product_name = trim($_POST['product_name']);
    $price = $_POST['price'];
    $stock_qty = $_POST['stock_qty'];
    $public_flg = isset($_POST['public_flg']) ? 1 : 0;
    $image = $_FILES['product_image'];

    // 入力チェック
    if ($product_name === '' || $price === '' || $stock_qty === '' || $image['name'] === '') {
        $errors[] = '全ての項目を入力してください。';
    } elseif (!preg_match('/^\d+$/', $price) || !preg_match('/^\d+$/', $stock_qty)) {
        $errors[] = '値段と在庫数は0以上の整数を入力してください。';
    } elseif (!in_array(mime_content_type($image['tmp_name']), ['image/jpeg', 'image/png'])) {
        $errors[] = '画像はJPEGまたはPNG形式のみ対応しています。';
    }

    if (empty($errors)) {
        // 画像ファイル名生成と保存
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $safe_product_name = preg_replace('/[\\\\\/:*?"<>|]/u', '_', $product_name);
        $image_name = $safe_product_name . '.' . $extension;
        $image_path = 'images/' . $image_name;
        move_uploaded_file($image['tmp_name'], $image_path);
    
        // データベースへの登録
        $stmt = $conn->prepare("INSERT INTO product_table (product_name, price, public_flg, create_date, update_date) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$product_name, $price, $public_flg]);
        $product_id = $conn->lastInsertId();
    
        $stmt = $conn->prepare("INSERT INTO stock_table (product_id, stock_qty, create_date, update_date) VALUES (?, ?, NOW(), NOW())");
        $stmt->execute([$product_id, $stock_qty]);
    
        $stmt = $conn->prepare("INSERT INTO images_table (product_id, image_name, create_date, update_date) VALUES (?, ?, NOW(), NOW())");
        $stmt->execute([$product_id, $image_name]);
    
        // ✅ 成功時のみメッセージとリダイレクト
        $_SESSION['success_message'] = '商品が正常に登録されました。';
        header('Location: product_management.php');
        exit;
    }    
}

// 在庫更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $stock_id = $_POST['stock_id'];
    $new_stock = $_POST['stock_qty'];

    if (!preg_match('/^\d+$/', $new_stock)) {
        // 全ての半角数字
        $errors[] = '在庫数は0以上の整数で入力してください。';
    } else {
        $stmt = $conn->prepare("UPDATE stock_table SET stock_qty = ?, update_date = NOW() WHERE stock_id = ?");
        $stmt->execute([$new_stock, $stock_id]);
        $messages[] = '在庫数が更新されました。';
    }
}

// 公開ステータス変更
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_public'])) {
    $product_id = $_POST['product_id'];
    $new_status = $_POST['public_flg'];
    $stmt = $conn->prepare("UPDATE product_table SET public_flg = ?, update_date = NOW() WHERE product_id = ?");
    $stmt->execute([$new_status, $product_id]);
    $messages[] = '公開ステータスを変更しました。';
}

// 商品削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM product_table WHERE product_id = ?");
    $stmt->execute([$product_id]);

    $stmt = $conn->prepare("DELETE FROM stock_table WHERE product_id = ?");
    $stmt->execute([$product_id]);

    $stmt = $conn->prepare("DELETE FROM images_table WHERE product_id = ?");
    $stmt->execute([$product_id]);

    $messages[] = '商品を削除しました。';
}

// 商品一覧取得
$stmt = $conn->query("
    SELECT 
        p.product_id, p.product_name, p.price, p.public_flg,
        s.stock_id, s.stock_qty,
        i.image_name
    FROM product_table p
    JOIN stock_table s ON p.product_id = s.product_id
    JOIN images_table i ON p.product_id = i.product_id
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品管理</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 10px; max-width: 900px; margin: auto; }
        .messages { color: green; }
        .errors { color: red; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 3px double #000;  padding: 8px; text-align: center; }
        img { max-width: 100px; }
    </style>
</head>
<body>
<div class="container">
    <h2>商品管理ページ</h2>

    <?php foreach ($messages as $msg): ?>
        <p class="messages"><?php echo htmlspecialchars($msg); ?></p>
    <?php endforeach; ?>
    <?php foreach ($errors as $err): ?>
        <p class="errors"><?php echo htmlspecialchars($err); ?></p>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data" class="button_area">
        <h3>商品追加フォーム</h3>
        <p>商品名: <input type="text" name="product_name"></p>
        <p>値段&emsp;: <input type="number" name="price" min="0"></p>
        <p>在庫数: <input type="number" name="stock_qty" min="0"></p>
        <p>公開&emsp;: <input type="checkbox" name="public_flg" value="1"></p>
        <p>画像&emsp;: <input type="file" name="product_image" accept="image/jpeg, image/png"></p>
        <p><button type="submit" name="add_product">商品追加</button></p>
    </form>

    <h3>商品一覧</h3>
    <?php if (!empty($_SESSION['success_message'])): ?>
    <p class="success-message" style="color: green; font-weight: bold;">
    <?php
        echo htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8');
        unset($_SESSION['success_message']); // 一度表示したら削除
    ?>
    </p>
    <?php endif; ?>
    <table>
        <tr>
            <th>画像</th>
            <th>商品名</th>
            <th>値段</th>
            <th>在庫数</th>
            <th>公開ステータス</th>
            <th>操作</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><img src="images/<?php echo htmlspecialchars($product['image_name']); ?>" alt=""></td>
                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?>円</td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="number" name="stock_qty" value="<?php echo htmlspecialchars($product['stock_qty']); ?>" min="0">
                        <input type="hidden" name="stock_id" value="<?php echo $product['stock_id']; ?>">
                        <button type="submit" name="update_stock">変更</button>
                    </form>
                </td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <select name="public_flg">
                            <option value="1" <?php if ($product['public_flg']) echo 'selected'; ?>>公開</option>
                            <option value="0" <?php if (!$product['public_flg']) echo 'selected'; ?>>非公開</option>
                        </select>
                        <button type="submit" name="toggle_public">変更</button>
                    </form>
                </td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <button type="submit" name="delete_product" onclick="return confirm('本当に削除しますか？')">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="login.php">ログアウト</a></p>
</div>
</body>
</html>
