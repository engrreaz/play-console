<?php
require 'functions-fcm.php';
define('API_ACCESS_KEY', 'AAAAiSanis8:APA91bGHIRxAjn8YBaf562fukaYy9N9_8LiNIm5XcTZnHEPqIK7Nr38PQhMJrWTpt9g0VI6U9DMvRT58K-D8AwHwwBvG3YqK8hKbxTMNu9qjaAm6KGj09FGyYT3RVUwExfs4IWXSfucp'); // Replace YOUR FIREBASE CLOUD MESSAGING API KEY with your Firebase Cloud Messaging server Key
// $token = 'd9bEq1MSSBOkO-DtXB5cEJ:APA91bEmc_dZnL3mkaHh37aPXLrwaKRTIMSSynseNMWChXf_WuZk8b5Ns5WT39tkJz5DlpYJK5kpohN8yvGEHK8GnPCfzWespwD8CWjfWMXk4DydCWAxc4Y';
$token = 'fJCTf1zdTc6WiaAbJ8PYB2:APA91bEgZYqfDo2-7N1ylHXHXA003Bws1tk3T47nF-UGuLuW2e0bj4i8nJV8cKevdyd_9KZ-XdQj78C42VfeK2iHO6_MNgcg_9xQhJ-72RvrzJjhHq46Whg';


$ttm = date("H:i:s");

$accessToken = getAccessToken();


$projectId = 'eimbox-9014d';

$url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

$data = [
    "message" => [
        "token" => $token,
        "notification" => [
            "title" => "Hello",
            "body" => "Test Message",
            "image" => "https://eimbox.com/images/fav.png"
        ],
        "data" => [
            "title" => "EIMBox আপডেট",
            "body" => "আপনার প্রোফাইল চেক করুন",
            "image" => "https://eimbox.com/images/fav.png",
            "data1" => $ttm
        ]
    ]
];

$headers = [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

curl_close($ch);

echo $response;

echo 'NO';