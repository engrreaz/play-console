<?php 
include '../inc.light.php';

$res=mysqli_query($conn,"SELECT * FROM task_manager");

$data=[];

while($r=mysqli_fetch_assoc($res)){
 $data[]=$r;
}

echo json_encode([
 'status'=>true,
 'count'=>count($data),
 'data'=>$data
]);
