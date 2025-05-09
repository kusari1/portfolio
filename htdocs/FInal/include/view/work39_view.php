<?php

var_dump($images);
function displayImages($images) {
    echo "<h2>アップロード済みの画像一覧</h2>";
    echo "<div class='image-container'>";
    foreach ($images as $row) {
        $imageId = $row["image_id"];
        $imageName = $row["image_name"];
        $publicFlg = $row["public_flg"];
        $statusClass = $publicFlg ? "public" : "private";
        $statusText = $publicFlg ? "公開中" : "非公開";
        $toggleText = $publicFlg ? "非公開にする" : "公開にする";
        $toggleClass = $publicFlg ? "toggle-private" : "toggle-public";

        echo "<div class='image-item'>";
        echo "<img src='uploads/$imageName' alt='$imageName' style='width:100%;'>";
        echo "<p>$imageName</p>";
        echo "<p class='$statusClass'>$statusText</p>";
        echo "<form action='/nakano/0005/work39/work39.php' method='post'>";
        echo "<input type='hidden' name='toggle_id' value='$imageId'>";
        echo "<input type='hidden' name='current_status' value='$publicFlg'>";
        echo "<button type='submit' name='toggle' class='toggle-btn $toggleClass'>$toggleText</button>";
        echo "</form>";
        echo "</div>";
    }
    echo "</div>";
}
?>
