<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-calendar.php';

// ১. ডাটা ফেচিং (Prepared Statement - Secure)
$c1 = $c2 = $c3 = $c4 = $c5 = $c6 = 0;
$datam_tattnd = [];
$ds = $sy . '-01-01';
$de = $sy . '-12-31';

$stmt_att = $conn->prepare("SELECT * FROM teacherattnd WHERE sccode = ? AND tid = ? AND adate BETWEEN ? AND ?");
$stmt_att->bind_param("ssss", $sccode, $userid, $ds, $de);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
while ($row = $res_att->fetch_assoc()) {
    $datam_tattnd[] = $row;
}
$stmt_att->close();

// ২. ছুটির আবেদন ফেচ করা
$my_app_datam = [];
$stmt_leave = $conn->prepare("SELECT date_from, date_to, status FROM teacher_leave_app WHERE sccode = ? AND tid = ? AND status = 1");
$stmt_leave->bind_param("ss", $sccode, $userid);
$stmt_leave->execute();
$res_leave = $stmt_leave->get_result();
while ($row = $res_leave->fetch_assoc()) {
    $my_app_datam[] = $row;
}
$stmt_leave->close();
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Top Stats Dashboard */
    .stats-container {
        background-color: #F3EDF7;
        border-radius: 28px;
        padding: 20px;
        margin: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .stat-box {
        text-align: center;
        padding: 10px 5px;
        border-radius: 16px;
        background: #fff;
        margin-bottom: 8px;
    }
    .stat-count { font-size: 1.2rem; font-weight: 800; line-height: 1; }
    .stat-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; margin-top: 4px; opacity: 0.7; }

    /* List Item Styling */
    .att-row {
        background: #fff;
        border-radius: 20px;
        padding: 14px 16px;
        margin: 0 16px 10px 16px;
        display: flex;
        align-items: center;
        border: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: 0.2s;
    }
    .att-row:active { background-color: #EADDFF; transform: scale(0.98); }

    .date-circle {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
    }
    .date-num { font-weight: 800; font-size: 1.1rem; line-height: 1; }
    .date-month { font-size: 0.65rem; text-transform: uppercase; font-weight: 700; }

    /* Icons and Status Text */
    .status-info { flex-grow: 1; overflow: hidden; }
    .status-main { font-weight: 700; color: #1C1B1F; font-size: 0.9rem; }
    .status-sub { font-size: 0.75rem; color: #49454F; }

    .time-badge { font-size: 0.7rem; font-weight: 700; color: #6750A4; background: #F3EDF7; padding: 2px 8px; border-radius: 6px; }

    /* Status Colors (M3 Tonal) */
    .bg-present { background-color: #E8F5E9; color: #2E7D32; }
    .bg-absent  { background-color: #FFEBEE; color: #D32F2F; }
    .bg-leave   { background-color: #E3F2FD; color: #1976D2; }
    .bg-weekend { background-color: #F5F5F5; color: #616161; }
    .bg-holiday { background-color: #FFF3E0; color: #E65100; }
</style>

<main class="pb-5">
    <div class="bg-white p-3 shadow-sm sticky-top mb-3 rounded-bottom-4">
        <div class="d-flex align-items-center">
            <a href="build.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <h5 class="fw-bold mb-0">Attendance Summary</h5>
        </div>
    </div>

    <div class="stats-container">
        <div class="row g-2">
            <div class="col-4"><div class="stat-box shadow-sm" style="color: #2E7D32;"><div class="stat-count" id="c1">0</div><div class="stat-label">Present</div></div></div>
            <div class="col-4"><div class="stat-box shadow-sm" style="color: #1976D2;"><div class="stat-count" id="c2">0</div><div class="stat-label">Leave</div></div></div>
            <div class="col-4"><div class="stat-box shadow-sm" style="color: #D32F2F;"><div class="stat-count" id="c3">0</div><div class="stat-label">Absent</div></div></div>
            <div class="col-4"><div class="stat-box shadow-sm" style="color: #616161;"><div class="stat-count" id="c4">0</div><div class="stat-label">Weekend</div></div></div>
            <div class="col-4"><div class="stat-box shadow-sm" style="color: #E65100;"><div class="stat-count" id="c5">0</div><div class="stat-label">Holiday</div></div></div>
            <div class="col-4"><div class="stat-box shadow-sm bg-primary text-white"><div class="stat-count" id="c6">0</div><div class="stat-label text-white">Total</div></div></div>
        </div>
    </div>

    <div class="px-1 mt-4">
        <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase">Daily Activity Log</h6>
        
        <?php
        $val_to = strtotime($cur);
        $val_from = strtotime($sy . '-01-01');
        $step = 3600 * 24;

        // উইকেন্ড সেটিং
        $wday_ind = array_search('Weekends', array_column($ins_all_settings, 'setting_title'));
        $wday_text = $ins_all_settings[$wday_ind]['settings_value'] ?? '';

        for ($x = $val_to; $x >= $val_from; $x -= $step):
            $run_date = date('Y-m-d', $x);
            $bar = date('l', $x);
            
            $status_title = "Absent";
            $status_sub = "No record found";
            $status_class = "bg-absent";
            $status_icon = "bi-x-circle-fill";
            $in_time = ""; $out_time = "";

            $workday_flag = 1;

            // ১. উইকেন্ড চেক
            if (str_contains($wday_text, $bar)) {
                $status_title = "Weekend"; $status_sub = "Weekly Holiday ($bar)"; $status_class = "bg-weekend";
                $status_icon = "bi-calendar-event"; $c4++; $workday_flag = 0;
            } else {
                // ২. ক্যালেন্ডার হলিডে চেক
                foreach ($datam_calendar_events as $eve) {
                    if ($eve['date'] == $run_date) {
                        $workday_flag *= $eve['class'];
                    }
                }
                if ($workday_flag == 0) {
                    $status_title = "Holiday"; $status_sub = "Scheduled Break"; $status_class = "bg-holiday";
                    $status_icon = "bi-sun-fill"; $c5++;
                } else {
                    // ৩. উপস্থিতির ডাটা চেক
                    $att_ind = array_search($run_date, array_column($datam_tattnd, 'adate'));
                    if ($att_ind !== false) {
                        $att_data = $datam_tattnd[$att_ind];
                        $status_title = ($att_data['statusin'] == 'Late') ? "Late Entry" : "Present";
                        $status_sub = "Via " . strtoupper($att_data['detectin']);
                        $status_class = "bg-present";
                        $status_icon = ($att_data['detectin'] == 'gps') ? "bi-geo-alt-fill" : "bi-fingerprint";
                        $in_time = $att_data['realin']; $out_time = $att_data['realout'];
                        $c1++;
                    } else {
                        // ৪. লিভ এপ্লিকেশন চেক
                        $leave_flag = 0;
                        foreach ($my_app_datam as $appl) {
                            if ($run_date >= $appl['date_from'] && $run_date <= $appl['date_to']) {
                                $leave_flag = 1; break;
                            }
                        }
                        if ($leave_flag) {
                            $status_title = "On Leave"; $status_sub = "Authorized Absence"; $status_class = "bg-leave";
                            $status_icon = "bi-bookmark-check-fill"; $c2++;
                        } else {
                            $c3++; // Absent
                        }
                    }
                }
            }
        ?>
            <div class="att-row shadow-sm">
                <div class="date-circle <?php echo $status_class; ?>">
                    <div class="date-num"><?php echo date('d', $x); ?></div>
                    <div class="date-month"><?php echo date('M', $x); ?></div>
                </div>
                
                <div class="status-info">
                    <div class="status-main"><?php echo $status_title; ?></div>
                    <div class="status-sub"><i class="bi <?php echo $status_icon; ?> me-1"></i> <?php echo $status_sub; ?></div>
                </div>

                <?php if($in_time): ?>
                <div class="text-end">
                    <div class="time-badge mb-1">In: <?php echo $in_time; ?></div>
                    <?php if($out_time): ?><div class="time-badge">Out: <?php echo $out_time; ?></div><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>
</main>

<div style="height: 60px;"></div>

<script>
    // পরিসংখ্যান আপডেট করা
    document.getElementById("c1").innerText = "<?php echo $c1; ?>";
    document.getElementById("c2").innerText = "<?php echo $c2; ?>";
    document.getElementById("c3").innerText = "<?php echo $c3; ?>";
    document.getElementById("c4").innerText = "<?php echo $c4; ?>";
    document.getElementById("c5").innerText = "<?php echo $c5; ?>";
    document.getElementById("c6").innerText = "<?php echo ($c1+$c2+$c3+$c4+$c5); ?>";
</script>

<?php include 'footer.php'; ?>