<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        //データベースへ接続
        $db = new mysqli('localhost','xb513874_vfrg6','7mumpav176','xb513874_t8tcu');
        if($db -> connect_error){
            echo $db -> connect_error;
            exit();
        }else{
            print("データベースへの接続に成功しました。");
        }
        $db -> close(); //接続を閉じる
    ?>
</body>
</html>