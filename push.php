<?php

// Server key from Firebase Console
define('API_ACCESS_KEY', 'AAAAiSanis8:APA91bGHIRxAjn8YBaf562fukaYy9N9_8LiNIm5XcTZnHEPqIK7Nr38PQhMJrWTpt9g0VI6U9DMvRT58K-D8AwHwwBvG3YqK8hKbxTMNu9qjaAm6KGj09FGyYT3RVUwExfs4IWXSfucp'); // Replace YOUR FIREBASE CLOUD MESSAGING API KEY with your Firebase Cloud Messaging server Key


// if($_SERVER["REQUEST_METHOD"] == "POST")
{

     // POST values
// $token= $_POST["token"];
// $title= $_POST["title"];
// $message= $_POST["message"];
// $postlink= $_POST["postlink"];
     $token = 'dyNNzaM0TpWsUtgK6j9MEH:APA91bEkjhXZ_iaUwJ0hdbrSFMcj1xF_cW0ZYB3ty8zajZ_gtUTNuIWaxM3lXwR1J2f-TD8uYmmoW7bdNuVgQVwPgbGNPbuKw4tISpxWaX5SAGeASXRjFcc';
     $title = 'Hello Test';
     $message = "Hello message";
     $postlink = "index.php";

     $token = htmlspecialchars($token, ENT_COMPAT);
     $title = htmlspecialchars($title, ENT_COMPAT);
     $message = htmlspecialchars($message, ENT_COMPAT);
     $postlink = htmlspecialchars($postlink, ENT_COMPAT);

     // Push Data's
     $data = array(
          "to" => "$token",
          "notification" => array(
               "title" => "$title",
               "body" => "$message",
               "icon" => "https://avatars2.githubusercontent.com/u/52190236?s=460&u=b5599a497d334f1edf4c2be8df4bd4d8f2a44e54&v=4", // Replace https://example.com/icon.png with your PUSH ICON URL
               "click_action" => "$postlink"
          )
     );

     // Print Output in JSON Format
     $data_string = json_encode($data);

     // FCM API Token URL
     $url = "https://fcm.googleapis.com/fcm/send";

     //Curl Headers
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

     // Variable for Print the Result
     $result = curl_exec($ch);

     curl_close($ch);

}
var_dump($result);