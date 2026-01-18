<?php
// ইউজার লগিন না থাকলে রিডাইরেক্ট
if (empty($usr) || $userlevel == 'Guest') {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
?>

<div class="bottom-nav-container noprint">
    <div class="bottom-nav">
        <?php if (in_array($userlevel, ['Head Teacher', 'Asstt. Head Teacher', 'Administrator', 'Super Administrator'])): ?>
            <a href="index.php" class="nav-item">
                <i class="bi bi-house-fill"></i>
                <span>Home</span>
            </a>
            <a href="reporthome.php" class="nav-item">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Reports</span>
            </a>
            <a href="tools.php" class="nav-item">
                <i class="bi bi-grid-fill"></i>
                <span>Tools</span>
            </a>
            <a href="settings_admin.php" class="nav-item">
                <i class="bi bi-gear-fill"></i>
                <span>Settings</span>
            </a>
            <a href="build.php" class="nav-item">
                <i class="bi bi-person-circle"></i>
                <span>Profile</span>
            </a>

        <?php elseif (in_array($userlevel, ['Teacher', 'Asstt. Teacher', 'Class Teacher'])): ?>
            <a href="index.php" class="nav-item">
                <i class="bi bi-house-fill"></i>
                <span>Home</span>
            </a>
            <a href="reporthome.php" class="nav-item">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Academic</span>
            </a>
            <a href="tools.php" class="nav-item">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Tools</span>
            </a>
            <a href="build.php" class="nav-item">
                <i class="bi bi-person-circle"></i>
                <span>Profile</span>
            </a>

        <?php elseif ($userlevel == "Student" || $userlevel == "Guardian"): ?>
            <a href="index.php" class="nav-item">
                <i class="bi bi-house-fill"></i>
                <span>Home</span>
            </a>
            <a href="my-profile.php" class="nav-item">
                <i class="bi bi-person-fill"></i>
                <span>Profile</span>
            </a>
            <a href="globalsetting.php" class="nav-item">
                <i class="bi bi-sliders"></i>
                <span>Settings</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<style>
    :root {
        --nav-bg: #ffffff;
        --nav-primary: #6750A4;
        /* Material M3 Primary */
        --nav-secondary: #79747E;
    }

    /* বটম বার ফিক্সড পজিশন */
    .bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--nav-bg);
        box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.08);
        padding-bottom: env(safe-area-inset-bottom);
        /* আইফোনের জন্য */
        z-index: 9999;
        border-top: 1px solid #eee;
    }

    .bottom-nav {
        display: flex;
        justify-content: space-around;
        align-items: center;
        height: 65px;
        max-width: 600px;
        margin: 0 auto;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: var(--nav-secondary);
        transition: all 0.2s ease;
        flex: 1;
    }

    .nav-item i {
        font-size: 22px;
        margin-bottom: 2px;
    }

    .nav-item span {
        font-size: 11px;
        font-weight: 500;
    }

    .nav-item:active,
    .nav-item.active {
        color: var(--nav-primary);
        transform: scale(0.95);
    }

    /* বডি গ্যাপ যাতে কন্টেন্ট ঢাকা না পড়ে */
    body {
        padding-bottom: 75px !important;
    }
</style>















<script>
function toggleAvatarMenu(){
    const m = document.getElementById("avatarMenu");
    m.style.display = (m.style.display === "block") ? "none" : "block";
}

document.addEventListener("click", e=>{
    if(!e.target.closest(".top-avatar")) {
        document.getElementById("avatarMenu").style.display="none";
    }
});

function goProfile(){ location.href="institute_profile.php"; }
function goMy(){ location.href="my_profile.php"; }
function goTicket(){ location.href="support_ticket.php"; }
function goNotify(){ location.href="notifications.php"; }

function doLogout(){
    if(confirm("Logout now?")){
        location.href="logout.php";
    }
}

function toggleTheme(){
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("theme", 
        document.body.classList.contains("dark-mode") ? "dark":"light");
}

// auto apply theme
if(localStorage.getItem("theme")==="dark"){
    document.body.classList.add("dark-mode");
}
</script>
