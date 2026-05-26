<?php

include '../inc.light.php';

$id = $_POST['id'];

$floor_id = $_POST['floor_id'];

$room_name = mysqli_real_escape_string($conn,$_POST['room_name']);

$total_rows = $_POST['total_rows'];

$total_cols = $_POST['total_cols'];

$default_capacity = isset($_POST['default_capacity']) ? $_POST['default_capacity'] : 2;

if($id==''){

    mysqli_query($conn,"
        INSERT INTO seat_rooms
        (
            floor_id,
            room_name,
            total_rows,
            total_cols
        )
        VALUES
        (
            '$floor_id',
            '$room_name',
            '$total_rows',
            '$total_cols'
        )
    ");

    $room_id = mysqli_insert_id($conn);

    // AUTO GENERATE BENCHES

    for($r=1; $r<=$total_rows; $r++){

        for($c=1; $c<=$total_cols; $c++){

            mysqli_query($conn,"
                INSERT INTO seat_room_benches
                (
                    room_id,
                    row_no,
                    col_no,
                    capacity
                )
                VALUES
                (
                    '$room_id',
                    '$r',
                    '$c',
                    '$default_capacity'
                )
            ");

        }

    }

}else{

    mysqli_query($conn,"
        UPDATE seat_rooms
        SET
        room_name='$room_name',
        total_rows='$total_rows',
        total_cols='$total_cols'
        WHERE id='$id'
    ");

    // Remove benches that are outside the new dimensions
    mysqli_query($conn,"
        DELETE FROM seat_room_benches
        WHERE room_id='$id' AND (row_no > '$total_rows' OR col_no > '$total_cols')
    ");

}

echo 1;