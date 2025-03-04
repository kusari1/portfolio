<!DOCTYPE  html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>TRY20</title>
    </head>
    <body>
        <div>フォームに入力した内容を取得する</div>
        <?php
            if( isset( $_GET['display_text'] )) {
                print '入力した内容： ' . htmlspecialchars($_GET['display_text'], ENT_QUOTES, 'UTF-8');
            } else {
                print '入力されていません';
            }
        ?>
    </body>
</html>