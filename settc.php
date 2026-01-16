<?php
include 'inc.php';
	

	$taka = $_POST['taka'];; 
	$cause = $_POST['cause'];; 
	$stid = $_POST['stid'];  
    


		$query3y ="update stfinance set amount='$taka', payableamt='$taka', dues='$taka' where stid='$stid' and sessionyear='$sy' and partid=40;";
		$conn->query($query3y);
		
		$query33x ="insert into tcert(id, sessionyear, sccode, stid, amount, cause)
        VALUES (NULL, '$sy', '$sccode', '$stid', '$taka', '$cause' );";
        $conn->query($query33x); 

		
		
		echo 'Process Complete successfully.';
		
