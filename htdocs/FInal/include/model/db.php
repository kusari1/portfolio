<?php
class DB {
    private $host = 'localhost';  // ここはデータベースホストに合わせて変更
    private $dbname = 'xb513874_t8tcu';  // データベース名
    private $username = 'xb513874_vfrg6';  // データベースのユーザー名
    private $password = '7mumpav176';  // データベースのパスワード

    private $conn;

    // データベース接続
    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
