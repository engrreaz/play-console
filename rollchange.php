<?php
include 'inc.php';
	


            $prev = 'Seven';
	        $syy = $sy - 1;
		    
		    $sql0r = "SELECT * FROM sessioninfo where sccode = '$sccode' and sessionyear='$syy' and  classname='$prev'  "; 
            $result0r = $conn->query($sql0r); if ($result0r->num_rows > 0) {while($row0r = $result0r->fetch_assoc()) { 
            $rollno=$row0r["rollno"];  $stid=$row0r["stid"];
            
            $query3g ="update sessioninfo set rollno = '$rollno' where stid='$stid'; "; //echo $query3g. '<br>';
    		$conn->query($query3g);
            }}
            
            
            echo 'done';
		
		
		
                            ?>