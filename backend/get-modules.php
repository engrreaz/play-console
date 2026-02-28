<?php
include '../inc.light.php';

$sql = "SELECT nav_title, related_pages, nav_icon 
        FROM modulemanager 
        WHERE status_name >= 0
        ORDER BY nav_title ASC";

$res = $conn->query($sql);

$data = [];

while($row = $res->fetch_assoc()){
    $data[] = $row;
}

echo json_encode([
    "ok" => true,
    "data" => $data
]);