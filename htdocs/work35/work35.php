<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>work35</title>
</head>
<body>
    <?php 
      // 乱数生成（1〜10）
      $random = rand(1, 10);

      // 関数の呼び出し（修正後）
      random_multiply($random);

      // 関数定義（修正後）
      function random_multiply($randomnumber) {
          if ($randomnumber % 2 == 0) {
              $randomnumber *= 10; // 10を掛ける
              echo "引数は偶数でした。10を掛けます: " . $randomnumber;
          } else {
              $randomnumber *= 100; // 100を掛ける
              echo "引数は奇数でした。100を掛けます: " . $randomnumber;
          }
      }
    ?>
</body>
</html>
