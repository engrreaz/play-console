<?php
include 'inc.php';
	
    $tail = $_POST['tail'];; 
    if($tail == 0){
        $cause = $_POST['cause'];; $date = $_POST['date'];  $descrip = $_POST['descrip']; 
    	$icon = strtolower($cause);
    	
    	if($cause == 'Bank Management'){$icon = 'bank';}
    	if($cause == 'Columner Cashbook'){$icon = 'cashbook';}
		
		$query33 ="insert into issue(id, category, description, deadline, issueby, issuetime, progress, status, stt, icon)
		VALUES (NULL, '$cause', '$descrip', '$date', '$usr', '$cur', '0', '', '0', '$icon');";
		$conn->query($query33); 
    } else  if($tail == 1) {
        $id = $_POST['id'];; 
        
        $sql0 = "SELECT * FROM issue where id = '$id' ";
        $result0wwrt = $conn->query($sql0);
        if ($result0wwrt->num_rows > 0) 
        {while($row0 = $result0wwrt->fetch_assoc()) { $prog = $row0["progress"];}}
        if($prog<100){
            $prog = $prog + 10;
        } else {
            $prog = 100;
        }
        
        if($prog<80){$stt = 0;} else if($prog<95){$stt = 1;} else {$stt = 2;} 
        if($prog==10){$status = 'Startup';} 
        else if($prog==20){$status = 'Under Preparing';} 
        else if($prog==30){$status = 'On Processing';} 
        else if($prog==40){$status = 'Under Building';} 
        else if($prog==50){$status = 'On Going';} 
        else if($prog==60){$status = 'On Progress';} 
        else if($prog==70){$status = 'On Test';} 
        else if($prog==80){$status = 'Done';} 
        else if($prog==90){$status = 'Tested';} 
        else if($prog==100){$status = 'Secured';} 
        else {$status = '___';}
        
        $query33 ="UPDATE issue set progress = '$prog', status='$status', stt='$stt' where id='$id';";
		$conn->query($query33); 
    }
    	









                            ?>
                            
                          
                          
                          
                          
                            
                            <script>
                                window.location.href = 'issue.php';
                            </script>