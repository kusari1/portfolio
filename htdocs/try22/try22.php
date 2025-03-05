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
            $fp = fopen("file_read.txt","r");
            //ファイルを開く
            while($line = fgets(($fp))){
                echo "$line<br>";
            }
            fclose($fp);
        ?>
    </form>
</body>
</html>