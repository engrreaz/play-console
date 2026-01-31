<?php
// ফাইল: front-page-block/schedule.php

$day_today = date('l'); 
$date_today = date('Y-m-d');
$now_time = date('H:i:s');

$current_class = null;
$next_class = null;
$special_event = null;
$show_routine = true; // ডিফল্টভাবে রুটিন দেখাবে

if (isset($conn, $sccode)) {
    
    // ১. ক্যালেন্ডার/ইভেন্ট চেক করা (সবার আগে)
    $q_cal = $conn->query("SELECT * FROM calendar WHERE date='$date_today' AND (sccode='$sccode' OR sccode=0) ORDER BY sccode DESC LIMIT 1");
    
    if($q_cal->num_rows > 0) {
        $special_event = $q_cal->fetch_assoc();
        
        // যদি ওই দিনে ক্লাস বন্ধ থাকে (class=0)
        if($special_event['class'] == 0) {
            $show_routine = false;
        }
    }

    // ২. উইকেন্ড চেক করা (যদি ক্যালেন্ডারে বিশেষ কিছু না থাকে)
    if ($show_routine) {
        $q_wk = $conn->query("SELECT settings_value FROM settings WHERE sccode='$sccode' AND setting_title='Weekends' LIMIT 1");
        $weekends = ($q_wk->num_rows > 0) ? explode(".", trim($q_wk->fetch_assoc()['settings_value'])) : [];
        
        // যদি আজ উইকেন্ড হয় এবং ক্যালেন্ডারে জোর করে ক্লাস করানোর নির্দেশ না থাকে
        if (in_array($day_today, $weekends) && (!$special_event || $special_event['class'] == 0)) {
            $show_routine = false;
        }
    }

    // ৩. রুটিন ফেচ করা (যদি দিনটি ক্লাস করার উপযুক্ত হয়)
    if ($show_routine) {
        $sql_sched = "
            (SELECT *, 'current' as status FROM classroutine 
             WHERE sccode = ? AND day = ? AND ? BETWEEN periodtime AND periodtimeend LIMIT 1)
            UNION
            (SELECT *, 'next' as status FROM classroutine 
             WHERE sccode = ? AND day = ? AND periodtime > ? 
             ORDER BY periodtime ASC LIMIT 1)
        ";

        $stmt_sched = $conn->prepare($sql_sched);
        $stmt_sched->bind_param("ssssss", $sccode, $day_today, $now_time, $sccode, $day_today, $now_time);
        $stmt_sched->execute();
        $res_sched = $stmt_sched->get_result();

        while ($row = $res_sched->fetch_assoc()) {
            if ($row['status'] == 'current') $current_class = $row;
            else $next_class = $row;
        }
        $stmt_sched->close();
    }
}
?>

