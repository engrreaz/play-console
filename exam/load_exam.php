<?php
include '../inc.light.php';

$session = $_GET['session'];

$q = mysqli_query($conn,"SELECT DISTINCT max(id) as id, slot, examtitle 
FROM seat_plans 
WHERE sessionyear='$session' group by slot, examtitle");

$data = [];
while($r = mysqli_fetch_assoc($q)){
    $data[] = $r;
}

echo json_encode($data);