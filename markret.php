<?php
date_default_timezone_set('Asia/Dhaka');;  include 'incc.php';
$cur = date('Y-m-d H:i:s');; //$sy = date('Y');
	include ('../db.php');;
	$etdt = date('Y-m-d H:i:s');;
	
	$sccode=$_POST['sccode'];;
	$stcount=$_POST['stcnt'];;
	$start=$_POST['start'];;
	$end = $stcount;

	$cn=$_POST['classname'];;
	$secname=$_POST['sectionname'];;
	$exam=$_POST['exam'];;
	$part=$_POST['part'];; // data tail for whole process or partial
	
	$allfourth = '';
	$sql22vle="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname' and  sccode = '$sccode' order by subject " ;

	$result22vle = $conn->query($sql22vle);
	if ($result22vle->num_rows > 0) 
		{while($row22vle = $result22vle->fetch_assoc()) 
			{
			$subject = $row22vle["subject"] ;
            $sql22vlh="SELECT * FROM subjects where subcode ='$subject' " ;
            $result22vlh = $conn->query($sql22vlh);
            if ($result22vlh->num_rows > 0) 
            {while($row22vlh = $result22vlh->fetch_assoc()) 
            {
            $fourth = $row22vlh["fourth"] ;
                if($fourth == 1){
                    $allfourth = $allfourth . $subject;
                }
            }}
	}}
	
	
// 	if($part == 0)
	{
	
// 	//******************************************************************************************************************************
	    include 'result-process/clear-previous.php';
	    include 'result-process/insert-student-list.php';
	    include 'result-process/retrive-subject-list.php';
	//******************************************************************************************************************************************************************
	}


	
	//************************************
	//************************************
	//************************************
	//************************************
	//************************************

//***************************************************************************************************************************************************************
echo '<div id="ccc">' . $start . '</div>';
?>