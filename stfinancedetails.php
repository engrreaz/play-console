<?php
$page_title = "Collect Payments";
include 'inc.php';

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear']
    ?? $_COOKIE['query-session']
    ?? $sy;
$sy_like = "%" . $current_session . "%";

$stid = $_GET['id'] ?? '';
$edit = $_GET['edit'] ?? 0;


// ২. ডাটা ফেচিং (Prepared Statements)
// স্টুডেন্ট সেশন তথ্য
$stmt_si = $conn->prepare("SELECT * FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND stid = ? LIMIT 1");
$stmt_si->bind_param("sss", $sy_like, $sccode, $stid);
$stmt_si->execute();
$si = $stmt_si->get_result()->fetch_assoc();
$stmt_si->close();

if (!$si) {
    die("<div class='alert alert-danger'>Student data not found for session $current_session.</div>");
}

// স্টুডেন্ট প্রোফাইল তথ্য
$stmt_st = $conn->prepare("SELECT stnameeng, stnameben, previll, guarmobile FROM students WHERE sccode = ? AND stid = ? LIMIT 1");
$stmt_st->bind_param("ss", $sccode, $stid);
$stmt_st->execute();
$st_profile = $stmt_st->get_result()->fetch_assoc();
$stmt_st->close();

// ৩. অটো-রিসিট জেনারেশন লজিক
$stmt_last_pr = $conn->prepare("SELECT MAX(prno) as lastpr FROM stpr WHERE sccode = ? AND stid = ? AND sessionyear LIKE ?");
$stmt_last_pr->bind_param("sss", $sccode, $stid, $sy_like);
$stmt_last_pr->execute();
$last_pr_val = $stmt_last_pr->get_result()->fetch_assoc()['lastpr'] ?? 0;
$stmt_last_pr->close();

// রিসিট নম্বর ক্যালকুলেশন
$year_prefix = substr($current_session, -2);
if ($last_pr_val > 0) {
    $prno = $last_pr_val + 1;
} else {
    $prno = ($year_prefix * 1000000) + (($stid % 10000) * 100) + 1;
}

$can_edit_date = (in_array($userlevel, ['Administrator', 'Super Administrator'])) ? '' : 'disabled';
?>

