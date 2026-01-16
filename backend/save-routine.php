<?php
	include ('inc.back.php');;
	
    
	$id = $_POST['id'];; 
	$sub = $_POST['sub'];; $tid = $_POST['tid'];; 
	$cls = $_POST['cls'];;  $sec = $_POST['sec'];;  
	$period = $_POST['period'];;  $wday = $_POST['wday'];;  
    
    if($id>0){
        $query33 = "UPDATE clsroutine set subcode='$sub', tid='$tid' where id='$id';";
    } else {
        $query33 ="INSERT INTO clsroutine (id, sccode, sessionyear, classname, sectionname, period, wday, day, subcode, tid, entryby ) 
                                    VALUES (NULL, '$sccode', '$sy', '$cls', '$sec', '$period', '$wday', '$day', '$sub', '$tid', '$usr');";
    }
    
    $conn->query($query33);
    echo '<span style="font-size:20px;"><i class="bi bi-check2-circle"></i></span>';
   //************************************************************************************************************************************************
