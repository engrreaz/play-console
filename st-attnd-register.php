<?php
$page_title = "Attendance Register";
include_once 'inc.php';
include_once 'datam/datam-stprofile.php';

$year_filter = isset($_GET['y_f']) ? intval($_GET['y_f']) : date('Y');
$month_filter = isset($_GET['m_f']) ? str_pad(intval($_GET['m_f']), 2, '0', STR_PAD_LEFT) : date('m');
$classname = $_GET['cls'] ?? ($cteacher_data[0]['cteachercls'] ?? '');
$sectionname = $_GET['sec'] ?? ($cteacher_data[0]['cteachersec'] ?? '');

$date_start = "$year_filter-$month_filter-01";
$days_in_month = date('t', strtotime($date_start));
$date_end = "$year_filter-$month_filter-$days_in_month";
$today_date = date('Y-m-d');

// ২. ডাটা ফেচিং (অপরিবর্তিত)
$holidays_str = '';
$stmt_hol = $conn->prepare("SELECT settings_value FROM settings WHERE sccode = ? AND setting_title = 'Weekends'");
$stmt_hol->bind_param("s", $sccode);
$stmt_hol->execute();
if ($row = $stmt_hol->get_result()->fetch_assoc()) {
    $holidays_str = $row['settings_value'];
}
$stmt_hol->close();

$students = [];
$stmt_st = $conn->prepare("SELECT stid, rollno FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND status='1' ORDER BY rollno ASC");
$stmt_st->bind_param("ssss", $sessionyear_param, $sccode, $classname, $sectionname);
$stmt_st->execute();
$res_st = $stmt_st->get_result();
while ($row = $res_st->fetch_assoc())
    $students[] = $row;
$stmt_st->close();

$attendance_map = [];
$sql_att = "SELECT stid, adate, yn, bunk FROM stattnd WHERE sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ? AND adate BETWEEN ? AND ?";
$stmt_att = $conn->prepare($sql_att);
$stmt_att->bind_param("ssssss", $sccode, $sessionyear_param, $classname, $sectionname, $date_start, $date_end);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
while ($row = $res_att->fetch_assoc()) {
    $attendance_map[$row['stid']][$row['adate']] = ['yn' => $row['yn'], 'bunk' => $row['bunk']];
}
$stmt_att->close();

$roll_call_url = "stattnd.php?cls=" . urlencode($classname) . "&sec=" . urlencode($sectionname) . "&year=" . $sessionyear;
?>

<style>
    /* M3 Core Sticky Table Logic */
    .register-container {
        margin: 0 12px 16px;
        background: #fff;
        border-radius: var(--m3-radius);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        box-shadow: var(--m3-shadow);
    }

    .table-responsive-m3 {
        max-height: 65vh;
        overflow: auto;
        position: relative;
    }

    .m3-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .m3-table th,
    .m3-table td {
        padding: 8px 4px;
        text-align: center;
        border-bottom: 1px solid #F7F2FA;
        border-right: 1px solid #F7F2FA;
        font-size: 0.72rem;
        white-space: nowrap;
    }

    /* Sticky Headers & Columns */
    .stk-h {
        position: sticky;
        top: 0;
        background: #F3EDF7 !important;
        z-index: 100;
        font-weight: 800;
        color: var(--m3-primary);
    }

    .stk-c1 {
        position: sticky;
        left: 0;
        background: #fff !important;
        z-index: 80;
        font-weight: 800;
        border-right: 2px solid #EADDFF !important;
        min-width: 35px;
    }

    .stk-c2 {
        position: sticky;
        left: 35px;
        background: #fff !important;
        z-index: 80;
        border-right: 2px solid #EADDFF !important;
        min-width: 110px;
        text-align: left !important;
        padding-left: 8px !important;
    }

    .stk-h.stk-c1,
    .stk-h.stk-c2 {
        z-index: 110;
    }

    /* Attendance Dots */
    .att-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .dot-p {
        background: #4CAF50;
        box-shadow: 0 0 4px rgba(76, 175, 80, 0.4);
    }

    /* Present */
    .dot-a {
        background: #B3261E;
        box-shadow: 0 0 4px rgba(179, 38, 30, 0.4);
    }

    /* Absent */
    .dot-b {
        background: #FF9800;
    }

    /* Bunk */
    .dot-h {
        background: #EADDFF;
    }

    /* Holiday */
    .dot-n {
        background: #eee;
    }

    /* Not Taken */

    /* FAB M3 Style */
    .m3-fab {
        position: fixed;
        bottom: 85px;
        right: 20px;
        background: var(--m3-primary-gradient);
        color: #fff;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(103, 80, 164, 0.3);
        z-index: 1050;
        transition: 0.2s;
        text-decoration: none;
    }

    .m3-fab:active {
        transform: scale(0.9);
    }
