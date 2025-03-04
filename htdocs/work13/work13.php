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
    while($i <= 100):
        if($i % 3 == 0 && $i % 4 == 0):
            print'FizzBuzz';
            $i++;
        elseif($i % 3 == 0):
            print'Fizz';
            $i++;
        elseif($i % 4 == 0):
            print'Buzz';
            $i++;
        else:
            print$i;
            $i++;
        endif;
    endwhile;

    $i = 1;
    $j = 1;
    while($i <= 9):
        while($j <= 9):
            print $i.'*'.$j.'='.$i*$j;
            $j++;
        endwhile;
        print'<br>';
        $i++;
    endwhile;

    $i = 1;
    $j = 1;
    $key = 0;

    while($i <= 100):
        if($i % 2 == 0):
            $key++;
            for($j=1;$j <= $key;$j++):
            print('*');
            endfor;
            $i++;
        else:
            print('!');
            $i++;
        endif;
    endwhile;
    ?>
</body>
</html>