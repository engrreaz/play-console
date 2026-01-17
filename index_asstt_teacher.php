<?php
// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;

// ২. টু-ডু লিস্ট ইনসার্ট লজিক (Condensed)
$stmt_todo = $conn->prepare("SELECT id FROM todolist WHERE date=? AND sccode=? AND user=? AND todotype='attendance'");
$stmt_todo->bind_param("sss", $td, $sccode, $usr);
$stmt_todo->execute();
if ($stmt_todo->get_result()->num_rows == 0) {
    $ins_todo = "INSERT INTO todolist (sccode, date, user, todotype, status, creationtime, response, responsetxt) 
                 VALUES ('$sccode', '$td', '$usr', 'Attendance', 0, '$cur', 'geoattnd', 'Submit')";
    $conn->query($ins_todo);
}
$stmt_todo->close();

// ৩. টোটাল কালেকশন ফেচিং (Prepared Statement)
$paisi = 0;
$stmt_pr = $conn->prepare("SELECT SUM(amount) as total FROM stpr WHERE sessionyear LIKE ? AND sccode = ? AND entryby = ?");
$sy_like = "%$current_session%";
$stmt_pr->bind_param("sss", $sy_like, $sccode, $usr);
$stmt_pr->execute();
$res_pr = $stmt_pr->get_result();
if ($row = $res_pr->fetch_assoc()) {
    $paisi = $row["total"] ?? 0;
}
$stmt_pr->close();
?>

<style>
    /* ড্যাশবোর্ড স্পেসিফিক স্লিম স্টাইল */
    .dashboard-wrapper { padding: 8px 12px; }
    
    /* গ্লোবাল ৮ পিক্সেল রেডিয়াস এবং বর্ডার */
    .m-card, .card, .alert, .btn, .list-group-item { 
        border-radius: 8px !important; 
        border: 1px solid #f0f0f0 !important; 
    }

    /* হিরো কালেকশন চিপ */
    .collection-chip {
        background: #E8F5E9; color: #2E7D32;
        padding: 10px 16px; border-radius: 8px;
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; border: 1px solid #C8E6C9;
    }
    .coll-val { font-size: 1.1rem; font-weight: 800; }
    .coll-lbl { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; }

    /* ক্যাটাগরি লেবেল */
    .m3-label {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 12px 0 8px 4px; letter-spacing: 0.8px;
    }

    /* ব্লক র্যাপার */
    .block-unit { margin-bottom: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
</style>

<div class="dashboard-wrapper pb-5">
    
    <div class="collection-chip shadow-sm">
        <div>
            <span class="coll-lbl d-block">My Collection (<?php echo $current_session; ?>)</span>
            <span class="coll-val">৳ <?php echo number_format($paisi, 0); ?></span>
        </div>
        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
            <i class="bi bi-wallet2 text-success"></i>
        </div>
    </div>

    <div class="row g-2">
        <div class="col-12">
            <div class="block-unit"><?php include 'front-page-block/schedule.php'; ?></div>
        </div>
        <div class="col-12">
            <div class="block-unit"><?php include 'front-page-block/holi-ramadan.php'; ?></div>
        </div>
    </div>

    <div class="m3-label">Daily Operations</div>
    <div class="block-unit"><?php include 'front-page-block/task-teacher.php'; ?></div>
    
    <?php if($notice_block == 1): ?>
        <div class="block-unit border-start border-4 border-warning bg-white shadow-sm" style="border-radius: 8px !important;">
            <?php include 'front-page-block/notice.php'; ?>
        </div>
    <?php endif; ?>

    <div class="m3-label">Class Management</div>
    <div class="row g-2">
        <div class="col-12"><?php include 'front-page-block/cls-teacher-attendance.php'; ?></div>
        <div class="col-12"><?php include 'front-page-block/clsteacherblock.php'; ?></div>
    </div>

    <div class="mt-3">
        <button class="btn btn-light w-100 btn-sm text-muted fw-bold py-2 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#quickLinks">
            <i class="bi bi- lightning-charge me-1"></i> QUICK ACTION CONSOLE
        </button>
        <div class="collapse mt-2" id="quickLinks">
            <div class="card card-body p-2 bg-white">
                <div class="d-grid gap-2">
                    <div class="row g-2">
                        <div class="col-6"><a class="btn btn-outline-primary btn-sm w-100" href="admin-sclist.php">Institute List</a></div>
                        <div class="col-6"><a class="btn btn-outline-info btn-sm w-100" href="receipt.php?cls=Nine&sec=Science&roll=25">EPOS</a></div>
                        <div class="col-6"><a class="btn btn-outline-secondary btn-sm w-100" href="kbase.php">Knowledge</a></div>
                        <div class="col-6"><a class="btn btn-outline-dark btn-sm w-100" href="?time=<?php echo random_int(1000, 9999); ?>">Reload</a></div>
                        <div class="col-12"><a class="btn btn-danger btn-sm w-100 fw-bold" href="sout.php">LOGOUT</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div style="height: 60px;"></div> <script>
    // ডাইনামিক নেভিগেশন (Session pass নিশ্চিত করা হয়েছে)
    function goclsp() { window.location.href = 'finclssec.php?year=<?php echo $current_session; ?>'; }
    function goclsa() { window.location.href = 'finacc.php?year=<?php echo $current_session; ?>'; }
    function sublist() { window.location.href = 'tools_allsubjects.php?year=<?php echo $current_session; ?>'; }
    function mypr() { window.location.href = 'mypr.php?year=<?php echo $current_session; ?>'; }
    function register(c, s) { window.location.href = `st-attnd-register.php?cls=${c}&sec=${s}&year=<?php echo $current_session; ?>`; }
    function goclsattall() { window.location.href = 'attndclssec.php?year=<?php echo $current_session; ?>'; }
    
    function gor() { 
        Swal.fire({
            title: 'Start Process?',
            text: "Initializing result processing wizard.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#6750A4',
            confirmButtonText: 'Continue'
        }).then((r) => { if(r.isConfirmed) window.location.href = 'resultprocess.php'; });
    }
</script>