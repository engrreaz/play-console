<?php
session_start();
header('Content-Type: application/json');

include_once '../inc.light.php';

if(!isset($_SESSION['user_id'])){
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access."
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// prevent duplicate request (optional safety)
$check = mysqli_query($conn, "SELECT id FROM deletion_requests WHERE user_id='$user_id' AND status='pending' LIMIT 1");

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
    (user_id, request_date, status) 
    VALUES 
    ('$user_id', NOW(), 'pending')
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