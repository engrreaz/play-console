<?php
/**
 * Dashboard Body - Refactored for Android WebView (EIMBox)
 * M3 Standards | 8px Radius | High Data Density
 */

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;

// ২. টু-ডু লিস্ট লজিক (নিভৃত রাখা হলো - আপনার লজিক ঠিক আছে)
$stmt_todo = $conn->prepare("SELECT id FROM todolist WHERE date=? AND sccode=? AND user=? AND todotype='attendance'");
$stmt_todo->bind_param("sss", $td, $sccode, $usr);
$stmt_todo->execute();
if ($stmt_todo->get_result()->num_rows == 0) {
    $ins_todo = "INSERT INTO todolist (sccode, date, user, todotype, status, creationtime, response, responsetxt) 
                 VALUES ('$sccode', '$td', '$usr', 'Attendance', 0, '$cur', 'geoattnd', 'Submit')";
    $conn->query($ins_todo);
}
$stmt_todo->close();

// ৩. কালেকশন সামারি ফেচিং
$paisi = 0;
$stmt_pr = $conn->prepare("SELECT SUM(amount) as total FROM stpr WHERE sessionyear LIKE ? AND sccode = ? AND entryby = ?");
$sy_like = "%$current_session%";
$stmt_pr->bind_param("sss", $sy_like, $sccode, $usr);
$stmt_pr->execute();
$res_pr = $stmt_pr->get_result();
if ($row = $res_pr->fetch_assoc()) { $paisi = $row["total"] ?? 0; }
$stmt_pr->close();

// ডাইনামিক গ্রিটিং
$hr = date('H');
$greet = ($hr < 12) ? "Good Morning" : (($hr < 17) ? "Good Afternoon" : "Good Evening");
?>

<style>
    /* ড্যাশবোর্ড র‍্যাপার */
    .m3-dashboard { padding: 12px; }

    /* গ্লোবাল ৮ পিক্সেল রেডিয়াস */
    .card, .m-card, .btn, .block-unit, .collapse-content { 
        border-radius: 8px !important; border: 1px solid #f0f0f0 !important; 
    }

    /* কালেকশন চিপ (M3 Success Tonal) */
    .m3-collection-hero {
        background: #E8F5E9; border: 1px solid #C8E6C9; padding: 16px;
        margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;
    }
    .coll-label { font-size: 0.65rem; font-weight: 800; color: #2E7D32; text-transform: uppercase; }
    .coll-amount { font-size: 1.25rem; font-weight: 900; color: #1B5E20; display: block; }

    /* ক্যাটাগরি লেবেল (Condensed) */
    .m3-lbl {
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 16px 0 8px 4px; letter-spacing: 1px;
    }

    /* গ্রিড এবং স্পেসিং */
    .block-item { margin-bottom: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); overflow: hidden; }

    /* কুইক অ্যাকশন বাটন */
    .btn-m3-tonal {
        background: #F3EDF7; color: #6750A4; font-size: 0.75rem; 
        font-weight: 700; padding: 8px; border: none !important;
    }
    .btn-m3-tonal:active { background: #EADDFF; transform: scale(0.98); }
</style>

    <div class="hero-container">
        <div class="small fw-bold opacity-75 text-uppercase mb-1" style="letter-spacing: 1px;">
            <?php echo $greeting; ?>, Sir
        </div>
        <div class="h3 fw-bold mb-0"><?php echo date('l'); ?></div>
        <div class="small opacity-90"><?php echo date('d M, Y'); ?></div>
        
        <div class="mt-3 d-flex gap-2">
            <span class="badge bg-white text-primary rounded-pill px-3 py-2 border-0 shadow-sm" style="font-size: 0.6rem;">
                <i class="bi bi-shield-check me-1"></i> System Active
            </span>
        </div>
    </div>


<div class="m3-dashboard pb-5">
    
    <div class="m3-collection-hero shadow-sm">
        <div>
            <span class="coll-label">Session Collection (<?php echo $current_session; ?>)</span>
            <span class="coll-amount">৳ <?php echo number_format($paisi, 0); ?></span>
        </div>
        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
            <i class="bi bi-wallet2 text-success fs-5"></i>
        </div>
    </div>

    <div class="row g-2">
        <div class="col-12"><div class="block-item"><?php include 'front-page-block/schedule.php'; ?></div></div>
        <div class="col-12"><div class="block-item"><?php include 'front-page-block/holi-ramadan.php'; ?></div></div>
    </div>

    <div class="m3-lbl">Daily Operations</div>
    <div class="block-item"><?php include 'front-page-block/task-teacher.php'; ?></div>
    
    <?php if($notice_block == 1): ?>
        <div class="block-item border-start border-4 border-warning bg-white shadow-sm p-1">
            <?php include 'front-page-block/notice.php'; ?>
        </div>
    <?php endif; ?>

    <div class="m3-lbl">Class Tracking</div>
    <div class="row g-2">
        <div class="col-12"><div class="block-item"><?php include 'front-page-block/cls-teacher-attendance.php'; ?></div></div>
        <div class="col-12"><div class="block-item"><?php include 'front-page-block/clsteacherblock.php'; ?></div></div>
    </div>

    <div class="mt-4">
        <button class="btn btn-dark w-100 btn-sm fw-bold py-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#consoleBox">
            <i class="bi bi-cpu-fill me-1"></i> QUICK CONSOLE
        </button>
        
        <div class="collapse mt-2" id="consoleBox">
            <div class="card card-body p-2 bg-white shadow-sm">
                <div class="row g-2">
                    <div class="col-6"><a class="btn btn-m3-tonal w-100" href="admin-sclist.php">Institutes</a></div>
                    <div class="col-6"><a class="btn btn-m3-tonal w-100" href="receipt.php?cls=Nine&sec=Science&roll=25">EPOS</a></div>
                    <div class="col-6"><a class="btn btn-m3-tonal w-100" href="kbase.php">Knowledge</a></div>
                    <div class="col-6"><a class="btn btn-m3-tonal w-100" href="?time=<?php echo random_int(100, 999); ?>">Reload</a></div>
                    <div class="col-12 mt-2"><a class="btn btn-danger btn-sm w-100 fw-bold py-2" href="sout.php">LOGOUT SESSION</a></div>
                </div>
            </div>
        </div>
    </div>

</div>

<div style="height: 65px;"></div> <script>
    /**
     * Session Persistence Navigation
     */
    function nav(url) {
        const session = '<?php echo $current_session; ?>';
        window.location.href = url + (url.includes('?') ? '&' : '?') + 'year=' + session;
    }

    function goclsp() { nav('finclssec.php'); }
    function goclsa() { nav('finacc.php'); }
    function sublist() { nav('tools_allsubjects.php'); }
    function mypr() { nav('mypr.php'); }
    function goclsattall() { nav('attndclssec.php'); }
    
    function register(c, s) { 
        const session = '<?php echo $current_session; ?>';
        window.location.href = `st-attnd-register.php?cls=${c}&sec=${s}&year=${session}`; 
    }
</script>