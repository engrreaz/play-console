<?php
session_start();
include_once '../inc.inc.php'; // DB connection and session variables

// Ensure the user is logged in and the request is a POST request
if (!isset($_SESSION['usr']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_email = $_SESSION['usr'];
$sccode = $_SESSION['sccode'];
$notification_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$mark_all = isset($_POST['all']) && $_POST['all'] === 'true';

if ($mark_all) {
    // Mark all notifications for the user as read
    $sql = "UPDATE notification SET rwstatus = '1' WHERE tomail = ? AND sccode = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'DB statement prepare failed.']);
        exit;
    }
    $stmt->bind_param("ss", $user_email, $sccode);

} elseif ($notification_id > 0) {
    // Mark a single notification as read
    $sql = "UPDATE notification SET rwstatus = '1' WHERE id = ? AND tomail = ? AND sccode = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'DB statement prepare failed.']);
        exit;
    }
    $stmt->bind_param("iss", $notification_id, $user_email, $sccode);

} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

// Execute the update
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update notification status.']);
}

$stmt->close();
$conn->close();
?>
