<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image">
        <input type="submit" value="アップロード">
    </form>
    <?php 
        //$_FILESスーパーグローバル変数を使用して、アップロードされた画像情報を取得
        $image = $_FILES['image'];

        //move_uploaded_file関数を使用して、画像をサーバーの安全な場所に保存します。
         move_uploaded_file($image['tmp_name'], './images/' . $image['name']);

        //画像を表示します。
        echo '<img src="./images/' . $image['name'] . '">';
    ?>
</body>
</html>