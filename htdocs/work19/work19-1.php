<?php 

    $title = '';
    $Contents = '';
    $error_message = '';  // エラーメッセージ用の変数

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // フォームの入力値を取得し、エスケープ処理
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
        $Contents = htmlspecialchars($_POST['Contents'], ENT_QUOTES, 'UTF-8');

        // 入力が空の場合、エラーメッセージを設定
        if (empty($title) || empty($Contents)) {
            $error_message = 'タイトルと内容は必須項目です。';
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>タイトルを入力</div>
    <form method="post">
        <!-- 送信された値をフォームに再表示 -->
        <input type="text" name="title" value="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
        
        <div>書き込み内容を入力</div>
        <input type="text" name="Contents" value="<?php echo htmlspecialchars($Contents, ENT_QUOTES, 'UTF-8'); ?>">
        
        <p><input type="submit" value="送信"></p>
    </form>

    <!-- POSTリクエストがあった場合のみエラーメッセージを表示 -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $error_message != ''): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- 入力されたデータが空でない場合、表示する -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error_message)): ?>
        <div>・<?php echo $title; ?>：<?php echo $Contents; ?></div>
    <?php endif; ?>

</body>
</html>
