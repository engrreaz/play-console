<?php
$page_title = "Footprint Insights | Usage Analytics";
include 'inc.php';

// ১. গ্লোবাল পয়েন্ট সংগ্রহ (Optimized)
$pt_sql = "SELECT SUM(points) as total_pts FROM user_actions WHERE email = '$usr' AND sccode = '$sccode'";
$pt_row = $conn->query($pt_sql)->fetch_assoc();
$total_pts = (int) ($pt_row['total_pts'] ?? 0);

// ২. পেজ অনুযায়ী পয়েন্ট সংগ্রহ (PHP Array Mapping)
$page_points = [];
$ua_sql = "SELECT page, SUM(points) as pts FROM user_actions WHERE email = '$usr' GROUP BY page";
$ua_res = $conn->query($ua_sql);
while ($u = $ua_res->fetch_assoc()) {
    $page_points[$u['page']] = (int) $u['pts'];
}

// ৩. লগবুক ডাটা সংগ্রহ (JOIN ছাড়া - High Performance)
$log_sql = "SELECT l.*, p.module, p.page_title 
            FROM logbook l 
            LEFT JOIN (
                SELECT page_name, MAX(page_title) as page_title, MAX(module) as module 
                FROM permission_map_app 
                GROUP BY page_name
            ) p ON l.pagename = p.page_name 
            WHERE l.email = '$usr' AND l.sccode = '$sccode'";
$log_res = $conn->query($log_sql);

$module_summary = [];
$page_summary = [];
$global_stats = ['visits' => 0, 'bw' => 0, 'duration' => 0];

while ($l = $log_res->fetch_assoc()) {
    $m = $l['module'] ?: 'System/General';
    $p = $l['pagename'];
    $p_title = (!empty($l['page_title'])) ? $l['page_title'] : $l['pagename'];
    $pts = $page_points[$p] ?? 0;

    // গ্লোবাল সামারি
    $global_stats['visits']++;
    $global_stats['bw'] += $l['bandwidth'];
    $global_stats['duration'] += $l['duration'];

    // মডিউল সামারি
    if (!isset($module_summary[$m])) {
        $module_summary[$m] = ['visits' => 0, 'bw' => 0, 'duration' => 0, 'pts' => 0];
    }
    $module_summary[$m]['visits']++;
    $module_summary[$m]['bw'] += $l['bandwidth'];
    $module_summary[$m]['duration'] += $l['duration'];

    // পেজ সামারি (র‍্যাংকিং এর জন্য)
    if (!isset($page_summary[$p])) {
        $page_summary[$p] = [
            'title' => $p_title,
            'visits' => 0,
            'bw' => 0,
            'duration' => 0,
            'pts' => $pts,
            'module' => $m
        ];
    }

    $page_summary[$p]['pagename'] = $p . '-';
    $page_summary[$p]['visits']++;
    $page_summary[$p]['bw'] += $l['bandwidth'];
    $page_summary[$p]['duration'] += $l['duration'];

}

// ৪. র‍্যাংকিং ক্যালকুলেশন (PHP-তে সর্টিং)
$top_hits = $page_summary;
uasort($top_hits, fn($a, $b) => $b['visits'] <=> $a['visits']);
$top_hits = array_slice($top_hits, 0, 3); // Top 3 Hits

$top_stay = $page_summary;
uasort($top_stay, fn($a, $b) => $b['duration'] <=> $a['duration']);
$top_stay = array_slice($top_stay, 0, 3); // Top 3 Stay

// ৫. মডিউলে পয়েন্ট আপডেট
foreach ($page_summary as $p) {
    $module_summary[$p['module']]['pts'] += $p['pts'];
}

// ফরম্যাটিং ফাংশন
function formatDuration($seconds)
{
    if ($seconds < 60)
        return $seconds . "s";
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    return ($h > 0 ? $h . "h " : "") . $m . "m";
}
function formatBytes($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    return round($bytes / pow(1024, $pow), 1) . ' ' . $units[$pow];
}


// লেভেল এবং র‍্যাংক লজিক
$current_level = floor($total_pts / 1000) + 1; // প্রতি ১০০০ পয়েন্টে ১ লেভেল
$points_to_next_level = 1000 - ($total_pts % 1000);
$level_progress = ($total_pts % 1000) / 10; // পার্সেন্টেজ

$rank_title = "Rookie";
$rank_icon = "bi-award";
if ($total_pts >= 1000) {
    $rank_title = "Explorer";
    $rank_icon = "bi-compass";
}
if ($total_pts >= 5000) {
    $rank_title = "Veteran";
    $rank_icon = "bi-shield-check";
}
if ($total_pts >= 10000) {
    $rank_title = "Master";
    $rank_icon = "bi-trophy-fill";
}

?>

<style>
    /* Hero & Rankings Styles */
    .insight-hero {
        background: linear-gradient(135deg, #6750A4 0%, #311B92 100%);
        color: white;
        padding: 40px 24px 80px;
        border-radius: 0 0 40px 40px;
        text-align: center;
    }

    .hero-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin: -50px 16px 20px;
        position: relative;
        z-index: 10;
    }

    .m3-stat-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        border: 1px solid #E7E0EC;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .ranking-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 0 16px;
        margin-top: 10px;
    }

    .rank-card {
        background: #fff;
        border-radius: 16px;
        padding: 12px;
        border: 1px solid #EADDFF;
    }

    .rank-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.75rem;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 4px;
    }

    .rank-title {
        font-weight: 800;
        color: #1C1B1F;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 70px;
    }

    .module-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #E7E0EC;
        margin: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .module-header {
        background: #f9f5fc;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chip {
        font-size: 0.65rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 8px;
        background: #F3EDF7;
        color: #6750A4;
    }
