<?php
session_start();

// ログインしていない場合、work38.phpにリダイレクト
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header("Location: work38.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>home</title>
</head>
<body>
    <p><?php echo $_SESSION['user_name']; ?>さん：ログイン中です。</p>
    <!-- ログアウトフォーム -->
    <form action="work38.php" method="post">
        <input type="hidden" name="logout" value="logout">
        <input type="submit" value="ログアウト">
    </form>
</body>
</html>
