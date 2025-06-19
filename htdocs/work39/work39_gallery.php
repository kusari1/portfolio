<?php
// 必要なモデルファイルを読み込む
require_once "../../include/model/work39_model.php";

// 画像データを管理するモデルのインスタンスを作成
$model = new ImageModel();

// 公開されている画像のみを取得
$publicImages = $model->getPublicImages();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>公開画像ギャラリー</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        /* 画像コンテナのレイアウト設定 */
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
            margin: 0 auto;
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
        img {
            display: inline-block;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <h2>画像ギャラリー</h2>
    <div class="image-container">
        <?php
        // 公開されている画像が存在する場合、それらを表示
        if (count($publicImages) > 0) {
            foreach ($publicImages as $row) {
                echo "<div class='image-item'>";
                echo "<img src='uploads/{$row['image_name']}' alt='{$row['image_name']}' style='width:50%;'>";
                echo "<p>{$row['image_name']}</p>";
                echo "</div>";
            }
        } else {
            // 公開されている画像がない場合、メッセージを表示
            echo "<p>公開されている画像はありません。</p>";
        }
        ?>
    </div>
    <!-- 画像アップロード画面へのリンク -->
    <a href="work39.php">画像アップロード画面へ</a>
</body>
</html>
