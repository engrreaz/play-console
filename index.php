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
        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm"
            style="width: 38px; background: #6750A4 !important;">
            <img src="iimg/logo.png" width="100%">
        </div>
        <div class="flex-grow-1 text-container" style="min-width: 0;" data-feature="Home Page">
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


// --- Enhanced What's New & Unexplored Logic ---
$new_updates_count = 0;
$unexplored_count = 0;

// ১. ইউজারের লাস্ট দেখা আইডি বের করা (whatsnew.php এর সাথে সামঞ্জস্য রেখে $usr ব্যবহার করা হয়েছে)
$stmt_last_seen = $conn->prepare("SELECT last_seen_id FROM user_timeline_seen WHERE email = ?");
$stmt_last_seen->bind_param("s", $usr);
$stmt_last_seen->execute();
$res_seen = $stmt_last_seen->get_result();
$last_seen_id = ($row_seen = $res_seen->fetch_assoc()) ? $row_seen['last_seen_id'] : 0;
$stmt_last_seen->close();

// ২. একদম নতুন (NEW) আপডেটের সংখ্যা
$stmt_new = $conn->prepare("SELECT COUNT(*) as total FROM dev_timeline WHERE platform = 'Android' AND id > ?");
$stmt_new->bind_param("i", $last_seen_id);
$stmt_new->execute();
$new_updates_count = $stmt_new->get_result()->fetch_assoc()['total'] ?? 0;
$stmt_new->close();

// ৩. দেখা হয়েছে কিন্তু ভিজিট করা হয়নি (UNEXPLORED) আপডেটের সংখ্যা
// লজিক: dev_timeline এর সময় > logbook এর লাস্ট এন্ট্রি সময় (শুধুমাত্র দেখা আইটেমগুলোর জন্য)
$sql_unexplored = "
    SELECT COUNT(*) as total 
    FROM dev_timeline t
    LEFT JOIN (
        SELECT pagename, MAX(entrytime) as last_v 
        FROM logbook 
        WHERE email = ? AND platform='Android' 
        GROUP BY pagename
    ) l ON t.page_name = l.pagename
    WHERE t.platform = 'Android' 
    AND t.id <= ? 
    AND (l.last_v IS NULL OR t.created_at > l.last_v)
";
$stmt_unexp = $conn->prepare($sql_unexplored);
$stmt_unexp->bind_param("si", $usr, $last_seen_id);
$stmt_unexp->execute();
$unexplored_count = $stmt_unexp->get_result()->fetch_assoc()['total'] ?? 0;
$stmt_unexp->close();









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




<!-- CASHBOOK QUICK ENTRY MODAL -->
<div class="modal fade" id="cashEntryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-cash-stack me-2"></i>
                    Add Cash Entry
                </h5>

                <button type="button" class="btn btn-light" onclick="cashModal.hide()">
                    <i class="bi bi-x-lg text-danger"></i>
                </button>
            </div>

            <div class="modal-body">

                <form id="cashEntryForm">

                    <input type="hidden" name="head_code" id="head_code">

                    <div class="row g-2">

                        <div class="col-6 mb-3">
                            <label class="small fw-bold">Date</label>

                            <input type="date" name="date" id="cb_date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="col-6 mb-3">

                            <label class="small fw-bold">Type</label>

                            <select name="type" id="cb_type" class="form-control" required>

                                <option value="Income">Income</option>
                                <option value="Expenditure" selected>Expenditure</option>

                            </select>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="small fw-bold">Account Sector</label>

                        <select name="partid" id="cb_partid" class="form-control" required>

                            <option value="">Select Sector</option>

                            <?php
                            $shq = mysqli_query($conn, "
                                SELECT s.id,
                                       s.sub_head,
                                       h.account_head,
                                       s.account_head_id
                                FROM account_sub_head s
                                LEFT JOIN account_head h
                                ON s.account_head_id=h.id
                                WHERE s.sccode='$sccode'
                                ORDER BY h.account_head,s.sub_head
                            ");

                            $current_head = '';

                            while ($sh = mysqli_fetch_assoc($shq)):

                                if ($current_head != $sh['account_head']) {

                                    if ($current_head != '')
                                        echo '</optgroup>';

                                    echo '<optgroup label="' . $sh['account_head'] . '">';

                                    $current_head = $sh['account_head'];
                                }
                                ?>

                                <option value="<?php echo $sh['id']; ?>" data-head="<?php echo $sh['account_head_id']; ?>">

                                    <?php echo $sh['sub_head']; ?>

                                </option>

                            <?php endwhile;

                            if ($current_head != '')
                                echo '</optgroup>';
                            ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="small fw-bold">Description</label>

                        <input type="text" name="particulars" id="cb_particulars" class="form-control" required>

                    </div>

                    <div class="row g-2">

                        <div class="col-6 mb-3">

                            <label class="small fw-bold">Amount</label>

                            <input type="number" name="amount" id="cb_amount" class="form-control" required>

                        </div>

                        <div class="col-6 mb-3">

                            <label class="small fw-bold">Memo</label>

                            <input type="text" name="memono" id="cb_memono" class="form-control">

                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-light" onclick="cashModal.hide()">
                    Cancel
                </button>

                <button type="submit" form="cashEntryForm" class="btn btn-primary">

                    <i class="bi bi-check2-circle me-1"></i>
                    Save Entry

                </button>

            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>





<script>

    let cashModal;


    /* PAGE READY */
    $(document).ready(function () {

        cashModal = new bootstrap.Modal(
            document.getElementById('cashEntryModal'),
            {
                backdrop: true,
                keyboard: true
            }
        );

    });


    /* OPEN MODAL */
    function openCashModal() {

        $('#cashEntryForm')[0].reset();

        $('#cb_date').val(
            new Date().toISOString().split('T')[0]
        );

        let date = getCookie("last_date");
        let memo = parseInt(("memono")) + 1;

        if (date) {
            $('#cb_date').val(date);
        }
        if (memo) {
            $('#cb_memono').val(memo);
        }



        $('#cb_type').val('Expenditure');

        cashModal.show();
    }


    /* ACCOUNT HEAD AUTO SET */
    $(document).on('change', '#cb_partid', function () {

        let headCode = $(this)
            .find(':selected')
            .data('head');

        $('#head_code').val(headCode);

    });


    /* AJAX SUBMIT */
    $(document).on('submit', '#cashEntryForm', function (e) {

        e.preventDefault();

        let fd = new FormData(this);
        let date = document.getElementById('cb_date').value;
        let memo = document.getElementById('cb_memono').value;




        $.ajax({

            url: 'front-page-block/cashbook_ajax_save.php',

            type: 'POST',

            data: fd,

            processData: false,

            contentType: false,

            cache: false,

            beforeSend: function () {

                cashModal.hide();
                Swal.fire({
                    title: 'Saving...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

            },

            success: function (res) {

                console.log(res);

                Swal.close();

                res = res.trim();

                if (res === 'success') {
                    setCookie("last_date", date);
                    setCookie("memono", memo);

                    cashModal.hide();

                    Swal.fire({
                        icon: 'success',
                        title: 'Entry Saved',
                        timer: 1400,
                        showConfirmButton: false
                    });

                    // setTimeout(function () {
                    //     location.reload();
                    // }, 1200);

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        html: res
                    });

                }

            },

            error: function (xhr) {

                console.log(xhr.responseText);

                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Ajax request failed'
                });

            }

        });

    });

</script>