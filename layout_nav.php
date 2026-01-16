<?php
// This is the navigation drawer for our new mobile-first layout.
// It contains the menu links for the application.
?>
<aside class="nav-drawer" id="nav-drawer">
    <div class="nav-drawer-header">
        <!-- User info will go here -->
        <div class="d-flex align-items-center">
            <img src="<?php echo $photourl ?? 'iimg/logo.png'; ?>" alt="User" class="rounded-circle" width="50" height="50">
            <div class="ms-3">
                <h5 class="mb-0"><?php echo $fullname ?? 'User'; ?></h5>
                <span class="small"><?php echo $userlevel ?? 'Role'; ?></span>
            </div>
        </div>
    </div>
    <div class="nav-drawer-body">
        <a href="index.php" class="nav-link">
            <i class="bi bi-house-door-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="my-profile-setup.php" class="nav-link">
            <i class="bi bi-person-fill"></i>
            <span>My Profile</span>
        </a>
        <a href="stattnd.php" class="nav-link">
            <i class="bi bi-calendar-check-fill"></i>
            <span>Attendance</span>
        </a>
        <a href="finacc.php" class="nav-link">
            <i class="bi bi-wallet-fill"></i>
            <span>Accounts</span>
        </a>
        <a href="resultprocess.php" class="nav-link">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Results</span>
        </a>
        <a href="settings.php" class="nav-link">
            <i class="bi bi-gear-fill"></i>
            <span>Settings</span>
        </a>
        <hr class="mx-3">
        <a href="sout.php" class="nav-link">
            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
