<?php
include '../inc.light.php';

header('Content-Type: application/json');

$tid = intval($_GET['tid'] ?? 0);

$out = [
    'gps'    => ['st'=>0],
    'bio'    => ['st'=>0],
    'card'   => ['st'=>0],
    'manual' => ['st'=>0],
];

$stmt = $conn->prepare("
    SELECT gps_st,bio_st,card_st,manual_st
    FROM tattnd_manager
    WHERE tid=? AND sccode=? order by id desc LIMIT 1
");

$stmt->bind_param("ii",$tid,$sccode);
$stmt->execute();

$r = $stmt->get_result()->fetch_assoc();

if($r){
    $out['gps']['st']    = (int)$r['gps_st'];
    $out['bio']['st']    = (int)$r['bio_st'];
    $out['card']['st']   = (int)$r['card_st'];
    $out['manual']['st']= (int)$r['manual_st'];
}

echo json_encode($out);
