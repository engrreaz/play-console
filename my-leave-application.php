<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

$appid = $_GET['appid'] ?? 0;
$type = $reason = $date1 = $date2 = "";
$show_form = isset($_GET['appid']) ? "" : "display:none;";

// ১. নির্দিষ্ট আবেদন ফেচ করা (এডিটের জন্য - Prepared Statement)
if ($appid > 0) {
    $stmt = $conn->prepare("SELECT * FROM teacher_leave_app WHERE sccode = ? AND id = ? LIMIT 1");
    $stmt->bind_param("si", $sccode, $appid);
    $stmt->execute();
    if ($row = $stmt->get_result()->fetch_assoc()) {
        $type = $row["leave_type"];
        $reason = $row["leave_reason"];
        $date1 = $row["date_from"];
        $date2 = $row["date_to"];
    }
    $stmt->close();
}

// ২. ইউজারের সব আবেদন ফেচ করা
$my_app_datam = [];
$stmt_all = $conn->prepare("SELECT * FROM teacher_leave_app WHERE sccode = ? AND tid = ? ORDER BY apply_date DESC, id DESC");
$stmt_all->bind_param("ss", $sccode, $userid);
$stmt_all->execute();
$res_all = $stmt_all->get_result();
while ($row = $res_all->fetch_assoc()) {
    $my_app_datam[] = $row;
}
$stmt_all->close();
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Stats Board Styling */
    .stats-board {
        background-color: #F3EDF7;
        border-radius: 28px;
        padding: 20px;
        margin: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .stat-chip {
        text-align: center;
        padding: 8px;
        border-radius: 16px;
        background: #fff;
    }
    .stat-val { font-size: 1.2rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
    .stat-lbl { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; opacity: 0.7; }

    /* FAB Styling */
    .fab-new {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 56px; height: 56px;
        border-radius: 16px;
        background-color: #6750A4;
        color: white;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.4);
        z-index: 1000; border: none; transition: 0.2s;
    }
    .fab-new:active { transform: scale(0.9); }

    /* Leave Card Style */
    .leave-card {
        background: #fff;
        border-radius: 24px;
        padding: 16px;
        margin: 0 16px 12px;
        border: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
    }

    .status-icon-box {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 16px; flex-shrink: 0;
    }

    /* Input Floating Style */
    .form-floating > .form-control, .form-floating > .form-select {
        border-radius: 12px;
        border: 1px solid #79747E;
        background: transparent;
    }

    .bg-approved { background-color: #E8F5E9; color: #2E7D32; }
    .bg-rejected { background-color: #FFEBEE; color: #D32F2F; }
    .bg-pending  { background-color: #FFF3E0; color: #E65100; }
</style>

<main class="pb-5">
    <div class="bg-white p-3 shadow-sm sticky-top mb-3 rounded-bottom-4">
        <div class="d-flex align-items-center">
            <a href="build.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <h5 class="fw-bold mb-0">My Leave Records</h5>
        </div>
    </div>

    <?php
    // স্ট্যাটাস ভিত্তিক কাউন্টিং
    $c1 = $c2 = $c3 = 0;
    foreach($my_app_datam as $a) {
        if($a['status'] == 1) $c1++;
        else if($a['status'] == 2) $c2++;
        else $c3++;
    }
    ?>

    <div class="stats-board">
        <div class="row g-2">
            <div class="col-3"><div class="stat-chip shadow-sm text-success"><div class="stat-val"><?php echo $c1; ?></div><div class="stat-lbl">Approved</div></div></div>
            <div class="col-3"><div class="stat-chip shadow-sm text-danger"><div class="stat-val"><?php echo $c2; ?></div><div class="stat-lbl">Rejected</div></div></div>
            <div class="col-3"><div class="stat-chip shadow-sm text-warning"><div class="stat-val"><?php echo $c3; ?></div><div class="stat-lbl">Review</div></div></div>
            <div class="col-3"><div class="stat-chip shadow-sm bg-primary text-white"><div class="stat-val"><?php echo count($my_app_datam); ?></div><div class="stat-lbl">Total</div></div></div>
        </div>
    </div>

    <div class="card mx-3 mb-4 shadow-sm border-0" id="leaveFormBlock" style="border-radius: 28px; <?php echo $show_form; ?>">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-pencil-square me-2"></i>Apply for Leave</h6>
            
            <input type="hidden" id="tid" value="<?php echo $userid; ?>">
            
            <div class="form-floating mb-3">
                <select class="form-select" id="types">
                    <option value="">Select Type</option>
                    <option value="Casual" <?php if($type=='Casual') echo 'selected';?>>Casual Leave</option>
                    <option value="Medical" <?php if($type=='Medical') echo 'selected';?>>Medical Leave</option>
                    <option value="Others" <?php if($type=='Others') echo 'selected';?>>Others</option>
                </select>
                <label for="types">Leave Category</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" id="reason" class="form-control" placeholder="Reason" value="<?php echo $reason; ?>">
                <label for="reason">Reason for Leave</label>
            </div>

            <div class="row g-2 mb-4">
                <div class="col-6">
                    <div class="form-floating">
                        <input type="date" id="date1" class="form-control" value="<?php echo $date1; ?>">
                        <label for="date1">From</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-floating">
                        <input type="date" id="date2" class="form-control" value="<?php echo $date2; ?>">
                        <label for="date2">To</label>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" 
                    onclick="save_leave_application(<?php echo $appid; ?>, 0);">
                <i class="bi bi-send-fill me-2"></i> SUBMIT APPLICATION
            </button>
            <div id="px" class="text-center mt-3 small fw-bold"></div>
        </div>
    </div>

    <div class="px-1 mt-4">
        <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase">My Application History</h6>
        
        <?php foreach ($my_app_datam as $appl): 
            $status = $appl['status'];
            $st_class = "bg-pending"; $st_icon = "bi-hourglass-split"; $st_text = "Pending";
            
            if($status == 1) { $st_class = "bg-approved"; $st_icon = "bi-check-circle-fill"; $st_text = "Approved"; }
            else if($status == 2) { $st_class = "bg-rejected"; $st_icon = "bi-x-circle-fill"; $st_text = "Rejected"; }
            else if($status == 3) { $st_class = "bg-pending"; $st_icon = "bi-eye-fill"; $st_text = "Reviewing"; }
        ?>
            <div class="leave-card shadow-sm">
                <div class="status-icon-box <?php echo $st_class; ?>">
                    <i class="<?php echo $st_icon; ?> fs-4"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark"><?php echo $appl['leave_type']; ?> Leave</div>
                    <div class="text-muted small text-truncate"><?php echo $appl['leave_reason']; ?></div>
                    <div class="small fw-medium mt-1" style="font-size: 0.7rem;">
                        <i class="bi bi-calendar-range me-1"></i> <?php echo date('d M', strtotime($appl['date_from'])); ?> — <?php echo date('d M', strtotime($appl['date_to'])); ?> 
                        <span class="text-primary ms-2">(<?php echo $appl['days']; ?> Days)</span>
                    </div>
                </div>
                <div class="ms-2">
                    <?php if($status == 0 || $status >= 3): ?>
                        <button class="btn btn-link text-primary p-0" onclick="leave_app_edit(<?php echo $appl['id']; ?>)">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="fab-new shadow" onclick="toggleForm();">
        <i class="bi bi-plus-lg fs-3"></i>
    </button>
</main>

<div style="height: 80px;"></div>



<script>
    function toggleForm() {
        const block = document.getElementById("leaveFormBlock");
        if(block.style.display === "none") {
            block.style.display = "block";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            block.style.display = "none";
        }
    }

    function leave_app_edit(id) {
        window.location.href = "my-leave-application.php?appid=" + id;
    }

    function save_leave_application(id, tail) {
        const formData = {
            tid: document.getElementById("tid").value,
            types: document.getElementById("types").value,
            reason: document.getElementById("reason").value,
            date1: document.getElementById("date1").value,
            date2: document.getElementById("date2").value,
            id: id,
            tail: tail
        };

        if(!formData.types || !formData.date1) {
            Swal.fire('Incomplete!', 'Please select leave type and dates.', 'warning');
            return;
        }

        $.ajax({
            type: "POST",
            url: "backend/save-leave-application.php",
            data: formData,
            beforeSend: function () { $('#px').html('<div class="spinner-border spinner-border-sm text-primary"></div> Processing...'); },
            success: function (html) {
                Swal.fire({ title: 'Submitted!', text: 'Your application is on its way.', icon: 'success', confirmButtonColor: '#6750A4' })
                .then(() => { window.location.href = 'my-leave-application.php'; });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>