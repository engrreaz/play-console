<?php
$page_title = "Teacher's Attendance";
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে


$current_time = $cur;
$today = $td;
$entry = true;
// ইন এবং আউট টাইম সেটিংস
$reqin = ($in_time_user != '00:00:00') ? $in_time_user : $in_time;
$reqout = ($out_time_user != '00:00:00') ? $out_time_user : $out_time;

$inout = 'in';
$stst = '';



// ২. ইউআই প্যারামিটার নির্ধারণ
$bgclr = '#B3261E'; // Default Error
$msg = 'Location Mismatch';
$icon = 'shield-lock-fill';

if ($distance == 0) {
    $msg = 'Location not detected';
    $icon = 'geo-alt-fill';
    $entry = false;
} else if ($distance > 0 && $distance <= $dista_differ) {



    // echo 'Hexa';
// ১. হাজিরা প্রসেসিং লজিক (Prepared Statements)
    $ddd = "SELECT id FROM teacherattnd WHERE entryuser = '$usr' AND adate = '$today' AND sccode = '$sccode' LIMIT 1";
    // echo $ddd;
    $stmt = $conn->prepare("SELECT id FROM teacherattnd WHERE entryuser = ? AND adate = ? AND sccode = ? LIMIT 1");
    $stmt->bind_param("sss", $usr, $today, $sccode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // echo 'XYZ';
        // --- Attendance Out সেশন ---
        $row = $result->fetch_assoc();
        $db_id = $row["id"];
        $full_reqout = $today . ' ' . $reqout;
        $diff_seconds = strtotime($current_time) - strtotime($full_reqout);

        $stst = ($diff_seconds < 0) ? 'Fast' : 'Late';
        $abs_diff = abs($diff_seconds);
        $balout = sprintf('%02d:%02d:%02d', ($abs_diff / 3600), ($abs_diff / 60 % 60), ($abs_diff % 60));

        $upd = $conn->prepare("UPDATE teacherattnd SET realout=?, balout=?, statusout=?, detectout='GPS', disout=? WHERE id=?");
        $upd->bind_param("sssdi", $current_time, $balout, $stst, $distance, $db_id);
        $upd->execute();
        $inout = 'out';
    } else {
        // --- Attendance In সেশন ---
        // echo 'ABC';
        $full_reqin = $today . ' ' . $reqin;
        $diff_seconds = strtotime($current_time) - strtotime($full_reqin);

        $stst = ($diff_seconds < 0) ? 'Fast' : 'Late';
        $abs_diff = abs($diff_seconds);
        $balin = sprintf('%02d:%02d:%02d', ($abs_diff / 3600), ($abs_diff / 60 % 60), ($abs_diff % 60));

        // ইনসার্ট কুয়েরি থেকে sessionyear সরিয়ে ফেলা হয়েছে

        //    echo $distance;
        $ins = $conn->prepare("INSERT INTO teacherattnd (entryuser, tid, adate, reqin, reqout, realin, balin, statusin, detectin, disin, sccode, entryby, entrytime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'GPS', ?, ?, ?, ?)");
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
    if (isset($todo_id)) {
        $upd_todo = $conn->prepare("UPDATE todolist SET status=1, responsetime=? WHERE id = ?");
        $upd_todo->bind_param("si", $current_time, $todo_id);
        $upd_todo->execute();
    }










    if ($inout == 'in') {
        $bgclr = ($stst == 'Fast') ? '#146C32' : '#E46C0A';
        $msg = ($stst == 'Fast') ? '<b>Checked In</b><br>Good morning! Arrived early.' : '<b>Checked In</b><br>You are a bit late today.';
    } else {
        $bgclr = ($stst == 'Fast') ? '#E46C0A' : '#146C32';
        $msg = ($stst == 'Fast') ? '<b>Checked Out</b><br>Leaving early? Have a safe trip.' : '<b>Checked Out</b><br>Duty completed. Well done!';
    }
    $icon = 'check-circle-fill';
} else {
    $msg = 'You\'r now out of area.';
    $icon = 'geo-alt-fill';
    $entry = false;
}
?>

<style>
    :root {

        --success: #146C32;
        --warning: #E46C0A;
        --error: #B3261E;

    }


    /* BODY */

    body {

        margin: 0;
        padding: 0;

        min-height: 100vh;

        background:
            linear-gradient(180deg,
                <?php echo $bgclr; ?>
                0%,
                color-mix(in srgb,
                    <?php echo $bgclr; ?>
                    82%, black) 100%);

        font-family: 'Roboto', sans-serif;

        /* display:flex; */
        align-items: center;
        justify-content: center;

        overflow: hidden;

        color: #fff;

    }


    /* MAIN WRAPPER */

    .m3-wrapper {

        width: 100%;
        max-width: 420px;
        margin: auto;
        padding: 24px;

    }


    /* TONAL CARD */

    .result-card {

        position: relative;

        background:
            rgba(255, 255, 255, 0.12);

        border:
            1px solid rgba(255, 255, 255, 0.14);

        border-radius: 28px;

        padding: 40px 24px 28px;

        backdrop-filter: blur(18px);

        box-shadow:
            0 10px 30px rgba(0, 0, 0, 0.18);

        overflow: hidden;

    }


    /* TOP GLOW */

    .result-card::before {

        content: '';

        position: absolute;

        top: -120px;
        left: -80px;

        width: 220px;
        height: 220px;

        background:
            rgba(255, 255, 255, 0.08);

        border-radius: 50%;

    }


    /* ICON */

    .icon-wrap {

        width: 88px;
        height: 88px;

        border-radius: 28px;

        background:
            rgba(255, 255, 255, 0.16);

        border:
            1px solid rgba(255, 255, 255, 0.16);

        display: flex;
        align-items: center;
        justify-content: center;

        margin: 0 auto 28px;

        backdrop-filter: blur(10px);

        box-shadow:
            inset 0 1px 1px rgba(255, 255, 255, 0.08),
            0 4px 12px rgba(0, 0, 0, 0.15);

    }


    .icon-wrap i {

        font-size: 42px;
        color: #fff;

    }


    /* TITLE */

    .status-title {

        font-size: 1.45rem;
        font-weight: 800;

        letter-spacing: 0.3px;

        margin-bottom: 10px;

    }


    /* SUBTITLE */

    .status-sub {

        font-size: 0.92rem;

        line-height: 1.7;

        opacity: 0.88;

        font-weight: 400;

        margin-bottom: 34px;

    }


    /* ACTION BUTTON */

    .btn-m3 {

        width: 100%;

        height: 54px;

        border: none;

        border-radius: 18px;

        background:
            rgba(255, 255, 255, 0.18);

        color: #fff;

        font-size: 0.92rem;
        font-weight: 800;

        letter-spacing: 0.6px;

        backdrop-filter: blur(10px);

        transition: 0.18s;

        box-shadow:
            0 2px 10px rgba(0, 0, 0, 0.14);

    }


    .btn-m3:hover {

        background:
            rgba(255, 255, 255, 0.22);

    }


    .btn-m3:active {

        transform: scale(0.97);

    }


    /* FOOT NOTE */

    .meta-info {

        text-align: center;

        margin-top: 22px;

        font-size: 0.72rem;

        font-weight: 700;

        letter-spacing: 1px;

        opacity: 0.72;

    }


    /* SMALL MOBILE */

    @media(max-width:480px) {

        .result-card {

            border-radius: 24px;

            padding: 34px 20px 24px;

        }

    }
</style>

<main class="m3-wrapper">

    <div class="result-card">

        <div class="icon-wrap">
            <i class="bi bi-<?php echo $icon; ?>"></i>
        </div>

        <?php
        if ($entry) {
            if ($inout == 'in') {
                $title = 'Attendance Recorded';
            } else {
                $title = 'Checkout Completed';
            }
        } else {
            $title = 'Location not correct';
        }

        ?>

        <div class="text-center">

            <div class="status-title">
                <?php echo $title; ?>
            </div>

            <div class="status-sub">
                <?php echo strip_tags($msg, '<br><b>'); ?>
            </div>

        </div>

        <button class="btn-m3" onclick="finish()">

            DONE

        </button>

    </div>

    <div class="meta-info">

        <?php echo date('h:i A'); ?>

        <i class="bi bi-dot"></i>

        ID: <?php echo $userid; ?>

    </div>

</main>

<script>
    function finish() {
        // সেশন ইয়ার ছাড়া সামারি পেজে নেভিগেট করা
        window.location.href = 'my-attnd-summery.php';
    }
</script>

<?php include 'footer.php'; ?>