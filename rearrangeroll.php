<?php
include 'inc.php';
	
    //         $mp = 1;
	   //     $sql0r = "SELECT * FROM stmark where sessionyear='$sy' and ca>0 order by ca "; 
    //         $result0r = $conn->query($sql0r); if ($result0r->num_rows > 0) {while($row0r = $result0r->fetch_assoc()) { 
    //         $ca=$row0r["ca"]; $stid=$row0r["stid"];  
            
    //         $rem = $ca % 2;
    //         if($rem == 0){$sec = 'Joba'; $roll = ($ca+0)/2;} else {$sec = 'Beli'; $roll = ($ca+1)/2;}
            
    //         echo $stid . ' --- Roll - ' . $roll . ' | ' . $sec . '<br>';
            
    //         $query3x ="update sessioninfo set rollno='$roll', sectionname='$sec' where stid='$stid' and sessionyear='$sy';";
		  //  $conn->query($query3x);
		    
		  //  $query3y ="update stpr set rollno='$roll', sectionname='$sec' where stid='$stid' and sessionyear='$sy';";
		  //  $conn->query($query3y);
		    
		  //  $query3z ="update stfinance set rollno='$roll', sectionname='$sec' where stid='$stid' and sessionyear='$sy';";
		  //  $conn->query($query3z);
		    
            
            
    //         }}

   
                $stid = 1031872821;
                $roll = 34; 
                $sec = 'Joba';
            
            echo $stid . ' --- Roll - ' . $roll . ' | ' . $sec . '<br>';
            
            $query3x ="update sessioninfo set rollno='$roll', sectionname='$sec' where stid='$stid' and sessionyear='$sy';";
		    $conn->query($query3x);
		    
		    $query3y ="update stpr set rollno='$roll', sectionname='$sec' where stid='$stid' and sessionyear='$sy';";
		    $conn->query($query3y);
		    
		    $query3z ="update stfinance set rollno='$roll', sectionname='$sec' where stid='$stid' and sessionyear='$sy';";
		    $conn->query($query3z);
		    
            
       
		
		echo 'Done';
		
		
		
                            ?>