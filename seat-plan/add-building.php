<?php

include '../inc.light.php';

$building_name = $_POST['building_name'];

mysqli_query($conn,"
    INSERT INTO seat_buildings
    (
        building_name
    )
    VALUES
    (
        '$building_name'
    )
");

echo 1;