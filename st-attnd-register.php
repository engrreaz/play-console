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
if ($row = $stmt_hol->get_result()->fetch_assoc()) { $holidays_str = $row['settings_value']; }
$stmt_hol->close();

$students = [];
$stmt_st = $conn->prepare("SELECT stid, rollno FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND status='1' ORDER BY rollno ASC");
$stmt_st->bind_param("ssss", $sy_param, $sccode, $classname, $sectionname);
$stmt_st->execute();
$res_st = $stmt_st->get_result();
while($row = $res_st->fetch_assoc()) $students[] = $row;
$stmt_st->close();

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

$roll_call_url = "stattnd.php?cls=" . urlencode($classname) . "&sec=" . urlencode($sectionname) . "&year=" . $sessionyear;
?>

<style>
    /* M3 Core Sticky Table Logic */
    .register-container {
        margin: 0 12px 16px; background: #fff; border-radius: var(--m3-radius);
        border: 1px solid #f0f0f0; overflow: hidden; box-shadow: var(--m3-shadow);
    }
    .table-responsive-m3 { max-height: 65vh; overflow: auto; position: relative; }
    
    .m3-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .m3-table th, .m3-table td { 
        padding: 8px 4px; text-align: center; border-bottom: 1px solid #F7F2FA; 
        border-right: 1px solid #F7F2FA; font-size: 0.72rem; white-space: nowrap;
    }

    /* Sticky Headers & Columns */
    .stk-h { position: sticky; top: 0; background: #F3EDF7 !important; z-index: 100; font-weight: 800; color: var(--m3-primary); }
    .stk-c1 { position: sticky; left: 0; background: #fff !important; z-index: 80; font-weight: 800; border-right: 2px solid #EADDFF !important; min-width: 35px; }
    .stk-c2 { position: sticky; left: 35px; background: #fff !important; z-index: 80; border-right: 2px solid #EADDFF !important; min-width: 110px; text-align: left !important; padding-left: 8px !important; }
    .stk-h.stk-c1, .stk-h.stk-c2 { z-index: 110; }

    /* Attendance Dots */
    .att-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
    .dot-p { background: #4CAF50; box-shadow: 0 0 4px rgba(76,175,80,0.4); } /* Present */
    .dot-a { background: #B3261E; box-shadow: 0 0 4px rgba(179,38,30,0.4); } /* Absent */
    .dot-b { background: #FF9800; } /* Bunk */
    .dot-h { background: #EADDFF; } /* Holiday */
    .dot-n { background: #eee; } /* Not Taken */

    /* FAB M3 Style */
    .m3-fab {
        position: fixed; bottom: 85px; right: 20px;
        background: var(--m3-primary-gradient); color: #fff;
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 15px rgba(103, 80, 164, 0.3); z-index: 1050;
        transition: 0.2s; text-decoration: none;
    }
    .m3-fab:active { transform: scale(0.9); }
</style>

<main>
    <div class="hero-container">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="session-pill" style="background: rgba(255,255,255,0.2); color: #fff; border:none;">
                        <?php echo date('F Y', strtotime($date_start)); ?>
                    </span>
                </div>
                <div style="font-size: 1.4rem; font-weight: 900; margin-top: 8px;">Attendance Register</div>
                <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 600;">Class: <?php echo $classname; ?> | Sec: <?php echo $sectionname; ?></div>
            </div>
            <a href="<?php echo $roll_call_url; ?>" class="tonal-icon-btn" style="background:rgba(255,255,255,0.2); color:#fff; border:none; width:44px; height:44px;">
                <i class="bi bi-pencil-square"></i>
            </a>
        </div>
    </div>

    <div class="m3-card" style="margin-top: -20px; position: relative; z-index: 10; padding: 12px;">
        <form method="GET" class="row gx-2 gy-0">
            <div class="col-4">
                <select name="cls" class="m3-select-floating" style="height: 40px; padding: 0 8px; font-size: 0.75rem;" onchange="this.form.submit()">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachercls']; ?>" <?php echo ($c['cteachercls'] == $classname) ? 'selected' : ''; ?>><?php echo $c['cteachercls']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-3">
                <select name="sec" class="m3-select-floating" style="height: 40px; padding: 0 8px; font-size: 0.75rem;" onchange="this.form.submit()">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachersec']; ?>" <?php echo ($c['cteachersec'] == $sectionname) ? 'selected' : ''; ?>><?php echo $c['cteachersec']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-3">
                <select name="m_f" class="m3-select-floating" style="height: 40px; padding: 0 8px; font-size: 0.75rem;">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo $m; ?>" <?php echo ($m == intval($month_filter)) ? 'selected' : ''; ?>><?php echo date('M', mktime(0, 0, 0, $m, 10)); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-2">
                <button type="submit" class="btn-m3-submit" style="height: 40px; margin:0; width:100%;"><i class="bi bi-search"></i></button>
            </div>
            <input type="hidden" name="year" value="<?php echo $current_session; ?>">
        </form>
    </div>

    <div class="register-container">
        <div class="table-responsive-m3">
            <table class="m3-table">
                <thead>
                    <tr>
                        <th class="stk-h stk-c1">#</th>
                        <th class="stk-h stk-c2">Name</th>
                        <?php for ($d = 1; $d <= $days_in_month; $d++): ?>
                            <th class="stk-h" style="min-width: 30px;"><?php echo $d; ?></th>
                        <?php endfor; ?>
                        <th class="stk-h" style="min-width: 45px; border-right: 0;">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr><td colspan="<?php echo $days_in_month + 3; ?>" style="padding: 40px; color: #999;">No students found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($students as $st): 
                            $stid = $st['stid'];
                            $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                            $st_name = ($st_idx !== false) ? $datam_st_profile[$st_idx]['stnameeng'] : 'ID: '.$stid;
                            $open = 0; $present = 0;
                        ?>
                            <tr>
                                <td class="stk-c1"><?php echo $st['rollno']; ?></td>
                                <td class="stk-c2" style="font-weight: 600; font-size: 0.68rem; color: #444;"><?php echo strtoupper($st_name); ?></td>
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
                                                } else { $dot_class = 'dot-a'; }
                                            } else { $dot_class = 'dot-n'; }
                                        }
                                    }
                                ?>
                                    <td><?php if($dot_class) echo '<span class="att-dot '.$dot_class.'"></span>'; ?></td>
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
        
        <div style="display: flex; flex-wrap: wrap; gap: 12px; padding: 12px; background: #F7F2FA; border-top: 1px solid #eee;">
            <div style="display: flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; color: #555;">
                <span class="att-dot dot-p"></span> Present
            </div>
            <div style="display: flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; color: #555;">
                <span class="att-dot dot-a"></span> Absent
            </div>
            <div style="display: flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; color: #555;">
                <span class="att-dot dot-b"></span> Bunk
            </div>
            <div style="display: flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; color: #555;">
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
