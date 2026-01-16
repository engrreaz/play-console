<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	

	$dtid = $_POST['dtid'];;  $val = $_POST['val'];;   $opt = $_POST['opt'];;     
    
        if($opt == 1){
            $query33 ="UPDATE sessioninfo set fourth_subject = '$val' where id = '$dtid'";
    		$conn->query($query33);
        } 
        /*
        else if($opt == 2){
            $query33 ="UPDATE sessioninfo set religion = '$val' where stid = '$dtid'";
    		$conn->query($query33);
            $query34 ="UPDATE students set religion = '$val' where stid = '$dtid'";
    		$conn->query($query34);
        } else if($opt == 3){
            if($val == 'true'){$v = 1;} else {$v = 0;}
            $query33 ="UPDATE sessioninfo set status = '$v' where stid = '$dtid'";
    		$conn->query($query33);
        } 
        */   
		
		echo 'Updated Subject Code : <b>' . $val . '</b>';
                            ?>