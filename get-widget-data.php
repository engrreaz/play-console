<?php 
include_once 'inc.light.php';
$to_do_value = 0;
$txt0 = '';
$txt1 = '';
$txt2 = '';
$txt3 = '';
$progress = 0;
$in_time = '';
$out_time = '16:00:00';

$attnd = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(yn) FROM stattnd WHERE adate='$td' AND sccode='$sccode' AND yn=1"))[0] ?? 0;
$txt1 = 'Attendance : ' . $attnd;

$total = mysqli_fetch_row(mysqli_query($conn,"SELECT SUM(amount) FROM stpr WHERE prdate='$td' AND sccode='$sccode'"))[0] ?? 0;
$txt2 = 'Collection : ' . number_format($total,2);

$data = [
    "to_do_value" => $to_do_value,
    "txt0" => $txt0,
    "txt1" => $txt1,
    "txt2" => $txt2,
    "txt3" => $txt3,
    "progress" => $progress,
    "in_time" => $in_time,
    "out_time" => $out_time
];

echo json_encode($data);