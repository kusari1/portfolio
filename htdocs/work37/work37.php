<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>work37</title>
</head>
<body>
<?php
// Cookieに値がある場合、変数に格納する
$cookie_confirmation = isset($_COOKIE['cookie_confirmation']) ? "checked" : "";
$user_id = $_COOKIE['user_id'] ?? '';
$user_name = $_COOKIE['user_name'] ?? '';
?>

<form action="home.php" method="post">
    <label for="user_id">ユーザーID:</label>
    <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>"><br>

    <label for="user_name">ユーザー名:</label>
    <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>"><br>

    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password"><br>

    <input type="checkbox" name="cookie_confirmation" value="checked" <?php echo $cookie_confirmation; ?>> 次回からログインIDの入力を省略する<br>
    
    <input type="submit" value="ログイン">
</form>
</body>
</html>
