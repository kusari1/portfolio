<?php
// cart.php

// カートに商品を追加する
function add_to_cart($db, $user_id, $product_id) {
    // ユーザーIDと商品IDを元にカートに商品がすでに存在するか確認
    $sql = "SELECT * FROM cart_table WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // すでにカートに追加されていない場合、カートに新規追加
    if ($stmt->rowCount() === 0) {
        $sql = "INSERT INTO cart_table (user_id, product_id, product_qty, create_date, update_date) VALUES (:user_id, :product_id, 1, NOW(), NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return 'カートに商品を追加しました。';
    } else {
        // すでにカートにある場合、数量を更新
        $sql = "UPDATE cart_table SET product_qty = product_qty + 1, update_date = NOW() WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return 'カートの数量が更新されました。';
    }
}

// カートの中身を取得する
function get_cart_items($db, $user_id) {
    $sql = "
        SELECT c.cart_id, p.product_name, p.price, c.product_qty, i.image_name
        FROM cart_table c
        JOIN product_table p ON c.product_id = p.product_id
        JOIN images_table i ON p.product_id = i.product_id
        WHERE c.user_id = :user_id
    ";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// カート内の商品を削除する
function remove_from_cart($db, $cart_id) {
    $sql = "DELETE FROM cart_table WHERE cart_id = :cart_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return 'カートから商品が削除されました。';
}

// カート内の商品数量を更新する
function update_cart_quantity($db, $user_id, $product_id, $new_qty) {
    $sql = 'UPDATE cart_table SET product_qty = :new_qty WHERE user_id = :user_id AND product_id = :product_id';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':new_qty', $new_qty, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    return 'カートの商品数量が更新されました。';
}

// -------------------------------------------
// 指定ユーザーのカート情報を全件取得
// 商品名・価格・画像・在庫も結合して取得する
// -------------------------------------------
function get_user_cart($db, $user_id) {
    // ユーザーのカート情報を取得するクエリ
    $sql = 'SELECT c.product_id, c.product_qty, i.product_name, i.price, img.image_name, s.stock_qty
        FROM cart_table AS c
        JOIN product_table AS i ON c.product_id = i.product_id
        JOIN stock_table AS s ON c.product_id = s.product_id
        JOIN images_table AS img ON c.product_id = img.product_id
        WHERE c.user_id = :user_id';
    
    // クエリを準備して実行
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // 結果を返す
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// -------------------------------------------
// 指定ユーザーのカートから、特定の商品を削除
// -------------------------------------------
function delete_cart_item($db, $user_id, $product_id) {
    $sql = 'DELETE FROM cart_table WHERE user_id = ? AND product_id = ?';
    return execute_query($db, $sql, [$user_id, $product_id]);
}

// -------------------------------------------
// 指定ユーザーのカートをすべてクリア（購入後など）
// -------------------------------------------
function clear_user_cart($db, $user_id) {
    $sql = 'DELETE FROM cart_table WHERE user_id = ?';
    return execute_query($db, $sql, [$user_id]);
}

// -------------------------------------------
// 指定商品の在庫が、購入希望数量以上あるかチェック
// true（在庫あり） / false（在庫不足）を返す
// -------------------------------------------
function check_stock($db, $product_id, $qty) {
    $sql = 'SELECT stock_qty FROM stock_table WHERE product_id = ?';
    $result = fetch_query($db, $sql, [$product_id]);
    return ($result !== false && $result['stock_qty'] >= $qty);
}

// -------------------------------------------
// 商品の在庫を購入数分減らす（購入処理で使用）
// -------------------------------------------
function update_item_stock($db, $product_id, $buy_qty) {
    $sql = 'UPDATE stock_table SET stock_qty = stock_qty - ?, update_date = NOW() WHERE product_id = ?';
    return execute_query($db, $sql, [$buy_qty, $product_id]);
}

// -------------------------------------------
// カート内商品の合計金額を計算する
// -------------------------------------------
function calculate_total_price($cart_items) {
    $total = 0;
    foreach ($cart_items as $item) {
        $total += (int)$item['price'] * (int)$item['product_qty'];
    }
    return $total;
}

?>
