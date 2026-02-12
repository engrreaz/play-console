<div id="avatarMenu" class="avatar-dropdown shadow-sm">


    <div class="dd-divider"></div>
    <div class="dd-item text-muted text-center small pb-2 d-block">
        <img src="https://eimbox.com/logo/<?= $sccode ?>.png" style="height:50px;" />
        <div class="dd-item text-muted text-center small pb-2"><?= $scname ?></div>

    </div>

    <?php if ($is_admin > 3) { ?>
        <div class="dd-divider"></div>
        <div class=" d-flex justify-content-between align-items-center mx-4 py-2">
            <i class="dd-item bi bi-shield-fill-check text-success fs-5 p-0 perm" data-perm="3"></i>
            <i class="dd-item bi bi-shield-fill-exclamation text-info fs-5 p-0 perm" data-perm="2"></i>
            <i class="dd-item bi bi-shield-slash-fill text-warning fs-5 p-0 perm" data-perm="1"></i>
            <i class="dd-item bi bi-shield-fill-x text-danger fs-5 p-0 perm" data-perm="0"></i>
        </div>

    <?php } ?>


    <div class="dd-divider"></div>

    <div class="dd-item text-muted small pb-2">Session <span
            class="session-pill py-0 float-end m-0"><?= $sessionyear ?></span></div>
    <div class="dd-divider"></div>


    <?php
    if (isset($drop_down_menu_1) && $drop_down_menu_1 !== '') {
        echo '<div id="drop-down-menu-1 " class="dd-item text-primary" onclick="drop_down_menu_1()">' . $drop_down_menu_1 . '</div>';
        echo ' <div class="dd-divider"></div>';
    }
    if (isset($drop_down_menu_2) && $drop_down_menu_2 !== '') {
        echo '<div id="drop-down-menu-2 " class="dd-item text-primary" onclick="drop_down_menu_2()">' . $drop_down_menu_2 . '</div>';
        echo ' <div class="dd-divider"></div>';
    }
    if (isset($drop_down_menu_3) && $drop_down_menu_3 !== '') {
        echo '<div id="drop-down-menu-3" class="dd-item  text-primary" onclick="drop_down_menu_3()">' . $drop_down_menu_3 . '</div>';
        echo ' <div class="dd-divider"></div>';
    }


    ?>




    <div class="dd-item" onclick="goProfile()"> <i class="bi bi-mortarboard-fill me-2"></i> Institute Profile
    </div>
    <div class="dd-item" onclick="goMy()"> <i class="bi bi-person-circle me-2"></i> My Profile</div>
    <div class="dd-item" onclick="goTicket()"> <i class="bi bi-ticket-perforated me-2"></i>Submit a Ticket</div>
    <div class="dd-item" onclick="goNotify()"> <i class="bi bi-bell me-2"></i>Notifications</div>

    <div class="dd-divider"></div>
    <div class="dd-item" onclick="task_manager()"> <i class="bi bi-list-task me-2"></i>Task Manager</div>
    <div class="dd-divider"></div>
    <div class=" d-flex justify-content-between align-items-center mx-4 py-2">
        <i class="dd-item bi bi-file-earmark-fill text-info fs-5 p-0"></i>
        <!-- <div class="vr"></div> -->
        <i class="dd-item bi bi-youtube text-danger fs-5 p-0" onclick="openVideo('<?php echo $video_id; ?>')"></i>
        <!-- <div class="vr"></div> -->
        <i class="dd-item bi bi-star-fill text-warning fs-5 p-0"></i>
        <i class="dd-item bi bi-info-circle-fill text-primary fs-5 p-0"></i>
    </div>
    <div class="dd-divider"></div>
    <div class="dd-item" onclick="toggleTheme()"> <i class="bi bi-moon-fill me-2"></i>Dark Mode</div>
    <div class="dd-item text-danger" onclick="doLogout()"><i class="bi bi-box-arrow-right me-2"></i>Logout</div>
</div>