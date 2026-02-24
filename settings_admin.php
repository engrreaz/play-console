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

    <div class="m3-section-title px-3 mt-4">Institution Setup</div>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_ins_info();">
        <div class="icon-box c-inst"><i class="bi bi-bank2"></i></div>
        <div class="setting-info">
            <div class="st-title">Institute Profile</div>
            <div class="st-desc">Identity, logo, EIIN and address</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="slot-manager.php" class="m3-setting-card shadow-sm" data-feature="Slot Manager">
        <div class="icon-box c-slot"><i class="bi bi-grid-1x2-fill"></i></div>
        <div class="setting-info">
            <div class="st-title">Slot Manager</div>
            <div class="st-desc">Merit type, report templates, and parent format</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="basic-settings.php" class="m3-setting-card shadow-sm card-wrapper">

        <div class="icon-box c-basic"><i class="bi bi-sliders2-vertical"></i></div>
        <div class="setting-info">
            <div class="st-title">Basic Settings</div>
            <div class="st-desc">Academic session, weekends, and global rules</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>

        <div class="m3-sparkle"><i class="bi bi-stars"></i></div>
    </a>

    <a href="javascript:void(0);" data-feature="Staff Management Card" class="m3-setting-card shadow-sm"
        onclick="settings_admin_add_edit_teacher();">
    
        <div class="icon-box c-inst "><i class="bi bi-person-workspace"></i></div>
        <div class="setting-info">
            <div class="st-title">Staff Management</div>
            <div class="st-desc">Add or edit teacher and employee profiles</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <div class="m3-section-title px-3 mt-4">Academic Framework</div>

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

    <div class="m3-section-title px-3 mt-4">Student & Finance</div>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_st_id_generate();">
        <div class="icon-box c-fina"><i class="bi bi-person-vcard-fill"></i></div>
        <div class="setting-info">
            <div class="st-title">Student ID Generator</div>
            <div class="st-desc">Auto-generate IDs for new admissions</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_fin_setup();">
        <div class="icon-box c-fina"><i class="bi bi-cash-coin"></i></div>
        <div class="setting-info">
            <div class="st-title">Payment setup</div>
            <div class="st-desc">Configure Financial Items</div>
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



    <div class="m3-section-title px-3 mt-4">Access Control</div>

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



    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_data_center_ins();">
        <div class="icon-box c-user"><i class="bi bi-info-circle"></i></div>
        <div class="setting-info">
            <div class="st-title">Information Center</div>
            <div class="st-desc">Information hub management</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>


    <a href="permission-manager.php" class="m3-setting-card shadow-sm">
        <div class="icon-box c-perm"><i class="bi bi-shield-lock"></i></div>
        <div class="setting-info">
            <div class="st-title">Permission Manager</div>
            <div class="st-desc">Define system-wide access rules and levels</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>




    <div class="m3-settings-block">

        <div class="m3-section-title px-3 mt-4">Data Center</div>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_backup_options();">
            <div class="icon-box c-user"><i class="bi bi-hdd-stack"></i></div>
            <div class="setting-info">
                <div class="st-title">Backup Options</div>
                <div class="st-desc">Configure schedules and auto backup</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_backup_data();">
            <div class="icon-box c-user"><i class="bi bi-database-down"></i></div>
            <div class="setting-info">
                <div class="st-title">Backed-up Data</div>
                <div class="st-desc">Browse or download backups</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_restore_data();">
            <div class="icon-box c-user"><i class="bi bi-arrow-counterclockwise"></i></div>
            <div class="setting-info">
                <div class="st-title">Restore Data</div>
                <div class="st-desc">Recover system data</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_data_scanner();">
            <div class="icon-box c-user"><i class="bi bi-search"></i></div>
            <div class="setting-info">
                <div class="st-title">Data Scanner</div>
                <div class="st-desc">Scan tables for issues</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_data_errors();">
            <div class="icon-box c-user"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="setting-info">
                <div class="st-title">Data Errors</div>
                <div class="st-desc">View integrity problems</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_storage_usage();">
            <div class="icon-box c-user"><i class="bi bi-pie-chart"></i></div>
            <div class="setting-info">
                <div class="st-title">Storage Usage</div>
                <div class="st-desc">Database size & stats</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>

        <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_data_logs();">
            <div class="icon-box c-user"><i class="bi bi-journal-text"></i></div>
            <div class="setting-info">
                <div class="st-title">Data Logs</div>
                <div class="st-desc">Track changes & activity</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>


    </div>







</main>

<div style="height: 5px;"></div>
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