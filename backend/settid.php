<?php
include 'inc.back.php';


$id = $_POST['id'];
;
$tea = $_POST['tea'];
;
$s = $_POST['s'];
;
if ($s == 0) {
	$query33 = "UPDATE subsetup set tid = '$tea' where id = '$id'";
} else {
	$query33 = "UPDATE areas set classteacher = '$tea' where id = '$id'";
}
$conn->query($query33);