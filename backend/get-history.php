<?php
include '../inc.light.php';
header('Content-Type: application/json'); // এটি অবশ্যই থাকতে হবে

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    $stmt = $conn->prepare("SELECT * FROM history WHERE id = ? AND (sccode = ? or sccode = 0)");
    $stmt->bind_param("ii", $id, $sccode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Record not found']);
    }
    $stmt->close();
}