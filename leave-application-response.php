<?php
$page_title = "Leave Requests";
ob_start();
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-teacher.php';

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear']
    ?? $_COOKIE['query-session']
    ?? $sy;
$sy_param = "%" . $current_session . "%";

// ২. স্ট্যাটাস আপডেট লজিক (Action Handling)
if (isset($_GET['appid']) && isset($_GET['tail'])) {
    $appid = $_GET['appid'];
    $resp = $_GET['tail'];

    $stmt_upd = $conn->prepare("UPDATE teacher_leave_app SET status = ?, response_by = ?, response_time = ?, modifieddate = ? WHERE id = ?");
    $stmt_upd->bind_param("isssi", $resp, $usr, $cur, $cur, $appid);
    $stmt_upd->execute();
    $stmt_upd->close();

    header("Location: leave-application-response.php?year=$current_session");
    exit();
}

// ৩. ডাটা ফেচিং (Pending এবং Under Review অ্যাপ্লিকেশন)
$my_app_datam = [];
$stmt_get = $conn->prepare("SELECT * FROM teacher_leave_app WHERE sccode = ? AND (status = 0 OR status >= 3) ORDER BY apply_date DESC, id DESC");
$stmt_get->bind_param("s", $sccode);
$stmt_get->execute();
$result = $stmt_get->get_result();
while ($row = $result->fetch_assoc()) {
    $my_app_datam[] = $row;
}
$stmt_get->close();


