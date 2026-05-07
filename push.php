<?php

define('API_ACCESS_KEY', 'AAAAiSanis8:APA91bGHIRxAjn8YBaf562fukaYy9N9_8LiNIm5XcTZnHEPqIK7Nr38PQhMJrWTpt9g0VI6U9DMvRT58K-D8AwHwwBvG3YqK8hKbxTMNu9qjaAm6KGj09FGyYT3RVUwExfs4IWXSfucp'); // Replace YOUR FIREBASE CLOUD MESSAGING API KEY with your Firebase Cloud Messaging server Key
{

     $token = 'd9bEq1MSSBOkO-DtXB5cEJ:APA91bEmc_dZnL3mkaHh37aPXLrwaKRTIMSSynseNMWChXf_WuZk8b5Ns5WT39tkJz5DlpYJK5kpohN8yvGEHK8GnPCfzWespwD8CWjfWMXk4DydCWAxc4Y';
     $title = 'Hello Test';
     $message = "Hello message";
     $postlink = "index.php";

     $token = htmlspecialchars($token, ENT_COMPAT);
     $title = htmlspecialchars($title, ENT_COMPAT);
     $message = htmlspecialchars($message, ENT_COMPAT);
     $postlink = htmlspecialchars($postlink, ENT_COMPAT);

     $data = array(
          "to" => "$token",
          "notification" => array(
               "title" => "$title",
               "body" => "$message",
               "icon" => "https://avatars2.githubusercontent.com/u/52190236?s=460&u=b5599a497d334f1edf4c2be8df4bd4d8f2a44e54&v=4", // Replace https://example.com/icon.png with your PUSH ICON URL
               "click_action" => "$postlink"
          )
     );

     $data_string = json_encode($data);

     $url = "https://fcm.googleapis.com/fcm/send";

     $headers = array
     (
          'Authorization: key=' . API_ACCESS_KEY,
          'Content-Type: application/json'
     );

     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

     $result = curl_exec($ch);

     curl_close($ch);

}
var_dump($result);