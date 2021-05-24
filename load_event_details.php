<?php

include 'auth.php';
if (!$userid = checkAuth()) exit;

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$id = mysqli_real_escape_string($conn, $_GET["id"]);

$query = "SELECT id, title, date_and_time, duration, tags, cover, d.name AS dir_name, d.surname AS dir_surname, 
d.qualification AS dir_qualification, d.img AS dir_img, g.name AS guide_name, g.surname AS guide_surname, 
g.qualification AS guide_qualification, g.img AS guide_img FROM events AS e LEFT JOIN directors AS d 
ON e.director = d.cf LEFT JOIN guides AS g ON e.guide = g.cf WHERE id = $id";

$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

$entry = mysqli_fetch_assoc($res);

$return[] = array('title' => $entry['title'], 'date_and_time' => $entry['date_and_time'],
    'duration' => $entry['duration'], 'cover' => $entry['cover'],
    'dir_name' => $entry['dir_name'], 'dir_surname' => $entry['dir_surname'], 
    'dir_qualification' => $entry['dir_qualification'], 'dir_img' => $entry['dir_img'],
    'guide_name' => $entry['guide_name'], 'guide_surname' => $entry['guide_surname'], 
    'guide_qualification' => $entry['guide_qualification'], 'guide_img' => $entry['guide_img']);

echo json_encode($return[0]);
mysqli_close($conn);
exit;

?>