</style>
<style>
    /* ক্লাস পিল অ্যানিমেশন */
    .class-pill {
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        border: 1px solid transparent;
    }

    .class-pill:active {
        transform: scale(0.95);
        opacity: 0.8;
    }

    /* হরিজন্টাল স্ক্রলবারের সৌন্দর্য */
    .scroll-hide::-webkit-scrollbar {
        display: none;
    }

    .scroll-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* হিরো কন্টেইনার আপডেট */
    .hero-container {
        border-radius: 0 0 24px 24px;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.15);
    }
</style>


<style>
    /* ফিল্টার র‍্যাপার স্টাইল */
    .m3-filter-wrapper {
        background: #fff;
        margin: -24px 16px 16px;
        /* হিরো সেকশনের সাথে ওভারল্যাপ */
        padding: 12px;
        border-radius: 16px;
        border: 1px solid #E7E0EC;
        position: relative;
        z-index: 100;
    }

    /* ইনপুট গ্রুপ যেখানে আইকন থাকবে */
    .m3-input-group {
        display: flex;
        align-items: center;
        background: #F3EDF7;
        /* M3 Tonal Color */
        border-radius: 10px;
        padding: 0 10px;
        border: 1px solid transparent;
        transition: 0.3s;
    }

    .m3-input-group:focus-within {
        border-color: var(--m3-primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(103, 80, 164, 0.1);
    }

    .m3-input-group i {
        color: var(--m3-primary);
        font-size: 0.9rem;
    }

    /* মিনিমাল সিলেক্ট বক্স */
    .m3-minimal-select {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        font-size: 0.8rem;
        font-weight: 700;
        color: #1D1B20;
        padding: 8px 5px !important;
        cursor: pointer;
    }

    /* M3 Tonal Button */
    .btn-m3-tonal {
        background: var(--m3-primary);
        color: #fff;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 800;
        height: 38px;
        border: none;
        transition: 0.2s;
    }

    .btn-m3-tonal:active {
        transform: scale(0.95);
        background: #4F378B;
    }
</style>

<style>
    /* সেল লিংকের স্টাইল */
    .att-cell-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 35px;
        /* টেবিল সেলের উচ্চতা অনুযায়ী */
        text-decoration: none;
        transition: background 0.2s;
    }

    /* হোভার ইফেক্ট - যাতে ইউজার বুঝতে পারে এটি ক্লিকেবল */
    .att-cell-link:hover {
        background-color: #F3EDF7;
    }

    .att-cell-link:active {
        background-color: #EADDFF;
        transform: scale(0.9);
    }

    /* ডটগুলোকে আরও স্পষ্ট করা */
    .att-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
</style>

