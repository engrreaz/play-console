<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	

	$scc = $_POST['scc'];;  $email = $_POST['email'];; 
    
      $query33 ="UPDATE usersapp set sccode = '$scc' where email = '$email'";
    		$conn->query($query33);