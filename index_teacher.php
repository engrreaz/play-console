<div class="container-fluid pt-3">
    <div class="d-flex align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0">Teacher Dashboard</h2>
        <span class="ms-auto badge rounded-pill bg-primary px-3 py-2"><?php echo date('d M, Y'); ?></span>
    </div>

    <div class="row g-3">
        
        <div class="col-12 col-md-6">
            <?php include 'front-page-block/schedule.php'; ?>
        </div>
        
        <div class="col-12 col-md-6">
            <?php include 'front-page-block/task-teacher.php'; ?>
        </div>

        <?php if($notice_block == 1): ?>
        <div class="col-12">
            <div class="m-card elevation-1 border-start border-4 border-warning">
                <?php include 'front-page-block/notice.php'; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="col-12 col-md-6">
            <?php include 'front-page-block/admin-st-attnd.php'; ?>
        </div>
        <div class="col-12 col-md-6">
            <?php include 'front-page-block/admin-teacher-attnd.php'; ?>
        </div>

        <div class="col-12">
            <h6 class="text-muted fw-bold small text-uppercase mt-3 mb-2">Management & Finance</h6>
        </div>
        
        <div class="col-6 col-md-4">
            <?php include 'front-page-block/cashmanager.php'; ?>
        </div>
        
        <div class="col-6 col-md-4">
            <?php include 'front-page-block/st-payment-block.php'; ?>
        </div>

        <div class="col-12 col-md-4">
            <?php include 'front-page-block/clsteacherblock.php'; ?>
        </div>

        <div class="col-12 mt-2">
            <?php include 'front-page-block/holi-ramadan.php'; ?>
        </div>

    </div>
</div>

<div style="height:80px;"></div>

<script>
    // একটি গ্লোবাল ফাংশন যা সব পেজে নেভিগেশন সহজ করবে
    function navigateTo(url) {
        if(url) window.location.href = url;
    }

    // আপনার আগের ফাংশনগুলোকে ক্লিন করা হলো
    const routes = {
        clsp: 'finclssec.php',
        clsa: 'finacc.php',
        result: 'resultprocess.php',
        settings: 'settings.php',
        sublist: 'tools_allsubjects.php',
        update: 'whatsnew.php',
        token: 'accountsecurity.php',
        mypr: 'mypr.php',
        clsattall: 'attnd-cls-sec.php'
    };

    function goclsatt(cls, sec) { window.location.href = `stattnd.php?cls=${cls}&sec=${sec}`; }
    function register(cls, sec) { window.location.href = `stattndregister.php?cls=${cls}&sec=${sec}`; }
</script>