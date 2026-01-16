<?php
include('inc.back.php');

$tid = $_POST['tid'];
$type =  $_POST['types'];
$reason = $_POST['reason'];
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$id = $_POST['id'];
$tail = $_POST['tail'];

$days = ((strtotime($date2) - strtotime($date1)) / (3600 * 24))+1;
  
// tail = 0, ADD/EDIT
// id -- 0 : add; id > 0 : edit; 
// tail = 2 , delete
// tail = 5, response......

if ($tail == 0) {
	if ($id == 0) {

		$query33 = "INSERT INTO teacher_leave_app (id, sccode, tid, apply_date, date_from, date_to, days, leave_type, leave_reason, apply_by, apply_time, status, response_by, response_time, modifieddate)
		VALUES (NULL, '$sccode', '$tid', '$td', '$date1', '$date2', '$days', '$type', '$reason', '$usr', '$cur', '0', 'NULL', 'NULL',  '$cur')";
	} else {
		$query33 = "UPDATE teacher_leave_app set leave_type = '$type', leave_reason = '$reason', date_from = '$date1', date_to = '$date2', days = '$days', modifieddate = '$cur' where sccode = '$sccode' and id='$id'";
	}
} else if($tail == 2) {
		$query33 = "DELETE FROM teacher_leave_app  where sccode = '$sccode' and id='$id'";
} else if($tail == 5) {

	// response update; .... under review that locked to modify....
	// $query33 = "DELETE FROM teacher_leave_app  where sccode = '$sccode' and id='$id'";
} else if($tail == 6) {

	// response by , time, status...
	// $query33 = "DELETE FROM teacher_leave_app  where sccode = '$sccode' and id='$id'";
}

$conn->query($query33);
echo 'submitted';
//echo $query33;
