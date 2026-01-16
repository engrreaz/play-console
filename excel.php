<?php
include 'inc.php';
	
            $sql0r = "SELECT * FROM emb "; 
            $result0r = $conn->query($sql0r); if ($result0r->num_rows > 0) {while($row0r = $result0r->fetch_assoc()) { 
            $cls=$row0r["COL2"];$sec=$row0r["COL3"];$roll=$row0r["COL4"];$stname=$row0r["COL5"];
            
            $sql0r = "SELECT * FROM sessioninfo where classname='$cls' and sectionname='$sec' and rollno='$roll' and sccode='105675' and sessionyear='$sy' "; 
            $result0rx = $conn->query($sql0r); if ($result0rx->num_rows > 0) {while($row0r = $result0rx->fetch_assoc()) { 
            $stid=$row0r["stid"];}}
            
            $query3x ="update students set stnameben='$stname' where stid='$stid' ;";
		    $conn->query($query3x);
		    echo $query3x . '<br>';
            }}
            
	
	
		echo 'Done';
		
		
		
                            ?>