<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন হ্যান্ডলিং (প্রয়োজনীয় ক্ষেত্রে সেশন ইয়ার যোগ করা যেতে পারে)
$current_session = $_GET['year'] ?? $_COOKIE['query-session'] ?? $sy;

$page_title = "My Account";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width Top App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; height: 56px; background: #fff; display: flex; align-items: center; 
        padding: 0 16px; position: sticky; top: 0; z-index: 1050; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Profile Hero Card (8px Radius) */
    .account-hero {
        background-color: #F3EDF7; border-radius: 8px; padding: 16px;
        margin: 12px; display: flex; align-items: center;
        border: 1px solid #EADDFF; box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .avatar-circle {
        width: 52px; height: 52px; border-radius: 8px; /* গাইডলাইন অনুযায়ী ৮ পিক্সেল */
        background-color: #EADDFF; color: #21005D;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin-right: 14px; flex-shrink: 0;
    }

    /* Condensed Setting Item (8px Radius) */
    .m3-list-item {
        background-color: #fff; border-radius: 8px; padding: 12px 14px;
        margin: 0 12px 6px; display: flex; align-items: center;
        border: 1px solid #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: transform 0.15s ease, background-color 0.15s;
        cursor: pointer;
    }
    .m3-list-item:active { transform: scale(0.98); background-color: #F7F2FA; }

    /* Icon Tonal Box (8px Radius) */
    .icon-box {
        width: 40px; height: 40px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 14px; flex-shrink: 0; font-size: 1.2rem;
    }

    /* Category Specific Tonal Colors */
    .c-info { background: #F3EDF7; color: #6750A4; }
    .c-util { background: #E8F5E9; color: #146C32; }
    .c-secu { background: #FFF3E0; color: #E46C0A; }
    .c-exit { background: #FFEBEE; color: #B3261E; }

    .item-info { flex-grow: 1; overflow: hidden; }
    .st-title { font-weight: 700; color: #1C1B1F; font-size: 0.9rem; margin-bottom: 0; }
    .st-desc { font-size: 0.7rem; color: #49454F; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .section-lbl {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 20px 0 8px 16px; letter-spacing: 0.8px;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="index.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons"><i class="bi bi-gear-wide-connected text-muted fs-5"></i></div>
</header>

<main class="pb-5 mt-2">
    <div class="account-hero shadow-sm">
        <div class="avatar-circle">
            <i class="bi bi-person-fill"></i>
        </div>
        <div>
            <div class="fw-bold text-dark" style="font-size: 1rem;"><?php echo $fullname ?? 'Staff Member'; ?></div>
            <div class="small text-muted" style="font-weight: 500;"><?php echo $userlevel; ?> | ID: <?php echo $userid; ?></div>
        </div>
    </div>

    <div class="section-lbl">Personal Identity</div>

    <div class="m3-list-item shadow-sm" onclick="settings_menu_my_profile();">
        <div class="icon-box c-info"><i class="bi bi-person-circle"></i></div>
        <div class="item-info">
            <div class="st-title">My Profile</div>
            <div class="st-desc">View or edit your official credentials</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </div>

    <div class="m3-list-item shadow-sm" onclick="profile_menu_my_attendance_summery();">
        <div class="icon-box c-info"><i class="bi bi-calendar2-check"></i></div>
        <div class="item-info">
            <div class="st-title">Attendance History</div>
            <div class="st-desc">Personal presence and punctuality log</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </div>

    <div class="section-lbl">Administrative Tools</div>

    <div class="m3-list-item shadow-sm" onclick="profile_menu_leave_application();">
        <div class="icon-box c-util"><i class="bi bi-journal-arrow-down"></i></div>
        <div class="item-info">
            <div class="st-title">Leaves & Movement</div>
            <div class="st-desc">Apply for leaves and track status</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </div>

    <div class="m3-list-item shadow-sm" onclick="profile_menu_offline_manager();">
        <div class="icon-box c-util"><i class="bi bi-cloud-slash"></i></div>
        <div class="item-info">
            <div class="st-title">Offline Hub</div>
            <div class="st-desc">Manage locally cached records and data</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </div>

    <div class="section-lbl">Security & Access</div>

    <div class="m3-list-item shadow-sm" onclick="settings_menu_login_method();">
        <div class="icon-box c-secu"><i class="bi bi-shield-lock"></i></div>
        <div class="item-info">
            <div class="st-title">Security Settings</div>
            <div class="st-desc">Manage password and login methods</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </div>

    <div class="m3-list-item shadow-sm" onclick="settings_menu_logout();">
        <div class="icon-box c-exit"><i class="bi bi-power"></i></div>
        <div class="item-info">
            <div class="st-title text-danger">End Session</div>
            <div class="st-desc text-danger opacity-75">Logout securely from this device</div>
        </div>
        <i class="bi bi-chevron-right text-danger opacity-25"></i>
    </div>

</main>

<div style="height: 75px;"></div> <script>
    function settings_menu_my_profile() { window.location.href = "my-profile.php"; }
    function profile_menu_my_attendance_summery() { window.location.href = "my-attnd-summery.php"; }
    function profile_menu_leave_application() { window.location.href = "my-leave-application.php"; }
    function profile_menu_offline_manager() { window.location.href = "offline-manager.php"; }
    function settings_menu_login_method() { window.location.href = "security-settings.php"; }
    
    function settings_menu_logout() {
        Swal.fire({
            title: 'End Session?',
            text: "Are you sure you want to logout now?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            cancelButtonColor: '#79747E',
            confirmButtonText: 'Yes, Logout'
        }).then((result) => {
            if (result.isConfirmed) { window.location.href = "logout.php"; }
        });
    }
</script>

<?php include 'footer.php'; ?>