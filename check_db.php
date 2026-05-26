<?php
include 'inc.light.php';
$res = $conn->query("SHOW COLUMNS FROM seat_plan_allocations");
while($row = $res->fetch_assoc()) { print_r($row); }
?>