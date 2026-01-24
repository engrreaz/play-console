<?php

$page_title = "My Account";
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে


?>




<main class="pb-5 mt-2">
    <div class="account-hero shadow-sm">
        <div class="avatar-circle">
            <i class="bi bi-person-fill"></i>
        </div>
        <div>
            <div class="fw-bold text-dark" style="font-size: 1rem;"><?php echo $fullname ?? 'Staff Member'; ?></div>
            <div class="small text-muted" style="font-weight: 500;"><?php echo $userlevel; ?> | ID:
                <?php echo $userid; ?></div>
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

<div style="height: 75px;"></div>
<script>
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