<?php
include '../inc.light.php';

$stid       = $_POST['stid'] ?? '';
$guarmobile = $_POST['guarmobile'] ?? null;
$gender     = $_POST['gender'] ?? 'Boy';
$religion   = $_POST['religion'] ?? 'Islam';
$status     = $_POST['status'] ?? 0;

if (!$stid) die("ID Missing");

// ১. students table আপডেট
$q1 = $conn->prepare("UPDATE students SET guarmobile=?, gender=?, religion=? WHERE stid=? AND sccode=?");
$q1->bind_param("ssssi", $guarmobile, $gender, $religion, $stid, $sccode);
$res1 = $q1->execute();

// ২. sessioninfo table আপডেট
$q2 = $conn->prepare("UPDATE sessioninfo SET status=? WHERE stid=? AND sccode=? AND sessionyear LIKE ?");
$q2->bind_param("isss", $status, $stid, $sccode, $sessionyear_param);
$res2 = $q2->execute();

if($res1 && $res2) {
    echo "success";
} else {
    echo "Update Failed: " . $conn->error;
}
?>