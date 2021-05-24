<?php

include 'auth.php';
if (!$userid = checkAuth()) exit;

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$query = "SELECT id, title, date_and_time, duration, cover, tags FROM events 
JOIN highlight ON events.id = highlight.id_event WHERE id_user = $userid ORDER BY since DESC";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));
if (mysqli_num_rows($res) > 0) {
    while($entry = mysqli_fetch_assoc($res)) {

        $return[] = array('id' => $entry['id'], 'title' => $entry['title'], 'date_and_time' => $entry['date_and_time'],
        'duration' => $entry['duration'], 'cover' => $entry['cover'], 'tags' => $entry['tags']);

    }
}else $return = 0;

echo json_encode($return);
mysqli_close($conn);
exit;

?>