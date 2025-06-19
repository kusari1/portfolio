<?php
// 必要なモデルとビューのファイルを読み込む
require_once "../../include/model/work39_model.php";
require_once "../../include/view/work39_view.php";

// 画像を管理するモデルをインスタンス化
$model = new ImageModel();

// フォームが送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["image"])) {
        // 画像アップロード処理
        $imageName = basename($_FILES["image"]["name"]); // アップロードされた画像のファイル名を取得
        $publicFlg = isset($_POST["public_flg"]) ? 1 : 0; // 公開フラグをチェックボックスの有無で設定（デフォルトは非公開）
        $targetDir = "uploads/"; // 画像を保存するディレクトリ
        $targetFile = $targetDir . $imageName; // 保存先のファイルパス

        // アップロードディレクトリが存在しない場合は作成
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // ファイルを指定のディレクトリに移動
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // データベースに画像情報を保存
            $model->uploadImage($imageName, $publicFlg);
            // 成功時にリダイレクト
            header("Location: work39.php?success=1");
        } else {
            // 失敗時にエラーメッセージ付きでリダイレクト
            header("Location: work39.php?error=1");
        }
        exit();
    } elseif (isset($_POST["toggle_id"])) {
        // 画像の公開・非公開の切り替え処理
        $model->toggleImageStatus($_POST["toggle_id"], $_POST["current_status"]);
        // 更新後にリダイレクト
        header("Location: work39.php");
        exit();
    }
}

// すべての画像データを取得
$images = $model->getAllImages();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像アップロード</title>
    <style>
        /* 画像コンテナのレイアウト調整 */
        .image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .image-item {
            width: 300px;
            text-align: center;
        }
        .image-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        /* 公開・非公開の背景色設定 */
        .publicimg {
            background-color: #aaa;
        }
        .privateimg {
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
    </style>
</head>
<body>
    <h2>画像アップロード</h2>
    <!-- 画像アップロードフォーム -->
    <form action="work39.php" method="post" enctype="multipart/form-data">
        <label>画像を選択:</label>
        <input type="file" name="image" required><br><br>
        <label><input type="checkbox" name="public_flg"> 公開する</label><br><br>
        <input type="submit" value="アップロード">
    </form>
    <!-- 画像一覧ページへのリンク -->
    <a href="work39_gallery.php" class="link">画像一覧ページへ</a>

    <!-- 画像一覧の表示 -->
    <?php displayImages($images); ?>
</body>
</html>
