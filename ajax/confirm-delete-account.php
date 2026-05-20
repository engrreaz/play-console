<?php
session_start();
header('Content-Type: application/json');

include_once '../inc.light.php';

if(!isset($_SESSION['user'])){
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access."
    ]);
    exit;
}

$user = $_SESSION['user'];

// prevent duplicate request (optional safety)
$check = mysqli_query($conn, "SELECT id FROM deletion_requests WHERE user_mail='$user' AND sccode='$sccode' AND  status='pending' LIMIT 1");

if(mysqli_num_rows($check) > 0){
    echo json_encode([
        "status" => "error",
        "message" => "You already have a pending deletion request."
    ]);
    exit;
}

// insert request instead of direct delete (SAFE approach)
$insert = mysqli_query($conn, "
    INSERT INTO deletion_requests 
    (user_mail, sccode, request_date, status) 
    VALUES 
    ('$user', '$sccode', NOW(), 'pending')
");

if($insert){
    echo json_encode([
        "status" => "success",
        "message" => "Your account deletion request has been submitted."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to submit request."
    ]);
}
?>