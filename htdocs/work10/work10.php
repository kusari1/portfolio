<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        $number01 = 1;

        for($i = 1; $i <= 100; $i++){
            if($i % 3 == 0 && $i % 4 == 0){
                echo 'FizzBuzz';
            }elseif($i % 3 == 0){
                echo 'Fizz';
            }elseif($i % 4 == 0){
                echo 'Buzz';
            }else{
                echo $i;
            }
        }
        echo '<br>';


        for($i = 1; $i <= 9; $i++){
            for($j = 1; $j <= 9; $j++){
                echo $i.'*'.$j.'='.$i*$j;
            }
            echo '<br>';
        }


        $count = 1;
        for($i =1; $i <= 100; $i++){
            if($i % 2 == 0){
                print'!';
                print'<br>';
            }else{
                for($j=1; $j <= $count; $j++){
                    print'*';
                }
                $count++;
                print'<br>';
            }    
        }

    ?>
</body>
</html>