<?php
// Initialization: Sets up session, DB connection, and global vars
include 'inc.php';
$app = $_GET['app'] ?? 5;
$sy_param = '%' . $sessionyear . '%';

$page_title = "Dashboard";

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];



$hour = date('H');
$greeting = ($hour < 12) ? "Good Morning" : (($hour < 17) ? "Good Afternoon" : "Good Evening");
?>

<style>
    .scname {
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        display: block;
        max-width: calc(100%-75px);
    }

    .m3-app-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100vw;
        /* ভিউপোর্ট উইডথ অনুযায়ী সেট করা */
        max-width: 100%;
        /* প্যারেন্ট এর বাইরে যাবে না */
        padding: 8px 16px;
        /* Material 3 স্ট্যান্ডার্ড প্যাডিং */
        background: #fff;
        position: sticky;
        top: 0;
        z-index: 1050;
        box-sizing: border-box;
        /* প্যাডিং যেন উইডথ বাড়িয়ে না দেয় */
        margin: 0;
        /* কোনো ডিফল্ট মার্জিন থাকলে তা মুছে দেবে */
    }

    /* ড্রপডাউন মেনু যেন স্ক্রিনের বাইরে না যায় */
    /* পুরো বডির জন্য সেফগার্ড */
    body {
        overflow-x: hidden;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    /* আভাটার কন্টেইনার যেন কখনো সংকুচিত না হয় বা ধাক্কা না খায় */
    .avatar-section {
        flex-shrink: 0;
        display: flex;
        align-items: center;
    }

    /* টেক্সট কন্টেইনারকে ছোট হতে অনুমতি দিন */


    .m3-app-bar>.d-flex {
        min-width: 0;
        flex-wrap: nowrap;
    }

    .text-container {
        flex: 1 1 auto;
        min-width: 0;
    }


    /* header */



    /* items */
</style>






<header class="m3-app-bar">
    <div class="d-flex align-items-center flex-grow-1">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm"
            style="width: 38px; height: 38px; background: #6750A4 !important;">
            <img src="iimg/logo.png" width="100%">
        </div>
        <div class="flex-grow-1 text-container" style="min-width: 0;">
            <div class="app-bar-title my-0">EIM<span style="color: #6750A4;">Box</span></div>
            <div class="text-small my-0 scname"><?= $scname ?></div>
        </div>

        <div class="d-flex align-items-center gap-2 position-relative avatar-section">
            <div class="rounded-circle overflow-hidden border top-avatar shadow-sm <?= $ringClass ?>"
                style="width:34px;height:34px;cursor:pointer;z-index:25999;" onclick="toggleAvatarMenu()">

                <img src="<?= $pth ?>" style="width:100%;border-radius:50%;">

            </div>

            <?php include_once 'core/avatar_menu.php'; ?>





        </div>

    </div>

</header>



<?php

// --- Data Fetching & Business Logic (Now Secured) ---
// TODO: This logic should be moved into functions for better organization.

$rest_career_days = 0;
$y = 0;
$n = 0;
$ccnntt = 0;
$ssx = 0;
$notices = [];
$cteacher_text = '';

if ($userlevel == 'Student') {
    $stid = $userid;

    // Get student name
    $stmt_st = $conn->prepare("SELECT stnameeng, stnameben FROM students WHERE stid = ? LIMIT 1");
    $stmt_st->bind_param("s", $stid);
    $stmt_st->execute();
    $result_st = $stmt_st->get_result();
    if ($result_st->num_rows > 0) {
        $student_data = $result_st->fetch_assoc();
        $stnameeng = $student_data["stnameeng"];
        $stnameben = $student_data["stnameben"];
    }

    // Get session info
    $stmt_sess = $conn->prepare("SELECT classname, sectionname, rollno FROM sessioninfo WHERE stid = ? AND sessionyear = ? LIMIT 1");
    $stmt_sess->bind_param("ss", $stid, $sy);
    $stmt_sess->execute();
    $result_sess = $stmt_sess->get_result();
    if ($result_sess->num_rows > 0) {
        $session_data = $result_sess->fetch_assoc();
        $cls = $session_data["classname"];
        $sec = $session_data["sectionname"];
        $rollno = $session_data["rollno"];
    }

    // Get tracking info for class 'Ten'
    if (($cls ?? '') == 'Ten') {
        $stmt_track = $conn->prepare("SELECT responsetime FROM sttracking WHERE stid = ? AND date = ?");
        $stmt_track->bind_param("ss", $stid, $td);
        $stmt_track->execute();
        $result_track = $stmt_track->get_result();
        if ($result_track->num_rows > 0) {
            while ($row_track = $result_track->fetch_assoc()) {
                if ($row_track["responsetime"] == NULL) {
                    $n++;
                } else {
                    $y++;
                }
            }
        }
    }
}

// Get To-Do list count
$stmt_todo = $conn->prepare("SELECT count(*) as koy FROM todolist WHERE date = ? AND sccode = ? AND user = ? AND status = 0");
$stmt_todo->bind_param("sss", $td, $sccode, $usr);
$stmt_todo->execute();
$result_todo = $stmt_todo->get_result();
$todo_data = $result_todo->fetch_assoc();
$n += $todo_data['koy'] ?? 0;

$perc = ($y + $n > 0) ? ceil($y * 100 / ($y + $n)) : 0;

// Get unread notification count
$stmt_noti = $conn->prepare("SELECT count(*) as ccnntt FROM notification WHERE tomail = ? AND rwstatus = 0");
$stmt_noti->bind_param("s", $usr);
$stmt_noti->execute();
$result_noti = $stmt_noti->get_result();
$noti_data = $result_noti->fetch_assoc();
$ccnntt = $noti_data['ccnntt'] ?? 0;

// Get open issues count
$stmt_issue = $conn->prepare("SELECT count(*) as sex FROM issue WHERE progress < 100");
$stmt_issue->execute();
$result_issue = $stmt_issue->get_result();
$issue_data = $result_issue->fetch_assoc();
$ssx = $issue_data['sex'] ?? 0;

// --- Notice Settings ---
$notice_marque = 0;
$notice_block = 0;
$find_app_notice = array_search('App Notice', array_column($ins_all_settings, 'setting_title'));
if ($find_app_notice !== false) {
    $settings_value = $ins_all_settings[$find_app_notice]['settings_value'];
    if (strpos($settings_value, 'Marque') !== false)
        $notice_marque = 1;
    if (strpos($settings_value, 'Block') !== false)
        $notice_block = 1;
}

// Get active notices
$stmt_notice = $conn->prepare("SELECT * FROM notice 
    WHERE sccode = ? 
    AND (expdate IS NULL OR expdate <= '1970-01-01' OR expdate >= ?) 
    ORDER BY entrytime DESC");
$stmt_notice->bind_param("ss", $sccode, $td);
$stmt_notice->execute();
$result_notice = $stmt_notice->get_result();
$notices = $result_notice->fetch_all(MYSQLI_ASSOC);

// --- Class Teacher Info ---
if (!empty($cteacher_data) && $cteacher_data[0]['cteachercls'] != '' && $cteacher_data[0]['cteachersec'] != '') {
    if ($userlevel != 'Staff') {
        $cteacher_text = htmlspecialchars($cteacher_data[0]['cteachercls'] . ' : ' . $cteacher_data[0]['cteachersec']);
    }
}

$unread_q = $conn->query("SELECT COUNT(id) as total FROM notifications WHERE user_id = '$user_id_no' AND is_read = 0");
$unread_count = $unread_q->fetch_assoc()['total'];

// --- Content Routing ---
// The included files (e.g., index_teacher.php) will need to be refactored next
// to fit the new design and remove their old, redundant HTML.
if ($userlevel == 'Guest') {
    include 'index_guest.php';
} else if ($userlevel == 'Student') {
    include 'index_student.php';
} else if ($userlevel == 'Asstt. Head Teacher' || $userlevel == 'Head Teacher' || $userlevel == 'Administrator') {
    if ($notice_marque == 1)
        include 'front-page-block/marque.php';
    include 'index_teacher.php';
} else if ($userlevel == 'Visitor') {
    include 'index_visitor.php';
} else if ($userlevel == 'Guardian') {
    include 'index_guardian.php';
} else if ($userlevel == 'Teacher' || $userlevel == 'Asstt. Teacher' || $userlevel == 'Class Teacher') {
    if ($notice_marque == 1)
        include 'front-page-block/marque.php';
    include 'index_asstt_teacher.php';
} else if ($userlevel == 'Staff') {
    include 'index_staff.php';
} else {
    include 'index_undef.php';
}

?>

<?php include 'footer.php'; ?>