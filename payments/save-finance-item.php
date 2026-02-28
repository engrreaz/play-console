<?php
include '../inc.light.php'; // DB কানেকশন এবং $sccode এখানে আছে
header('Content-Type: application/json');

$id    = intval($_POST['id']);
$eng   = $_POST['eng'];
$ben   = $_POST['ben'];
$mon   = $_POST['mon'];
$new   = intval($_POST['new_only']);
$split = intval($_POST['splitable']);

$sy   = $_COOKIE['session'] ?? $sy;

$slot = $_COOKIE['chain-slot'] ?? '';
$sy = $_COOKIE['chain-session'] ;

if ($id == 0) {
    // নতুন আইটেম তৈরি
    $uid = 99; // ইউনিক আইটেম কোড
    $icode = uniqid();
    $sql = "INSERT INTO financesetup (sccode, sessionyear, particulareng, particularben, month, new_only, splitable, itemcode, slno, slot) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssiiisis", $sccode, $sy, $eng, $ben, $mon, $new, $split, $icode, $uid, $slot);
} else {
    // বিদ্যমান আইটেম আপডেট
    $sql = "UPDATE financesetup SET particulareng=?, particularben=?, month=?, new_only=?, splitable=? WHERE id=? AND sccode=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiiii", $eng, $ben, $mon, $new, $split, $id, $sccode);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Item synchronized successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}
$stmt->close();