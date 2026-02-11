<?php
require_once "../inc.light.php";

$tid   = $_GET['tid'] ?? 0;
$month = $_GET['month'] ?? date('m');
$year  = $_GET['year'] ?? date('Y');

if(!$tid) die('Teacher ID missing');

$start = "$year-$month-01";
$end   = date("Y-m-t", strtotime($start));

/* -----------------------
   Weekend Days
------------------------ */
$weekendDays = [];
foreach($ins_all_settings as $row){
    if($row['setting_title'] === 'Weekends'){
        $weekendDays = explode(' ', trim($row['settings_value']));
        break;
    }
}

/* -----------------------
   Attendance
------------------------ */
$aq = $conn->prepare("
    SELECT adate, statusin, realin, realout
    FROM teacherattnd
    WHERE tid=? AND sccode=? AND adate BETWEEN ? AND ?
");
$aq->bind_param("iiss", $tid, $sccode, $start, $end);
$aq->execute();
$ar = $aq->get_result();

$att = [];
while($r=$ar->fetch_assoc()) $att[$r['adate']] = $r;

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
while($r=$lr->fetch_assoc()){
    $cur = $r['date_from'];
    while($cur <= $r['date_to']){
        $leave[$cur] = strtolower($r['leave_type']);
        $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
    }
}

/* -----------------------
   Dates
------------------------ */
$dates = [];
$d = $start;
while($d <= $end){
    $dates[] = $d;
    $d = date("Y-m-d", strtotime("+1 day", strtotime($d)));
}
?>

<div class="m3-section-title px-1 mt-4 mb-2 text-primary fw-black" style="font-size: 0.85rem; letter-spacing: 1px; border-bottom: 1px dashed var(--m3-outline-variant); padding-bottom: 5px;">
    <i class="bi bi-calendar-month me-2"></i><?= strtoupper(date("F Y", strtotime($start))) ?>
</div>

<div class="row g-0">
    <?php 
    // তারিখগুলোকে উল্টো করে দেখানো (নতুন থেকে পুরাতন)
    foreach(array_reverse($dates) as $dt):
        $cls=''; $icon='';
        $dayName = date('l', strtotime($dt));

        if(isset($leave[$dt])){
            $cls='leave'; $icon='LV';
        }
        elseif(in_array($dayName, $weekendDays)){
            $cls='weekend'; $icon='W';
        }
        elseif(isset($att[$dt])){
            $stin=strtoupper($att[$dt]['statusin']);
            if($stin=='ABSENT'){ $cls='absent'; $icon='A'; }
            elseif($stin=='LATE'){ $cls='late'; $icon='L'; }
            else{ $cls='present'; $icon='P'; }
        }
        else{
            $cls='absent'; $icon='A';
        }
    ?>
        <div class="col-12">
            <div class="attendance-tile shadow-sm mb-2" style="background: white; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; border: 1px solid #F0F0F0;">
                
                <div class="date-box text-center me-3 pe-3" style="border-right: 1px solid #EEE; min-width: 55px;">
                    <div class="day" style="font-size: 1.1rem; font-weight: 900; color: var(--m3-primary); line-height: 1;"><?= date('d', strtotime($dt)) ?></div>
                    <div class="month" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #79747E;"><?= date('D', strtotime($dt)) ?></div>
                </div>

                <div class="status-indicator <?= $cls ?> me-3" style="width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.9rem; flex-shrink: 0;">
                    <?= $icon ?>
                </div>

                <div class="flex-grow-1">
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= $dayName ?></div>
                    <?php if(isset($att[$dt])): ?>
                        <div class="time-info mt-1" style="font-size: 0.75rem; font-weight: 600; color: #79747E;">
                            In: <span class="text-dark"><?= $att[$dt]['realin'] ?: '--:--' ?></span> | 
                            Out: <span class="text-dark"><?= $att[$dt]['realout'] ?: '--:--' ?></span>
                        </div>
                    <?php elseif(isset($leave[$dt])): ?>
                        <div class="time-info text-warning fw-bold" style="font-size: 0.7rem;">On Leave: <?= strtoupper($leave[$dt]) ?></div>
                    <?php elseif(in_array($dayName, $weekendDays)): ?>
                        <div class="time-info text-muted" style="font-size: 0.7rem;">Weekly Holiday</div>
                    <?php else: ?>
                        <div class="time-info text-danger" style="font-size: 0.7rem;">No attendance record</div>
                    <?php endif; ?>
                </div>

                <i class="bi bi-chevron-right text-muted opacity-25"></i>
            </div>
        </div>
    <?php endforeach; ?>
</div>