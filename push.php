<?php
require 'functions-fcm.php';
define('API_ACCESS_KEY', 'AAAAiSanis8:APA91bGHIRxAjn8YBaf562fukaYy9N9_8LiNIm5XcTZnHEPqIK7Nr38PQhMJrWTpt9g0VI6U9DMvRT58K-D8AwHwwBvG3YqK8hKbxTMNu9qjaAm6KGj09FGyYT3RVUwExfs4IWXSfucp'); // Replace YOUR FIREBASE CLOUD MESSAGING API KEY with your Firebase Cloud Messaging server Key
$token = 'd9bEq1MSSBOkO-DtXB5cEJ:APA91bEmc_dZnL3mkaHh37aPXLrwaKRTIMSSynseNMWChXf_WuZk8b5Ns5WT39tkJz5DlpYJK5kpohN8yvGEHK8GnPCfzWespwD8CWjfWMXk4DydCWAxc4Y';




$accessToken = getAccessToken();
var_dump($accessToken);
exit;

$projectId = 'eimbox-9014d';

$url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

$data = [
    "message" => [
        "token" => $token,
        "notification" => [
            "title" => "Hello",
            "body" => "Test Message"
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