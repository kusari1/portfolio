<?php
// データベース接続情報の設定
$servername = "localhost"; // サーバー名
$username = "xb513874_vfrg6"; // ユーザー名
$password = "7mumpav176"; // パスワード
$dbname = "xb513874_t8tcu"; // データベース名

// MySQLデータベースに接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラーが発生した場合、エラーメッセージを表示して終了
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// imagesテーブルから、public_flgが1（公開中）の画像を最新のアップロード順（create_date DESC）で取得する
$sql = "SELECT image_id, image_name FROM images WHERE public_flg = 1 ORDER BY create_date DESC";

// SQLを実行し、結果を取得
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>公開画像ギャラリー</title>
    <style>
        .image-container {
            display: flex; 
            flex-wrap: wrap; 
            gap: 10px; 
        }
        .image-item {
            width: 200px; 
            text-align: center;
        }
        .image-item img {
            width: 100%; 
            height: auto;
            border-radius: 8px; 
        }
        .link {
            display: block;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>画像ギャラリー</h2>

<div class="image-container">
    <?php
    // 取得した結果が1件以上の場合、画像情報を表示する
    if ($result->num_rows > 0) {
        // データベースから取得した各画像情報に対して繰り返し処理
        while($row = $result->fetch_assoc()) {
            // 画像IDと画像ファイル名を取得
            $imageName = $row["image_name"]; // 画像ファイル名

            // 画像表示のためのHTMLを作成
            echo "<div class='image-item'>"; // 画像アイテムの始まり
            // 画像を表示。画像ファイル名はuploads/フォルダ内に保存されていることを想定
            echo "<img src='uploads/$imageName' alt='$imageName'>"; // 画像タグ。srcには保存場所を指定
            // 画像名を表示
            echo "<p>$imageName</p>"; 
            echo "</div>"; // 画像アイテムの終わり
        }
    } else {
        
        echo "<p>公開されている画像はありません。</p>";
    }
    ?>
</div>

<a href="work30.php">画像アップロード画面へ</a>
</body>
</html>

<?php
// データベース接続を閉じる
$conn->close(); // 使用後は接続を閉じる
?>
