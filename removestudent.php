<?php
include 'inc.php';
	

	$tail = $_POST['tail'];; 
	$stid = $_POST['stid'];  
    
    $new_sccode = $sccode . '0';
    
    if($tail  == 0){
        $query3x ="update sessioninfo set sccode='$new_sccode' where stid='$stid' and sessionyear='$sy';";
		$conn->query($query3x);
		
		$query3y ="update stfinance set sccode='$new_sccode' where stid='$stid' and sessionyear='$sy';";
		$conn->query($query3y);
		
		echo 'Successfully remove the student temporary.';
		
    } else {
        echo 'Function Currently Unavailable';
    }
		
	
		
		
		
                            ?>