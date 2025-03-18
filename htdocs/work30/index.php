<?php 
    $host = 'localhost';
    $login_user = 'xb513874_vfrg6';
    $password = '7mumpav176';
    $database = 'xb513874_t8tcu';
    $error_msg = [];
    $image_id;
    $image_name;
    $public_flg;
    $create_date;
    $update_date;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TRY24</title>
</head>
    <body>
    <?php

        //ファイルが送信されていない場合はエラー
        if(!isset($_FILES['upload_image']) && !isset($_POST['upload_name'])){
            echo 'ファイルが送信されていません。';
            exit;
        }
        //データベースへ接続
        $db = new mysqli($host,$login_user,$password,$database);
        if($db -> connect_error){
            echo $db -> connect_error;
            exit();
        } else {
            $db->set_charset("utf8");
        }

        $save = 'img/' . basename($_FILES['upload_image']['name']);
        $upload_name = $_POST['upload_name'];
        $day = date('Y-m-d H:i:s');

        //ファイルを保存先ディレクトリに移動させる
        if(move_uploaded_file($_FILES['upload_image']['tmp_name'], $save)){
            echo 'アップロード成功しました。';
            // echo $_POST['upload_name'];
            // print_r($save);
            $upload = "INSERT INTO images(image_id,image_name,public_flg,create_date,update_date)VALUES(1,$upload_name,1,$day,$day);";
            if($result = $db->query($upload)){
              $row = $db->affected_rows;
          } else {
              $error_msg[] = 'UPDATE実行エラー[実行SQK]'.$upload;
          }
        }else{
            echo 'アップロード失敗しました。';
        }

        $db -> close(); //接続を閉じる
    ?>
    </body>
</html>