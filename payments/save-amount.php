<?php
include '../inc.light.php';
header('Content-Type: application/json');

$aitemcode = $_POST['fitemcode'];
$cls = $_POST['class'] ?? '';
$sec = $_POST['section'] ?? '';
$amt = floatval($_POST['amount']);
$spl = intval($_POST['spl']);


$sy = $_COOKIE['chain-session'];
$slot = $_COOKIE['chain-slot'] ?? '';


// বিদ্যমান রেকর্ড চেক করা
$check = $conn->prepare("SELECT id FROM financesetupvalue WHERE itemcode=? AND classname=? AND sectionname=? AND sessionyear=? AND slot=? AND sccode=?");
$check->bind_param("sssssi", $aitemcode, $cls, $sec, $sy, $slot, $sccode);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    // আপডেট
    $rowId = $res->fetch_assoc()['id'];
    $sql = "UPDATE financesetupvalue SET amount=?, splitable=?, modifieddate=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("disi", $amt, $spl, $cur, $rowId);
} else {
    // নতুন ইনসার্ট
    $sql = "INSERT INTO financesetupvalue (classname, sectionname, amount, sessionyear, slot, sccode, itemcode, splitable, modifieddate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisssss", $cls, $sec, $amt, $sy, $slot, $sccode, $aitemcode, $spl, $cur);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Amount updated!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed.']);
}
$stmt->close();