?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-on-primary: #FFFFFF;
        --m3-primary-container: #EADDFF;
        --m3-secondary-container: #F3EDF7;
        --m3-success-container: #C7EBD1;
        --m3-error-container: #F9DEDC;
        --m3-warning-container: #FFDDB3;
    }

    body { background-color: var(--m3-surface); font-family: 'Inter', sans-serif; }

    /* M3 Tonal Hero Area */
    .m3-hero-tonal {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white; padding: 40px 20px 70px;
        border-radius: 0 0 32px 32px;
        text-align: center; margin-bottom: -30px;
    }

    /* Condensed Tonal Card */
    .app-card {
        background: white;
        border-radius: 24px; /* M3 Large Shape */
        padding: 16px;
        margin: 0 12px 16px;
        border: 1px solid #E7E0EC;
        transition: all 0.3s cubic-bezier(0, 0, 0.2, 1);
    }

    .app-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    /* Tonal Badges/Chips */
    .m3-chip {
        font-size: 0.65rem; font-weight: 800;
        padding: 4px 12px; border-radius: 100px;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .status-0 { background: var(--m3-primary-container); color: #21005D; }
    .status-3 { background: var(--m3-warning-container); color: #291800; }

    .day-badge {
        background: var(--m3-secondary-container);
        color: var(--m3-primary);
        width: 50px; height: 50px;
        border-radius: 16px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        font-weight: 900;
    }

    .t-avatar-m3 {
        width: 52px; height: 52px;
        border-radius: 12px; object-fit: cover;
        border: 2px solid white;
    }

    /* Tonal Action Buttons */
    .btn-tonal {
        border-radius: 100px !important;
        border: none; padding: 10px 16px;
        font-weight: 700; font-size: 0.75rem;
        flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: 0.2s;
    }
    .btn-tonal-success { background: var(--m3-success-container); color: #00210B; }
    .btn-tonal-error { background: var(--m3-error-container); color: #410002; }
    .btn-tonal-primary { background: var(--m3-primary-container); color: #21005D; }

    .reason-box-tonal {
        background: var(--m3-secondary-container);
        border-radius: 12px; padding: 12px;
        margin: 12px 0; font-size: 0.85rem;
        color: #49454F; border-left: 4px solid var(--m3-primary);
    }
</style>

<main class="pb-5">
    <div class="m3-hero-tonal shadow">
        <h3 class="fw-black mb-1">Leave Requests</h3>
        <p class="small opacity-75 fw-bold mb-0">Management Dashboard • <?= $current_session ?></p>
        
        <?php if (!empty($my_app_datam)): ?>
            <div class="mt-3">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-black">
                    <i class="bi bi-envelope-open-fill me-1"></i> <?= count($my_app_datam) ?> PENDING
                </span>
            </div>
        <?php endif; ?>
    </div>

    <div class="list-container px-1" style="margin-top: 15px;">
        <?php if (empty($my_app_datam)): ?>
            <div class="text-center py-5 opacity-25 mt-5">
                <i class="bi bi-check2-circle display-1"></i>
                <p class="fw-bold mt-2">All caught up!</p>
            </div>
        <?php endif; ?>

        <?php foreach ($my_app_datam as $appl):
            $id = $appl['id'];
            $st = $appl['status'];
            $t_idx = array_search($appl['tid'], array_column($datam_teacher_profile, 'tid'));
            $tname = ($t_idx !== false) ? $datam_teacher_profile[$t_idx]['tname'] : 'Academic Staff';
            $tphoto = "https://eimbox.com/teacher/" . $appl['tid'] . ".jpg";
        ?>
            <div class="app-card shadow-sm">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= $tphoto ?>" class="t-avatar-m3 shadow-sm me-3" 
                         onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
                    
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="m3-chip status-<?= $st ?> mb-1">
                            <i class="bi <?= ($st==0)?'bi-clock-fill':'bi-pause-circle-fill' ?>"></i>
                            <?= ($st == 0) ? 'New Request' : 'Under Review' ?>
                        </div>
                        <div class="fw-black text-dark text-truncate fs-6"><?= $tname ?></div>
                        <div class="small text-muted fw-bold"><?= $appl['leave_type'] ?></div>
                    </div>

                    <div class="day-badge shadow-sm">
                        <span class="fs-5"><?= $appl['days'] ?></span>
                        <span style="font-size: 0.5rem; margin-top: -5px;">DAYS</span>
                    </div>
                </div>

                <div class="reason-box-tonal">
                    <i class="bi bi-quote fs-4 opacity-25"></i>
                    <span class="fw-medium italic"><?= htmlspecialchars($appl['leave_reason']) ?></span>
                </div>

                <div class="d-flex align-items-center justify-content-center gap-3 py-2 px-3 rounded-pill bg-light border mb-4">
                    <div class="small fw-bold"><i class="bi bi-calendar-event text-primary me-1"></i> <?= date('d M', strtotime($appl['date_from'])) ?></div>
                    <i class="bi bi-arrow-right text-muted small"></i>
                    <div class="small fw-bold"><i class="bi bi-calendar-check text-primary me-1"></i> <?= date('d M, y', strtotime($appl['date_to'])) ?></div>
                </div>

                <div class="d-flex gap-2">
                    <button onclick="processApp(<?= $id ?>, 1)" class="btn-tonal btn-tonal-success shadow-sm">
                        <i class="bi bi-check-lg"></i> APPROVE
                    </button>
                    <button onclick="processApp(<?= $id ?>, 2)" class="btn-tonal btn-tonal-error shadow-sm">
                        <i class="bi bi-x-lg"></i> REJECT
                    </button>
                    <?php if ($st == 0): ?>
                        <button onclick="processApp(<?= $id ?>, 3)" class="btn-tonal btn-tonal-primary shadow-sm" title="Mark for Review">
                            <i class="bi bi-pause-fill"></i> HOLD
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'footer.php'; ?>



<script>
    function processApp(id, tail) {
        const actions = { 1: "Approve", 2: "Reject", 3: "Put on Hold" };
        const colors = { 1: "#146C32", 2: "#B3261E", 3: "#6750A4" };

        Swal.fire({
            title: actions[tail] + '?',
            text: "Are you sure you want to process this leave request?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: colors[tail],
            confirmButtonText: 'Yes, ' + actions[tail],
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `leave-application-response.php?appid=${id}&tail=${tail}&year=<?php echo $current_session; ?>`;
            }
        });
    }
</script>

