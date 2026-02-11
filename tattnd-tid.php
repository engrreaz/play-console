<?php
$page_title = "Teacher Attendance Detail";
require_once "inc.php";

$tid = $_GET['tid'] ?? 0;
if (!$tid)
    die("Teacher ID not specified");

// Month & Year
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
        $weekendDays = explode(' ', trim($row['settings_value']));
        break;
    }
}

/* -----------------------
   Teacher Info
------------------------ */
$tq = $conn->prepare("SELECT tid, tname FROM teacher WHERE tid=? AND sccode=?");
$tq->bind_param("ii", $tid, $sccode);
$tq->execute();
$teacher = $tq->get_result()->fetch_assoc();
if (!$teacher)
    die("Teacher not found");

/* -----------------------
   Attendance
------------------------ */
$aq = $conn->prepare("
    SELECT tid, adate, statusin, statusout, detectin, detectout, disin, disout, realin, realout
    FROM teacherattnd
    WHERE tid=? AND sccode=? AND adate BETWEEN ? AND ?
");
$aq->bind_param("iiss", $tid, $sccode, $start, $end);
$aq->execute();
$ar = $aq->get_result();

$att = [];
while ($r = $ar->fetch_assoc()) {
    $att[$r['adate']] = $r;
}

/* -----------------------
   Leave
------------------------ */
$lq = $conn->prepare("
    SELECT date_from, date_to, leave_type
    FROM teacher_leave_app
    WHERE tid=? AND sccode=? AND status=1 AND date_from <= ? AND date_to >= ?
");
$lq->bind_param("iiss", $tid, $sccode, $end, $start);
$lq->execute();
$lr = $lq->get_result();

$leave = [];
while ($r = $lr->fetch_assoc()) {
    $cur = $r['date_from'];
    while ($cur <= $r['date_to']) {
        $leave[$cur] = strtolower($r['leave_type']);
        $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
    }
}

/* -----------------------
   Calendar
------------------------ */
$cq = $conn->prepare("
    SELECT date, dateto, category, work 
    FROM calendar
    WHERE sccode=? AND (date BETWEEN ? AND ? OR dateto BETWEEN ? AND ?)
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
   Dates
------------------------ */
$dates = [];
$d = $start;
while ($d <= $end) {
    $dates[] = $d;
    $d = date("Y-m-d", strtotime("+1 day", strtotime($d)));
}

/* -----------------------
   Statistics
------------------------ */
$stats = ['present' => 0, 'absent' => 0, 'leave' => 0, 'late' => 0];
foreach ($dates as $dt) {
    $dayName = date('l', strtotime($dt));
    if (isset($leave[$dt]))
        $stats['leave']++;
    elseif (isset($att[$dt])) {
        $stin = strtolower($att[$dt]['statusin']);
        if ($stin == 'absent')
            $stats['absent']++;
        elseif ($stin == 'late')
            $stats['late']++;
        else
            $stats['present']++;
    } else {
        if (!in_array($dayName, $weekendDays))
            $stats['absent']++;
    }
}

?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-tonal: #EADDFF;
        --m3-on-tonal: #21005D;
        --m3-outline: #CAC4D0;

        /* Attendance Colors (Tonal) */
        --att-present: #E8F5E9;
        --att-present-text: #2E7D32;
        --att-absent: #F9DEDC;
        --att-absent-text: #B3261E;
        --att-leave: #FFF8E1;
        --att-leave-text: #FF8F00;
        --att-late: #FFF9C4;
        --att-late-text: #F57F17;
        --att-holiday: #F3EDF7;
        --att-holiday-text: #6750A4;
    }

    body {
        background: var(--m3-surface);
        font-family: 'Segoe UI', sans-serif;
    }

    /* Hero Section */
    .hero-container {
        margin: 12px;
        padding: 28px 20px;
        border-radius: 20px;
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(103, 80, 164, 0.2);
    }

    /* Stats Dashboard */
    .m3-stat-card {
        background: white;
        border-radius: 16px;
        padding: 12px;
        text-align: center;
        border: 1px solid #F0F0F0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .m3-stat-card b {
        font-size: 1.2rem;
        display: block;
        line-height: 1;
    }

    .m3-stat-card span {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #79747E;
    }

    /* Timeline Style Tiles */
    .attendance-tile {
        background: white;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        border: 1px solid #F0F0F0;
        transition: 0.2s;
    }

    .attendance-tile:active {
        transform: scale(0.98);
        background: #fafafa;
    }

    .date-box {
        min-width: 50px;
        text-align: center;
        border-right: 1px solid #EEE;
        margin-right: 15px;
        padding-right: 15px;
    }

    .date-box .day {
        font-size: 1.1rem;
        font-weight: 900;
        color: var(--m3-primary);
        line-height: 1;
    }

    .date-box .month {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #79747E;
    }

    .status-indicator {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 0.9rem;
        flex-shrink: 0;
        margin-right: 15px;
    }

    /* Dynamic Logic Classes */
    .present {
        background: var(--att-present);
        color: var(--att-present-text);
    }

    .absent {
        background: var(--att-absent);
        color: var(--att-absent-text);
    }

    .late {
        background: var(--att-late);
        color: var(--att-late-text);
    }

    .leave {
        background: var(--att-leave);
        color: var(--att-leave-text);
    }

    .holiday,
    .weekend {
        background: #F1F0F4;
        color: #49454F;
    }

    .time-info {
        font-size: 0.75rem;
        font-weight: 600;
        color: #79747E;
    }

    .time-info b {
        color: #1C1B1F;
    }

    .m3-chart-card {
        background: white;
        border-radius: 20px;
        padding: 16px;
        margin: 0 12px 20px;
        border: 1px solid #F0F0F0;
    }
</style>

<div class="hero-container">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-black m-0"><?= $teacher['tname'] ?></h5>
            <p class="small m-0 opacity-75"><?= date("F Y", strtotime($start)) ?></p>
        </div>
        <div class="text-end">
            <span class="badge rounded-pill bg-white text-primary fw-bold px-3">ID: <?= $tid ?></span>
        </div>
    </div>
</div>



<!-- Attendance Bar Chart -->



<div class="container-fluid px-3 mb-4">
    <div class="row g-2">
        <div class="col-3">
            <div class="m3-stat-card"><b><?= $stats['present'] ?></b><span>Present</span></div>
        </div>
        <div class="col-3">
            <div class="m3-stat-card"><b><?= $stats['absent'] ?></b><span>Absent</span></div>
        </div>
        <div class="col-3">
            <div class="m3-stat-card"><b><?= $stats['late'] ?></b><span>Late</span></div>
        </div>
        <div class="col-3">
            <div class="m3-stat-card"><b><?= $stats['leave'] ?></b><span>Leave</span></div>
        </div>
    </div>
</div>

<div class="m3-chart-card shadow-sm">
    <div class="small fw-bold text-muted mb-2 text-uppercase" style="letter-spacing: 1px;">Work Hours Trend</div>
    <canvas id="attChart" height="120"></canvas>
</div>


<div class="px-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-black text-muted small text-uppercase">Attendance Logs</span>
        <i class="bi bi-filter-right fs-4 text-primary"></i>
    </div>

    <div id="dayCards">
        <?php foreach ($dates as $dt):
            $cls = '';
            $icon = '';
            $dayName = date('l', strtotime($dt));

            // --- [আপনার কন্ডিশনাল লজিক] ---
            if (isset($cal[$dt]) && $cal[$dt]['work'] == 0) {
                $cls = 'holiday';
                $icon = 'H';
            } elseif (in_array($dayName, $weekendDays)) {
                $cls = 'weekend';
                $icon = 'W';
            } elseif (isset($leave[$dt])) {
                $cls = 'leave';
                $icon = 'LV';
            } elseif (isset($att[$dt])) {
                $stin = strtoupper($att[$dt]['statusin']);
                if ($stin == 'ABSENT') {
                    $cls = 'absent';
                    $icon = 'A';
                } elseif ($stin == 'LATE') {
                    $cls = 'late';
                    $icon = 'L';
                } else {
                    $cls = 'present';
                    $icon = 'P';
                }
            } else {
                $cls = 'absent';
                $icon = 'A';
            }
            ?>
            <div class="attendance-tile shadow-sm" data-date="<?= $dt ?>">
                <div class="date-box">
                    <div class="day"><?= date('d', strtotime($dt)) ?></div>
                    <div class="month"><?= date('D', strtotime($dt)) ?></div>
                </div>

                <div class="status-indicator <?= $cls ?>">
                    <?= $icon ?>
                </div>

                <div class="flex-grow-1">
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                        <?= (isset($cal[$dt]) && $cal[$dt]['work'] == 0) ? $cal[$dt]['category'] : $dayName ?>
                    </div>
                    <?php if (isset($att[$dt])): ?>
                        <div class="time-info mt-1">
                            In: <b><?= $att[$dt]['realin'] ?: '--:--' ?></b> |
                            Out: <b><?= $att[$dt]['realout'] ?: '--:--' ?></b>
                        </div>
                    <?php elseif (isset($leave[$dt])): ?>
                        <div class="time-info text-warning fw-bold">On Leave: <?= strtoupper($leave[$dt]) ?></div>
                    <?php endif; ?>
                </div>

                <i class="bi bi-chevron-right text-muted opacity-25"></i>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-4 mb-5">
        <button class="btn btn-outline-primary rounded-pill px-5 fw-bold" id="loadMore" data-month="<?= $month ?>"
            data-year="<?= $year ?>" data-tid="<?= $tid ?>">
            LOAD PREVIOUS MONTH
        </button>
    </div>
</div>










<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const weekendDays = <?= json_encode($weekendDays) ?>; // JS array

    const ctx = document.getElementById('attChart').getContext('2d');
    const chartData = {
        labels: [<?php foreach ($dates as $dt) {
            echo "'" . date('d', strtotime($dt)) . "',";
        } ?>],
        datasets: [{
            label: 'Attendance',
            data: [
                <?php foreach ($dates as $dt) {
                    if (isset($att[$dt])) {
                        $startTime = strtotime($att[$dt]['realin'] ?: '09:30');
                        $endTime = strtotime($att[$dt]['realout'] ?: '17:30');
                        echo (($endTime - $startTime) / 3600) . ",";
                    } else {
                        echo "0,";
                    }
                } ?>
            ],
            backgroundColor: [
                <?php foreach ($dates as $dt) {
                    $dayName = date('l', strtotime($dt));
                    if (isset($cal[$dt]) && $cal[$dt]['work'] == 0)
                        echo "'#ccc',";
                    elseif (in_array($dayName, $weekendDays))
                        echo "'#aaa',";
                    elseif (isset($leave[$dt]))
                        echo "'#FF8F00',";
                    elseif (isset($att[$dt])) {
                        $stin = strtolower($att[$dt]['statusin']);
                        if ($stin == 'late')
                            echo "'#FFEB3B',";
                        elseif ($stin == 'absent')
                            echo "'#F44336',";
                        else
                            echo "'#4CAF50',";
                    } else
                        echo "'#F44336',";
                } ?>
            ]
        }]
    };

    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Hours' } },
                x: { title: { display: true, text: 'Day' } }
            }
        }
    });

</script>




<script>
    $(function () {

        const $loadBtn = $('#loadMore');
        const $monthCards = $('#dayCards');

        $loadBtn.on('click', function () {

            let month = parseInt($loadBtn.data('month'));
            let year = parseInt($loadBtn.data('year'));
            const tid = $loadBtn.data('tid');

            // alert(tid);
            // আগের মাসের হিসাব
            month--;
            if (month < 1) { month = 12; year--; }

            $.ajax({
                url: 'teacher/tattnd-tid-ajax.php',
                type: 'GET',
                data: { tid, month, year },
                dataType: 'html',
                beforeSend: function () {
                    $loadBtn.prop('disabled', true).text('Loading...');
                },
                success: function (res) {
                    // নতুন মাসের কার্ডগুলো অ্যাপেন্ড করুন
                    $monthCards.append(res);

                    // বাটনের ডেটা আপডেট করুন
                    $loadBtn.data('month', month);
                    $loadBtn.data('year', year);

                    $loadBtn.prop('disabled', false).text('More...');
                },
                error: function () {
                    alert('Error loading previous month data.');
                    $loadBtn.prop('disabled', false).text('More...');
                }
            });
        });

    });
</script>

</body>

</html>