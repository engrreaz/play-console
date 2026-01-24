<?php // ajax_rolls.php
include '../inc.light.php';
$slot = $_GET['slot'];
$session = $_GET['session'];
$class = $_GET['class'];
$section = $_GET['section'];

$res = $conn->query(
  "SELECT si.stid, si.rollno, st.stnameeng
   FROM sessioninfo si
   LEFT JOIN students st ON st.stid=si.stid
   WHERE si.slot='$slot' AND si.sessionyear='$session' AND si.classname='$class' AND si.sectionname='$section'
   ORDER BY si.rollno"
);
$out=[];
while($r=$res->fetch_assoc()) $out[]=$r;
header('Content-Type: application/json');
echo json_encode($out);
