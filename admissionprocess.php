<?php
include 'inc.php';
	$mp = 1;
    $sql0r = "SELECT * FROM stmark where sessionyear='$sy' and sccode='$sccode' and classname='Six' and exam='Admission' and markobt>0 order by markobt desc "; 
    $result0r = $conn->query($sql0r); if ($result0r->num_rows > 0) {while($row0r = $result0r->fetch_assoc()) { 
    $stid=$row0r["stid"]; $id=$row0r["id"]; 
    
        $query3g ="update stmark set ca='$mp' where id='$id';";
    	$conn->query($query3g);
    	$mp++;
    }}
    
    
    
		echo 'Done';
		
		header('Location : index.php');
		
                            ?>