<?php

include '../inc.light.php';

$id = $_POST['id'];
$building_name = mysqli_real_escape_string($conn,$_POST['building_name']);

if($id==''){

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

}else{

    mysqli_query($conn,"
        UPDATE seat_buildings
        SET
        building_name='$building_name'
        WHERE id='$id'
    ");

}

echo 1;