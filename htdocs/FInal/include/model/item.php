<?php

// item.php では get_open_items() を再定義せずに、functions.php のものを使う
require_once __DIR__ . '/../model/functions.php';  // functions.php を読み込む

// get_open_items() を使って公開商品の情報を取得
$db = get_db_connection();
$open_items = get_open_items($db);

?>
