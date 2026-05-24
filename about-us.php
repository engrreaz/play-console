<?php 
$page_title = "About Us";
include 'inc.guest.php'; 
?>

<main class="pb-5">
    <!-- HERO BANNER -->
    <div class="guest-hero-banner text-center" style="background: #E8DEF8; color: #1D192B; border-bottom: 1px solid #CAC4D0;">
        <div class="mb-3">
            <div class="icon-box-flat mx-auto" style="background: #D0BCFF; color: #381E72; width: 72px; height: 72px; font-size: 2rem;">
                <i class="bi bi-info-square-fill"></i>
            </div>
        </div>
        <div class="inst-title">About <?php echo htmlspecialchars($scinfo['scname'] ?? $institution_name); ?></div>
        <div class="inst-meta"><?php echo htmlspecialchars($scinfo['sccode'] ?? $institution_code); ?></div>
        <div class="inst-desc mt-2">Discover our history, vision, and the values that drive our institution forward.</div>
    </div>

    <!-- CONTENT SECTION -->
    <div class="section-lbl">Our Story</div>
    <div class="p-4 bg-white border-bottom" style="border-color: #ECE6F0 !important;">
        <p class="mb-3" style="color: #49454F; line-height: 1.6;">
            Welcome to <strong><?php echo htmlspecialchars($scinfo['scname'] ?? $institution_name); ?></strong>. We are committed to providing an exceptional learning environment that fosters academic excellence and personal growth.
        </p>
        <p class="mb-0" style="color: #49454F; line-height: 1.6;">
            Founded with a vision to empower the next generation of leaders, our institution combines traditional values with modern educational practices. Our dedicated faculty and staff work tirelessly to ensure every student reaches their full potential.
        </p>
    </div>

    <!-- MISSION & VISION (Flat List) -->
    <div class="section-lbl">Mission & Vision</div>
    <div class="m3-flat-list-group">
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #FCE4EC; color: #C2185B;"><i class="bi bi-eye-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Our Vision</div>
                <div class="st-flat-desc">To be a premier institution of learning, recognized globally for excellence in education and character development.</div>
            </div>
        </div>
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #E8F5E9; color: #1B5E20;"><i class="bi bi-bullseye"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Our Mission</div>
                <div class="st-flat-desc">To nurture intellectual curiosity, promote lifelong learning, and prepare students to contribute meaningfully to society.</div>
            </div>
        </div>
    </div>

    <!-- KEY HIGHLIGHTS -->
    <div class="section-lbl">Institution Highlights</div>
    <div class="m3-flat-list-group">
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #E3F2FD; color: #1565C0;"><i class="bi bi-award-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Academic Excellence</div>
                <div class="st-flat-desc">Consistent track record of outstanding board results.</div>
            </div>
        </div>
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #FFF3E0; color: #E65100;"><i class="bi bi-palette-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Co-curricular Activities</div>
                <div class="st-flat-desc">Rich programs in sports, arts, and technology.</div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer-guest.php'; ?>
