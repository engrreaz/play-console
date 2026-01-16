<?php
include 'inc.php';

	$id = $_POST['id'];  $cou = $_POST['cou'];  $st = $_POST['st'];  $pri = $_POST['pri'];  $taka = $_POST['taka'];  $opt = $_POST['opt'];  
	if($cou=='true'){$cou = 1;} else {$cou = 0;}
	if($st=='true'){$st = 1;} else {$st = 0;}

		if($opt<5){
		    $query3x ="update lottery set codestatus='$cou', prizestatus='$st', prize='$pri', taka='$taka' where id='$id' ;";
		    $conn->query($query3x);
		} else {
		    
		    $query3x ="update lottery set randprize='' ;";
		    $conn->query($query3x);
		    
		    $sql00 = "SELECT * FROM lottery where  prizestatus=1 order by rand();"; 
            $result00gt = $conn->query($sql00);
            if ($result00gt->num_rows > 0) 
            {while($row00 = $result00gt->fetch_assoc()) {   
                $datam[]=$row00; 
            }}
		    
		    $sql00 = "SELECT * FROM lottery where  prizestatus=0 order by rand();"; 
            $result00gt2 = $conn->query($sql00);
            if ($result00gt2->num_rows > 0) 
            {while($row00 = $result00gt2->fetch_assoc()) {   
                $datam2[]=$row00; 
            }}
            
            $ind = 0;
            $sql00 = "SELECT * FROM lottery where  codestatus=1 order by id;"; 
            $result00gtr = $conn->query($sql00);
            if ($result00gtr->num_rows > 0) 
            {while($row00 = $result00gtr->fetch_assoc()) {   
                $id=$row00["id"]; 
                $gp = $datam[$ind]["prize"];
                $query3xx ="update lottery set randprize='$gp' where id='$id' ;";
		        $conn->query($query3xx);
		        
		        $ind++;
            }}
            $ttt = $ind;
            $ind = 0; 
            $sql00 = "SELECT * FROM lottery where  codestatus=0 order by id;"; 
            $result00gtr22 = $conn->query($sql00);
            if ($result00gtr22->num_rows > 0) 
            {while($row00 = $result00gtr22->fetch_assoc()) {   
                $id=$row00["id"]; 
                $gp = $datam2[$ind]["prize"];
                $query3xx ="update lottery set randprize='$gp' where id='$id' ;";
		        $conn->query($query3xx);
		        
		        $ind++;
            }}
            
            $ttt += $ind;
		    
		    
		    
		    echo 'Generated ' . $ttt . ' Lottery';
		}
		

		echo '*';
		
		
		
                            ?>