<?php
include '../inc.light.php';

$room  = $_POST['room'];
$date  = $_POST['date'];
$shift = $_POST['shift'];
$tid   = $_POST['tid'];

mysqli_query($conn,"
    UPDATE invigilators 
    SET tid='$tid'
    WHERE room_id='$room'
      AND exam_date='$date'
      AND shift='$shift'
      AND sccode='$sccode'
");

echo "updated";
?>