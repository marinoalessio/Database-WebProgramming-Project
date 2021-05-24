<?php 

include 'auth.php';
if (!$userid = checkAuth()) exit;

header('Content-Type: application/json');
$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);


$request = mysqli_real_escape_string($conn, $_GET["request"]);
$id_event = mysqli_real_escape_string($conn, $_GET["id"]);

if($request == 'add'){
    $query = "INSERT INTO highlight(id_event, id_user) values ($id_event, $userid)";
}

if($request == 'remove'){
    $query = "DELETE FROM highlight WHERE id_event = $id_event AND id_user = $userid";
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