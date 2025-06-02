<?php
// データベース接続情報（共通定数などにまとめている場合は、requireで読み込むこと）
// require_once '../../include/config/const.php';

$host = "localhost";
$user = "xb513874_vfrg6";
$password = "7mumpav176";
$dbname = "xb513874_t8tcu";

// MySQLデータベースに接続
$conn = new mysqli($host, $user, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// 公開画像のみ取得
$sql = "SELECT image_id, image_name FROM images WHERE public_flg = 1 ORDER BY create_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
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

<h2>公開画像ギャラリー</h2>

<div class="image-container">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="image-item">
                <img src="uploads/<?php echo htmlspecialchars($row['image_name'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
                <p><?php echo htmlspecialchars($row['image_name'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>公開されている画像はありません。</p>
    <?php endif; ?>
</div>

<a class="link" href="work30.php">画像アップロード画面へ戻る</a>

</body>
</html>

<?php
$conn->close();
?>
