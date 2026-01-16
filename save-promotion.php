<?php
	include ('incb.php');;
	

	$stid = $_POST['stid'];; 
	$roll = $_POST['roll'];; 
	$cls = $_POST['cls'];;  $sec = $_POST['sec'];;  
    
    $query33 ="INSERT INTO sessioninfo (id, stid, sessionyear, classname, sectionname, rollno, sccode ) 
                VALUES (NULL, '$stid', '$sy', '$cls', '$sec', '$roll', '$sccode');";
    $conn->query($query33);
    
    echo '<span style="font-size:30px;"><i class="bi bi-check2-circle"></i></span>';
   //************************************************************************************************************************************************
