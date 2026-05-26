<?php
include '../inc.light.php';

$room_id = $_POST['room_id'] ?? 0;
$examtitle = $_POST['examtitle'] ?? '';

$room_id = (int)$room_id;
$examtitle_esc = $conn->real_escape_string($examtitle);

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

// Fetch allocations for this room and exam
$allocations = [];
$alloc_q = $conn->query("
    SELECT a.bench_id, a.seat_no, a.rollno, a.classname, a.sectionname, a.stid 
    FROM seat_plan_allocations a 
    JOIN seat_plans p ON a.plan_id = p.id
    WHERE a.room_id = '$room_id' AND p.examtitle = '$examtitle_esc'
");
if($alloc_q) {
    while($row = $alloc_q->fetch_assoc()) {
        $allocations[$row['bench_id']][] = $row;
    }
}

$q = mysqli_query($conn,"
    SELECT *
    FROM seat_room_benches
    WHERE room_id='$room_id'
    ORDER BY row_no,col_no
");

while($row = mysqli_fetch_assoc($q)){
    $bench_id = $row['id'];
    $row['allocations'] = $allocations[$bench_id] ?? [];
    $data['benches'][] = $row;
}

echo json_encode($data);
?>