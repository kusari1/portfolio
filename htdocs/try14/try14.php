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
    ?>
    <p>$random: <?php echo $random?></p>
    <?php switch($random):
        case 1: ?>
            <p>変数$randomの値は1です。</p>
        <?php 
            break; //switc分の処理を終了する。
            case 2:
        ?>
            <p>変数$randomの値は2です。</p>
        <?php break;
            default:
        ?>
            <p>変数$random値は1,2ではりません</p>
        <?php endswitch; ?>    
</body>
</html>