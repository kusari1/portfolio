<?php 
    $host = 'localhost';
    $login_user = 'xb513874_vfrg6';
    $password = '7mumpav176';
    $database = 'xb513874_t8tcu';
    $error_msg = [];
    $product_name;
    $price;
    $price_val;
    $product_id = 21;
    $product_name = "エシャロット";
    $price = 200;
    $product_code = 1021;
    $category_id = 1;
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
        //データベースへ接続
        $db = new mysqli($host,$login_user,$password,$database);
        if($db -> connect_error){
            echo $db -> connect_error;
            exit();
        } else {
            $db->set_charset("utf8");
        }

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if (isset($_POST['insert'])){ 
            $db->begin_transaction();//トランザクション開始

            //UPDATE文の実行
            $insert = "INSERT INTO product(product_id,product_code,product_name,price,category_id)VALUES(21, 1021, 'エシャロット', 200, 1);";
            if($result = $db->query($insert)){
                $row = $db->affected_rows;
            } else {
                $error_msg[] = 'UPDATE実行エラー[実行SQK]'.$insert;
            }
            //$erroe_msg[] = 強制的にエラーメッセージを挿入

            //エラーメッセージ格納の有無によりトランザクションの成否を判定
            if(count($error_msg) == 0){
                echo $row.'件更新しました。';
                $db->commit(); //正常に終了したらコミット
            } else {
                echo '更新が失敗しました。';
                $db->rollback(); //エラーが起きたらロールバック
            }
               // 下記はエラー確認用。エラー確認が必要な際にはコメントを外してください。
            // var_dump($error_msg); 
            }

            //deleteボタン
            if(isset($_POST['delete'])){
                $db->begin_transaction();//トランザクション開始
            //delete文実行
                $delete = "DELETE FROM product WHERE product_id = 21";
                if($result = $db->query($delete)){
                    $row = $db->affected_rows;
                } else {
                    $error_msg[] = 'UPDATE実行エラー[実行SQK]'.$delete;
                }
                //$erroe_msg[] = 強制的にエラーメッセージを挿入
                if(count($error_msg) == 0){
                    echo $row.'件削除しました。';
                    $db->commit(); //正常に終了したらコミット
                } else {
                    echo '更新が失敗しました。';
                    $db->rollback(); //エラーが起きたらロールバック
                }
                   // 下記はエラー確認用。エラー確認が必要な際にはコメントを外してください。
                // var_dump($error_msg); 
            }
        }

        // $select = "SELECT product_name, price FROM product WHERE product_id = 1";
        // if($result = $db->query($select)){
        //     //連想配列を取得
        //     while ($row = $result->fetch_assoc()){
        //         $product_name = $row["product_name"];
        //         $price = $row["price"];
        //     }
        //     //結果セットを閉じる
        //     $result -> close();
        // }
        // if($price == 150){
        //     $price_val = 130;
        // } else {
        //     $price_val = 150;
        // }

        $db -> close(); //接続を閉じる

    ?>
    
    <form method="post">
        <p><?php echo $product_name?>を挿入する。</p>
        <input type="submit" name = "insert" value="挿入">
        <p><?php echo $product_name ?>を削除する</p>
        <input type="submit" name = "delete" value="削除">
    </form>
</body>
</html>