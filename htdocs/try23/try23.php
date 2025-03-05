<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <?php 
            $fp = fopen("file_write.txt","w");

            fwrite($fp,'ファイルへ書き込む'); //ファイへ書き込む

            fclose($fp);

        ?>
    </form>
</body>
</html>