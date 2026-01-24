<?php
$page_title = "Teachers & Staff";
include 'inc.php';
include 'datam/datam-teacher.php';



?>

<style>
    /* Directory Specific Enhancements */
    .teacher-photo-box {
        width: 58px;
        height: 58px;
        border-radius: 8px;
        /* Strict 8px Radius */
        background: var(--m3-tonal-surface);
        border: 1px solid rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-right: 14px;
        flex-shrink: 0;
    }

    .teacher-photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .rank-badge {
        font-size: 0.65rem;
        background: var(--m3-tonal-container);
        color: var(--m3-on-tonal-container);
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 800;
        text-transform: uppercase;
    }
</style>

<main>
    <div class="hero-container">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;"
                    onclick="location.href='reporthome.php'">
                    <i class="bi bi-arrow-left"></i>
                </div>
                <div>
                    <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;">Staff Directory</div>
                    <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 600;">Faculty & Employees</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div id="cnt_display" style="font-size: 2.2rem; font-weight: 900; line-height: 1;">0</div>
                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; opacity: 0.9;">Total
                    Members</div>
            </div>
        </div>

        <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
            <span class="session-pill" style="background: rgba(255,255,255,0.15); color: #fff; border: none;">
                YEAR: <?php echo $current_session; ?>
            </span>
            <div class="tonal-icon-btn"
                style="background: rgba(255,255,255,0.15); color: #fff; border:none; width:38px; height:38px;">
                <i class="bi bi-search"></i>
            </div>
        </div>
    </div>

    <div class="widget-grid" style="margin-top: 15px; padding: 0 12px;">
        <div class="m3-section-title" style="margin-left: 4px;">Active Staff Members</div>

        <?php
        $cnt = 0;
        foreach ($datam_teacher_profile as $teacher) {
            $tid = $teacher["tid"];
            $status = $teacher["status"] ?? 1;

            // ইনএকটিভ স্টাফদের জন্য স্টাইল
            $card_style = ($status == 0) ? 'style="opacity: 0.6; filter: grayscale(1);"' : '';

            // ফটো লজিক
            $photo_path = $BASE_PATH_URL . 'teacher/' . $tid . ".jpg";
            // Note: file_exists usually works on local paths, for URLs check headers or assume path
            $display_photo = $BASE_PATH_URL_FILE . 'teacher/' . $tid . ".jpg";
            ?>
            <div class="m3-list-item" onclick="go(<?php echo $tid; ?>)" <?php echo $card_style; ?>
                style="padding: 12px; margin-bottom: 8px;">
                <div class="teacher-photo-box">
                    <img src="<?php echo teacher_profile_image_path($tid); ?>" alt="Teacher">
                </div>

                <div class="item-info">
                    <div class="st-title" style="font-size: 0.95rem; font-weight: 800; color: #1C1B1F;">
                        <?php echo $teacher['tname']; ?>
                    </div>
                    <div class="st-desc" style="color: var(--m3-primary); font-weight: 700; font-size: 0.82rem;">
                        <?php echo $teacher['tnameb']; ?>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                        <span class="id-badge" style="font-size: 0.6rem;">ID: <?php echo $tid; ?></span>
                        <span class="rank-badge"><?php echo $teacher['ranks'] ?? 'Staff'; ?></span>
                    </div>
                </div>

                <div style="color: var(--m3-outline); opacity: 0.4;">
                    <i class="bi bi-chevron-right" style="font-size: 1.2rem;"></i>
                </div>
            </div>
            <?php
            $cnt++;
        }
        ?>
    </div>
</main>

<div style="height: 80px;"></div>



<script>
    // ডাইনামিক কাউন্টার আপডেট
    document.getElementById("cnt_display").innerText = "<?php echo $cnt; ?>";

    function go(id) {
        window.location.href = "hr-profile.php?id=" + id;
    }
</script>

<?php include 'footer.php'; ?>