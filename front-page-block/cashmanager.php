<?php
// File: front-page-block/cashmanager.php

// --- Data Fetching & Logic ---
$net_balance = 0;
$account_ids = [];

if (isset($conn, $sccode)) {
    // 1. Get all active bank account IDs for the school securely
    $stmt_accounts = $conn->prepare("SELECT id FROM bankinfo WHERE sccode = ? AND status = 1");
    $stmt_accounts->bind_param("s", $sccode);
    $stmt_accounts->execute();
    $result_accounts = $stmt_accounts->get_result();
    
    if($result_accounts) {
        while ($row = $result_accounts->fetch_assoc()) {
            $account_ids[] = $row['id'];
        }
    }
    $stmt_accounts->close();

    // 2. For each account, get the balance of the last transaction
    if (!empty($account_ids)) {
        $stmt_trans = $conn->prepare("SELECT balance FROM banktrans WHERE accid = ? ORDER BY date DESC, id DESC LIMIT 1");
        foreach ($account_ids as $acc_id) {
            $stmt_trans->bind_param("i", $acc_id);
            $stmt_trans->execute();
            $result_trans = $stmt_trans->get_result();
            if ($result_trans && $result_trans->num_rows > 0) {
                $trans_data = $result_trans->fetch_assoc();
                $net_balance += $trans_data['balance'];
            }
        }
        $stmt_trans->close();
    }
}

// --- Presentation ---
?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="card-title text-muted mb-1">Net Balance</h6>
                <h4 class="fw-bold mb-0">BDT <?php echo number_format($net_balance, 2); ?></h4>
            </div>
            <i class="bi bi-wallet2 text-primary" style="font-size: 2.5rem;"></i>
        </div>
        <div class="d-flex justify-content-start mt-3">
             <a href="cashbook.php" class="btn btn-outline-primary btn-sm me-2">
                <i class="bi bi-book-fill me-1"></i> Cashbook
             </a>
             <a href="bank-book.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-bank me-1"></i> Bank Book
            </a>
        </div>
    </div>
</div>

<?php
// The forms for adding transactions have been removed from this dashboard block
// to simplify the UI. Users can access them via the buttons above.
?>
