<?php
include 'inc.light.php';


$tokens = [
    $token
];

// $tokens[] = $token;
pushFCM($tokens, 'Hello', 'Test Message, This is for check', 'https://playconsole.eimbox.com/notific-icon/fingerprint.png');
/*
$ttm = date("H:i:s");

$accessToken = getAccessToken();

$projectId = 'eimbox-9014d';

$url = "https://fcm.googleapis.com/v1/projects/" . $projectId . "/messages:send";

foreach ($tokens as $token) {

    $data = [
        "message" => [
            // "topic" => "eimbox_notif_channel",

            "token" => $token,

            "notification" => [
                "title" => "Hello",
                "body" => "Test Message @ " . $ttm
            ],

            "data" => [
                "title" => "EIMBox আপডেট",
                "body" => "আপনার প্রোফাইল চেক করুন " . $ttm,
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
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/json"
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo curl_error($ch);
    } else {
        echo $response;
    }

    curl_close($ch);

}
    */