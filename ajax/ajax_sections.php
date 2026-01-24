


<?php
include '../inc.light.php';
$class = isset($_GET['class']) ? $_GET['class'] : '';

$out = [];

if($class != '') {
    $sql = "SELECT DISTINCT sectionname AS subarea
            FROM sessioninfo
            WHERE classname='$class'
            ORDER BY sectionname";
    $res = $conn->query($sql);
    while($r = $res->fetch_assoc()){
        $out[] = $r;
    }
}

header('Content-Type: application/json');
echo json_encode($out);
