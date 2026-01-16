<?php
date_default_timezone_set('Asia/Dhaka');
;
$cur = date('Y-m-d H:i:s');
;
include 'incc.php';
include ('../db.php');
;
$sessionyear = $sy;
;
$sccode = $_POST['sccode'];
;
$stcount = $_POST['stcnt'];
;
$cn = $_POST['classname'];
;
$secname = $_POST['sectionname'];
;
$exam = $_POST['exam'];
;
$etdt = date('Y-m-d H:i:s');
;
$ed = '';
$sql22vq = "SELECT * FROM holiday where  sccode = '$sccode' and hdtype='Exam' order by date";
$result22vq = $conn->query($sql22vq);
if ($result22vq->num_rows > 0) {
    while ($row22vq = $result22vq->fetch_assoc()) {
        $examdate = $row22vq["date"];
        $ed = $ed . $examdate;
    }
}


$dexam = substr($exam, 0, 1);
if ($dexam == "1") {
    $efrom = date('Y') . '-01-01';
    $eto = substr($ed, ($dexam - 1) * 10, 10);
} else {
    $efrom = substr($ed, ($dexam - 1) * 10, 10);
    $eto = substr($ed, ($dexam - 1) * 10, 10);
}


$sql22vr = "SELECT count(adate) as wd FROM stattnd where  sccode = '$sccode' and yn='1' and adate between '$efrom' and '$eto' group by adate";
$result22vr = $conn->query($sql22vr);
if ($result22vr->num_rows > 0) {
    while ($row22vr = $result22vr->fetch_assoc()) {
        $twday = $row22vr["wd"];
    }
}
$twday = $twday - 1;



//************************************************************************************ END OF ATTENDANCE COUNT ********************************************


$sql22v = "SELECT * FROM sessioninfo where classname ='$cn' and sectionname='$secname' and sessionyear ='$sessionyear' and sccode = '$sccode' order by rollno";
$result22v = $conn->query($sql22v);
if ($result22v->num_rows > 0) {
    while ($row22v = $result22v->fetch_assoc()) {
        $stid = $row22v["stid"];
        $rollno = $row22v["rollno"];
        $fourth = $row22v["fourth_subject"];

        $sql22vr = "SELECT * from tabulatingsheet where  classname ='$cn' and sectionname='$secname' and sessionyear ='$sessionyear' and sccode = '$sccode' and stid='$stid' ";
        $result22vr = $conn->query($sql22vr);
        if ($result22vr->num_rows > 0) {
            while ($row22vr = $result22vr->fetch_assoc()) {
                $allfourth = $row22vr["allfourth"];
            }
        }

        $u1 = substr($allfourth, 0, 3) * 1;
        $u2 = substr($allfourth, 3, 3) * 1;
        $u3 = substr($allfourth, 6, 3) * 1;
        $u4 = substr($allfourth, 9, 3) * 1;

        //echo $stid;
        $ben_sub = 0;
        $ben_obj = 0;
        $ben_pra = 0;
        $ben_ca = 0;
        $ben_total = 0;
        $ben_gp = 0;
        $ben_gl = '';
        $eng_sub = 0;
        $eng_obj = 0;
        $eng_pra = 0;
        $eng_ca = 0;
        $eng_total = 0;
        $eng_gp = 0;
        $eng_gl = '';
        $ben_fullmarks = 0;
        $eng_fullmarks = 0;
        $sss = 0;
        $ooo = 0;
        $ppp = 0;
        $ccc = 0;
        $ssss = 0;
        $oooo = 0;
        $pppp = 0;
        $cccc = 0;


        $totalfullmarks = 0;
        $totalmarks = 0;
        $tfail = 0;
        $totalgp = 0;
        $totalsubject = 0;

        include 'count_total_avg.php';

        //echo '<script> alert(' . $totalgp . '|' . $totalsubject . ');</script>';


        $avgrate = $totalmarks * 100 / $totalfullmarks;
        if ($tfail == 0) {
            $gpa = $totalgp / $totalsubject;
            if ($gpa > 5) {
                $gpa = 5;
            }
            $sql22vnh = "SELECT * FROM gpa where gp<='$gpa' order by gp desc limit 0, 1  ";
            $result22vnh = $conn->query($sql22vnh);
            if ($result22vnh->num_rows > 0) {
                while ($row22vnh = $result22vnh->fetch_assoc()) {
                    $gla = $row22vnh["gl"];

                }
            }
        } else {
            $gla = 'F';
            $gpa = 0;
        }



        //update goes here...............................
        $tms = 0;
        $totaltotal = $totalmarks;


        $sql22vnhg = "SELECT totalmarks FROM tabulatingsheet where classname ='$cn' and sectionname='$secname' and sessionyear ='$sessionyear' and sccode = '$sccode' and rollno='$rollno' and exam='Half Yearly' ";
        $result22vnhg = $conn->query($sql22vnhg);
        if ($result22vnhg->num_rows > 0) {
            while ($row22vnhg = $result22vnhg->fetch_assoc()) {
                $tms = $row22vnhg["totalmarks"];
            }
        } else {
            $tms = 0;
        }


        $totaltotal = round(($totalmarks + $tms) / 2);



        //$fs = $u1 . $u2 . $u3 . $u4;
        
        $query334x = "UPDATE tabulatingsheet SET 
								prevexam = '$tms', thisexam = '$totalmarks', 
								totalmarks = '$totaltotal', avgrate = '$avgrate', gpa = '$gpa', gla='$gla', totalfail='$tfail'	, full_marks = '$totalfullmarks',
								 totalsubject = '$totalsubject', failsub = '$fs'
								WHERE sessionyear='$sessionyear' and exam='$exam' and stid='$stid'  ";
        echo $query334x;
        
        
        
        
        $query334 = "UPDATE tabulatingsheet SET 
								prevexam = '$tms', thisexam = '$totalmarks', 
								totalmarks = '$totaltotal', avgrate = '$avgrate', gpa = '$gpa', gla='$gla', totalfail='$tfail'	, full_marks = '$totalfullmarks',
								ben_sub = '$ben_sub', ben_obj = '$ben_obj', ben_pra = '$ben_pra', ben_ca = '$ben_ca', ben_total = '$ben_total', ben_gp = '$ben_gp', ben_gl = '$ben_gl',
								eng_sub = '$eng_sub', eng_obj = '$eng_obj', eng_pra = '$eng_pra', eng_ca = '$eng_ca', eng_total = '$eng_total', eng_gp = '$eng_gp', eng_gl = '$eng_gl',
								totalgp = '$totalgp', totalsubject = '$totalsubject', failsub = '$fs'
								WHERE sessionyear='$sessionyear' and exam='$exam' and stid='$stid'  ";
        echo $query334;
        
        ?>
        <script>console.log("active");</script>
        <?php
        if ($conn->query($query334) === TRUE) {
            echo '';
        } else {
            echo '';
        }
    }
}


