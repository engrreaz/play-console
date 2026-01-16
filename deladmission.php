<?php
include ('inc.php');;
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');; $sy = date('Y-m-d');
	include ('../db.php');; 

  $stid = $_POST['stid'];;  
	$query33 ="delete from admission where sccode='$sccode' and stid='$stid';";
		$conn->query($query33);

		
?>


<b>Information Deleted Successfully.</b>