<style>
    /* M3 Components (8px Radius) */
    .m3-card {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        margin: 0 8px 8px;
        border: 1px solid #eee;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .hero-banner {
        background: #6750A4;
        color: #fff;
        border-radius: 0 0 8px 8px;
        padding: 16px;
        margin-bottom: 12px;
    }

    /* Condensed Item Style */
    .fee-row {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        background: #fff;
        border-radius: 8px;
        margin: 0 8px 4px;
        border: 1px solid #f0f0f0;
        transition: 0.2s;
    }

    .fee-row.selected {
        background-color: #F3EDF7;
        border-color: #6750A4;
    }

    .st-img-circle {
        width: 60px;
        height: 76px;
        border-radius: 6px;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .m3-checkbox {
        width: 22px;
        height: 22px;
        border-radius: 4px;
        border: 2px solid #6750A4;
    }

    .amt-text {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1C1B1F;
    }

    .input-m3 {
        border-radius: 8px !important;
        border: 1px solid #79747E;
        background: #fff;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .btn-m3-danger {
        background: #db1308;
        color: white;
        border-radius: 8px;
        font-weight: 700;
    }
</style>

<style>
    /* মডালের বডিতে স্ক্রলবার লজিক */
    .m3-scroll-body {
        max-height: 450px;
        /* আপনার পছন্দমতো উচ্চতা সেট করুন */
        overflow-y: auto;
        /* ডাটা বেশি হলে শুধু লম্বা (Y) স্ক্রলবার আসবে */
        overflow-x: hidden;
        /* আড়াআড়ি (X) স্ক্রলবার বন্ধ থাকবে */
    }

    /* স্ক্রলবারটিকে চিকন এবং সুন্দর করার জন্য (Chrome/Safari) */
    .m3-scroll-body::-webkit-scrollbar {
        width: 6px;
    }

    .m3-scroll-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .m3-scroll-body::-webkit-scrollbar-thumb {
        background: #EADDFF;
        /* M3 Tonal Container Color */
        border-radius: 10px;
    }

    .m3-scroll-body::-webkit-scrollbar-thumb:hover {
        background: #6750A4;
        /* Primary Color on Hover */
    }
</style>

<main class="pb-0">
    <div class="hero-container shadow-sm">
        <div class="d-flex align-items-center">
            <img src="<?= student_profile_image_path($stid) ?>" class="st-img-circle me-3">
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-bold text-truncate" style="font-size: 0.95rem;"><?php echo $st_profile['stnameeng']; ?>
                </div>
                <div class="small opacity-80"><?php echo $si['classname'] . ' - ' . $si['sectionname']; ?> | Roll:
                    <?php echo $si['rollno']; ?>
                </div>
                <div class="session-pill small fw-bold mt-2">Session: <?php echo $sessionyear; ?></div>
            </div>

            <div class="text-end" style="z-index:1000;">
                <div class="action-icons">
                    <i class="bi bi-clock-history fs-4 me-3" style="cursor: pointer;" onclick="showHistory();"
                        title="Payment History"></i>
                    <i class="bi bi-plus-circle fs-4 me-3" style="cursor: pointer;" onclick="addFine();"></i>
                    <i class="bi bi-pencil-square fs-4" style="cursor: pointer;" onclick="goedit();"></i>
                </div>

                <div class="small opacity-75">Total Dues</div>
                <div class="h3 text-warning fw-extrabold mb-0" id="total_dues_label">0.00</div>
            </div>
        </div>
    </div>



    <style>
        /* পেমেন্ট কার্ডের কাস্টম স্টাইল */
        .m3-payment-card {
            background: #fff;
            border-radius: 8px;
            padding: 8px;
            margin: 12px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* পেমেন্ট অ্যামাউন্ট ফিল্ডের বিশেষ হাইলাইট */
        .amt-highlight {
            background: var(--m3-tonal-container) !important;
            color: var(--m3-primary) !important;
            font-size: 1.2rem !important;
            font-weight: 900 !important;
        }

        /* বাটন ফিক্স */
        .btn-pay-now {
            height: 48px;
            border-radius: 8px;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 4px 12px rgba(179, 38, 30, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    </style>

    <div class="m3-payment-card shadow-sm">
        <div class="row g-3">
            <div class="col-6">
                <div class="m3-floating-group" style="margin-bottom: 0;">
                    <i class="bi bi-receipt m3-field-icon"></i>
                    <input type="text" class="m3-input-floating bg-light" id="prno" value="<?php echo $prno; ?>"
                        disabled>
                    <label class="m3-floating-label">RECEIPT NO.</label>
                </div>
            </div>

            <div class="col-6">
                <div class="m3-floating-group" style="margin-bottom: 0;">
                    <i class="bi bi-calendar-check m3-field-icon"></i>
                    <input type="date" class="m3-input-floating" style="padding-right:8px;" id="prdate"
                        value="<?php echo date('Y-m-d'); ?>" <?php echo $can_edit_date; ?>>
                    <label class="m3-floating-label">DATE</label>
                </div>
            </div>

            <div class="col-6">
                <div class="m3-floating-group" style="margin-bottom: 0;">
                    <i class="bi bi-cash-stack m3-field-icon" style="color: var(--m3-primary);"></i>
                    <input type="number" class="m3-input-floating amt-highlight" id="amt" value="0" readonly>
                    <label class="m3-floating-label" style="color: var(--m3-primary); font-weight: 800;">AMOUNT</label>
                </div>
            </div>

            <div class="col-6">
                <button class="btn btn-m3-danger btn-pay-now w-100 shadow-sm d-flex" onclick="save();">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                    <div class="ms-2">
                        <div class="m-0 fs-6">Pay Now</div>
                        <div class="m-0" style="font-size: 0.5rem;">by Cash</div>
                    </div>

                </button>
            </div>
        </div>
    </div>








    <div class="px-2 mt-3">
        <h6 class="fw-bold text-secondary mb-2 ms-2 small uppercase">Due Breakdowns</h6>
        <?php
        $idx = 0;
        $total_calc = 0;
        $month_curr = date('m');
        $stmt_due = $conn->prepare("SELECT * FROM stfinance WHERE sccode=? AND stid=? AND sessionyear LIKE ? AND month <= ? AND (dues > 0 OR particulareng='Fine' OR particulareng='Misc') ORDER BY id ASC");
        $stmt_due->bind_param("ssss", $sccode, $stid, $sy_like, $month_curr);
        $stmt_due->execute();
        $res_due = $stmt_due->get_result();

        while ($row = $res_due->fetch_assoc()):
            $total_calc += $row['dues'];
            $is_fine = (strpos($row['particulareng'], 'FINE') !== false);
            ?>
            <div class="fee-row shadow-sm" id="row_<?php echo $idx; ?>" onclick="toggleCheck(<?php echo $idx; ?>)">
                <div class="me-3">
                    <input class="form-check-input m3-checkbox" type="checkbox" id="rex<?php echo $idx; ?>"
                        onclick="event.stopPropagation(); syncPayAmt();">
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark text-truncate" id="peng<?php echo $idx; ?>">
                        <?php echo $row['particulareng']; ?>
                    </div>
                    <div class="text-muted small" style="font-size: 0.65rem;" id="pben<?php echo $idx; ?>">
                        <?php echo $row['particularben']; ?>
                    </div>
                    <span id="fid<?php echo $idx; ?>" hidden><?php echo $row['id']; ?></span>
                </div>
                <div class="text-end">
                    <div class="amt-text" id="amt_val_<?php echo $idx; ?>"><?php echo number_format($row['dues'], 0); ?>
                    </div>
                    <?php if ($is_fine): ?>
                        <i class="bi bi-trash text-danger"
                            onclick="event.stopPropagation(); mergerow(<?php echo $row['id']; ?>, 0, 4);"></i>
                    <?php endif; ?>
                </div>
            </div>
            <?php $idx++; endwhile;
        $stmt_due->close(); ?>
    </div>
</main>

<div id="modal_container"><?php include 'component/m3-modals.php'; ?></div>



<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-black text-primary"><i class="bi bi-clock-history me-2"></i>Payment History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 m3-scroll-body">
                <div id="history_content">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<?php include 'footer.php'; ?>

<script>
    document.getElementById("total_dues_label").innerText = new Intl.NumberFormat().format(<?php echo $total_calc; ?>);

    function toggleCheck(id) {
        const chk = document.getElementById("rex" + id);
        chk.checked = !chk.checked;
        document.getElementById("row_" + id).classList.toggle('selected', chk.checked);
        syncPayAmt();
    }

    function syncPayAmt() {
        let total = 0;
        let count = <?php echo $idx; ?>;
        let selectedCount = 0;
        for (let i = 0; i < count; i++) {
            if (document.getElementById("rex" + i).checked) {
                total += parseFloat(document.getElementById("amt_val_" + i).innerText.replace(/,/g, ''));
                selectedCount++;
            }
        }
        document.getElementById("amt").value = total;
        // Hidden input for count if needed
    }


    function save() {
        const payAmt = parseFloat(document.getElementById("amt").value);
        const prdate = document.getElementById("prdate").value;
        const prno = document.getElementById("prno").value;
        const stid = '<?php echo $stid; ?>';
        const sessionyear = '<?php echo $current_session; ?>';

        // ১. ভ্যালিডেশন: অ্যামাউন্ট চেক
        if (payAmt <= 0) {
            Swal.fire({ icon: 'warning', title: 'Selection Required', text: 'Please, select atleast one item.' });
            return;
        }

        // ২. ডেটা অবজেক্ট তৈরি (পিএইচপি স্ক্রিপ্টের চাহিদা অনুযায়ী)
        let postData = {
            stid: stid,
            prno: prno,
            prdate: prdate,
            sessionyear: sessionyear,
            // মেটাডেটা (রিসিট ও SMS এর জন্য)
            cls: '<?php echo $si['classname']; ?>',
            sec: '<?php echo $si['sectionname']; ?>',
            rollno: '<?php echo $si['rollno']; ?>',
            nben: '<?php echo $st_profile['stnameben']; ?>',
            mobileno: '<?php echo $st_profile['guarmobile']; ?>',
            tail: 1
        };

        // ৩. নির্বাচিত আইটেমগুলো লুপের মাধ্যমে সংগ্রহ করা
        let selectedCount = 0;
        let totalIdx = <?php echo $idx; ?>; // পিএইচপি থেকে আসা লুপ কাউন্ট

        for (let i = 0; i < totalIdx; i++) {
            const chk = document.getElementById("rex" + i);
            if (chk && chk.checked) {
                // আইটেম আইডি এবং অ্যামাউন্ট আলাদা আলাদা কী (Key) হিসেবে সেট করা
                postData['fid' + selectedCount] = document.getElementById("fid" + i).innerText;
                postData['amt' + selectedCount] = document.getElementById("amt_val_" + i).innerText.replace(/,/g, '');
                selectedCount++;
            }
        }

        // পিএইচপি-র প্রয়োজনীয় 'count' ভ্যারিয়েবল সেট করা
        postData['count'] = selectedCount;

        // ৪. কনফার্মেশন ও AJAX সাবমিশন
        Swal.fire({
            title: 'Confirm Payment?',
            html: `Are you sure you want to pay ৳<b>${new Intl.NumberFormat().format(payAmt)}</b> ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2E7D32',
            confirmButtonText: 'Yes, Pay Now!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "backend/save-pr.php",
                    data: postData, // আমাদের তৈরি করা ডাইনামিক অবজেক্ট
                    beforeSend: function () {
                        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                    },
                    success: function (response) {
                        if (response.trim() === "success") {
                            Swal.fire({ icon: 'success', title: 'Success!', timer: 1500, showConfirmButton: false })
                                .then(() => {
                                    // রিসিট পেজে রিডাইরেক্ট
                                    window.location.href = `stmoneyreceipt.php?prno=${prno}&stid=${stid}`;
                                });
                        } else {
                            Swal.fire('Error', 'Data saving failed: ' + response, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Disconnected from server', 'error');
                    }
                });
            }
        });
    }











    // function goedit() {
    //     window.location.href = `st-payment-setup-indivisual.php?stid=<?php echo $stid; ?>&year=<?php echo $current_session; ?>`;
    // }

    function goedit() {
    Swal.fire({
        icon: 'error',
        title: 'Access Denied',
        text: "You're now alloed to change this value",
        confirmButtonColor: '#B3261E', 
        confirmButtonText: 'OK'
    });
}
</script>






<script>
    function splitable0(fid, amt) {
        event.stopPropagation();
        document.getElementById("spltid").value = fid;
        document.getElementById("spltamt").value = amt;
        document.getElementById("spltamtpre").value = amt;
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            keyboard: false
        });
        myModal.show();
        const input = document.getElementById("spltamt");
        input.focus();
        input.select();
    }

    function splitable2() {
        event.stopPropagation();
        // document.getElementById("fine").value = fid;
        // document.getElementById("spltamt").value = amt;
        // document.getElementById("spltamtpre").value = amt;
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal2'), {
            keyboard: false
        });
        myModal.show();
        const input = document.getElementById("spltamt");
        input.focus();
        input.select();
    }


    function addFine() {
        event.stopPropagation();
        // document.getElementById("fine").value = fid;
        // document.getElementById("spltamt").value = amt;
        // document.getElementById("spltamtpre").value = amt;
        var myModal = new bootstrap.Modal(document.getElementById('fineModal'), {
            keyboard: false
        });
        myModal.show();
        const input = document.getElementById("fine_amount");
        input.focus();
        input.select();
    }

</script>
<script>

    function splitable() {

        var fid = document.getElementById("spltid").value;
        var amtpre = document.getElementById("spltamtpre").value * 1;
        var amt = document.getElementById("spltamt").value * 1;


        if (amt >= amtpre || amt <= 0 || amt == '') {
            alert('Invalid Amount');
            const input = document.getElementById("spltamt");
            input.focus();
            input.select();
            return;
        }

        var infor = "fid=" + fid + "&amt=" + amt + "&tail=1";
        // alert(infor);

        $("#history").html("");
        $.ajax({
            type: "POST",
            url: "backend/stfinance-item-split.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#history").html('<i class="mdi mdi-autorenew"></i>');
            },
            success: function (html) {
                $("#history").html(html);
                var modalEl = document.getElementById('exampleModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                window.location.reload();

            }
        });
    }




    function saveFine() {
        // alert('trigger');
        var fid = '<?php echo $stid; ?>';
        var amt = document.getElementById("fine_amount").value * 1;



        var infor = "fid=" + fid + "&amt=" + amt + "&tail=3";
        // alert(infor);

        $("#history").html("");
        $.ajax({
            type: "POST",
            url: "backend/stfinance-item-split.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#history").html('<i class="mdi mdi-autorenew"></i>');
            },
            success: function (html) {
                $("#history").html(html);
                var modalEl = document.getElementById('fineModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                window.location.reload();
                // window.location.reload();

            }
        });
    }
</script>


<script>


    function mergerow(id, amt, tail) {
        event.stopPropagation();
        var infor = "fid=" + id + "&amt=" + amt + "&tail=" + tail;

        $("#history").html("");
        $.ajax({
            type: "POST",
            url: "backend/stfinance-item-split.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#history").html('<i class="mdi mdi-autorenew"></i>');
            },
            success: function (html) {
                $("#history").html(html);
                window.location.reload();
            }
        });
    }



    function rollback(id, taka, tail) {
        var infor = "id=" + id + "&taka=" + taka + "&tail=" + tail;
        // alert(infor);
        $("#bbttnn" + id).html("");
        $.ajax({
            type: "POST",
            url: "backend/roll-back-st-finance-item.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#bbttnn" + id).html('<i class="mdi mdi-autorenew"></i>');
            },
            success: function (html) {
                $("#bbttnn" + id).html(html);
            }
        });
    }
</script>

<script>
    const button = document.getElementById("mybtn");
    const input = document.getElementById("spltamt");
    input.addEventListener("keyup", function (event) {
        if (event.key === "Enter") {
            button.click();
        }
    });
</script>


<script>
    function showHistory() {
        const stid = '<?php echo $stid; ?>';
        const sccode = '<?php echo $sccode; ?>';

        // মডাল ওপেন করা
        var historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
        historyModal.show();

        // অ্যাজাক্স কল
        $.ajax({
            url: "ajax/get-student-payment-history.php",
            type: "POST",
            data: { stid: stid, sccode: sccode },
            success: function (data) {
                $("#history_content").html(data);
                $(".m3-scroll-body").scrollTop(0); // নতুন ডাটা লোড হলে স্ক্রলবার উপরে চলে যাবে
            },
            error: function () {
                $("#history_content").html('<div class="alert alert-danger">Problem Loading Data</div>');
            }
        });
    }
</script>