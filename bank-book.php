<?php
$page_title = "Bank Book";
include 'inc.php'; 

$bank_accounts = [];
$total_balance = 0;

// অপ্টিমাইজড কুয়েরি: ব্যালেন্স সহ লিস্ট
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
}
$stmt->close();
?>

<style>
    :root {
        --m3-surface: #FDFBFF;
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-secondary-container: #E8DEF8;
    }
    body { background: var(--m3-surface); font-family: 'Roboto', sans-serif; }
    .hero-card {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 0 0 32px 32px;
        padding: 40px 24px; color: white; text-align: center;
    }
    .acc-card {
        background: #fff; border-radius: 20px; padding: 16px;
        margin: 0 16px 12px; display: flex; align-items: center;
        border: 1px solid #E0E2EC; transition: 0.2s;
    }
    .acc-card:active { transform: scale(0.97); background: var(--m3-secondary-container); }
    .icon-box {
        width: 48px; height: 48px; border-radius: 12px;
        background: var(--m3-primary-container); color: var(--m3-primary);
        display: flex; align-items: center; justify-content: center; margin-right: 16px;
    }
</style>

<main>
    <div class="hero-card">
        <div class="small opacity-75 text-uppercase">Total Bank Balance</div>
        <div class="display-5 fw-bold">৳ <?php echo number_format($total_balance, 2); ?></div>
    </div>

    <div class="p-3">
        <h6 class="ms-2 mb-3 text-secondary fw-bold small">ACTIVE ACCOUNTS</h6>
        <?php foreach ($bank_accounts as $acc): ?>
            <div class="acc-card shadow-sm" onclick="location.href='bank-statement.php?id=<?php echo $acc['id']; ?>'">
                <div class="icon-box"><i class="bi bi-bank fs-5"></i></div>
                <div style="flex-grow:1">
                    <div class="fw-bold"><?php echo $acc['bankname']; ?></div>
                    <div class="small text-muted"><?php echo $acc['accno']; ?></div>
                </div>
                <div class="text-end">
                    <div class="small text-muted" style="font-size:10px">BALANCE</div>
                    <div class="fw-bold">৳<?php echo number_format($acc['current_balance'], 0); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'footer.php'; ?>