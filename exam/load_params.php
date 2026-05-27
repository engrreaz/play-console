<?php
include '../inc.light.php';

$session = $_GET['session'];
$plan_id = $_GET['exam'];
$type = $_GET['type'];

$qq = "SELECT examtitle FROM seat_plans WHERE id='$plan_id' AND sessionyear='$session' AND sccode='$sccode' LIMIT 1";
$res = mysqli_query($conn, $qq);
$exam = "";
while ($r = mysqli_fetch_assoc($res)) {
    $exam = $r['examtitle'];
}

// $q = mysqli_query($conn,"SELECT DISTINCT max(id) as id, slot, examtitle 
// FROM seat_plans 
// WHERE sessionyear='$session' group by slot, examtitle");

$data = [];
// while($r = mysqli_fetch_assoc($q)){
//     $data[] = $r;
// }

if ($type == "room") {

    $q_room = "SELECT DISTINCT room_id as value, room_id as title FROM invigilators WHERE sessionyear='$session' AND examname='$exam' AND sccode='$sccode'";
//   echo $q_room;
    $res_room = mysqli_query($conn, $q_room);
    while ($r = mysqli_fetch_assoc($res_room)) {
        $data[] = $r;
    }
}

if ($type == "day") {
    $q_day = "SELECT DISTINCT exam_date as value, exam_date as title FROM invigilators WHERE sessionyear='$session' AND examname='$exam' AND sccode='$sccode'";
    $res_day = mysqli_query($conn, $q_day);
    while ($r = mysqli_fetch_assoc($res_day)) {
        $data[] = $r;
    }
}

if ($type == "teacher") {
    $q_teacher = "SELECT DISTINCT tid as value, tid as title FROM invigilators WHERE sessionyear='$session' AND examname='$exam' AND sccode='$sccode'";
    $res_teacher = mysqli_query($conn, $q_teacher);
    while ($r = mysqli_fetch_assoc($res_teacher)) {
        $data[] = $r;
    }
}

echo json_encode($data);