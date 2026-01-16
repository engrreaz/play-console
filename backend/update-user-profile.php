<?php
date_default_timezone_set('Asia/Dhaka');
$dt = date('Y-m-d H:i:s');
include('inc.back.php');

$dispname = $_POST['dispname'];
$mno = $_POST['mno'];
$id = $_POST['id'];

$query33 = "update usersapp set
		            profilename = '$dispname', mobile = '$mno' where id = '$id';";
$conn->query($query33);

echo '<i class="bi bi-check2-circle text-success"></i> <b>Update Successfully.</b>';