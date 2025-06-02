<?php
class ImageModel {
    private $conn; // データベース接続用のプロパティ

    // コンストラクタ: データベースに接続
    public function __construct() {
        $this->conn = new mysqli("localhost", "xb513874_vfrg6", "7mumpav176", "xb513874_t8tcu");
        
        // 接続エラーがある場合はスクリプトを停止し、エラーメッセージを表示
        if ($this->conn->connect_error) {
            die("接続失敗: " . $this->conn->connect_error);
        }
    }

    // 画像をデータベースに登録するメソッド
    public function uploadImage($imageName, $publicFlg) {
        // 画像情報をデータベースに挿入
        $stmt = $this->conn->prepare("INSERT INTO images (image_name, public_flg, create_date, update_date) VALUES (?, ?, NOW(), NOW())");
        $stmt->bind_param("si", $imageName, $publicFlg); // バインド変数: 画像名(string)と公開フラグ(int)
        return $stmt->execute(); // クエリ実行
    }

    // 全ての画像データを取得するメソッド
    public function getAllImages() {
        $result = $this->conn->query("SELECT image_id, image_name, public_flg FROM images ORDER BY create_date DESC");
        return $result->fetch_all(MYSQLI_ASSOC); // 取得結果を連想配列として返す
    }

    // 画像の公開・非公開を切り替えるメソッド
    public function toggleImageStatus($imageId, $currentStatus) {
        $newStatus = $currentStatus ? 0 : 1; // 現在の状態を反転（公開→非公開、非公開→公開）
        $stmt = $this->conn->prepare("UPDATE images SET public_flg = ?, update_date = NOW() WHERE image_id = ?");
        $stmt->bind_param("ii", $newStatus, $imageId); // バインド変数: 公開フラグ(int)と画像ID(int)
        return $stmt->execute(); // クエリ実行
    }

    // 公開状態の画像のみを取得するメソッド
    public function getPublicImages() {
        $result = $this->conn->query("SELECT image_id, image_name FROM images WHERE public_flg = 1 ORDER BY create_date DESC");
        return $result->fetch_all(MYSQLI_ASSOC); // 取得結果を連想配列として返す
    }
}
?>
