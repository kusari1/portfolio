<?php
// データベース接続情報の設定
$host = "localhost"; // サーバー名
$user = "xb513874_vfrg6"; // ユーザー名
$password = "7mumpav176"; // パスワード
$dbname = "xb513874_t8tcu"; // データベース名

// MySQLに接続
$conn = new mysqli($host, $user, $password, $dbname);

// 接続エラーチェック
if ($conn->connect_error) {
    die('データベース接続失敗: ' . $conn->connect_error);
}

$message = '';

// 画像がアップロードされたか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $publicFlg = 1; // 初期値として公開

    // ユーザーが指定した名前を取得（未入力の場合はエラーメッセージ）
    $imageName = isset($_POST['image_name']) ? trim($_POST['image_name']) : '';

    if (empty($imageName)) {
        $message = '画像の名前を入力してください。';
    } else {
        // アップロード成功かチェック
        if ($image['error'] === UPLOAD_ERR_OK) {
            $tmpName = $image['tmp_name'];
            $originalName = basename($image['name']);
            $ext = pathinfo($originalName, PATHINFO_EXTENSION);  // 拡張子を取得
            $uniqueName = $imageName . '.' . $ext;  // ユーザー指定の名前 + 拡張子

            $uploadDir = 'uploads/';
            $uploadPath = $uploadDir . $uniqueName;

            // アップロードディレクトリがなければ作成
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($tmpName, $uploadPath)) {
                $date = date('Y-m-d');

                // 画像情報をデータベースに挿入
                $stmt = $conn->prepare("INSERT INTO images (public_flg, image_name, create_date, update_date) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $publicFlg, $uniqueName, $date, $date);
                $stmt->execute();

                $message = '画像をアップロードしました。';
            } else {
                $message = '画像の保存に失敗しました。';
            }
        } else {
            $message = '画像のアップロード中にエラーが発生しました。';
        }
    }
}

// 公開・非公開切り替え処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
    $toggleId = $_POST['toggle_id'];
    $currentFlg = $_POST['current_flg'];
    $newFlg = $currentFlg == 1 ? 0 : 1;
    $updateDate = date('Y-m-d');

    $stmt = $conn->prepare("UPDATE images SET public_flg = ?, update_date = ? WHERE image_id = ?");
    $stmt->bind_param("isi", $newFlg, $updateDate, $toggleId);
    $stmt->execute();

    $message = '公開状態を変更しました。';
}

// 全画像を取得
$result = $conn->query("SELECT * FROM images ORDER BY create_date DESC");
$images = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像アップロード</title>
    <style>
        ul{
          list-style: none;  
        }

        ul li{
            width: 350px;
        }

        ul li img{
            width: 100%;
            background-size: cover;
        }

        ul li p{
            margin: 0;
            text-align: center;
        }

        ul li form{
            display: block;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>画像アップロード</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <!-- ユーザー指定の画像名入力フィールド -->
        <input type="text" name="image_name" placeholder="画像名" required>
        <input type="file" name="image" required>
        <button type="submit">アップロード</button>
    </form>

    <h2>アップロード済み画像</h2>
    <ul>
        <?php foreach ($images as $img): ?>
            <li>
                <img src="uploads/<?php echo htmlspecialchars($img['image_name'], ENT_QUOTES, 'UTF-8'); ?>" width="200">
                <p>公開状態: <?php echo $img['public_flg'] == 1 ? '公開' : '非公開'; ?></p>
                <form method="post">
                    <input type="hidden" name="toggle_id" value="<?php echo $img['image_id']; ?>">
                    <input type="hidden" name="current_flg" value="<?php echo $img['public_flg']; ?>">
                    <button type="submit"><?php echo $img['public_flg'] == 1 ? '非公開にする' : '公開にする'; ?></button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <a class="link" href="work30_gallery.php">画像ギャラリーへ</a>
</body>
</html>
