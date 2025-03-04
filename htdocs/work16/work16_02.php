<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php 
    if( isset($_GET['name'] ) && $_GET['name'] != ""){
      print '名前：'.$_GET['name'];
    } else {
      print '名前が入力されていません';
    }

    $check = array();
    print '<p>選択された選択肢：';

    if( isset($_GET['checkbox'] ) && $_GET['checkbox'] != ""){
      print $_GET['checkbox'];
    }

    if( isset($_GET['checkbox2'] ) && $_GET['checkbox2'] != ""){
      print $_GET['checkbox2'];
    }

    if( isset($_GET['checkbox3'] ) && $_GET['checkbox3'] != ""){
      print $_GET['checkbox3'].'</p>';
    }

    // print count($_GET['checkbox']);
  ?>
</body>
</html>