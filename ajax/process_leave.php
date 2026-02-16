<?php
require_once "../inc.light.php";

$id     = $_POST['id'] ?? 0;
$status = $_POST['status'] ?? '';
$res_by = $user_name ?? 'Admin'; // সেশন থেকে ইউজারের নাম
$now    = date('Y-m-d H:i:s');

if($id && $status) {
    // স্ট্যাটাস আপডেট কুয়েরি
    $stmt = $conn->prepare("UPDATE student_leave_app SET status = ?, response_date = ?, response_by = ? WHERE id = ? AND sccode = ?");
    $stmt->bind_param("sssii", $status, $now, $res_by, $id, $sccode);

    if($stmt->execute()) {
        echo 'success';
    } else {
        echo 'DB Error';
    }
} else {
    echo 'Invalid Data';
}
?>