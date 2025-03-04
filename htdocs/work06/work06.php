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
        print '<p>$score:'.$score.'</p>';
        if ($score % 3 == 0 && $score % 6 == 0){
            print'<p>3と6の倍数です</p>';
        } else if ($score % 3 == 0){
            print'<p>3の倍数で、6の倍数ではありません。</p>';
        } else {
            print'<p>倍数ではありません。</p>';
        }

        $random01 = rand(1,10);
        $random02 = rand(1,10);
        $count = 0;
        print '<p>$score:'.$random01.'</p>';
        print '<p>$score:'.$random02.'</p>';

        print'<p>random01 = '.$random01.',random02 = '.$random02;

        if($random01 > $random02):
            print'random01の方が大きいです。 ';
        elseif($random01 == $random02):
            print'2つの数は同じです。';
        else:
            print'random02の方が大きいです。 ';
        endif;

        // if($random01 % 3 == 0 && $random02 % 3 == 0){
        //     print '2つの数字の中には3の倍数が2つ含まれています';
        // }else if($random01 % 3 == 0 || $random02 % 3 == 0){
        //     print '2つの数字の中には3の倍数が1つ含まれています';
        // }else {
        //     print '2つの数字の中に3の倍数が含まれていません';
        // }

        if($random01 % 3 == 0 && $random02 % 3 == 0):
            print '2つの数字の中には3の倍数が2つ含まれています';
        elseif($random01 % 3 == 0 || $random02 % 3 == 0):
            print '2つの数字の中には3の倍数が1つ含まれています';
        else:
            print '2つの数字の中に3の倍数が含まれていません';
        endif;
    ?>
</body>
</html>