<?php
include 'inc.php';

$id = $userid;
$current_time = $cur; // inc.php থেকে প্রাপ্ত
$today = $td;         // inc.php থেকে প্রাপ্ত

// ইন এবং আউট টাইম সেট করা
$reqin = ($in_time_user != '00:00:00') ? $in_time_user : $in_time;
$reqout = ($out_time_user != '00:00:00') ? $out_time_user : $out_time;

$inout = 'in';
$status_label = '';

// ১. চেক করা হচ্ছে আজকের হাজিরা আগে দেওয়া হয়েছে কি না
$stmt = $conn->prepare("SELECT id FROM teacherattnd WHERE user = ? AND adate = ? AND sccode = ? LIMIT 1");
$stmt->bind_param("sss", $usr, $today, $sccode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // এটি "Attendance Out" সেশন
    $row = $result->fetch_assoc();
    $id2 = $row["id"];
    $full_reqout = $today . ' ' . $reqout;
    $diff_seconds = strtotime($current_time) - strtotime($full_reqout);
    
    $stst = ($diff_seconds < 0) ? 'Fast' : 'Late';
    $abs_diff = abs($diff_seconds);
    $balout = sprintf('%02d:%02d:%02d', ($abs_diff/3600), ($abs_diff/60%60), ($abs_diff%60));

    // আপডেট কুয়েরি (Prepared Statement)
    $upd = $conn->prepare("UPDATE teacherattnd SET realout=?, balout=?, statusout=?, detectout='GPS', disout=? WHERE id=?");
    $upd->bind_param("sssdi", $current_time, $balout, $stst, $distance, $id2);
    $upd->execute();
    
    $inout = 'out';
} else {
    // এটি নতুন "Attendance In" সেশন
    $full_reqin = $today . ' ' . $reqin;
    $diff_seconds = strtotime($current_time) - strtotime($full_reqin);
    
    $stst = ($diff_seconds < 0) ? 'Fast' : 'Late';
    $abs_diff = abs($diff_seconds);
    $balin = sprintf('%02d:%02d:%02d', ($abs_diff/3600), ($abs_diff/60%60), ($abs_diff%60));

    // ইনসার্ট কুয়েরি (Prepared Statement)
    $ins = $conn->prepare("INSERT INTO teacherattnd (user, tid, adate, reqin, reqout, realin, balin, statusin, detectin, disin, sccode, entryby, entrytime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'GPS', ?, ?, ?, ?)");
    $ins->bind_param("ssssssssdsss", $usr, $userid, $today, $full_reqin, $reqout, $current_time, $balin, $stst, $distance, $sccode, $usr, $current_time);
    $ins->execute();

    // টোডো লিস্টে আউট এন্ট্রি যোগ করা (যদি প্রয়োজন হয়)
    if ($tattndout == 1) {
        $todo = $conn->prepare("INSERT INTO todolist (sccode, date, user, todotype, descrip1, status, creationtime, response, responsetxt) VALUES (?, ?, ?, 'Attendance', 'Attendance Out', 0, ?, 'geoattnd', 'Submit')");
        $todo->bind_param("ssss", $sccode, $today, $usr, $current_time);
        $todo->execute();
    }
    $inout = 'in';
}

// টোডো লিস্ট স্ট্যাটাস আপডেট
$upd_todo = $conn->prepare("UPDATE todolist SET status=1, responsetime=? WHERE id = ?");
$upd_todo->bind_param("si", $current_time, $id);
$upd_todo->execute();

// ২. ইউআই প্যারামিটার নির্ধারণ
$bgclr = '#B3261E'; // M3 Error Color (Crimson)
$msg = 'Area mismatch';
$icon = 'shield-fill-x';

if ($distance == 0) {
    $msg = 'Location not detected';
    $icon = 'shield-slash-fill';
} else if ($distance > 0 && $distance <= $dista_differ) {
    if ($inout == 'in') {
        if ($stst == 'Fast') {
            $bgclr = '#146C32'; // M3 Success Green
            $msg = '<b>Checked In</b><br>Good morning! Arrived early.';
        } else {
            $bgclr = '#E46C0A'; // M3 Warning Orange
            $msg = '<b>Checked In</b><br>You are a bit late today.';
        }
    } else {
        if ($stst == 'Fast') {
            $bgclr = '#E46C0A';
            $msg = '<b>Checked Out</b><br>Leaving early? Have a safe trip.';
        } else {
            $bgclr = '#146C32';
            $msg = '<b>Checked Out</b><br>Duty completed. Well done!';
        }
    }
    $icon = 'shield-fill-check';
} else {
    $msg = 'You are out of the designated area';
}
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
    }
    body { margin: 0; font-family: 'Roboto', sans-serif; overflow: hidden; }
    .m3-container {
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        transition: background 0.5s ease;
        text-align: center;
        padding: 20px;
    }
    .m3-icon-wrapper {
        background: rgba(255, 255, 255, 0.2);
        padding: 30px;
        border-radius: 32px; /* M3 extra rounded */
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .status-msg { font-size: 1.5rem; line-height: 1.4; margin-bottom: 40px; }
    .m3-btn {
        background: #1C1B1F; /* On Surface */
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 100px; /* Pill button */
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        cursor: pointer;
    }
    .gps-tag {
        position: absolute;
        top: 20px;
        font-size: 0.8rem;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 5px;
    }
</style>

<main>
    <div class="m3-container" style="background: <?php echo $bgclr; ?>;">
        <div class="gps-tag">
            <i class="bi bi-geo-alt-fill"></i>
            GPS VERIFIED ATTENDANCE
        </div>

        <div class="m3-icon-wrapper">
            <i class="bi bi-<?php echo $icon; ?>" style="font-size: 80px;"></i>
        </div>

        <div class="status-msg">
            <?php echo $msg; ?>
        </div>

        <button class="m3-btn" onclick="backs();">
            DONE
        </button>
    </div>
</main>

<script>
    function backs() {
        // অ্যান্ড্রয়েড নেটিভ অ্যাপ হলে ব্যাক করবে, নয়তো সামারি পেজে যাবে
        if(window.history.length > 1) {
            window.location.href = 'my-attnd-summery.php';
        } else {
            window.location.href = 'index.php';
        }
    }
</script>