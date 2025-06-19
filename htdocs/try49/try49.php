<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    //引数なし 帰り値なしの関数を実行
    output_function();
    //引数あり・返り値なしの関数を実行
    output_function_num(10);
    // 引数あり 返り値ありの関数を実行し、返り値を出力
    $function_num = make_function_num(10);
    echo $function_num;

    function output_function(){
      echo "<p>引数:なし、返り値:なしの関数</p>";
    }

    function output_function_num($num){
      echo "<p>引数:".$num."返り値:なしの関数</p>";
    }

    function make_function_num($num){
      $str = "<p>引数:".$num."返り値:ありの関数</p>";
      return $str;
    }
  ?>
</body>
</html>