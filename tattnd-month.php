<?php
$page_title = "Monthly Teacher Attendance";
require_once "inc.php";

$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

$start = "$year-$month-01";
$end = date("Y-m-t", strtotime($start));

/* -----------------------
   Weekend Settings
------------------------ */
$weekendDays = [];
foreach ($ins_all_settings as $row) {
    if ($row['setting_title'] === 'Weekends') {
        $weekendDays = explode('.', trim($row['settings_value']));
        break;
    }
}

/* -----------------------
   Teachers
------------------------ */
$tq = $conn->prepare("
    SELECT tid, tname 
    FROM teacher 
    WHERE sccode=? 
    ORDER BY tid DESC
");
$tq->bind_param("i", $sccode);
$tq->execute();
$teachers = $tq->get_result()->fetch_all(MYSQLI_ASSOC);

/* -----------------------
   Attendance
------------------------ */
$aq = $conn->prepare("
    SELECT tid, adate, statusin, statusout,
           detectin, detectout,
           disin, disout
    FROM teacherattnd
    WHERE sccode=? 
      AND adate BETWEEN ? AND ?
");
$aq->bind_param("iss", $sccode, $start, $end);
$aq->execute();
$ar = $aq->get_result();

$att = [];
while ($r = $ar->fetch_assoc()) {
    $att[$r['tid']][$r['adate']] = $r;
}

/* -----------------------
   Leave
------------------------ */
$lq = $conn->prepare("
    SELECT tid, date_from, date_to, leave_type
    FROM teacher_leave_app
    WHERE sccode=? 
      AND status=1
      AND date_from <= ?
      AND date_to >= ?
");
$lq->bind_param("iss", $sccode, $end, $start);
$lq->execute();
$lr = $lq->get_result();

$leave = [];
while ($r = $lr->fetch_assoc()) {
    $cur = $r['date_from'];
    while ($cur <= $r['date_to']) {
        $leave[$r['tid']][$cur] = strtolower($r['leave_type']);
        $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
    }
}

/* -----------------------
   Calendar
------------------------ */
$cq = $conn->prepare("
    SELECT date, dateto, category, work 
    FROM calendar
    WHERE sccode=? 
      AND (
           date BETWEEN ? AND ?
        OR dateto BETWEEN ? AND ?
      )
");
$cq->bind_param("issss", $sccode, $start, $end, $start, $end);
$cq->execute();
$cr = $cq->get_result();

$cal = [];
while ($r = $cr->fetch_assoc()) {

    $to = $r['dateto'] ?: $r['date'];
    $cur = $r['date'];

    while ($cur <= $to) {
        $cal[$cur] = $r;
        $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
    }
}

/* -----------------------
   Date Range
------------------------ */
$dates = [];
$d = $start;
while ($d <= $end) {
    $dates[] = $d;
    $d = date("Y-m-d", strtotime("+1 day", strtotime($d)));
}
?>



<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-tonal: #EADDFF;
        --m3-on-surface: #1C1B1F;
        --m3-outline-variant: #CAC4D0;
        --m3-surface-container: #F3EDF7;

        /* Attendance Colors (Tonal) */
        --att-present: #E8F5E9;
        --att-present-text: #2E7D32;
        --att-absent: #F9DEDC;
        --att-absent-text: #B3261E;
        --att-leave: #FFF8E1;
        --att-leave-text: #FF8F00;
        --att-holiday: #F3EDF7;
        --att-holiday-text: #6750A4;
        --att-weekend: #F1F0F4;
        --att-weekend-text: #49454F;
    }



    /* Floating Filter Bar */
    .filter-card {
        background: white;
        border-radius: 8px;
        padding: 12px;
        margin: -30px 20px 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
    }

    /* Table Container with Sticky Column */
    .m3-attendance-wrapper {
        margin: 0 12px;
        background: white;
        border-radius: 8px;
        border: 1px solid var(--m3-outline-variant);
        overflow: hidden;
    }

    .table-responsive {
        max-height: 70vh;
        overflow: auto;
    }

    .m3-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .m3-table thead th {
        background: var(--m3-surface-container);
        color: var(--m3-primary);
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 12px 8px;
        border: none;
        position: sticky;
        top: 0;
        z-index: 20;
    }

    /* Teacher Name Sticky Column */
    .sticky-col {
        position: sticky;
        left: 0;
        z-index: 30;
        background: white;
        border-right: 2px solid var(--m3-outline-variant) !important;
        min-width: 150px;
        text-align: left !important;
        padding-left: 15px !important;
    }

    thead th.sticky-col {
        background: var(--m3-surface-container);
        z-index: 40;
    }

    /* Status Cell Styling */
    .att-cell {
        width: 34px;
        height: 34px;
        font-weight: 400;
        font-size: 0.75rem;
        transition: transform 0.2s;
        border: 1px solid #f8f8f8 !important;
    }

    .att-cell:hover {
        transform: scale(1.1);
        z-index: 5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Dynamic Classes from your Logic */
    .present {
        background: var(--att-present) !important;
        color: var(--att-present-text) !important;
    }

    .absent {
        background: var(--att-absent) !important;
        color: var(--att-absent-text) !important;
    }

    .leave {
        background: var(--att-leave) !important;
        color: var(--att-leave-text) !important;
    }

    .holiday {
        background: var(--att-holiday) !important;
        color: var(--att-holiday-text) !important;
    }

    .weekend {
        background: var(--att-weekend) !important;
        color: var(--att-weekend-text) !important;
    }

    .late {
        background: #E0F2F1 !important;
        color: #00796B !important;
    }

    /* Legend Footer */
    .legend-box {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding: 15px;
        justify-content: center;
    }

    .legend-item {
        font-size: 0.65rem;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 100px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .sticky-col,
    .att-cell {
        cursor: pointer;
    }

    .sticky-col:hover {
        text-decoration: underline;
        color: var(--m3-primary);
    }
</style>


<div class="hero-container">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-black m-0">Teacher Attendance</h5>
            <p class="small m-0 opacity-75"><?= date("F Y", strtotime($start)) ?></p>
        </div>
        <i class="bi bi-person-check fs-1 opacity-25"></i>
    </div>
</div>

<div class="filter-card">
    <form class="row g-2">
        <div class="col-5">
            <select name="month" class="form-select border-0 bg-light">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= sprintf('%02d', $m) ?>" <?= ($month == $m ? 'selected' : '') ?>>
                        <?= date("F", strtotime("$year-$m-01")) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-5">
            <select name="year" class="form-select border-0 bg-light">
                <?php for ($y = date('Y') - 4; $y <= date('Y') + 0; $y++): ?>
                    <option <?= ($year == $y ? 'selected' : '') ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-2">
            <button class="btn btn-primary w-100 shadow-sm border-0" style="background: var(--m3-primary);">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </form>
</div>

<div class="m3-attendance-wrapper shadow-sm">
    <div class="table-responsive">
        <table class="table m3-table table-sm">
            <thead>
                <tr>
                    <th class="sticky-col">Teacher Name</th>
                    <?php foreach ($dates as $dt): ?>
                        <th><?= date('d', strtotime($dt)) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($teachers as $t):
                    // পরিসংখ্যান হিসাব করুন
                    $p = 0;
                    $a = 0;
                    $l = 0;
                    $lv = 0;
                    foreach ($dates as $dt) {
                        $dayName = date('l', strtotime($dt));
                        if (isset($leave[$t['tid']][$dt]))
                            $lv++;
                        elseif (isset($att[$t['tid']][$dt])) {
                            $stin = $att[$t['tid']][$dt]['statusin'];
                            if ($stin == 'fast' || $stin == 'late')
                                $p++; // আপনার লজিক অনুযায়ী
                            if ($stin == 'ABSENT')
                                $a++;
                        }
                    }
                    ?>
                    <tr>
                        <td class="sticky-col text-dark teacher-click" data-tid="<?= $t['tid'] ?>" data-tname="<?= $t['tname'] ?>" data-p="<?= $p ?>"
                            data-a="<?= $a ?>" data-lv="<?= $lv ?>">
                            <?= $t['tname'] ?>
                        </td>


                        <?php foreach ($dates as $dt):

                            $cls = '';
                            $icon = '';

                            $dayName = date('l', strtotime($dt));

                            /* -----------------------
                               Holiday
                            ------------------------ */
                            if (isset($cal[$dt]) && $cal[$dt]['work'] == 0) {
                                $cls = 'holiday';
                                $icon = '<i class="bi bi-calendar-x"></i>';
                            }

                            /* -----------------------
                               Weekend
                            ------------------------ */ elseif (in_array($dayName, $weekendDays)) {
                                $cls = 'weekend';
                                $icon = '<i class="bi bi-calendar2-week"></i>';
                            }

                            /* -----------------------
                               Leave (priority after holiday/weekend)
                            ------------------------ */
                            if (isset($leave[$t['tid']][$dt])) {

                                $lv = strtolower($leave[$t['tid']][$dt]);

                                if (str_contains($lv, 'medical')) {
                                    $cls = 'leave';
                                    $icon = '<i class="bi bi-heart-pulse text-danger"></i>';
                                } else {
                                    $cls = 'leave';
                                    $icon = '<i class="bi bi-person-dash text-warning"></i>';
                                }
                            }

                            /* -----------------------
                               Attendance (DO NOT override leave)
                            ------------------------ */
                            if (!isset($leave[$t['tid']][$dt]) && isset($att[$t['tid']][$dt])) {

                                $a = $att[$t['tid']][$dt];

                                $stin = strtoupper($a['statusin']);
                                $stout = strtoupper($a['statusout']);

                                if ($stin == 'ABSENT') {
                                    $cls = 'absent';
                                    $icon = '<i class="bi bi-x-circle-fill text-danger"></i>';

                                } elseif ($stin == 'LATE') {
                                    $cls = 'late';
                                    $icon = '<i class="bi bi-exclamation-triangle text-warning"></i>';

                                } elseif ($stin == 'FAST') {
                                    $cls = 'present';
                                    $icon = '<i class="bi bi-check-circle text-success"></i>';
                                }

                                if ($stout == 'FAST') {
                                    $cls = 'late';
                                    $icon = '<i class="bi bi-exclamation-triangle text-warning"></i>';
                                }

                                if ($stout == 'LATE') {
                                    $cls = 'present';
                                    $icon = '<i class="bi bi-check-circle text-success"></i>';
                                }
                            }

                            /* -----------------------
                               Absent fallback
                            ------------------------ */
                            if ($icon == '' && !in_array($dayName, $weekendDays)) {
                                $cls = 'absent';
                                $icon = '<i class="bi bi-x-circle-fill text-danger"></i>';
                            }






                            // লুপের ভেতর যেখানে কন্ডিশন চেক করছেন সেখানে $detail সেট করুন:
                            $detail = "";
                            $header = "Record Detail";

                            if (isset($cal[$dt]) && $cal[$dt]['work'] == 0) {
                                $header = "Holiday";
                                $detail = "Event: " . $cal[$dt]['category'];
                            } elseif (in_array($dayName, $weekendDays)) {
                                $header = "Weekend";
                                $detail = "Day: " . $dayName;
                            } elseif (isset($leave[$t['tid']][$dt])) {
                                $header = "Leave";
                                $detail = "Type: " . strtoupper($leave[$t['tid']][$dt]);
                            } elseif (isset($att[$t['tid']][$dt])) {
                                $a = $att[$t['tid']][$dt];
                                $header = date('d M, Y', strtotime($dt));
                                $detail = "In: " . ($a['detectin'] ?: '--:--') . " (" . $a['statusin'] . ")<br>" .
                                    "Out: " . ($a['detectout'] ?: '--:--') . " (" . $a['statusout'] . ")";
                            }
                            ?>


                            <td class="att-cell <?= $cls ?> cell-click" data-header="<?= $header ?>" data-info="<?= $detail ?>">
                                <?= $icon ?>
                            </td>
                        <?php endforeach; ?>





                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="legend-box">
    <div class="legend-item present">P: Present</div>
    <div class="legend-item absent">A: Absent</div>
    <div class="legend-item late">L: Late</div>
    <div class="legend-item leave">LV: Leave</div>
    <div class="legend-item holiday">H: Holiday</div>
    <div class="legend-item weekend">W: Weekend</div>
</div>



<div class="modal fade" id="cellDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content modal-content-m3 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold mb-0" id="detailHeader">Attendance Detail</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent" class="text-center py-2"></div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="teacherSummaryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-m3 border-0 shadow-lg" style="border-radius: 28px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold mb-0">Attendance Summary</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h5 class="fw-black text-primary mb-3 text-center" id="summaryTName"></h5>
                <div class="row g-2 mb-4" id="summaryStats"></div>

                <div class="text-center mt-2">
                    <a id="viewFullLogsBtn" href="#" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm"
                        style="letter-spacing: 1px;">
                        <i class="bi bi-journal-text me-2"></i> VIEW FULL LOGS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include 'footer.php'; ?>

<script>

</script>


<script>
    $(document).ready(function () {
        // ১. সেলে ক্লিক করলে বিস্তারিত দেখানো
        $('.cell-click').on('click', function () {
            let header = $(this).data('header');
            let info = $(this).data('info');

            $('#detailHeader').text(header);
            $('#detailContent').html(info || 'No record found');
            $('#cellDetailModal').modal('show');
        });

        // ২. টিচারের নামে ক্লিক করলে পরিসংখ্যান দেখানো
        $('.teacher-click').on('click', function () {
            // ডাটা রিসিভ করা
            let tid = $(this).data('tid'); // নিশ্চিত করুন আপনার HTML-এ data-tid আছে
            let name = $(this).data('tname');
            let p = $(this).data('p');
            let a = $(this).data('a');
            let lv = $(this).data('lv');

            // টেক্সট এবং পরিসংখ্যান সেট করা
            $('#summaryTName').text(name);
            $('#summaryStats').html(`
                <div class="col-4 text-center">
                    <div class="p-2 bg-success-subtle rounded-4"><b class="d-block fs-4">${p}</b><span>Present</span></div>
                </div>
                <div class="col-4 text-center">
                    <div class="p-2 bg-danger-subtle rounded-4"><b class="d-block fs-4">${a}</b><span>Absent</span></div>
                </div>
                <div class="col-4 text-center">
                    <div class="p-2 bg-warning-subtle rounded-4"><b class="d-block fs-4">${lv}</b><span>Leave</span></div>
                </div>
            `);

            // লিংকে ডাইনামিকভাবে TID সেট করা
            $('#viewFullLogsBtn').attr('href', `tattnd-tid.php?tid=${tid}`);

            $('#teacherSummaryModal').modal('show');
        });
    });
</script>

</body>

</html>