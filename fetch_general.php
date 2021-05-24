<?php

include 'auth.php';
if (!checkAuth()) {
    exit;
}

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$from = mysqli_real_escape_string($conn, $_GET["from"]);

$query = "SELECT * FROM $from";

$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

if (mysqli_num_rows($res) > 0) {
    while($entry = mysqli_fetch_assoc($res)) {
        $return[] = array('cf' => $entry['cf'], 'name' => $entry['name'], 'surname' => $entry['surname'],
        'qualification' => $entry['qualification']);
    }
}
echo json_encode($return);

?>