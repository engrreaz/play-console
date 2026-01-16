<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
$exp = strtotime($cur) + 3600*24*3; $exp = date('Y-m-d H:i:s', $exp);
	include ('../db.php');;
	

	$user = $_POST['user'];;   $sccode = $_POST['sccode'];;  
	$query33 ="UPDATE scinfo set pack = 1, packdate = '$cur', expire = '$exp' where rootuser = '$user' and sccode = '$sccode'";
		$conn->query($query33); 
	
                            ?>