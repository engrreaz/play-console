<?php
require_once "../inc.light.php"; // ডাটাবেস এবং সেটিংস লোড

$stid = isset($_GET['stid']) ? intval($_GET['stid']) : 0;
$month = isset($_GET['month']) ? sprintf('%02d', $_GET['month']) : date('m');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

if (!$stid)
    die('Invalid Student ID');

// ১. ওই মাসের তারিখ রেঞ্জ নির্ধারণ
$start = "$year-$month-01";
$end = date("Y-m-t", strtotime($start));

/* -----------------------
   Weekend Settings
------------------------ */
/* -----------------------
   Weekend Settings (উন্নত লজিক)
------------------------ */
$weekendDays = [];
foreach ($ins_all_settings as $row) {
    if ($row['setting_title'] === 'Weekends') {
        // ডট, কমা বা স্পেস—যাই থাকুক না কেন সেটিকে আলাদা করবে (Regex ব্যবহার করে)
        $raw_value = trim($row['settings_value']);
        $weekendDays = preg_split('/[\s,.]+/', $raw_value);

        // সবগুলোকে Capitalize (যেমন: friday -> Friday) করে নেওয়া যাতে date('l') এর সাথে মেলে
        $weekendDays = array_map(function ($day) {
            return ucfirst(strtolower(trim($day)));
        }, $weekendDays);
        break;
    }
}

/* -----------------------
   Attendance Data (stattnd)
------------------------ */
$stmt_att = $conn->prepare("SELECT adate, yn, bunk FROM stattnd WHERE stid = ? AND sccode = ? AND adate BETWEEN ? AND ?");
$stmt_att->bind_param("iiss", $stid, $sccode, $start, $end);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
$att_data = [];
while ($r = $res_att->fetch_assoc()) {
    $att_data[$r['adate']] = $r;
}
$stmt_att->close();

/* -----------------------
   Leave Data (student_leave_app)
------------------------ */
$stmt_lv = $conn->prepare("SELECT date_from, date_to FROM student_leave_app WHERE stid = ? AND sccode = ? AND date_from <= ? AND date_to >= ?");
$stmt_lv->bind_param("iiss", $stid, $sccode, $end, $start);
$stmt_lv->execute();
$res_lv = $stmt_lv->get_result();
$leave_dates = [];
while ($r = $res_lv->fetch_assoc()) {
    $cur = $r['date_from'];
    while ($cur <= $r['date_to']) {
        $leave_dates[$cur] = true;
        $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
    }
}

/* -----------------------
   Calendar/Holiday Data
------------------------ */
$stmt_cal = $conn->prepare("SELECT date, dateto, category, work FROM calendar WHERE (sccode=? OR sccode=0) AND (date BETWEEN ? AND ? OR dateto BETWEEN ? AND ?)");
$stmt_cal->bind_param("issss", $sccode, $start, $end, $start, $end);
$stmt_cal->execute();
$res_cal = $stmt_cal->get_result();
$holidays = [];
while ($r = $res_cal->fetch_assoc()) {
    $to = $r['dateto'] ?: $r['date'];
    $cur = $r['date'];
    while ($cur <= $to) {
        $holidays[$cur] = $r['category'];
        $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
    }
}

/* -----------------------
   Generate Date Array
------------------------ */
$dates = [];
$d = $start;
while ($d <= $end) {
    $dates[] = $d;
    $d = date("Y-m-d", strtotime("+1 day", strtotime($d)));
}
$dates = array_reverse($dates);
?>

<div class="m3-section-title px-1 mt-4 mb-2 text-primary fw-black"
    style="font-size: 0.85rem; letter-spacing: 1px; border-bottom: 1px dashed #CAC4D0; padding-bottom: 5px;">
    <i class="bi bi-calendar-event me-2"></i><?= strtoupper(date("F Y", strtotime($start))) ?>
</div>

<?php foreach ($dates as $dt):
    $dayName = date('l', strtotime($dt));
    $is_h = isset($holidays[$dt]);
    $is_w = in_array($dayName, $weekendDays);
    $is_lv = isset($leave_dates[$dt]);
    $is_att = isset($att_data[$dt]);

    $cls = 'absent';
    $icon = 'A';
    $status_txt = 'Absent from Class';

    if ($is_h) {
        $cls = 'off';
        $icon = 'H';
        $status_txt = $holidays[$dt];
    } elseif ($is_w) {
        $cls = 'off';
        $icon = 'W';
        $status_txt = 'Weekly Weekend';
    } elseif ($is_lv) {
        $cls = 'leave';
        $icon = 'LV';
        $status_txt = 'Approved Leave';
    } elseif ($is_att) {
        if ($att_data[$dt]['yn'] == 1) {
            $cls = ($att_data[$dt]['bunk'] == 1) ? 'bunk' : 'present';
            $icon = ($att_data[$dt]['bunk'] == 1) ? 'B' : 'P';
            $status_txt = ($att_data[$dt]['bunk'] == 1) ? 'Bunked after Attendance' : 'Present in Class';
        }
    }
    ?>
    <div class="att-tile shadow-sm"
        style="background: white; border-radius: 16px; padding: 12px 16px; margin-bottom: 10px; display: flex; align-items: center; border: 1px solid #F0F0F0;">
        <div class="date-box text-center me-3 pe-3" style="border-right: 1px solid #EEE; min-width: 50px;">
            <div class="day" style="font-size: 1.2rem; font-weight: 900; color: #6750A4; line-height: 1;">
                <?= date('d', strtotime($dt)) ?></div>
            <div class="wk" style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; color: #79747E;">
                <?= date('D', strtotime($dt)) ?></div>
        </div>
        <div class="status-icon <?= $cls ?> me-3"
            style="width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1rem; flex-shrink: 0;">
            <?= $icon ?>
        </div>
        <div class="flex-grow-1">
            <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= $status_txt ?></div>
            <div class="small text-muted"><?= $dayName ?></div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </div>
<?php endforeach; ?>



<?php
// পরিসংখ্যান ক্যালকুলেশন (অ্যাজাক্স ফাইলের ভেতরেই লুপের বাইরে)
$m_p = 0;
$m_a = 0;
$m_b = 0;
$m_lv = 0;

foreach ($dates as $dt) {
    $dayName = date('l', strtotime($dt));
    $is_h = isset($holidays[$dt]);
    $is_w = in_array($dayName, $weekendDays);
    $is_lv = isset($leave_dates[$dt]);
    $is_att = isset($att_data[$dt]);

    if ($is_h || $is_w)
        continue; // ছুটি বা উইকেন্ড কাউন্ট হবে না

    if ($is_lv) {
        $m_lv++;
    } elseif ($is_att) {
        if ($att_data[$dt]['yn'] == 1) {
            $m_p++;
            if ($att_data[$dt]['bunk'] == 1)
                $m_b++;
        } else {
            $m_a++;
        }
    } else {
        $m_a++;
    }
}
?>

<div class="new-stats-data d-none" data-p="<?= $m_p ?>" data-a="<?= $m_a ?>" data-b="<?= $m_b ?>"
    data-lv="<?= $m_lv ?>">
</div>