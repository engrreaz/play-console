<?php
$page_title = "Teacher Attendance Detail";
require_once "inc.php";

$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;


// Month & Year
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');
$day = date('d');

$start = "$year-$month-01";
$end = date("Y-m-$day", strtotime($start));

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



/* ============================
   SHOW TEACHER LIST IF NO TID
============================ */
/* ============================
   SHOW TEACHER LIST IF NO TID
============================ */
if (!$tid) {
    // ১. পুরো বছরের সময়সীমা নির্ধারণ
    $year_start = date('Y-01-01');
    $today = date('Y-m-d');

    // ২. টিচারদের তালিকা সংগ্রহ
    $tlist = $conn->prepare("SELECT tid, tname, position FROM teacher WHERE sccode=? ORDER BY sl");
    $tlist->bind_param("i", $sccode);
    $tlist->execute();
    $teachers = $tlist->get_result()->fetch_all(MYSQLI_ASSOC);

    // ৩. পুরো বছরের হাজিরা ডাটা সংগ্রহ (একবারে)
    $aq_year = $conn->prepare("SELECT tid, adate, statusin FROM teacherattnd WHERE sccode=? AND adate BETWEEN ? AND ?");
    $aq_year->bind_param("iss", $sccode, $year_start, $today);
    $aq_year->execute();
    $ar_year = $aq_year->get_result();
    $year_att = [];
    while ($r = $ar_year->fetch_assoc()) {
        $year_att[$r['tid']][$r['adate']] = strtolower($r['statusin']);
    }

    // ৪. পুরো বছরের লিভ ডাটা সংগ্রহ
    $lq_year = $conn->prepare("SELECT tid, date_from, date_to FROM teacher_leave_app WHERE sccode=? AND status=1 AND date_from <= ? AND date_to >= ?");
    $lq_year->bind_param("iss", $sccode, $today, $year_start);
    $lq_year->execute();
    $lr_year = $lq_year->get_result();
    $year_leave = [];
    while ($r = $lr_year->fetch_assoc()) {
        $cur = $r['date_from'];
        while ($cur <= $r['date_to']) {
            if ($cur >= $year_start && $cur <= $today) {
                $year_leave[$r['tid']][$cur] = true;
            }
            $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
        }
    }

    // ৫. ক্যালেন্ডার ও হলিডে ডাটা
    $cq_year = $conn->prepare("SELECT date, dateto, work FROM calendar WHERE (sccode=? OR sccode=0) AND (date BETWEEN ? AND ? OR dateto BETWEEN ? AND ?)");
    $cq_year->bind_param("issss", $sccode, $year_start, $today, $year_start, $today);
    $cq_year->execute();
    $cr_year = $cq_year->get_result();
    $year_cal = [];
    while ($r = $cr_year->fetch_assoc()) {
        $to = $r['dateto'] ?: $r['date'];
        $cur = $r['date'];
        while ($cur <= $to) {
            if ($cur >= $year_start && $cur <= $today)
                $year_cal[$cur] = $r['work'];
            $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
        }
    }

    // ৬. তারিখের তালিকা তৈরি (Stats গণনার জন্য)
    $all_dates = [];
    $tmp_d = $year_start;
    while ($tmp_d <= $today) {
        $all_dates[] = $tmp_d;
        $tmp_d = date("Y-m-d", strtotime("+1 day", strtotime($tmp_d)));
    }
    ?>

    <style>
        :root {
            --m3-primary: #6750A4;
            --m3-surface: #FEF7FF;
            --m3-on-tonal: #21005D;
        }

        body {
            background: var(--m3-surface);
        }

        /* Modern Hero */
        .hero-list {
            background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
            color: white;
            padding: 40px 20px 60px;
            border-radius: 0 0 32px 32px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(103, 80, 164, 0.2);
        }

        .hero-list::after {
            content: '';
            position: absolute;
            right: -20px;
            top: -20px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        /* Teacher Card Redesign */
        .teacher-card {
            background: white;
            border-radius: 20px;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid #E7E0EC;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none !important;
            display: block;
        }

        .teacher-card:active {
            transform: scale(0.97);
            background: #F3EDF7;
        }

        .avatar-box {
            width: 54px;
            height: 54px;
            background: #EADDFF;
            color: var(--m3-on-tonal);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .stat-pill {
            flex: 1;
            padding: 6px 2px;
            text-align: center;
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .sp-p {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .sp-a {
            background: #F9DEDC;
            color: #B3261E;
        }

        .sp-h {
            background: #F3EDF7;
            color: #6750A4;
        }

        .sp-l {
            background: #FFF8E1;
            color: #FF8F00;
        }
    </style>

    <div class="hero-list">
        <h3 class="fw-black m-0" style="letter-spacing: -1px;">Staff Directory</h3>
        <p class="small m-0 opacity-75 fw-bold">Yearly Attendance Overview (<?= date('Y') ?>)</p>
        <div class="mt-3 d-flex gap-2">
            <span class="badge bg-white text-primary rounded-pill px-3 py-2 border-0 shadow-sm" style="font-size: 0.75rem;">
                <i class="bi bi-people-fill me-1"></i> <?= count($teachers) ?> Members
            </span>
        </div>
    </div>

    <div class="container py-3" style="margin-top: -30px;">
        <div class="row g-2">
            <?php foreach ($teachers as $t):
                // পরিসংখ্যান গণনা (Yearly)
                $p = 0;
                $a = 0;
                $h = 0;
                $lv = 0;
                foreach ($all_dates as $dt) {
                    $dayName = date('l', strtotime($dt));
                    if (isset($year_cal[$dt]) && $year_cal[$dt] == 0) {
                        $h++;
                    } elseif (in_array($dayName, $weekendDays)) {
                        $h++;
                    } elseif (isset($year_leave[$t['tid']][$dt])) {
                        $lv++;
                    } elseif (isset($year_att[$t['tid']][$dt])) {
                        if ($year_att[$t['tid']][$dt] == 'absent')
                            $a++;
                        else
                            $p++;
                    } else {
                        $a++;
                    }
                }
                ?>
                <div class="col-12">
                    <a href="?tid=<?= $t['tid'] ?>" class="teacher-card shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-box"><?= strtoupper(substr($t['tname'], 0, 1)) ?></div>
                            <div class="ms-3 flex-grow-1">
                                <div class="fw-black text-dark" style="font-size: 1.05rem; line-height: 1.2;"><?= $t['tname'] ?>
                                </div>
                                <div class="small text-muted fw-bold"><?= $t['position'] ?> <span class="mx-1">•</span> ID:
                                    <?= $t['tid'] ?>
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-muted opacity-50"></i>
                        </div>

                        <div class="d-flex gap-2">
                            <div class="stat-pill sp-p"><b><?= $p ?></b><br>Present</div>
                            <div class="stat-pill sp-a"><b><?= $a ?></b><br>Absent</div>
                            <div class="stat-pill sp-h"><b><?= $h ?></b><br>Holiday</div>
                            <div class="stat-pill sp-l"><b><?= $lv ?></b><br>Leave</div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
    include 'footer.php';
    exit;
}



/* -----------------------
   Teacher Info
------------------------ */
$tq = $conn->prepare("SELECT tid, tname, position FROM teacher WHERE tid=? AND sccode=?");
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
    WHERE (sccode=? OR sccode=0) AND (date BETWEEN ? AND ? OR dateto BETWEEN ? AND ?)
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
$dates = array_reverse($dates);

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





    /* Stats Dashboard */
    .m3-stat-card {
        background: white;
        border-radius: 8px;
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
        border-radius: 8px;
        padding: 16px;
        margin: 0 12px 20px;
        border: 1px solid #F0F0F0;
    }



    .weekend {
        background: #F5F5F5;
        color: #757575;
        border: 1px dashed #E0E0E0;
    }
</style>


<style>
    /* ডিটেইল ভিউয়ের জন্য বিশেষ স্টাইল */
    .hero-profile {
        background: linear-gradient(135deg, #6750A4 0%, #4527A0 100%);
        color: white;
        padding: 40px 20px 80px;
        border-radius: 0 0 40px 40px;
        position: relative;
        overflow: hidden;
    }

    .profile-info {
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .avatar-large {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border: 3px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 900;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* স্ট্যাটাস কন্টেইনার যা হিরোর ওপর ভাসবে */
    .stats-overlay {
        margin-top: -50px;
        padding: 0 16px;
        position: relative;
        z-index: 10;
    }

    .m3-stat-card-group {
        background: white;
        border-radius: 28px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(103, 80, 164, 0.1);
        border: 1px solid #E7E0EC;
        display: flex;
        gap: 10px;
    }

    .stat-unit {
        flex: 1;
        text-align: center;
        padding: 12px 5px;
        border-radius: 20px;
        transition: 0.3s;
    }

    .stat-unit b {
        font-size: 1.4rem;
        display: block;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-unit span {
        font-size: 0.6rem;
        font-weight: 800;
        text-transform: uppercase;
        opacity: 0.8;
    }

    /* টোনাল কালার স্কিম */
    .bg-p {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .bg-a {
        background: #F9DEDC;
        color: #B3261E;
    }

    .bg-l {
        background: #FFF9C4;
        color: #F57F17;
    }

    .bg-lv {
        background: #F3EDF7;
        color: #6750A4;
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 700;
        backdrop-filter: blur(5px);
        text-decoration: none;
    }
</style>

<div class="hero-profile shadow">


    <div class="profile-info">
        <div class="avatar-large"><?= strtoupper(substr($teacher['tname'], 0, 1)) ?></div>
        <div class="ms-3 flex-grow-1">
            <h3 class="fw-black m-0" style="letter-spacing: -0.5px;"><?= $teacher['tname'] ?></h3>
            <div class="d-flex align-items-center opacity-90 mt-1">
                <i class="bi bi-person-workspace me-2"></i>
                <span
                    class="small fw-bold text-uppercase flex-grow-1"><?= $teacher['position'] ?? 'Staff Member' ?></span>

            </div>
            <div class="d-flex">
                <div class="small opacity-75 mt-1 flex-grow-1">
                    <i class="bi bi-calendar3 me-1"></i> <?= date("F Y", strtotime($start)) ?>
                </div>
                <div class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold">
                    ID: <?= $tid ?>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="stats-overlay">
    <div class="m3-stat-card-group shadow-sm">
        <div class="stat-unit bg-p">
            <b><?= $stats['present'] ?></b>
            <span>Present</span>
        </div>
        <div class="stat-unit bg-a">
            <b><?= $stats['absent'] ?></b>
            <span>Absent</span>
        </div>
        <div class="stat-unit bg-l">
            <b><?= $stats['late'] ?></b>
            <span>Late</span>
        </div>
        <div class="stat-unit bg-lv">
            <b><?= $stats['leave'] ?></b>
            <span>Leave</span>
        </div>
    </div>
</div>

<div class="mt-4"></div>

<!-- ********************************************************************************* -->

<!-- Attendance Bar Chart -->

<div class="m3-chart-card shadow-sm">
    <div class="small fw-bold text-muted mb-2 text-uppercase" style="letter-spacing: 1px;">Work Hours Trend</div>
    <canvas id="attChart" height="150"></canvas>
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
            <div class="attendance-tile shadow-sm  " data-date="<?= $dt ?>">
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

    const ctx = document.getElementById('attChart').getContext('2d');

    const labels = [];
    const bottomSeg = [];
    const middleSeg = [];
    const topSeg = [];
    const middleColors = [];

    <?php foreach ($dates as $dt):

        $day = date('d', strtotime($dt));

        // ----- DEFAULT TIMES -----
        $inHour = 9;
        $outHour = 15;

        if (isset($att[$dt])) {

            if (!empty($att[$dt]['realin'])) {
                $ts = strtotime($att[$dt]['realin']);
                $inHour = date('H', $ts) + (date('i', $ts) / 60);
            }

            if (!empty($att[$dt]['realout'])) {
                $ts = strtotime($att[$dt]['realout']);
                $outHour = date('H', $ts) + (date('i', $ts) / 60);
            }

        }


        // Boundaries
        $start = 7;
        $end = 18;

        $bottom = max(0, $inHour - $start + 0.5);
        $middle = max(0, $outHour - $inHour);
        $top = max(0, $end - $outHour);

        // ----- COLOR LOGIC -----
        $dayName = date('l', strtotime($dt));

        if (isset($cal[$dt]) && $cal[$dt]['work'] == 0)
            $color = '#CCCCCC';
        elseif (in_array($dayName, $weekendDays))
            $color = '#AAAAAA';
        elseif (isset($leave[$dt]))
            $color = '#FF8F00';
        elseif (isset($att[$dt])) {
            $stin = strtolower($att[$dt]['statusin']);
            if ($stin == 'late')
                $color = '#FFEB3B';
            elseif ($stin == 'absent')
                $color = '#F44336';
            else
                $color = '#4CAF50';
        } else
            $color = '#F44336';
        ?>

        labels.push("<?= $day ?>");
        bottomSeg.push(<?= $bottom ?>);
        middleSeg.push(<?= $middle ?>);
        topSeg.push(<?= $top ?>);
        middleColors.push("<?= $color ?>");

    <?php endforeach; ?>

    // Reverse to show latest first
    labels.reverse();
    bottomSeg.reverse();
    middleSeg.reverse();
    topSeg.reverse();
    middleColors.reverse();

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    data: bottomSeg,
                    backgroundColor: '#f5f3f3',
                    stack: 'timeline'
                },
                {
                    data: middleSeg,
                    backgroundColor: middleColors,
                    stack: 'timeline'
                },
                {
                    data: topSeg,
                    backgroundColor: '#E0E0E0',
                    stack: 'timeline'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { stacked: true },
                y: {
                    stacked: true,
                    min: 0,
                    max: 11,
                    ticks: {
                        callback: (v) => (v + 7) + ":00"
                    },
                    title: {
                        display: true,
                        text: 'Time'
                    }
                }
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