<?php
/**
 * Offline Attendance Sync Backend
 * Logic: Optimized Prepared Statements (Prepare Once, Execute Many)
 * Performance: High | Security: SQL Injection Protected
 */

date_default_timezone_set('Asia/Dhaka');
include('../inc.light.php');

// ১. ইনপুট ডাটা রিসিভ ও ভ্যালিডেশন
$count = isset($_POST['count']) ? intval($_POST['count']) : 0;
$cls   = $_POST['cls']   ?? '';
$sec   = $_POST['sec']   ?? '';
$adate = $_POST['adate'] ?? '';
$eby   = $_POST['eby']   ?? '';

// যদি ডাটা না থাকে তবে প্রসেস বন্ধ করা
if ($count <= 0 || empty($adate)) {
    die("❌ No data received for syncing.");
}

// ২. সিকিউর কুয়েরি প্রিপারেশন (লুপের বাইরে - Performance Boost)
$sql = "INSERT INTO stattnd 
        (sccode, sessionyear, stid, adate, 
         period1, period2, period3, period4, 
         period5, period6, period7, period8, 
         yn, entryby, entrytime, classname, sectionname, rollno)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            period1 = VALUES(period1), period2 = VALUES(period2),
            period3 = VALUES(period3), period4 = VALUES(period4),
            period5 = VALUES(period5), period6 = VALUES(period6),
            period7 = VALUES(period7), period8 = VALUES(period8),
            yn      = VALUES(yn), entryby = VALUES(entryby),
            entrytime = VALUES(entrytime), classname = VALUES(classname),
            sectionname = VALUES(sectionname), rollno = VALUES(rollno)";

$stmt = $conn->prepare($sql);

if ($stmt) {
    // ৩. লুপ করে ডাটা প্রসেস করা
    for ($i = 0; $i < $count; $i++) {
        $stid   = $_POST['stid' . $i] ?? '';
        $yn     = isset($_POST['yn' . $i]) ? intval($_POST['yn' . $i]) : 0;
        $rollno = $i + 1; // আপনার বর্তমান লজিক অনুযায়ী রোল সেট করা হচ্ছে

        if (!empty($stid)) {
            // "isisiiiiiiiiissssi" -> প্যারামিটার টাইপ ম্যাপিং
            $stmt->bind_param(
                "isisiiiiiiiiissssi",
                $sccode,    // inc.back থেকে প্রাপ্ত
                $sy,        // inc.back থেকে প্রাপ্ত (Session Year)
                $stid,      // Student ID
                $adate,     // Attendance Date
                $yn, $yn, $yn, $yn, $yn, $yn, $yn, $yn, // Period 1-8
                $yn,        // Main YN status
                $eby,       // Entered By
                $cur,       // Entry Time (Current Time from inc.back)
                $cls,       // Class Name
                $sec,       // Section Name
                $rollno     // Roll No
            );

            $stmt->execute();
        }
    }
    $stmt->close();
    
    // ৪. সাকসেস রেসপন্স
    echo "✅ Success: $count records synced to cloud.";
} else {
    // এরর হ্যান্ডলিং
    http_response_code(500);
    echo "❌ Server Error: Database sync failed.";
}

$conn->close();