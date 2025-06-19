<?php
session_start();
require_once('../../include/config/const.php');
require_once('../../include/model/db.php');

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// DB接続
$db = new DB();
$conn = $db->connect();

// セッションからメッセージを取得して初期化
$errors = $_SESSION['errors'] ?? [];
$messages = $_SESSION['messages'] ?? [];
unset($_SESSION['errors'], $_SESSION['messages']);

// 商品追加処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $errors = [];
    $messages = [];

    $product_name = trim($_POST['product_name']);
    $price = $_POST['price'];
    $stock_qty = $_POST['stock_qty'];
    $public_flg = isset($_POST['public_flg']) ? 1 : 0;
    $image = $_FILES['product_image'];

    // 入力チェック
    if ($product_name === '') $errors[] = '商品名を入力してください。';
    if ($price === '' || !preg_match('/^\d+$/', $price)) $errors[] = '価格は0以上の整数で入力してください。';
    if ($stock_qty === '' || !preg_match('/^\d+$/', $stock_qty)) $errors[] = '在庫数は0以上の整数で入力してください。';
    if ($image['name'] === '') {
        $errors[] = '商品画像を選択してください。';
    } elseif (!in_array(mime_content_type($image['tmp_name']), ['image/jpeg', 'image/png'])) {
        $errors[] = '画像はJPEGまたはPNG形式のみ対応しています。';
    }

    if (empty($errors)) {
        $image_name = uniqid() . '_' . basename($image['name']);
        $image_path = 'images/' . $image_name;
        move_uploaded_file($image['tmp_name'], $image_path);

        // 商品登録
        $stmt = $conn->prepare("INSERT INTO product_table (product_name, price, public_flg, create_date, update_date) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$product_name, $price, $public_flg]);
        $product_id = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO stock_table (product_id, stock_qty, create_date, update_date) VALUES (?, ?, NOW(), NOW())");
        $stmt->execute([$product_id, $stock_qty]);

        $stmt = $conn->prepare("INSERT INTO images_table (product_id, image_name, create_date, update_date) VALUES (?, ?, NOW(), NOW())");
        $stmt->execute([$product_id, $image_name]);

        $messages[] = '商品が追加されました。';
    }

    // メッセージをセッションへセットしてリダイレクト
    $_SESSION['errors'] = $errors;
    $_SESSION['messages'] = $messages;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// 在庫数更新
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $errors = [];
    $messages = [];

    $stock_id = $_POST['stock_id'];
    $new_stock = $_POST['stock_qty'];

    if (!preg_match('/^\d+$/', $new_stock)) {
        $errors[] = '在庫数は0以上の整数で入力してください。';
    } else {
        $stmt = $conn->prepare("UPDATE stock_table SET stock_qty = ?, update_date = NOW() WHERE stock_id = ?");
        $stmt->execute([$new_stock, $stock_id]);
        $messages[] = '在庫数が更新されました。';
    }

    $_SESSION['errors'] = $errors;
    $_SESSION['messages'] = $messages;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// 公開ステータス変更
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_public'])) {
    $errors = [];
    $messages = [];

    $product_id = $_POST['product_id'];
    $new_status = $_POST['public_flg'];
    $stmt = $conn->prepare("UPDATE product_table SET public_flg = ?, update_date = NOW() WHERE product_id = ?");
    $stmt->execute([$new_status, $product_id]);
    $messages[] = '公開ステータスを変更しました。';

    $_SESSION['errors'] = $errors;
    $_SESSION['messages'] = $messages;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// 商品削除
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $errors = [];
    $messages = [];

    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM product_table WHERE product_id = ?");
    $stmt->execute([$product_id]);

    $stmt = $conn->prepare("DELETE FROM stock_table WHERE product_id = ?");
    $stmt->execute([$product_id]);

    $stmt = $conn->prepare("DELETE FROM images_table WHERE product_id = ?");
    $stmt->execute([$product_id]);

    $messages[] = '商品を削除しました。';

    $_SESSION['errors'] = $errors;
    $_SESSION['messages'] = $messages;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
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

// Viewを呼び出す
include_once VIEW_PATH . 'product_manage_view.php';
