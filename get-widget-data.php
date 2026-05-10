<?php 
$to_do_value = 0;
$txt0 = '';
$txt1 = '';
$txt2 = '';
$txt3 = '';
$progress = 0;
$in_time = '';
$out_time = '';

$total = mysqli_fetch_row(mysqli_query($conn,"SELECT SUM(amount) FROM stpr WHERE prdate='$td' AND sccode='$sccode'"))[0] ?? 0;
$txt2 = 'Collection : ' . number_format($total,2);

$data = [
    "to_do_value" => $to_do_value,
    "txt0" =>"Bingo Batle",
    "txt1" => 'Attendance : 1430',
    "txt2" => $txt2,
    "txt3" => 'Time Range : 11:00 - 11:55',
    "progress" => $progress,
    "in_time" => '09:30:32',
    "out_time" => '04:12:21'
];

echo json_encode($data);