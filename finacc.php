<?php
include 'inc.php'; // DB সংযোগ এবং সেশন লোড করবে

// ১. কুইক ক্যালকুলেশন (Prepared Statements - Secure)
$sy_param = "%$sy%";

// Cash-in-my-hand (CIMH) ক্যালকুলেশন
$stmt1 = $conn->prepare("SELECT SUM(amount) FROM transaction WHERE sessionyear LIKE ? AND sccode = ? AND receivedby = ?");
$stmt1->bind_param("sss", $sy_param, $sccode, $usr);
$stmt1->execute();
$stmt1->bind_result($money_rec);
$stmt1->fetch();
$stmt1->close();

$stmt2 = $conn->prepare("SELECT SUM(amount) FROM banktrans WHERE entryby = ? AND sccode = ? AND transtype = 'Deposit'");
$stmt2->bind_param("ss", $usr, $sccode);
$stmt2->execute();
$stmt2->bind_result($money_dep);
$stmt2->fetch();
$stmt2->close();

$stmt3 = $conn->prepare("SELECT SUM(amount) FROM banktrans WHERE entryby = ? AND sccode = ? AND transtype = 'Withdraw'");
$stmt3->bind_param("ss", $usr, $sccode);
$stmt3->execute();
$stmt3->bind_result($money_wth);
$stmt3->fetch();
$stmt3->close();

// আপনার লজিক অনুযায়ী CIMH ক্যালকুলেশন (হার্ডকোড অংশসহ)
$cimh = (($money_rec ?? 0) + ($money_wth ?? 0) - ($money_dep ?? 0)) - 4832462.5;
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface */

    /* Top Hero Bar */
    .m3-hero-bar {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 0 0 32px 32px;
        padding: 30px 20px 40px;
        color: white;
        text-align: center;
        box-shadow: 0 4px 12px rgba(103,80,164,0.2);
    }
    .total-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; letter-spacing: 1px; }
    .total-val { font-size: 2.2rem; font-weight: 800; line-height: 1; }

    /* Settlement Card */
    .settle-card {
        background: white; border-radius: 28px; padding: 20px;
        margin: -20px 16px 20px; border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* List Item Styling */
    .m3-list-card {
        background: #fff; border-radius: 20px; padding: 16px;
        margin: 0 16px 12px; border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex; flex-direction: column;
    }
    
    .user-info { display: flex; align-items: center; margin-bottom: 12px; }
    .avatar-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: #F3EDF7; color: #6750A4;
        display: flex; align-items: center; justify-content: center;
        margin-right: 12px;
    }

    .form-floating > .form-control, .form-floating > .form-select {
        border-radius: 12px; border: 1px solid #79747E; background: transparent;
    }

    .btn-m3-tonal {
        background: #EADDFF; color: #21005D; border-radius: 100px;
        font-weight: 700; border: none; padding: 10px 20px; font-size: 0.85rem;
    }
    .btn-m3-primary {
        background: #6750A4; color: white; border-radius: 100px;
        font-weight: 700; border: none; padding: 12px 24px;
    }
</style>

