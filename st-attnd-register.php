<?php
session_start();
include_once 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include_once 'datam/datam-stprofile.php';

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

// ২. ফিল্টার হ্যান্ডলিং
$year_filter = isset($_GET['y_f']) ? intval($_GET['y_f']) : date('Y');
$month_filter = isset($_GET['m_f']) ? str_pad(intval($_GET['m_f']), 2, '0', STR_PAD_LEFT) : date('m');

$classname = $_GET['cls'] ?? ($cteacher_data[0]['cteachercls'] ?? '');
$sectionname = $_GET['sec'] ?? ($cteacher_data[0]['cteachersec'] ?? '');

$date_start = "$year_filter-$month_filter-01";
$days_in_month = date('t', strtotime($date_start));
$date_end = "$year_filter-$month_filter-$days_in_month";
$today_date = date('Y-m-d');
$page_title = "Monthly Register";

// ৩. ডাটা ফেচিং (Optimized)

// উইকেন্ড সেটিংস
$holidays_str = '';
$stmt_hol = $conn->prepare("SELECT settings_value FROM settings WHERE sccode = ? AND setting_title = 'Weekends'");
$stmt_hol->bind_param("s", $sccode);
$stmt_hol->execute();
if ($row = $stmt_hol->get_result()->fetch_assoc()) { $holidays_str = $row['settings_value']; }
$stmt_hol->close();

// স্টুডেন্ট লিস্ট
$students = [];
$stmt_st = $conn->prepare("SELECT stid, rollno FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND status='1' ORDER BY rollno ASC");
$stmt_st->bind_param("ssss", $sy_param, $sccode, $classname, $sectionname);
$stmt_st->execute();
$res_st = $stmt_st->get_result();
while($row = $res_st->fetch_assoc()) $students[] = $row;
$stmt_st->close();

// অ্যাটেনডেন্স ম্যাপ (Lookup Table)
$attendance_map = [];
$sql_att = "SELECT stid, adate, yn, bunk FROM stattnd WHERE sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ? AND adate BETWEEN ? AND ?";
$stmt_att = $conn->prepare($sql_att);
$stmt_att->bind_param("ssssss", $sccode, $sy_param, $classname, $sectionname, $date_start, $date_end);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
while ($row = $res_att->fetch_assoc()) {
    $attendance_map[$row['stid']][$row['adate']] = ['yn' => $row['yn'], 'bunk' => $row['bunk']];
}
$stmt_att->close();
?>

<?php
// ... (আপনার আগের সব PHP লজিক অপরিবর্তিত থাকবে) ...

// আপনার কাঙ্ক্ষিত লিঙ্কটি জেনারেট করা
$roll_call_url = "stattnd.php?cls=" . urlencode($classname) . "&sec=" . urlencode($sectionname) . "&year=" . $current_session;
?>

