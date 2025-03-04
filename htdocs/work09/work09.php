<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        $score = rand(1,100);
        ?>

        <p>$score:<?php echo $score ?></p>

        <?php
        if ($score % 3 == 0 && $score % 6 == 0){
            print'<p>3と6の倍数です</p>';
        } else if ($score % 3 == 0){
            print'<p>3の倍数で、6の倍数ではありません。</p>';
        } else {
            print'<p>倍数ではありません。</p>';
        }
        ?>

        <?php
        $random01 = rand(1,10);
        $random02 = rand(1,10);
        $count = 0;
        ?>
        <p>$score:<?php echo $random01 ?></p>
        <p>$score:<?php echo $random02 ?></p>

        <p>random01 = <?php echo $random01 ?>,random02 = <?php echo $random02 ?></p>

        <?php
        if($random01 > $random02){
            print'random01の方が大きいです。 ';
        }else if ($random01 == $random02){
            print'2つの数は同じです。';
        }else{
            print'random02の方が大きいです。 ';
        }
        ?>

        <?php
        if($random01 % 3 == 0 && $random02 % 3 == 0){
            print '2つの数字の中には3の倍数が2つ含まれています';
        }else if($random01 % 3 == 0 || $random02 % 3 == 0){
            print '2つの数字の中には3の倍数が1つ含まれています';
        }else {
            print '2つの数字の中に3の倍数が含まれていません';
        }
        ?>
</body>
</html>