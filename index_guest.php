<?php include 'inc.guest.php'; ?>

<style>
    .inst-logo-squircle {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        /* Material 3 Squircle Shape Metric */
        /* background: #6750A4; */
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 16px;
        /* border: 1px solid #D0BCFF; */
    }
</style>


<main class="pb-5">

    <!-- 1. MODERN INSTITUTION HERO BANNER (GUEST CONTEXT) -->
    <div class="guest-hero-banner">
        <div>
            <img class="inst-logo-squircle" src="<?= $BASE_PATH_URL . 'logo/' . $sccode . '.png' ?>"
                alt="Institution Logo">
        </div>
        <div class="hero-info-block">
            <div class="inst-title"><?php echo htmlspecialchars($scinfo['scname']); ?></div>
            <div class="inst-meta"><?php echo htmlspecialchars($scinfo['sccode']); ?></div>
            <div class="inst-desc"><?php echo htmlspecialchars($welcome_msg); ?></div>
        </div>
    </div>

    <!-- 2. QUICK GATEWAY ACCESS -->
    <div class="section-lbl">Instant Gateway</div>
    <div class="guest-gate-panel">
        <a href="guest-dashboard.php" class="btn-m3-primary-tonal">
            <i class="bi bi-person-bounding-box"></i> Enter As Guest
        </a>
        <span class="gate-warning-text">* কোনো পাসওয়ার্ড বা রেজিস্ট্রেশন ছাড়াই পাবলিক মডিউলগুলো দেখুন</span>
    </div>

    <!-- 3. PUBLIC INFORMATION EXPLORER (FLAT LINIAR ROWS) -->
    <div class="section-lbl">Explore Campus Data</div>
    <div class="m3-flat-list-group">

        <!-- About Institution -->
        <a href="about-us.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-about"><i class="bi bi-info-square-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">About Our Institution</div>
                <div class="st-flat-desc">History, achievements, governing body, and vision</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Public Notices -->
        <a href="notices-guest.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-notice"><i class="bi bi-megaphone-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Public Notices & News</div>
                <div class="st-flat-desc">General circulars, admission updates, and event news</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Academic Gallery -->
        <a href="campus-gallery.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-gallery"><i class="bi bi-images"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Campus Gallery & Media</div>
                <div class="st-flat-desc">Glimpses of campus events, sports, and infrastructure</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Contact & Location -->
        <a href="contact-details.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-contact"><i class="bi bi-geo-alt-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Contact & Location Map</div>
                <div class="st-flat-desc">Official helpdesk numbers, email, and location tracker</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

    </div>

    <!-- 4. ACADEMIC PORTALS (ADDITIONAL REFERENCE) -->
    <div class="section-lbl">Official Access Ports</div>
    <div class="m3-flat-list-group">

        <!-- Student/Teacher Login Prompt -->
        <a href="logout.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-portal"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Regular Portal Login</div>
                <div class="st-flat-desc">Authorized access point for students, guardians, and teachers</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-box-arrow-in-right"></i></div>
        </a>

    </div>
</main>

<?php
include 'footer-guest.php';
?>



<script>

</script>

</body>

</html>