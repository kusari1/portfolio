<?php 

$title = '';
$Contents = '';
$error_message = '';  // エラーメッセージ用の変数
$upload_result = ''; // 画像アップロード結果（表示用）

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームの入力値を取得し、エスケープ処理
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    $Contents = htmlspecialchars($_POST['Contents'], ENT_QUOTES, 'UTF-8');

    // 入力必須チェック（タイトル・内容・画像すべて必須）
    if (empty($title) || empty($Contents) || empty($_FILES['upload_image']['name'])) {
        $error_message = '入力情報が不足しています。';
    } else {
        // 画像ファイル名を取得（表示用）
        $upload_result = htmlspecialchars($_FILES['upload_image']['name'], ENT_QUOTES, 'UTF-8');
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>入力チェック</title>
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
        <p style="color: red;"><?php echo $error_message; ?></p> <!-- 入力エラーがある場合に表示 -->
    <?php endif; ?>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !$error_message): ?>
        <div>
            <p>入力された内容：</p>
            <p>・タイトル：<?php echo $title; ?></p>
            <p>・内容：<?php echo $Contents; ?></p>
            <?php 
              $save = 'img/' . basename($_FILES['upload_image']['name']);

              //ファイルを保存先ディレクトリに移動させる
              if(move_uploaded_file($_FILES['upload_image']['tmp_name'], $save)){
                  echo 'アップロード成功しました。';
              }else{
                  echo 'アップロード失敗しました。';
              }
            ?>
            <p>・アップロード画像名：<?php echo '<img src="'.$save.'">'; ?></p>
        </div>
    <?php endif; ?>
</body>
</html>
