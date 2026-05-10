<?php

require 'functions-fcm.php';

$token = 'dRx0KEj0QwOCm0aJr7mb71:APA91bFHZ5lpsPANkvI_zU7Oo34W9z7rSw1UIIQCD_1kwFHkDXMsvDxiDnupLJUTIP2hWkHwiVL97clXs0cNgCYTzkgdgsVqLm5JARSX8tGsp5sgbi4_RcA';

$ttm = date("H:i:s");

$accessToken = getAccessToken();

$projectId = 'eimbox-9014d';

$url = "https://fcm.googleapis.com/v1/projects/".$projectId."/messages:send";

$data = [
    "message" => [

        "token" => $token,

        "notification" => [
            "title" => "Hello",
            "body" => "Test Message @ ".$ttm
        ],

        "data" => [
            "title" => "EIMBox আপডেট",
            "body" => "আপনার প্রোফাইল চেক করুন ".$ttm,
            "image" => "https://eimbox.com/images/fav.png",
            "m_icon" => "baseline_fingerprint_24",
            "data1" => $ttm
        ],

        "android" => [
            "notification" => [
                "image" => "https://eimbox.com/images/fav.png",
                "sound" => "default",
                "channel_id" => "default_channel"
            ]
        ]

    ]
];

$headers = [
    "Authorization: Bearer ".$accessToken,
    "Content-Type: application/json"
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo curl_error($ch);
}else{
    echo $response;
}

curl_close($ch);