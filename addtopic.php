<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	
	if(isset($_POST['id'])){
	    $id = $_POST['id'];;  $title = $_POST['title'];;  $l1 = $_POST['l1'];;  $l2 = $_POST['l2'];;  $l3 = $_POST['l3'];;  
        $query33 ="UPDATE pibitopics set topictitle='$title', level1='$l1', level2='$l2', level3='$l3' where id = '$id' ";
		$conn->query($query33);   echo $query33 . "UPDATED...........................................";
	} else {
	    $cls = $_POST['cls'];;  $sub = $_POST['sub'];;  $topic = $_POST['topic'];; 
	    
	    if(strlen($topic)==3){
	        $t1 = floor($topic/100);
	    $t2 = floor(($topic - (floor($topic/100) * 100))/10);
	    $t3 = $topic % 10;
	    $topic= $t1 . '.' . $t2 . '.' . $t3;
	    }
	    
	    
	    
	  
	        $sql0 = "SELECT * FROM pibitopics where class = '$cls' and subcode = '$sub' and topiccode = '$topic' and exam='Annual'";
                $result0 = $conn->query($sql0);
                if ($result0->num_rows > 0) 
                {while($row0 = $result0->fetch_assoc()) { 
                    $id = $row0["id"];  $title = $row0["topictitle"];  $level1 = $row0["level1"];  $level2 = $row0["level2"];  $level3 = $row0["level3"];  
             
                    
                ?>
                    <input type="text" id="id" value="<?php echo $id;?>" />
                    <br>
                    <input type="text" id="title" size="50"  value="<?php echo $title;?>"/>
                    <br>
                    <input type="text" id="l1"  size="50"  value="<?php echo $level1;?>"/>
                    <br>
                    <input type="text" id="l2"  size="50"  value="<?php echo $level2;?>"/>
                    <br>
                    <input type="text" id="l3"  size="50"  value="<?php echo $level3;?>" />
                    <br><br>
                <?php 
         
                }}
	     else {
            $query33 ="insert into pibitopics (sessionyear, class, exam, subcode, topiccode, total) values
            ('2023', '$cls', 'Annual', '$sub', '$topic', 1);";
    		$conn->query($query33);   echo "ADD Success -- ";
    		
    		$sql0 = "SELECT count(*) as ddx FROM pibitopics where class = '$cls' and subcode = '$sub' and  exam='Annual'"; // echo $sql0;
                $result0s = $conn->query($sql0);
                if ($result0s->num_rows > 0) 
                {while($row0 = $result0s->fetch_assoc()) { 
                    $ddx = $row0["ddx"];  
                }}
    		echo $ddx;
	    }
	    
	}



	    
		?>