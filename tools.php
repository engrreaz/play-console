<?php
$page_title = "Management Tools";
include_once 'inc.php'; // DB সংযোগ এবং প্রাথমিক ডাটা লোড করে





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