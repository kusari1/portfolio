<?php 
  $checks = '';
  if (isset($_POST['checkbox'])){
    $checks = htmlspecialchars(());
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>work17</title>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
    <div>名前入力</div>
    <form method = "post" action ="work16_02.php">
      <input type="text" name="name">
    <div>選択肢をチェック</div>
      <input type="checkbox" name="checkbox" value="1">
      <label>選択肢1</label>
      <input type="checkbox" name="checkbox2" value="2">
      <label>選択肢2</label>
      <input type="checkbox" name="checkbox3" value="3">
      <label>選択肢3</label>
    <input type="submit" value="送信">
    </form>
</body>
</html>
</body>
</html>