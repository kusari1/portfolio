<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    $num = array(); 
    for($i = 0; $i < 5; $i++):
      $num[$i] = rand(1,100);
    endfor;

    // print $num[0];
  ?>
</body>
</html>