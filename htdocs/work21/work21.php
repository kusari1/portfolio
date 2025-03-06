<?php
  $check_data ='';		// 初期化
  if(isset($_POST['check_data'])){
    $check_data = htmlspecialchars($_POST['check_data'], ENT_QUOTES, 'UTF-8');
  }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>TRY25</title>
    </head>
    <body>
        <form method="POST">
            <div>半角アルファベットの大文字と小文字のみ入力してください。</div>
            <input type="text" name="check_data" value= <?php echo $check_data ?>>
            <input type="submit" value="送信">
        </form>
        <?php
            if (!preg_match("/^[Ａ-Ｚ0-9０-９]+$/", $check_data) && $check_data !== '') {
                if(preg_match("/dc/", $check_data)){
                  echo "ディーキャリアが含まれています";
                }
                if(preg_match("/end$/", $check_data)){
                  echo "終了です！";
                }
            }else{
              echo "正しい入力形式ではありません";
            }
        ?>
    </body>
</html>