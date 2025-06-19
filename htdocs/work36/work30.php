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
            margin: 0 auto;
        }

        .publicimg{
            background-color: #aaa;
        }

        .privateimg{
            background-color: #777;
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

// データベース接続関数
function getDatabaseConnection() {
    $dsn = "mysql:host=localhost;dbname=xb513874_t8tcu;charset=utf8mb4";
    $username = "xb513874_vfrg6";
    $password = "7mumpav176";

    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("データベース接続失敗: " . $e->getMessage());
    }
}

//エラーメッセージ関数
function showError($message) {
    echo "<p style='color: red;'>エラー: $message</p>";
}

// 画像の挿入処理関数
function insertImage($pdo, $imageName, $publicFlg, $createDate, $updateDate) {
    $sql = "INSERT INTO images (image_name, public_flg, create_date, update_date) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$imageName, $publicFlg, $createDate, $updateDate]);
    return $stmt->rowCount() > 0;
}

// 画像データを取得する関数
function getImages($pdo) {
    $sql = "SELECT image_id, image_name, public_flg FROM images ORDER BY create_date DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// 画像の公開・非公開を切り替える関数
function toggleImageStatus($pdo, $imageId, $currentStatus) {
    $newStatus = $currentStatus ? 0 : 1;
    $sql = "UPDATE images SET public_flg = ?, update_date = NOW() WHERE image_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newStatus, $imageId]);
    return $stmt->rowCount() > 0;
}

// 公開・非公開の切り替え処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["toggle_id"])) {
    $pdo = getDatabaseConnection();
    $imageId = intval($_POST["toggle_id"]);
    $currentStatus = intval($_POST["current_status"]);
    
    $updated = toggleImageStatus($pdo, $imageId, $currentStatus);
    if ($updated) {
        echo "<p style='color: green;'>公開状態が更新されました。</p>";
    } else {
        showError("更新状態に失敗しました。");
    }
}

// 画像アップロード処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $pdo = getDatabaseConnection();

    // アップロードされた画像のファイル名を取得
    $imageName = basename($_FILES["image"]["name"]);
    $customName = !empty($_POST["custom_name"]) ? $_POST["custom_name"] : pathinfo($imageName, PATHINFO_FILENAME);

    // 画像名のバリデーション（半角英数字のみ許可）
    if (!preg_match('/^[a-zA-Z0-9]+$/', $customName)) {
        showError("画像名は半角英数字のみ使用できます。");
        exit; // スクリプトを停止したい場合
    }

    // ファイルの拡張子を取得
    $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $uniqueName = $customName . "." . $fileExtension;

    // 公開フラグ（チェックボックス）を取得
    $publicFlg = isset($_POST["public_flg"]) ? 1 : 0;
    $currentDate = date("Y-m-d H:i:s");

    // 画像の保存先ディレクトリを設定
    $targetDir = "uploads/";
    $targetFile = $targetDir . $uniqueName;

    // アップロード先ディレクトリが存在しない場合、作成する
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // アップロード可能な画像のMIMEタイプを指定
    $allowedTypes = ['image/jpeg', 'image/png'];
    $fileType = mime_content_type($_FILES["image"]["tmp_name"]);

    // アップロードされたファイルが許可されたタイプか確認
    if (!in_array($fileType, $allowedTypes)) {
        showError("JPG,PNG画像のみアップロード可能です。");
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $inserted = insertImage($pdo, $uniqueName, $publicFlg, $currentDate, $currentDate);
            if ($inserted) {
                echo "<p style='color: green;'>画像が正常にアップロードされました。</p>";
            } else {
                showError("データベース登録エラー");
            }
        } else {
            showError("画像のアップロードに失敗しました。");
        }
    }
}

?>

<!-- 画像アップロードフォーム -->
<h2>画像アップロード</h2>
<form action="work30.php" method="post" enctype="multipart/form-data">
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

<a href="work30_gallery.php" class="link">画像一覧ページへ</a>

<h2>アップロード済みの画像一覧</h2>
<div class="image-container">
    <?php
    $pdo = getDatabaseConnection();
    $images = getImages($pdo);

    if (count($images) > 0) {
        foreach ($images as $row) {
            $imageId = $row["image_id"];
            $imageName = $row["image_name"];
            $publicFlg = $row["public_flg"];
            $statusClass = $publicFlg ? "public" : "private";
            $statusText = $publicFlg ? "公開中" : "非公開";
            $statusimg = $publicFlg ? "publicimg" : "privateimg";
            $toggleText = $publicFlg ? "非公開にする" : "公開にする";
            $toggleClass = $publicFlg ? "toggle-private" : "toggle-public";

            echo "<div class='image-item $statusimg'>";
            echo "<img src='uploads/$imageName' alt='$imageName' style='width:50%;'>";
            echo "<p>$imageName</p>";
            echo "<p class='$statusClass'>$statusText</p>";
            echo "<form method='post' action='work30.php'>";
            echo "<input type='hidden' name='toggle_id' value='$imageId'>";
            echo "<input type='hidden' name='current_status' value='$publicFlg'>";
            echo "<button type='submit' class='toggle-btn $toggleClass'>$toggleText</button>";
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
unset($pdo);
?>
