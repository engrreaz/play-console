<?php
// র্যাংক এবং পারমিশন চেক
$allowed_ranks = ['Accountants', 'Principal', 'Head Teacher', 'Office Assistant'];
if (in_array($rank, $allowed_ranks) || $reallevel == 'Super Administrator' || $userlevel == 'Administrator') {
    
    $mon = date('m');
    $sy_param = "%$sy%";

    // ১. ট্রানজেকশন ক্যালকুলেশন (Prepared Statements)
    // ইনকাম (Received by user)
    $stmt1 = $conn->prepare("SELECT SUM(amount) FROM transaction WHERE sessionyear LIKE ? AND sccode = ? AND receivedby = ?");
    $stmt1->bind_param("sss", $sy_param, $sccode, $usr);
    $stmt1->execute();
    $stmt1->bind_result($money);
    $stmt1->fetch();
    $stmt1->close();

    // আউটগোয়িং (Received from user)
    $stmt2 = $conn->prepare("SELECT SUM(amount) FROM transaction WHERE sessionyear LIKE ? AND sccode = ? AND receivedfrom = ?");
    $stmt2->bind_param("sss", $sy_param, $sccode, $usr);
    $stmt2->execute();
    $stmt2->bind_result($money1);
    $stmt2->fetch();
    $stmt2->close();

    // ব্যাংক ডিপোজিট
    $stmt3 = $conn->prepare("SELECT SUM(amount) FROM banktrans WHERE transtype='Deposit' AND entryby = ? AND sccode = ?");
    $stmt3->bind_param("ss", $usr, $sccode);
    $stmt3->execute();
    $stmt3->bind_result($money2);
    $stmt3->fetch();
    $stmt3->close();

    // ব্যাংক উইথড্র
    $stmt4 = $conn->prepare("SELECT SUM(amount) FROM banktrans WHERE transtype='Withdraw' AND entryby = ? AND sccode = ?");
    $stmt4->bind_param("ss", $usr, $sccode);
    $stmt4->execute();
    $stmt4->bind_result($money3);
    $stmt4->fetch();
    $stmt4->close();

    // ক্যাশবুক ব্যালেন্স
    $stmt5 = $conn->prepare("SELECT SUM(income), SUM(expenditure) FROM cashbook WHERE entryby = ? AND sccode = ?");
    $stmt5->bind_param("ss", $usr, $sccode);
    $stmt5->execute();
    $stmt5->bind_result($inco, $expe);
    $stmt5->fetch();
    $stmt5->close();

    $money4 = ($inco ?? 0) - ($expe ?? 0);
    $cimh = ($paisi ?? 0) + ($money ?? 0) + ($money3 ?? 0) - ($money1 ?? 0) - ($money2 ?? 0) + $money4;

    // ২. স্টুডেন্ট ফিন্যান্স সামারি
    $stmt6 = $conn->prepare("SELECT SUM(dues), SUM(payableamt), SUM(paid) FROM stfinance WHERE sessionyear LIKE ? AND sccode = ? AND month <= ?");
    $stmt6->bind_param("sss", $sy_param, $sccode, $mon);
    $stmt6->execute();
    $stmt6->bind_result($totaldues, $tpaya, $tpaid);
    $stmt6->fetch();
    $stmt6->close();

    // পার্সেন্টেজ ক্যালকুলেশন
    $perc = ($tpaya > 0) ? ceil(($tpaid * 100) / $tpaya) : 0;
    $deg = ($tpaya > 0) ? ceil(($tpaid * 360) / $tpaya) : 0;
    ?>

    <style>
        .m3-card {
            background-color: #fff;
            border-radius: 28px; /* M3 Large Shape */
            padding: 20px;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 16px;
        }
        .m3-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #6750A4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .balance-text {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1C1B1F;
            letter-spacing: -0.5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .circular-progress {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: conic-gradient(#6750A4 <?php echo $deg; ?>deg, #EADDFF 0deg);
            position: relative;
        }
        .circular-progress::before {
            content: "<?php echo $perc; ?>%";
            position: absolute;
            width: 60px;
            height: 60px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #6750A4;
        }
        .small-label {
            font-size: 0.75rem;
            color: #49454F;
            font-weight: 500;
        }
        .stats-box {
            background: #F3EDF7;
            border-radius: 16px;
            padding: 10px;
            margin-top: 15px;
        }
    </style>

    <div class="m3-card shadow-sm" onclick="goclsa();">
        <div class="m3-title">
            <span>Accounts Overview</span>
            <i class="bi bi-cash-coin fs-4"></i>
        </div>

        <div class="info-row">
            <div>
                <div class="small-label">Cash in Hand</div>
                <div class="balance-text">৳ <?php echo number_format($cimh, 2); ?></div>
            </div>
            <div class="circular-progress shadow-sm"></div>
        </div>

        <div class="stats-box">
            <div class="d-flex justify-content-between mb-1">
                <span class="small-label">Total Collection</span>
                <span class="small-label fw-bold">৳ <?php echo number_format($tpaid ?? 0, 2); ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="small-label">Outstanding Dues</span>
                <span class="small-label fw-bold text-danger">৳ <?php echo number_format($totaldues ?? 0, 2); ?></span>
            </div>
        </div>
        
        <div class="mt-3 text-center">
            <small class="text-muted" style="font-size: 0.65rem;">Tap to view detailed financial reports</small>
        </div>
    </div>
    <?php
}
?>

<script>
    function goclsp() { window.location.href = 'finclssec.php'; }
    function goclsa() { window.location.href = 'finacc.php'; }
</script>