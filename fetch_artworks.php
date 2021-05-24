<?php

require_once 'auth.php';
if (!checkAuth()) exit;
header('Content-Type: application/json');

    $fields = 'id,title,image_id';
    $image_endpoint = 'https://www.artic.edu/iiif/2/';
    
    $query = urlencode($_GET["q"]);
    $url = 'https://api.artic.edu/api/v1/artworks/search?q=' . $query . '&limit=8&fields=' . $fields;
    
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return the value instead of a boolean
    
    $data = curl_exec($ch); //request
    $json = json_decode($data, true);
    curl_close($ch);

    $newJson = array();
    for ($i = 0; $i < count($json['data']); $i++) {
        $newJson[] = array('id' => $json['data'][$i]['id'], 'title' => $json['data'][$i]['title'], 
        'image_id' => $image_endpoint . $json['data'][$i]['image_id'] . '/full/843,/0/default.jpg');
    }
    
    echo json_encode($newJson);
     
?>