//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************







///********************************************************************************************





$cn2 = '%' . substr($cn, 0, 3) . '%';
$mp = 1;
//Combined Merit List
// 	$sql22vnp="SELECT * FROM tabulatingsheet where classname LIKE '$cn'  and sessionyear ='$sessionyear' and exam='$exam' and sccode='$sccode' order by totalfail, thisexam desc" ;
//Indivisual Meril List
$sql22vnp = "SELECT * FROM tabulatingsheet where classname LIKE '$cn'  and sessionyear ='$sessionyear' and exam='$exam' and sccode='$sccode' and sectionname='$secname' order by totalfail, thisexam desc";
//Boys/Girl Merit List
// $sql22vnp="SELECT * FROM tabulatingsheet where classname LIKE '$cn'  and sessionyear ='$sessionyear' and exam='$exam' and sccode='$sccode' and gender='Girl' order by totalfail, totalmarks desc" ;

//if($sccode=='103187'){
//    $sql22vnp="SELECT * FROM tabulatingsheet where classname LIKE '$cn'  and sessionyear ='$sessionyear' and exam='$exam' and sccode='$sccode' and sectionname='$secname' order by totalfail, totalmarks desc" ;
//}

echo $sql22vnp;

$result22vnp = $conn->query($sql22vnp);
if ($result22vnp->num_rows > 0) {
    while ($row22vnp = $result22vnp->fetch_assoc()) {
        $tf = $row22vnp["totalfail"];
        $tm = $row22vnp["totalmarks"];
        $stid = $row22vnp["stid"];

        $sql22vnhx = "SELECT * FROM meritlist where numplace = '$mp'   ";
        $result22vnhx = $conn->query($sql22vnhx);
        if ($result22vnhx->num_rows > 0) {
            while ($row22vnhx = $result22vnhx->fetch_assoc()) {
                $mpla = $row22vnhx["meritplace"];
            }
        }

        $sql22vnhxc = "SELECT sum(yn) as attcnt FROM stattnd  where  sccode = '$sccode' and yn='1' and adate between '$efrom' and '$eto' and stid='$stid'";
        $result22vnhxc = $conn->query($sql22vnhxc);
        if ($result22vnhxc->num_rows > 0) {
            while ($row22vnhxc = $result22vnhxc->fetch_assoc()) {
                $upo = $row22vnhxc["attcnt"];
            }
        } else {
            $upo = 0;
        }

        $upo = 0;
        $mpla = $twday = $upo = 333;
        $query3348 = "UPDATE tabulatingsheet SET 
								meritplace = '$mpla' , twday = '$twday'	, attnd = '$upo'							
								WHERE sessionyear='$sessionyear' and exam='$exam' and stid='$stid'  "; //echo $query3348;
        $conn->query($query3348);

        //	echo '<br><br>';
        $mp = $mp + 1;





    }
}

//include 'bhs105532.php';
?>

<span class="button cycle-button info"><span class="mif-right-arrow"></span> </span> <span class="fg-emerald"> Calculation Complete Successfully</span>