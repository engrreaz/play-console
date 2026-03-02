<?php
$page_title = "Accounts Hub";
include 'inc.php'; 

// ১. কুইক ক্যালকুলেশন (Prepared Statements - Secure)
$sy_param = "%$sy%";

// Cash-in-my-hand (CIMH) ক্যালকুলেশন
function get_sum($conn, $query, $params, $types) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    return $result ?? 0;
}

$money_rec = get_sum($conn, "SELECT SUM(amount) FROM transaction WHERE sessionyear LIKE ? AND sccode = ? AND receivedby = ?", [$sy_param, $sccode, $usr], "sss");
$money_dep = get_sum($conn, "SELECT SUM(amount) FROM banktrans WHERE entryby = ? AND sccode = ? AND transtype = 'Deposit'", [$usr, $sccode], "ss");
$money_wth = get_sum($conn, "SELECT SUM(amount) FROM banktrans WHERE entryby = ? AND sccode = ? AND transtype = 'Withdraw'", [$usr, $sccode], "ss");

// আপনার লজিক অনুযায়ী CIMH (অ্যাডজাস্টমেন্টসহ)
$cimh = ($money_rec + $money_wth - $money_dep) - 4832462.5;
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-on-primary: #FFFFFF;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-surface: #FEF7FF;
        --m3-surface-container: #F3EDF7;
        --m3-secondary: #625B71;
        --m3-outline: #79747E;
    }

    body { background-color: var(--m3-surface); font-family: 'Roboto', sans-serif; margin: 0; }

    /* Top Gradient Hero Section */
    .hero-box {
        background: linear-gradient(180deg, var(--m3-primary) 0%, #9581CD 100%);
        padding: 40px 20px 60px;
        color: white;
        text-align: center;
        border-radius: 0 0 32px 32px;
    }

    .hero-box .label { font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.8; font-weight: 700; }
    .hero-box .amount { font-size: 36px; font-weight: 800; margin-top: 5px; }

    /* Floating Action Card (Deposit) */
    .m3-card-elevated {
        background: white;
        border-radius: 24px;
        padding: 20px;
        margin: -40px 16px 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
    }

    /* List Items */
    .staff-card {
        background: white;
        border-radius: 20px;
        padding: 16px;
        margin: 0 16px 12px;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s;
        border: 1px solid var(--m3-surface-container);
    }

    .staff-card:active { transform: scale(0.98); background: var(--m3-surface-container); }

    .avatar-circle {
        width: 48px; height: 48px; border-radius: 12px;
        background: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; margin-right: 12px;
    }

    /* Form Styling */
    .m3-input-group {
        display: flex; gap: 8px; margin-top: 15px; padding-top: 15px; border-top: 1px dashed var(--m3-outline);
    }

    .m3-field {
        flex: 1; border: 1px solid var(--m3-outline); border-radius: 8px;
        padding: 8px 12px; font-size: 14px; outline: none;
    }

    .btn-tonal {
        background: var(--m3-primary-container); color: var(--m3-on-primary-container);
        border: none; border-radius: 100px; padding: 10px 16px; font-weight: 600; font-size: 13px;
    }

    .btn-primary-m3 {
        background: var(--m3-primary); color: white;
        border: none; border-radius: 100px; padding: 12px 24px; font-weight: 600; width: 100%;
    }
</style>

<main>
    <div class="hero-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="reporthome.php" class="text-white"><i class="bi bi-arrow-left-short fs-2"></i></a>
            <span class="fw-bold">Accounts Hub</span>
            <div style="width:32px;"></div>
        </div>
        <div class="label">Total Net Receivable</div>
        <div class="amount">৳ <span id="sobar_display">0.00</span></div>
    </div>

    <div class="m3-card-elevated">
        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-bank2 text-primary me-2"></i>
            <span class="fw-bold small text-uppercase">Bank Deposit Settlement</span>
        </div>
        
        <select class="m3-field mb-3 w-100" id="partid2" style="height: 45px;">
            <?php
            $stmt_bank = $conn->prepare("SELECT id, bankname, acctype FROM bankinfo WHERE sccode = ? AND status = 1");
            $stmt_bank->bind_param("s", $sccode);
            $stmt_bank->execute();
            $res_bank = $stmt_bank->get_result();
            while($row = $res_bank->fetch_assoc()) {
                echo "<option value='".$row['id']."'>".$row['bankname']." (".$row['acctype'].")</option>";
            }
            $stmt_bank->close();
            ?>
        </select>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <input type="date" class="m3-field w-100" id="date" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-6">
                <input type="number" class="m3-field w-100" id="amount" value="<?php echo round($cimh, 2); ?>" placeholder="Amount">
            </div>
        </div>

        <button class="btn-primary-m3 shadow-sm" id="subm" onclick="add_bank_deposit();">
            <i class="bi bi-check2-circle me-1"></i> Confirm Deposit
        </button>
    </div>

    <div class="px-4 mb-3 d-flex justify-content-between align-items-center">
        <span class="fw-bold text-secondary small">STAFF BALANCES</span>
        <i class="bi bi-funnel small"></i>
    </div>

    <div id="receivable-list">
        <?php
        $sl = 0; $sobar = 0;
        $sql_stpr = "SELECT entryby, SUM(amount) as mottaka FROM stpr WHERE sccode = ? AND sessionyear LIKE ? GROUP BY entryby";
        $stmt_stpr = $conn->prepare($sql_stpr);
        $stmt_stpr->bind_param("ss", $sccode, $sy_param);
        $stmt_stpr->execute();
        $res_stpr = $stmt_stpr->get_result();

        while ($row = $res_stpr->fetch_assoc()) {
            $by = $row["entryby"];
            $mottaka_raw = $row["mottaka"];

            // হ্যান্ডওভার চেক
            $handed_over = get_sum($conn, "SELECT SUM(amount) FROM transaction WHERE receivedfrom = ? AND sccode = ? AND classname != 'Cashbook'", [$by, $sccode], "ss");

            $balance = $mottaka_raw - $handed_over;
            if ($balance > 0) {
                $sobar += $balance;
                // ইউজার নাম ফেচ
                $tname = get_sum($conn, "SELECT profilename FROM usersapp WHERE email = ? LIMIT 1", [$by], "s");
        ?>
            <div class="staff-card shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="avatar-circle">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold text-dark text-truncate"><?php echo $tname ?: 'Unknown Staff'; ?></div>
                        <div class="text-muted small"><?php echo $by; ?></div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary h5 mb-0">৳<?php echo number_format($balance, 0); ?></div>
                        <div class="text-danger" style="font-size: 10px; font-weight: 700;">DUE</div>
                    </div>
                </div>
                
                <div class="m3-input-group">
                    <input type="number" class="m3-field" style="max-width: 120px;" id="amt<?php echo $sl; ?>" value="<?php echo $balance; ?>">
                    <button class="btn-tonal flex-grow-1" id="btnbox<?php echo $sl; ?>" onclick="receive_cash(<?php echo $sl; ?>, '<?php echo $by; ?>');">
                        <i class="bi bi-plus-lg me-1"></i> Receive
                    </button>
                </div>
            </div>
        <?php 
            $sl++;
            }
        }
        $stmt_stpr->close();
        ?>
    </div>
</main>

<?php include 'footer.php'; ?>
<script>
    document.getElementById("sobar_display").innerText = "<?php echo number_format($sobar, 2); ?>";

    function receive_cash(sl, from) {
        const amt = document.getElementById("amt" + sl).value;
        const btn = document.getElementById("btnbox" + sl);

        if(amt <= 0) return;

        Swal.fire({
            title: 'Receive Cash?',
            text: `Confirming ৳${amt} from ${from}`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#6750A4',
            confirmButtonText: 'Yes, Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "paymentreceived.php",
                    data: { user: '<?php echo $usr; ?>', sccode: '<?php echo $sccode; ?>', sy: '<?php echo $sy; ?>', from: from, amt: amt, tail: 0 },
                    beforeSend: function () { btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; btn.disabled = true; },
                    success: function () {
                        Swal.fire('Success', 'Receipt Recorded', 'success').then(() => location.reload());
                    }
                });
            }
        });
    }

    function add_bank_deposit() {
        const amount = document.getElementById("amount").value;
        if(!amount || amount <= 0) return;

        $.ajax({
            type: "POST",
            url: "savebanktrans.php",
            data: { date: $('#date').val(), partid2: $('#partid2').val(), amount: amount, tail: 0 },
            beforeSend: function () { $('#subm').disabled = true; },
            success: function () {
                Swal.fire('Deposited!', 'Entry saved.', 'success').then(() => location.reload());
            }
        });
    }
</script>

