<?php
include '../inc.light.php'; // আপনার ফাইল পাথ অনুযায়ী পরিবর্তন করুন

// ১. ইনপুট প্যারামিটার রিসিভ করা
$cls  = $_POST['classname'] ?? '';
$sec  = $_POST['sectionname'] ?? '';
$date = $_POST['date'] ?? '';

if (empty($cls) || empty($sec) || empty($date)) {
    die("Error: Missing required fields.");
}

// ২. stattnd টেবিল থেকে ওই নির্দিষ্ট ক্লাস, সেকশন ও তারিখের ক্যালকুলেশন করা
$stmt_calc = $conn->prepare("SELECT 
        COUNT(*) as total, 
        SUM(CASE WHEN yn = 1 THEN 1 ELSE 0 END) as present,
        SUM(CASE WHEN bunk = 1 THEN 1 ELSE 0 END) as bunks
    FROM stattnd 
    WHERE sccode = ? AND adate = ? AND classname = ? AND sectionname = ?");

$stmt_calc->bind_param("isss", $sccode, $date, $cls, $sec);
$stmt_calc->execute();
$calc_res = $stmt_calc->get_result()->fetch_assoc();
$stmt_calc->close();

$total_st   = (int)($calc_res['total'] ?? 0);
$present_st = (int)($calc_res['present'] ?? 0);
$bunk_st    = (int)($calc_res['bunks'] ?? 0);
$attnd_rate = ($total_st > 0) ? round(($present_st * 100) / $total_st) : 0;

// ৩. stattndsummery টেবিলে আগে থেকে রেকর্ড আছে কি না চেক করা
$stmt_check = $conn->prepare("SELECT id FROM stattndsummery WHERE sccode = ? AND date = ? AND classname = ? AND sectionname = ? LIMIT 1");
$stmt_check->bind_param("isss", $sccode, $date, $cls, $sec);
$stmt_check->execute();
$exist = $stmt_check->get_result()->fetch_assoc();
$stmt_check->close();

if ($exist) {
    // ৪. রেকর্ড থাকলে UPDATE করা
    $stmt_upd = $conn->prepare("UPDATE stattndsummery SET 
            totalstudent = ?, 
            attndstudent = ?, 
            bunk = ?, 
            attndrate = ?, 
            sessionyear = ? 
        WHERE id = ?");
    $stmt_upd->bind_param("iiiisi", $total_st, $present_st, $bunk_st, $attnd_rate, $sy, $exist['id']);
    $success = $stmt_upd->execute();
    $stmt_upd->close();
} else {
    // ৫. রেকর্ড না থাকলে নতুন INSERT করা
    $stmt_ins = $conn->prepare("INSERT INTO stattndsummery 
        (sccode, sessionyear, date, classname, sectionname, totalstudent, attndstudent, bunk, attndrate) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_ins->bind_param("issssiiii", $sccode, $sy, $date, $cls, $sec, $total_st, $present_st, $bunk_st, $attnd_rate);
    $success = $stmt_ins->execute();
    $stmt_ins->close();
}

// ৬. ফলাফল পাঠানো
if ($success) {
    echo "success";
} else {
    echo "Error updating summary: " . $conn->error;
}