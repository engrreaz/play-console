<?php
$page_title = "Management Tools";
include_once 'inc.php'; 

// সেশন ইয়ার নিশ্চিত করা
$current_session = $current_session ?? $sy;

/**
 * ২. মডিউল কনফিগারেশন
 */
$academic_tools = [
    [
        'title' => 'Marks Entry',
        'desc' => 'Manage exam marks & results',
        'icon' => 'bi-pencil-square',
        'color' => 'c-inst', 
        'url' => 'markentryselect.php'
    ],
    [
        'title' => 'Class Test',
        'desc' => 'Assessments & class tests',
        'icon' => 'bi-journal-check',
        'color' => 'c-util', 
        'url' => 'class_test.php'
    ],
    [
        'title' => 'Co-Curricular',
        'desc' => 'Extra-curricular activities',
        'icon' => 'bi-trophy',
        'color' => 'c-fina', 
        'url' => 'co_curricular_entry.php'
    ]
];

$admin_tools = [
    [
        'title' => 'Leave Applications',
        'desc' => 'Respond to staff leave requests',
        'icon' => 'bi-file-earmark-person',
        'color' => 'c-exit', 
        'url' => 'leave-application-response.php'
    ],
    [
        'title' => 'Notice Manager',
        'desc' => 'Broadcast institute notifications',
        'icon' => 'bi-megaphone',
        'color' => 'c-acad',
        'url' => 'add-notice.php'
    ]
];
?>

<style>
    /* ক্লিক নিশ্চিত করার জন্য স্টাইল */
    .m3-list-item {
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        display: flex !important; /* নিশ্চিত করে যে এটি পুরো জায়গা নেবে */
        align-items: center;
        margin-bottom: 10px;
    }
    
    /* হিরো কন্টেইনার অ্যাডজাস্টমেন্ট */
  
</style>

<main>


    <div class="widget-grid" style="padding-bottom: 100px;">
        
        <div class="m3-section-title px-3">Class Management</div>
        <?php if (!empty($cteacher_data)): ?>
            <?php foreach ($cteacher_data as $class): 
                $lnk = "cls=" . urlencode($class['cteachercls']) . "&sec=" . urlencode($class['cteachersec']) . "&year=" . $current_session;
            ?>
                <div class="m3-list-item shadow-sm" onclick="go('stattnd.php', '<?php echo $lnk; ?>')">
                    <div class="icon-box c-acad">
                        <i class="bi bi-fingerprint"></i>
                    </div>
                    <div class="item-info">
                        <div class="st-title">Roll Call</div>
                        <div class="st-desc">Attendance for <b><?php echo $class['cteachercls'] . " (" . $class['cteachersec'] . ")"; ?></b></div>
                    </div>
                    <div style="color: var(--m3-outline); opacity: 0.3;"><i class="bi bi-chevron-right"></i></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="m3-list-item shadow-sm" onclick="go('stattnd.php', 'year=<?php echo $current_session; ?>')">
                <div class="icon-box c-inst">
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="item-info">
                    <div class="st-title">General Attendance</div>
                    <div class="st-desc">Student presence tracking system</div>
                </div>
                <div style="color: var(--m3-outline); opacity: 0.3;"><i class="bi bi-chevron-right"></i></div>
            </div>
        <?php endif; ?>

        <div class="m3-section-title px-3 mt-3">Academic Excellence</div>
        <?php foreach ($academic_tools as $item): ?>
            <div class="m3-list-item shadow-sm" onclick="go('<?php echo $item['url']; ?>', 'year=<?php echo $current_session; ?>')">
                <div class="icon-box <?php echo $item['color']; ?>">
                    <i class="bi <?php echo $item['icon']; ?>"></i>
                </div>
                <div class="item-info">
                    <div class="st-title"><?php echo $item['title']; ?></div>
                    <div class="st-desc"><?php echo $item['desc']; ?></div>
                </div>
                <div style="color: var(--m3-outline); opacity: 0.3;"><i class="bi bi-chevron-right"></i></div>
            </div>
        <?php endforeach; ?>

        <div class="m3-section-title px-3 mt-3">Administration</div>
        <?php foreach ($admin_tools as $item): ?>
            <div class="m3-list-item shadow-sm" onclick="go('<?php echo $item['url']; ?>', 'year=<?php echo $current_session; ?>')">
                <div class="icon-box <?php echo $item['color']; ?>">
                    <i class="bi <?php echo $item['icon']; ?>"></i>
                </div>
                <div class="item-info">
                    <div class="st-title"><?php echo $item['title']; ?></div>
                    <div class="st-desc"><?php echo $item['desc']; ?></div>
                </div>
                <div style="color: var(--m3-outline); opacity: 0.3;"><i class="bi bi-chevron-right"></i></div>
            </div>
        <?php endforeach; ?>

    </div>
</main>



<script>
    /**
     * নেভিগেশন ফাংশন (ক্লিক কাজ না করার সমাধান)
     * @param {string} page - গন্তব্য পেজ
     * @param {string} params - কুয়েরি প্যারামিটার
     */
    function go(page, params) {
        if (!page) return;
        const url = params ? `${page}?${params}` : page;
        window.location.href = url;
    }
</script>

<div style="height: 80px;"></div> 
<?php include_once 'footer.php'; ?>