<style>
    /* ... (আগের সব CSS অপরিবর্তিত থাকবে) ... */

    /* M3 Floating Action Button (FAB) */
    .fab-roll-call {
        position: fixed;
        bottom: 85px; /* বটম নেভ এর উপরে */
        right: 20px;
        background-color: #6750A4; /* M3 Primary */
        color: #FFFFFF;
        width: 56px;
        height: 56px;
        border-radius: 16px; /* M3 FAB standard radius */
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
        text-decoration: none !important;
        z-index: 1100;
        transition: transform 0.2s, background-color 0.2s;
    }
    .fab-roll-call:active { transform: scale(0.9); background-color: #4F378B; }
    .fab-roll-call i { font-size: 1.5rem; }

    /* Header Action Button (8px radius) */
    .btn-roll-call-sm {
        background-color: #EADDFF;
        color: #21005D;
        border: none;
        padding: 6px 12px;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 8px !important;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>


<style>
    body { background-color: #FEF7FF; font-size: 0.85rem; margin: 0; padding: 0; }

    /* Full Width Top Bar */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1060;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Condensed Filter Card */
    .filter-card {
        background: #fff; border-radius: 8px; padding: 12px; margin: 8px;
        border: 1px solid #eee; box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .form-select-sm { border-radius: 6px; border: 1px solid #79747E; font-size: 0.75rem; font-weight: 600; }

    /* Attendance Register Container */
    .register-wrapper {
        background: #fff; border-radius: 8px; margin: 0 8px 12px;
        border: 1px solid #eee; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .table-container { max-height: 60vh; overflow: auto; position: relative; }
    
    .table-m3 { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-m3 th, .table-m3 td { 
        padding: 6px 2px; text-align: center; border-bottom: 1px solid #F3EDF7; 
        border-right: 1px solid #F3EDF7; font-size: 0.7rem;
    }

    /* Sticky Logic */
    .stk-head { position: sticky; top: 0; background: #F3EDF7 !important; z-index: 20; font-weight: 800; color: #6750A4; }
    .stk-roll { position: sticky; left: 0; background: #fff !important; z-index: 10; font-weight: 800; border-right: 2px solid #EADDFF !important; min-width: 35px; }
    .stk-name { position: sticky; left: 35px; background: #fff !important; z-index: 10; border-right: 2px solid #EADDFF !important; min-width: 100px; text-align: left !important; padding-left: 6px !important; }
    .stk-head.stk-roll, .stk-head.stk-name { z-index: 30; }

    /* Attendance Dots */
    .dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
    .dot-p { background: #4CAF50; } /* Present */
    .dot-a { background: #B3261E; } /* Absent */
    .dot-b { background: #FF9800; } /* Bunk */
    .dot-h { background: #E7E0EC; } /* Holiday */
    .dot-n { background: #49454F; opacity: 0.3; } /* Not Taken */
    .dot-f { background: transparent; border: 1px solid #eee; } /* Future */

    /* Legend Bar */
    .legend-row { display: flex; flex-wrap: wrap; gap: 8px; padding: 10px 16px; background: #F3EDF7; border-radius: 0 0 8px 8px; }
    .lg-item { display: flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; color: #49454F; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    
    <a href="<?php echo $roll_call_url; ?>" class="btn-roll-call-sm shadow-sm me-2">
        <i class="bi bi-pencil-square"></i> ROLL CALL
    </a>
    
    <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.6rem;"><?php echo $current_session; ?></span>
</header>

<main class="pb-5 mt-1">
    <a href="<?php echo $roll_call_url; ?>" class="fab-roll-call shadow-lg" title="Start Roll Call">
        <i class="bi bi-fingerprint"></i>
    </a>
</main>

<main class="pb-5 mt-1">
    <div class="filter-card shadow-sm">
        <form method="GET" class="row gx-2 gy-2 align-items-center">
            <div class="col-4">
                <select name="cls" class="form-select form-select-sm" onchange="this.form.submit()">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachercls']; ?>" <?php echo ($c['cteachercls'] == $classname) ? 'selected' : ''; ?>><?php echo $c['cteachercls']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-3">
                <select name="sec" class="form-select form-select-sm" onchange="this.form.submit()">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachersec']; ?>" <?php echo ($c['cteachersec'] == $sectionname) ? 'selected' : ''; ?>><?php echo $c['cteachersec']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-3">
                <select name="m_f" class="form-select form-select-sm">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo $m; ?>" <?php echo ($m == intval($month_filter)) ? 'selected' : ''; ?>><?php echo date('M', mktime(0, 0, 0, $m, 10)); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm" style="border-radius: 6px;"><i class="bi bi-search"></i></button>
            </div>
            <input type="hidden" name="year" value="<?php echo $current_session; ?>">
        </form>
    </div>

    <div class="register-wrapper shadow-sm">
        <div class="table-container">
            <table class="table-m3">
                <thead>
                    <tr>
                        <th class="stk-head stk-roll">#</th>
                        <th class="stk-head stk-name">Name</th>
                        <?php for ($d = 1; $d <= $days_in_month; $d++): ?>
                            <th class="stk-head" style="min-width: 28px;"><?php echo $d; ?></th>
                        <?php endfor; ?>
                        <th class="stk-head" style="min-width: 40px; border-right: 0;">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr><td colspan="<?php echo $days_in_month + 3; ?>" class="p-5 text-muted">No records.</td></tr>
                    <?php else: ?>
                        <?php foreach ($students as $st): 
                            $stid = $st['stid'];
                            $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                            $st_name = ($st_idx !== false) ? $datam_st_profile[$st_idx]['stnameeng'] : 'ID: '.$stid;
                            $open = 0; $present = 0;
                        ?>
                            <tr>
                                <td class="stk-roll fw-bold"><?php echo $st['rollno']; ?></td>
                                <td class="stk-name text-truncate"><?php echo $st_name; ?></td>
                                <?php 
                                for ($d = 1; $d <= $days_in_month; $d++): 
                                    $c_date = "$year_filter-$month_filter-" . str_pad($d, 2, '0', STR_PAD_LEFT);
                                    $day_name = date('l', strtotime($c_date));
                                    $dot_class = 'dot-f'; // Future
                                    
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
                                                } else { $dot_class = 'dot-a'; }
                                            } else { $dot_class = 'dot-n'; }
                                        }
                                    }
                                ?>
                                    <td><span class="dot <?php echo $dot_class; ?>"></span></td>
                                <?php endfor; ?>
                                <td class="fw-bold text-primary" style="border-right: 0;">
                                    <?php echo ($open > 0) ? round(($present / $open) * 100) : '0'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="legend-row">
            <div class="lg-item"><span class="dot dot-p"></span> Present</div>
            <div class="lg-item"><span class="dot dot-a"></span> Absent</div>
            <div class="lg-item"><span class="dot dot-b"></span> Bunk</div>
            <div class="lg-item"><span class="dot dot-h"></span> Holiday</div>
            <div class="lg-item"><span class="dot dot-n"></span> Missing</div>
        </div>
    </div>
</main>

<div style="height: 65px;"></div> <?php include 'footer.php'; ?>