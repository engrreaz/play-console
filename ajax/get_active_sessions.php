<?php
include '../inc.light.php';

$data = [];

$q = $conn->query("
    SELECT syear AS sessionyear
    FROM sessionyear
    WHERE active=1 and sccode='$sccode'
    ORDER BY syear DESC
");

while ($r = $q->fetch_assoc()) {
    $data[] = $r;
}

header('Content-Type: application/json');
echo json_encode($data);
