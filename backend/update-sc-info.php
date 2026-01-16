<?php
include('inc.back.php');
;

$dt = date('Y-m-d H:i:s');
;

$sccode = $_POST['sccode'];
;
$scname = str_replace("'", "&apos;", $_POST['scname']);
;
;
$add1 = $_POST['add1'];
;
$add2 = $_POST['add2'];
;
$ps = $_POST['ps'];
;
$dist = $_POST['dist'];
;
$mno = $_POST['mno'];
;
$pth = 'iimg/logo.png';
$query33 = "update scinfo set
		            scname = '$scname', scadd1 = '$add1', scadd2 = '$add2', ps = '$ps', dist = '$dist', mobile = '$mno', modifieddate = '$dt' where sccode = '$sccode'";
$conn->query($query33);

//echo $query33;
