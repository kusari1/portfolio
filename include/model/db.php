<?php

require_once MODEL_PATH . 'functions.php'; // 必要ならfunctions.phpも読み込む
require_once __DIR__ . '/../config/const.php';  // 定数読み込み

class DB {
    private $conn;

    // データベース接続
    public function connect() {
        $this->conn = null;
        try {
            // const.phpで定義した定数を使用
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
            $this->conn = new PDO($dsn, DB_USER, DB_PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
