<?php
include 'inc.php';
include 'datam/datam-teacher.php';
// ১. প্যারামিটার হ্যান্ডলিং (অপরিবর্তিত)
$tid = $_GET['id'] ?? '';
$page_title = "Staff Profile";



// ২. ডাটা ফেচিং (Secure Prepared Statement)
$stmt = $conn->prepare("SELECT * FROM teacher WHERE tid = ? LIMIT 1");
$stmt->bind_param("s", $tid);
$stmt->execute();
$result = $stmt->get_result();
$tp = $result->fetch_assoc();
$stmt->close();

if (!$tp) {
    die("<div class='m3-card c-exit' style='margin:20px; padding:20px; text-align:center; font-weight:800;'>
            <i class='bi bi-exclamation-triangle me-2'></i> Profile Not Found!
         </div>");
}


$display_photo = teacher_profile_image_path($tid);

// ৪. পারমিশন চেকিং (অপরিবর্তিত)
$profile_entry_permission = 0;
$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
if (isset($settings_map['Profile Entry']) && strpos($settings_map['Profile Entry'], $userlevel) !== false) {
    $profile_entry_permission = 1;
}
?>

<style>
    /* Profile Specific Enhancements */
    .hero-profile {
        padding-bottom: 35px;
        margin-bottom: 0;
        border-radius: 0 0 24px 24px;
    }

    .profile-avatar-box {
        width: 84px;
        height: 84px;
        border-radius: 12px;
        /* M3 Large Corner */
        border: 3px solid rgba(255, 255, 255, 0.3);
        overflow: hidden;
        background: #eee;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .profile-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .id-tag {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1px;
        display: inline-block;
        margin-top: 8px;
        backdrop-filter: blur(5px);
    }

    .info-overlay-card {
        margin: -25px 16px 20px;
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.08);
    }
</style>

<main>
    <div class="hero-container hero-profile">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">

            <?php if ($profile_entry_permission): ?>
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;"
                    onclick="edit('<?php echo $tid; ?>')">
                    <i class="bi bi-pencil-square"></i>
                </div>
            <?php endif; ?>
        </div>

        <div style="display: flex; align-items: center; margin-top: 20px;">
            <div class="profile-avatar-box">
                <img src="<?php echo teacher_profile_image_path($tid); ?>">
            </div>
            <div style="margin-left: 18px; overflow: hidden;">
                <div style="font-size: 1.4rem; font-weight: 900; line-height: 1.2; text-transform: uppercase;">
                    <?php echo $tp['tname']; ?>
                </div>
                <div style="font-size: 1rem; opacity: 0.9; font-weight: 600;"><?php echo $tp['tnameb']; ?></div>
                <div class="id-tag">STAFF ID: <?php echo $tp['tid']; ?></div>
            </div>
        </div>
    </div>

    <div class="info-overlay-card">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="icon-box c-inst" style="width: 44px; height: 44px; border-radius: 8px;">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="flex-grow-1">
                <div style="font-size: 0.65rem; font-weight: 800; color: #777; text-transform: uppercase;">Designation
                </div>
                <div style="font-size: 0.95rem; font-weight: 800; color: var(--m3-primary);">
                    <?php echo $tp['ranks'] ?? 'Faculty Member'; ?>
                </div>
            </div>
            <div style="text-align: right;">
                <span class="session-pill" style="font-size: 0.6rem;">ACTIVE</span>
            </div>
        </div>
    </div>

    <div class="profile-content-area" style="padding: 0 4px;">
        <?php
        $user_role_map = [
            'Administrator' => 'hr-profile-administrator.php',
            'Teacher' => 'hr-profile-teacher.php',
            'Student' => 'hr-profile-student.php',
            'guardian' => 'hr-profile-guardian.php'
        ];

        // লোড করা হচ্ছে ইউজার লেভেল অনুযায়ী ফাইল
        $include_file = ($tid == $userid && $userlevel != 'Administrator') ? $user_role_map[$userlevel] : ($user_role_map[$userlevel] ?? 'hr-profile-teacher.php');

        if (file_exists($include_file)) {
            include $include_file;
        } else {
            echo "<div class='m3-card c-util' style='margin:12px; padding:15px; font-size:0.8rem;'>
                    <i class='bi bi-info-circle me-2'></i> Additional details are loading...
                  </div>";
        }
        ?>
    </div>
</main>

<div style="height: 80px;"></div>

<script>
    function edit(id) {
        window.location.href = "teacher-profile-edit.php?id=" + id;
    }

    // আপনার অন্যান্য AJAX ফাংশনগুলো এখানে থাকবে
</script>

<?php include 'footer.php'; ?>