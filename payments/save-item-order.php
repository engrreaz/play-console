<?php
include '../inc.light.php';
$order = explode(',', $_POST['order']); // 'id=1,id=2' ফরম্যাটে আসবে

foreach($order as $row) {
    list($id, $sl) = explode('=', $row);
    $conn->query("UPDATE financesetup SET slno=".intval($sl)." WHERE id=".intval($id)." AND sccode='$sccode'");
}
echo json_encode(['status' => 'success', 'message' => 'Sort order synced.']);