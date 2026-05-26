<?php

include '../inc.light.php';

$id = $_POST['id'];

$q = mysqli_query($conn,"
    SELECT *
    FROM seat_room_benches
    WHERE id='$id'
");

echo json_encode(mysqli_fetch_assoc($q));