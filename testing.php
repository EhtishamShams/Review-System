<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 24/08/2018
 * Time: 4:21 AM
 */


require __DIR__.'/vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_url' => 'http://localhost:8000',
    'defaults' => [
        'exceptions' => false,
        'verify' => false
    ]
]);


$user = [
    "grant_type" => "password",
    "client_id" =>  "8_6jarg69laeoswc84gw4k0kc0oc08w40wsw0c8swskkc4cggw8",
    "client_secret" => "4fajtperzack4gscg8w8oo80k04o44008wcs4k0wws8g0cg0s0",
    "username" => "test4",
    "password" => "test"
];


//$resp = $client->post('/oauth/v2/token', [
//    'body' => json_encode($user),
////    'body' => 'grant_type=token&client_id=2_4alrpsm5u6ucgcwcosow8sk8cg08gc4kowc4k04g884kkscgko&client_secret=1tqopgcntltwo0c40gggkscc0sgcwk0o0k0ssgok8skkckk4c0&username=test4&password=test',
//    'headers' => ['Content-Type' => 'application/json']
////    'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
//]);

$resp = $client->post('/api/tokens', [
    'body' => json_encode($user),
    'headers' => ['Content-Type' => 'application/json'],
    'auth' => ['test4', 'test']
]);

//echo $resp."\n\n";die;
$resArr = json_decode($resp->getBody(), true);

$token = $resArr['access_token'];

$data = [
    'name' => "Api Product 1",
    'description' => "Aik or chuss Product",
    'price' => 10000
];

$resp = $client->post('/api/products', [
    'body' => json_encode($data),
    'headers' => ['Authorization' => 'Bearer '.$token]
]);

echo $resp."\n\n\n\n";

//$url = $resp->getHeader('Location');

//$resp = $client->get('/api/products/43');

$reviewData = [
    'customerName' => "Api Test Customer",
    'comment' => "API Review",
    'rating' => 5,
];


$resp = $client->post('/api/reviews/44', [
    'body' => json_encode($reviewData)
]);

//$resp = $client->get('/api/reviews/44');

echo $resp."\n\n\n\n";

?>