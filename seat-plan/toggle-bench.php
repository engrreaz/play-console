<?php

include '../inc.light.php';

$id = $_POST['id'];

$status = $_POST['status'];

$new_status = $status == 1 ? 0 : 1;

mysqli_query($conn,"
    UPDATE seat_room_benches
    SET is_blocked='$new_status'
    WHERE id='$id'
");

echo 1;