<?php
include '../inc.light.php';

$session = $_GET['session'];
$exam = $_GET['exam'];
$type = $_GET['type'];

// $q = mysqli_query($conn,"SELECT DISTINCT max(id) as id, slot, examtitle 
// FROM seat_plans 
// WHERE sessionyear='$session' group by slot, examtitle");

$data = [];
// while($r = mysqli_fetch_assoc($q)){
//     $data[] = $r;
// }

if ($type == "room") {
    $data = [
        [
            "value" => 3,
            "title" => "3 - Ramanujan"
        ],
        [
            "value" => 6,
            "title" => "6 - Sreenibas"
        ]
    ];
}

if ($type == "day") {
    $data = [
        [
            "value" => "2026-06-28",
            "title" => "2026-06-28"
        ],
        [
            "value" => "2026-06-29",
            "title" => "2026-06-29"
        ]
    ];
}

if ($type == "teacher") {
    $data = [
        [
            "value" => 1031879999,
            "title" => "Saidur Rahman"
        ],
        [
            "value" => 1031879995,
            "title" => "Dr. Anwar Hossain"
        ]
    ];
}

echo json_encode($data);