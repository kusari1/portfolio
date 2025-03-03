<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        $fruit01 = "リンゴ";
        $fruit02 = "バナナ";

        if($fruit01 == "りんご" && $fruit02 == "バナナ"){
            echo "<p>fruit01はリンゴで、かつfruit02はバナナです。";
        }
        if($fruit01 == "りんご" || $fruit02 == "バナナ"){
            echo "<p>fruit01がリンゴで、あるいはfruit02がバナナです。";
        }

        if(!($fruit02 == "リンゴ")){
            echo "<p>fruit02はリンゴではありません。";
        }
    ?>
</body>
</html>