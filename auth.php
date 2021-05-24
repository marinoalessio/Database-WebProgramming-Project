<?php
    require_once 'dbconfig.php';
    session_start();

    function checkAuth() {
        GLOBAL $dbconfig;

        if(!isset($_SESSION['_user_id'])) { // if session does not exists
            
            if(isset($_COOKIE['_user_id']) && isset($_COOKIE['_token']) && isset($_COOKIE['_cookie_id'])){
                $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

                $cookieid = mysqli_real_escape_string($conn, $_COOKIE['_cookie_id']);
                $userid = mysqli_real_escape_string($conn, $_COOKIE['_user_id']);

                $res = mysqli_query($conn, "SELECT * FROM cookies WHERE id = $cookieid AND user = $userid");
                if($cookie = mysqli_fetch_assoc($res)){
                    if(time() > $cookie['expires']){    //if cookie expired
                        mysqli_query($conn, "DELETE FROM cookies WHERE id = ".$cookie['id']) or die(mysqli_error($conn));
                        header("Location: logout.php");
                        exit;
                    } else if (password_verify($_COOKIE['_token'], $cookie['hash'])){
                        $_SESSION['_user_id'] = $_COOKIE['_user_id'];
                        mysqli_close($conn);
                        return $_SESSION['_user_id'];
                    }
                }
            }else return 0; // worst case

        }else return $_SESSION['_user_id'];
    }
?>
