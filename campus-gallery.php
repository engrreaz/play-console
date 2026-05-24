<?php 
$page_title = "Campus Gallery";
include 'inc.guest.php'; 
?>

<main class="pb-5">
    <!-- HERO BANNER -->
    <div class="guest-hero-banner text-center" style="background: #E0F2F1; color: #004D40; border-bottom: 1px solid #B2DFDB;">
        <div class="mb-3">
            <div class="icon-box-flat mx-auto" style="background: #B2DFDB; color: #004D40; width: 72px; height: 72px; font-size: 2rem;">
                <i class="bi bi-images"></i>
            </div>
        </div>
        <div class="inst-title" style="color: #00251A;">Campus Gallery</div>
        <div class="inst-meta" style="color: #004D40; margin-bottom: 0;">Glimpses of Campus Life</div>
        <div class="inst-desc mt-2" style="color: #004D40;">Explore our events, facilities, and academic environment through images.</div>
    </div>

    <!-- GALLERY GRID -->
    <div class="section-lbl">Photo Gallery</div>
    <div class="container px-3 mt-2">
        <div class="row g-3">
            <!-- Item 1 -->
            <div class="col-6">
                <div class="card border-0" style="border-radius: 16px; overflow: hidden; background: #FAF8FC;">
                    <div style="background: #E8DEF8; height: 120px; display: flex; align-items: center; justify-content: center; color: #4F378B; font-size: 2rem;">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="card-body p-2 text-center">
                        <h6 class="mb-0" style="font-size: 0.8rem; font-weight: 700; color: #1C1B1F;">Campus Building</h6>
                    </div>
                </div>
            </div>
            
            <!-- Item 2 -->
            <div class="col-6">
                <div class="card border-0" style="border-radius: 16px; overflow: hidden; background: #FAF8FC;">
                    <div style="background: #FCE4EC; height: 120px; display: flex; align-items: center; justify-content: center; color: #C2185B; font-size: 2rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="card-body p-2 text-center">
                        <h6 class="mb-0" style="font-size: 0.8rem; font-weight: 700; color: #1C1B1F;">Annual Sports</h6>
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="col-6">
                <div class="card border-0" style="border-radius: 16px; overflow: hidden; background: #FAF8FC;">
                    <div style="background: #FFF3E0; height: 120px; display: flex; align-items: center; justify-content: center; color: #E65100; font-size: 2rem;">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div class="card-body p-2 text-center">
                        <h6 class="mb-0" style="font-size: 0.8rem; font-weight: 700; color: #1C1B1F;">Science Fair</h6>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="col-6">
                <div class="card border-0" style="border-radius: 16px; overflow: hidden; background: #FAF8FC;">
                    <div style="background: #E3F2FD; height: 120px; display: flex; align-items: center; justify-content: center; color: #1565C0; font-size: 2rem;">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div class="card-body p-2 text-center">
                        <h6 class="mb-0" style="font-size: 0.8rem; font-weight: 700; color: #1C1B1F;">Award Ceremony</h6>
                    </div>
                </div>
            </div>
            
            <!-- Item 5 -->
            <div class="col-6">
                <div class="card border-0" style="border-radius: 16px; overflow: hidden; background: #FAF8FC;">
                    <div style="background: #E8F5E9; height: 120px; display: flex; align-items: center; justify-content: center; color: #1B5E20; font-size: 2rem;">
                        <i class="bi bi-tree-fill"></i>
                    </div>
                    <div class="card-body p-2 text-center">
                        <h6 class="mb-0" style="font-size: 0.8rem; font-weight: 700; color: #1C1B1F;">Green Campus</h6>
                    </div>
                </div>
            </div>
            
            <!-- Item 6 -->
            <div class="col-6">
                <div class="card border-0" style="border-radius: 16px; overflow: hidden; background: #FAF8FC;">
                    <div style="background: #F3E5F5; height: 120px; display: flex; align-items: center; justify-content: center; color: #7B1FA2; font-size: 2rem;">
                        <i class="bi bi-easel2-fill"></i>
                    </div>
                    <div class="card-body p-2 text-center">
                        <h6 class="mb-0" style="font-size: 0.8rem; font-weight: 700; color: #1C1B1F;">Classrooms</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 20px; background: #ECE6F0; color: #49454F;">
            <i class="bi bi-three-dots"></i>
        </div>
        <div class="mt-2" style="font-size: 0.75rem; color: #79747E; font-weight: 600;">More photos coming soon</div>
    </div>
</main>

<?php include 'footer-guest.php'; ?>
