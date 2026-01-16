<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* M3 App Bar Style */
    .m3-app-bar {
        background-color: #FFFFFF;
        padding: 20px 16px;
        border-radius: 0 0 28px 28px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    /* Account Profile Card (Top Area) */
    .account-hero {
        background-color: #F3EDF7;
        border-radius: 24px;
        padding: 20px;
        margin: 16px;
        display: flex;
        align-items: center;
    }
    .avatar-box {
        width: 64px; height: 64px;
        border-radius: 50%;
        background-color: #EADDFF;
        color: #21005D;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-right: 16px;
    }

    /* M3 List Item Style */
    .m3-list-item {
        background-color: #FFFFFF;
        border: none;
        border-radius: 20px;
        padding: 12px 16px;
        margin: 0 16px 8px 16px;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .m3-list-item:active {
        background-color: #EADDFF;
        transform: scale(0.98);
    }

    /* Icon Box with Tonal Background */
    .item-icon-box {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        flex-shrink: 0;
    }

    /* Category-specific Colors */
    .bg-account  { background-color: #F3EDF7; color: #6750A4; }
    .bg-attnd    { background-color: #E3F2FD; color: #1976D2; }
    .bg-leave    { background-color: #FFF3E0; color: #E65100; }
    .bg-offline  { background-color: #E8F5E9; color: #2E7D32; }
    .bg-security { background-color: #FCE4EC; color: #C2185B; }
    .bg-logout   { background-color: #FFEBEE; color: #D32F2F; }

    .item-title { font-weight: 700; color: #1C1B1F; font-size: 0.95rem; margin-bottom: 0; }
    .item-desc { font-size: 0.75rem; color: #49454F; line-height: 1.3; }

    .section-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6750A4;
        margin: 24px 24px 8px 30px;
        letter-spacing: 1px;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="index.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <h4 class="fw-bold mb-0">My Account</h4>
        </div>
    </div>

    <div class="account-hero shadow-sm">
        <div class="avatar-box">
            <i class="bi bi-person-fill"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0 text-dark"><?php echo $fullname ?? 'Welcome Back'; ?></h5>
            <div class="small text-muted"><?php echo $userlevel; ?> | EIMBox ID: <?php echo $userid; ?></div>
        </div>
    </div>

    <div class="section-label">Personal Information</div>

    <div class="m3-list-item shadow-sm" onclick="settings_menu_my_profile();">
        <div class="item-icon-box bg-account"><i class="bi bi-person-circle fs-5"></i></div>
        <div class="flex-grow-1">
            <h6 class="item-title">My Profile</h6>
            <div class="item-desc">Edit or update your professional identity</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-list-item shadow-sm" onclick="profile_menu_my_attendance_summery();">
        <div class="item-icon-box bg-attnd"><i class="bi bi-fingerprint fs-5"></i></div>
        <div class="flex-grow-1">
            <h6 class="item-title">My Attendance</h6>
            <div class="item-desc">Monthly and daily attendance history</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-list-item shadow-sm" onclick="profile_menu_leave_application();">
        <div class="item-icon-box bg-leave"><i class="bi bi-box-arrow-down-left fs-5"></i></div>
        <div class="flex-grow-1">
            <h6 class="item-title">Leaves & Movement</h6>
            <div class="item-desc">Manage leave requests and off-site logs</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="section-label">Utilities & Tools</div>

    <div class="m3-list-item shadow-sm" onclick="profile_menu_offline_manager();">
        <div class="item-icon-box bg-offline"><i class="bi bi-cloud-slash-fill fs-5"></i></div>
        <div class="flex-grow-1">
            <h6 class="item-title">Offline Manager</h6>
            <div class="item-desc">Sync and manage locally stored data</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="section-label">Account Security</div>

    <div class="m3-list-item shadow-sm" onclick="settings_menu_login_method();">
        <div class="item-icon-box bg-security"><i class="bi bi-shield-lock-fill fs-5"></i></div>
        <div class="flex-grow-1">
            <h6 class="item-title">Security Settings</h6>
            <div class="item-desc">Password and login authentication methods</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="m3-list-item shadow-sm" onclick="settings_menu_logout();">
        <div class="item-box bg-logout item-icon-box"><i class="bi bi-box-arrow-left fs-5"></i></div>
        <div class="flex-grow-1">
            <h6 class="item-title text-danger">Logout Account</h6>
            <div class="item-desc text-danger opacity-75">Securely end your current session</div>
        </div>
        <i class="bi bi-chevron-right text-danger small"></i>
    </div>

</main>

<div style="height: 70px;"></div>

<script>
    // Navigation Functions (আপনার আগের ফাংশন নামগুলো অপরিবর্তিত)
    function settings_menu_my_profile() { window.location.href = "my-profile.php"; }
    function profile_menu_my_attendance_summery() { window.location.href = "my-attnd-summery.php"; }
    function profile_menu_leave_application() { window.location.href = "my-leave-application.php"; }
    function profile_menu_offline_manager() { window.location.href = "offline-manager.php"; }
    function settings_menu_login_method() { window.location.href = "security-settings.php"; }
    
    function settings_menu_logout() {
        Swal.fire({
            title: 'Logout?',
            text: "Are you sure you want to end your session?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D32F2F',
            cancelButtonColor: '#79747E',
            confirmButtonText: 'Yes, Logout'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php";
            }
        });
    }
</script>

<?php include 'footer.php'; ?>