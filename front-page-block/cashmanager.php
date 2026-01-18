<?php
// File: front-page-block/cashmanager.php

// ১. সেশন ইয়ার হ্যান্ডলিং (লিঙ্কগুলোর জন্য)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;

// --- Data Fetching & Logic (Prepared Statements) ---
$net_balance = 0;
$account_ids = [];

if (isset($conn, $sccode)) {
    // ১. প্রতিষ্ঠানের সব একটিভ ব্যাংক অ্যাকাউন্ট আইডি ফেচ করা
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

    // ২. প্রতিটি অ্যাকাউন্টের সর্বশেষ ব্যালেন্স যোগ করা
    if (!empty($account_ids)) {
        $stmt_trans = $conn->prepare("SELECT balance FROM banktrans WHERE accid = ? ORDER BY date DESC, id DESC LIMIT 1");
        foreach ($account_ids as $acc_id) {
            $stmt_trans->bind_param("i", $acc_id);
            $stmt_trans->execute();
            $result_trans = $stmt_trans->get_result();
            if ($result_trans && $result_trans->num_rows > 0) {
                $trans_data = $result_trans->fetch_assoc();
                $net_balance += (float)$trans_data['balance'];
            }
        }
        $stmt_trans->close();
    }
}
?>

<style>
    .m3-cash-card { background: #fff; border-radius: 8px; padding: 12px; }
    
    .cash-lbl-header { font-size: 0.65rem; font-weight: 800; color: #146C32; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .cash-metric-val { font-size: 1.6rem; font-weight: 800; color: #1C1B1F; line-height: 1.1; }
    .cash-metric-unit { font-size: 0.75rem; font-weight: 700; color: #146C32; margin-right: 4px; }
    
    /* Tonal Icon Container (8px Radius) */
    .cash-icon-box {
        width: 44px; height: 44px; border-radius: 8px;
        background-color: #E8F5E9; color: #146C32;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; flex-shrink: 0;
    }

    /* M3 Tonal Buttons (8px Radius) */
    .btn-m3-tonal-cash {
        background: #E8F5E9; color: #146C32; border-radius: 8px;
        padding: 8px 12px; font-size: 0.7rem; font-weight: 800; 
        border: none; text-decoration: none !important;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: 0.2s; flex: 1;
    }
    .btn-m3-tonal-cash:active { background: #C8E6C9; transform: scale(0.97); }
    
    .btn-m3-tonal-sec {
        background: #F3EDF7; color: #6750A4; border-radius: 8px;
    }
</style>

<div class="m3-cash-card shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="flex-grow-1 overflow-hidden">
            <div class="cash-lbl-header mb-1"><i class="bi bi-shield-check me-1"></i> Liquidity Status</div>
            <div class="d-flex align-items-baseline">
                <span class="cash-metric-unit">৳</span>
                <span class="cash-metric-val text-truncate"><?php echo number_format($net_balance, 2); ?></span>
            </div>
            <div class="small text-muted mt-1" style="font-size: 0.65rem; font-weight: 600;">Net Institution Balance</div>
        </div>
        
        <div class="cash-icon-box shadow-sm ms-2">
            <i class="bi bi-wallet2"></i>
        </div>
    </div>

    <div class="d-flex gap-2 pt-1">
        <a href="cashbook.php?year=<?php echo $current_session; ?>" class="btn-m3-tonal-cash shadow-sm">
            <i class="bi bi-book-fill"></i> CASHBOOK
        </a>
        <a href="bank-book.php?year=<?php echo $current_session; ?>" class="btn-m3-tonal-cash btn-m3-tonal-sec shadow-sm">
            <i class="bi bi-bank"></i> BANK LOGS
        </a>
    </div>
</div>