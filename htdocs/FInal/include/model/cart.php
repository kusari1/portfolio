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
function update_cart_quantity($db, $cart_id, $new_qty) {
    $sql = "UPDATE cart_table SET product_qty = :new_qty, update_date = NOW() WHERE cart_id = :cart_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->bindParam(':new_qty', $new_qty, PDO::PARAM_INT);
    $stmt->execute();
    
    return 'カートの商品数量が更新されました。';
}
?>
