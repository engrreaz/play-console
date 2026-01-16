<?php
session_start();
include_once 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include_once 'datam/datam-stprofile.php';


// --- ১. ফিল্টার হ্যান্ডলিং (Secure & Default logic) ---
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? str_pad(intval($_GET['month']), 2, '0', STR_PAD_LEFT) : date('m');
$classname = $_GET['cls'] ?? ($cteacher_data[0]['cteachercls'] ?? '');
$sectionname = $_GET['sec'] ?? ($cteacher_data[0]['cteachersec'] ?? '');

$date_start = "$year-$month-01";
$days_in_month = date('t', strtotime($date_start));
$date_end = "$year-$month-$days_in_month";
$today_date = date('Y-m-d');

// --- ২. ডাটা ফেচিং (Optimized for Register View) ---

// ছুটির দিন (Weekends) ফেচ করা
$holidays_str = '';
$stmt_hol = $conn->prepare("SELECT settings_value FROM settings WHERE sccode = ? AND setting_title = 'Weekends'");
$stmt_hol->bind_param("s", $sccode);
$stmt_hol->execute();
if ($row = $stmt_hol->get_result()->fetch_assoc()) {
    $holidays_str = $row['settings_value'];
}
$stmt_hol->close();

// সংশ্লিষ্ট ক্লাসের স্টুডেন্ট লিস্ট
$students = [];
$sy = '%' . $sy . '%';
$stmt_st = $conn->prepare("SELECT stid, rollno FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND status='1' ORDER BY rollno ASC");
$stmt_st->bind_param("ssss", $sy, $sccode, $classname, $sectionname);
$stmt_st->execute();
$res_st = $stmt_st->get_result();
while($row = $res_st->fetch_assoc()) $students[] = $row;
$stmt_st->close();

