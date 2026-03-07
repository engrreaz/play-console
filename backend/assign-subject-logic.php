<?php
include '../inc.light.php';
header('Content-Type: application/json');

$stids = $_POST['stids']; // Array of IDs
$subjects = str_replace(',', '.', $_POST['subjects'] ?? '') . '.';

$slot = $_POST['slot'];
$session = $_POST['session'];

if (empty($raw_fourth)) {
    $fourth_sub = '0';
} else {
    // জাভাস্ক্রিপ্ট থেকে যদি স্ট্রিং আসে তবে সরাসরি সেটি ব্যবহার হবে
    // আর যদি কোনো কারণে অ্যারে আসে তবে সেটি ডট (.) দিয়ে জোড়া লাগবে
    $fourth_sub = is_array($raw_fourth) ? implode('.', $raw_fourth) : $raw_fourth;
}

$success_count = 0;
foreach ($stids as $stid) {
    $stid = $conn->real_escape_string($stid);
    $sql = "UPDATE sessioninfo SET 
            subject_list = '$subjects', 
            fourth_subject = '$fourth_sub' 
            WHERE stid = '$stid' AND sccode = '$sccode' AND slot = '$slot' AND sessionyear = '$session'";
    $conn->query($sql);
    $success_count++;
}

echo json_encode([
    'status' => 'success',
    'message' => "$success_count students updated successfully!"
]);