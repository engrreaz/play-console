<?php
/**
 * Attendance Summary - M3-EIM-Floating Style
 * Standards: 8px Radius | Tonal Palette | Android WebView Optimized
 */
$page_title = "Attendance Summary";
include 'inc.php'; 
include 'datam/datam-calendar.php';

// ১. ডাটা ফেচিং (প্যারামিটারগুলো inc.php থেকে আসে)
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
    body { background-color: #FEF7FF; margin: 0; padding: 0; }

    /* M3 App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Stats Dashboard (Strict 8px) */
    .m3-stats-card {
        background-color: #F3EDF7;
        border-radius: 8px !important;
        padding: 16px; margin: 12px 16px;
        box-shadow: 0 1px 3px rgba(103, 80, 164, 0.05);
    }
    
    .m3-stat-item {
        background: #fff; border-radius: 8px; 
        text-align: center; padding: 12px 4px;
        border: 1px solid #EADDFF;
    }
    .m3-stat-count { font-size: 1.15rem; font-weight: 900; line-height: 1; color: #21005D; }
    .m3-stat-label { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; margin-top: 5px; color: #6750A4; }

    /* Daily Log Cards (M3-EIM-Floating Style) */
    .m3-log-card {
        background: #fff; border-radius: 8px; padding: 12px 16px;
        margin: 0 16px 10px; display: flex; align-items: center;
        border: 1px solid #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: 0.2s cubic-bezier(0, 0, 0.2, 1);
    }
    .m3-log-card:active { background-color: #F7F2FA; transform: scale(0.98); }

    .m3-date-box {
        width: 46px; height: 46px; border-radius: 8px; /* Strict 8px */
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        margin-right: 14px; flex-shrink: 0;
    }
    .m3-date-num { font-weight: 900; font-size: 1rem; line-height: 1; }
    .m3-date-month { font-size: 0.6rem; text-transform: uppercase; font-weight: 800; }

    .m3-status-content { flex-grow: 1; }
    .m3-status-text { font-weight: 800; color: #1C1B1F; font-size: 0.85rem; }
    .m3-status-meta { font-size: 0.7rem; color: #79747E; font-weight: 600; display: flex; align-items: center; gap: 4px; }

    .m3-time-pill { font-size: 0.65rem; font-weight: 800; color: #6750A4; background: #F3EDF7; padding: 3px 10px; border-radius: 6px; white-space: nowrap; }

    /* Tonal Palette Colors */
    .tone-present { background-color: #E8F5E9; color: #2E7D32; }
    .tone-absent  { background-color: #FFEBEE; color: #B3261E; }
    .tone-leave   { background-color: #E3F2FD; color: #1976D2; }
    .tone-weekend { background-color: #F5F5F5; color: #49454F; }
    .tone-holiday { background-color: #FFF3E0; color: #E65100; }
</style>


<main class="pb-5">
    <div class="m3-stats-card shadow-sm">
        <div class="row g-2">
            <div class="col-4"><div class="m3-stat-item"><div class="m3-stat-count" id="c1" style="color: #2E7D32;">0</div><div class="m3-stat-label">Present</div></div></div>
            <div class="col-4"><div class="m3-stat-item"><div class="m3-stat-count" id="c2" style="color: #1976D2;">0</div><div class="m3-stat-label">Leave</div></div></div>
            <div class="col-4"><div class="m3-stat-item"><div class="m3-stat-count" id="c3" style="color: #B3261E;">0</div><div class="m3-stat-label">Absent</div></div></div>
            <div class="col-4"><div class="m3-stat-item"><div class="m3-stat-count" id="c4" style="color: #49454F;">0</div><div class="m3-stat-label">Weekend</div></div></div>
            <div class="col-4"><div class="m3-stat-item"><div class="m3-stat-count" id="c5" style="color: #E65100;">0</div><div class="m3-stat-label">Holiday</div></div></div>
            <div class="col-4"><div class="m3-stat-item" style="background: #6750A4; border: none;"><div class="m3-stat-count text-white" id="c6">0</div><div class="m3-stat-label text-white opacity-75">Total</div></div></div>
        </div>
    </div>

    <div class="px-1 mt-4">
        <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase" style="letter-spacing: 1px;">Daily Activity Log</h6>
        
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
            $status_class = "tone-absent";
            $status_icon = "bi-x-circle-fill";
            $in_time = ""; $out_time = "";
            $workday_flag = 1;

            // ১. উইকেন্ড চেক
            if (str_contains($wday_text, $bar)) {
                $status_title = "Weekend"; $status_sub = "Weekly Break ($bar)"; $status_class = "tone-weekend";
                $status_icon = "bi-calendar2-week"; $c4++; $workday_flag = 0;
            } else {
                // ২. ক্যালেন্ডার হলিডে চেক
                foreach ($datam_calendar_events as $eve) {
                    if ($eve['date'] == $run_date) {
                        $workday_flag *= $eve['class'];
                    }
                }
                if ($workday_flag == 0) {
                    $status_title = "Holiday"; $status_sub = "Institutional Holiday"; $status_class = "tone-holiday";
                    $status_icon = "bi-sun-fill"; $c5++;
                } else {
                    // ৩. উপস্থিতির ডাটা চেক
                    $att_ind = array_search($run_date, array_column($datam_tattnd, 'adate'));
                    if ($att_ind !== false) {
                        $att_data = $datam_tattnd[$att_ind];
                        $status_title = ($att_data['statusin'] == 'Late') ? "Late Entry" : "Present";
                        $status_sub = "Clocked via " . strtoupper($att_data['detectin']);
                        $status_class = "tone-present";
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
                            $status_title = "On Leave"; $status_sub = "Authorized Leave"; $status_class = "tone-leave";
                            $status_icon = "bi-bookmark-star-fill"; $c2++;
                        } else {
                            $c3++; // Absent
                        }
                    }
                }
            }
        ?>
            <div class="m3-log-card shadow-sm">
                <div class="m3-date-box <?php echo $status_class; ?>">
                    <div class="m3-date-num"><?php echo date('d', $x); ?></div>
                    <div class="m3-date-month"><?php echo date('M', $x); ?></div>
                </div>
                
                <div class="m3-status-content">
                    <div class="m3-status-text"><?php echo $status_title; ?></div>
                    <div class="m3-status-meta">
                        <i class="bi <?php echo $status_icon; ?>"></i> 
                        <?php echo $status_sub; ?>
                    </div>
                </div>

                <?php if($in_time): ?>
                <div class="text-end ms-2">
                    <div class="m3-time-pill mb-1">IN: <?php echo $in_time; ?></div>
                    <?php if($out_time): ?><div class="m3-time-pill">OUT: <?php echo $out_time; ?></div><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>
</main>

<div style="height: 60px;"></div>

<?php 
// আপনার নির্দেশ অনুযায়ী JS স্ক্রিপ্ট শুরু করার আগে footer.php ইনক্লুড করা হলো
include 'footer.php'; 
?>

<script>
    /**
     * Update Dashboard Stats Dynamically
     */
    $(document).ready(function() {
        $("#c1").text("<?php echo $c1; ?>");
        $("#c2").text("<?php echo $c2; ?>");
        $("#c3").text("<?php echo $c3; ?>");
        $("#c4").text("<?php echo $c4; ?>");
        $("#c5").text("<?php echo $c5; ?>");
        $("#c6").text("<?php echo ($c1+$c2+$c3+$c4+$c5); ?>");
    });
</script>