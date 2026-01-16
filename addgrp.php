<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	
	$sccode = $_POST['sccode'];;  $eby = $_POST['eby'];;  
	$cls = $_POST['cls'];;  $sec = $_POST['sec'];;  $grp = $_POST['grp'];;   
    
    $query33 ="INSERT INTO pibigroup (id, sccode, sessionyear, classname, sectionname, groupname, entryby, entrytime)
		    VALUES(NULL,  '$sccode', '$sy', '$cls', '$sec', '$grp', '$eby', '$cur' )";
		$conn->query($query33);
   
   //************************************************************************************************************************************************
   //****************************************************************************************************************************************************************
   