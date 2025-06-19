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
  <title>TRY45</title>
</head>
<body>
  <?php 
    // このデータベースへの接続試行がtry〜catch構文の try ブロック内にあるため、エラーは catch ブロックによって捕捉され、適切なエラーメッセージが出力され、プログラムの処理が停止します（例外処理）。
    try{
      //データベースへ接続
      // 上記のコードにおいて、まず new PDO($dsn, $login_user, $password); が試行されます。ここではデータベースへの接続を試みています
      $db=new PDO($dsn,$login_user,$password); 
    } catch (PDOException $e){
      echo $e->getMessage();
      exit();
    }
    //SELECT文の実行
    $sql = "SELECT product_name, price FROM product WHERE price <= 100";
    // query()メソッドは、与えられたSQL文をデータベースで実行し、結果をPDOStatementオブジェクトとして返します。
    if($result = $db->query($sql)){
      //連想配列を取得
      // 、fetch()メソッドにより取得した結果セットを一行ずつ処理します。各行は連想配列として取得され、その値を出力します。
      while($row = $result->fetch()){
        echo $row["product_name"] . $row["price"] . "<br>";
      }
    }
  ?>
</body>
</html>