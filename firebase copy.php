<?php
declare(strict_types=1);
include 'inc.php';
$title = 'Uppers - ' . date('l, F, d, Y H:i:s');
$body = 'Offline Background Message';
$icon = 'https://rose.xeneen.com/images/user/engrreaz.png';
$bt = "............................";

$device_token = $token;
echo $device_token . '<br><br>';
$apiKey = 'AIzaSyC1DRjBjvCVwf86SAnJb98b76pdGYcLRn4';
$headers = array('Authorization: key=' . $apiKey, 'Content-Type: application/json');
$url = 'https://fcm.googleapis.com/v1/projects/eimbox-4c743/messages:send';

//----------------------------------------------------------------------------------------------------------------------------------------------------------------


/*
  // FCM API Url
  

  // Put your Server Key here
  //$apiKey = "server-api-key";

  // Compile headers in one variable
  $headers = array (
    'Authorization:key=' . $apiKey,
    'Content-Type:application/json'
  );
*/
// Add notification background
$notifData = [
  'title' => $title . '/2',
  'body' => $body,
  'image' => $icon,
];

$dataPayload = [
  'data1' => 'Sagor Kolas', //-----------------------------on running
  'data2' => 80,
  'data3' => 'This is extra payload',
  'priority' => 'high',
  'content_available' => true
];

// Create the api body
$apiBody = [
  'notification' => $notifData,
  'data' => $dataPayload,
  'time_to_live' => 600,
  'click_action' => 'PendingActivity',
  'to' => $device_token
];


// Initialize curl with the prepared headers and body
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

// Execute call and save result
$result = curl_exec($ch);
// print ($result);
// Close curl after call
curl_close($ch);

echo '<pre>';
print_r($result);
echo '</pre>';


$jd = json_decode($result, true);

echo '<br><br>';
// echo $jd['multicast_id'] . '<br>';
// echo $jd['success'] . '<br>';
// echo $jd['failure'] . '<br>';
// echo $jd['canonical_ids'] . '<br>';
// echo $jd['results'][0]['message_id'] . '<br>';
/* */


$serviceJsonPath = "xeneen-48f5d-firebase-adminsdk-1noh0-fb4d1d9f05.json";
$projectId = "eimbox-4c743";
// ************************************************************
# run.php

# This example uses Google Application Credentials exposed via the
# `GOOGLE_APPLICATION_CREDENTIALS` environment variable
# See https://github.com/googleapis/google-auth-library-php/blob/main/README.md
# for more alternative ways to authenticate requests



require 'vendor/autoload.php';

use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise;

### Create a Guzzle client that authenticates requests to the FCM API Endpoints

putenv('GOOGLE_APPLICATION_CREDENTIALS=xeneen-48f5d-firebase-adminsdk-1noh0-fb4d1d9f05.json');

// https://developers.google.com/identity/protocols/oauth2/scopes#fcm
$scopes = [
    'https://www.googleapis.com/auth/cloud-platform',
    'https://www.googleapis.com/auth/firebase.messaging',
];

// create middleware
$middleware = ApplicationDefaultCredentials::getMiddleware($scopes);
$stack = HandlerStack::create();
$stack->push($middleware);

$client = new Client([
  'handler' => $stack,
  'auth' => 'google_auth'
]);

### Setup the messages

$deviceTokens = [$device_token];
$messages = [];

foreach ($deviceTokens as $token) {
  echo $token;
    $messages[] = [
        'token' => $token,
        'notification' => [
            'title' => 'Notification Title',
            'body' => 'Notification Body',
            'image' => 'https://example.com/test.jpg',
        ],
        'webpush' => [
            'fcm_options' => [
                'link' => 'https://example.com'
            ],
        ],
    ];
}

### Create message request promises

$promises = function() use ($client, $messages) {
    foreach ($messages as $message) {
        yield $client->requestAsync('POST', 'https://fcm.googleapis.com/v1/projects/eimbox-4c743/messages:send', [
            'json' => ['message' => $message],
        ]);
    }
};

### Create response handler

$handleResponses = function (array $responses) {
    foreach ($responses as $response) {
        if ($response['state'] === Promise\PromiseInterface::FULFILLED) {
            // $response['value'] is an instance of \Psr\Http\Message\RequestInterface
            echo $response['value']->getBody();
        } elseif ($response['state'] === Promise\PromiseInterface::REJECTED) {
            // $response['reason'] is an exception
            echo $response['reason']->getMessage();
        }
    }
};

Promise\Utils::settle($promises())
    ->then($handleResponses)
    ->wait();