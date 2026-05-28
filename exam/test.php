<?php
include '../inc.light.php';
$res = $conn->query("SELECT * FROM invigilators LIMIT 1");
print_r($res->fetch_assoc());
