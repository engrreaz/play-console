<?php
include_once 'inc.php'; // DB সংযোগ এবং প্রাথমিক ডাটা লোড করে

// টিচারের অ্যাসাইন করা ক্লাসের ডাটা হ্যান্ডলিং
$cteacher_data = $cteacher_data ?? [];

/**
 * মডিউল ক্যাটাগরি এবং আইটেম নির্ধারণ
 * এখানে আপনি খুব সহজে নতুন মডিউল যোগ বা পরিবর্তন করতে পারবেন।
 */
$academic_tools = [
    [
        'title' => 'Marks Entry',
        'desc' => 'Entry, edit, and manage exam marks',
        'icon' => 'bi-pencil-square',
        'color' => '#0061A4', // M3 Primary
        'url' => 'markentryselect.php'
    ],
    [
        'title' => 'Class Test',
        'desc' => 'Manage student class tests and assessments',
        'icon' => 'bi-journal-check',
        'color' => '#7D5260', // M3 Tertiary
        'url' => 'class_test.php'
    ],
    [
        'title' => 'Co-Curricular',
        'desc' => 'Manage student extra-curricular activities',
        'icon' => 'bi-trophy',
        'color' => '#196D35', // M3 Success
        'url' => 'co_curricular_entry.php'
    ]
];

$admin_tools = [
    [
        'title' => 'Leave Applications',
        'desc' => 'Review and respond to staff leave requests',
        'icon' => 'bi-file-earmark-person',
        'color' => '#BA1A1A', // M3 Error
        'url' => 'leave-application-response.php'
    ],
    [
        'title' => 'Notice Manager',
        'desc' => 'Manage institute notifications',
        'icon' => 'bi-megaphone',
        'color' => '#6750A4',
        'url' => 'noticemanager.php',
        'hidden' => true // প্রয়োজন অনুযায়ী হাইড রাখা যাবে
    ]
];
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    .category-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6750A4;
        margin: 20px 0 10px 16px;
        letter-spacing: 1px;
    }

    /* M3 List Item Style */
    .tool-item {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 16px;
        margin: 0 12px 10px 12px;
        display: flex;
        align-items: center;
        text-decoration: none !important;
        color: inherit;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .tool-item:active {
        background-color: #EADDFF; /* Tonal Container on click */
        transform: scale(0.98);
    }

    /* Tonal Icon Container */
    .icon-container {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .icon-container i { font-size: 1.5rem; }

    .tool-title { font-weight: 700; color: #1C1B1F; margin-bottom: 2px; font-size: 1rem; }
    .tool-desc { font-size: 0.75rem; color: #49454F; line-height: 1.2; }

    /* Top App Bar Customization */
    .m3-app-bar {
        background: white;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-4">
        <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                <i class="bi bi-grid-fill fs-5"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Modules</h4>
                <small class="text-muted">Tools & Management</small>
            </div>
        </div>
    </div>

    <div class="container-fluid px-2">
        
        <div class="category-label">Attendance & Records</div>
        
        <?php if (!empty($cteacher_data)): ?>
            <?php foreach ($cteacher_data as $class): ?>
                <a href="stattnd.php?cls=<?php echo urlencode($class['cteachercls']); ?>&sec=<?php echo urlencode($class['cteachersec']); ?>" class="tool-item shadow-sm">
                    <div class="icon-container" style="background-color: #E3F2FD; color: #1976D2;">
                        <i class="bi bi-fingerprint"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="tool-title">Student Attendance</div>
                        <div class="tool-desc">Mark attendance for <b><?php echo $class['cteachercls'] . " | " . $class['cteachersec']; ?></b></div>
                    </div>
                    <i class="bi bi-chevron-right text-muted opacity-50"></i>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <a href="stattnd.php" class="tool-item shadow-sm">
                <div class="icon-container" style="background-color: #E3F2FD; color: #1976D2;">
                    <i class="bi bi-fingerprint"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="tool-title">Attendance</div>
                    <div class="tool-desc">General student attendance management</div>
                </div>
                <i class="bi bi-chevron-right text-muted opacity-50"></i>
            </a>
        <?php endif; ?>

        <div class="category-label">Academic Tools</div>
        <?php foreach ($academic_tools as $item): ?>
            <a href="<?php echo $item['url']; ?>" class="tool-item shadow-sm">
                <div class="icon-container" style="background-color: <?php echo $item['color']; ?>15; color: <?php echo $item['color']; ?>;">
                    <i class="bi <?php echo $item['icon']; ?>"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="tool-title"><?php echo $item['title']; ?></div>
                    <div class="tool-desc"><?php echo $item['desc']; ?></div>
                </div>
                <i class="bi bi-chevron-right text-muted opacity-50"></i>
            </a>
        <?php endforeach; ?>

        <div class="category-label">Administration</div>
        <?php foreach ($admin_tools as $item): ?>
            <?php if (!isset($item['hidden']) || !$item['hidden']): ?>
                <a href="<?php echo $item['url']; ?>" class="tool-item shadow-sm">
                    <div class="icon-container" style="background-color: <?php echo $item['color']; ?>15; color: <?php echo $item['color']; ?>;">
                        <i class="bi <?php echo $item['icon']; ?>"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="tool-title"><?php echo $item['title']; ?></div>
                        <div class="tool-desc"><?php echo $item['desc']; ?></div>
                    </div>
                    <i class="bi bi-chevron-right text-muted opacity-50"></i>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

    </div>
</main>

<div style="height: 70px;"></div>

<?php include_once 'footer.php'; ?>