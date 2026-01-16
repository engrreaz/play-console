<?php
date_default_timezone_set('Asia/Dhaka');
;
$cur = date('Y-m-d H:i:s');
;
$sy = date('Y');
include('inc.back.php');
;


$prt = $_POST['prt'];
;


if ($prt == 0) {
    $stid = $_POST['stid'];
    ;
    $sector = $_POST['sector'];
    ;
    $rate = $_POST['rate'];
    ;
    $sql0 = "SELECT * FROM sessioninfo where sessionyear='$sy' and sccode='$sccode' and stid='$stid'";
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) {
        while ($row0 = $result0->fetch_assoc()) {
            $clscls = $row0["classname"];
        }
    }


    $realfee = 0;
    $sql0 = "SELECT * FROM financesetup where sessionyear='$sy' and sccode='$sccode' and particulareng like '%Tution Fee%'";
    $result0tt = $conn->query($sql0);
    if ($result0tt->num_rows > 0) {
        while ($row0 = $result0tt->fetch_assoc()) {
            $realfee = $row0[strtolower($clscls)];
        }
    }


    $amount = floor($realfee * $rate / 100);

    $query33 = "UPDATE sessioninfo set sector = '$sector', rate='$rate', amount='$amount' where sessionyear='$sy' and sccode='$sccode' and stid='$stid'";
    $conn->query($query33);
    $query34 = "UPDATE stfinance set payableamt = '$amount', modifieddate='$cur', modifiedby='$usr', dues='$amount'-paid where sessionyear='$sy' and sccode='$sccode' and stid='$stid' and particulareng like '%Tution Fee%'";
    $conn->query($query34);



    echo '<b>Tution Fee Applied.</b>';
} else if ($prt == 1) {
    $stid = $_POST['stid'];
    ;
    $fid = $_POST['fid'];
    ;
    $tk = $_POST['tk'];
    ;
    $prt = $_POST['prt'];
    ;
    $query34 = "UPDATE stfinance set payableamt = '$tk', modifieddate='$cur', modifiedby='$usr', dues='$tk'-paid where sessionyear='$sy' and sccode='$sccode' and stid='$stid' and id='$fid'";
    $conn->query($query34);
    echo '<b>New Setting Applied.</b>';
}