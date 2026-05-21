<?php

$page_title = "My Account";
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

?>

<style>
    body {
        background-color: #FAF8FC; /* M3 Light Surface Tint */
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
        font-family: system-ui, -apple-system, sans-serif;
    }

    /* 1. Modern Minimalist Account Hero Banner (No Card / No Shadow) */
    .account-modern-hero {
        background: #FFFFFF;
        padding: 32px 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        border-bottom: 1px solid #ECE6F0;
    }

    .modern-avatar-squircle {
        width: 64px;
        height: 64px;
        border-radius: 20px; /* Material 3 Squircle Metric */
        background: #EADDFF; /* Tonal Purple Container */
        color: #21005D;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
        border: 1px solid #D0BCFF;
    }

    .hero-info-block {
        flex-grow: 1;
    }

    .user-profile-name {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1C1B1F;
        letter-spacing: -0.3px;
        margin-bottom: 4px;
    }

    .user-profile-meta {
        font-size: 0.72rem;
        font-weight: 700;
        color: #6750A4;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* 2. Flat List Core Container Layout */
    .section-lbl {
        font-size: 0.75rem;
        font-weight: 800;
        color: #49454F;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 20px 24px 8px 24px;
        background: #FAF8FC;
    }

    .m3-flat-list-group {
        background: #FFFFFF;
        border-bottom: 1px solid #ECE6F0;
    }

    .m3-list-flat-item {
        display: flex;
        align-items: center;
        padding: 14px 24px;
        background: #FFFFFF;
        border-bottom: 1px solid #F4EFF4;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .m3-list-flat-item:last-child {
        border-bottom: none;
    }

    .m3-list-flat-item:active {
        background-color: #EADDFF; /* M3 State Layer Overprint */
    }

    /* Tonal Icon Containers (No Hard Shadows) */
    .icon-box-flat {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        margin-right: 16px;
        flex-shrink: 0;
    }

    /* Material 3 Soft Tonal Palettes */
    .c-info   { background: #E8DEF8; color: #1D192B; } /* Identity Module */
    .c-util   { background: #E0F2F1; color: #004D40; } /* Communication Module */
    .c-ms     { background: #E0F7FA; color: #006064; } /* Insights Module */
    .c-comm   { background: #E8F5E9; color: #1B5E20; } /* Logs Module */
    .c-secu   { background: #FFF3E0; color: #E65100; } /* Security Module */
    .c-exit   { background: #F9DEDC; color: #410E0B; } /* Destructive Action */

    .item-info-block {
        flex-grow: 1;
        overflow: hidden;
    }

    .st-flat-title {
        font-weight: 700;
        font-size: 0.9rem;
        color: #1C1B1F;
        margin-bottom: 1px;
    }

    .st-flat-desc {
        font-size: 0.72rem;
        color: #79747E;
        font-weight: 500;
    }

    .flat-chevron {
        color: #79747E;
        font-size: 0.95rem;
        opacity: 0.5;
        margin-left: 8px;
    }
</style>

<main class="pb-5">
    
    <!-- 1. MODERN STUDENT/STAFF HERO (CARD-LESS SMART BANNER) -->
    <div class="account-modern-hero">
        <div class="modern-avatar-squircle">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="hero-info-block">
            <div class="user-profile-name"><?php echo $fullname ?? 'Staff Member'; ?></div>
            <div class="user-profile-meta">
                <?php echo $userlevel; ?> <i class="bi bi-dot"></i> ID: <?php echo $userid; ?>
            </div>
        </div>
    </div>

    <!-- 2. PERSONAL IDENTITY MODULE -->
    <div class="section-lbl">Personal Identity</div>
    <div class="m3-flat-list-group">
        
        <div class="m3-list-flat-item" onclick="settings_menu_my_profile();">
            <div class="icon-box-flat c-info"><i class="bi bi-person-circle"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">My Profile</div>
                <div class="st-flat-desc">View or edit your official credentials</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>

        <div class="m3-list-flat-item" onclick="profile_menu_my_attendance_summery();">
            <div class="icon-box-flat c-info"><i class="bi bi-calendar2-check"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Attendance History</div>
                <div class="st-flat-desc">Personal presence and punctuality log</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>

    </div>

    <!-- 3. COMMUNICATION & TASKS MODULE -->
    <div class="section-lbl">Communication & Tasks</div>
    <div class="m3-flat-list-group">

        <div class="m3-list-flat-item" onclick="profile_menu_notifications();">
            <div class="icon-box-flat c-util"><i class="bi bi-bell"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Messages & Notifications</div>
                <div class="st-flat-desc">Recent alerts and system messages</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>

        <div class="m3-list-flat-item" onclick="profile_menu_leave_application();">
            <div class="icon-box-flat c-util"><i class="bi bi-journal-arrow-down"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Leaves & Movement</div>
                <div class="st-flat-desc">Apply for leaves and track status</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>

        <div class="m3-list-flat-item" onclick="profile_menu_offline_manager();">
            <div class="icon-box-flat c-util"><i class="bi bi-cloud-slash"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Offline Hub</div>
                <div class="st-flat-desc">Manage locally cached records and data</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>

    </div>

    <!-- 4. ACTIVITY & INSIGHTS MODULE -->
    <div class="section-lbl">Activity & Insights</div>
    <div class="m3-flat-list-group">

        <div class="m3-list-flat-item" onclick="front_page_manager();" data-bs-toggle="offcanvas" data-bs-target="#managerDrawer">
            <div class="icon-box-flat c-ms"><i class="bi bi-layout-text-window-reverse"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Front Page Manager</div>
                <div class="st-flat-desc">Personalize your dashboard layout and visibility</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-gear-fill text-primary"></i></div>
        </div>

        <div class="m3-list-flat-item" onclick="profile_menu_my_logs();">
            <div class="icon-box-flat c-comm"><i class="bi bi-clock-history"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">My Activity Log</div>
                <div class="st-flat-desc">Trace your recent interactions in the system</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>

        <div class="m3-list-flat-item" onclick="profile_menu_permissions();">
            <div class="icon-box-flat c-comm"><i class="bi bi-shield-check"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Access Permissions</div>
                <div class="st-flat-desc">Check your assigned roles and module access</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>

    </div>

    <!-- 5. SECURITY & PRIVACY MODULE -->
    <div class="section-lbl">Security & Privacy</div>
    <div class="m3-flat-list-group">

        <div class="m3-list-flat-item" onclick="settings_menu_login_method();">
            <div class="icon-box-flat c-secu"><i class="bi bi-shield-lock"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Security Settings</div>
                <div class="st-flat-desc">Manage password and login methods</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>

        <div class="m3-list-flat-item" onclick="settings_menu_logout();">
            <div class="icon-box-flat c-exit"><i class="bi bi-power"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title text-danger">End Session</div>
                <div class="st-flat-desc text-danger opacity-75">Logout securely from this device</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right text-danger"></i></div>
        </div>

    </div>

    <!-- 6. LEGAL & COMPLIANCE MODULE -->
    <div class="section-lbl">Legal & Compliance</div>
    <div class="m3-flat-list-group">

        <div class="m3-list-flat-item" onclick="settings_menu_privacy_policy();">
            <div class="icon-box-flat c-secu"><i class="bi bi-file-earmark-lock"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Privacy Policy</div>
                <div class="st-flat-desc">Review our privacy practices and data handling</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>
        
        <div class="m3-list-flat-item" onclick="settings_menu_terms_conditions();">
            <div class="icon-box-flat c-secu"><i class="bi bi-journal-text"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Terms & Conditions</div>
                <div class="st-flat-desc">Review our terms and conditions of use</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </div>

        <div class="m3-list-flat-item" onclick="settings_account_deletion_policy();">
            <div class="icon-box-flat c-exit"><i class="bi bi-person-x-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title text-danger">Account Deletion</div>
                <div class="st-flat-desc text-danger opacity-75">Learn about your account deletion rights</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right text-danger"></i></div>
        </div>

    </div>
</main>


<?php include 'footer.php'; ?>

<script>
    function settings_menu_my_profile() { window.location.href = "my-profile.php"; }
    function profile_menu_my_attendance_summery() { window.location.href = "my-attnd-summery.php"; }
    function profile_menu_leave_application() { window.location.href = "my-leave-application.php"; }
    function profile_menu_offline_manager() { window.location.href = "offline-manager.php"; }
    function settings_menu_login_method() { window.location.href = "security-settings.php"; }
    function profile_menu_notifications() { window.location.href = "notification.php"; }
    function profile_menu_my_logs() { window.location.href = "my-logs.php"; }
    function profile_menu_permissions() { window.location.href = "my-permissions.php"; }
    function front_page_manager() { window.location.href = "front-page-manager.php"; }
    function settings_account_deletion_policy() { window.location.href = "account-deletion-policy.php"; }
    function settings_menu_privacy_policy() { window.location.href = "privacy-policy.php"; }
    function settings_menu_terms_conditions() { window.location.href = "tc.php"; }

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

