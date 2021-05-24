<?php

include 'auth.php';
if (!$userid = checkAuth()) exit;

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$query = "SELECT * FROM DIRECTORS LEFT JOIN SUBSCRIPTION ON DIRECTORS.cf = SUBSCRIPTION.cf_director ORDER BY since DESC";

$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

if (mysqli_num_rows($res) > 0) {
    while($entry = mysqli_fetch_assoc($res)) {
        if($entry['user_id'] == $userid) $is_following = true;
        else $is_following = false;
        $return[] = array('cf' => $entry['cf'], 'name' => $entry['name'], 'surname' => $entry['surname'],
        'qualification' => $entry['qualification'], 'img' => $entry['img'], 'is_following' => $is_following);
    }
}

echo json_encode($return);
mysqli_close($conn);
exit;
?>