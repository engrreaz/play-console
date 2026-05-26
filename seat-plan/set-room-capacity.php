<?php

include '../inc.light.php';

$room_id = $_POST['room_id'];
$capacity = $_POST['capacity'];

$room = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM seat_rooms WHERE id='$room_id'"));
if ($room) {
    $q = mysqli_query($conn,"SELECT row_no, col_no FROM seat_room_benches WHERE room_id='$room_id'");
    $existing = [];
    while($row = mysqli_fetch_assoc($q)){
        $existing[$row['row_no']][$row['col_no']] = true;
    }

    for ($r = 1; $r <= $room['total_rows']; $r++) {
        for ($c = 1; $c <= $room['total_cols']; $c++) {
            if (!isset($existing[$r][$c])) {
                mysqli_query($conn, "INSERT INTO seat_room_benches (room_id, row_no, col_no, capacity) VALUES ('$room_id', '$r', '$c', '$capacity')");
            }
        }
    }
}

mysqli_query($conn,"
    UPDATE seat_room_benches
    SET capacity='$capacity'
    WHERE room_id='$room_id'
");

echo 1;





