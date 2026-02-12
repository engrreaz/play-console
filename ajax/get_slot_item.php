<?php
include_once '../inc.light.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("SELECT * FROM slots WHERE id = ? AND sccode = ?");
    $stmt->bind_param("ii", $id, $sccode);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    echo json_encode($row);
    $stmt->close();
}
?>