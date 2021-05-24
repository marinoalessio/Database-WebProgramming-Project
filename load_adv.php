<?php 
require_once 'auth.php';
if (!checkAuth()) exit;

header('Content-Type: application/json');

$client_id = '31c7f0bef211b0956069f6e5f13749279db03717'; 
$client_secret = 'VDeHaCYEg8iD5rtBxNaJ8476p/VR8eA22ZUBgvqZnRGqU5sBnGhc0YHUkWQoiPUHlesdDSF8VkgXSYT4jEg+53AWgoN4rPCumN0d2XyAuGn1mY9INAop7i6YPuW1rGvW'; 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.vimeo.com/oauth/authorize/client' );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_POST, 1);  //post, set header and body
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials'); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret), 
'Content-Type:application/x-www-form-urlencoded', 'Accept:application/vnd.vimeo.*+json;version=3.4')); 

$token=json_decode(curl_exec($ch), true);
curl_close($ch);    

$category = filter_var($_GET["category"], FILTER_VALIDATE_BOOLEAN);

$url = "";

if($category){  //if array is empty fetch the category "art"
    $url = 'https://api.vimeo.com/categories/art/videos';
}else{  // else fetch the tags of event
    $query = urlencode($_GET["query"]);
    $url = 'https://api.vimeo.com/videos?query='.$query;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// setting token
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token'])); 
$data=curl_exec($ch);

$json = json_decode($data, true);
curl_close($ch);

$newJson = array();

for ($i = 0; $i < 3; $i++) {
    $newJson[] = array('title' => $json['data'][$i]['name'], 'embed' => $json['data'][$i]['embed']['html']);
}

echo json_encode($newJson);
   
?>
