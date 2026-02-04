<?php
/**
 * Save Class Routine - M3-EIM Standard
 * Logic: Prepared Statements | Tonal Feedback | Smart Data Mapping
 */
include '../inc.light.php';

// ১. ইনপুট ডাটা গ্রহণ ও স্যানিটাইজেশন
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$sub = $_POST['sub'] ?? 0;
$tid = $_POST['tid'] ?? 0;
$cls = $_POST['cls'] ?? '';
$sec = $_POST['sec'] ?? '';
$period = $_POST['period'] ?? 0;
$wday = $_POST['wday'] ?? 0;

if (!empty($_POST['days']) && is_array($_POST['days'])) {
    $days = $_POST['days'];
} else {

    // আজকের দিন নাও (1–7 format)
    $today = date('w'); // 0=Sunday
    $today = ($today == 0) ? 1 : $today + 1;

    $days = [$today];
}


// ২. দিনের নাম ম্যাপিং (wday থেকে অটোমেটিক জেনারেট)
$days_map = [
    1 => 'Sunday',
    2 => 'Monday',
    3 => 'Tuesday',
    4 => 'Wednesday',
    5 => 'Thursday',
    6 => 'Friday',
    7 => 'Saturday'
];
$day_name = $days_map[$wday] ?? 0;

// ৩. ডাটাবেজ লজিক (Prepared Statements)

$update = true;

foreach ($days as $day) {

    $day = intval($day);
    $day_name = $days_map[$day];

    $check = $conn->query("SELECT id FROM clsroutine 
        WHERE sccode='$sccode' AND sessionyear LIKE '$sessionyear_param'
        AND classname='$cls' AND sectionname='$sec'
        AND period='$period' AND wday='$day'");

    if ($check->num_rows) {

        $old = $check->fetch_assoc();

        $stmt = $conn->prepare(
            "UPDATE clsroutine SET subcode=?,tid=? WHERE id=?"
        );
        $stmt->bind_param("iii", $sub, $tid, $old['id']);

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO clsroutine
            (sccode,sessionyear,classname,sectionname,period,wday,day,subcode,tid,entryby)
            VALUES (?,?,?,?,?,?,?,?,?,?)"
        );

        $stmt->bind_param(
            "ssssiisiis",
            $sccode,
            $sessionyear,
            $cls,
            $sec,
            $period,
            $day,
            $day_name,
            $sub,
            $tid,
            $usr
        );
    }

    if ($stmt->execute()) {
        $update = true;
    } else {
        $update = false;
        break;
    }
}





$stmt->close();
$conn->close();