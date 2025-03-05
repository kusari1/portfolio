<?php 
  $checks = '';
  if (isset($_POST['checkbox'])){
    $checks = htmlspecialchars($_POST['checkbox'],ENT_QUOTES,'UTF-8');
  }

  $name = '';
  if (isset($_POST['name'])){
    $name = htmlspecialchars($_POST['name'],ENT_QUOTES,'UTF-8');
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
    <form method = "post">
      <input type="text" name="name">
    <div>選択肢をチェック</div>
      <input type="checkbox" name="checkbox" value="1">
      <label>選択肢1</label>
      <input type="checkbox" name="checkbox" value="2">
      <label>選択肢2</label>
      <input type="checkbox" name="checkbox" value="3">
      <label>選択肢3</label>
    <input type="submit" value="送信">
    </form>
    <!-- ここではリクエストメソッドがPOSTかどうかを判定しています。 -->
<!-- 従って、初期表示の際はif文の中は読み込まれません。 -->
    <?php if($_SERVER["REQUEST_METHOD"] == "POST"): ?>
      <div>名前は：<?php echo $name; ?></div>
      <div>選んだ選択肢は「<?php echo $checks; ?>」です。</div>
    <?php endif; ?>

</body>
</html>
</body>
</html>