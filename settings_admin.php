<?php
include 'inc.php'; // header.php এবং কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

$page_title = "System Settings";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width M3 Top Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Condensed Category Labels */
    .m3-cat-label {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 20px 0 8px 16px; letter-spacing: 0.8px;
    }

    /* M3 Setting Card (8px Radius) */
    .m3-setting-card {
        background: #fff; border-radius: 8px; padding: 12px;
        margin: 0 12px 6px; border: 1px solid #f0f0f0;
        display: flex; align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: transform 0.15s ease, background 0.15s;
        text-decoration: none !important; color: inherit;
    }
    .m3-setting-card:active { transform: scale(0.98); background-color: #F3EDF7; }

    /* Tonal Icon Container (8px Radius) */
    .icon-box {
        width: 44px; height: 44px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 14px; flex-shrink: 0; font-size: 1.3rem;
    }

    /* Category Specific Tonal Colors */
    .c-inst { background: #F3EDF7; color: #6750A4; }
    .c-acad { background: #E3F2FD; color: #1976D2; }
    .c-fina { background: #E8F5E9; color: #2E7D32; }
    .c-user { background: #FFF3E0; color: #E65100; }

    .setting-info { flex-grow: 1; overflow: hidden; }
    .st-title { font-weight: 700; color: #1C1B1F; font-size: 0.9rem; margin-bottom: 0; }
    .st-desc { font-size: 0.7rem; color: #49454F; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Dev Console Grid */
    .dev-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; padding: 0 12px; }
    .btn-dev { 
        border-radius: 8px; font-size: 0.75rem; font-weight: 700; 
        padding: 10px; border: 1px solid #F9DEDC; background: #fff;
        color: #B3261E; text-decoration: none; text-align: center;
    }
    .btn-dev:active { background: #FFEBEE; }

    .session-badge {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="index.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="session-badge"><?php echo $current_session; ?></span>
    </div>
</header>

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

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_class_routine_setup();">
        <div class="icon-box c-acad"><i class="bi bi-clock-history"></i></div>
        <div class="setting-info">
            <div class="st-title">Time Table</div>
            <div class="st-desc">Period management and routine setup</div>
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

    <a href="javascript:void(0);" class="m3-setting-card shadow-sm" onclick="settings_admin_st_id_payment_indivisula();">
        <div class="icon-box c-fina"><i class="bi bi-coin"></i></div>
        <div class="setting-info">
            <div class="st-title">Fee Configuration</div>
            <div class="st-desc">Custom payments and special waivers</div>
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

    <?php if ($usr == 'engrreaz@gmail.com'): ?>
    <div class="m3-cat-label">Developer Console</div>
    <div class="dev-grid">
        <a href="promotion.php" class="btn-dev shadow-sm">PROMOTION</a>
        <a href="studentadmission.php" class="btn-dev shadow-sm">ADMISSION</a>
        <a href="cashbookview.php" class="btn-dev shadow-sm">CASHBOOK</a>
        <a href="trackreport.php" class="btn-dev shadow-sm">TRACKING</a>
    </div>
    <?php endif; ?>

</main>

<div style="height: 75px;"></div> <script>
    function settings_admin_ins_info() { window.location.href = "settings-institute-info.php"; }
    function settings_admin_add_edit_teacher() { window.location.href = "settingsteacher.php"; }
    function settings_admin_cls_sec() { window.location.href = "settings-class.php"; }
    function settings_admin_st_id_generate() { window.location.href = "st-id-gen.php"; }
    function settings_admin_st_id_payment_indivisula() { window.location.href = "st-payment-setup-indivisual.php"; }
    function settings_admin_subject_setup() { window.location.href = "settings-subject.php"; }
    function settings_admin_class_routine_setup() { window.location.href = "clsroutine-setup.php"; }
    function settings_sms_menu() { window.location.href = "sms-manager.php"; }
    function settings_admin_user_manager() { window.location.href = "user-manager.php"; }
</script>

<?php include 'footer.php'; ?>