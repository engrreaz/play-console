<?php 

$dd1 = date('Y') . '-01-01';
$dd2 = date('Y') . '-12-31';
$datam_calendar_events = array();
$sql0 = "SELECT * FROM calendar where  sccode='$sccode' and date between '$dd1' and '$dd2' and descrip!='' order by date, id  ";
// echo $sql0;
$result0 = $conn->query($sql0);
if ($result0->num_rows > 0) {
  while ($row0 = $result0->fetch_assoc()) {
    $datam_calendar_events[] = $row0;
  }
}