<?php

include '../inc.light.php';


$id = $_POST['id'];

$capacity = $_POST['capacity'];
$is_blocked = $_POST['is_blocked'];

$blocked_reason = mysqli_real_escape_string(
    $conn,
    $_POST['blocked_reason']
);

$bench_label = mysqli_real_escape_string(
    $conn,
    $_POST['bench_label']
);

mysqli_query($conn,"
    UPDATE seat_room_benches
    SET
    capacity='$capacity',
    is_blocked='$is_blocked',
    blocked_reason='$blocked_reason',
    bench_label='$bench_label'
    WHERE id='$id'
");

echo 1;