<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php 
    //グローバル変数
    $global_variable = "グローバル変数";
    
    function set_local_variable(){
      $local_variable = "ローカル変数";
      echo "<p>関数内のローカル変数:".$local_variable."</p>";
      echo "<p>関数内のグローバル変数".$global_variable."</p>";
    }

    echo set_local_variable();
    echo "<p>関数外のグローバル変数:".$global_variable."</p>";
    echo "<p>関数外のローカル変数:".$local_variable."</p>";
  ?>
</body>
</html>