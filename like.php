<?php 

include 'auth.php';
if (!$userid = checkAuth()) exit;

header('Content-Type: application/json');
$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$user_review = mysqli_real_escape_string($conn, $_GET["user_review"]);
$artwork_review = mysqli_real_escape_string($conn, $_GET["artwork_review"]);

$query = "INSERT INTO LIKES(user_review, artwork_review, user_id) values ($user_review, $artwork_review, $userid)";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

$query_review = "SELECT n_likes FROM reviews WHERE user_id = $user_review AND artwork_id = $artwork_review";
$res = mysqli_query($conn, $query_review) or die(mysqli_error($conn));
$entry = mysqli_fetch_assoc($res);

echo json_encode(array('n_likes' => $entry['n_likes']));

mysqli_close($conn);
exit;
?>