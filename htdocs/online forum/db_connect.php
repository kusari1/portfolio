<?php

// include/config/const.php

// モデルファイルのパス
define('MODEL_PATH', dirname(__FILE__) . '/../model/');
define('DB_HOST', 'localhost');
define('DB_NAME', 'xb513874_t8tcu');
define('DB_USER', 'xb513874_vfrg6');
define('DB_PASS', '7mumpav176');
define('IMAGE_PATH', '../../htdocs/ec_site/images');
define('VIEW_PATH', '../../include/view/');
define('CSS_PATH', './assets/');

// データベース接続
function doDB() {
    $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
        die("データベース接続に失敗しました: " . mysqli_connect_error());
    }
    mysqli_set_charset($mysqli, "utf8mb4");
    return $mysqli;
}
?>
