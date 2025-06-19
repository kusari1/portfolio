<?php 
  $dsn = 'mysql:host=localhost;dbname=xb513874_t8tcu';
  $login_user = 'xb513874_vfrg6';
  $password = '7mumpav176';
?>
<!DOCTYPE html>
<html lang="en">
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
      //PDOのエラー時にPDOExcptionが発生する
      $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

      $db->beginTransaction();

      // クエリを作成する
      $sql = "UPDATE product SET price = ? WHERE pruduct_id = ?";

      //preparaメソッドによりクエリの実行準備
      $stmt = $db -> prepare($sql);

      //値をバインドする
      // バインドする部分を「?」で指定し、
      // バインドする際には指定した左から順番に1, 2…として扱います。
      $stmt -> bindValue(1,170);
      $stmt -> bindValue(2,'1');

      //クエリの実行
      $stmt->execute();
      $row = $stmt->rowCount();
      echo $row.'件更新しました。';
      $db->commit(); //正常に終了
    } catch(PDOException $e){
      echo $e->getMessage();
      $db->rollBack(); //エラーが発生したらロールバック
    }
  ?>
</body>
</html>l