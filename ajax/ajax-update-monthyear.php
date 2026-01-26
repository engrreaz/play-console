<?php
include '../inc.light.php';

$id=$_POST['id'];
$month=$_POST['month'];
$year=$_POST['year'];

$conn->query("
    UPDATE cashbook
    SET month='$month',
        year='$year'
    WHERE id='$id'
");
