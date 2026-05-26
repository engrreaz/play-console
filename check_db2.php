<?php
include 'inc.light.php';
$res = $conn->query("SHOW COLUMNS FROM seat_plans");
if($res){ while($row = $res->fetch_assoc()) { print_r($row); } } else { echo $conn->error; }
?>