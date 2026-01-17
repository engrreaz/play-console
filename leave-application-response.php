<?php
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
while ($row = $result->fetch_assoc()) { $my_app_datam[] = $row; }
$stmt_get->close();

$page_title = "Leave Requests";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.85rem; }

    /* M3 Standard App Bar (8px radius bottom) */
    .m3-app-bar {
        background: #fff; height: 56px; display: flex; align-items: center; padding: 0 16px;
        position: sticky; top: 0; z-index: 1050; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Condensed Application Card (8px Radius) */
    .app-card {
        background: #fff; border-radius: 8px; padding: 12px;
        margin: 0 8px 10px; border: 1px solid #eee;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: 0.2s;
    }
    .app-card:active { transform: scale(0.98); background: #F7F2FA; }

    .status-chip {
        font-size: 0.6rem; font-weight: 800; text-transform: uppercase;
        padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 6px;
    }
    .status-0 { background: #EADDFF; color: #21005D; } /* New */
    .status-3 { background: #FFF4E5; color: #663C00; border: 1px solid #FFB900; } /* Review */

    .t-avatar-sm { width: 44px; height: 44px; border-radius: 6px; object-fit: cover; background: #eee; margin-right: 12px; }
    
    .reason-box {
        background-color: #F3EDF7; border-radius: 6px; padding: 10px;
        margin: 8px 0; font-size: 0.8rem; color: #49454F;
        border-left: 3px solid #6750A4; font-style: italic;
    }

    /* M3 Button Styling (8px Radius) */
    .btn-action {
        border-radius: 8px !important; font-weight: 700; font-size: 0.75rem;
        padding: 8px 12px; border: none; flex: 1;
    }
    .btn-approve { background: #146C32; color: white; }
    .btn-reject { background: #B3261E; color: white; }
    .btn-hold { background: #6750A4; color: white; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="tools.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.65rem;">Session: <?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5 mt-2">
    <?php if (empty($my_app_datam)): ?>
        <div class="text-center py-5 opacity-25">
            <i class="bi bi-mailbox2 display-1"></i>
            <p class="fw-bold mt-2">Inbox is empty.</p>
        </div>
    <?php endif; ?>

    <div class="list-container px-1">
        <?php foreach ($my_app_datam as $appl): 
            $id = $appl['id'];
            $st = $appl['status'];
            
            // টিচার ডাটা লুকআপ
            $t_idx = array_search($appl['tid'], array_column($datam_teacher_profile, 'tid'));
            $tname = ($t_idx !== false) ? $datam_teacher_profile[$t_idx]['tname'] : 'Unknown';
            $tphoto = "https://eimbox.com/teacher/" . $appl['tid'] . ".jpg";
        ?>
            <div class="app-card shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <img src="<?php echo $tphoto; ?>" class="t-avatar-sm shadow-sm" onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
                    <div class="overflow-hidden flex-grow-1">
                        <span class="status-chip status-<?php echo $st; ?>">
                            <?php echo ($st == 0) ? 'Pending Request' : 'Held for Review'; ?>
                        </span>
                        <div class="fw-bold text-dark text-truncate"><?php echo $tname; ?></div>
                    </div>
                    <div class="text-end">
                        <div class="fw-extrabold text-primary h6 mb-0"><?php echo $appl['days']; ?></div>
                        <small class="text-muted" style="font-size: 0.6rem;">DAYS</small>
                    </div>
                </div>

                <div class="reason-box">
                    "<?php echo htmlspecialchars($appl['leave_reason']); ?>"
                </div>

                <div class="d-flex justify-content-between align-items-center small text-muted px-1 mb-3" style="font-size: 0.7rem;">
                    <span><i class="bi bi-calendar3 me-1"></i> <?php echo date('d M', strtotime($appl['date_from'])); ?> - <?php echo date('d M, y', strtotime($appl['date_to'])); ?></span>
                    <span class="fw-bold"><?php echo $appl['leave_type']; ?></span>
                </div>

                <div class="d-flex gap-2">
                    <button onclick="processApp(<?php echo $id; ?>, 1)" class="btn-action btn-approve shadow-sm">
                        <i class="bi bi-check2-all me-1"></i> APPROVE
                    </button>
                    <button onclick="processApp(<?php echo $id; ?>, 2)" class="btn-action btn-reject shadow-sm">
                        <i class="bi bi-x-circle me-1"></i> REJECT
                    </button>
                    <?php if ($st == 0): ?>
                        <button onclick="processApp(<?php echo $id; ?>, 3)" class="btn-action btn-hold shadow-sm" title="Review">
                            <i class="bi bi-hourglass-split"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<div style="height: 65px;"></div> <script>
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

<?php include 'footer.php'; ?>