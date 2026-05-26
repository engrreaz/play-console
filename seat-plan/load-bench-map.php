<?php

include '../inc.light.php';

$room_id = $_POST['room_id'];

$room = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT *
    FROM seat_rooms
    WHERE id='$room_id'
"));

$data = [];

$data['room_name'] = $room['room_name'];

$data['total_rows'] = $room['total_rows'];

$data['total_cols'] = $room['total_cols'];

$data['benches'] = [];

$q = mysqli_query($conn,"
    SELECT *
    FROM seat_room_benches
    WHERE room_id='$room_id'
    ORDER BY row_no,col_no
");

$existing_benches = [];
while($row = mysqli_fetch_assoc($q)){
    $existing_benches[$row['row_no']][$row['col_no']] = $row;
}

for ($r = 1; $r <= $room['total_rows']; $r++) {
    for ($c = 1; $c <= $room['total_cols']; $c++) {
        if (isset($existing_benches[$r][$c])) {
            $data['benches'][] = $existing_benches[$r][$c];
        } else {
            mysqli_query($conn, "INSERT INTO seat_room_benches (room_id, row_no, col_no, capacity) VALUES ('$room_id', '$r', '$c', '2')");
            $new_id = mysqli_insert_id($conn);
            $data['benches'][] = [
                'id' => $new_id,
                'room_id' => $room_id,
                'row_no' => $r,
                'col_no' => $c,
                'bench_label' => '',
                'capacity' => 2,
                'is_active' => 1,
                'is_blocked' => 0,
                'blocked_reason' => '',
                'seat_type' => 'normal'
            ];
        }
    }
}

echo json_encode($data);