<?php
include ('inc.php');;
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');; $sy = date('Y-m-d');
	include ('../db.php');; 

	$nameeng = $_POST['nameeng'];;  $nameben = $_POST['nameben'];;  
	$fname = $_POST['fname'];;  $mname = $_POST['mname'];;  
	$vill = $_POST['vill'];;  $po = $_POST['po'];;  $ps = $_POST['ps'];;  $dist = $_POST['dist'];;  
	
	$reli = $_POST['reli'];;  $gen = $_POST['gen'];;  
	$mno = $_POST['mno'];;  $stid = $_POST['stid'];;  
	
	$admcls = $_POST['admcls'];;  $preins = $_POST['preins'];;  $taka = $_POST['taka'];;  
	
	
	
	if($stid == 0){
	    $stid = strtotime(date('YmdHis'));
	    $query33x ="INSERT INTO admission (id, sccode, stid, admdate, admby, admtime) VALUES (NULL, '$sccode', '$stid', '$sy', '$usr', '$dt')";
		$conn->query($query33x);
	}
	
		$query33 ="update admission set
		            stnameeng = '$nameeng', stnameben = '$nameben', fname = '$fname', mname = '$mname', religion='$reli', gender='$gen', previll = '$vill', prepo = '$po', preps = '$ps', predist = '$dist', guarmobile = '$mno', admclass='$admcls', preins='$preins', openingfee='$taka' where stid = '$stid' and sccode='$sccode';";
		$conn->query($query33);
// echo $query33;
//         $query34 ="update sessioninfo set  religion='$reli' where stid = '$stid' and sessionyear='$sy';";
// 		$conn->query($query34);
		
?>


