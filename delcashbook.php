<?php
    include 'inc.light.php';

    $id = $_POST['id'];   $tail = $_POST['tail'];  
    
    if($tail == 1){
        $query3g ="delete from cashbook where id='$id' ; "; //echo $query3g. '<br>';
        $conn->query($query3g);
        echo 'Deleted';
    } else if($tail == 2){
        
        $query3g ="update cashbook set sccode = sccode/10 where id='$id' ; "; //echo $query3g. '<br>';
        $conn->query($query3g);
        echo 'Bill Accepted';
    }
        
		
	?>	
		