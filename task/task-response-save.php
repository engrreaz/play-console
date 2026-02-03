<?php
include '../inc.light.php';

$id=$_POST['task_id'];
$status=$_POST['response_status'];
$notes=$_POST['notes'];

mysqli_query($conn,"
INSERT INTO task_response(task_id,notes,response_status)
VALUES('$id','$notes','$status')
");

mysqli_query($conn,"
UPDATE task_manager SET status='$status' WHERE id='$id'
");
