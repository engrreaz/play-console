<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

$current_time = $cur; 
$today = $td;

// ইন এবং আউট টাইম সেটিংস
$reqin = ($in_time_user != '00:00:00') ? $in_time_user : $in_time;
$reqout = ($out_time_user != '00:00:00') ? $out_time_user : $out_time;

$inout = 'in';
$stst = '';

// ১. হাজিরা প্রসেসিং লজিক (Prepared Statements)
$stmt = $conn->prepare("SELECT id FROM teacherattnd WHERE user = ? AND adate = ? AND sccode = ? LIMIT 1");
$stmt->bind_param("sss", $usr, $today, $sccode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // --- Attendance Out সেশন ---
    $row = $result->fetch_assoc();
    $db_id = $row["id"];
    $full_reqout = $today . ' ' . $reqout;
    $diff_seconds = strtotime($current_time) - strtotime($full_reqout);
    
    $stst = ($diff_seconds < 0) ? 'Fast' : 'Late';
    $abs_diff = abs($diff_seconds);
    $balout = sprintf('%02d:%02d:%02d', ($abs_diff/3600), ($abs_diff/60%60), ($abs_diff%60));

    $upd = $conn->prepare("UPDATE teacherattnd SET realout=?, balout=?, statusout=?, detectout='GPS', disout=? WHERE id=?");
    $upd->bind_param("sssdi", $current_time, $balout, $stst, $distance, $db_id);
    $upd->execute();
    $inout = 'out';
} else {
    // --- Attendance In সেশন ---
    $full_reqin = $today . ' ' . $reqin;
    $diff_seconds = strtotime($current_time) - strtotime($full_reqin);
    
    $stst = ($diff_seconds < 0) ? 'Fast' : 'Late';
    $abs_diff = abs($diff_seconds);
    $balin = sprintf('%02d:%02d:%02d', ($abs_diff/3600), ($abs_diff/60%60), ($abs_diff%60));

    // ইনসার্ট কুয়েরি থেকে sessionyear সরিয়ে ফেলা হয়েছে
    $ins = $conn->prepare("INSERT INTO teacherattnd (user, tid, adate, reqin, reqout, realin, balin, statusin, detectin, disin, sccode, entryby, entrytime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'GPS', ?, ?, ?, ?)");
    $ins->bind_param("ssssssssdsss", $usr, $userid, $today, $full_reqin, $reqout, $current_time, $balin, $stst, $distance, $sccode, $usr, $current_time);
    $ins->execute();

    // টোডো লিস্টে আউট এন্ট্রি যোগ করা
    if ($tattndout == 1) {
        $todo = $conn->prepare("INSERT INTO todolist (sccode, date, user, todotype, descrip1, status, creationtime, response, responsetxt) VALUES (?, ?, ?, 'Attendance', 'Attendance Out', 0, ?, 'geoattnd', 'Submit')");
        $todo->bind_param("ssss", $sccode, $today, $usr, $current_time);
        $todo->execute();
    }
    $inout = 'in';
}

// টোডো লিস্ট স্ট্যাটাস আপডেট (যদি থাকে)
if(isset($todo_id)) {
    $upd_todo = $conn->prepare("UPDATE todolist SET status=1, responsetime=? WHERE id = ?");
    $upd_todo->bind_param("si", $current_time, $todo_id);
    $upd_todo->execute();
}

// ২. ইউআই প্যারামিটার নির্ধারণ
$bgclr = '#B3261E'; // Default Error
$msg = 'Location Mismatch';
$icon = 'shield-lock-fill';

if ($distance == 0) {
    $msg = 'Location not detected';
    $icon = 'geo-alt-fill';
} else if ($distance > 0 && $distance <= $dista_differ) {
    if ($inout == 'in') {
        $bgclr = ($stst == 'Fast') ? '#146C32' : '#E46C0A';
        $msg = ($stst == 'Fast') ? '<b>Checked In</b><br>Good morning! Arrived early.' : '<b>Checked In</b><br>You are a bit late today.';
    } else {
        $bgclr = ($stst == 'Fast') ? '#E46C0A' : '#146C32';
        $msg = ($stst == 'Fast') ? '<b>Checked Out</b><br>Leaving early? Have a safe trip.' : '<b>Checked Out</b><br>Duty completed. Well done!';
    }
    $icon = 'check-circle-fill';
}
?>

<style>
    body { margin: 0; background-color: <?php echo $bgclr; ?>; color: white; height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; font-family: 'Roboto', sans-serif; overflow: hidden; }
    
    .result-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(15px);
        border-radius: 8px; /* গাইডলাইন অনুযায়ী ৮ পিক্সেল */
        padding: 40px 24px;
        margin: 20px;
        text-align: center;
        width: 85%;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .icon-circle {
        width: 90px; height: 90px;
        background: white; color: <?php echo $bgclr; ?>;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 24px; font-size: 48px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .status-msg { font-size: 1.1rem; line-height: 1.5; margin-bottom: 32px; font-weight: 400; letter-spacing: 0.3px; }
    
    .btn-m3 {
        background: white; color: <?php echo $bgclr; ?>;
        border: none; padding: 12px 48px;
        border-radius: 8px; /* গাইডলাইন অনুযায়ী ৮ পিক্সেল */
        font-weight: 800; font-size: 0.9rem;
        letter-spacing: 1px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: 0.2s;
    }
    .btn-m3:active { transform: scale(0.96); opacity: 0.9; }
</style>

<main class="text-center w-100">
    <div class="result-card shadow-lg">
        <div class="icon-circle">
            <i class="bi bi-<?php echo $icon; ?>"></i>
        </div>

        <div class="status-msg">
            <?php echo $msg; ?>
        </div>

        <button class="btn-m3" onclick="finish();">
            DONE
        </button>
    </div>
    
    <div class="mt-4 small opacity-50 fw-bold" style="letter-spacing: 1px;">
        <?php echo date('h:i A'); ?> <i class="bi bi-dot"></i> ID: <?php echo $userid; ?>
    </div>
</main>

<script>
    function finish() {
        // সেশন ইয়ার ছাড়া সামারি পেজে নেভিগেট করা
        window.location.href = 'my-attnd-summery.php';
    }
</script>

<?php include 'footer.php'; ?>