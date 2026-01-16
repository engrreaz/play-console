<?php
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');;
	include ('../db.php');;

	$ch = $_POST['ch'];;  $id = $_POST['id'];;  

		if($ch == 1) {
		    $query33 ="update pibiprocess set status=1, jobdate = '$dt' where id='$id'";
    		$conn->query($query33);
		}
    		
	
		

		
		
?>

Done.