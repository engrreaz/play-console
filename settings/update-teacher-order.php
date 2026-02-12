<?php
include '../inc.light.php'; // DB connection ready

if(!isset($_POST['order'])) exit;

$data = json_decode($_POST['order'], true);

$stmt = $conn->prepare("UPDATE teacher SET sl=? WHERE tid=? AND sccode=?");

foreach($data as $row){
    $sl  = intval($row['sl']);
    $tid = intval($row['tid']);

    $stmt->bind_param("iii", $sl, $tid, $sccode);
    $stmt->execute();
}

$stmt->close();

echo "OK";
