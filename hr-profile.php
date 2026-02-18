<?php
$page_title = "Teacher/Staff Profile";
include 'inc.php';
include 'datam/datam-teacher.php';

// ১. প্যারামিটার হ্যান্ডলিং
$tid = $_GET['id'] ?? '';

// ২. ডাটা ফেচিং
$stmt = $conn->prepare("SELECT * FROM teacher WHERE tid = ? and sccode=? LIMIT 1");
$stmt->bind_param("si", $tid, $sccode);
$stmt->execute();
$result = $stmt->get_result();
$tp = $result->fetch_assoc();
$stmt->close();

if (!$tp) {
    echo "<div class='m3-card c-exit' style='margin:20px; padding:20px; text-align:center; font-weight:800;'>
            <i class='bi bi-exclamation-triangle me-2'></i> Profile Not Found!
          </div>";
    include 'footer.php';
    exit;
}

// ৩. র‍্যাঙ্ক/ডেজিগনেশন লজিক
$designation = $tp['position'] ?? ($tp['ranks'] ?? 'Faculty Member');

// ৪. পারমিশন চেকিং
$profile_entry_permission = 0;
$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
if (isset($settings_map['Profile Entry']) && strpos($settings_map['Profile Entry'], $userlevel) !== false) {
    $profile_entry_permission = 1;
}
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-surface: #FDF7FF;
    }

    body {
        background: var(--m3-surface);
    }

    /* Modern Hero Profile */
    .hero-profile-enhanced {
        background: linear-gradient(135deg, #6750A4 0%, #4527A0 100%);
        color: white;
        padding: 40px 20px 70px;
        border-radius: 0 0 40px 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(103, 80, 164, 0.3);
    }

    .hero-profile-enhanced::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* Avatar System */
    .profile-photo-wrapper {
        width: 100px;
        height: 100px;
        border-radius: 24px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        overflow: hidden;
        background: #eee;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .profile-photo-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Overlay Card */
    .floating-info-card {
        margin: -40px 16px 20px;
        background: white;
        border-radius: 28px;
        padding: 20px;
        border: 1px solid #E7E0EC;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        position: relative;
        z-index: 10;
    }

    .status-pill-active {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 0.6rem;
        font-weight: 900;
        text-transform: uppercase;
        border: 1px solid #C8E6C9;
    }

    /* Quick Action Buttons */
    .action-row {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .action-pill-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #F3EDF7;
        color: var(--m3-primary);
        padding: 12px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 0.8rem;
        text-decoration: none !important;
        border: 1px solid #EADDFF;
    }

    .action-pill-btn:active {
        background: #EADDFF;
        transform: scale(0.97);
    }

    .id-badge-dark {
        background: rgba(0, 0, 0, 0.2);
        color: white;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 800;
        display: inline-block;
        margin-top: 6px;
    }
</style>

<main>
    <div class="hero-profile-enhanced">


        <div class="d-flex align-items-center">
            <div class="profile-photo-wrapper">
                <img src="<?= teacher_profile_image_path($tid) ?>" >
            </div>
            <div class="ms-3 flex-grow-1">
                <h3 class="fw-black m-0" style="letter-spacing: -0.5px;"><?= $tp['tname'] ?></h3>
                <div class="small opacity-90 fw-bold"><?= $tp['tnameb'] ?></div>
                <div class="id-badge-dark">ID: <?= $tid ?></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3 text-right" style="z-index:1250;">
                <?php if ($permission == 3): ?>
                    <button class="btn btn-link p-0 text-white opacity-80" onclick="edit('<?= $tid ?>')">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </button>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <div class="floating-info-card">
        <div class="d-flex align-items-center">
            <div class="icon-box c-inst shadow-sm"
                style="width: 48px; height: 48px; border-radius: 14px; background: #F3EDF7; color: #6750A4;">
                <i class="bi bi-person-workspace fs-4"></i>
            </div>
            <div class="ms-3 flex-grow-1">
                <div class="small fw-black text-muted text-uppercase" style="font-size: 0.6rem; letter-spacing: 1px;">
                    Current Designation</div>
                <div class="fw-bold text-dark"><?= $designation ?></div>
            </div>
            <div class="status-pill-active">Active</div>
        </div>

        <div class="action-row">
            <a href="tel:<?= $tp['mobile'] ?? '' ?>" class="action-pill-btn">
                <i class="bi bi-telephone-fill"></i> CALL
            </a>
            <a href="sms:<?= $tp['mobile'] ?? '' ?>" class="action-pill-btn">
                <i class="bi bi-chat-left-text-fill"></i> SMS
            </a>
            <a href="mailto:<?= $tp['email'] ?? '' ?>" class="action-pill-btn">
                <i class="bi bi-envelope-at-fill"></i> MAIL
            </a>
        </div>
    </div>

    <div class="px-3">
        <div class="profile-content-area">
            <?php
            $user_role_map = [
                'Administrator' => 'hr-profile-administrator.php',
                'Teacher' => 'hr-profile-teacher.php',
                'Student' => 'hr-profile-student.php',
                'guardian' => 'hr-profile-guardian.php'
            ];

            $include_file = ($tid == $userid && $userlevel != 'Administrator') ? ($user_role_map[$userlevel] ?? 'hr-profile-teacher.php') : ($user_role_map[$userlevel] ?? 'hr-profile-teacher.php');

            if (file_exists($include_file)) {
                include $include_file;
            } else {
                echo "<div class='m3-card shadow-sm p-4 text-center' style='border-radius:24px; background:white;'>
                        <i class='bi bi-info-circle fs-3 text-muted opacity-50'></i>
                        <p class='small fw-bold text-muted mt-2'>Loading additional details...</p>
                      </div>";
            }
            ?>
        </div>
    </div>
</main>

<div style="height: 100px;"></div>

<?php include 'footer.php'; ?>

<script>
    function edit(id) {
        window.location.href = "teacher-profile-edit.php?id=" + id;
    }
</script>