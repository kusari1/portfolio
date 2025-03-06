<?php 

$title = '';
$Contents = '';
$error_message = '';
$upload_result = ''; // アップロード結果メッセージ

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    $Contents = htmlspecialchars($_POST['Contents'], ENT_QUOTES, 'UTF-8');

    if (empty($title) || empty($Contents)) {
        $error_message = 'タイトルと内容は必須項目です。';
    }

    // ★ ファイルがアップロードされているか確認（isset + エラーコードチェック）
    if (isset($_FILES['upload_image']) && $_FILES['upload_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'img/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $save_path = $upload_dir . basename($_FILES['upload_image']['name']);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($save_path, PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_extensions) && move_uploaded_file($_FILES['upload_image']['tmp_name'], $save_path)) {
            $upload_result = '<img src="' . htmlspecialchars($save_path, ENT_QUOTES, 'UTF-8') . '">';
        } else {
            $upload_result = '<p style="color: red;">アップロードに失敗しました。</p>';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ファイルアップロード</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <div>タイトルを入力</div>
        <input type="text" name="title" value="<?php echo $title; ?>">
        
        <div>書き込み内容を入力</div>
        <input type="text" name="Contents" value="<?php echo $Contents; ?>">
        
        <p><input type="file" name="upload_image"></p>
        <p><input type="submit" value="送信"></p>
    </form>

    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !$error_message): ?>
        <div>・<?php echo $title; ?>：<?php echo $Contents; ?></div>
        <?php echo $upload_result; ?>
    <?php endif; ?>
</body>
</html>
