<?php 
require ('FcmApi.php');
$FcmApi = new \FcmApi(
[
    'path' => 'firebase.json', 
    'project_id' => 'eimbox-4c743'
]);

$token = 'eLjeTSSjRiWof_uRLVClLh:APA91bEGV47tBFB9tAIxkYAQc-Y060orxJlc_tCM_u-F1TEg42Fkv6gzwE3Guz4Azj-GM2mpjO3O7YvZSkfqRuJ1zaFz0GrTByphpL8uyQJ8E89cDa8tOQg';
if($result = $FcmApi->setMessage(
[
    'title' => 'Hi ON MODE', 
    'body' => 'Hello World! ',
])->send($token)) {
    echo 'Message sent successfully. Message ID: '. $result['name'];
}   
else {
    echo $FcmApi->getError();
}