<?php
$page_title = "Cash Book Ledger";
include 'inc.php';

$report_type = [
    "date" => "Date Based Report",
    "head" => 'Account Head Based Report'
];
// ১. ফিল্টার প্যারামিটার
$dtst = $_GET['dtst'] ?? $_COOKIE['dtst'] ?? date('Y-m-01');
$dted = $_GET['dted'] ?? $_COOKIE['dted'] ?? date('Y-m-d');
$report_style = $_GET['report_type'] ?? $_COOKIE['report_type'] ?? 'date';

// month input এর জন্য
if (
    date('Y-m-01', strtotime($dtst)) ==
    date('Y-m-01', strtotime($dted))
) {
    $month = date('Y-m', strtotime($dtst));
} else {
    $month = date('Y-m');
}

// ২. ডাটা ফেচিং
$sql0 = "SELECT id, date, type, partid, particulars, amount, entrytime, entryby, 
                memono, refno, month, year
         FROM cashbook
         WHERE sccode='$sccode'
         AND date BETWEEN '$dtst' AND '$dted'
         ORDER BY entrytime DESC";

$result0 = $conn->query($sql0);

$cnt = 0;
$mottaka = 0;



$sub_heads = $conn->query("SELECT s.id, s.sub_head, h.account_head, s.account_head_id
                           FROM account_sub_head s 
                           LEFT JOIN account_head h ON s.account_head_id = h.id 
                           WHERE s.sccode='$sccode' ORDER BY h.account_head, s.sub_head");

// সাব-হেড ডেটা লোড করার পর এই অংশটুকু যোগ করুন
$sub_heads_map = [];
$sub_heads->data_seek(0); // পয়েন্টার শুরুতে নেওয়া
while ($sh = $sub_heads->fetch_assoc()) {
    $sub_heads_map[$sh['id']] = [
        'sub_name' => $sh['sub_head'],
        'main_name' => $sh['account_head']
    ];
}

?>

<style>
    /* ১. হিরো সেকশন স্পেসিফিক */
    .hero-ledger {
        padding-bottom: 35px;
    }

    /* ২. ট্রানজ্যাকশন কার্ড */
    .trans-card {
        padding: 14px 16px;
        margin-bottom: 12px;
        border: 1px solid rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .amt-income {
        color: #2E7D32;
        font-weight: 900;
        font-size: 1.1rem;
    }

    .amt-expense {
        color: #B3261E;
        font-weight: 900;
        font-size: 1.1rem;
    }

    /* ৩. পিরিয়ড চিপ (Actionable) */
    .period-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--m3-tonal-container);
        color: var(--m3-primary);
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        cursor: pointer;
        transition: 0.2s;
        border: 1px solid transparent;
    }

    .period-chip:active {
        transform: scale(0.95);
        background: var(--m3-primary);
        color: white;
    }

    .session-pill-date {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #E3F2FD, #BBDEFB);
        color: #0D47A1;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: .65rem;
        font-weight: 800;
        cursor: pointer;
        user-select: none;
        box-shadow: 0 2px 6px rgba(13, 71, 161, .15);
        transition: .2s ease;
    }

    .session-pill-date:hover,
    .period-chip:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(13, 71, 161, .25);
    }


    /* ৪. মডাল কাস্টমাইজেশন */
    .m3-modal-content {
        border-radius: 28px;
        padding: 20px;
        border: none;
    }
</style>

