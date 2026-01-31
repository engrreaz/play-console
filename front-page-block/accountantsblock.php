<?php
// র্যাংক এবং পারমিশন চেক
$allowed_ranks = ['Accountants', 'Principal', 'Head Teacher', 'Office Assistant'];
if (in_array($rank, $allowed_ranks) || $reallevel == 'Super Administrator' || $userlevel == 'Administrator') {
    
    $mon = date('m');
    $sessionyear_param = $sessionyear . "%";

    // ১. ট্রানজেকশন ক্যালকুলেশন (Prepared Statements)
    // ইনকাম (Received by user)
    $stmt1 = $conn->prepare("SELECT SUM(amount) FROM transaction WHERE sessionyear LIKE ? AND sccode = ? AND receivedby = ?");
    $stmt1->bind_param("sss", $sessionyear_param, $sccode, $usr);
    $stmt1->execute();
    $stmt1->bind_result($money);
    $stmt1->fetch();
    $stmt1->close();

    // আউটগোয়িং (Received from user)
    $stmt2 = $conn->prepare("SELECT SUM(amount) FROM transaction WHERE sessionyear LIKE ? AND sccode = ? AND receivedfrom = ?");
    $stmt2->bind_param("sss", $sessionyear_param, $sccode, $usr);
    $stmt2->execute();
    $stmt2->bind_result($money1);
    $stmt2->fetch();
    $stmt2->close();

    // ব্যাংক ট্রানজেকশন (Deposit & Withdraw)
    $stmt3 = $conn->prepare("SELECT SUM(CASE WHEN transtype='Deposit' THEN amount ELSE 0 END), 
                                   SUM(CASE WHEN transtype='Withdraw' THEN amount ELSE 0 END) 
                            FROM banktrans WHERE entryby = ? AND sccode = ?");
    $stmt3->bind_param("ss", $usr, $sccode);
    $stmt3->execute();
    $stmt3->bind_result($money2, $money3);
    $stmt3->fetch();
    $stmt3->close();

    // ক্যাশবুক ব্যালেন্স
    $stmt5 = $conn->prepare("SELECT SUM(income), SUM(expenditure) FROM cashbook WHERE entryby = ? AND sccode = ?");
    $stmt5->bind_param("ss", $usr, $sccode);
    $stmt5->execute();
    $stmt5->bind_result($inco, $expe);
    $stmt5->fetch();
    $stmt5->close();

    $money4 = ($inco ?? 0) - ($expe ?? 0);
    // Cash in Hand Calculation
    $cimh = ($paisi ?? 0) + ($money ?? 0) + ($money3 ?? 0) - ($money1 ?? 0) - ($money2 ?? 0) + $money4;

    // ২. স্টুডেন্ট ফিন্যান্স সামারি
    $stmt6 = $conn->prepare("SELECT SUM(dues), SUM(payableamt), SUM(paid) FROM stfinance WHERE sessionyear LIKE ? AND sccode = ? AND month <= ?");
    $stmt6->bind_param("sss", $sessionyear_param, $sccode, $mon);
    $stmt6->execute();
    $stmt6->bind_result($totaldues, $tpaya, $tpaid);
    $stmt6->fetch();
    $stmt6->close();

    // পার্সেন্টেজ ক্যালকুলেশন
    $perc = ($tpaya > 0) ? ceil(($tpaid * 100) / $tpaya) : 0;
    $deg = ($tpaya > 0) ? ceil(($tpaid * 360) / $tpaya) : 0;
    ?>

    <style>
      

        .m3-card-accounts {
            padding: 24px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
            cursor: pointer;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .m3-card-accounts:hover {
            background-color: #F7F2FA; /* Slight tonal shift on hover */
            transform: translateY(-2px);
        }

        .m3-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .m3-header span {
            font-size: 1rem;
            font-weight: 500;
            color: var(--m3-primary);
            letter-spacing: 0.1px;
        }

        .m3-icon-box {
            background-color: var(--m3-primary-container);
            color: var(--m3-on-primary-container);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-balance-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .label-small {
            font-size: 0.75rem;
            color: var(--m3-outline);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .balance-amount {
            font-size: 2rem;
            font-weight: 700;
            color: #1C1B1F;
            margin: 4px 0;
        }

        /* Circular Progress Styling */
        .m3-progress {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: conic-gradient(var(--m3-primary) <?php echo $deg; ?>deg, var(--m3-primary-container) 0deg);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(103, 80, 164, 0.15);
        }

        .m3-progress-inner {
            width: 66px;
            height: 66px;
            background: var(--m3-surface);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .m3-progress-inner span {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--m3-primary);
        }

        /* Stats Section */
        .m3-stats-container {
            background-color: var(--m3-surface-container);
            border-radius: 16px;
            padding: 16px;
            margin-top: 24px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .stat-item:last-child {
            margin-bottom: 0;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #49454F;
        }

        .stat-value {
            font-size: 0.85rem;
            font-weight: 700;
        }

        .text-error { color: var(--m3-error); }

        .m3-footer-hint {
            margin-top: 16px;
            text-align: center;
            font-size: 0.7rem;
            color: var(--m3-outline);
            font-style: italic;
        }
    </style>

    <div class="m3-card-accountsss shadow-sm" onclick="goclsa();">
        <div class="m3-header">
            <span>Accounts Summary</span>
            <div class="m3-icon-box">
                <i class="bi bi-wallet2"></i>
            </div>
        </div>

        <div class="main-balance-section">
            <div>
                <div class="label-small">Cash in Hand</div>
                <div class="balance-amount">৳ <?php echo number_format($cimh, 2); ?></div>
                <div class="label-small" style="color: #2E7D32;">● Active Session</div>
            </div>

            <div class="m3-progress">
                <div class="m3-progress-inner">
                    <span><?php echo $perc; ?>%</span>
                    <small style="font-size: 0.5rem; color: var(--m3-outline);">COLLECTED</small>
                </div>
            </div>
        </div>

        <div class="m3-stats-container">
            <div class="stat-item">
                <span class="stat-label">Expected Collection</span>
                <span class="stat-value">৳ <?php echo number_format($tpaya ?? 0, 2); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Received Amount</span>
                <span class="stat-value" style="color: var(--m3-primary);">৳ <?php echo number_format($tpaid ?? 0, 2); ?></span>
            </div>
            <div class="stat-item" style="border-top: 1px solid rgba(0,0,0,0.05); padding-top: 8px; margin-top: 8px;">
                <span class="stat-label fw-bold">Outstanding Dues</span>
                <span class="stat-value text-error">৳ <?php echo number_format($totaldues ?? 0, 2); ?></span>
            </div>
        </div>

        <div class="m3-footer-hint">
            <i class="bi bi-info-circle me-1"></i> Tap to view ledger and detailed reports
        </div>
    </div>

    

    <?php
}
?>

<script>
    function goclsa() { 
        // আপনি চাইলে এখানে একটি Ripple Effect এর জন্য কোড যোগ করতে পারেন
        window.location.href = 'finacc.php'; 
    }
</script>