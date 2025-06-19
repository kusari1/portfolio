<?php 
  $dsn = 'mysql:host=localhost;dbname=xb513874_t8tcu';
  $login_user = 'xb513874_vfrg6';
  $password = '7mumpav176';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php 
    try{
      //データベースへ接続
      $db = new PDO($dsn,$login_user,$password);
      //PDOのエラーに字にPDOExceptionが発生するように設定
      $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

      $db->beginTransaction(); //トランザクション開始

      // UPDATE文の実行
      // 変数$sqlに実行するSQL文が書かれた文字列を代入します。
      // その後、queryメソッドにより変数$sqlに代入されたSQL文の実行を行い、PDOStatementオブジェクトを変数$resultに代入します。
      $sql = "UPDATE product SET price = 150 WHERE product_id=1";
      $result = $db->query($sql);
      $row = $result->rowCount();
      echo $row.'件更新しました。';
      $db->commit(); //正常に終了したらコミット
    } catch(PDOException $e){
      echo $e->getMessage();
      $db->rollBack(); //エラーが起きたらロールバック
    }
    //ここではデータベースの接続を行い、更新するまでの処理が記述されています。
  ?>
</body>
</html>