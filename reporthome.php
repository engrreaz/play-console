<?php
session_start();
include_once 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

/**
 * ১. লজিক ও ডাটা সোর্সিং (টেবিল বা কলাম অপরিবর্তিত)
 * এখানে সব আইটেমকে ক্যাটাগরি অনুযায়ী সাজানো হয়েছে
 */
$categories = [
    'Academic' => [
        ['onclick' => 'report_menu_student_list();', 'icon' => 'bi-people-fill', 'title' => 'Students', 'level' => 'any'],
        ['onclick' => 'report_menu_attnd_register();', 'icon' => 'bi-fingerprint', 'title' => 'Attendance', 'level' => 'any'],
        ['onclick' => 'report_menu_absent_bunk_list();', 'icon' => 'bi-slash-circle', 'title' => 'Absent/Bunk', 'level' => 'any'],
        ['onclick' => 'report_menu_cls_routine();', 'icon' => 'bi-clock-history', 'title' => 'Routine', 'level' => 'any'],
        ['onclick' => 'report_menu_my_subjects();', 'icon' => 'bi-file-text', 'title' => 'My Subjects', 'level' => 'any'],
        ['onclick' => 'report_menu_honorable_teachers();', 'icon' => 'bi-file-person', 'title' => 'Teachers', 'level' => 'any']
    ],
    'Finance' => [
        ['onclick' => 'report_menu_my_collection();', 'icon' => 'bi-coin', 'title' => 'My Collection', 'level' => 'any'],
        ['onclick' => 'report_menu_daily_collection();', 'icon' => 'bi-currency-exchange', 'title' => 'Institute Cash', 'level' => ['Administrator', 'Super Administrator', 'Accountants']]
    ],
    'Resources' => [
        ['onclick' => 'report_menu_ebooks_x();', 'icon' => 'bi-book-half', 'title' => 'E-Library', 'level' => 'any'],
        ['onclick' => 'report_menu_calendar();', 'icon' => 'bi-calendar-check', 'title' => 'Calendar', 'level' => 'any'],
        ['onclick' => 'report_menu_notices();', 'icon' => 'bi-megaphone', 'title' => 'Notices', 'level' => 'any'],
        ['onclick' => 'report_menu_notification();', 'icon' => 'bi-chat-right-fill', 'title' => 'Inbox', 'level' => 'any']
    ]
];

// 'My Class' বিশেষ কন্ডিশন (অ্যারেতে যোগ করা)
if (isset($count_class) && $count_class > 0) {
    array_unshift($categories['Academic'], ['onclick' => 'report_menu_my_class();', 'icon' => 'bi-diagram-2-fill', 'title' => 'My Class', 'level' => 'any']);
}
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-surface-variant: #E7E0EC;
        --m3-primary: #6750A4;
        --m3-secondary-container: #E8DEF8;
    }

    body { background-color: var(--m3-surface); }
    
    .category-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--m3-primary);
        margin: 24px 0 12px 10px;
        letter-spacing: 1px;
    }

    .report-card {
        background-color: #FFFFFF;
        border-radius: 20px; /* M3 Medium shape */
        border: none;
        padding: 1rem 0.5rem;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none !important;
        height: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .report-card:active {
        background-color: var(--m3-secondary-container);
        transform: scale(0.96);
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background-color: var(--m3-surface-variant);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        color: var(--m3-primary);
    }

    .report-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #1D1B20;
        margin: 0;
        text-align: center;
    }

    /* Top App Bar Styling */
    .top-app-bar {
        background-color: white;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
</style>

<main class="container-fluid px-3 pb-5">
    
    <div class="top-app-bar mb-4">
        <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;">
                <i class="bi bi-grid-fill fs-5"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Information Hub</h4>
                <small class="text-muted">School Reports & Resources</small>
            </div>
        </div>
    </div>

    <?php foreach ($categories as $cat_name => $items): ?>
        <?php 
        // ক্যাটাগরিতে অন্তত একটি দেখার যোগ্য আইটেম আছে কি না পরীক্ষা করা
        $has_visible_item = false;
        foreach ($items as $item) {
            if ($item['level'] === 'any' || (is_array($item['level']) && in_array($userlevel, $item['level']))) {
                $has_visible_item = true; break;
            }
        }
        
        if ($has_visible_item): ?>
            <div class="category-label"><?php echo $cat_name; ?></div>
            <div class="row g-2 px-1">
                <?php foreach ($items as $item): 
                    $display = ($item['level'] === 'any' || (is_array($item['level']) && in_array($userlevel, $item['level'])));
                    if ($display):
                ?>
                <div class="col-4 col-sm-3 col-md-2">
                    <div class="report-card shadow-sm" onclick="<?php echo $item['onclick']; ?>">
                        <div class="icon-wrapper">
                            <i class="bi <?php echo $item['icon']; ?> fs-4"></i>
                        </div>
                        <h6 class="report-title"><?php echo $item['title']; ?></h6>
                    </div>
                </div>
                <?php endif; endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</main>

<div style="height: 70px;"></div> <script>
    // Navigation Functions (অপরিবর্তিত রাখা হয়েছে)
    function report_menu_my_class() { window.location.href = "my-class-report.php"; }
    function report_menu_student_list() { window.location.href = "student-list.php"; }
    function report_menu_my_collection() { window.location.href = "my-collection.php"; }
    function report_menu_daily_collection() { window.location.href = "dailycollection.php"; }
    function report_menu_attnd_register() { window.location.href = "st-attnd-register.php"; }
    function report_menu_absent_bunk_list() { window.location.href = "absent-bunk-list.php"; }
    function report_menu_cls_routine() { window.location.href = "clsroutine.php"; }
    function report_menu_my_subjects() { window.location.href = "my-subject-list.php"; }
    function report_menu_honorable_teachers() { window.location.href = "teacher-list.php"; }
    function report_menu_ebooks_x() { window.location.href = "e-books.php"; }
    function report_menu_calendar() { window.location.href = "calendar.php"; }
    function report_menu_notices() { window.location.href = "notices.php"; }
    function report_menu_notification() { window.location.href = "notification.php"; }
</script>

<?php include 'footer.php'; ?>