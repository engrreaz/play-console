<?php 
$page_title = "Faculty & Staff";
include 'inc.guest.php'; 
?>

<main class="pb-5">
    <!-- HERO BANNER -->
    <div class="guest-hero-banner text-center" style="background: #E3F2FD; color: #0D47A1; border-bottom: 1px solid #CAC4D0;">
        <div class="mb-3">
            <div class="icon-box-flat mx-auto" style="background: #90CAF9; color: #0D47A1; width: 72px; height: 72px; font-size: 2rem;">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div class="inst-title">Our Faculty & Staff</div>
        <div class="inst-meta"><?php echo htmlspecialchars($scinfo['scname'] ?? $institution_name); ?></div>
        <div class="inst-desc mt-2">Meet the dedicated educators and professionals guiding our students.</div>
    </div>

    <!-- ADMINISTRATION -->
    <div class="section-lbl">Administration</div>
    <div class="m3-flat-list-group">
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #FFF3E0; color: #E65100;"><i class="bi bi-person-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Principal</div>
                <div class="st-flat-desc">Head of Institution</div>
            </div>
        </div>
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #E8F5E9; color: #1B5E20;"><i class="bi bi-person-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Vice Principal</div>
                <div class="st-flat-desc">Academic & Administrative Coordinator</div>
            </div>
        </div>
    </div>

    <!-- SENIOR FACULTY -->
    <div class="section-lbl">Senior Faculty</div>
    <div class="m3-flat-list-group">
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #FCE4EC; color: #C2185B;"><i class="bi bi-person-badge"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Science Department</div>
                <div class="st-flat-desc">Senior Teachers - Physics, Chemistry, Biology</div>
            </div>
        </div>
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #E8DEF8; color: #381E72;"><i class="bi bi-person-badge"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Arts & Humanities</div>
                <div class="st-flat-desc">Senior Teachers - History, Economics, Geography</div>
            </div>
        </div>
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #E0F7FA; color: #006064;"><i class="bi bi-person-badge"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Business Studies</div>
                <div class="st-flat-desc">Senior Teachers - Accounting, Management</div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer-guest.php'; ?>
