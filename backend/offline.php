<?php
date_default_timezone_set('Asia/Dhaka');
include('inc.back.php');

// ইনপুট ডাটা রিসিভ করুন
$count = intval($_POST['count']);
$cls = $_POST['cls'] ?? '';
$sec = $_POST['sec'] ?? '';
$adate = $_POST['adate'] ?? '';
$eby = $_POST['eby'] ?? '';

echo "<pre>";
print_r($_POST);   // POST ডেটা দেখাবে
echo "</pre>";
// exit; 


// লুপ করে ডাটাবেজে ইন্সার্ট/আপডেট
for ($i = 0; $i < $count; $i++) {
    $stid = $_POST['stid' . $i] ?? '';
    $yn = $_POST['yn' . $i] ?? 0;
    $rollno = $i + 1;

    echo $stid .'/' . $yn . '/' . $rollno . '////';

    if ($stid != '') {
        $stmt = $conn->prepare("
    INSERT INTO stattnd 
    (sccode, sessionyear, stid, adate, 
     period1, period2, period3, period4, 
     period5, period6, period7, period8, 
     yn, entryby, entrytime, classname, sectionname, rollno)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        period1 = VALUES(period1),
        period2 = VALUES(period2),
        period3 = VALUES(period3),
        period4 = VALUES(period4),
        period5 = VALUES(period5),
        period6 = VALUES(period6),
        period7 = VALUES(period7),
        period8 = VALUES(period8),
        yn      = VALUES(yn),
        entryby = VALUES(entryby),
        entrytime = VALUES(entrytime),
        classname = VALUES(classname),
        sectionname = VALUES(sectionname),
        rollno  = VALUES(rollno)
");

        $stmt->bind_param(
            "isisiiiiiiiiissssi",
            $sccode,      // s
            $sy,          // s (sessionyear)
            $stid,        // i
            $adate,       // s
            $yn,
            $yn,
            $yn,
            $yn,
            $yn,
            $yn,
            $yn,
            $yn, // ssssss.. (period1–period8, একই ভ্যালু দিলে)
            $yn,          // i/string (main yn)
            $eby,         // s (entryby)
            $cur,         // s (entrytime)
            $cls,         // s (classname)
            $sec,         // s (sectionname)
            $rollno       // s (rollno)
        );

        $stmt->execute();
    }


}

echo "✅ Attendance synced successfully.";
?>