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