<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像アップロード</title>
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
        .public {
            color: green;
        }
        .private {
            color: red;
        }
        .link {
            display: block;
            margin-top: 20px;
        }
        .toggle-btn {
            display: inline-block;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .toggle-public {
            background-color: green;
            color: white;
        }
        .toggle-private {
            background-color: red;
            color: white;
        }
        img{
            display: inline-block;
            margin: 0 10px;
        }
    </style>
</head>
<body>    

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

// 公開・非公開の切り替え処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["toggle_id"])) {
    // POSTデータから画像IDと現在の公開状態を取得
    $imageId = intval($_POST["toggle_id"]);
    $currentStatus = intval($_POST["current_status"]);
    
    // 公開状態を切り替える（公開→非公開 or 非公開→公開）
    $newStatus = $currentStatus ? 0 : 1;

    // 画像の公開・非公開を更新するSQL文
    $stmt = $conn->prepare("UPDATE images SET public_flg = ?, update_date = NOW() WHERE image_id = ?");
    $stmt->bind_param("ii", $newStatus, $imageId); // パラメータをバインド

    // SQL文の実行結果に応じてメッセージを表示
    if ($stmt->execute()) {
        // ここでは何も表示しない（コメントアウトされています）
    } else {
        echo "<p style='color: red;'>エラー: " . $stmt->error . "</p>";
    }

    // ステートメントを閉じる
    $stmt->close();
}

// 画像アップロード処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    // アップロードされた画像のファイル名を取得
    $imageName = basename($_FILES["image"]["name"]);
    
    // ユーザーが入力した画像名があれば、それを使用。なければファイル名をそのまま使用
    $customName = !empty($_POST["custom_name"]) ? $_POST["custom_name"] : pathinfo($imageName, PATHINFO_FILENAME);
    
    // ファイルの拡張子を取得
    $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

    // ユーザーが入力した名前に拡張子を追加
    $uniqueName = $customName . "." . $fileExtension; // 例えば、sample.png

    // 公開フラグ（チェックボックス）を取得
    $publicFlg = isset($_POST["public_flg"]) ? 1 : 0;
    
    // 現在の日付と時間を取得
    $currentDate = date("Y-m-d H:i:s");

    // 画像の保存先ディレクトリを設定
    $targetDir = "uploads/";
    
    // 完全なファイルパスを設定
    $targetFile = $targetDir . $uniqueName;

    // アップロード先ディレクトリが存在しない場合、作成する
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // アップロード可能な画像のMIMEタイプを指定
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($_FILES["image"]["tmp_name"]);

    // アップロードされたファイルが許可されたタイプか確認
    if (!in_array($fileType, $allowedTypes)) {
        echo "エラー: JPEG、PNG、GIFの画像のみアップロードできます。";
    } else {
        // ファイルを指定の場所に移動
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // 画像情報をデータベースに挿入するSQL文
            $stmt = $conn->prepare("INSERT INTO images (image_name, public_flg, create_date, update_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siss", $uniqueName, $publicFlg, $currentDate, $currentDate); // パラメータをバインド

            // SQL文を実行し、結果に応じてメッセージを表示
            if ($stmt->execute()) {
                echo "<p style='color: green;'>画像が正常にアップロードされました。</p>";
            } else {
                echo "<p style='color: red;'>データベース登録エラー: " . $stmt->error . "</p>";
            }

            // ステートメントを閉じる
            $stmt->close();
        } else {
            echo "<p style='color: red;'>画像のアップロードに失敗しました。</p>";
        }
    }
}


?>

<!-- 画像アップロードフォーム -->
<h2>画像アップロード</h2>
<form action="work302.php" method="post" enctype="multipart/form-data">
    <label>画像を選択:</label>
    <input type="file" name="image" required>
    <br><br>

    <label>画像名:</label>
    <input type="text" name="custom_name" placeholder="画像の名前を入力">
    <br><br>

    <label>
        <input type="checkbox" name="public_flg"> 公開する
    </label>
    <br><br>

    <input type="submit" value="アップロード">
</form>

<h2>アップロード済みの画像一覧</h2>
<div class="image-container">
    <?php
    // 画像情報をデータベースから取得するSQL文
    $sql = "SELECT image_id, image_name, public_flg FROM images ORDER BY create_date DESC";
    $result = $conn->query($sql);

    // 結果があれば、画像ごとに表示
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $imageId = $row["image_id"]; // 画像ID
            $imageName = $row["image_name"]; // 画像ファイル名
            $publicFlg = $row["public_flg"]; // 公開・非公開フラグ
            $statusClass = $publicFlg ? "public" : "private"; // 公開・非公開に応じたクラス名
            $statusText = $publicFlg ? "公開中" : "非公開"; // 公開・非公開の状態テキスト
            $toggleText = $publicFlg ? "非公開にする" : "公開にする"; // ボタンのテキスト
            $toggleClass = $publicFlg ? "toggle-private" : "toggle-public"; // ボタンのクラス名

            // 画像の表示
            echo "<div class='image-item'>";
            echo "<img src='uploads/$imageName' alt='$imageName'>"; // 画像表示
            echo "<p class='$statusClass'>$statusText</p>"; // 公開・非公開の状態表示
            echo "<form method='post' action='work302.php'>";
            echo "<input type='hidden' name='toggle_id' value='$imageId'>"; // 画像ID
            echo "<input type='hidden' name='current_status' value='$publicFlg'>"; // 現在の公開状態
            echo "<button type='submit' class='toggle-btn $toggleClass'>$toggleText</button>"; // 状態切替ボタン
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>画像がありません。</p>";
    }
    ?>
</div>

</body>
</html>

<?php
// データベース接続を閉じる
$conn->close();
?>

