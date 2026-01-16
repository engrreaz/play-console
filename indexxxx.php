<?php
date_default_timezone_set('Asia/Dhaka');
require 'vendor/autoload.php';
use Google\Client;

echo date('d-m-Y H:i:s');
echo '<br>';
function getAccessToken($serviceAccountPath)
{
    $client = new Client();
    $client->setAuthConfig($serviceAccountPath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $client->useApplicationDefaultCredentials();
    $token = $client->fetchAccessTokenWithAssertion();
    return $token['access_token'];
}

function sendMessage($accessToken, $projectId, $message)
{
    $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
    $response = curl_exec($ch);
    if ($response === false) {
        throw new Exception('Curl error: ' . curl_error($ch));
    }
    curl_close($ch);
    return json_decode($response, true);
}


// Path to your service account JSON key file
$serviceAccountPath = 'xeneen-48f5d-firebase-adminsdk-8qjfn-9f3e67bb62.json';
// "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCecOIizV/iDSy1\nDmQm4uoahAK+hi9VTMNis21LRcawghQbtdMytaTQFRUvbXnixnvVo5Eq0yLWvY0w\nn8N+jtOvn7Xf0dWd5WYSbVhoDqR2LcYwnpotJA9JPctJ1sPNkcVDi/sIoKxb+B5S\nRJjJ3amzXU7X/7vkTSDRJBcjtoeThBrZjw/evquI7ziNW+gyiDPJN39X9w+KrDu2\n01wBnpdOn3/skIWp8zLXI55fbx4BTQz2Nb5T5aj8TL1RCi+W546I47Z2swYCUttA\n6+OjonEKmPwCZw0XFF9SBGYbOjkTVM+8DWW3T+dJk1yKzMjOxocznygVxLJ/vmzj\nShRaOeFnAgMBAAECggEANVsibEHpRkDVi2t7QrUrzR4jpnGdwwYEzlpKNg0bahmE\nGAjVRMBy7jgLWFtvrnTVAw8ANgHAO7y9rWA//4CtvPj10Jfjbjbwdsgn+3Li44Fq\nurjOhuEb3LhYm1cdvT6XxbWAJlmlcZtO6rl7Eo/5NBP6Fzdh2PI4WPXkbPxtD7nn\nMUf/q/nL8uVQlzBdNkPTRt7oHboqZ8kYrO/xICJpLFEoSgzsA5P5ldX58qe3h89Q\nI5ur64NvNoQBKvdqt+z3oRCfdbU53lH4GjKbmZVqrPA8ATCUM+0xlTOCQtA/6T0C\n+TMIDaf47xFi1rAU32QnaXfIcWZ7cRQHLM2gzTKSIQKBgQDXvsE0BVVpdto3zqhc\nwIxI95XIKoUVQBXIQZl44dRqkqN5KOgTlZqUPb3BSQRp4JXI/3cJ7cvDE3X71Mez\nrXJm9Vn2lO4ssiMYFswPgbeAAiSeGHFVGexGBXr3m0TXdMZW7KtwGtM9qdWaiVXU\n/EQrYVm0Fo+1IgmujmCo0UIyDwKBgQC8APJc4cTZKDjruIrlQ57fKjnpgwXSpR2a\nPwDLQGms4G0XRJVgGDKDb3uIrr6oiKkRftrrh2Yq7Udp1CFiAaJxrFulr75/ToSk\nbI7KmHPP5p63lZoRHP4XXm8JmqGf9fWWsBZW8ikGD0E0No5pYJc0htxo0xJp31kD\nFWg06ktTKQKBgQCfcjAxtRtpxVgDVK0jV36WUryU4a/Xg7RVev3k0+n8FYHRgoT6\nLF/A3VyHI+KqKTwp/3vHj8I+2vwgcSJiTXa2Vu/1CB56U/ER1Y9cin7GkU7ktKXV\nwkHXgideJZecMIaBKYqOYJTsBr+B5avUq9fpw5nbOa1drdk/86PGQFlOywKBgQCg\nU6pcHUjy7ANGunTwuS72H5uNkOfZUGgjT1FNA9xaynUd36YHcfs57UuLL4J2VTzh\nJ65oJ2qwdvNsw7PIUZ6HlDX/4RTymjIxykYnbcVt6b020ES4DWJ+6VCF/zGQKX/L\nhtU2RoNHoKC5d0ERiobIC65RUpckZI0TPSFF5vxfqQKBgHn8cqk552r/Qr3eIVJj\njdzXrVBa+dN6vrpVuITsZYg0FKA5nhkoMPqL3r1GCbTh2SruPS7En95zgqF/J/zX\n64zfDvs4vvTCaMld2Xqly/jODARterRscAJqmEw3TwdYul5Mi72SIXk4NtbumEy0\nTwbrwcAuOnicRSHql/ndbKGB\n-----END PRIVATE KEY-----\n",

// Your Firebase project ID
$projectId = 'xeneen-48f5d';

$icon = 'https://dashboard.eimbox.com/assets/imgs/logo.png';


// Example message payload
$notifData = [
    'title' => 'RPCS-Check-2024',
    'body' => 'Labib ' . date('i:s'),
    'image' => $icon,
];


$dataPayload = [
    'data1' => 'Kolas' , //-----------------------------on running
    'data2' => '80',
    'title' => 'Off Screen',
    'body' => 'Message Text' . date('d-m-Y H:i:s'),
    'data3' => 'This is extra payload',
    'priority' => 'high',
    'icon' => $icon,
];

$prio = [
    "priority" => "high",
];
      


$message = [
    'token' => 'drj6GzRaSISFqHbqXdCnc0:APA91bEFFVeDaH86Qikvso6eDkLhOB9XU2mfar9ACvUAOtyfvGJca5GmjKXeWRsFinEET18s99L63mgeBGaSYU4qRwEN6_ziVml2WB4CGA2-WM9tayycOg8',
    'notification' => $notifData,
    'data' => $dataPayload,
    'android' => $prio,
];


try {
    $accessToken = getAccessToken($serviceAccountPath);
    $response = sendMessage($accessToken, $projectId, $message);
    echo 'Message sent successfully: ' . print_r($response, true);
} catch (Exception $e) {
    echo '<br>**********************<br>Error: ' . $e->getMessage();
}

