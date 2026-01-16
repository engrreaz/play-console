<?php
include('inc.back.php');
date_default_timezone_set('Asia/Dhaka');

$sessionyear = date("Y");

$sccode = $_POST['sccode'];
$usr = $_POST['usr'];
$cn = $_POST['cls'];
$secname = $_POST['sec'];
$exam = $_POST['exam'];
$subname = $_POST['sub'];
$fullmark = $_POST['fm'];
$iii = $_POST['stid'];
$subj = $_POST['subj'];
$obj = $_POST['obj'];
$pra = $_POST['pra'];
$ca = $_POST['ca'];
// 	$ca = $_POST['ccc'.$i];

$etdt = date('Y-m-d H:i:s');

$ratio = 1;
if ($subname == 850) {
	$ratio = 0.1;
}

$realsubjobj = $subj + $obj;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql22v = "SELECT * FROM subsetup where classname ='$cn'  and sectionname='$secname' and subject ='$subname' and sccode = '$sccode' and sessionyear='$sessionyear' ";
// 	echo $sql22v;
$result22v = $conn->query($sql22v);
if ($result22v->num_rows > 0) {
	while ($row22v = $result22v->fetch_assoc()) {
		$fullmark = $row22v["fullmarks"];
		$careal = $row22v["ca"];
		$checkcamanual = $row22v["camanual"];
		$pass_algorithm = $row22v["pass_algorithm"];
		$subj_full = $row22v["subj"];
		$obj_full = $row22v["obj"];
		$pra_full = $row22v["pra"];
		$ca_full = $row22v["ca"];
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 			echo $pass_algorithm . '//';

$calc = (100 - $ca_full) / 100;
$sub_final = $subj * $calc;
$obj_final = $obj * $calc;
$pra_final = $pra * $calc;
// $ca = 0;

$yn = $sub_final + $obj_final + $pra_final + $ca;

// echo $yn . '/' . $fullmark;
if ($fullmark > 0) {
	$ynt = 100 * $yn / $fullmark;
} else {
	$ynt = 0;
}

//***************************************************************************************************************	
if ($pass_algorithm == 0) {

	if ($ynt >= 33) {
		$sql22 = "SELECT * FROM gpa where minvalues <='$ynt'  order by minvalues DESC  LIMIT 0,1";
		// echo $sql22;
		$result22 = $conn->query($sql22);
		if ($result22->num_rows > 0) {
			while ($row22 = $result22->fetch_assoc()) {
				$gp = $row22["gp"];
				$gl = $row22["gl"];
			}
		}
	} else {
		$gp = 0;
		$gl = 'F';
	}
} else if ($pass_algorithm == 2) {
	if ($realsubjobj * 100 / $fullmark >= 33) {

		if ($ynt >= 33) {
			$sql22 = "SELECT * FROM gpa where maxvalues >='$ynt'  order by maxvalues LIMIT 0,1";
			// echo $sql22;
			$result22 = $conn->query($sql22);
			if ($result22->num_rows > 0) {
				while ($row22 = $result22->fetch_assoc()) {
					$gp = $row22["gp"];
					$gl = $row22["gl"];
				}
			}
		} else {
			$gp = 0;
			$gl = 'F';
		}

	} else {
		$gp = 0;
		$gl = 'F';
	}

} else {
	if (($subj < floor($subj_full * 33 / 100)) || ($obj < floor($obj_full * 33 / 100)) || ($pra < floor($pra_full * 33 / 100))) {

		$gp = 0;
		$gl = 'F';
	} else {
		$sql22 = "SELECT * FROM gpa where maxvalues >='$ynt'  order by maxvalues LIMIT 0,1";
		$result22 = $conn->query($sql22);
		if ($result22->num_rows > 0) {
			while ($row22 = $result22->fetch_assoc()) {
				$gp = $row22["gp"];
				$gl = $row22["gl"];
			}
		}
	}

}
//***************************************************************************************************************	


//************************************************************************************************
$sql0 = "SELECT * FROM stmark where sessionyear='$sessionyear' and exam='$exam' and subject='$subname' and stid='$iii' ";
//echo $sql0;
$result0 = $conn->query($sql0);
if ($result0->num_rows > 0) {

	$query33 = "UPDATE stmark SET 
            subj = '$subj', obj = '$obj', pra = '$pra', ca = '$ca', 
            markobt = '$yn', gp = '$gp', gl = '$gl', sub_final = '$sub_final', obj_final = '$obj_final', pra_final = '$pra_final',
            
            entryby = '$usr' ,  modifieddate = '$etdt'
            
            WHERE sessionyear='$sessionyear' and exam='$exam' and subject='$subname' and stid='$iii'  ";

} else {

	$query33 = "insert into stmark
            (id, sessionyear, sccode, exam, classname, sectionname, subject, fullmark, stid, markobt, gp, gl,  entryby, entrydate, subj, obj, pra, ca, sub_final, obj_final, pra_final, modifieddate)
            values 	(NULL, '$sessionyear', '$sccode', '$exam', '$cn', '$secname', '$subname', '$fullmark', '$iii','$yn','$gp','$gl', '$usr','$etdt', '$subj', '$obj', '$pra', '$ca'	, '$sub_final', '$obj_final', '$pra_final', '$etdt')";

}
// echo $query33;
$conn->query($query33);
//**********************************************************************************************

$query333 = "insert into markmodify (id, sessionyear, sccode, exam, classname, sectionname, dtmodify)
                            values 	(NULL, '$sessionyear', '$sccode', '$exam', '$cn', '$secname', '$etdt'	)";
$conn->query($query333);

echo $gp . ' / ' . $gl;