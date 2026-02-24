<?php
include_once '../inc.light.php';

$id = $_POST['id'] ?? '';
$slotname = $_POST['slotname'];
$merit = $_POST['merit'];
$parents = $_POST['parents'];
$reqin = $_POST['reqin'];
$reqout = $_POST['reqout'];
$eng = isset($_POST['trans_name_eng']) ? 1 : 0;
$ben = isset($_POST['trans_name_ben']) ? 1 : 0;

if (!empty($id)) {
    // Update Logic
    $stmt = $conn->prepare("UPDATE slots SET slotname=?, merit=?, parents=?, reqin=?, reqout=?, trans_name_eng=?, trans_name_ben=? WHERE id=? AND sccode=?");
    $stmt->bind_param("sisssiiii", $slotname, $merit, $parents, $reqin, $reqout, $eng, $ben, $id, $sccode);
} else {
    // Insert Logic
    $stmt = $conn->prepare("INSERT INTO slots (sccode, slotname, merit, parents, reqin, reqout, trans_name_eng, trans_name_ben) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisssii", $sccode, $slotname, $merit, $parents, $reqin, $reqout, $eng, $ben);
}

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error: " . $conn->error;
}