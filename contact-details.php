<?php 
$page_title = "Contact Us";
include 'inc.guest.php'; 
?>

<main class="pb-5">
    <!-- HERO BANNER -->
    <div class="guest-hero-banner text-center" style="background: #FFF3E0; color: #E65100; border-bottom: 1px solid #FFE0B2;">
        <div class="mb-3">
            <div class="icon-box-flat mx-auto" style="background: #FFE0B2; color: #E65100; width: 72px; height: 72px; font-size: 2rem;">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
        </div>
        <div class="inst-title" style="color: #3E2723;">Contact & Location</div>
        <div class="inst-meta" style="color: #E65100; margin-bottom: 0;">Get in Touch With Us</div>
        <div class="inst-desc mt-2" style="color: #E65100;">Official helpdesk, email, and campus location.</div>
    </div>

    <!-- CONTACT INFO -->
    <div class="section-lbl">Helpdesk & Support</div>
    <div class="m3-flat-list-group">
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #E8F5E9; color: #1B5E20;"><i class="bi bi-telephone-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Primary Phone</div>
                <div class="st-flat-desc">+880 1234 567890</div>
            </div>
            <a href="tel:+8801234567890" class="btn btn-sm" style="background: #1B5E20; color: #fff; border-radius: 8px;"><i class="bi bi-telephone"></i></a>
        </div>
        
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #E3F2FD; color: #1565C0;"><i class="bi bi-envelope-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Email Address</div>
                <div class="st-flat-desc">info@<?php echo strtolower(str_replace(' ', '', $scinfo['scname'] ?? 'institution')); ?>.edu</div>
            </div>
            <a href="mailto:info@<?php echo strtolower(str_replace(' ', '', $scinfo['scname'] ?? 'institution')); ?>.edu" class="btn btn-sm" style="background: #1565C0; color: #fff; border-radius: 8px;"><i class="bi bi-envelope"></i></a>
        </div>

        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #F3E5F5; color: #7B1FA2;"><i class="bi bi-browser-chrome"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Website</div>
                <div class="st-flat-desc">www.<?php echo strtolower(str_replace(' ', '', $scinfo['scname'] ?? 'institution')); ?>.edu</div>
            </div>
            <a href="#" class="btn btn-sm" style="background: #7B1FA2; color: #fff; border-radius: 8px;"><i class="bi bi-box-arrow-up-right"></i></a>
        </div>
    </div>

    <!-- LOCATION MAP -->
    <div class="section-lbl">Campus Location</div>
    <div class="p-3 bg-white border-bottom" style="border-color: #ECE6F0 !important;">
        <div class="card border-0" style="border-radius: 16px; overflow: hidden; background: #FAF8FC;">
            <div style="background: #E0E0E0; height: 200px; display: flex; align-items: center; justify-content: center; color: #757575;">
                <div class="text-center">
                    <i class="bi bi-map" style="font-size: 3rem;"></i>
                    <div style="font-size: 0.8rem; font-weight: 600; margin-top: 8px;">Map Integration Placeholder</div>
                </div>
            </div>
            <div class="card-body p-3">
                <h6 class="mb-1" style="font-size: 0.9rem; font-weight: 800; color: #1C1B1F;">Main Campus</h6>
                <p class="mb-0" style="font-size: 0.8rem; color: #49454F; line-height: 1.5;">
                    123 Education Street, Knowledge City,<br>
                    Dhaka, Bangladesh.
                </p>
            </div>
        </div>
    </div>
</main>

<?php include 'footer-guest.php'; ?>
