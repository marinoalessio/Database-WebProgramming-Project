<?php 

include 'auth.php';
if (!$userid = checkAuth()) exit;

header('Content-Type: application/json');
$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$request = mysqli_real_escape_string($conn, $_GET["request"]);
$cf = mysqli_real_escape_string($conn, $_GET["id"]);

if($request == 'unfollow'){
    $query = "DELETE FROM SUBSCRIPTION WHERE user_id = $userid AND cf_director = '$cf'";
}else{
    $query = "INSERT INTO SUBSCRIPTION(user_id, cf_director) VALUES ($userid, '$cf')";
}
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

if($res){
    echo json_encode(array('ok' => true));
}else{
    echo json_encode(array('ok' => false));
}

mysqli_close($conn);
exit;
?>