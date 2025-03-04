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
    $j = 0;
    for($i = 0; $i < 5; $i++):
      $j = rand(1,100);
      array_push($num,$j);
    endfor;

    // print $num[0];
    print_r($num);
  ?>
</body>
</html>