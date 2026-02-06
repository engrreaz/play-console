<?php

$page_title = "Information Hub";
include_once 'inc.php';

/* ============================
   MENU CONFIG
============================ */

$categories = [

    'Academic' => [

        [
            'onclick' => 'report_menu_student_list();',
            'icon' => 'bi-people',
            'title' => 'Students',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_attnd_register();',
            'icon' => 'bi-fingerprint',
            'title' => 'Attendance',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_absent_bunk_list();',
            'icon' => 'bi-slash-circle',
            'title' => 'Absent/Bunk',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_cls_routine();',
            'icon' => 'bi-calendar3-range',
            'title' => 'Routine',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_my_subjects();',
            'icon' => 'bi-journal-text',
            'title' => 'Subjects',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_honorable_teachers();',
            'icon' => 'bi-person-workspace',
            'title' => 'Staffs',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_examination();',
            'icon' => 'bi-pencil',
            'title' => 'Examination',
            'level' => 'any',
            'active' => true
        ]
    ],


    'Finance' => [

        [
            'onclick' => 'report_menu_my_collection();',
            'icon' => 'bi-wallet2',
            'title' => 'My Cash',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_daily_collection();',
            'icon' => 'bi-bank',
            'title' => 'Vault',
            'level' => ['Administrator', 'Super Administrator', 'Accountants'],
            'active' => true
        ]
    ],


    'Resources' => [

        [
            'onclick' => 'report_menu_ebooks_x();',
            'icon' => 'bi-book-fill',
            'title' => 'E-Library',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_calendar();',
            'icon' => 'bi-calendar-event',
            'title' => 'Calendar',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_notices();',
            'icon' => 'bi-megaphone',
            'title' => 'Notices',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_notification();',
            'icon' => 'bi-chat-dots',
            'title' => 'Inbox',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_syllabus();',
            'icon' => 'bi-book-half',
            'title' => 'Syllabus',
            'level' => 'any',
            'active' => true
        ],

        [
            'onclick' => 'report_menu_lesson_plan();',
            'icon' => 'bi-book',
            'title' => 'Lesson Plan',
            'level' => 'any',
            'active' => true
        ]
    ]

];


/* ============================
   MY CLASS CONDITION
============================ */

$count_class = count($cteacher_data ?? []);

if ($count_class > 0) {

    array_unshift(
        $categories['Academic'],
        [
            'onclick' => 'report_menu_my_class();',
            'icon' => 'bi-microsoft-teams',
            'title' => 'My Class',
            'level' => 'any',
            'active' => true
        ]
    );
}

?>

<style>
.m3-disabled{
    opacity:.45;
    filter:grayscale(1);
    pointer-events:none;
    background:#f4f4f4!important;
}

.m3-disabled .icon-box{
    background:#ddd!important;
    color:#999!important;
}

.m3-disabled .report-title{
    color:#888!important;
}
</style>


<main class="pb-0">

<?php foreach ($categories as $cat_name => $items): ?>

    <?php
    $visible_count = 0;

    foreach ($items as $item) {

        if (
            $item['level'] === 'any' ||
            (is_array($item['level']) && in_array($userlevel, $item['level']))
        ) {
            $visible_count++;
        }
    }

    if ($visible_count === 0) continue;
    ?>

    <div class="m3-category-lbl"><?= $cat_name ?></div>

    <div class="m3-grid">

        <?php foreach ($items as $item): ?>

            <?php
            $is_allowed = (
                $item['level'] === 'any' ||
                (is_array($item['level']) && in_array($userlevel, $item['level']))
            );

            if (!$is_allowed) continue;

            $active = $item['active'] ?? true;
            ?>

            <a href="javascript:void(0);"
               class="m3-report-card shadow-sm <?= !$active ? 'm3-disabled' : '' ?>"
               <?= $active ? 'onclick="'.$item['onclick'].'"' : '' ?>>

                <div class="icon-box" style="margin-right:0;margin-bottom:10px;">
                    <i class="bi <?= $item['icon'] ?>"></i>
                </div>

                <span class="report-title"><?= $item['title'] ?></span>

            </a>

        <?php endforeach; ?>

    </div>

<?php endforeach; ?>

</main>


<?php include 'footer.php'; ?>