<main class="pb-5">
    <div class="m3-hero-bar">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="reporthome.php" class="text-white"><i class="bi bi-arrow-left fs-4"></i></a>
            <h6 class="fw-bold mb-0">Cash Settlement</h6>
            <div style="width: 24px;"></div>
        </div>
        <div class="total-label">Combined Net Receivable</div>
        <div class="total-val">৳ <span id="sobar_display">0.00</span></div>
    </div>

    <div class="settle-card shadow">
        <h6 class="fw-bold text-primary mb-3 small uppercase">Deposit to Bank</h6>
        <div class="form-floating mb-3">
            <select class="form-select" id="partid2">
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
            <label>Select Bank Account</label>
        </div>
        <div class="row g-2">
            <div class="col-6">
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="date" value="<?php echo date('Y-m-d'); ?>">
                    <label>Date</label>
                </div>
            </div>
            <div class="col-6">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="amount" value="<?php echo round($cimh, 2); ?>">
                    <label>Amount</label>
                </div>
            </div>
        </div>
        <button class="btn btn-m3-primary w-100 shadow-sm" id="subm" onclick="add_bank_deposit();">
            <i class="bi bi-cloud-arrow-up-fill me-2"></i> SUBMIT DEPOSIT
        </button>
        <div id="status" class="text-center mt-2"></div>
    </div>

    <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase tracking-wider">Receivable from Staff</h6>

    <div id="receivable-list">
        <?php
        $sl = 0; $sobar = 0;
        
        // ২. স্টুডেন্ট পেমেন্ট থেকে বকেয়া ফেচ করা
        $sql_stpr = "SELECT entryby, classname, sectionname, SUM(amount) as mottaka FROM stpr WHERE sccode = ? AND sessionyear LIKE ? GROUP BY entryby";
        $stmt_stpr = $conn->prepare($sql_stpr);
        $stmt_stpr->bind_param("ss", $sccode, $sy_param);
        $stmt_stpr->execute();
        $res_stpr = $stmt_stpr->get_result();

        while ($row = $res_stpr->fetch_assoc()) {
            $by = $row["entryby"];
            $mottaka_raw = $row["mottaka"];

            // অলরেডি হ্যান্ডওভার করা টাকা চেক করা
            $stmt_paid = $conn->prepare("SELECT SUM(amount) FROM transaction WHERE receivedfrom = ? AND sccode = ? AND classname != 'Cashbook'");
            $stmt_paid->bind_param("ss", $by, $sccode);
            $stmt_paid->execute();
            $stmt_paid->bind_result($handed_over);
            $stmt_paid->fetch();
            $stmt_paid->close();

            $balance = $mottaka_raw - ($handed_over ?? 0);
            if ($balance > 0) {
                $sobar += $balance;
                // ইউজার/টিচার নাম ফেচ করা
                $stmt_usr = $conn->prepare("SELECT profilename FROM usersapp WHERE email = ? LIMIT 1");
                $stmt_usr->bind_param("s", $by);
                $stmt_usr->execute();
                $stmt_usr->bind_result($tname);
                $stmt_usr->fetch();
                $stmt_usr->close();
        ?>
            <div class="m3-list-card shadow-sm">
                <div class="user-info">
                    <div class="avatar-icon"><i class="bi bi-person-fill"></i></div>
                    <div class="overflow-hidden">
                        <div class="fw-bold text-dark text-truncate small"><?php echo $tname ?? $by; ?></div>
                        <div class="text-muted" style="font-size: 0.65rem;"><?php echo $by; ?></div>
                    </div>
                    <div class="ms-auto text-end">
                        <div class="fw-extrabold text-primary h5 mb-0">৳<?php echo number_format($balance, 0); ?></div>
                        <div class="small text-muted" style="font-size: 0.6rem;">PENDING</div>
                    </div>
                </div>
                
                <input type="hidden" id="from<?php echo $sl; ?>" value="<?php echo $by; ?>">
                <input type="hidden" id="cls<?php echo $sl; ?>" value=""> <input type="hidden" id="sec<?php echo $sl; ?>" value="">

                <div class="d-flex gap-2 align-items-center border-top pt-3 mt-1">
                    <div class="input-group input-group-sm" style="max-width: 150px;">
                        <span class="input-group-text bg-white border-0">৳</span>
                        <input type="number" class="form-control border-primary-subtle" id="amt<?php echo $sl; ?>" value="<?php echo $balance; ?>">
                    </div>
                    <button class="btn btn-m3-tonal flex-grow-1" id="btnbox<?php echo $sl; ?>" onclick="receive_cash(<?php echo $sl; ?>);">
                        RECEIVE BY ME
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

<div style="height: 60px;"></div>



<script>
    document.getElementById("sobar_display").innerText = "<?php echo number_format($sobar, 2); ?>";

    // ১. স্টাফ থেকে ক্যাশ গ্রহণ
    function receive_cash(sl) {
        const from = document.getElementById("from" + sl).value;
        const amt = document.getElementById("amt" + sl).value;
        const btn = document.getElementById("btnbox" + sl);

        if(amt <= 0) return;

        Swal.fire({
            title: 'Confirm Receipt?',
            text: `You are confirming ৳${amt} from ${from}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6750A4'
        }).then((result) => {
            if (result.isConfirmed) {
                const data = `user=<?php echo $usr; ?>&sccode=<?php echo $sccode; ?>&sy=<?php echo $sy; ?>&cls=&sec=&from=${from}&amt=${amt}&tail=0`;
                
                $.ajax({
                    type: "POST",
                    url: "paymentreceived.php",
                    data: data,
                    beforeSend: function () { btn.innerHTML = '<div class="spinner-border spinner-border-sm"></div>'; btn.disabled = true; },
                    success: function (html) {
                        Swal.fire('Success', 'Cash received successfully', 'success').then(() => location.reload());
                    }
                });
            }
        });
    }

    // ২. ব্যাংক ডিপোজিট এন্ট্রি
    function add_bank_deposit() {
        const partid2 = document.getElementById("partid2").value;
        const date = document.getElementById("date").value;
        const amount = document.getElementById("amount").value;
        const btn = document.getElementById("subm");

        if(!amount || amount <= 0) return;

        $.ajax({
            type: "POST",
            url: "savebanktrans.php",
            data: `date=${date}&partid2=${partid2}&amount=${amount}&tail=0`,
            beforeSend: function () { btn.disabled = true; $('#status').html('<div class="spinner-border text-primary"></div>'); },
            success: function (html) {
                Swal.fire('Deposited!', 'Bank entry saved.', 'success').then(() => location.reload());
            }
        });
    }
</script>

<?php include 'footer.php'; ?>