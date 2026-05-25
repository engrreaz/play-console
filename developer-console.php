<?php
$page_title = "System Settings";
include 'inc.php'; // header.php এবং কানেকশন লোড করবে

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_eiin'])) {
    $new_eiin = $_POST['new_eiin'];
    $update_sql = "UPDATE usersapp SET sccode = ? WHERE email = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $new_eiin, $usr);
    if($stmt->execute()) {
        echo "<script>alert('EIIN Changed Successfully'); window.location.href='developer-console.php';</script>";
        exit;
    }
}

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear']
    ?? $_COOKIE['query-session']
    ?? $sy;
$sy_param = '%' . $current_session . '%';

?>

<style>
    .square-box {
        width: 80px;
        height: 80px;
        background: #f8f9fa;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
        border: 1px solid #e9ecef;
    }
    .square-box:hover {
        background: #e2e6ea;
    }
    .square-box i {
        font-size: 1.5rem;
        margin-bottom: 5px;
        color: #6750A4;
    }
    .square-box div {
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1.2;
        color: #49454F;
    }
</style>

<main class="pb-5 mt-2">

    <?php if ($usr == 'engrreaz@gmail.com'): ?>


        <div class="d-flex p-3" id="flex-box" style="gap: 12px;">
            <div class="square-box shadow-sm" data-bs-toggle="modal" data-bs-target="#changeEiinModal">
                <i class="bi bi-arrow-left-right"></i>
                <div>Change EIIN</div>
            </div>
            <div class="square-box shadow-sm">
                <i class="bi bi-palette2"></i>
                <div>Theme</div>
            </div>
            <div class="square-box shadow-sm">
                <i class="bi bi-database-check"></i>
                <div>Logs</div>
            </div>
        </div>

        <div class="m3-section-title px-3 mt-4">Developer Console</div>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_task_manager();">
            <div class="icon-box c-user"><i class="bi bi-list-columns-reverse"></i></div>
            <div class="setting-success">
                <div class="st-title">Task Manager</div>
                <div class="st-desc">Manage Task, Developing issues, Track Bugs</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm " onclick="settings_admin_data_center();">
            <div class="icon-box c-user"><i class="bi bi-info-circle-fill"></i></div>
            <div class="setting-info">
                <div class="st-title">Information Center</div>
                <div class="st-desc">Information hub management</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>

        <a href="permission-mapper.php" class="m3-setting-card shadow-sm">
            <div class="icon-box c-map"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="setting-info">
                <div class="st-title">Permission Mapper</div>
                <div class="st-desc">Assign specific access roles to users</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>




        <div class="dev-grid">
            <a href="promotion.php" class="btn-dev shadow-sm">PROMOTION</a>
            <a href="studentadmission.php" class="btn-dev shadow-sm">ADMISSION</a>
            <a href="cashbookview.php" class="btn-dev shadow-sm">CASHBOOK</a>
            <a href="trackreport.php" class="btn-dev shadow-sm">TRACKING</a>
            <div class="m3-cat-label">Co-Curricular Activities</div>
            <hr>
            <a href="activity_manager.php" class="btn-dev shadow-sm">CCA</a>
            <a href="student_activity_entry.php" class="btn-dev shadow-sm">Entry</a>
            <a href="student_activity_list.php" class="btn-dev shadow-sm">Student CCA</a>
            <a href="all_activities.php" class="btn-dev shadow-sm">All CCA</a>

            <div class="m3-cat-label">Cashbook</div>
            <hr>
            <a href="accounts-manager.php" class="btn-dev shadow-sm">Charts of Account</a>
            <a href="cashbookview.php" class="btn-dev shadow-sm">Cashbook View</a>
            <a href="cashbook.php" class="btn-dev shadow-sm">Cashbook</a>


        </div>
    <?php endif; ?>

</main>

<div style="height: 75px;"></div>
<script>
    function settings_admin_ins_info() { window.location.href = "institute-info.php"; }
    function settings_admin_add_edit_teacher() { window.location.href = "settingsteacher.php"; }
    function settings_admin_cls_sec() { window.location.href = "settings-class.php"; }
    function settings_admin_st_id_generate() { window.location.href = "st-id-gen.php"; }
    function settings_admin_st_id_payment_indivisula() { window.location.href = "st-payment-setup-indivisual.php"; }
    function settings_admin_subject_setup() { window.location.href = "settings-subject.php"; }
    function settings_admin_class_routine_setup_schedule() { window.location.href = "class-schedule-manager.php"; }
    function settings_admin_class_routine_setup() { window.location.href = "clsroutine-setup.php"; }
    function settings_sms_menu() { window.location.href = "sms-manager.php"; }
    function settings_admin_user_manager() { window.location.href = "user-manager.php"; }
    function settings_admin_task_manager() { window.location.href = "task-manager.php"; }
    function settings_admin_fin_setup() { window.location.href = "st-fin-setup.php"; }
    function settings_admin_data_center() { window.location.href = "hub-admin.php"; }
    function settings_admin_data_center_ins() { window.location.href = "information-hub.php"; }
</script>

<!-- Change EIIN Modal -->
<div class="modal fade" id="changeEiinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: #21005D;">Change EIIN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="developer-console.php" class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Enter 6-digit EIIN</label>
                    <input type="number" name="new_eiin" class="form-control form-control-lg" placeholder="e.g. 104235" required min="100000" max="999999" style="border-radius: 12px; background: #f8f9fa;">
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 12px; background: #6750A4; border: none;">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>