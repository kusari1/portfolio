<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        $random = rand(0,4);
        print '<p>$random: '.$random.'</p>';

        switch($random){
            case 1:
                print '<p>変数の値は1です。';
                break;
            case 2:
                print'<p>変数$randomの値は2です。';
                break;
            default:
                print'変数$randomの値は1,2ではないです。';
        }
    ?>
</body>
</html>