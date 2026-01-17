<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. প্যারামিটার হ্যান্ডলিং
$tid = $_GET['id'] ?? '';
$page_title = "Staff Profile";

// ২. ডাটা ফেচিং (Prepared Statement)
$stmt = $conn->prepare("SELECT * FROM teacher WHERE tid = ? LIMIT 1");
$stmt->bind_param("s", $tid);
$stmt->execute();
$result = $stmt->get_result();
$tp = $result->fetch_assoc();
$stmt->close();

if (!$tp) { die("<div class='alert alert-danger m-3'>Teacher profile not found.</div>"); }

// ৩. ফটো লজিক
$pth = '../teacher/' . $tid . '.jpg';
if (file_exists($pth)) {
    $display_photo = 'https://eimbox.com/teacher/' . $tid . '.jpg';
} else {
    $display_photo = 'https://eimbox.com/teacher/noimg.jpg';
}

// ৪. পারমিশন চেকিং (Profile Entry)
$profile_entry_permission = 0;
$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
if (isset($settings_map['Profile Entry']) && strpos($settings_map['Profile Entry'], $userlevel) !== false) {
    $profile_entry_permission = 1;
}
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* M3 Standard App Bar (8px radius bottom) */
    .m3-app-bar {
        background: #fff; height: 56px; display: flex; align-items: center; padding: 0 16px;
        position: sticky; top: 0; z-index: 1050; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Condensed Hero Profile Card */
    .hero-profile-card {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 8px; padding: 20px 16px;
        margin: 12px 12px 8px; color: white;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.2);
    }

    .profile-avatar {
        width: 72px; height: 72px; border-radius: 50%;
        object-fit: cover; border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .t-name-eng { font-size: 1.1rem; font-weight: 800; line-height: 1.2; }
    .t-name-ben { font-size: 0.9rem; opacity: 0.9; font-weight: 500; }
    .t-id-badge {
        background: rgba(255, 255, 255, 0.2); border-radius: 6px;
        padding: 2px 8px; font-size: 0.75rem; font-weight: 700;
        display: inline-block; margin-top: 6px;
    }

    /* Section Container for Dynamic Includes */
    .profile-content { padding: 0 4px; }
    
    .btn-m3 { border-radius: 8px !important; font-weight: 700; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="javascript:history.back()" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <?php if($profile_entry_permission): ?>
            <i class="bi bi-pencil-square fs-5" onclick="edit('<?php echo $tid; ?>')"></i>
        <?php endif; ?>
    </div>
</header>

<main class="pb-5">
    <div class="hero-profile-card shadow">
        <div class="d-flex align-items-center">
            <img src="<?php echo $display_photo; ?>" class="profile-avatar me-3" onerror="this.src='https://eimbox.com/teacher/noimg.jpg';">
            <div class="overflow-hidden">
                <div class="t-name-eng text-truncate"><?php echo $tp['tname']; ?></div>
                <div class="t-name-ben text-truncate"><?php echo $tp['tnameb']; ?></div>
                <div class="t-id-badge">STAFF ID: <?php echo $tp['tid']; ?></div>
            </div>
        </div>
    </div>

    <div class="profile-content">
        <?php
        if ($userlevel == 'Administrator' || $tid == $userid) {
            include 'hr-profile-administrator.php';
        } else if ($userlevel == 'Teacher') {
            include 'hr-profile-teacher.php';
        } else if ($userlevel == 'Student') {
            include 'hr-profile-student.php';
        } else if ($userlevel == 'guardian') { // Fixed typo from 'guardina'
            include 'hr-profile-guardian.php';
        }
        ?>
    </div>
</main>

<div style="height: 65px;"></div> <script>
    function edit(id) {
        window.location.href = "teacher-profile-edit.php?id=" + id;
    }

    // আপনার অন্যান্য AJAX ফাংশনগুলো এখানে থাকবে
    function tcamount() {
        const cause = document.getElementById("cause").value;
        const taka = document.getElementById("taka").value;
        // AJAX logic...
    }
</script>

<?php include 'footer.php'; ?>