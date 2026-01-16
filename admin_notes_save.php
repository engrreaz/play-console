<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	

	    $id = $_POST['id'];;  $usr = $_POST['usr'];;  $txt = $_POST['txt'];;
        $query33 ="insert into adminnotes (id, sccode, notes, count, entryby, entrytime) values (null, '$id', '$txt', count+1, '$usr', '$cur') ";
		$conn->query($query33);   echo 'Data Saved';
        
        $query33r ="update scinfo set count=count+1 where sccode = '$id' ";
		$conn->query($query33r);   
        
	    
		?>