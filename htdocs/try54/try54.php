<?php 
  // session_start()関数によりセッションを開始します。これは、セッションを開始するための関数です。
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TRY54</title>
</head>
<body>
    <?php 
      // var_dump()関数によりスーパーグローバル変数$_SESSIONに保存されているデータを表示します。このvar_dump()の実行結果が下記となります。
      // 二回目以降のアクセスでは、セッションが続いているため前回に保持された値が表示されます。
      var_dump($_SESSION);
      echo "<br>";

      // スーパーグローバル変数$_SESSIONに値の保存を行い、var_dump()関数によりスーパーグローバル変数$_SESSIONに保存されているデータを表示します。
      // このvar_dump()の実行結果が下記となります。二回目では既にyearの値が先に格納されていたため、順番的にyearが先に来ています。
      $_SESSION['id'] = 1;
      $_SESSION['username'] = 'login_user';
      $_SESSION['year'] = date("Y");
      var_dump($_SESSION);
      echo "<br>";

      // unset()関数により$_SESSION[‘username’]を削除し、
      // 「var_dump()」関数によりスーパーグローバル変数$_SESSIONに保存されているデータを表示します
      unset($_SESSION['username']);
      var_dump($_SESSION);
      echo "<br>";
    ?>
</body>
</html>