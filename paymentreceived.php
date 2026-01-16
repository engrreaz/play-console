<?php
include 'inc.php';
	
    $td = date('Y-m-d');
	// $count = $_POST['count'];;   //$ = $_POST[''];;  
	$cls = $_POST['cls'];  $sec = $_POST['sec'];  
	$user = $_POST['user'];;   $sccode = $_POST['sccode'];;  $sy = $_POST['sy'];;
	$from = $_POST['from'];;   $amt = $_POST['amt'];; $tail = $_POST['tail'];;
	
	
    if($tail == 0){
        
    }
		
		
		
		
		$query33 ="insert into transaction(id, sessionyear, sccode, classname, sectionname, date, receivedby, receivedfrom, amount, entrytime, bankaccid)
		VALUES (NULL, '$sy', '$sccode', '$cls', '$sec', '$td', '$user', '$from', '$amt', '$cur', NULL );";
		$conn->query($query33); 
		//echo $query33;
		echo '<b>Received</b>';
		
		
		
                            ?>