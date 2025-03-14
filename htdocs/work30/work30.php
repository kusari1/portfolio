<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像アップロード</title>
</head>
<body>    

<?php

// データベース接続情報
$servername = "localhost";  // サーバー名
$username = "xb513874_vfrg6";  // ユーザー名
$password = "7mumpav176";  // パスワード
$dbname = "xb513874_t8tcu";  // データベース名

// MySQLデータベースに接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラーが発生した場合、エラーメッセージを表示して終了
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// フォームが送信され、画像がアップロードされた場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    // アップロードされたファイルの名前を取得
    $imageName = basename($_FILES["image"]["name"]);

    // ユーザーが指定した画像名を取得 pathinfo(ファイルの拡張子を取り出す
    $customName = !empty($_POST["custom_name"]) ? $_POST["custom_name"] : pathinfo($imageName, PATHINFO_FILENAME);

    // 公開フラグ（チェックボックスの状態に応じて1または0）
    $publicFlg = isset($_POST["public_flg"]) ? 1 : 0;

    // 画像保存先のディレクトリ
    $targetDir = "uploads/";

    // 保存するファイルのフルパスを設定
    $targetFile = $targetDir . $imageName;

    // アップロードディレクトリが存在しない場合は作成
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);  // 0777は読み書き実行可能な権限
    }

    // 許可する画像の形式を設定
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    // アップロードされたファイルの形式を取得
    $fileType = mime_content_type($_FILES["image"]["tmp_name"]);

    // 許可されたMIMEタイプでない場合はエラーメッセージを表示して処理を中断
    if (!in_array($fileType, $allowedTypes)) {
        echo "エラー: JPEG、PNG、GIFの画像のみアップロードできます。";
    } else {
        // 【ファイル移動】一時ファイルから指定のディレクトリに画像を保存
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // 【データベース登録】画像情報を `images` テーブルに追加
            $stmt = $conn->prepare("INSERT INTO images (image_id,image_name, public_flg) VALUES (1,?, ?)");

            // クエリのプレースホルダに値をバインド
            $stmt->bind_param("si", $customName, $publicFlg);

            // SQL実行
            if ($stmt->execute()) {
                echo "画像が正常にアップロードされました。";
            } else {
                echo "データベース登録エラー: " . $stmt->error;
            }

            // ステートメントを閉じる
            $stmt->close();
        } else {
            // ファイル移動に失敗した場合のエラーメッセージ
            echo "画像のアップロードに失敗しました。";
        }
    }
}

// データベース接続を閉じる
$conn->close();
?>

<!-- 画像アップロードフォーム -->
<form action="work30.php" method="post" enctype="multipart/form-data">
    画像を選択: <input type="file" name="image" required> <!-- ファイル選択ボタン -->
    <br>
    画像名（省略可）: <input type="text" name="custom_name" placeholder="画像の名前を入力"> <!-- 画像名を任意で入力 -->
    <br>
    <label><input type="checkbox" name="public_flg"> 公開する</label> <!-- 公開設定チェックボックス -->
    <br>
    <input type="submit" value="アップロード"> <!-- アップロードボタン -->
</form>

</body>
</html>
