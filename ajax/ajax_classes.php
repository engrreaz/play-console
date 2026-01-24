<?php
include '../inc.light.php';
$slot = isset($_GET['slot']) ? $_GET['slot'] : '';
$session = isset($_GET['session']) ? $_GET['session'] : '';

$out = [];

if($slot != '' && $session != '') {
    $sql = "SELECT DISTINCT classname AS areaname
            FROM sessioninfo
            WHERE slot='$slot' AND sessionyear='$session'
            ORDER BY FIELD(classname,'Six','Seven','Eight','Nine','Ten')";
    $res = $conn->query($sql);
    while($r = $res->fetch_assoc()){
        $out[] = $r;
    }
}

header('Content-Type: application/json');
echo json_encode($out);