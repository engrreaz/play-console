<?php
session_start();
include_once 'inc.php'; 

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

$page_title = "Information Hub";

/**
 * ২. মেনু ডাটা সোর্সিং
 * এখানে সব আইটেম ক্যাটাগরি অনুযায়ী সাজানো হয়েছে
 */
$categories = [
    'Academic' => [
        ['onclick' => 'report_menu_student_list();', 'icon' => 'bi-people', 'title' => 'Students', 'level' => 'any'],
        ['onclick' => 'report_menu_attnd_register();', 'icon' => 'bi-fingerprint', 'title' => 'Attendance', 'level' => 'any'],
        ['onclick' => 'report_menu_absent_bunk_list();', 'icon' => 'bi-slash-circle', 'title' => 'Absent/Bunk', 'level' => 'any'],
        ['onclick' => 'report_menu_cls_routine();', 'icon' => 'bi-calendar3-range', 'title' => 'Routine', 'level' => 'any'],
        ['onclick' => 'report_menu_my_subjects();', 'icon' => 'bi-journal-text', 'title' => 'Subjects', 'level' => 'any'],
        ['onclick' => 'report_menu_honorable_teachers();', 'icon' => 'bi-person-workspace', 'title' => 'Staffs', 'level' => 'any']
    ],
    'Finance' => [
        ['onclick' => 'report_menu_my_collection();', 'icon' => 'bi-wallet2', 'title' => 'My Cash', 'level' => 'any'],
        ['onclick' => 'report_menu_daily_collection();', 'icon' => 'bi-bank', 'title' => 'Vault', 'level' => ['Administrator', 'Super Administrator', 'Accountants']]
    ],
    'Resources' => [
        ['onclick' => 'report_menu_ebooks_x();', 'icon' => 'bi-book', 'title' => 'E-Library', 'level' => 'any'],
        ['onclick' => 'report_menu_calendar();', 'icon' => 'bi-calendar-event', 'title' => 'Calendar', 'level' => 'any'],
        ['onclick' => 'report_menu_notices();', 'icon' => 'bi-megaphone', 'title' => 'Notices', 'level' => 'any'],
        ['onclick' => 'report_menu_notification();', 'icon' => 'bi-chat-dots', 'title' => 'Inbox', 'level' => 'any']
    ]
];

// 'My Class' বিশেষ কন্ডিশন (Class Teacher হলে শীর্ষে দেখাবে)
if (isset($count_class) && $count_class > 0) {
    array_unshift($categories['Academic'], ['onclick' => 'report_menu_my_class();', 'icon' => 'bi-microsoft-teams', 'title' => 'My Class', 'level' => 'any']);
}
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* Full Width M3 App Bar (8px radius bottom) */
    .m3-app-bar {
        width: 100%; height: 56px; background: #fff; display: flex; align-items: center; 
        padding: 0 16px; position: sticky; top: 0; z-index: 1050; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Category Labels */
    .m3-category-lbl {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 20px 0 10px 16px; letter-spacing: 0.8px;
    }

    /* Condensed Icon Grid (3 Columns for Mobile) */
    .m3-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 0 12px; }

    /* M3 Report Card (8px Radius) */
    .m3-report-card {
        background: #fff; border-radius: 8px; padding: 16px 8px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        border: 1px solid #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: transform 0.15s ease, background 0.15s;
        text-decoration: none !important; aspect-ratio: 1 / 1;
    }
    .m3-report-card:active { transform: scale(0.95); background-color: #F3EDF7; border-color: #EADDFF; }

    .icon-box {
        width: 42px; height: 42px; border-radius: 8px;
        background: #F7F2FA; color: #6750A4;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 8px; font-size: 1.4rem;
    }

    .report-title { font-size: 0.7rem; font-weight: 700; color: #1D1B20; text-align: center; line-height: 1.2; }
    
    .session-chip {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="index.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="session-chip"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5">
    <?php foreach ($categories as $cat_name => $items): ?>
        <?php 
        // ক্যাটাগরিতে অন্তত একটি দেখার যোগ্য আইটেম আছে কি না পরীক্ষা
        $visible_count = 0;
        foreach ($items as $item) {
            if ($item['level'] === 'any' || (is_array($item['level']) && in_array($userlevel, $item['level']))) $visible_count++;
        }
        
        if ($visible_count > 0): ?>
            <div class="m3-category-lbl"><?php echo $cat_name; ?></div>
            <div class="m3-grid">
                <?php foreach ($items as $item): 
                    $is_allowed = ($item['level'] === 'any' || (is_array($item['level']) && in_array($userlevel, $item['level'])));
                    if ($is_allowed):
                ?>
                    <a href="javascript:void(0);" class="m3-report-card shadow-sm" onclick="<?php echo $item['onclick']; ?>">
                        <div class="icon-box">
                            <i class="bi <?php echo $item['icon']; ?>"></i>
                        </div>
                        <span class="report-title"><?php echo $item['title']; ?></span>
                    </a>
                <?php endif; endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</main>

<div style="height: 75px;"></div> <script>
    // Navigation Functions
    function report_menu_my_class() { window.location.href = "my-class-report.php"; }
    function report_menu_student_list() { window.location.href = "student-list.php"; }
    function report_menu_my_collection() { window.location.href = "mypr.php"; }
    function report_menu_daily_collection() { window.location.href = "dailycollection.php"; }
    function report_menu_attnd_register() { window.location.href = "st-attnd-register.php"; }
    function report_menu_absent_bunk_list() { window.location.href = "absent-bunk-list.php"; }
    function report_menu_cls_routine() { window.location.href = "clsroutine.php"; }
    function report_menu_my_subjects() { window.location.href = "my-subject-list.php"; }
    function report_menu_honorable_teachers() { window.location.href = "teachers-list.php"; }
    function report_menu_ebooks_x() { window.location.href = "e-books.php"; }
    function report_menu_calendar() { window.location.href = "calendar.php"; }
    function report_menu_notices() { window.location.href = "notices.php"; }
    function report_menu_notification() { window.location.href = "notification.php"; }
</script>

<?php include 'footer.php'; ?>