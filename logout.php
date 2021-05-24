<?php
    include 'dbconfig.php';

    session_start();
    session_destroy();

    if(isset($_COOKIE['_user_id']) && isset($_COOKIE['_token']) && isset($_COOKIE['_cookie_id'])){
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
        $cookieid = mysqli_real_escape_string($conn, $_COOKIE['_cookie_id']);
        $userid = mysqli_real_escape_string($conn, $_COOKIE['_user_id']);

        $res = mysqli_query($conn, "SELECT id, hash FROM cookies WHERE id = $cookieid AND user = $userid");
        if($cookie = mysqli_fetch_assoc($res)){
            if(password_verify($_COOKIE['_token'], $cookie['hash'])){
                mysqli_query($conn, "DELETE FROM cookies WHERE id = $cookieid");
                setcookie('_user_id', '');
                setcookie('_cookie_id', '');
                setcookie('_token', '');
            }
        }
        mysqli_close($conn);
    }

    header('Location: login.php');
?>