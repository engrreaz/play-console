<?php

include '../inc.light.php';

$id = $_POST['id'];

mysqli_query($conn,"
    DELETE FROM seat_buildings
    WHERE id='$id'
");

echo 1;