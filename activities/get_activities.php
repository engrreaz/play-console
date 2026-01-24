<?php
include '../inc.light.php';

$category = $_GET['category'];
$res = $conn->query("SELECT id,title FROM activities_master WHERE category='$category' AND status=1 ORDER BY title");

$activities = [];
while($r=$res->fetch_assoc()){
    $activities[] = $r;
}

header('Content-Type: application/json');
echo json_encode($activities);
