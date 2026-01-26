<?php
$page_title = "Cash Book Ledger";
include 'inc.php';

// ১. ফিল্টার প্যারামিটার
$dtst = $_GET['dtst'] ?? $_COOKIE['dtst'] ?? date('Y-m-01');
$dted = $_GET['dted'] ?? $_COOKIE['dted'] ?? date('Y-m-d');

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

    .session-pill-date:hover, .period-chip:hover {
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
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;"
                onclick="location.reload()">
                <i class="bi bi-arrow-repeat"></i>
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
            <?php while ($row0 = $result0->fetch_assoc()):
                $cnt++;
                $mottaka += $row0['amount'];
                $id = $row0['id'];
                $type = $row0['type'];
                $tk = $row0['amount'];

                // হেড নাম ফেচিং
                $head_q = $conn->query("SELECT particulareng FROM financesetup WHERE id='{$row0['partid']}'");
                $head_name = $head_q->num_rows ? $head_q->fetch_assoc()['particulareng'] : "General";
                ?>
                <div class="m3-list-item trans-card shadow-sm mx-0"  id="block<?= $id ?>">
                    <div class="icon-box <?= ($type == 'Income' ? 'c-inst' : 'c-exit') ?>"
                        style="width: 44px; height: 44px; border-radius: 10px;">
                        <i class="bi <?= ($type == 'Income' ? 'bi-arrow-down-left-circle' : 'bi-arrow-up-right-circle') ?>"></i>
                    </div>

                    <div class="item-info">
                        <div class="st-title" style="font-size: 0.95rem; font-weight: 850;"><?= $head_name ?></div>
                        <div class="st-desc" style="font-size: 0.75rem; color: #555;"><?= $row0['particulars'] ?></div>

                        <div class="mt-2 d-flex align-items-center gap-2">
                            <span class="period-chip shadow-sm"
                                onclick="openMonthYearModal(<?= $id ?>, '<?= $row0['month'] ?>', '<?= $row0['year'] ?>')">
                                <i class="bi bi-clock-history"></i> <?= $row0['month'] . '/' . $row0['year'] ?>
                            </span>
                            <div style="font-size: 0.6rem; color: #999; font-weight: 700; text-transform: uppercase;">
                                MEMO: <?= $row0['memono'] ?: 'N/A' ?> | <i class="bi bi-event me-1"></i>
                                <?= date('d M, Y', strtotime($row0['date'])) ?>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <div class="<?= ($type == 'Income' ? 'amt-income' : 'amt-expense') ?>">
                            <?= ($type == 'Income' ? '+' : '-') ?>৳<?= number_format($tk, 0) ?>
                        </div>
                        <div style="font-size: 0.55rem; color: #ccc; font-weight: 700; margin-top: 5px;">
                            ID: #<?= $id ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
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

        document.cookie = "dtst=" + from + ";path=/";
        document.cookie = "dted=" + to + ";path=/";

        location.reload();
    });

</script>