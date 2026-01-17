<?php
include 'inc.php';
include 'datam/datam-teacher.php';

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

$page_title = "Teachers & Staff";
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

    /* Hero Summary Card */
    .hero-stats {
        background: #F3EDF7; border-radius: 8px;
        padding: 12px; margin: 8px 12px;
        display: flex; justify-content: center; align-items: center;
        text-align: center; border: 1px solid #EADDFF;
    }
    .stat-val { font-size: 1.4rem; font-weight: 800; color: #6750A4; margin-right: 8px; }
    .stat-lbl { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #49454F; letter-spacing: 0.5px; }

    /* Condensed Teacher Card (8px Radius) */
    .teacher-card {
        background-color: #FFFFFF; border-radius: 8px;
        padding: 10px 12px; margin: 0 8px 6px;
        border: 1px solid #eee; display: flex; align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: transform 0.2s, background-color 0.2s;
        cursor: pointer;
    }
    .teacher-card:active { transform: scale(0.98); background-color: #F7F2FA; }

    .teacher-avatar {
        width: 52px; height: 52px; border-radius: 6px;
        object-fit: cover; background: #eee;
        margin-right: 14px; border: 1px solid #E7E0EC;
    }

    .info-box { flex-grow: 1; overflow: hidden; }
    .t-name-eng { font-weight: 700; color: #1C1B1F; font-size: 0.9rem; margin-bottom: 0; line-height: 1.2; }
    .t-name-ben { font-size: 0.8rem; color: #6750A4; font-weight: 600; }
    .t-meta { font-size: 0.7rem; color: #49454F; margin-top: 2px; }

    .id-badge {
        background: #EADDFF; color: #21005D;
        padding: 2px 8px; border-radius: 4px;
        font-size: 0.65rem; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <i class="bi bi-search fs-5"></i>
    </div>
</header>

<main class="pb-5 mt-2">
    <div class="hero-stats shadow-sm">
        <span class="stat-val" id="cnt_display">0</span>
        <span class="stat-lbl">Active Staff Members</span>
    </div>

    <div class="list-container">
        <?php
        $cnt = 0;
        foreach ($datam_teacher_profile as $teacher) {
            $tid = $teacher["tid"];
            $status = $teacher["status"] ?? 1; // ডিফল্ট ১ ধরা হয়েছে

            // ইনএকটিভ স্টাফদের জন্য কার্ডের স্টাইল পরিবর্তন
            $card_opacity = ($status == 0) ? 'opacity: 0.6; filter: grayscale(1);' : '';

            // ফটো লজিক
            $photo_path = $BASE_PATH_URL . 'teacher/' . $tid . ".jpg";
            if (!file_exists($photo_path)) {
                $display_photo = "https://eimbox.com/teacher/no-img.jpg";
            } else {
                $display_photo = $BASE_PATH_URL_FILE . 'teacher/' . $tid . ".jpg";
            }
        ?>
            <div class="teacher-card shadow-sm" style="<?php echo $card_opacity; ?>" 
                 onclick="go(<?php echo $tid; ?>)">
                
                <img src="<?php echo $display_photo; ?>" class="teacher-avatar" alt="Profile">
                
                <div class="info-box">
                    <div class="t-name-eng text-truncate"><?php echo $teacher['tname']; ?></div>
                    <div class="t-name-ben"><?php echo $teacher['tnameb']; ?></div>
                    <div class="t-meta d-flex align-items-center gap-2">
                        <span class="id-badge">ID: <?php echo $tid; ?></span>
                        <span class="small opacity-75"><?php echo $teacher['ranks'] ?? ''; ?></span>
                    </div>
                </div>

                <div class="ms-2 opacity-25">
                    <i class="bi bi-chevron-right fs-5"></i>
                </div>
            </div>
        <?php
            $cnt++;
        }
        ?>
    </div>
</main>

<div style="height: 65px;"></div> <script>
    // ডাইনামিক কাউন্ট আপডেট
    document.getElementById("cnt_display").innerText = "<?php echo $cnt; ?>";

    function go(id) {
        window.location.href = "hr-profile.php?id=" + id;
    }
</script>

<?php include 'footer.php'; ?>