// হাজিরা ডেটা ফেচ করা (একবার কুয়েরি করে মেমরিতে রাখা - O(1) Lookup)
$attendance_map = [];
$sql_att = "SELECT stid, adate, yn, bunk FROM stattnd WHERE sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ? AND adate BETWEEN ? AND ?";
$stmt_att = $conn->prepare($sql_att);
$stmt_att->bind_param("ssssss", $sccode, $sy, $classname, $sectionname, $date_start, $date_end);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
while ($row = $res_att->fetch_assoc()) {
    $attendance_map[$row['stid']][$row['adate']] = ['yn' => $row['yn'], 'bunk' => $row['bunk']];
}
$stmt_att->close();
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* App Bar Style */
    .m3-app-bar {
        background-color: #FFFFFF;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1050;
    }

    /* Filter Card */
    .m3-card { background: #fff; border-radius: 24px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 16px; margin-bottom: 16px; }
    .form-select { border-radius: 12px; border: 1px solid #79747E; padding: 10px; font-size: 0.9rem; }

    /* Attendance Register Table Layout */
    .register-container {
        background: #fff;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .table-responsive { max-height: 65vh; overflow: auto; }
    .table { margin-bottom: 0; font-size: 0.85rem; }
    .table th, .table td { vertical-align: middle; text-align: center; border-color: #E7E0EC; }

    /* Sticky Header & Columns */
    .sticky-header { position: sticky; top: 0; background: #F3EDF7 !important; z-index: 10; font-weight: 700; color: #6750A4; }
    .sticky-col-roll { position: sticky; left: 0; background: #fff !important; z-index: 5; border-right: 2px solid #E7E0EC !important; width: 50px; }
    .sticky-col-name { position: sticky; left: 50px; background: #fff !important; z-index: 5; border-right: 2px solid #E7E0EC !important; width: 140px; text-align: left !important; }

    /* Attendance Indicators (Badges) */
    .att-indicator { width: 14px; height: 14px; border-radius: 50%; display: inline-block; }
    .bg-p { background-color: #4CAF50; } /* Present */
    .bg-a { background-color: #F44336; } /* Absent */
    .bg-b { background-color: #FFB300; } /* Bunk */
    .bg-h { background-color: #9E9E9E; } /* Holiday */
    .bg-n { background-color: #424242; } /* Not Taken */
    .bg-f { background-color: #F3EDF7; } /* Future */

    /* Legend Bar */
    .m3-legend {
        background: #F3EDF7; padding: 12px; border-radius: 16px;
        display: flex; flex-wrap: wrap; gap: 12px; font-size: 0.75rem; font-weight: 600;
    }
    .legend-item { display: flex; align-items: center; gap: 4px; }
</style>

<main class="container-fluid px-3 pt-3">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="reporthome.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h5 class="fw-bold mb-0">Monthly Register</h5>
                <small class="text-muted"><?php echo date('F Y', strtotime($date_start)); ?></small>
            </div>
        </div>
    </div>

    <div class="m3-card shadow-sm">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6">
                <label class="small fw-bold ms-2 mb-1">Class</label>
                <select name="cls" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachercls']; ?>" <?php echo ($c['cteachercls'] == $classname) ? 'selected' : ''; ?>><?php echo $c['cteachercls']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6">
                <label class="small fw-bold ms-2 mb-1">Section</label>
                <select name="sec" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachersec']; ?>" <?php echo ($c['cteachersec'] == $sectionname) ? 'selected' : ''; ?>><?php echo $c['cteachersec']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-5">
                <select name="month" class="form-select">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo $m; ?>" <?php echo ($m == intval($month)) ? 'selected' : ''; ?>><?php echo date('M', mktime(0, 0, 0, $m, 10)); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-4">
                <select name="year" class="form-select">
                    <?php for ($y = date('Y'); $y >= date('Y') - 2; $y--): ?>
                        <option value="<?php echo $y; ?>" <?php echo ($y == $year) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    <div class="register-container">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr class="sticky-header">
                        <th class="sticky-col-roll">Roll</th>
                        <th class="sticky-col-name">Student Name</th>
                        <?php for ($d = 1; $d <= $days_in_month; $d++): ?>
                            <th style="min-width: 35px;"><?php echo $d; ?></th>
                        <?php endfor; ?>
                        <th style="min-width: 60px;">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr><td colspan="<?php echo $days_in_month + 3; ?>" class="p-5 text-muted">No students found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($students as $st): 
                            $stid = $st['stid'];
                            // প্রোফাইল থেকে নাম খুঁজে বের করা
                            $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                            $st_name = ($st_idx !== false) ? $datam_st_profile[$st_idx]['stnameeng'] : 'ID: '.$stid;
                            
                            $open_days = 0; $present_days = 0;
                        ?>
                            <tr>
                                <td class="sticky-col-roll fw-bold"><?php echo $st['rollno']; ?></td>
                                <td class="sticky-col-name text-truncate small"><?php echo $st_name; ?></td>
                                <?php 
                                for ($d = 1; $d <= $days_in_month; $d++): 
                                    $c_date = "$year-$month-" . str_pad($d, 2, '0', STR_PAD_LEFT);
                                    $day_n = date('l', strtotime($c_date));
                                    $indicator = 'bg-f'; // Future
                                    
                                    if (strtotime($c_date) <= strtotime($today_date)) {
                                        if (str_contains($holidays_str, $day_n)) {
                                            $indicator = 'bg-h'; // Holiday
                                        } else {
                                            $open_days++;
                                            if (isset($attendance_map[$stid][$c_date])) {
                                                $att = $attendance_map[$stid][$c_date];
                                                if ($att['yn'] == '1') {
                                                    $present_days++;
                                                    $indicator = ($att['bunk'] == '1') ? 'bg-b' : 'bg-p';
                                                } else {
                                                    $indicator = 'bg-a';
                                                }
                                            } else {
                                                $indicator = 'bg-n'; // Not Taken
                                            }
                                        }
                                    }
                                ?>
                                    <td><span class="att-indicator <?php echo $indicator; ?>"></span></td>
                                <?php endfor; ?>
                                <td class="fw-bold text-primary">
                                    <?php echo ($open_days > 0) ? round(($present_days / $open_days) * 100) . '%' : '--'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="m3-legend">
            <div class="legend-item"><span class="att-indicator bg-p"></span> Present</div>
            <div class="legend-item"><span class="att-indicator bg-a"></span> Absent</div>
            <div class="legend-item"><span class="att-indicator bg-b"></span> Bunk</div>
            <div class="legend-item"><span class="att-indicator bg-h"></span> Weekend</div>
            <div class="legend-item"><span class="att-indicator bg-n"></span> Missing</div>
        </div>
    </div>
</main>

<div style="height: 60px;"></div>

<?php include 'footer.php'; ?>