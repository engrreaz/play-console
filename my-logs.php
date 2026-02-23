<?php
$page_title = "My Activity Log | Footprint Insights";
include 'inc.php'; 

// ১. ইনসাইট ক্যালকুলেশন (Summary Stats)
$stats_sql = "SELECT 
                COUNT(id) as total_visits, 
                SUM(bandwidth) as total_bw, 
                SUM(duration) as total_time,
                COUNT(DISTINCT pagename) as unique_pages
              FROM logbook 
              WHERE email = '$usr' AND sccode = '$sccode'";
$stats = $conn->query($stats_sql)->fetch_assoc();

// ২. প্ল্যাটফর্ম ডিস্ট্রিবিউশন
$platform_sql = "SELECT platform, COUNT(id) as count FROM logbook 
                 WHERE email = '$usr' GROUP BY platform";
$platforms = $conn->query($platform_sql);

// ৩. সাম্প্রতিক লগ লিস্ট
$logs_sql = "SELECT * FROM logbook 
             WHERE email = '$usr' AND sccode = '$sccode' 
             ORDER BY entrytime DESC LIMIT 20";
$logs_res = $conn->query($logs_sql);

// ব্যান্ডউইথ ফরম্যাটিং ফাংশন
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>


<style>
    :root { --m3-primary: #6750A4; --m3-surface: #FDF7FF; }
    body { background: var(--m3-surface); font-family: 'Inter', sans-serif; }

    /* Hero Section */
    .insight-hero {
        background: linear-gradient(135deg, #6750A4 0%, #4527A0 100%);
        color: white; padding: 35px 24px 70px;
        border-radius: 0 0 32px 32px; margin-bottom: -40px;
    }

    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 0 16px; position: relative; z-index: 5; }
    .stat-card {
        background: white; border-radius: 24px; padding: 16px;
        border: 1px solid #E7E0EC; box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .stat-val { font-size: 1.2rem; font-weight: 900; color: #1C1B1F; display: block; }
    .stat-label { font-size: 0.7rem; color: #49454F; font-weight: 700; text-transform: uppercase; }

    /* Log List */
    .log-item {
        background: white; border-radius: 16px; padding: 12px 16px;
        margin-bottom: 8px; border: 1px solid #E7E0EC;
        display: flex; align-items: center; gap: 12px;
    }
    .platform-icon {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
    }
    .bg-web { background: #E3F2FD; color: #1565C0; }
    .bg-android { background: #E8F5E9; color: #2E7D32; }
</style>

<main>
    <div class="insight-hero text-center">
        <h4 class="fw-black mb-1">Activity Insights</h4>
        <p class="small opacity-75 fw-bold"><?= $usr ?></p>
    </div>

    <div class="stats-grid">
        <div class="stat-card shadow-sm">
            <span class="stat-label">Data Consumed</span>
            <span class="stat-val text-primary"><?= formatBytes($stats['total_bw']) ?></span>
        </div>
        <div class="stat-card shadow-sm">
            <span class="stat-label">Total Visits</span>
            <span class="stat-val"><?= $stats['total_visits'] ?> <small style="font-size: 0.6rem;">Sessions</small></span>
        </div>
    </div>

    <div class="px-3 mt-5">
        <h6 class="fw-bold mb-3 px-1">Recent Activity Log</h6>
        
        <?php while($log = $logs_res->fetch_assoc()): 
            $is_web = ($log['platform'] == 'WEB');
            $p_icon = $is_web ? 'bi-globe' : 'bi-android2';
            $p_class = $is_web ? 'bg-web' : 'bg-android';
        ?>
        <div class="log-item">
            <div class="platform-icon <?= $p_class ?>">
                <i class="bi <?= $p_icon ?>"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-bold text-truncate" style="font-size: 0.85rem;"><?= $log['pagename'] ?></div>
                <div class="small text-muted" style="font-size: 0.7rem;">
                    <?= date('d M, h:i A', strtotime($log['entrytime'])) ?> • <?= formatBytes($log['bandwidth']) ?>
                </div>
            </div>
            <div class="text-end">
                <span class="badge rounded-pill bg-light text-dark border fw-bold" style="font-size: 0.6rem;">
                    <?= $log['platform'] ?>
                </span>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</main>

<?php include 'footer.php'; ?>