<style>
    /* ১. টেবিল হেডার লিংকের আন্ডারলাইন রিমুভ এবং স্টাইল */
    .m3-day-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        min-height: 40px;
        text-decoration: none !important;
        /* আন্ডারলাইন রিমুভ */
        color: var(--m3-primary) !important;
        font-weight: 800;
        font-size: 0.75rem;
        transition: 0.2s;
    }

    /* ২. বর্তমান তারিখের জন্য হাইলাইট ক্লাস */
    .is-today-h {
        background: #6750A4 !important;
        /* প্রাইমারি কালার */
        color: #FFFFFF !important;
        border-radius: 8px 8px 0 0;
        /* উপরের কোণা গোল */
    }

    .is-today-h .m3-day-link {
        color: #FFFFFF !important;
    }

    /* ৩. বর্তমান তারিখের কলামের বডি সেল হাইলাইট */
    .cell-today {
        background-color: rgba(103, 80, 164, 0.05) !important;
        /* হালকা বেগুনি আভা */
        border-left: 1px solid #EADDFF !important;
        border-right: 1px solid #EADDFF !important;
    }

    /* সেল লিংকের আন্ডারলাইন রিমুভ */
    .att-cell-link {
        text-decoration: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 35px;
    }
</style>



<main>

    <div class="hero-container" style="padding-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="session-pill" style="background: rgba(255,255,255,0.2); color: #fff; border:none;">
                        <?php echo date('F Y', strtotime($date_start)); ?>
                    </span>
                </div>
                <div style="font-size: 1.4rem; font-weight: 900; margin-top: 8px;">Attendance Register</div>

                <div class="d-flex flex-wrap gap-2 mt-3 scroll-hide"
                    style="overflow-x: auto; white-space: nowrap; padding-bottom: 5px;">
                    <?php
                    $found_in_assigned = false;
                    foreach ($cteacher_data as $c):
                        $is_active = ($c['cteachercls'] == $classname && $c['cteachersec'] == $sectionname);
                        if ($is_active)
                            $found_in_assigned = true;

                        $pill_style = $is_active
                            ? 'background: #fff; color: var(--m3-primary); font-weight: 800;'
                            : 'background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.2);';
                        ?>
                        <div class="class-pill shadow-sm"
                            style="<?= $pill_style ?> cursor: pointer; padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; text-transform: uppercase;"
                            onclick="window.location.href='?cls=<?= urlencode($c['cteachercls']) ?>&sec=<?= urlencode($c['cteachersec']) ?>&m_f=<?= $month_filter ?>&y_f=<?= $year_filter ?>'">
                            <i class="bi bi-mortarboard-fill me-1"></i> <?= $c['cteachercls'] ?> - <?= $c['cteachersec'] ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!$found_in_assigned && !empty($classname)): ?>
                        <div class="class-pill shadow-sm"
                            style="background: #FFB900; color: #000; font-weight: 800; padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; text-transform: uppercase;">
                            <i class="bi bi-geo-alt-fill me-1"></i> <?= $classname ?> - <?= $sectionname ?> (Foreign)
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <a href="<?php echo $roll_call_url; ?>" class="tonal-icon-btn"
                style="background:rgba(255,255,255,0.2); color:#fff; border:none; width:44px; height:44px;">
                <i class="bi bi-pencil-square"></i>
            </a>
        </div>
    </div>

    <div class="m3-filter-wrapper shadow-sm">
        <form method="GET" class="row gx-2 align-items-center">
            <div class="col-5">
                <div class="m3-input-group">
                    <i class="bi bi-calendar-month"></i>
                    <select name="m_f" class="form-select m3-minimal-select">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo ($m == intval($month_filter)) ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $m, 10)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="col-4">
                <div class="m3-input-group">
                    <i class="bi bi-calendar-check"></i>
                    <select name="y_f" class="form-select m3-minimal-select">
                        <?php
                        $start_year = date('Y') - 1;
                        for ($y = $start_year; $y <= date('Y') + 1; $y++): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y == $year_filter) ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="col-3">
                <button type="submit" class="btn btn-m3-tonal w-100">
                    <i class="bi bi-funnel-fill me-1"></i> VIEW
                </button>
            </div>

            <input type="hidden" name="cls" value="<?php echo $classname; ?>">
            <input type="hidden" name="sec" value="<?php echo $sectionname; ?>">
            <input type="hidden" name="year" value="<?php echo $sessionyear; ?>">
        </form>
    </div>

    <div class="register-container">
        <div class="table-responsive-m3">
            <table class="m3-table">

                <thead>
                    <tr>
                        <th class="stk-h stk-c1">#</th>
                        <th class="stk-h stk-c2">Name</th>

                        <?php for ($d = 1; $d <= $days_in_month; $d++):
                            $c_date = "$year_filter-$month_filter-" . str_pad($d, 2, '0', STR_PAD_LEFT);

                            // বর্তমান তারিখ চেক করা
                            $is_today_col = ($c_date == $today_date) ? 'is-today-h' : '';

                            $header_link = "stattnd.php?cls=" . urlencode($classname) .
                                "&sec=" . urlencode($sectionname) .
                                "&year=" . $sessionyear .
                                "&date=" . $c_date;
                            ?>
                            <th class="stk-h p-0 <?= $is_today_col ?>" style="min-width: 35px;">
                                <a href="<?= $header_link ?>" class="m3-day-link">
                                    <?= $d ?>
                                </a>
                            </th>
                        <?php endfor; ?>

                        <th class="stk-h" style="min-width: 45px; border-right: 0;">%</th>
                    </tr>
                </thead>



                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="<?php echo $days_in_month + 3; ?>" style="padding: 40px; color: #999;">No students
                                found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $st):
                            $stid = $st['stid'];
                            $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                            $st_name = ($st_idx !== false) ? $datam_st_profile[$st_idx]['stnameeng'] : 'ID: ' . $stid;
                            $open = 0;
                            $present = 0;
                            ?>
                            <tr>
                                <td class="stk-c1"><?php echo $st['rollno']; ?></td>
                                <td class="stk-c2" style="font-weight: 600; font-size: 0.68rem; color: #444;">
                                    <?php echo strtoupper($st_name); ?>
                                </td>
                                <?php
                                for ($d = 1; $d <= $days_in_month; $d++):
                                    $c_date = "$year_filter-$month_filter-" . str_pad($d, 2, '0', STR_PAD_LEFT);
                                    $day_name = date('l', strtotime($c_date));
                                    $dot_class = ''; // Default Empty
                        
                                    if (strtotime($c_date) <= strtotime($today_date)) {
                                        if (str_contains($holidays_str, $day_name)) {
                                            $dot_class = 'dot-h';
                                        } else {
                                            $open++;
                                            if (isset($attendance_map[$stid][$c_date])) {
                                                $att = $attendance_map[$stid][$c_date];
                                                if ($att['yn'] == '1') {
                                                    $present++;
                                                    $dot_class = ($att['bunk'] == '1') ? 'dot-b' : 'dot-p';
                                                } else {
                                                    $dot_class = 'dot-a';
                                                }
                                            } else {
                                                $dot_class = 'dot-n';
                                            }
                                        }
                                    }
                                    ?>
                                    <td><?php if ($dot_class)
                                        echo '<span class="att-dot ' . $dot_class . '"></span>'; ?></td>
                                <?php endfor; ?>
                                <td style="font-weight: 800; color: var(--m3-primary); border-right: 0;">
                                    <?php echo ($open > 0) ? round(($present / $open) * 100) : '0'; ?>%
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div
            style="display: flex; flex-wrap: wrap; gap: 12px; padding: 12px; background: #F7F2FA; border-top: 1px solid #eee;">
            <div
                style="display: flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; color: #555;">
                <span class="att-dot dot-p"></span> Present
            </div>
            <div
                style="display: flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; color: #555;">
                <span class="att-dot dot-a"></span> Absent
            </div>
            <div
                style="display: flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; color: #555;">
                <span class="att-dot dot-b"></span> Bunk
            </div>
            <div
                style="display: flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; color: #555;">
                <span class="att-dot dot-h"></span> Holiday
            </div>
        </div>
    </div>

    <a href="<?php echo $roll_call_url; ?>" class="m3-fab">
        <i class="bi bi-fingerprint" style="font-size: 1.6rem;"></i>
    </a>
</main>

<div style="height: 80px;"></div>

<?php include 'footer.php'; ?>