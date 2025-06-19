<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php 
    // $class01 = ['tokugawa','oda','toyotomi','takeda'];
    // $class02 = ['minamoto','taira','sugawara','fujiwara'];

    $class01 [0][0] = 'tokugawa';
    $class01 [0][1] = rand(1,100);
    $class01 [1][0] = 'oda';
    $class01 [1][1] = rand(1,100);
    $class01 [2][0] = 'toyotomi';
    $class01 [2][1] = rand(1,100);
    $class01 [3][0] = 'takeda';
    $class01 [3][1] = rand(1,100);

    $class02 [0][0] = 'minamoto';
    $class02 [0][1] = rand(1,100);
    $class02 [1][0] = 'taira';
    $class02 [1][1] = rand(1,100);
    $class02 [2][0] = 'sugawara';
    $class02 [2][1] = rand(1,100);
    $class02 [3][0] = 'fujiwara';
    $class02 [3][1] = rand(1,100);

    $school = array($class01,$class02);

    print_r($school);
  ?>
</body>
</html>