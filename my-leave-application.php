<?php
/**
 * My Leave Application - M3 Modern Design
 * Hero | Modal Form | AJAX Sync
 */
$page_title = "My Leave Records";
include 'inc.php';

// ১. এডিট ডাটা ফেচিং (মডালে অটো-পপুলেট করার জন্য)
$appid = $_GET['appid'] ?? 0;
$edit_data = ['type' => '', 'reason' => '', 'date1' => '', 'date2' => ''];
if ($appid > 0) {
    $stmt = $conn->prepare("SELECT * FROM teacher_leave_app WHERE sccode = ? AND id = ? LIMIT 1");
    $stmt->bind_param("si", $sccode, $appid);
    $stmt->execute();
    if ($row = $stmt->get_result()->fetch_assoc()) {
        $edit_data = [
            'type' => $row["leave_type"],
            'reason' => $row["leave_reason"],
            'date1' => $row["date_from"],
            'date2' => $row["date_to"]
        ];
    }
}

// ২. স্ট্যাটাস সামারি ও রেকর্ডস ফেচিং
$my_app_datam = [];
$c1 = $c2 = $c3 = 0;
$stmt_all = $conn->prepare("SELECT * FROM teacher_leave_app WHERE sccode = ? AND tid = ? ORDER BY apply_date DESC, id DESC");
$stmt_all->bind_param("ss", $sccode, $userid);
$stmt_all->execute();
$res_all = $stmt_all->get_result();
while ($row = $res_all->fetch_assoc()) {
    $my_app_datam[] = $row;
    if ($row['status'] == 1) $c1++;
    else if ($row['status'] == 2) $c2++;
    else $c3++;
}
?>

