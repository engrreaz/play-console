<?php

function base64UrlEncode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function getAccessToken()
{
    $json = json_decode(file_get_contents('firebase-key.json'), true);

    $header = [
        'alg' => 'RS256',
        'typ' => 'JWT'
    ];

    $now = time();

    $payload = [
        'iss' => $json['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $now + 3600
    ];

    $base64Header = base64UrlEncode(json_encode($header));
    $base64Payload = base64UrlEncode(json_encode($payload));

    $unsignedJWT = $base64Header . "." . $base64Payload;

    openssl_sign(
        $unsignedJWT,
        $signature,
        $json['private_key'],
        'SHA256'
    );

    $jwt = $unsignedJWT . '.' . base64UrlEncode($signature);

    $postFields = http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    curl_close($ch);

    $result = json_decode($response, true);

    return $result['access_token'] ?? null;
}



function pushFCM($tokens = [], $title = '', $body = '', $imageurl = 'https://eimbox.com/images/fav.png', $icon = 'noti_currency', $db = 1, $conn = null)
{

    if (empty($tokens)) {
        return false;
    }

    $projectId = 'eimbox-9014d';

    $url = "https://fcm.googleapis.com/v1/projects/" . $projectId . "/messages:send";

    $accessToken = getAccessToken();

    $headers = [
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/json"
    ];

    $results = [];

    foreach ($tokens as $token) {

        $data = [

            "message" => [

                "token" => $token,

                "notification" => [
                    "title" => $title,
                    "body" => $body
                ],

                "data" => [
                    "title" => $title,
                    "body" => $body,
                    "image" => $imageurl,
                    "m_icon" => $icon
                ],

                "android" => [
                    "notification" => [
                        "image" => $imageurl,
                        "sound" => "default",
                        "channel_id" => "default_channel"
                    ]
                ]

            ]
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {

            $results[] = [
                'token' => $token,
                'success' => false,
                'message' => curl_error($ch)
            ];

        } else {

            $results[] = [
                'token' => $token,
                'success' => true,
                'response' => json_decode($response, true)
            ];
        }

        if ($db == 1 && $conn) {

            $success = 1;
            $responseData = json_decode($response, true);

            if (curl_errno($ch)) {
                $success = 0;
                $responseText = curl_error($ch);
            } else {
                $responseText = json_encode($responseData);
            }

            $stmt = $conn->prepare("SELECT email, sccode FROM usersapp WHERE token=?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $email = $row['email'] ?? '';
            $sccode = $row['sccode'] ?? '';
            $stmt->close();
            
            echo $token . ' | ' .
                $success . ' | ' .
                $responseText . ' | ' .
                $email . ' | ' .
                $sccode . '<br>';

        }


        curl_close($ch);
    }

    return $results;
}