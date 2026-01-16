<?php
date_default_timezone_set('Asia/Dhaka');
;
$sy = date('Y');
include('inc.back.php');
;


$id = $_POST['id'];
;
$ch = $_POST['ch'];
;


if ($ch == 0) {
	$ul = 'Teacher';
	$query33 = "UPDATE usersapp set userlevel = '$ul' where id = '$id'";
	$conn->query($query33);

} else if ($ch == 1) {
	if (isset($_POST['tid'])) {
		$tid = $_POST['tid'];
		$query333 = "UPDATE usersapp set userid = '$tid' where id = '$id'";
		$conn->query($query333);
		echo 'Teacher binded with this user.';
	} else {
		$rank = $_POST['rank'];
		;
		if ($rank == 0) {
			$ul = 'Teacher';
		} else if ($rank == 1) {
			$ul = 'Administrator';
		} else if ($rank == 2) {
			$ul = 'x';
		}
		$query33 = "UPDATE usersapp set userlevel = '$ul' where id = '$id'";
		$conn->query($query33);
	}
} else if ($ch == 2) {
	$query333 = "DELETE from usersapp  where id = '$id'";
	$conn->query($query333);
}