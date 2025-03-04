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

      if($school[0][1][1] > $school[1][2][1]){
        print 'odaさんの方が点数が高いです。';
      }else{
        print 'sugawaraさんの方が点数が高いです。';
      }

    $avenum = 0;
    $avenum2 = 0;
    $count  = 0;
    for($i=0;$i < 5; $i++){
      $avenum += $school[0][$i][1]; 
      $avenum2+= $school[1][$i][1];
      $count = $i;
    }

    $avenum = $avenum / $count;
    $avenum2 = $avenum2 / $count;

    print'<p>'.$avenum.'</p>';
    print'<p>'.$avenum2.'</p>';
  ?>

  <pre>
    <?php 
    
    print_r($school); 
    print $school[0][0][0];
    
    ?>
  </pre> 
</body>
</html>