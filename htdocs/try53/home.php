<?php
//Cookieの保存期間
define('EXPIRATION_PERIOD', 30);
$cookie_expiration = time() + EXPIRATION_PERIOD * 60 * 24 * 365;

//POSTされたフォームの値を変数に格納する
if (isset($_POST['cookie_confirmation']) === TRUE) {
    $cookie_confirmation = $_POST['cookie_confirmation'];
} else {
    $cookie_confirmation = '';
}
if (isset($_POST['login_id']) === TRUE) {
    $login_id = $_POST['login_id'];
} else {
    $login_id = '';
}

// ログインIDの入力省略にチェックがされている場合はCookieを保存
if ($cookie_confirmation === 'checked') {
    setcookie('cookie_confirmation', $cookie_confirmation, $cookie_expiration);
    setcookie('login_id', $login_id, $cookie_expiration);
} else {
    // チェックされていない場合はCookieを削除する
    // ログインIDの入力省略にチェックがなされている場合、Cookieとしてフォームの内容が格納されます。チェックをされていない場合は削除されます。
// Cookieは、保存期限を現在の時間よりも過去に設定をすることによって削除されます。
    setcookie('cookie_confirmation', '', time() - 30);
    setcookie('login_id', '', time() - 30);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <title>TRY53</title>
</head>
<body>
   <p>ログイン（擬似的）が完了しました</p>
</body>
</html>