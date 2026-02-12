<?php
include '../inc.light.php';
include_once '../functions.php';

$tid = $_POST['tid'];
$time = $_POST['time'] ?? $cur;
$detect = 'Manual';


$att = saveTeacherAttendance($tid, $detect, $time);
if($att){
    echo 'OK';
} else {
    echo 'FALSE';
}
?>