



<main class="pb-5">
    
    <div class="hero-container">
        <div class="small fw-bold opacity-75 text-uppercase mb-1" style="letter-spacing: 1px;">
            <?php echo $greeting; ?>, Admin
        </div>
        <div class="h3 fw-bold mb-0"><?php echo date('l'); ?></div>
        <div class="small opacity-90"><?php echo date('d M, Y'); ?></div>
        
        <div class="mt-3 d-flex gap-2">
            <span class="badge bg-white text-primary rounded-pill px-3 py-2 border-0 shadow-sm" style="font-size: 0.6rem;">
                <i class="bi bi-shield-check me-1"></i> System Active
            </span>
        </div>
    </div>

    <div class="widget-grid">

        <div class="m3-section-title">Schedule & Routine</div>
        <div class="row gx-2">
            <div class="col-12 col-md-6">
                <div class="block-unit shadow-sm"><?php include 'front-page-block/schedule.php'; ?></div>
            </div>
            <div class="col-12 col-md-6">
                <div class="block-unit shadow-sm"><?php include 'front-page-block/task-teacher.php'; ?></div>
            </div>
        </div>

        <?php if ($notice_block == 1): ?>
            <div class="mt-2">
                <div class="block-unit border-start border-4 border-primary bg-white shadow-sm">
                    <?php include 'front-page-block/notice.php'; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="m3-section-title">Finance & Stats</div>
        <div class="block-unit shadow-sm"><?php include 'front-page-block/cashmanager.php'; ?></div>
        <div class="block-unit shadow-sm"><?php include 'front-page-block/st-payment-block.php'; ?></div>
        <div class="block-unit shadow-sm"><?php include 'front-page-block/clsteacherblock.php'; ?></div>

        <div class="mt-3">
            <div class="block-unit shadow-sm" style="background: #F3EDF7;"><?php include 'front-page-block/holi-ramadan.php'; ?></div>
        </div>

        <div class="m3-section-title">Attendance Insights</div>
        <div class="row gx-2">
            <div class="col-12 col-md-6">
                <div class="block-unit shadow-sm"><?php include 'front-page-block/admin-st-attnd.php'; ?></div>
            </div>
            <div class="col-12 col-md-6">
                <div class="block-unit shadow-sm"><?php include 'front-page-block/admin-teacher-attnd.php'; ?></div>
            </div>
        </div>

    </div>
</main>

<div style="height: 80px;"></div>

<script>
    // প্রোফেশনাল নেভিগেশন স্ক্রিপ্ট
    function navigateTo(url) {
        if (!url) return;
        const session = '<?php echo $current_session; ?>';
        const target = url.includes('?') ? `${url}&year=${session}` : `${url}?year=${session}`;
        window.location.href = target;
    }

    // আপনার বিদ্যমান রাউট গুলো এখানে হ্যান্ডেল হবে
    function goclsatt(cls, sec) { 
        window.location.href = `stattnd.php?cls=${cls}&sec=${sec}&year=<?php echo $current_session; ?>`; 
    }
</script>

<?php include 'footer.php'; ?>