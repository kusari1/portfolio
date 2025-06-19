<?php 
  //Model(model.php)を読み込む
  // ここではrequire_onceという記述を行い、model.phpの読み込みを行っています。
// 「require_once」は外部ファイルを読み込むための記述です。「require」のみでも外部ファイルを読み込むことができます。「require_once」と記述することで同じファイルを2回読み込んだ際にエラーを出すことができます。
  require_once 'Model.php';

  // model.phpに記述されている関数を利用し、viewで使用する値の取得を行っています。
  $product_data = [];
  $pdo = get_connection();
  $product_data = get_product_list($pdo);
  $product_data = h_array($product_data);

  // / View(view.php）読み込み
  // include_onceを利用し、view.phpの読み込みを行っています。controller.php上でview.phpを読み込むことで、controller.phpに記述された変数をview.php で使用することができます。
  include_once 'view.php';
?>