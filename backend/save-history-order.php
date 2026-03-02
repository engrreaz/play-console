<?php
include '../inc.light.php';
header('Content-Type: application/json');

$order = json_decode($_POST['order'], true);


if (!empty($order)) {
    foreach ($order as $item) {
        $id = intval($item['id']);
        $priority = intval($item['priority']);
        
        $sql = "UPDATE history SET priority = ? WHERE id = ? AND (sccode = ? or sccode = 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $priority, $id, $sccode);
        $stmt->execute();
    }
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}