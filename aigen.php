<?php
date_default_timezone_set('Asia/Dhaka');;
include 'inc.php';
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	

	$exam = $_POST['exam'];;  $sub = $_POST['sub'];;  $sccode = $_POST['sccode'];;  
	$cls = $_POST['cls'];;  $sec = $_POST['sec'];;  
	$assess = $_POST['type'];;  $topic = $_POST['topicid'];;   $areacode  = $_POST['areacode'];;   $val  = $_POST['val'];; 
	
	//echo $exam.$sub.$sccode.$cls.$sec.$assess.$topic.$areacode.$val;
	$inp = '';
	$sql00x = "SELECT rollno, stid FROM sessioninfo where  sccode='$sccode' and sessionyear='$sy' and classname='$cls' and sectionname='$sec'  order by rollno";
// 	$sql00x = "SELECT rollno, stid FROM sessioninfo where  sccode='$sccode' and sessionyear='$sy' and classname='$cls' and sectionname='$sec' and rollno between 1 and 5 order by rollno";
	//echo $sql00x;
    $result00xr = $conn->query($sql00x);
    if ($result00xr->num_rows > 0) {while($row00x = $result00xr->fetch_assoc()) {
        $roll = $row00x["rollno"];
        $stid = $row00x["stid"];
        
        $sql00xg = "Select sum(assessment) as sss, count(*) as ccc FROM pibientry WHERE sessionyear = '$sy' AND exam ='$exam' AND subcode ='$sub' AND sccode ='$sccode' AND classname ='$cls' AND sectionname = '$sec' AND assesstype ='$assess' AND topicid ='$topic' and stid='$stid'";
    $result00xrg = $conn->query($sql00xg);
    
    //echo $sql00xg;
    if ($result00xrg->num_rows > 0) {while($row00xg = $result00xrg->fetch_assoc()) {
        // $datam[] = $row00x;
        $vvv = round($row00xg["sss"] / $row00xg["ccc"]);
        $inp = $inp . $vvv;
    }}
    }}
	
    echo '<span id="pulp' . $topic . '">' . $inp . '</span>';
    
	
//	echo '<br><b>Generate Successfully.</b>';
	
                            ?>