<style>
    .m3-schedule-card { background: #fff; border-radius: 8px; padding: 14px; border: 1px solid rgba(0,0,0,0.05); }
    .event-card { border-radius: 8px; padding: 20px; text-align: center; transition: 0.3s; }
    
    /* ইভেন্ট টাইপ অনুযায়ী ডাইনামিক কালার */
    .ev-holiday { background: #FDE7E9; border: 1px dashed #F2B8B5; color: #601410; }
    .ev-weekend { background: #FFF8E1; border: 1px dashed #FFD54F; color: #7F4D00; }
    .ev-special { background: #E8F0FF; border: 1px dashed #ADC6FF; color: #002D85; }

    .ongoing-box { background: #F3EDF7; border-radius: 10px; padding: 12px; border: 1px solid #EADDFF; }
</style>

<div class="m3-schedule-card shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-bold text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.8px;">
            <i class="bi bi-calendar3-event me-1 text-primary"></i> 
            <?php echo !$show_routine ? 'Special Day / Holiday' : 'Class Schedule'; ?>
        </span>
        <span class="badge bg-light text-dark fw-bold" style="font-size: 0.6rem;"><?php echo date('h:i A'); ?></span>
    </div>

    <?php 
    // কেস ১: ক্যালেন্ডারে কোনো ইভেন্ট বা হলিডে পাওয়া গেছে
    if ($special_event && ($special_event['descrip'] != "" || $special_event['class'] == 0)): 
        $box_class = ($special_event['class'] == 0) ? 'ev-holiday' : 'ev-special';
        $icon = $special_event['icon'] ?: 'calendar-event-fill';
    ?>
        <div class="event-card <?php echo $box_class; ?> shadow-sm">
            <i class="bi bi-<?php echo $icon; ?> display-5 opacity-50"></i>
            <div class="fw-black h5 mt-2 mb-1"><?php echo $special_event['category'] ?: 'Institution Event'; ?></div>
            <p class="small fw-bold mb-0 opacity-75"><?php echo $special_event['descrip']; ?></p>
            
            <?php if($special_event['class'] == 1): // ইভেন্ট আছে কিন্তু ক্লাসও চলছে ?>
                <hr class="opacity-10">
                <div class="small fw-bold"><i class="bi bi-info-circle me-1"></i> Special classes are in progress</div>
            <?php endif; ?>
        </div>

    <?php 
    // কেস ২: আজ ক্যালেন্ডারে কিছু নেই কিন্তু সেটি উইকেন্ড
    elseif (!$show_routine): 
    ?>
        <div class="event-card ev-weekend shadow-sm">
            <i class="bi bi-cup-hot-fill display-5 opacity-50"></i>
            <div class="fw-black h5 mt-2 mb-1">Happy Weekend</div>
            <p class="small fw-bold mb-0 opacity-75">Take a rest! Classes are off for today.</p>
        </div>

    <?php 
    // কেস ৩: নিয়মিত ক্লাস শিডিউল
    elseif ($current_class): 
    ?>
        <div class="ongoing-box shadow-sm">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="status-pill mb-1" style="background:#C8E6C9; color:#1B5E20; font-size:0.6rem; padding:2px 8px; border-radius:4px; font-weight:800;">
                        <span class="spinner-grow spinner-grow-sm me-1" style="width:6px; height:6px;"></span> ONGOING
                    </div>
                    <div class="h6 fw-bold mb-0"><?php echo htmlspecialchars($current_class['subject']); ?></div>
                    <div class="small text-muted fw-bold" style="font-size:0.65rem;">Room: <?php echo $current_class['room_no'] ?? 'N/A'; ?></div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-primary"><?php echo date('h:i A', strtotime($current_class['periodtimeend'])); ?></div>
                    <div class="small text-muted fw-bold" style="font-size: 0.55rem;">ENDS AT</div>
                </div>
            </div>
            <?php 
                $start = strtotime($current_class['periodtime']);
                $end = strtotime($current_class['periodtimeend']);
                $now = time();
                $diff = ($end > $start) ? (($now - $start) / ($end - $start)) * 100 : 0;
                $diff = max(0, min(100, $diff));
            ?>
            <div class="mt-3" style="background:#E7E0EC; height:6px; border-radius:10px; overflow:hidden;">
                <div style="background:#6750A4; height:100%; width: <?php echo $diff; ?>%;"></div>
            </div>
        </div>

        <?php if ($next_class): ?>
            <div class="d-flex align-items-center mt-3 p-2 bg-light rounded-3 border">
                <span class="small fw-bold text-muted text-uppercase me-2" style="font-size:0.55rem;">Next:</span>
                <span class="small fw-bold text-dark flex-grow-1 text-truncate"><?php echo htmlspecialchars($next_class['subject']); ?></span>
                <span class="badge bg-white text-primary border" style="font-size:0.6rem;"><?php echo date('h:i A', strtotime($next_class['periodtime'])); ?></span>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-4 opacity-50">
            <i class="bi bi-calendar-check display-6"></i>
            <p class="fw-bold mt-2 small">No more classes for today.</p>
        </div>
    <?php endif; ?>
</div>