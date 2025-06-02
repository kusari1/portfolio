<?php

// セッションにユーザーIDがあるかどうかでログイン判定
function is_logined() {
    return isset($_SESSION['user_id']);
}

function get_user_by_id($db, $user_id) {
    $sql = 'SELECT user_id, user_name FROM user_table WHERE user_id = ?';
    $params = array($user_id);
    $result = fetch_all_query($db, $sql, $params);

    // 1件しか返らないはずなので、1件目を返す
    return isset($result[0]) ? $result[0] : null;
}



// セッションからユーザー名を取得（使うかはお好み）
function get_login_user($db) {
    if (isset($_SESSION['user_id']) === FALSE) {
        return null;
    }

    $user_id = $_SESSION['user_id'];
    return get_user_by_id($db, $user_id);
}
?>

