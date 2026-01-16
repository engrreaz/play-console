<?php
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');;
	include ('../db.php');;
	
	$id = $_POST['id'];; $ch = $_POST['ch'];;
	
    $sql00xgr = "SELECT * FROM pibiprocess where id='$id'"; 
    $result00xgr = $conn->query($sql00xgr);
    if ($result00xgr->num_rows > 0) {while($row00xgr = $result00xgr->fetch_assoc()) {
    $sy=$row00xgr["sessionyear"];  $sccode=$row00xgr["sccode"];  $exam=$row00xgr["exam"];  
    $classname=$row00xgr["classname"];  $sectionname=$row00xgr["sectionname"];  $subcode=$row00xgr["subcode"];  $assess=$row00xgr["assess"];}}
    
    if($ch==1){
    $query33 ="DELETE from pibientry where sessionyear='$sy' and classname='$classname' and sectionname='$sectionname' and sccode='$sccode' and exam='$exam' and subcode='$subcode' and assesstype='Merged PI'";
	$conn->query($query33);
    } else if($ch==2){
    $query33 ="DELETE from pibientry where sessionyear='$sy' and classname='$classname' and sectionname='$sectionname' and sccode='$sccode' and exam='$exam' and subcode='100' and assesstype='Merged BI'";
	$conn->query($query33);    
    }else if($ch==3){
        
    $sql00xgrt = "SELECT * FROM areas where id='$id'"; 
    $result00xgrt = $conn->query($sql00xgrt);
    if ($result00xgrt->num_rows > 0) {while($row00xgrt = $result00xgrt->fetch_assoc()) {
    $cll=$row00xgrt["areaname"]; $see=$row00xgrt["subarea"];  $sy=$row00xgrt["sessionyear"];  $su=$row00xgrt["user"]; }}
    
    $sql00xgrt = "SELECT * FROM scinfo where rootuser='$su'"; 
    $result00xgrt = $conn->query($sql00xgrt);
    if ($result00xgrt->num_rows > 0) {while($row00xgrt = $result00xgrt->fetch_assoc()) {
    $sccode=$row00xgrt["sccode"]; }}
    
        
    $query31r = "DELETE from pibiprocess where sessionyear = '$sy' and sccode = '$sccode' and exam = '$exam' and classname = '$cll' and sectionname = '$see'";
    //echo $query31r;
    $conn->query($query31r) ;   
    }
	
	$sql00xg = "SELECT * FROM sessioninfo where sessionyear='$sy' and classname='$classname' and sectionname='$sectionname' and sccode='$sccode' and status=1 order by rollno"; 
    $result00xg = $conn->query($sql00xg);
    if ($result00xg->num_rows > 0) {while($row00xg = $result00xg->fetch_assoc()) {
    $rollno=$row00xg["rollno"]; $stid=$row00xg["stid"]; $religion=$row00xg["religion"];
    //echo $rollno;
    
        if($ch==1){   //PI Merging
            
            
			
        	$sql00x = "SELECT * FROM pibitopics where sessionyear='$sy' and class='$classname' and exam='$exam' and subcode='$subcode' order by id"; 
        // 	echo $sql00x . '<hr>';
            $result00x = $conn->query($sql00x);
            if ($result00x->num_rows > 0) {while($row00x = $result00x->fetch_assoc()) {
            $topicid=$row00x["id"];	  $acode=$row00x["pibiarea"];	 $tcode=$row00x["topiccode"];
            
            if($exam == 'Annual'){
                $sql00x = "SELECT * FROM pibitopics where sessionyear='$sy' and class='$classname' and exam='Half Yearly' and subcode='$subcode' and topiccode='$tcode' order by id"; 
                 $result00xgg = $conn->query($sql00x);
                if ($result00xgg->num_rows > 0) {while($row00x = $result00xgg->fetch_assoc()) {
                $topicidpre=$row00x["id"];}}  else { $topicidpre=0;}
            } else {
                $topicidpre=0;
            }
                // echo '/ ' .  $acode . ' \ ' . $topicid . ' % ' . $topicidpre . '*****';
            
            
            
                    $sql00 = "SELECT * FROM pibientry where sessionyear='$sy'  and subcode='$subcode' and (topicid='$topicid' || topicid='$topicidpre')  and stid='$stid' and (assesstype='Continious Assessment' or assesstype = 'Total Assessment')   order by assessment desc LIMIT 1"; 
                    //echo $sql00;
                    $result00 = $conn->query($sql00);
                    if ($result00->num_rows > 0) {while($row00 = $result00->fetch_assoc()) {
                    $assessment=$row00["assessment"];	
                    }} else {
                        $assessment=0;
                    }   
                    
                        $query330 ="INSERT into pibientry (sessionyear, exam, subcode, sccode, classname, sectionname, stid, roll, assesstype, topicid, assessment, entryby, entrytime, areacode) 
                        VALUES ('$sy', '$exam', '$subcode', '$sccode', '$classname', '$sectionname', '$stid', '$rollno', 'Merged PI', '$topicid', '$assessment', 'System-AUTO', '$dt', '$acode')";
            			$conn->query($query330); //echo $query330;
            }}
			
            
        } else if($ch == 2) {
            $tr = 0;
            $subcode = 100;
            $sql00x = "SELECT * FROM pibitopics where sessionyear='$sy' and  exam='$exam' and subcode='$subcode' order by id"; 
            $result00x = $conn->query($sql00x);
            if ($result00x->num_rows > 0) {while($row00x = $result00x->fetch_assoc()) {
            $topicid=$row00x["id"];	   $acode=$row00x["pibiarea"];	 $tcode=$row00x["topiccode"];
            
            /*
            if($exam == 'Annual'){
                $sql00x = "SELECT * FROM pibitopics where sessionyear='$sy' and class='$classname' and exam='Half Yearly' and subcode='$subcode' and topiccode='$tcode' order by id"; 
                $result00xgg = $conn->query($sql00x);
                if ($result00xgg->num_rows > 0) {while($row00x = $result00xgg->fetch_assoc()) {
                $topicidpre=$row00x["id"];}}
            } else {
                $topicidpre=$topicid;
            }
            */
            
                    $sql00 = "SELECT assessment, count(assessment) as ass FROM pibientry where sessionyear='$sy'  and topicid='$topicid'  and stid='$stid' and assesstype='Behavioural Assessment'  order by assessment desc, ass desc LIMIT 1"; 
                    $result00 = $conn->query($sql00);
                    if ($result00->num_rows > 0) {while($row00 = $result00->fetch_assoc()) {
                    $assessment=$row00["assessment"];	
                    
                        
                    }} else {
                        $assessment=0;
                    }
                    $query330 ="INSERT into pibientry (sessionyear, exam, subcode, sccode, classname, sectionname, stid, roll, assesstype, topicid, assessment, entryby, entrytime, areacode) 
                        VALUES ('$sy', '$exam', '$subcode', '$sccode', '$classname', '$sectionname', '$stid', '$rollno', 'Merged BI', '$topicid', '$assessment', 'System-AUTO', '$dt', '$acode')";
            			$conn->query($query330);            
                    $tr++;
            
            
            }}
            
            
            
        }
        
        
        
    }}

        echo ' Done !';
		
?>
