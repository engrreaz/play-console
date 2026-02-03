<?php 
include '../inc.light.php';

$id=$_POST['task_id'];

mysqli_query($conn,"
INSERT INTO task_response(task_id,notes,response_status)
VALUES('$id','$_POST[notes]','$_POST[response_status]')
");

mysqli_query($conn,"
UPDATE task_manager
SET status='$_POST[response_status]'
WHERE id='$id'
");

echo json_encode(['status'=>true]);
