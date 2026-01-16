<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	
	$id = $_POST['id'];;  
    

	if(isset($_POST['rls'])){
	    $rls = $_POST['rls']; $cls = $_POST['cls']; $sec = $_POST['sec']; $sccode = $_POST['sccode'];  $grname = $_POST['grname']; 
	    
	    $myarr = explode(".", $rls);
	    foreach($myarr as $value) {
	        $sql0 = "update sessioninfo set groupname='$grname' where sessionyear = '$sy' and sccode='$sccode' and classname='$cls' and sectionname='$sec' and rollno='$value'";
            $conn->query($sql0);
        }

	    $query33 ="UPDATE pibigroup set rolls='$rls' where id='$id'";
		$conn->query($query33);
	} else {
	    $query33 ="DELETE from pibigroup where id='$id'";
		$conn->query($query33);
	}
   
   //************************************************************************************************************************************************
   //****************************************************************************************************************************************************************
   