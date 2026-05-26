<?php

include '../inc.light.php';

$id = $_POST['id'];

$building_id = $_POST['building_id'];

$floor_name = mysqli_real_escape_string($conn,$_POST['floor_name']);

$floor_no = $_POST['floor_no'];

if($id==''){

    mysqli_query($conn,"
        INSERT INTO seat_floors
        (
            building_id,
            floor_name,
            floor_no
        )
        VALUES
        (
            '$building_id',
            '$floor_name',
            '$floor_no'
        )
    ");

}else{

    mysqli_query($conn,"
        UPDATE seat_floors
        SET
        floor_name='$floor_name',
        floor_no='$floor_no'
        WHERE id='$id'
    ");

}

echo 1;