</style>


<style>
    /* New Immersive Hero */
    .insight-hero-modern {
        background: linear-gradient(135deg, #6750A4 0%, #311B92 100%);
        padding: 50px 24px 100px;
        border-radius: 0 0 50px 50px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-bg-circle {
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        top: -100px;
        right: -50px;
    }

    .rank-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 15px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .points-display {
        font-size: 3.5rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -2px;
        text-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .level-progress-container {
        max-width: 250px;
        margin: 15px auto 0;
    }

    .m3-progress-track {
        height: 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        overflow: hidden;
        margin-top: 8px;
    }

    .m3-progress-bar {
        height: 100%;
        background: #FFD600;
        /* Gold color */
        box-shadow: 0 0 10px #FFD600;
        border-radius: 10px;
    }

    /* Floating Stat Grid Overlay */
    .hero-stat-overlay {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 0 16px;
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 28px;
        padding: 20px;
        border: 1px solid #E7E0EC;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .stat-label {
        font-size: 0.65rem;
        font-weight: 800;
        color: #79747E;
        text-transform: uppercase;
    }

    .stat-val {
        font-size: 1.1rem;
        font-weight: 900;
        color: #1C1B1F;
        display: block;
    }
</style>

<div class="insight-hero-modern">
    <div class="hero-bg-circle"></div>

    <div class="text-center position-relative">
        <div class="rank-badge">
            <i class="bi <?= $rank_icon ?>"></i> <?= $rank_title ?> RANK
        </div>

        <div class="points-display"><?= number_format($total_pts) ?></div>
        <div class="small opacity-75 fw-bold mt-1">LIFETIME ACHIEVEMENT POINTS</div>

        <div class="level-progress-container">
            <div class="d-flex justify-content-between small fw-black">
                <span>LEVEL <?= $current_level ?></span>
                <span class="opacity-75"><?= $points_to_next_level ?> PTS TO GO</span>
            </div>
            <div class="m3-progress-track">
                <div class="m3-progress-bar" style="width: <?= $level_progress ?>%"></div>
            </div>
        </div>
    </div>
</div>

<div class="hero-stat-overlay">
    <div class="glass-card">
        <i class="bi bi-clock-history text-primary fs-3 mb-1 d-block"></i>
        <span class="stat-val"><?= formatDuration($global_stats['duration']) ?></span>
        <span class="stat-label">Active Time</span>
    </div>
    <div class="glass-card">
        <i class="bi bi-lightning-charge-fill text-warning fs-3 mb-1 d-block"></i>
        <span class="stat-val"><?= $global_stats['visits'] ?></span>
        <span class="stat-label">Total Hits</span>
    </div>
    <div class="glass-card">
        <i class="bi bi-hdd-network text-success fs-3 mb-1 d-block"></i>
        <span class="stat-val"><?= formatBytes($global_stats['bw']) ?></span>
        <span class="stat-label">Data Used</span>
    </div>
    <div class="glass-card">
        <i class="bi bi-person-badge text-danger fs-3 mb-1 d-block"></i>
        <span class="stat-val"><?= $usr ?></span>
        <span class="stat-label">User ID</span>
    </div>
</div>


<main class="pb-5">


 

    <div class="m3-section-title">TOP PERFORMANCE RANKINGS</div>
    <div class="ranking-section">
        <div class="rank-card shadow-sm">
            <h6 class="small fw-black text-primary mb-2"><i class="bi bi-fire"></i> Most Visited</h6>
            <?php foreach ($top_hits as $h): ?>
                <div class="rank-item">
                    <span class="rank-title"><?= $h['title'] ?></span>
                    <span class="fw-bold text-primary"><?= $h['visits'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="rank-card shadow-sm">
            <h6 class="small fw-black text-success mb-2"><i class="bi bi-clock-fill"></i> Longest Stay</h6>
            <?php foreach ($top_stay as $s): ?>
                <div class="rank-item">
                    <span class="rank-title"><?= $s['title'] ?></span>
                    <span class="fw-bold text-success"><?= formatDuration($s['duration']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="m3-section-title">MODULE WISE BREAKDOWN</div>
    <?php foreach ($module_summary as $mName => $m): ?>
        <div class="module-card shadow-sm">
            <div class="module-header">
                <div>
                    <h6 class="module-name fw-black mb-0"><?= $mName ?></h6>
                    <span class="small fw-bold text-muted"><?= $m['visits'] ?> Sessions</span>
                </div>
                <div class="text-end">
                    <div class="fw-black text-primary"><?= formatDuration($m['duration']) ?></div>
                    <div class="small fw-bold text-warning">+ <?= $m['pts'] ?> Pts</div>
                </div>
            </div>
            <div class="module-body p-2 pb-0" hidden>
                <?php foreach ($page_summary as $pName => $pData):
                    if ($pData['module'] == $mName): ?>
                        <div class="page-row d-flex justify-content-between align-items-center p-3 border-bottom">
                            <div>
                                <div class="fw-bold small"><?= $pData['title'] ?></div>
                                <div class=" small" style="font-size:10px;"><?= $pName ?></div>
                                <div class="d-flex gap-2 mt-1">
                                    <span class="chip"><i class="bi bi-hourglass"></i>
                                        <?= formatDuration($pData['duration']) ?></span>
                                    <span class="chip" style="background: #FFF8E1; color: #FF8F00;"><i class="bi bi-star"></i>
                                        <?= $pData['pts'] ?> Pts</span>
                                </div>
                            </div>
                            <div class="badge bg-primary rounded-pill"><?= $pData['visits'] ?></div>
                        </div>
                    <?php endif; endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</main>

<?php include 'footer.php'; ?>