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

    <div class="m3-cat-label">Institution Setup</div>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_ins_info();">
        <div class="icon-box c-inst"><i class="bi bi-bank2"></i></div>
        <div class="setting-info">
            <div class="st-title">Institute Profile</div>
            <div class="st-desc">Identity, logo, EIIN and address</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_add_edit_teacher();">
        <div class="icon-box c-inst"><i class="bi bi-person-workspace"></i></div>
        <div class="setting-info">
            <div class="st-title">Staff Management</div>
            <div class="st-desc">Add or edit teacher and employee profiles</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <div class="m3-cat-label">Academic Framework</div>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_cls_sec();">
        <div class="icon-box c-acad"><i class="bi bi-diagram-3-fill"></i></div>
        <div class="setting-info">
            <div class="st-title">Class & Sections</div>
            <div class="st-desc">Manage institute structure</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_subject_setup();">
        <div class="icon-box c-acad"><i class="bi bi-book-half"></i></div>
        <div class="setting-info">
            <div class="st-title">Subjects & Marks</div>
            <div class="st-desc">Subject list and distribution setup</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm"
        onclick="settings_admin_class_routine_setup_schedule();">
        <div class="icon-box c-acad"><i class="bi bi-clock-history"></i></div>
        <div class="setting-info">
            <div class="st-title">Time Table</div>
            <div class="st-desc">Period management and routine setup</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_class_routine_setup();">
        <div class="icon-box c-acad"><i class="bi bi-calendar-week"></i></div>
        <div class="setting-info">
            <div class="st-title">Setup Class Routine</div>
            <div class="st-desc">Setup class routine with day/period & teacher attachment</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <div class="m3-cat-label">Student & Finance</div>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_st_id_generate();">
        <div class="icon-box c-fina"><i class="bi bi-person-vcard-fill"></i></div>
        <div class="setting-info">
            <div class="st-title">Student ID Wizard</div>
            <div class="st-desc">Auto-generate IDs for new admissions</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm"
        onclick="settings_admin_st_id_payment_indivisula();">
        <div class="icon-box c-fina"><i class="bi bi-coin"></i></div>
        <div class="setting-info">
            <div class="st-title">Fee Configuration</div>
            <div class="st-desc">Custom payments and special waivers</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>
    
    <a href="javascript:void(0);" class="m3-setting-card shadow-sm"
        onclick="settings_admin_fin_setup();">
        <div class="icon-box c-fina"><i class="bi bi-coin"></i></div>
        <div class="setting-info">
            <div class="st-title">Payment setup</div>
            <div class="st-desc">Configure Financial Items</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <div class="m3-cat-label">Access Control</div>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_sms_menu();">
        <div class="icon-box c-user"><i class="bi bi-chat-right-dots-fill"></i></div>
        <div class="setting-info">
            <div class="st-title">SMS Gateway</div>
            <div class="st-desc">Broadcast templates and logs</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_user_manager();">
        <div class="icon-box c-user"><i class="bi bi-person-lock"></i></div>
        <div class="setting-info">
            <div class="st-title">System Users</div>
            <div class="st-desc">Roles, passwords and permissions</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_data_center();">
        <div class="icon-box c-user"><i class="bi bi-info-circle"></i></div>
        <div class="setting-info">
            <div class="st-title">Data Center</div>
            <div class="st-desc">Information hub management</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <?php if ($usr == 'engrreaz@gmail.com'): ?>
        <div class="m3-cat-label">Developer Console</div>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_task_manager();">
            <div class="icon-box c-user"><i class="bi bi-bug"></i></div>
            <div class="setting-warning">
                <div class="st-title">Task Manager</div>
                <div class="st-desc">Manage Task, Developing issues, Track Bug, Fixing Issues</div>
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

            <hr>
            <div class="m3-cat-label">Permission</div>
            <hr>
            <a href="permission-mapper.php" class="btn-dev shadow-sm">Mapper</a>
            <a href="permission-manager.php" class="btn-dev shadow-sm">Manager</a>
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
</script>

<?php include 'footer.php'; ?>