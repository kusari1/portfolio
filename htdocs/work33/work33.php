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
      $db=new PDO($dsn,$login_user,$password);
      //PDOのエラー時にPDOExceptionが発生するように設定
      $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      $db->beginTransaction(); //トランザクション開始
      
      // プリペアドステートメントを使用している部分について解説を行います。
      //クエリを作成する
      $sql = "SELECT * FROM `product` WHERE category_id = :id";

      //prepareメソッドによるクエリの実行準備
      $stmt = $db -> prepare($sql);

      //値をバインドする
      $stmt -> bindValue(':id',1);

      //クエリの実行
      $stmt->execute();
      $row = $stmt->rowCount();
      echo $row.'件更新しました。';
      $db->commit(); //正常に終了したらコミット
    } catch (PDOException $e){ 
      echo $e->getMessage();
      $db->rollBack(); //エラーが起きたらロールバック
    }
  ?>
</body>
</html>