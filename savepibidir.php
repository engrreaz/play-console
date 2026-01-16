<?php
date_default_timezone_set('Asia/Dhaka');;
include 'inc.php';
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	

	$exam = $_POST['exam'];;  $sub = $_POST['sub'];;  $sccode = $_POST['sccode'];;  
	$cls = $_POST['cls'];;  $sec = $_POST['sec'];;  
	$assess = $_POST['type'];;  $topic = $_POST['topicid'];;   $areacode  = $_POST['areacode'];;   $val  = $_POST['val'];; 
	
	//echo $exam.$sub.$sccode.$cls.$sec.$assess.$topic.$areacode.$val;
	
	$sql00x = "SELECT rollno, stid FROM sessioninfo where  sccode='$sccode' and sessionyear='$sy' and classname='$cls' and sectionname='$sec' order by rollno";
	//echo $sql00x;
    $result00xr = $conn->query($sql00x);
    if ($result00xr->num_rows > 0) {while($row00x = $result00xr->fetch_assoc()) {
        $datam[] = $row00x;
    }}
	
    
    
    $query33j = "DELETE FROM pibientry WHERE sessionyear = '$sy' AND exam ='$exam' AND subcode ='$sub' AND sccode ='$sccode' AND classname ='$cls' AND sectionname = '$sec' AND assesstype ='$assess' AND topicid ='$topic'";
    //echo $query33j;
    $conn->query($query33j);
	
	$st = strlen($val);
	for($i=0; $i<$st; $i++){
    	$roll = $datam[$i]["rollno"] ;
    	$stid = $datam[$i]["stid"];
    	$pibi = substr($val,$i,1);
	    
	    $query33 ="INSERT INTO pibientry (id, sessionyear, exam, subcode, sccode, classname, sectionname, stid, roll, assesstype, topicid, areacode, assessment, entryby, entrytime)
		    VALUES(NULL, '$sy', '$exam', '$sub', '$sccode', '$cls', '$sec', '$stid', '$roll', '$assess', '$topic', '$areacode', '$pibi', '$usr', '$cur' )";
		$conn->query($query33);
	}
	
	echo '<b>Saved Successfully.</b>';
	
	/*
	$stid = $_POST['stid'];;  $roll = $_POST['roll'];;  
	$pibi = $_POST['pi'];; $eby = $_POST['usr'];;  
    
    if(isset($_POST['code'])){$topic = $_POST['code'];}
    
    $sql00x = "SELECT * FROM pibientry where  sccode='$sccode' and exam = '$exam' and sessionyear='$sy' and subcode='$sub' and stid='$stid' and assesstype='$assess' and topicid='$topic' LIMIT 1";
    $result00x = $conn->query($sql00x);
    if ($result00x->num_rows > 0) {while($row00x = $result00x->fetch_assoc()) {
        $markid=$row00x["id"];
            
        $query33 ="UPDATE pibientry set assessment = '$pibi' where id = '$markid'";
		$conn->query($query33);
    }} else {
        $query33 ="INSERT INTO pibientry (id, sessionyear, exam, subcode, sccode, classname, sectionname, stid, roll, assesstype, topicid, areacode, assessment, entryby, entrytime)
		    VALUES(NULL, '$sy', '$exam', '$sub', '$sccode', '$cls', '$sec', '$stid', '$roll', '$assess', '$topic', '$areacode', '$pibi', '$eby', '$cur' )";
		$conn->query($query33);
    }
    
    echo $query33;
*/
                            ?>