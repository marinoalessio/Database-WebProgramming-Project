<?php
    include 'auth.php';
    if (!$id = checkAuth()) exit;
    require_once 'dbconfig.php';

    header('Content-Type: application/json');
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $query = "SELECT username, name, surname, avatar, nfollowing, nreviews FROM users WHERE id = '$id'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $entry = mysqli_fetch_assoc($res);

    $returndata = array('id' => $id, 'username' => $entry['username'], 'name' => $entry['name'], 'surname' => $entry['surname'],
        'avatar' => $entry['avatar'], 'nfollowing' => $entry['nfollowing'], 'nreviews' => $entry['nreviews']);
    echo json_encode($returndata);
    

    mysqli_close($conn);
    exit;
?>