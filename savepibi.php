<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('inc.php');;
	

	$exam = $_POST['exam'];;  $sub = $_POST['sub'];;  $sccode = $_POST['sccode'];;  
	$cls = $_POST['cls'];;  $sec = $_POST['sec'];;  $stid = $_POST['stid'];;  $roll = $_POST['roll'];;  
	$assess = $_POST['assess'];;  $topic = $_POST['topic'];;  $pibi = $_POST['pi'];; $eby = $_POST['usr'];;  $areacode  = $_POST['acode'];;  
    
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
    $rollno = $roll; 
    
    if(isset($_POST['code'])){$tid = $topic;;}
   
   //************************************************************************************************************************************************
   //****************************************************************************************************************************************************************
   
    
    if($stid<100000){
        $a = $stid; $b = $stid + 10000; $c = $pibi;
        $sql00xgr = "SELECT * FROM pibigroup where id='$stid'";  
        $result00xgr = $conn->query($sql00xgr);
        if ($result00xgr->num_rows > 0) {while($row00xgr = $result00xgr->fetch_assoc()) {
            $grname=$row00xgr["groupname"];
        }}
        
        $sql00xgrv = "SELECT * FROM sessioninfo where sessionyear='$sy' and sccode='$sccode' and classname='$cls' and sectionname='$sec' and groupname='$grname' order by stid";
        $result00xgrv = $conn->query($sql00xgrv);
        if ($result00xgrv->num_rows > 0) {while($row00xgrv = $result00xgrv->fetch_assoc()) {
            $stid=$row00xgrv["stid"]; $roll=$row00xgrv["rollno"]; 
            
            $sql00xgc = "SELECT * FROM pibientry where  sccode='$sccode' and exam = '$exam' and sessionyear='$sy' and subcode='$sub' and stid='$stid' and assesstype='$assess' and topicid='$topic' LIMIT 1";
            $result00xgc = $conn->query($sql00xgc);
            if ($result00xgc->num_rows > 0) {while($row00xgc = $result00xgc->fetch_assoc()) {
                $markid=$row00xgc["id"];
                    
                $query33 ="UPDATE pibientry set assessment = '$pibi' where id = '$markid'";
        		$conn->query($query33);
            }} else {
                $query33 ="INSERT INTO pibientry (id, sessionyear, exam, subcode, sccode, classname, sectionname, stid, roll, assesstype, topicid, assessment, entryby, entrytime)
        		    VALUES(NULL, '$sy', '$exam', '$sub', '$sccode', '$cls', '$sec', '$stid', '$roll', '$assess', '$topic', '$pibi', '$eby', '$cur' )";
        		$conn->query($query33);
            }
            
        }}  
        $stid = $a; $rollno = $b; $pibi = $c;  
    }
    
    
    
    include 'pibiblock.php';
    
                            ?>