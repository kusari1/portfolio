<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $i = 1;
    while($i <= 100){
        if($i % 3 == 0 && $i % 4 == 0){
            print 'FizzBuzz';
            $i++;
        }elseif($i % 3 == 0){
            print 'Fizz';
            $i++;
        }elseif($i % 4 == 0){
            print 'Buzz';
            $i++;
        }
    }

    $i = 1;
    $j = 1;
    while($i <= 9){
        while($j <= 9){
            print $i.'*'.$j.'='.$i*$j;
        }
        print '<br>';
    }

    ?>
</body>
</html>