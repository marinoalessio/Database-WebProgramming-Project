<?php

include 'auth.php';
if (!$userid = checkAuth()) exit;

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$is_checked = filter_var($_GET["is_checked"], FILTER_VALIDATE_BOOLEAN);
$limit = mysqli_real_escape_string($conn, $_GET["limit"]);
if($limit == ""){$limit = 100;};

$query = "SELECT e.id, e.title, e.date_and_time, e.duration, e.cover FROM events AS e";

if($is_checked){
    $query .= " JOIN directors AS d ON e.director = d.cf JOIN subscription AS s ON d.cf = s.cf_director WHERE s.user_id = $userid";
}

$query .= " ORDER BY e.date_and_time ASC";

$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

if (mysqli_num_rows($res) > 0) {
    while($entry = mysqli_fetch_assoc($res)) {

        if(time() > strtotime($entry['date_and_time'])) continue;

        $event = $entry['id'];
        $query_check = "SELECT * FROM highlight WHERE id_event = $event AND id_user = $userid";
        $res_check = mysqli_query($conn, $query_check) or die(mysqli_error($conn));
        if (mysqli_num_rows($res_check) > 0) $highlighted = true;
        else $highlighted = false;

        $return[] = array('id' => $event, 'title' => $entry['title'], 'date_and_time' => $entry['date_and_time'],
        'duration' => $entry['duration'], 'cover' => $entry['cover'], 'highlighted' => $highlighted);
    }
}else $return = 0;

echo json_encode($return);
mysqli_close($conn);
exit;

?>