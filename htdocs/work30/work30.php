<?php 
    $host = 'localhost';
    $login_user = 'xb513874_vfrg6';
    $password = '7mumpav176';
    $database = 'xb513874_t8tcu';
    $message= [];
    $image_id;
    $image_name;
    $public_flg;
    $create_date;
    $update_date;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WORK30</title>
</head>
<body>
    <?php 
        //データベースへ接続
        $db = new mysqli($host,$login_user,$password,$database);
        if($db -> connect_error){
            echo $db -> connect_error;
            exit();
        } else {
            $db->set_charset("utf8");
        }

        if (isset($_POST['upload']))　{
            $temp_file = $_FILES['image']['tmp_name'];
            $dir = './images/';
        }

        $db -> close(); //接続を閉じる
    ?>
    <h1>画像アップロード</h1>
    <!-- 送信ボタンが押された場合 -->
     <?php if (isset($_POST['upload'])): ?>
        <p><?php echo $message; ?></p>
     <?php else: ?>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="image">
            <button><input type="submit" name="upload" value="送信"></button>
        </form>

      <?php endif;?>
</body>
</html>