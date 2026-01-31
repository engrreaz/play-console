<?php

include_once '../inc.light.php'; // পাথ চেক করে নিন, সাধারণত এক ধাপ উপরে থাকে


if ($user_id_no == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ১. সব নোটিফিকেশন একসাথে রিড মার্ক করা
    if (isset($_POST['all']) && $_POST['all'] == 'true') {
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
        $stmt->bind_param("i", $user_id_no);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'All marked as read']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Query failed']);
        }
        $stmt->close();
    } 
    
    // ২. নির্দিষ্ট একটি নোটিফিকেশন রিড মার্ক করা
    elseif (isset($_POST['id'])) {
        $notif_id = intval($_POST['id']);
        
        // শুধু ওই ইউজারের নিজস্ব নোটিফিকেশন আপডেট হবে (Security Check)
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $notif_id, $user_id_no);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Marked as read']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Query failed']);
        }
        $stmt->close();
    }
}

$conn->close();
?>