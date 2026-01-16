<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-teacher.php';

// ১. স্ট্যাটাস আপডেট লজিক (Prepared Statement)
if (isset($_GET['appid']) && isset($_GET['tail'])) {
    $appid = $_GET['appid'];
    $resp = $_GET['tail'];
    
    $stmt_upd = $conn->prepare("UPDATE teacher_leave_app SET status = ?, response_by = ?, response_time = ?, modifieddate = ? WHERE id = ?");
    $stmt_upd->bind_param("isssi", $resp, $usr, $cur, $cur, $appid);
    $stmt_upd->execute();
    $stmt_upd->close();
    
    // রিফ্রেশ করে ক্লিন URL রাখা
    header("Location: leave-application-response.php");
    exit();
}

// ২. ডাটা ফেচিং (Pending এবং Under Review অ্যাপ্লিকেশন)
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
    body { background-color: #FEF7FF; } /* M3 Surface */

    /* Application Card Style */
    .app-card {
        border: none;
        border-radius: 28px;
        background-color: #FFFFFF;
        margin-bottom: 16px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    /* Status-based Accents */
    .status-badge {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 100px;
        display: inline-block;
        margin-bottom: 10px;
    }
    .status-0 { background: #EADDFF; color: #21005D; } /* New */
    .status-3 { background: #FFFBFE; color: #8D5C00; border: 1px solid #FFB900; } /* Review */

    .teacher-name { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; }
    .leave-info { font-size: 0.85rem; color: #49454F; margin-top: 5px; }
    .reason-box {
        background-color: #F7F2FA;
        border-radius: 16px;
        padding: 12px;
        margin: 12px 0;
        font-size: 0.9rem;
        color: #1D1B20;
        border-left: 4px solid #6750A4;
    }

    /* Action Buttons */
    .action-bar { display: flex; gap: 10px; margin-top: 15px; }
    .btn-m3 {
        border-radius: 100px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        border: none;
        transition: 0.2s;
    }
    .btn-approve { background-color: #4CAF50; color: white; }
    .btn-reject { background-color: #F44336; color: white; }
    .btn-review { background-color: #FF9800; color: white; }
    .btn-m3:active { transform: scale(0.95); opacity: 0.8; }
    
    .icon-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: #F3EDF7; color: #6750A4;
    }
</style>

<main class="container mt-3 pb-5">
    <div class="d-flex align-items-center mb-4 px-2">
        <a href="tools.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
        <h4 class="fw-bold mb-0">Leave Responses</h4>
    </div>

    <?php if (empty($my_app_datam)): ?>
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-check display-1 text-muted opacity-25"></i>
            <p class="text-muted mt-3">No pending applications found.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($my_app_datam as $appl): 
        $appl_id = $appl['id'];
        $status = $appl['status'];
        
        // টিচারের নাম খোঁজা
        $t_idx = array_search($appl['tid'], array_column($datam_teacher_profile, 'tid'));
        $tname = ($t_idx !== false) ? $datam_teacher_profile[$t_idx]['tname'] : 'Unknown Teacher';
        $tphoto = "teacher/" . $appl['tid'] . ".jpg";
    ?>
        <div class="app-card shadow-sm border-start border-4 <?php echo ($status == 0) ? 'border-primary' : 'border-warning'; ?>">
            <div class="d-flex align-items-start">
                <img src="<?php echo $tphoto; ?>" class="rounded-circle me-3 border" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
                <div class="flex-grow-1">
                    <span class="status-badge status-<?php echo $status; ?>">
                        <?php echo ($status == 0) ? 'New Application' : 'Under Review'; ?>
                    </span>
                    <div class="teacher-name"><?php echo htmlspecialchars($tname); ?></div>
                    <div class="leave-info">
                        <i class="bi bi-tag-fill me-1"></i> <?php echo htmlspecialchars($appl['leave_type']); ?> 
                        <span class="mx-2">|</span>
                        <i class="bi bi-calendar-range me-1"></i> <?php echo $appl['days']; ?> Days
                    </div>
                </div>
            </div>

            <div class="reason-box">
                "<?php echo htmlspecialchars($appl['leave_reason']); ?>"
            </div>

            <div class="small text-muted mb-3">
                <i class="bi bi-clock-history me-1"></i> Period: <?php echo date('d M', strtotime($appl['date_from'])); ?> to <?php echo date('d M, Y', strtotime($appl['date_to'])); ?>
            </div>

            <div class="action-bar">
                <button onclick="respond(<?php echo $appl_id; ?>, 1)" class="btn-m3 btn-approve flex-grow-1 justify-content-center">
                    <i class="bi bi-check2-circle me-2"></i> Approve
                </button>
                <button onclick="respond(<?php echo $appl_id; ?>, 2)" class="btn-m3 btn-reject flex-grow-1 justify-content-center">
                    <i class="bi bi-x-circle me-2"></i> Reject
                </button>
                <?php if ($status == 0): ?>
                    <button onclick="respond(<?php echo $appl_id; ?>, 3)" class="btn-m3 btn-review" title="Hold for Review">
                        <i class="bi bi-hourglass-split"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</main>

<div style="height: 70px;"></div>

<script>
    function respond(id, tail) {
        const statusText = tail === 1 ? "Approve" : (tail === 2 ? "Reject" : "Review");
        const confirmColor = tail === 1 ? "#4CAF50" : (tail === 2 ? "#F44336" : "#FF9800");

        Swal.fire({
            title: statusText + ' Application?',
            text: "Are you sure you want to perform this action?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#79747E',
            confirmButtonText: 'Yes, ' + statusText
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "leave-application-response.php?appid=" + id + "&tail=" + tail;
            }
        });
    }
</script>

<?php include 'footer.php'; ?>