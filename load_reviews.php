<?php

include 'auth.php';
if (!$userid = checkAuth()) exit;

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$limit = mysqli_real_escape_string($conn, $_GET["limit"]);

if(isset($_GET["limit"])){
    $limit = mysqli_real_escape_string($conn, $_GET["limit"]);
}else{
    $limit = 100;
}

$query = "SELECT a.title, a.artists, a.img, a.publication_year, a.place_of_origin, a.description, a.category, 
u.name, u.surname, u.avatar, u.username, r.user_id, r.artwork_id, r.stars, r.body, r.n_likes, r.publication_date FROM REVIEWS AS r 
LEFT JOIN ARTWORKS AS a ON r.artwork_id = a.id JOIN USERS AS u ON u.id = r.user_id ";

if(isset($_GET["user"])){
    $query .= "WHERE user_id = $userid ";
}else{
    $query .= "WHERE user_id <> $userid ";
}

$query .= "ORDER BY r.publication_date DESC ";

$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

if (mysqli_num_rows($res) > 0) {
    while($entry = mysqli_fetch_assoc($res)) {
        $time = getTime($entry['publication_date']);

        $user_likes = $entry['user_id'];
        $artwork_likes = $entry['artwork_id'];
        $query_likes = "SELECT * FROM LIKES WHERE user_review = $user_likes AND artwork_review = $artwork_likes AND user_id = $userid";
        $res_likes = mysqli_query($conn, $query_likes) or die(mysqli_error($conn));
        if (mysqli_num_rows($res_likes) > 0) $is_liked = true;
        else $is_liked = false;

        $return[] = array('user_review' => $user_likes, 'artwork_review' => $artwork_likes, 'title' =>  $entry['title'], 
        'artists' =>  $entry['artists'], 'img' =>  $entry['img'], 'publication_year' =>  $entry['publication_year'], 
        'place_of_origin' =>  $entry['place_of_origin'], 'description' =>  $entry['description'], 
        'category' =>  $entry['category'], 'name' =>  $entry['name'], 'surname' =>  $entry['surname'],
        'avatar' =>  $entry['avatar'], 'username' =>  $entry['username'], 'stars' =>  $entry['stars'],
        'body' =>  $entry['body'], 'likes' =>  $entry['n_likes'], 'publication_date' => "$time", 'is_liked' => $is_liked);
        
        if(count($return) >= $limit) break;
    }
}

function getTime($timestamp) {             
    $input_time = strtotime($timestamp);    //transform to integer(seconds)
    $diff = time() - $input_time;           
    $input_time = date("F j, Y, g:i a", $input_time);

    if ($diff /60 < 1) {
        return intval($diff%60)." secondi fa";
    } else if (intval($diff/60) == 1)  {
        return "Un minuto fa";  
    } else if ($diff / 60 < 60) {
        return intval($diff/60)." minuti fa";
    } else if (intval($diff / 3600) == 1) {
        return "Un'ora fa";
    } else if ($diff / 3600 < 24) {
        return intval($diff/3600) . " ore fa";
    } else if (intval($diff/86400) == 1) {
        return "Ieri";
    } else if ($diff/86400 < 30) {
        return intval($diff/86400) . " giorni fa";
    } else {
        return $input_time; 
    }
}

echo json_encode($return);
mysqli_close($conn);
exit;
?>