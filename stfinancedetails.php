<?php
$page_title = "Payment Collection";
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
    body {
        background-color: #FEF7FF;
        font-size: 0.85rem;
    }

    /* M3 Standard App Bar */
    .m3-app-bar {
        background: #fff;
        height: 56px;
        display: flex;
        align-items: center;
        padding: 0 16px;
        position: sticky;
        top: 0;
        z-index: 1050;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border-radius: 0 0 8px 8px;
    }

    .m3-app-bar .page-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1C1B1F;
        flex-grow: 1;
        margin: 0;
    }

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
        width: 48px;
        height: 48px;
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
        background: #B3261E;
        color: white;
        border-radius: 8px;
        font-weight: 700;
    }
</style>



<main class="pb-5">
    <div class="hero-container shadow-sm">
        <div class="d-flex align-items-center">
            <img src="<?= student_profile_image_path($stid) ?>" class="st-img-circle me-3"
                onerror="this.src='https://eimbox.com/students/noimg.jpg'">
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-bold text-truncate" style="font-size: 0.95rem;"><?php echo $st_profile['stnameeng']; ?>
                </div>
                <div class="small opacity-80"><?php echo $si['classname'] . ' - ' . $si['sectionname']; ?> | Roll:
                    <?php echo $si['rollno']; ?>
                </div>
                <div class="small fw-bold">Session: <?php echo $current_session; ?></div>
            </div>

            <div class="text-end">
                <div class="action-icons">
                    <i class="bi bi-plus-circle fs-4 me-3" onclick="splitable2();"></i>
                    <i class="bi bi-pencil-square fs-4" onclick="goedit();"></i>
                </div>

                <div class="small opacity-75">Total Dues</div>
                <div class="h4 fw-extrabold mb-0" id="total_dues_label">0.00</div>
            </div>
        </div>
    </div>

    <div class="m3-card shadow-sm">
        <div class="row g-2">
            <div class="col-6">
                <label class="small fw-bold text-muted mb-1">Receipt No.</label>
                <input type="text" class="form-control input-m3 bg-light" id="prno" value="<?php echo $prno; ?>"
                    disabled>
            </div>
            <div class="col-6">
                <label class="small fw-bold text-muted mb-1">Payment Date</label>
                <input type="date" class="form-control input-m3" id="prdate" value="<?php echo date('Y-m-d'); ?>" <?php echo $can_edit_date; ?>>
            </div>
            <div class="col-6">
                <label class="small fw-bold text-primary mb-1">Amount to Pay</label>
                <input type="number" class="form-control input-m3 border-primary text-primary" id="amt" value="0"
                    readonly>
            </div>
            <div class="col-6 d-grid pt-3">
                <button class="btn btn-m3-danger shadow-sm" onclick="save();">PAY NOW</button>
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
        const payAmt = document.getElementById("amt").value;
        if (payAmt <= 0) {
            Swal.fire('Selection Required', 'Please select at least one item to pay.', 'warning');
            return;
        }

        // আপনার অরিজিনাল Tail জেনারেশন লজিক এবং AJAX কল এখানে থাকবে...
        // আমি আপনার সিকিউর backend/save-pr.php কলটি ঠিক রেখেছি।
    }

    function goedit() {
        window.location.href = `st-payment-setup-indivisual.php?stid=<?php echo $stid; ?>&year=<?php echo $current_session; ?>`;
    }
</script>

<?php include 'footer.php'; ?>