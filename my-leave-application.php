<?php
/**
 * My Leave Application - M3-EIM-Floating Style
 * Standards: 8px Radius | Tonal Containers | Floating Labels | FAB UI
 */
$page_title = "My Leave Records";
include 'inc.php'; 

$appid = $_GET['appid'] ?? 0;
$type = $reason = $date1 = $date2 = "";
$show_form = isset($_GET['appid']) ? "" : "display:none;";

// ১. নির্দিষ্ট আবেদন ফেচ করা (এডিটের জন্য)
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

// স্ট্যাটাস ভিত্তিক কাউন্টিং লজিক
$c1 = $c2 = $c3 = 0;
foreach($my_app_datam as $a) {
    if($a['status'] == 1) $c1++;
    else if($a['status'] == 2) $c2++;
    else $c3++;
}
?>

<style>
    body { background-color: #FEF7FF; margin: 0; padding: 0; }

    /* Stats Board (Strict 8px) */
    .m3-stats-board {
        background-color: #F3EDF7;
        border-radius: 8px !important;
        padding: 16px; margin: 16px;
        box-shadow: 0 1px 3px rgba(103, 80, 164, 0.05);
    }
    
    .m3-stat-chip {
        background: #fff; border-radius: 8px; 
        text-align: center; padding: 12px 4px;
        border: 1px solid #EADDFF;
    }
    .m3-stat-val { font-size: 1.1rem; font-weight: 900; line-height: 1; color: #21005D; }
    .m3-stat-lbl { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; margin-top: 4px; color: #6750A4; }

    /* Leave Form Card */
    .m3-leave-form-card {
        background: #fff; border-radius: 8px; padding: 20px;
        margin: 0 16px 20px; border: 1px solid #EADDFF;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.08);
    }

    /* History Card (M3-EIM-Floating Style) */
    .m3-history-card {
        background: #fff; border-radius: 8px; padding: 12px 16px;
        margin: 0 16px 10px; display: flex; align-items: center;
        border: 1px solid #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: 0.2s cubic-bezier(0, 0, 0.2, 1);
    }
    .m3-history-card:active { background-color: #F7F2FA; transform: scale(0.98); }

    .m3-status-icon-box {
        width: 44px; height: 44px; border-radius: 8px; /* Strict 8px */
        display: flex; align-items: center; justify-content: center;
        margin-right: 14px; flex-shrink: 0;
    }

    .m3-content-box { flex-grow: 1; overflow: hidden; }
    .m3-leave-title { font-weight: 800; color: #1C1B1F; font-size: 0.85rem; }
    .m3-leave-meta { font-size: 0.7rem; color: #79747E; font-weight: 600; margin-top: 2px; }

    /* FAB M3 Style */
    .m3-fab-plus {
        position: fixed; bottom: 85px; right: 20px;
        width: 56px; height: 56px; border-radius: 16px;
        background-color: #6750A4; color: white;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
        z-index: 1000; border: none; transition: 0.2s;
    }

    /* Status Tonal Colors */
    .tone-approved { background-color: #E8F5E9; color: #2E7D32; }
    .tone-rejected { background-color: #FFEBEE; color: #B3261E; }
    .tone-review   { background-color: #FFF3E0; color: #E65100; }
</style>

<main class="pb-5">
    <div class="m3-stats-board shadow-sm">
        <div class="row g-2">
            <div class="col-3"><div class="m3-stat-chip text-success"><div class="m3-stat-val"><?php echo $c1; ?></div><div class="m3-stat-lbl">Approved</div></div></div>
            <div class="col-3"><div class="m3-stat-chip text-danger"><div class="m3-stat-val"><?php echo $c2; ?></div><div class="m3-stat-lbl">Rejected</div></div></div>
            <div class="col-3"><div class="m3-stat-chip text-warning"><div class="m3-stat-val"><?php echo $c3; ?></div><div class="m3-stat-lbl">Review</div></div></div>
            <div class="col-3"><div class="m3-stat-chip bg-primary text-white" style="border:none;"><div class="m3-stat-val text-white"><?php echo count($my_app_datam); ?></div><div class="m3-stat-lbl text-white opacity-75">Total</div></div></div>
        </div>
    </div>

    <div class="m3-leave-form-card" id="leaveFormBlock" style="<?php echo $show_form; ?>">
        <h6 class="fw-bold mb-4 text-primary d-flex align-items-center">
            <i class="bi bi-pencil-square me-2 fs-5"></i> Application Form
        </h6>
        
        <input type="hidden" id="tid" value="<?php echo $userid; ?>">
        
        <div class="m3-floating-group">
            <label class="m3-floating-label">Leave Category</label>
            <i class="bi bi-tag m3-field-icon"></i>
            <select class="m3-select-floating" id="types">
                <option value="">Select Type</option>
                <option value="Casual" <?php if($type=='Casual') echo 'selected';?>>Casual Leave</option>
                <option value="Medical" <?php if($type=='Medical') echo 'selected';?>>Medical Leave</option>
                <option value="Others" <?php if($type=='Others') echo 'selected';?>>Others</option>
            </select>
        </div>

        <div class="m3-floating-group">
            <label class="m3-floating-label">Reason for Leave</label>
            <i class="bi bi-chat-left-text m3-field-icon"></i>
            <input type="text" id="reason" class="m3-input-floating" placeholder="Brief reason" value="<?php echo $reason; ?>">
        </div>

        <div class="row g-2 mb-4">
            <div class="col-6">
                <div class="m3-floating-group mb-0">
                    <label class="m3-floating-label">Date From</label>
                    <i class="bi bi-calendar-event m3-field-icon"></i>
                    <input type="date" id="date1" class="m3-input-floating" value="<?php echo $date1; ?>">
                </div>
            </div>
            <div class="col-6">
                <div class="m3-floating-group mb-0">
                    <label class="m3-floating-label">Date To</label>
                    <i class="bi bi-calendar-check m3-field-icon"></i>
                    <input type="date" id="date2" class="m3-input-floating" value="<?php echo $date2; ?>">
                </div>
            </div>
        </div>

        <button class="btn-m3-submit shadow" onclick="save_leave_application(<?php echo $appid; ?>, 0);">
            <span>SUBMIT APPLICATION</span>
            <i class="bi bi-send-fill"></i>
        </button>
        <div id="px" class="text-center mt-3 small fw-bold text-primary"></div>
    </div>

    <div class="px-1 mt-4">
        <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase" style="letter-spacing: 1px;">Application History</h6>
        
        <?php foreach ($my_app_datam as $appl): 
            $status = $appl['status'];
            $st_class = "tone-review"; $st_icon = "bi-hourglass-split"; $st_text = "Pending";
            
            if($status == 1) { $st_class = "tone-approved"; $st_icon = "bi-check-circle-fill"; }
            else if($status == 2) { $st_class = "tone-rejected"; $st_icon = "bi-x-circle-fill"; }
            else if($status == 3) { $st_class = "tone-review"; $st_icon = "bi-eye-fill"; }
        ?>
            <div class="m3-history-card shadow-sm">
                <div class="m3-status-icon-box <?php echo $st_class; ?>">
                    <i class="bi <?php echo $st_icon; ?> fs-4"></i>
                </div>
                <div class="m3-content-box">
                    <div class="m3-leave-title"><?php echo $appl['leave_type']; ?> Leave</div>
                    <div class="m3-leave-meta text-truncate"><?php echo $appl['leave_reason']; ?></div>
                    <div class="m3-leave-meta mt-1" style="font-size: 0.65rem;">
                        <i class="bi bi-calendar-range me-1"></i> 
                        <?php echo date('d M', strtotime($appl['date_from'])); ?> — <?php echo date('d M', strtotime($appl['date_to'])); ?> 
                        <span class="text-primary ms-1 fw-bold">(<?php echo $appl['days']; ?> Days)</span>
                    </div>
                </div>
                <div class="ms-2">
                    <?php if($status == 0 || $status >= 3): ?>
                        <button class="btn btn-sm btn-outline-primary border-0 rounded-circle" onclick="leave_app_edit(<?php echo $appl['id']; ?>)">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="m3-fab-plus shadow" onclick="toggleForm();">
        <i class="bi bi-plus-lg fs-3"></i>
    </button>
</main>

<div style="height: 60px;"></div>

<?php 
// আপনার নির্দেশ অনুযায়ী JS স্ক্রিপ্ট শুরু করার আগে footer.php ইনক্লুড করা হলো
include 'footer.php'; 
?>

<script>
    /**
     * Form Toggle Logic
     */
    function toggleForm() {
        const block = document.getElementById("leaveFormBlock");
        if(block.style.display === "none") {
            $(block).fadeIn(300);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            $(block).fadeOut(200);
        }
    }

    function leave_app_edit(id) {
        window.location.href = "my-leave-application.php?appid=" + id;
    }

    /**
     * AJAX Submission
     */
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
            beforeSend: function () { 
                $('#px').html('<div class="spinner-border spinner-border-sm text-primary me-2"></div> SYNCING...'); 
            },
            success: function (html) {
                Swal.fire({ 
                    title: 'Submitted!', 
                    text: 'Your application is on its way.', 
                    icon: 'success', 
                    confirmButtonColor: '#6750A4' 
                }).then(() => { 
                    window.location.href = 'my-leave-application.php'; 
                });
            }
        });
    }
</script>