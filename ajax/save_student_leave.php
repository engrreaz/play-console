<?php
require_once "../inc.light.php";

$stid        = $_POST['stid'];
$sccode      = $_POST['sccode'];
// $sessionyear = $_POST['sessionyear'];
$date_from   = $_POST['date_from'];
$date_to     = $_POST['date_to'];
$leave_type  = $_POST['leave_type'];
$apply_by    = $_POST['apply_by'] ?? 'Parent/Self';
$apply_date  = date('Y-m-d H:i:s');

// ১. ডাইনামিকভাবে স্টুডেন্টের ক্লাস ও রোল বের করা (যদি প্রয়োজন হয়)
$stmt = $conn->prepare("SELECT classname, sectionname, rollno FROM sessioninfo WHERE stid=? AND sessionyear LIKE ?");
$stmt->bind_param("is", $stid, $sessionyear);
$stmt->execute();
$std = $stmt->get_result()->fetch_assoc();

$cls = $std['classname'] ?? '';
$sec = $std['sectionname'] ?? '';
$roll = $std['rollno'] ?? 0;

// ২. ইনসার্ট কুয়েরি
$ins = $conn->prepare("INSERT INTO student_leave_app (sccode, sessionyear, classname, sectionname, rollno, stid, date_from, date_to, apply_date, apply_by, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
$ins->bind_param("isssiissss", $sccode, $sessionyear, $cls, $sec, $roll, $stid, $date_from, $date_to, $apply_date, $apply_by);

if($ins->execute()){
    echo 'success';
} else {
    echo 'Error: ' . $conn->error;
}
?>