<main>
    <div class="hero-container hero-ledger">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div style="display: flex; align-items: center; gap: 12px;">

                <div>
                    <div style="font-size: 1.4rem; font-weight: 950; line-height: 1.1;">Cash Ledger</div>
                    <div style="font-size: 0.75rem; opacity: 0.85; font-weight: 600;">Full Transaction View</div>
                </div>
            </div>
            <div class="tonal-icon-btn"
                style="background: rgba(255,255,255,0.2); z-index:9999; color: #fff; border:none; margin:0; padding:0; font-size:24px;"
                onclick="cashbookviewpage();">
                <i class="bi bi-file-text-fill"></i>
            </div>
        </div>

        <div style="text-align: center; margin-top: 10px;">
            <div
                style="font-size: 0.65rem; font-weight: 800; opacity: 0.8; letter-spacing: 1px; text-transform: uppercase;">
                Total Volume in Period</div>
            <div style="font-size: 2.2rem; font-weight: 950;" id="hero_total_amt">৳ 0.00</div>
            <span class="session-pill-date" onclick="openDateRangeModal()">
                <i class="bi bi-calendar3"></i>
                <?= date('d M', strtotime($dtst)) ?> - <?= date('d M Y', strtotime($dted)) ?>
            </span>

        </div>
    </div>

    <div class="widget-grid" style="padding: 15px 12px 100px;">
        <div class="m3-section-title" style="margin-left: 5px;">Ledger Records</div>

        <?php if ($result0->num_rows): ?>

            <?php

            $ledger = [];

            while ($row0 = $result0->fetch_assoc()) {

                $date = $row0['date'];

                $headid = $row0['partid'];

                $main_head = $sub_heads_map[$headid]['main_name'] ?? 'Unknown';
                $sub_head = $sub_heads_map[$headid]['sub_name'] ?? 'Unknown';

                $ledger[$date][$main_head][$sub_head][] = $row0;
            }

            ?>



            <?php if ($report_style == 'date'): ?>

                <?php foreach ($ledger as $date => $heads): ?>

                    <?php $date_total = 0; ?>

                    <div class="card shadow-sm mb-4 border-0 rounded-4">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <div>
                                    <div style="font-size:1.1rem;font-weight:900;">
                                        <?= date('d M Y', strtotime($date)) ?>
                                    </div>

                                    <div style="font-size:.7rem;color:#777;">
                                        DATE BASED REPORT
                                    </div>
                                </div>

                            </div>


                            <?php foreach ($heads as $main_head => $subs): ?>

                                <div class="mb-3">

                                    <div class="fw-bold text-primary mb-2">
                                        <?= $main_head ?>
                                    </div>

                                    <?php foreach ($subs as $sub_head => $rows): ?>

                                        <div class="border rounded-3 p-2 mb-2">

                                            <div class="fw-bold mb-2" style="font-size:.85rem;">
                                                <?= $sub_head ?>
                                            </div>

                                            <?php foreach ($rows as $r): ?>

                                                <?php

                                                $amt = $r['amount'];

                                                if ($r['type'] == 'Expense') {
                                                    $amt = -$amt;
                                                }

                                                $date_total += $amt;

                                                ?>

                                                <div class="d-flex justify-content-between border-bottom py-2">

                                                    <div>

                                                        <div style="font-weight:700;font-size:.8rem;">
                                                            <?= $r['particulars'] ?>
                                                        </div>

                                                        <div style="font-size:.65rem;color:#888;">
                                                            Memo: <?= $r['memono'] ?: 'N/A' ?>
                                                        </div>

                                                    </div>

                                                    <div class="<?= ($r['type'] == 'Income' ? 'amt-income' : 'amt-expense') ?>">
                                                        <?= ($r['type'] == 'Income' ? '+' : '-') ?>
                                                        ৳<?= number_format($r['amount'], 0) ?>
                                                    </div>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endforeach; ?>

                            <div class="text-end mt-3">

                                <div class="fw-bold">
                                    SUB TOTAL :
                                    ৳<?= number_format($date_total, 2) ?>
                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>

                <?php

                $head_based = [];

                foreach ($ledger as $date => $heads) {

                    foreach ($heads as $main_head => $subs) {

                        foreach ($subs as $sub_head => $rows) {

                            foreach ($rows as $r) {

                                $head_based[$main_head][$sub_head][$date][] = $r;
                            }
                        }
                    }
                }

                ?>

                <?php foreach ($head_based as $main_head => $subs): ?>

                    <?php $head_total = 0; ?>

                    <div class="card shadow-sm mb-4 border-0 rounded-4">

                        <div class="card-body">

                            <div class="mb-3">

                                <div style="font-size:1.1rem;font-weight:900;">
                                    <?= $main_head ?>
                                </div>

                                <div style="font-size:.7rem;color:#777;">
                                    ACCOUNT HEAD BASED REPORT
                                </div>

                            </div>

                            <?php foreach ($subs as $sub_head => $dates): ?>

                                <div class="border rounded-3 p-3 mb-3">

                                    <div class="fw-bold text-primary mb-2">
                                        <?= $sub_head ?>
                                    </div>

                                    <?php foreach ($dates as $date => $rows): ?>

                                        <div class="mb-2">

                                            <div class="fw-bold mb-2" style="font-size:.8rem;">
                                                <?= date('d M Y', strtotime($date)) ?>
                                            </div>

                                            <?php foreach ($rows as $r): ?>

                                                <?php

                                                $amt = $r['amount'];

                                                if ($r['type'] == 'Expense') {
                                                    $amt = -$amt;
                                                }

                                                $head_total += $amt;

                                                ?>

                                                <div class="d-flex justify-content-between border-bottom py-2">

                                                    <div>

                                                        <div style="font-weight:700;font-size:.8rem;">
                                                            <?= $r['particulars'] ?>
                                                        </div>

                                                        <div style="font-size:.65rem;color:#888;">
                                                            Memo: <?= $r['memono'] ?: 'N/A' ?>
                                                        </div>

                                                    </div>

                                                    <div class="<?= ($r['type'] == 'Income' ? 'amt-income' : 'amt-expense') ?>">
                                                        <?= ($r['type'] == 'Income' ? '+' : '-') ?>
                                                        ৳<?= number_format($r['amount'], 0) ?>
                                                    </div>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endforeach; ?>

                            <div class="text-end">

                                <div class="fw-bold">
                                    HEAD TOTAL :
                                    ৳<?= number_format($head_total, 2) ?>
                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        <?php else: ?>

            <div style="text-align: center; padding: 80px 20px; opacity: 0.4;">
                <i class="bi bi-search" style="font-size: 3.5rem;"></i>
                <div style="font-weight: 800; margin-top: 10px;">No Data Found</div>
            </div>

        <?php endif; ?>
    </div>
