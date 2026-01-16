<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. ডাটা ফেচিং অপ্টিমাইজেশন (লুপের বাইরে একবারেই ব্যালেন্সসহ সব ডাটা আনা)
$bank_accounts = [];
$total_balance = 0;
$account_count = 0;

// এই কুয়েরিটি প্রতিটি ব্যাংকের সর্বশেষ ট্রানজেকশন থেকে ব্যালেন্স নিয়ে আসবে
$sql_bank = "SELECT b.*, 
            (SELECT balance FROM banktrans WHERE accid = b.id ORDER BY date DESC, id DESC LIMIT 1) as current_balance 
            FROM bankinfo b 
            WHERE b.sccode = ? AND b.status = 1 
            ORDER BY b.bankname ASC";

$stmt = $conn->prepare($sql_bank);
$stmt->bind_param("s", $sccode);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['current_balance'] = $row['current_balance'] ?? 0;
    $bank_accounts[] = $row;
    $total_balance += $row['current_balance'];
    $account_count++;
}
$stmt->close();
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Hero Summary Card */
    .hero-balance-card {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 0 0 32px 32px;
        padding: 40px 24px 30px;
        color: white;
        text-align: center;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.2);
    }
    .total-label { font-size: 0.85rem; font-weight: 600; opacity: 0.8; text-transform: uppercase; letter-spacing: 1px; }
    .total-amount { font-size: 2.2rem; font-weight: 800; margin-top: 5px; line-height: 1; }
    
    /* Stats Chips */
    .stat-chip {
        display: inline-flex; align-items: center; background: rgba(255,255,255,0.2);
        padding: 6px 16px; border-radius: 100px; font-size: 0.75rem; margin-top: 15px;
    }

    /* Account Card Item */
    .acc-card {
        background: #fff;
        border-radius: 24px;
        padding: 16px 20px;
        margin: 0 16px 12px;
        border: none;
        display: flex;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s, background-color 0.2s;
    }
    .acc-card:active { transform: scale(0.98); background-color: #F7F2FA; }

    .bank-icon-wrapper {
        width: 48px; height: 48px; border-radius: 14px;
        background: #F3EDF7; color: #6750A4;
        display: flex; align-items: center; justify-content: center;
        margin-right: 16px; flex-shrink: 0;
    }

    .acc-info { flex-grow: 1; overflow: hidden; }
    .bank-name { font-weight: 700; color: #1C1B1F; font-size: 0.95rem; margin-bottom: 2px; }
    .acc-meta { font-size: 0.75rem; color: #49454F; line-height: 1.3; }
    
    .acc-balance { text-align: right; font-weight: 800; color: #1C1B1F; font-size: 1.1rem; }
    .type-badge {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 8px; border-radius: 6px; font-weight: 700;
    }
</style>

<main class="pb-5">
    <div class="hero-balance-card shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="reporthome.php" class="text-white"><i class="bi bi-arrow-left fs-4"></i></a>
            <h6 class="fw-bold mb-0">Bank Book</h6>
            <div style="width: 24px;"></div>
        </div>

        <div class="total-label">Combined Net Balance</div>
        <div class="total-amount">৳ <?php echo number_format($total_balance, 2); ?></div>
        
        <div class="stat-chip">
            <i class="bi bi-bank me-2"></i> <?php echo $account_count; ?> Active Accounts
        </div>
    </div>

    <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase tracking-wider">Account Breakdown</h6>

    <div class="account-list">
        <?php if (!empty($bank_accounts)): ?>
            <?php foreach ($bank_accounts as $acc): ?>
                <div class="acc-card shadow-sm" onclick="viewDetails(<?php echo $acc['id']; ?>)">
                    <div class="bank-icon-wrapper shadow-sm">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                    
                    <div class="acc-info">
                        <div class="bank-name text-truncate"><?php echo $acc['bankname']; ?></div>
                        <div class="acc-meta">
                            <span class="type-badge"><?php echo $acc['acctype']; ?></span><br>
                            <span class="text-muted">No. <?php echo $acc['accno']; ?></span>
                        </div>
                    </div>

                    <div class="acc-balance">
                        <div style="font-size: 0.65rem; color: #49454F; font-weight: 600;">BALANCE</div>
                        ৳<?php echo number_format($acc['current_balance'], 0); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-building-exclamation display-1"></i>
                <p class="mt-2 fw-bold">No bank records found.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="px-4 mt-4">
        <div class="d-flex align-items-start text-muted p-3 bg-white rounded-4 shadow-sm" style="border: 1px dashed #CAC4D0;">
            <i class="bi bi-info-circle-fill me-3 mt-1 color-primary"></i>
            <p style="font-size: 0.75rem; margin-bottom: 0;">
                These balances are calculated based on the latest transaction entry in the system. Please verify with physical bank statements.
            </p>
        </div>
    </div>
</main>

<div style="height: 60px;"></div>



<script>
    function viewDetails(id) {
        // আপনি যদি বিস্তারিত ট্রানজেকশন দেখতে চান তবে এখানে লজিক দিন
        // window.location.href = "bank-statement.php?id=" + id;
        Swal.fire({
            title: 'Account Options',
            text: 'View full statement or edit info?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Statement',
            cancelButtonText: 'Edit',
            confirmButtonColor: '#6750A4'
        });
    }
</script>

<?php include 'footer.php'; ?>