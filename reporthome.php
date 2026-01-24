<?php

$page_title = "Information Hub";
include_once 'inc.php';

$categories = [
    'Academic' => [
        ['onclick' => 'report_menu_student_list();', 'icon' => 'bi-people', 'title' => 'Students', 'level' => 'any'],
        ['onclick' => 'report_menu_attnd_register();', 'icon' => 'bi-fingerprint', 'title' => 'Attendance', 'level' => 'any'],
        ['onclick' => 'report_menu_absent_bunk_list();', 'icon' => 'bi-slash-circle', 'title' => 'Absent/Bunk', 'level' => 'any'],
        ['onclick' => 'report_menu_cls_routine();', 'icon' => 'bi-calendar3-range', 'title' => 'Routine', 'level' => 'any'],
        ['onclick' => 'report_menu_my_subjects();', 'icon' => 'bi-journal-text', 'title' => 'Subjects', 'level' => 'any'],
        ['onclick' => 'report_menu_honorable_teachers();', 'icon' => 'bi-person-workspace', 'title' => 'Staffs', 'level' => 'any'],
        ['onclick' => 'report_menu_syllabus();', 'icon' => 'bi-book-half', 'title' => 'Syllabus', 'level' => 'any'],
        ['onclick' => 'report_menu_lesson_plan();', 'icon' => 'bi-book', 'title' => 'Lesson Plan', 'level' => 'any']
    ],
    'Finance' => [
        ['onclick' => 'report_menu_my_collection();', 'icon' => 'bi-wallet2', 'title' => 'My Cash', 'level' => 'any'],
        ['onclick' => 'report_menu_daily_collection();', 'icon' => 'bi-bank', 'title' => 'Vault', 'level' => ['Administrator', 'Super Administrator', 'Accountants']]
    ],
    'Resources' => [
        ['onclick' => 'report_menu_ebooks_x();', 'icon' => 'bi-book-fill', 'title' => 'E-Library', 'level' => 'any'],
        ['onclick' => 'report_menu_calendar();', 'icon' => 'bi-calendar-event', 'title' => 'Calendar', 'level' => 'any'],
        ['onclick' => 'report_menu_notices();', 'icon' => 'bi-megaphone', 'title' => 'Notices', 'level' => 'any'],
        ['onclick' => 'report_menu_notification();', 'icon' => 'bi-chat-dots', 'title' => 'Inbox', 'level' => 'any']
    ]
];

$count_class = count($cteacher_data);
// 'My Class' বিশেষ কন্ডিশন (Class Teacher হলে শীর্ষে দেখাবে)
if (isset($count_class) && $count_class > 0) {
    array_unshift($categories['Academic'], ['onclick' => 'report_menu_my_class();', 'icon' => 'bi-microsoft-teams', 'title' => 'My Class', 'level' => 'any']);
}
?>




<main class="pb-5">
    <?php foreach ($categories as $cat_name => $items): ?>
        <?php
        // ক্যাটাগরিতে অন্তত একটি দেখার যোগ্য আইটেম আছে কি না পরীক্ষা
        $visible_count = 0;
        foreach ($items as $item) {
            if ($item['level'] === 'any' || (is_array($item['level']) && in_array($userlevel, $item['level'])))
                $visible_count++;
        }

        if ($visible_count > 0): ?>
            <div class="m3-category-lbl"><?php echo $cat_name; ?></div>
            <div class="m3-grid">
                <?php foreach ($items as $item):
                    $is_allowed = ($item['level'] === 'any' || (is_array($item['level']) && in_array($userlevel, $item['level'])));
                    if ($is_allowed):
                        ?>
                        <a href="javascript:void(0);" class="m3-report-card shadow-sm" onclick="<?php echo $item['onclick']; ?>">
                            <div class="icon-box" style="margin-right:0; margin-bottom:10px;">
                                <i class="bi <?php echo $item['icon']; ?>"></i>
                            </div>
                            <span class="report-title"><?php echo $item['title']; ?></span>
                        </a>
                    <?php endif; endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</main>

<div style="height: 75px;"></div>
<script>
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
    function report_menu_syllabus() { window.location.href = "syllabus.php"; }
    function report_menu_lesson_plan() { window.location.href = "lesson-plan.php"; }
</script>

<?php include 'footer.php'; ?>