<?php

define('API_ACCESS_KEY', 'AAAAiSanis8:APA91bGHIRxAjn8YBaf562fukaYy9N9_8LiNIm5XcTZnHEPqIK7Nr38PQhMJrWTpt9g0VI6U9DMvRT58K-D8AwHwwBvG3YqK8hKbxTMNu9qjaAm6KGj09FGyYT3RVUwExfs4IWXSfucp'); // Replace YOUR FIREBASE CLOUD MESSAGING API KEY with your Firebase Cloud Messaging server Key
$token = 'd9bEq1MSSBOkO-DtXB5cEJ:APA91bEmc_dZnL3mkaHh37aPXLrwaKRTIMSSynseNMWChXf_WuZk8b5Ns5WT39tkJz5DlpYJK5kpohN8yvGEHK8GnPCfzWespwD8CWjfWMXk4DydCWAxc4Y';


$data = [
     "to" => $token,
     "notification" => [
          "title" => "Hello Test",
          "body" => "Hello message",
          "icon" => "https://example.com/icon.png",
          "click_action" => "index.php"
     ]
];

$data_string = json_encode($data);

$headers = [
     'Authorization: key=' . API_ACCESS_KEY,
     'Content-Type: application/json'
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

$result = curl_exec($ch);

if (curl_errno($ch)) {
     echo curl_error($ch);
}

curl_close($ch);

echo "<pre>";
print_r($result);