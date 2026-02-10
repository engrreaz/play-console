<?php
include '../inc.light.php';

$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $row) {
    $id = (int)$row['id'];
    $pos = (int)$row['pos'];

    $stmt = $conn->prepare("UPDATE hub_categories SET sort_order=? WHERE id=?");
    $stmt->bind_param("ii", $pos, $id);
    $stmt->execute();
}

echo "ok";