</main>



<div class="modal fade" id="monthYearModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-modal-content shadow-lg">
            <h5 class="fw-bold mb-4" style="color: var(--m3-primary);"><i class="bi bi-calendar-event me-2"></i>Adjust
                Period</h5>

            <form id="monthYearForm">
                <input type="hidden" id="my_entry_id">

                <div class="row g-2">
                    <div class="col-6">
                        <div class="m3-floating-group">
                            <i class="bi bi-calendar-month m3-field-icon"></i>
                            <input type="number" id="my_month" min="1" max="12" class="m3-input-floating"
                                placeholder=" ">
                            <label class="m3-floating-label">MONTH</label>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="m3-floating-group">
                            <i class="bi bi-calendar-check m3-field-icon"></i>
                            <input type="number" id="my_year" class="m3-input-floating" placeholder=" ">
                            <label class="m3-floating-label">YEAR</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-light flex-fill py-2"
                        style="border-radius: 12px; font-weight: 700;" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary flex-fill py-2"
                        style="border-radius: 12px; font-weight: 700;">UPDATE DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="dateRangeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">

            <h5 class="fw-bold mb-3">Select Date Range</h5>

            <form id="dateRangeForm">

                <div class="row g-2">

                    <div class="col-6">
                        <label class="small fw-bold">From</label>
                        <input type="date" id="dr_from" class="form-control" value="<?= $dtst ?>">
                    </div>

                    <div class="col-6">
                        <label class="small fw-bold">To</label>
                        <input type="date" id="dr_to" class="form-control" value="<?= $dted ?>">
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">
                        <label class="small fw-bold">or, Choose Month</label>
                        <input type="month" id="dr_month" class="form-control" value="<?= $month ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label class="small fw-bold">Report Style</label>
                        <select id="report_type" class="form-control">

                            <?php foreach ($report_type as $k => $v): ?>

                                <option value="<?= $k ?>" <?= ($report_style == $k ? 'selected' : '') ?>>
                                    <?= $v ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>

            </form>

        </div>
    </div>
</div>




<?php include 'footer.php'; ?>

<script>
    // ৪. হিরো সেকশনে টোটাল আপডেট
    document.getElementById("hero_total_amt").innerText = "৳ <?= number_format($mottaka, 2) ?>";

    const myModal = new bootstrap.Modal('#monthYearModal');

    // মডাল ওপেন
    function openMonthYearModal(id, month, year) {
        $('#my_entry_id').val(id);
        $('#my_month').val(month);
        $('#my_year').val(year);
        myModal.show();
    }

    // AJAX সাবমিট
    $('#monthYearForm').on('submit', function (e) {
        e.preventDefault();

        const btn = $(this).find('button[type="submit"]');
        btn.html('<span class="spinner-border spinner-border-sm"></span>');

        $.post('ajax/ajax-update-monthyear.php', {
            id: $('#my_entry_id').val(),
            month: $('#my_month').val(),
            year: $('#my_year').val()
        }, function () {
            myModal.hide();
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Transaction period has been updated.',
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        });
    });
</script>


<script>
    const dateRangeModal = new bootstrap.Modal('#dateRangeModal');

    function openDateRangeModal() {
        dateRangeModal.show();
    }

    $('#dateRangeForm').on('submit', function (e) {

        e.preventDefault();

        let from = $('#dr_from').val();
        let to = $('#dr_to').val();
        let month = $('#dr_month').val();
        let report_type = $('#report_type').val();

        // month select করলে auto from/to
        if (month) {

            from = month + '-01';

            let d = new Date(month + '-01');
            d.setMonth(d.getMonth() + 1);
            d.setDate(0);

            to = d.toISOString().split('T')[0];
        }

        document.cookie = "dtst=" + from + ";path=/";
        document.cookie = "dted=" + to + ";path=/";
        document.cookie = "report_type=" + report_type + ";path=/";

        location.reload();

    });

</script>

<script>
    function cashbookviewpage() {
        // alert('OK');
        window.location.href = 'cashbook.php';
    }
</script>