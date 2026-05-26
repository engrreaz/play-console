<?php

include '../inc.light.php';

$id = $_POST['id'];

mysqli_query($conn,"
    DELETE FROM seat_rooms
    WHERE id='$id'
");

mysqli_query($conn,"
    DELETE FROM seat_room_benches
    WHERE room_id='$id'
");

echo 1;