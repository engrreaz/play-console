<?php

include '../inc.light.php';

$id = $_POST['id'];

mysqli_query($conn,"
    DELETE FROM seat_floors
    WHERE id='$id'
");

echo 1;