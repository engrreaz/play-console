<?php
include_once 'inc.php'; // DB সংযোগ এবং প্রাথমিক ডাটা লোড করে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

$page_title = "Management Tools";

/**
 * ২. মডিউল কনফিগারেশন
 */
$academic_tools = [
    [
        'title' => 'Marks Entry',
        'desc' => 'Manage exam marks & results',
        'icon' => 'bi-pencil-square',
        'color' => '#6750A4', // M3 Primary
        'url' => 'markentryselect.php'
    ],
    [
        'title' => 'Class Test',
        'desc' => 'Assessments & class tests',
        'icon' => 'bi-journal-check',
        'color' => '#7D5260', // M3 Tertiary
        'url' => 'class_test.php'
    ],
    [
        'title' => 'Co-Curricular',
        'desc' => 'Extra-curricular activities',
        'icon' => 'bi-trophy',
        'color' => '#146C32', // M3 Success
        'url' => 'co_curricular_entry.php'
    ]
];

$admin_tools = [
    [
        'title' => 'Leave Applications',
        'desc' => 'Respond to staff leave requests',
        'icon' => 'bi-file-earmark-person',
        'color' => '#B3261E', // M3 Error
        'url' => 'leave-application-response.php'
    ],
    [
        'title' => 'Notice Manager',
        'desc' => 'Broadcast institute notifications',
        'icon' => 'bi-megaphone',
        'color' => '#6750A4',
        'url' => 'noticemanager.php'
    ]
];
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width M3 App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; height: 56px; background: #fff; display: flex; align-items: center; 
        padding: 0 16px; position: sticky; top: 0; z-index: 1050; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Compact Category Labels */
    .m3-cat-label {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 20px 0 8px 16px; letter-spacing: 0.8px;
    }

    /* Condensed Tool Card (8px Radius) */
    .tool-card {
        background: #fff; border-radius: 8px; padding: 12px;
        margin: 0 12px 6px; border: 1px solid #f0f0f0;
        display: flex; align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: transform 0.15s ease, background 0.15s;
        text-decoration: none !important; color: inherit;
    }
    .tool-card:active { transform: scale(0.98); background-color: #F3EDF7; }

    /* Tonal Icon Box (8px Radius) */
    .icon-box {
        width: 44px; height: 44px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 14px; flex-shrink: 0; font-size: 1.3rem;
    }

    .tool-info { flex-grow: 1; overflow: hidden; }
    .tool-name { font-weight: 700; color: #1C1B1F; font-size: 0.9rem; margin-bottom: 0; }
    .tool-meta { font-size: 0.7rem; color: #49454F; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .session-indicator {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 8px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="index.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="session-indicator"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5 mt-2">
    <div class="m3-cat-label">Class Management</div>
    <?php if (!empty($cteacher_data)): ?>
        <?php foreach ($cteacher_data as $class): ?>
            <a href="stattnd.php?cls=<?php echo urlencode($class['cteachercls']); ?>&sec=<?php echo urlencode($class['cteachersec']); ?>&year=<?php echo $current_session; ?>" class="tool-card shadow-sm">
                <div class="icon-box shadow-sm" style="background-color: #E3F2FD; color: #1976D2;">
                    <i class="bi bi-fingerprint"></i>
                </div>
                <div class="tool-info">
                    <div class="tool-name">Roll Call</div>
                    <div class="tool-meta">Attendance for <b><?php echo $class['cteachercls'] . " (" . $class['cteachersec'] . ")"; ?></b></div>
                </div>
                <i class="bi bi-chevron-right text-muted opacity-25"></i>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <a href="stattnd.php?year=<?php echo $current_session; ?>" class="tool-card shadow-sm">
            <div class="icon-box shadow-sm" style="background-color: #F3EDF7; color: #6750A4;">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="tool-info">
                <div class="tool-name">Attendance</div>
                <div class="tool-meta">Daily student presence tracking</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>
    <?php endif; ?>

    <div class="m3-cat-label">Academic Excellence</div>
    <?php foreach ($academic_tools as $item): ?>
        <a href="<?php echo $item['url']; ?>?year=<?php echo $current_session; ?>" class="tool-card shadow-sm">
            <div class="icon-box shadow-sm" style="background: <?php echo $item['color']; ?>15; color: <?php echo $item['color']; ?>;">
                <i class="bi <?php echo $item['icon']; ?>"></i>
            </div>
            <div class="tool-info">
                <div class="tool-name"><?php echo $item['title']; ?></div>
                <div class="tool-meta"><?php echo $item['desc']; ?></div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>
    <?php endforeach; ?>

    <div class="m3-cat-label">Administration</div>
    <?php foreach ($admin_tools as $item): ?>
        <a href="<?php echo $item['url']; ?>?year=<?php echo $current_session; ?>" class="tool-card shadow-sm">
            <div class="icon-box shadow-sm" style="background: <?php echo $item['color']; ?>15; color: <?php echo $item['color']; ?>;">
                <i class="bi <?php echo $item['icon']; ?>"></i>
            </div>
            <div class="tool-info">
                <div class="tool-name"><?php echo $item['title']; ?></div>
                <div class="tool-meta"><?php echo $item['desc']; ?></div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-25"></i>
        </a>
    <?php endforeach; ?>
</main>

<div style="height: 75px;"></div> <?php include_once 'footer.php'; ?>