<?php
include 'inc.php'; // header.php এবং কানেকশন লোড করবে
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Category Header Style */
    .m3-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6750A4;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin: 24px 16px 8px 16px;
    }

    /* M3 List Item / Card Style */
    .m3-setting-card {
        background-color: #FFFFFF;
        border: none;
        border-radius: 20px;
        padding: 12px 16px;
        margin: 0 12px 8px 12px;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .m3-setting-card:active {
        background-color: #EADDFF; /* M3 Primary Container on touch */
        transform: scale(0.98);
    }

    /* Icon Container with Tonal Color */
    .m3-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        flex-shrink: 0;
    }

    /* Category Specific Colors */
    .bg-inst { background-color: #F3EDF7; color: #6750A4; }
    .bg-acad { background-color: #E3F2FD; color: #1976D2; }
    .bg-fina { background-color: #E8F5E9; color: #2E7D32; }
    .bg-user { background-color: #FFF3E0; color: #E65100; }
    .bg-dev  { background-color: #FCE4EC; color: #C2185B; }

    .setting-title { font-weight: 700; color: #1C1B1F; font-size: 0.95rem; margin-bottom: 0; }
    .setting-desc { font-size: 0.75rem; color: #49454F; line-height: 1.3; }

    .m3-app-bar {
        background: #fff;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="index.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <h4 class="fw-bold mb-0">System Settings</h4>
        </div>
    </div>

    <div class="m3-section-title">Institution Setup</div>
    
    <div class="m3-setting-card shadow-sm" onclick="settings_admin_ins_info();">
        <div class="m3-icon-box bg-inst"><i class="bi bi-bank2 fs-4"></i></div>
        <div class="flex-grow-1">
            <h6 class="setting-title">Institution Information</h6>
            <div class="setting-desc">Identity, logo, address and contact info</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-setting-card shadow-sm" onclick="settings_admin_add_edit_teacher();">
        <div class="m3-icon-box bg-inst"><i class="bi bi-person-workspace fs-4"></i></div>
        <div class="flex-grow-1">
            <h6 class="setting-title">Teachers & Staffs</h6>
            <h6 class="setting-desc">Add, edit and manage employee profiles</h6>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-section-title">Academic Management</div>

    <div class="m3-setting-card shadow-sm" onclick="settings_admin_cls_sec();">
        <div class="m3-icon-box bg-acad"><i class="bi bi-diagram-3-fill fs-4"></i></div>
        <div class="flex-grow-1">
            <h6 class="setting-title">Class & Section Manager</h6>
            <div class="setting-desc">Structure and class-wise settings</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-setting-card shadow-sm" onclick="settings_admin_subject_setup();">
        <div class="m3-icon-box bg-acad"><i class="bi bi-book-half fs-4"></i></div>
        <div class="flex-grow-1">
            <h6 class="setting-title">Subjects Manager</h6>
            <div class="setting-desc">Subject list and mark distributions</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-setting-card shadow-sm" onclick="settings_admin_class_routine_setup();">
        <div class="m3-icon-box bg-acad"><i class="bi bi-clock-history fs-4"></i></div>
        <div class="flex-grow-1">
            <h6 class="setting-title">Routine Setup</h6>
            <div class="setting-desc">Period management and teacher binding</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-section-title">Students & Finance</div>

    <div class="m3-setting-card shadow-sm" onclick="settings_admin_st_id_generate();">
        <div class="m3-icon-box bg-fina"><i class="bi bi-person-vcard-fill fs-4"></i></div>
        <div class="flex-grow-1">
            <h6 class="setting-title">Student ID Generator</h6>
            <div class="setting-desc">Auto-generate IDs for profile creation</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-setting-card shadow-sm" onclick="settings_admin_st_id_payment_indivisula();">
        <div class="m3-icon-box bg-fina"><i class="bi bi-coin fs-4"></i></div>
        <div class="flex-grow-1">
            <h6 class="setting-title">Payment Setup (Individual)</h6>
            <div class="setting-desc">Special fee configurations per student</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-section-title">System & Access</div>

    <div class="m3-setting-card shadow-sm" onclick="settings_sms_menu();">
        <div class="m3-icon-box bg-user"><i class="bi bi-chat-right-dots-fill fs-4"></i></div>
        <div class="flex-grow-1">
            <h6 class="setting-title">SMS Manager</h6>
            <div class="setting-desc">Templates, audience and history</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-setting-card shadow-sm" onclick="settings_admin_user_manager();">
        <div class="m3-icon-box bg-user"><i class="bi bi-person-lock fs-4"></i></div>
        <div class="flex-grow-1">
            <h6 class="setting-title">User Account Manager</h6>
            <div class="setting-desc">Access control and permission levels</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <?php if ($usr == 'engrreaz@gmail.com'): ?>
    <div class="m3-section-title">Developer Console</div>
    <div class="mx-3 p-3 rounded-4 bg-light border border-danger-subtle">
        <div class="row g-2">
            <div class="col-6"><a href="promotion.php" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Promotion</a></div>
            <div class="col-6"><a href="studentadmission.php" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Admission</a></div>
            <div class="col-6"><a href="cashbookview.php" class="btn btn-outline-dark btn-sm w-100 rounded-pill">Cashbook</a></div>
            <div class="col-6"><a href="trackreport.php" class="btn btn-outline-dark btn-sm w-100 rounded-pill">Tracking</a></div>
        </div>
    </div>
    <?php endif; ?>

</main>

<div style="height:70px;"></div>

<script>
    // Navigation functions (আপনার আগের ফাংশন নামগুলো ঠিক রাখা হয়েছে)
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