<?php
$page_title = "System Settings";
include 'inc.php'; // header.php এবং কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear']
    ?? $_COOKIE['query-session']
    ?? $sy;
$sy_param = '%' . $current_session . '%';


?>



<main class="pb-5 mt-2">

    <?php if ($usr == 'engrreaz@gmail.com'): ?>
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

<?php include 'footer.php'; ?>