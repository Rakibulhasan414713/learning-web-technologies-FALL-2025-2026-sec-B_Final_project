<?php
session_start();

if (isset($_POST['content'])) {

    // Reuse existing login/DB connection script
    include("userlogincheck.php"); // defines $link and $con

    if (!isset($_SESSION['ID'])) {
        http_response_code(401);
        echo "not_logged_in";
        exit;
    }

    // Escape input to prevent SQL injection
    $content = mysqli_real_escape_string($link, $_POST['content']);
    $id      = mysqli_real_escape_string($link, $_SESSION['ID']);

    $query = "UPDATE `user` SET `messages` = '$content' WHERE `ID` = '$id' LIMIT 1";

    if (mysqli_query($link, $query)) {
        echo "success";
    } else {
        echo "fail";
    }
}
?>
