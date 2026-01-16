<?php
include('inc.back.php');

$sccode = $_POST['sccode'];
$rootuser = $_POST['rootuser'];
$id = $_POST['id'];
$from = $_POST['from'];
$to = $_POST['to'];

$sql00xgr = "SELECT * FROM areas where id='$id'";
$result00xgr = $conn->query($sql00xgr);
if ($result00xgr->num_rows > 0) {
    while ($row00xgr = $result00xgr->fetch_assoc()) {
        $cls = $row00xgr["areaname"];
        $sec = $row00xgr["subarea"];
        $sy = $row00xgr["sessionyear"];
    }
}

$query3r = "update areas set rollfrom='$from' , rollto='$to' where id='$id'";
$conn->query($query3r);

$sql00xgrf = "SELECT * FROM sessioninfo where sccode='$sccode' order by stid desc LIMIT 1";
$result00xgrf = $conn->query($sql00xgrf);
if ($result00xgrf->num_rows > 0) {
    while ($row00xgrf = $result00xgrf->fetch_assoc()) {
        $lastid = $row00xgrf["stid"];
    }
} else {
    $lastid = $sccode * 10000;
}
$lastid = $lastid + 1;

for ($x = $from; $x <= $to; $x++) {

    $sql242 = "SELECT * FROM sessioninfo where sessionyear LIKE '%$sy%' and classname='$cls' and sectionname = '$sec' and rollno = '$x' and sccode='$sccode'";
    $result242 = $conn->query($sql242);
    if ($result242->num_rows > 0) {
        while ($row242 = $result242->fetch_assoc()) {
            $stid = $row242['stid'];
        }
    } else {
        $query3 = "insert into sessioninfo (id, stid, sessionyear, classname, sectionname, rollno, sccode, religion) values 	(NULL, '$lastid','$sy','$cls','$sec','$x','$sccode', 'Islam')";
        $conn->query($query3);

        $query33 = "insert into students (id, stid, sccode, religion) values (NULL, '$lastid','$sccode', 'Islam')";
        $conn->query($query33);
        $lastid = $lastid + 1;
    }
}

//////////////////////////////////////

?>

<div class="text-success pt-3" style="">
    <i class="bi bi-check2-circle"></i> ID has been generated.
</div>