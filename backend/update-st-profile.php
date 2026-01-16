<?php
include('inc.back.php');

$nameeng = $_POST['nameeng'];
$nameben = $_POST['nameben'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$vill = $_POST['vill'];
$po = $_POST['po'];
$ps = $_POST['ps'];
$dist = $_POST['dist'];
$mno = $_POST['mno'];
$dob = $_POST['dob'];
;
$stid = $_POST['stid'];
$roll = $_POST['roll'] + 1;
$cls = $_POST['cls'];
$sec = $_POST['sec'];
;

$query33 = "update students set
		            stnameeng = '$nameeng', stnameben = '$nameben', fname = '$fname', mname = '$mname', previll = '$vill', prepo = '$po', preps = '$ps', predist = '$dist', guarmobile = '$mno', dob = '$dob' where stid = '$stid';";
if ($conn->query($query33) === TRUE) {
}

$stidgo = '';
$sql0x = "SELECT * FROM sessioninfo where classname='$cls' and sectionname='$sec' and rollno='$roll' and sessionyear LIKE '%$sy%'  and sccode='$sccode' LIMIT 1";
$result0x = $conn->query($sql0x);
if ($result0x->num_rows > 0) {
	while ($row0x = $result0x->fetch_assoc()) {
		$stidgo = $row0x["stid"];
	}
}

echo '<div id="stidgo" hidden>' . $stidgo . '</div>';