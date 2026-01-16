<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	$etdt = date('Y-m-d H:i:s');;
	
	$sccode=$_POST['sccode'];;
	$stcount=$_POST['stcnt'];;
	$start=$_POST['start'];;
	$end = $stcount;

	$cn=$_POST['classname'];;
	$secname=$_POST['sectionname'];;
	$exam=$_POST['exam'];;
	
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
	
	
	$tp="DELETE  from tabulatingsheetpibi where classname ='$cn'  and sectionname='$secname' and sessionyear ='$sy' and sccode = '$sccode' and exam='$exam' and roll between '$start' and '$start'  " ;
    $conn->query($tp);
    //echo $tp;
	
	$sql22v="SELECT * FROM sessioninfo where classname ='$cn' and sectionname='$secname' and sessionyear ='$sy' and sccode = '$sccode' and  rollno = '$start' " ;
	//$sql22v="SELECT * FROM sessioninfo where classname ='$cn' and sectionname='$secname' and sessionyear ='$sy' and sccode = '$sccode' and  status = 1 order by rollno" ;
	//echo $sql22v;
	$result22v = $conn->query($sql22v);
			if ($result22v->num_rows > 0) 
				{while($row22v = $result22v->fetch_assoc()) 
					{
                            $stid = $row22v["stid"] ;
                            //************************************************************************
                            $sql22vx="SELECT * FROM students where stid = '$stid'" ;
                            $result22vx = $conn->query($sql22vx);
                            if ($result22vx->num_rows > 0) 
                            {while($row22vx = $result22vx->fetch_assoc()) 
                            {
                            $gender = $row22vx["gender"] ;}}
					        //************************************************************************
					
					$rollno = $row22v["rollno"] ;
					
					$query33 ="insert into tabulatingsheetpibi
							(id, sessionyear, sccode, exam, classname, sectionname, stid, roll)
					values 	(NULL, '$sy', '$sccode', '$exam', '$cn', '$secname', '$stid', '$rollno')"; //echo $query33;
					$conn->query($query33);
					}}
	
	//************************************
	//************************************
	//************************************
	//************************************
	//************************************
	
	
        $sql22vlf="SELECT topicid, areacode, assessment FROM pibientry where sessionyear='$sy' and exam='$exam' and sccode='$sccode' and classname ='$cn' and sectionname='$secname' and stid='$stid' and  roll = '$start' and assesstype = 'Merged PI' order by id" ;
        //echo $sql22vlf;
        $result22vlfm = $conn->query($sql22vlf);
        if ($result22vlfm->num_rows > 0) {while($row22vlf = $result22vlfm->fetch_assoc()) {
        $datam[] = $row22vlf;
        }}
        
        echo var_dump($datam);
	
	
	
	
	
	
	
	
	
	
	//************************************
	//************************************
	//************************************
	//************************************
	//************************************
	
	$col = 1; $tp=0; $gp = 0;
	for($lup = 901; $lup<=911; $lup++){
	    
	    $fld = 'pi' . $col;
        $query3340 = "UPDATE tabulatingsheetpibi SET $fld = '$lup' where sessionyear='$sy' and exam='$exam' and sccode='$sccode' and classname ='$cn' and sectionname='$secname' and stid='$stid' and  roll = '$start' ;";
        //echo $query334; 
        $conn->query($query3340);
	    $col++;
	    
        $sql22vl="SELECT * FROM pibitopics where class ='$cn' and sessionyear='$sy' and subcode='$lup' and  exam = '$exam' order by id " ;
        //echo $sql22vl;
        $result22vl = $conn->query($sql22vl);
        if ($result22vl->num_rows > 0) {while($row22vl = $result22vl->fetch_assoc()) {
        $tcode = $row22vl["id"] ; $acode = $row22vl["pibiarea"] ;

        
        /*
            $sql22vlf="SELECT * FROM pibientry where sessionyear='$sy' and exam='$exam' and subcode='$lup' and sccode='$sccode' and classname ='$cn' and sectionname='$secname' and stid='$stid' and  roll = '$start' and topicid='$tcode' and assesstype = 'Merged PI' LIMIT 1" ;
            //echo $sql22vlf;
            $result22vlf = $conn->query($sql22vlf);
            if ($result22vlf->num_rows > 0) {while($row22vlf = $result22vlf->fetch_assoc()) {
            $fol = $row22vlf["assessment"] ;   //$acode = $row22vlf["areacode"] ;
            }} else {$fol = 0;} 
        */    
            $assm = array_search($tcode, array_column($datam, "topicid"));
            if($assm != ''){
                $fol = $datam[$assm]["assessment"];
            } else {
                $fol = 0;
            }

        
            
            
            $tp = $tp + 3 ; $gp = $gp + $fol;
        
        $fld = 'pi' . $col;
        
        $hfld = 'h' . $acode;
        $lfld = 'l' . $acode;
        if($fol == 3){$hh = 1; $ll = 0;} else if($fol == 1){$hh = 0; $ll = 1;} else {$hh = 0; $ll = 0;}
        $query334 = "UPDATE tabulatingsheetpibi SET $fld = '$fol', $hfld=$hfld + $hh, $lfld = $lfld + $ll where sessionyear='$sy' and exam='$exam' and sccode='$sccode' and classname ='$cn' and sectionname='$secname' and stid='$stid' and  roll = '$start' ;";
        $conn->query($query334);
        $col++;
        }}
	}
	
	
	
	
	
	
	
	$col = $col;
	$fld = 'pi' . $col;
    $query3340 = "UPDATE tabulatingsheetpibi SET $fld = '100' where sessionyear='$sy' and exam='$exam' and sccode='$sccode' and classname ='$cn' and sectionname='$secname' and stid='$stid' and  roll = '$start' ;";
    //echo $query334; 
    $conn->query($query3340);
    $col++;
	    
    	   
	for($lup = 100; $lup<=100; $lup++){
        $sql22vl2="SELECT * FROM pibitopics where sessionyear='$sy' and subcode='$lup' and  exam = '$exam' order by id " ;
        //echo $sql22vl2;
        $result22vl2 = $conn->query($sql22vl2);
        if ($result22vl2->num_rows > 0) {while($row22vl2 = $result22vl2->fetch_assoc()) {
        $tcode = $row22vl2["id"] ; $acode = $row22vl2["pibiarea"] ;
            $sql22vlf2="SELECT * FROM pibientry where sessionyear='$sy' and exam='$exam' and subcode='$lup' and sccode='$sccode' and classname ='$cn' and sectionname='$secname' and stid='$stid' and  roll = '$start' and topicid='$tcode' and assesstype = 'Merged BI' LIMIT 1" ;
            //echo $sql22vlf2;
            $result22vlf2 = $conn->query($sql22vlf2);
            if ($result22vlf2->num_rows > 0) {while($row22vlf2 = $result22vlf2->fetch_assoc()) {
            $fol = $row22vlf2["assessment"] ;  //$acode = $row22vlf["areacode"] ;
            }} else {$fol = 0;}
            $tp = $tp + 3 ; $gp = $gp + $fol;
        $fld = 'pi' . $col;
        
         $hfld = 'h' . $acode;
        $lfld = 'l' . $acode;
        if($fol == 3){$hh = 1; $ll = 0;} else if($fol == 1){$hh = 0; $ll = 1;} else {$hh = 0; $ll = 0;}
        $query3344 = "UPDATE tabulatingsheetpibi SET $fld = '$fol' , $hfld=$hfld + $hh, $lfld = $lfld + $ll  where sessionyear='$sy' and exam='$exam' and sccode='$sccode' and classname ='$cn' and sectionname='$secname' and stid='$stid' and  roll = '$start' ;";
        //echo $query3344; 
        $conn->query($query3344);
        $col++;
        }}
	}
	
	$rate = floor($gp*100/$tp);
	$query1 = "UPDATE tabulatingsheetpibi SET rate = '$rate' where sessionyear='$sy' and exam='$exam' and sccode='$sccode' and classname ='$cn' and sectionname='$secname' and stid='$stid' and  roll = '$start' ;";
    $conn->query($query1);
	
	/**/

    //echo '<div id="ccc' . $start . '">' . $start . '</div>';
    echo '<div id="ccc">' . $start . '</div>';
    $next = $start + 1;
    
    $etdt2 = date('Y-m-d H:i:s');;
    echo strtotime($etdt2) - strtotime($etdt);
?>



<script>
    markret2(<?php echo $next;?>);
</script>