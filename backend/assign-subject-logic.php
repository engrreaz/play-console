<?php
include '../inc.light.php';
header('Content-Type: application/json');

$stids = $_POST['stids']; // Array of IDs
$subjects = str_replace(',', '.', $_POST['subjects'] ?? '') . '.';

$slot = $_POST['slot'];
$session = $_POST['session'];

$fourth_sub = is_array($_POST['fourth_subject']) ? implode('.', $_POST['fourth_subject']) : $_POST['fourth_subject'] . '.';

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