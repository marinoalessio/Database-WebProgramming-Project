<?php
    include 'auth.php';
    if (!$userid = checkAuth()) exit;

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $artwork_id = mysqli_real_escape_string($conn, $_POST["artwork_id"]);
    $img = mysqli_real_escape_string($conn, $_POST["img"]);
    $stars = mysqli_real_escape_string($conn, $_POST["stars"]);
    $comment = mysqli_real_escape_string($conn, $_POST["comment"]);

    $query = "SELECT * FROM artworks WHERE id = $artwork_id";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if (mysqli_num_rows($res) == 0) {   //fetch artwork if not already done
        
        $url = 'https://api.artic.edu/api/v1/artworks/' . $artwork_id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $json = json_decode($data, true);
        curl_close($ch);

        $title = mysqli_real_escape_string($conn, $json['data']['title']);
        $artists = mysqli_real_escape_string($conn, $json['data']['artist_display']);
        $publication_year = mysqli_real_escape_string($conn, $json['data']['date_end']);
        $place_of_origin = mysqli_real_escape_string($conn, $json['data']['place_of_origin']);
        $description = mysqli_real_escape_string($conn, $json['data']['thumbnail']['alt_text']);
        $category = mysqli_real_escape_string($conn, $json['data']['department_title']);

        $query_in = "INSERT INTO artworks(id, title, artists, img, publication_year, place_of_origin, description, category) 
        VALUES($artwork_id, '$title', '$artists', '$img', $publication_year, '$place_of_origin', '$description', '$category')";
        mysqli_query($conn, $query_in) or die(mysqli_error($conn));
    }

    $query = "SELECT * FROM reviews WHERE user_id = $userid AND artwork_id = $artwork_id";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if (mysqli_num_rows($res) === 0){    //if no match execute query

        $query_review = "INSERT INTO reviews(user_id, artwork_id, stars, body) values($userid, $artwork_id, $stars, '$comment')";
        $res_review = mysqli_query($conn, $query_review) or die(mysqli_error($conn));
        echo json_encode(array('ok' => true));

    }else{
        echo json_encode(array('ok' => false));
    }

    mysqli_close($conn);
    exit;
    
?>