<?php
include '../inc.light.php';
$itemcode = $_POST['itemcode'];
$cls = $_POST['class'] ?? '';
$sec = $_POST['section'] ?? '';
$slot = $_COOKIE['slot'] ?? '';

$sql = "SELECT amount, splitable FROM financesetupvalue WHERE itemcode='$itemcode' AND classname='$cls' AND sectionname='$sec' AND sccode='$sccode' AND sessionyear='$sessionyear' AND slot='$slot'";
$res = $conn->query($sql);

if ($res->num_rows) {
    echo json_encode($res->fetch_assoc());
} else {
    echo json_encode(['amount' => 0, 'splitable' => 0]);
}