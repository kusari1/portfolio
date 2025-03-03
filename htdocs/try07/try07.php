<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
    <?php 
      $num = 10;
      $str = 'str';

      print var_dump($num == $str);
      print var_dump($num === $str);
    
    ?>
</body>
</html>