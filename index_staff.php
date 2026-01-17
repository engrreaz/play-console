<?php
// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;

// ২. টু-ডু লিস্ট ইনসার্ট লজিক (Condensed & Secure)
$stmt_todo = $conn->prepare("SELECT id FROM todolist WHERE date=? AND sccode=? AND user=? AND todotype='attendance'");
$stmt_todo->bind_param("sss", $td, $sccode, $usr);
$stmt_todo->execute();
$res_todo = $stmt_todo->get_result();

if ($res_todo->num_rows == 0) {
    $ins_query = "INSERT INTO todolist (sccode, date, user, todotype, status, creationtime, response, responsetxt) 
                  VALUES (?, ?, ?, 'Attendance', 0, ?, 'geoattnd', 'Submit')";
    $ins_stmt = $conn->prepare($ins_query);
    $ins_stmt->bind_param("ssss", $sccode, $td, $usr, $cur);
    $ins_stmt->execute();
}
$stmt_todo->close();

// ৩. কালেকশন সামারি ফেচিং
$paisi = 0;
$stmt_coll = $conn->prepare("SELECT SUM(amount) as total FROM stpr WHERE sessionyear LIKE ? AND sccode = ? AND entryby = ?");
$sy_param = "%$current_session%";
$stmt_coll->bind_param("sss", $sy_param, $sccode, $usr);
$stmt_coll->execute();
$res_coll = $stmt_coll->get_result();
if ($row_c = $res_coll->fetch_assoc()) {
    $paisi = $row_c["total"] ?? 0;
}
$stmt_coll->close();

$randval = random_int(1000, 9999);
?>

<style>
    /* ড্যাশবোর্ড স্পেসিফিক স্লিম স্টাইল */
    .dashboard-body { padding: 8px 12px; }
    
    /* M3 কার্ড র্যাপার (8px radius) */
    .m3-block-wrapper {
        margin-bottom: 12px;
        border-radius: 8px !important;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    /* কালেকশন চিপ (Condensed) */
    .hero-summary-chip {
        background: #EADDFF; color: #21005D;
        padding: 12px 16px; border-radius: 8px;
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; border: 1px solid #D0BCFF;
    }
    .hero-val { font-size: 1.1rem; font-weight: 800; }
    .hero-lbl { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; }

    /* ক্যাটাগরি লেবেল */
    .m3-section-lbl {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 16px 0 8px 4px; letter-spacing: 1px;
    }

    /* কুইক অ্যাকশন বাটন */
    .btn-m3-outline {
        border: 1px solid #79747E; border-radius: 8px; color: #49454F;
        padding: 6px 12px; font-size: 0.75rem; font-weight: 700;
        background: transparent; transition: 0.2s;
    }
    .btn-m3-outline:active { background: #F3EDF7; transform: scale(0.98); }
</style>

<div class="dashboard-body pb-5">
    
    <div class="hero-summary-chip shadow-sm">
        <div>
            <span class="hero-lbl d-block">Collection (<?php echo $current_session; ?>)</span>
            <span class="hero-val">৳ <?php echo number_format($paisi, 0); ?></span>
        </div>
        <i class="bi bi-wallet2 fs-3 opacity-50"></i>
    </div>

    <div class="m3-block-wrapper"><?php include 'front-page-block/schedule.php'; ?></div>
    <div class="m3-block-wrapper"><?php include 'front-page-block/holi-ramadan.php'; ?></div>

    <div class="m3-section-lbl">Daily Assignments</div>
    <div class="m3-block-wrapper"><?php include 'front-page-block/task-teacher.php'; ?></div>
    
    <?php if($notice_block == 1): ?>
        <div class="m3-block-wrapper border-start border-4 border-warning">
            <?php include 'front-page-block/notice.php'; ?>
        </div>
    <?php endif; ?>

    <div class="m3-section-lbl">Quick Console</div>
    <div class="row g-2 mb-4">
        <div class="col-6">
            <button class="btn-m3-outline w-100 shadow-sm" onclick="navigateTo('admin-sclist.php')">
                <i class="bi bi-buildings me-1"></i> INSTITUTES
            </button>
        </div>
        <div class="col-6">
            <button class="btn-m3-outline w-100 shadow-sm" onclick="navigateTo('receipt.php?cls=Nine&sec=Science&roll=25')">
                <i class="bi bi-printer me-1"></i> EPOS
            </button>
        </div>
        <div class="col-6">
            <button class="btn-m3-outline w-100 shadow-sm" onclick="navigateTo('kbase.php')">
                <i class="bi bi-lightbulb me-1"></i> K-BASE
            </button>
        </div>
        <div class="col-6">
            <button class="btn-m3-outline w-100 shadow-sm" onclick="location.reload();">
                <i class="bi bi-arrow-clockwise me-1"></i> RELOAD
            </button>
        </div>
    </div>

</div>

<div style="height: 60px;"></div> <script>
    // ডাইনামিক নেভিগেশন (Session pass নিশ্চিত করা হয়েছে)
    function navigateTo(url) {
        window.location.href = url + (url.includes('?') ? '&' : '?') + 'year=<?php echo $current_session; ?>';
    }

    function goclsp() { navigateTo('finclssec.php'); }
    function goclsa() { navigateTo('finacc.php'); }
    function sublist() { navigateTo('tools_allsubjects.php'); }
    function mypr() { navigateTo('mypr.php'); }
    function register(c, s) { window.location.href = `stattndregister.php?cls=${c}&sec=${s}&year=<?php echo $current_session; ?>`; }
    function goclsattall() { navigateTo('attndclssec.php'); }

    function gor() { 
        Swal.fire({
            title: 'Initialize Result?',
            text: "Starting result processing wizard.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6750A4',
            confirmButtonText: 'Proceed'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = 'resultprocess.php';
        });
    }
</script>