<style>
    :root { --m3-primary: #6750A4; --m3-surface: #FEF7FF; --m3-tonal: #F3EDF7; }
    body { background-color: var(--m3-surface); font-family: 'Inter', sans-serif; }

    /* New Modern Hero */
    .leave-hero {
        background: linear-gradient(135deg, #6750A4 0%, #311B92 100%);
        color: white; padding: 40px 24px 80px;
        border-radius: 0 0 40px 40px; text-align: center;
        position: relative;
    }
    .hero-avatar-box {
        width: 70px; height: 70px; background: rgba(255,255,255,0.2);
        border-radius: 20px; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 15px; border: 1px solid rgba(255,255,255,0.3);
        font-size: 2rem;
    }

    /* Floating Stats (Overlapping Hero) */
    .m3-stats-overlay {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;
        padding: 0 16px; margin-top: -50px; position: relative; z-index: 10;
    }
    .stat-chip {
        background: white; border-radius: 16px; padding: 12px 4px;
        text-align: center; border: 1px solid #EADDFF;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.08);
    }
    .stat-val { font-size: 1.1rem; font-weight: 900; color: #21005D; line-height: 1; }
    .stat-lbl { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; margin-top: 4px; color: #6750A4; }

    /* History Cards */
    .m3-history-card {
        background: #fff; border-radius: 12px; padding: 14px;
        margin: 0 16px 10px; display: flex; align-items: center;
        border: 1px solid #f0f0f0; transition: 0.2s;
    }
    .status-icon-box {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 14px; flex-shrink: 0;
    }
    
    /* M3 Modal Customization */
    .modal-content { border-radius: 28px; border: none; }
    .m3-input-group { background: #F7F2FA; border-radius: 12px; padding: 10px 15px; margin-bottom: 12px; }
    .m3-input-group label { font-size: 0.7rem; font-weight: 800; color: var(--m3-primary); display: block; margin-bottom: 2px; }
    .m3-input-group input, .m3-input-group select { border: none; background: transparent; width: 100%; font-weight: 600; outline: none; }

    .btn-m3-fab {
        position: fixed; bottom: 85px; right: 20px;
        width: 56px; height: 56px; border-radius: 16px;
        background: var(--m3-primary); color: white;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 15px rgba(103, 80, 164, 0.4); z-index: 1000; border: none;
    }

    /* Tonal Accents */
    .tone-green { background: #E8F5E9; color: #2E7D32; }
    .tone-red { background: #FFEBEE; color: #B3261E; }
    .tone-orange { background: #FFF3E0; color: #E65100; }
</style>

<main class="pb-5">
    <div class="leave-hero">
        <div class="hero-avatar-box shadow-sm">
            <i class="bi bi-calendar-check"></i>
        </div>
        <h4 class="fw-black mb-1">Leave Management</h4>
        <p class="small opacity-75 fw-bold mb-0">Record of: <?php echo $fullname; ?></p>
    </div>

    <div class="m3-stats-overlay">
        <div class="stat-chip">
            <div class="stat-val"><?php echo $c1; ?></div>
            <div class="stat-lbl">Approved</div>
        </div>
        <div class="stat-chip">
            <div class="stat-val"><?php echo $c2; ?></div>
            <div class="stat-lbl">Rejected</div>
        </div>
        <div class="stat-chip">
            <div class="stat-val"><?php echo $c3; ?></div>
            <div class="stat-lbl">Review</div>
        </div>
        <div class="stat-chip bg-primary text-white border-0">
            <div class="stat-val text-white"><?php echo count($my_app_datam); ?></div>
            <div class="stat-lbl text-white opacity-75">History</div>
        </div>
    </div>

    <div class="px-2 mt-5">
        <h6 class="ms-3 mb-3 text-secondary fw-black small text-uppercase" style="letter-spacing: 1px;">Application Log</h6>

        <?php foreach ($my_app_datam as $appl): 
            $status = $appl['status'];
            $st_class = ($status == 1) ? "tone-green" : (($status == 2) ? "tone-red" : "tone-orange");
            $st_icon = ($status == 1) ? "bi-check-circle-fill" : (($status == 2) ? "bi-x-circle-fill" : "bi-hourglass-split");
        ?>
            <div class="m3-history-card shadow-sm">
                <div class="status-icon-box <?php echo $st_class; ?>">
                    <i class="bi <?php echo $st_icon; ?> fs-4"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-black" style="font-size: 0.85rem;"><?php echo $appl['leave_type']; ?> Leave</div>
                    <div class="text-truncate small text-muted fw-bold"><?php echo $appl['leave_reason']; ?></div>
                    <div class="small fw-black text-primary mt-1">
                        <i class="bi bi-calendar-range me-1"></i>
                        <?php echo date('d M', strtotime($appl['date_from'])); ?> — <?php echo date('d M', strtotime($appl['date_to'])); ?> 
                        <span class="ms-1">(<?php echo $appl['days']; ?>d)</span>
                    </div>
                </div>
                <?php if ($status == 0 || $status >= 3): ?>
                    <button class="btn btn-light rounded-pill btn-sm ms-2" onclick="editApp(<?php echo $appl['id']; ?>)">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="btn-m3-fab shadow" data-bs-toggle="modal" data-bs-target="#leaveModal">
        <i class="bi bi-plus-lg fs-3"></i>
    </button>
</main>

<div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-black m-0 text-primary">Leave Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="appid" value="<?php echo $appid; ?>">
                
                <div class="m3-input-group">
                    <label>Category</label>
                    <select id="types">
                        <option value="Casual" <?php if($edit_data['type']=='Casual') echo 'selected'; ?>>Casual Leave</option>
                        <option value="Medical" <?php if($edit_data['type']=='Medical') echo 'selected'; ?>>Medical Leave</option>
                        <option value="Others" <?php if($edit_data['type']=='Others') echo 'selected'; ?>>Others</option>
                    </select>
                </div>

                <div class="m3-input-group">
                    <label>Reason</label>
                    <input type="text" id="reason" value="<?php echo $edit_data['reason']; ?>" placeholder="Brief explanation">
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="m3-input-group mb-0">
                            <label>Start Date</label>
                            <input type="date" id="date1" value="<?php echo $edit_data['date1']; ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="m3-input-group mb-0">
                            <label>End Date</label>
                            <input type="date" id="date2" value="<?php echo $edit_data['date2']; ?>">
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary w-100 rounded-pill py-3 mt-4 fw-black shadow-sm" onclick="submitApplication()">
                    <i class="bi bi-send-fill me-2"></i> SUBMIT TO OFFICE
                </button>
                <div id="status_msg" class="text-center mt-3 small fw-bold text-primary"></div>
            </div>
        </div>
    </div>
</div>

<div style="height: 60px;"></div>

<?php include 'footer.php'; ?>

<script>
    /**
     * এডিট মুডে পেজ লোড হলে অটোমেটিক মডাল ওপেন করা
     */
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('appid')) {
            const myModal = new bootstrap.Modal(document.getElementById('leaveModal'));
            myModal.show();
        }
    });

    /**
     * এডিট বাটন লজিক
     */
    function editApp(id) {
        window.location.href = "my-leave-application.php?appid=" + id;
    }

    /**
     * লিভ অ্যাপ্লিকেশন সাবমিশন লজিক
     */
    function submitApplication() {
        const btn = event.target;
        const formData = {
            tid: '<?php echo $userid; ?>',
            types: document.getElementById("types").value,
            reason: document.getElementById("reason").value,
            date1: document.getElementById("date1").value,
            date2: document.getElementById("date2").value,
            id: document.getElementById("appid").value,
            tail: 0
        };

        // ভ্যালিডেশন
        if (!formData.reason || !formData.date1 || !formData.date2) {
            Swal.fire('Incomplete!', 'Please fill all required fields.', 'warning');
            return;
        }

        $.ajax({
            type: "POST",
            url: "backend/save-leave-application.php",
            data: formData,
            beforeSend: function () {
                $('#status_msg').html('<div class="spinner-border spinner-border-sm"></div> SYNCING WITH SERVER...');
                $(btn).prop('disabled', true);
            },
            success: function (response) {
                Swal.fire({
                    title: 'Application Sent!',
                    text: 'Your request has been filed successfully.',
                    icon: 'success',
                    confirmButtonColor: '#6750A4',
                    border_radius: '28px'
                }).then(() => {
                    window.location.href = 'my-leave-application.php';
                });
            },
            error: function() {
                Swal.fire('Error!', 'Something went wrong on the server.', 'error');
                $(btn).prop('disabled', false);
            }
        });
    }
</script>