<?php
include '../inc.light.php';
header('Content-Type: application/json');

// ডিলিট লজিক
if (isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];
    $conn->query("DELETE FROM notice WHERE id = $id AND sccode = '$sccode'");
    echo json_encode(['status' => 'success']);
    exit;
}

// সেভ/আপডেট লজিক
$id = (int) $_POST['id'];
$title = $_POST['title'];
$category = $_POST['category'];
$descrip = $_POST['descrip'];
$expdate = $_POST['expdate'];
$teacher = isset($_POST['teacher']) ? 1 : 0;
$smc = isset($_POST['smc']) ? 1 : 0;
$guardian = isset($_POST['guardian']) ? 1 : 0;

if ($id == 0) {
    // INSERT
    $sql = "INSERT INTO notice (sccode, category, title, descrip, expdate, teacher, smc, guardian, entryby, entrytime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $now = date('Y-m-d H:i:s');
    $stmt->bind_param("issssiiiss", $sccode, $category, $title, $descrip, $expdate, $teacher, $smc, $guardian, $usr, $now);
} else {
    // UPDATE
    $sql = "UPDATE notice SET category=?, title=?, descrip=?, expdate=?, teacher=?, smc=?, guardian=? WHERE id=? AND sccode=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiiiii", $category, $title, $descrip, $expdate, $teacher, $smc, $guardian, $id, $sccode);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}
$stmt->close();