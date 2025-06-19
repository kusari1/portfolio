<?php 
  //DB接続を行いPDOインスタンスを返す
  //@return object $pdo

  // get_connection()は、PDOを用いてデータベースに接続する関数です。
  function get_connection(){
    try{
      //PDOインスタンスの生成
      $pdo = new PDO('mysql:host=localhost;dbname=databasename','login_user','password');
    } catch(PDOException $e){
      echo $e->getMessage();
      exit();
    }

    return $pdo;
  };

  //SQL文を実行　結果を配列で取得する
  //@param object $pdo
  //@param string $sql 実行されるSQL文章
  //@terun array 結果セットの配列

  // get_sql_result()は、引数として渡されたPDOオブジェクト
  // （DBを制御するとっかかりとして「DBハンドル」と呼ばれることもあります）とSQL文を使用して、結果を返します。

  function get_sql_result($pdo,$sql){
    $data = [];
    if($result = $pdo->query($sql)){
      if($result->rowCount() > 0){
        while($row = $result->fetch()){
          $data[] = $row;
        }
      }
    }
    return $data;
  }

  //全商品の商品名データ取得
  //@param obejct
  //@return array
  // get_product_list()は、引数として渡されたPDOオブジェクトを使用して、
  // productテーブルから全商品のproduct_nameをSELECTした結果セットを返します。

  function get_product_list($pdo){
    $sql = 'SELECT prodcut_name, price FROM product';
    return get_sql_result($pdo,$sql);
  }

  //htmlspqcialchars(特殊文字の変換)のラッパー関数
  //@params string
  //@return string
  // h()は htmlspecialchars()のラッパー関数です。

// 「ラップ（Wrap）」とは「包む」という意味ですが、多く作られる関数を関数で包むことにより、引数指定の繰り返しを避けたり、仕様変更に耐えうる設計にすることができます。

  function h($str){
    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
  }

  //特殊文字の変換(二次元配列対応)
  //@param array 
  //@return array
  // h_array()は、h()を二次元配列で使用するための関数です。使用頻度が高かったり、使いまわしたりできたりする場合には、このように自分で定義した関数を呼び出す処理を更に関数化するやり方を覚えておきましょう。

  function h_array($array){
    //二次元配列でforeachでループさせる
    foreach($array as $keys => $values){
      foreach($values as $key => $value){
        //ここの値にh関数を仕様して置き換える
        $array[$keys][$key] = h($value);
      }
    }
    return $array;
  }