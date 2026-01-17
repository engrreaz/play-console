<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;

$page_title = "Welcome Visitor";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width Top App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; height: 56px; background: #fff; display: flex; align-items: center; 
        padding: 0 16px; position: sticky; top: 0; z-index: 1050; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Hero Clock Section (8px Radius) */
    .hero-clock-card {
        background: #F3EDF7; border-radius: 8px; padding: 20px;
        margin: 12px; border: 1px solid #EADDFF;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); text-align: center;
    }
    .hero-time { font-size: 1.8rem; font-weight: 800; color: #6750A4; display: block; line-height: 1.2; }
    .hero-date { font-size: 0.8rem; font-weight: 700; color: #49454F; text-transform: uppercase; letter-spacing: 1px; }

    /* Modern Progress Bar */
    .m3-progress-container { background: #E7E0EC; height: 8px; border-radius: 4px; margin-top: 15px; overflow: hidden; }
    .m3-progress-fill { background: #6750A4; height: 100%; width: 37%; transition: width 0.5s ease; }

    /* Action List Card (8px Radius) */
    .m3-nav-card {
        background: #fff; border-radius: 8px; padding: 14px 16px;
        margin: 0 12px 10px; border: 1px solid #f0f0f0;
        display: flex; align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: transform 0.15s ease, background 0.15s;
        text-decoration: none !important; color: inherit;
    }
    .m3-nav-card:active { transform: scale(0.98); background-color: #F7F2FA; }

    .nav-icon {
        width: 40px; height: 40px; border-radius: 8px;
        background: #F3EDF7; color: #6750A4;
        display: flex; align-items: center; justify-content: center;
        margin-right: 14px; font-size: 1.2rem;
    }

    .nav-label { font-weight: 700; color: #1D1B20; font-size: 0.95rem; }
    
    .debug-chip {
        display: inline-block; background: #eee; color: #666;
        font-size: 0.65rem; font-weight: 700; padding: 2px 10px;
        border-radius: 4px; margin: 10px 16px;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
        <i class="bi bi-person-badge-fill"></i>
    </div>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <a href="sout.php" class="btn btn-sm btn-outline-danger border-0 fw-bold" style="border-radius: 8px;">OUT</a>
</header>

<main class="pb-5 mt-2">
    <div class="hero-clock-card shadow-sm">
        <span class="hero-time" id="m3-time">00:00:00</span>
        <span class="hero-date" id="m3-date"><?php echo date('l, d F'); ?></span>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <span class="small fw-bold text-muted text-uppercase">Institute Progress</span>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.65rem;">Active</span>
        </div>
        <div class="m3-progress-container">
            <div class="m3-progress-fill" id="m3-bar"></div>
        </div>
    </div>

    <div class="debug-chip shadow-sm">
        <?php echo strtoupper($userlevel); ?> <i class="bi bi-dot"></i> <?php echo $usr; ?> <i class="bi bi-dot"></i> <?php echo $sccode; ?>
    </div>

    <div class="px-3 mb-2 mt-2 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Quick Information</div>

    <a href="about.php?sccode=<?php echo $sccode; ?>" class="m3-nav-card shadow-sm">
        <div class="nav-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <div class="flex-grow-1">
            <div class="nav-label">About Institute</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

    <a href="contact.php?sccode=<?php echo $sccode; ?>" class="m3-nav-card shadow-sm">
        <div class="nav-icon" style="background: #E3F2FD; color: #1976D2;"><i class="bi bi-geo-alt-fill"></i></div>
        <div class="flex-grow-1">
            <div class="nav-label">Office Address</div>
        </div>
        <i class="bi bi-chevron-right text-muted opacity-25"></i>
    </a>

</main>

<div style="height: 60px;"></div> <script>
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour12: false });
        const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        const dateStr = now.toLocaleDateString('en-US', dateOptions);
        
        document.getElementById('m3-time').innerHTML = timeStr;
        document.getElementById('m3-date').innerHTML = dateStr;

        // প্রগ্রেস বার সিমুলেশন
        // (আপনি চাইলে এখানে নির্দিষ্ট টাইমের উপর ভিত্তি করে লজিক যোগ করতে পারেন)
        document.getElementById('m3-bar').style.width = "42%";
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>

<?php include 'footer.php'; ?>