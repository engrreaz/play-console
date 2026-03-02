<?php
include '../inc.php'; // Path adjusts based on folder

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accid = $_POST['accid'];
    $date = $_POST['date'];
    $transtype = $_POST['transtype'];
    $amount = floatval($_POST['amount']);
    $particulars = $_POST['particulars'];
    $chqno = $_POST['chqno'];
    $refno = $sccode . date('YmdHis');

    // ডাটা ইনসার্ট
    $sql = "INSERT INTO banktrans (sccode, accid, date, transtype, particulareng, chqno, amount, refno, entryby, entrytime) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssssss", $sccode, $accid, $date, $transtype, $particulars, $chqno, $amount, $refno, $usr);
    
    if ($stmt->execute()) {
        // সেভ সফল হলে ব্যালেন্স রিক্যালকুলেট করা
        recalculate_bank_balance($conn, $accid);
        echo json_encode(['status' => 'success', 'message' => 'Transaction recorded and ledger updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
    $stmt->close();
}

// রিক্যালকুলেশন ফাংশনটি এখানেও রাখতে পারেন বা আলাদা ফাইলে
function recalculate_bank_balance($conn, $accid) {
    $sql = "SELECT id, transtype, amount FROM banktrans WHERE accid = ? ORDER BY date ASC, id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $accid);
    $stmt->execute();
    $result = $stmt->get_result();

    $running_balance = 0;
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $amt = $row['amount'];
        $type = strtolower($row['transtype']);

        $opening = $running_balance;
        // চেক: কোন টাইপগুলো যোগ হবে
        if ($type == 'deposit' || $type == 'interest' || empty($type)) {
            $running_balance += $amt;
        } else {
            $running_balance -= $amt;
        }

        $update = $conn->prepare("UPDATE banktrans SET transopening = ?, balance = ? WHERE id = ?");
        $update->bind_param("ddi", $opening, $running_balance, $id);
        $update->execute();
    }
}
?>