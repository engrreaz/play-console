<?php
// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear']
    ?? $_COOKIE['query-session']
    ?? $sy;
$sy_param = '%' . $current_session . '%';

$page_title = "Dashboard";

// ডাইনামিক গ্রিটিং লজিক
$hour = date('H');
$greeting = ($hour < 12) ? "Good Morning" : (($hour < 17) ? "Good Afternoon" : "Good Evening");
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.88rem; margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, sans-serif; }

    /* Glassmorphism App Bar */
    .m3-app-bar {
        width: 100%; height: 64px; background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        display: flex; align-items: center; padding: 0 16px; position: sticky; 
        top: 0; z-index: 1060; border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .app-bar-title { font-size: 1.2rem; font-weight: 800; color: #1C1B1F; flex-grow: 1; }

    /* Mesh Gradient Hero Section */
    .hero-container {
        margin: 12px; padding: 24px 20px; border-radius: 16px; /* M3 Large Corner */
        background: linear-gradient(135deg, #6750A4 0%, #B69DF8 100%);
        position: relative; overflow: hidden; color: white;
        box-shadow: 0 10px 20px rgba(103, 80, 164, 0.15);
    }
    .hero-container::after {
        content: ''; position: absolute; top: -50%; right: -20%; width: 200px; height: 200px;
        background: rgba(255,255,255,0.1); border-radius: 50%;
    }

    /* Condensed Category Styling */
    .m3-section-title {
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 20px 16px 8px; letter-spacing: 1.5px;
        display: flex; align-items: center; gap: 8px;
    }
    .m3-section-title::after { content: ''; flex: 1; height: 1px; background: #EADDFF; }

    /* Block Units (Strict 8px Radius) */
    .block-unit { 
        background: #fff; border-radius: 8px !important; 
        border: 1px solid #f0f0f0 !important; overflow: hidden;
        margin-bottom: 8px; transition: transform 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .block-unit:active { transform: scale(0.99); }

    /* Quick Stats Pill */
    .session-pill {
        font-size: 0.6rem; background: #EADDFF; color: #21005D;
        padding: 4px 12px; border-radius: 100px; font-weight: 900;
        border: 1px solid rgba(103, 80, 164, 0.2);
    }

    .widget-grid { padding: 0 12px; }
</style>

<header class="m3-app-bar">
    <div class="d-flex align-items-center flex-grow-1">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm"
            style="width: 38px; height: 38px; background: #6750A4 !important;">
            <i class="bi bi-intersect fs-5"></i>
        </div>
        <div class="app-bar-title">EIM<span style="color: #6750A4;">Box</span></div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="session-pill"><?php echo $current_session; ?></span>
        <div class="rounded-circle overflow-hidden border" style="width: 34px; height: 34px;">
            <img src="https://ui-avatars.com/api/?name=Admin&background=EADDFF&color=21005D" width="100%">
        </div>
    </div>
</header>

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