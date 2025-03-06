<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TRY24</title>
</head>
<body>
<!-- enctypeはPOSTメソッドを使用する際に、送信データのエンコード方式を設定するために記述します。 -->
<!-- フォームでファイルを取り扱う場合には、「enctype=”multipart/form-data”」と記述します。 -->
    <form method="post" action="image save.php" enctype="multipart/form-data">
      <p><input type="file" name="upload_image"></p>
      <p><input type="submit" value="送信"></p>
    </form>
</body>
</html>