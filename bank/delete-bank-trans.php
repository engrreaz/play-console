<?php
include '../inc.php';

$id = $_POST['id'];
$accid = $_POST['accid'];

$stmt = $conn->prepare("DELETE FROM banktrans WHERE id = ?");
$stmt->bind_param("i", $id);
if($stmt->execute()) {
    // ডিলিট করার পর অবশ্যই রিক্যালকুলেট করতে হবে
    recalculate_bank_balance($conn, $accid); 
    echo json_encode(['status' => 'success']);
}
?>