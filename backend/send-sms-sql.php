<?php
// $camp = "";
$type = "";

$url = "http://bulksmsbd.net/api/smsapi";
$api_key = "tNrdSSziORSgTc85sDxJ";
$senderid = "8809617618425x";
// $number = $_POST['number'];
// $message = $_POST['message'];

$len = strlen($message);
$cnt = ceil($len / 159);
$cost = $cnt * $sms_price;


$data = [
    "api_key" => $api_key,
    "senderid" => $senderid,
    "number" => $number,
    "message" => $message
];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);


// $resp = json_encode($response);
$search_text = array('{', '}', '"');
$replace_text = array('', '', '');

$response = str_replace($search_text, $replace_text, $response);
$response = explode(",", $response);
for ($a = 0; $a < count($response); $a++) {
    $response[$a] = explode(":", $response[$a]);
}

if (count($response) == 3) {
    $code = $response[0][1];
    $sms_id = NULL;
    $succ_msg = $response[1][1];
    $err_msg = $response[2][1];
} else if (count($response) == 4) {
    $code = $response[0][1];
    $sms_id = $response[1][1];
    $succ_msg = $response[2][1];
    $err_msg = $response[3][1];
}

if($code == 1001 || $code == 1005) {
    $cost = 0;
}

$query33f = "INSERT INTO sms(id, sccode, sessionyear, date, campaign, sms_type, mobile_number, sms_text, sms_len, count, send_by, send_time, cost, response_code, message_id, success_message, error_message, status, comments, modifieddate)
VALUES (NULL, '$sccode', '$sy', '$td', '$camp', '$type', '$number', '$message', '$len', '$cnt', '$usr', '$cur', '$cost', '$code', '$sms_id', '$succ_msg', '$err_msg', '', '', '$cur');";
$conn->query($query33f);

// echo '<i class="bi bi-check-circle"></i>';
// echo $query33f;

// echo $code;
// echo '<pre>';
// print_r($response);
// echo '</pre>';