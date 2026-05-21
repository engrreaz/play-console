<style>
    /* New Material 3 Flat & Tonal Dashboard Component Styles */
    .m3-flat-section {
        background: #fff;
        margin: 0 12px 12px 12px;
        padding: 16px;
        border-radius: 8px;
        border: 1px solid #EADDFF;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 800;
        color: #1C1B1F;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .m3-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #F3EDF7;
        color: #6750A4;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: none;
        transition: background 0.2s;
    }

    .m3-icon-btn:active {
        background: #EADDFF;
    }

    /* Attendance Dot Graph & Rings */
    .attendance-dots {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        background: #FBEEFF;
        padding: 8px;
        border-radius: 6px;
    }

    .att-dot {
        width: 10px;
        height: 10px;
        border-radius: 2px;
        /* M3 Flat Small Geometry */
    }

    .att-present {
        background-color: #2E7D32;
    }

    .att-absent {
        background-color: #D32F2F;
    }

    .att-leave {
        background-color: #FBC02D;
    }

    .att-future {
        background-color: #E0E0E0;
    }

    /* Progress Ring & Bars for Performance & Fees */
    .tonal-progress-bar {
        height: 8px;
        background: #F3EDF7;
        border-radius: 4px;
        overflow: hidden;
    }

    .tonal-progress-fill {
        height: 100%;
        background: #6750A4;
        border-radius: 4px;
    }

    /* Co-Curricular Badges */
    .m3-badge-tonal {
        background: #E8DEF8;
        color: #1D192B;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .block-unit {
        margin:0 !important;
        border-radius:0 !important;
    }
</style>

<?php
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear']
    ?? $_COOKIE['query-session']
    ?? $sy;
$sy_param = '%' . $current_session . '%';

$sess_info = "SELECT * FROM sessioninfo where sccode='$sccode' AND sessionyear LIKE '$sy_param' and stid='$userid' ORDER BY sessionyear DESC LIMIT 1";
$sess_result = mysqli_query($conn, $sess_info);
if ($sess_result && mysqli_num_rows($sess_result) > 0) {
    $sess_row = mysqli_fetch_assoc($sess_result);
    $cls = $sess_row['classname'] ?? 'N/A';
    $sec = $sess_row['sectionname'] ?? 'N/A';
    $rollno = $sess_row['rollno'] ?? 'N/A';
} else {
    $cls = 'N/A';
    $sec = 'N/A';
    $rollno = 'N/A';
}

include_once 'data/student_dashboard_data.php';

/**
 * ব্যাকএন্ড ডেটা ম্যাপিং (Fallback structure)
 */
$attendance_summary = $student_dashboard_data['attendance_summary'] ?? [
    'status_today' => 'Present',
    'present_count' => 18,
    'absent_count' => 2,
    'leave_count' => 1,
    'total_days' => 21,
    'monthly_dots' => ['P', 'P', 'P', 'A', 'P', 'P', 'P', 'P', 'L', 'P', 'P', 'P', 'P', 'P', 'A', 'P', 'P', 'P', 'P', 'P', 'P']
];

$financial_summary = $student_dashboard_data['financial_summary'] ?? [
    'total_due' => 4500,
    'paid_amount' => 12000,
    'currency' => '৳'
];

$exam_summary = $student_dashboard_data['exam_summary'] ?? [
    'exam_name' => 'Term 1 Final',
    'gpa' => '3.85',
    'total_marks' => '82%',
    'highest_gpa' => '4.00'
];

$activities_summary = $student_dashboard_data['activities_summary'] ?? [
    ['name' => 'Debate Club', 'role' => 'Member'],
    ['name' => 'Football Team', 'role' => 'Striker'],
];
?>

<style>
    body {
        background-color: #FAF8FC;
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
        font-family: system-ui, -apple-system, sans-serif;
    }

    /* 1. Modern Minimalist Hero Banner (No Cards) */
    .student-modern-hero {
        background: #FFFFFF;
        padding: 28px 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        border-bottom: 1px solid #ECE6F0;
    }

    .modern-squircle-avatar {
        width: 84px;
        height: 84px;
        border-radius: 24px;
        /* Material 3 Squircle Form Metric */
        object-fit: cover;
        background: #F3EDF7;
        border: 1px solid #EADDFF;
    }

    .hero-info-block {
        flex-grow: 1;
    }

    .st-modern-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1C1B1F;
        letter-spacing: -0.3px;
        margin-bottom: 6px;
    }

    .meta-chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .modern-meta-badge {
        font-size: 0.68rem;
        font-weight: 700;
        background: #F3EDF7;
        color: #4F378B;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* 2. Pure Flat & Card-less Main Row Layout */
    .flat-row-section {
        background: transparent;
        padding: 16px 20px;
        border-bottom: 1px solid #ECE6F0;
    }

    .flat-row-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .flat-row-title {
        font-size: 0.75rem;
        font-weight: 800;
        color: #1C1B1F;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .m3-link-icon {
        color: #6750A4;
        text-decoration: none;
        font-size: 1.1rem;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .m3-link-icon:active {
        background: #EADDFF;
    }

    /* Attendance Dot Grid Optimization */
    .attendance-grid-flat {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        padding: 4px 0;
    }

    .flat-dot {
        width: 10px;
        height: 10px;
        border-radius: 5px;
    }

    .att-present {
        background-color: #2E7D32;
    }

    .att-absent {
        background-color: #D32F2F;
    }

    .att-leave {
        background-color: #FBC02D;
    }

    .att-future {
        background-color: #E6E1E5;
    }

    /* Performance Tonal Bar */
    .flat-progress-container {
        height: 6px;
        background: #E6E1E5;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 6px;
    }

    .flat-progress-fill {
        height: 100%;
        background: #6750A4;
    }

    /* Minimal Badges for Activities */
    .flat-tag {
        background: #E8DEF8;
        color: #1D192B;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #EADDFF;
    }

    /* Flat Task Row Object */
    .flat-task-item {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #F4EFF4;
        background: #FFFFFF;
    }

    .flat-task-icon {
        font-size: 1.25rem;
        margin-right: 16px;
        display: flex;
        align-items: center;
    }

    .task-txt-title {
        font-weight: 700;
        color: #1C1B1F;
        font-size: 0.88rem;
    }

    .task-txt-meta {
        font-size: 0.7rem;
        color: #79747E;
        font-weight: 500;
    }

    .btn-flat-action {
        font-size: 0.7rem;
        font-weight: 800;
        border-radius: 8px;
        padding: 6px 14px;
        background: #6750A4;
        color: #FFFFFF;
        border: none;
        letter-spacing: 0.3px;
    }

    .btn-flat-action:active {
        background: #4F378B;
    }
</style>

<main class="pb-1">

    <!-- MODERN STUDENT HERO (CARD-LESS SMART BANNER) -->
    <div class="student-modern-hero">
        <img src="<?php echo htmlspecialchars($pth); ?>" class="modern-squircle-avatar"
            onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
        <div class="hero-info-block">
            <div class="st-modern-name text-info"><?php echo htmlspecialchars($stnameeng); ?></div>
            <div class="mt-0 fs-tiny">ID # <?= $userid ?></div>
            <div class="meta-chip-row">
                <span class="modern-meta-badge">Class <?php echo $cls; ?></span>
                <span class="modern-meta-badge">Sec <?php echo $sec; ?></span>
                <span class="modern-meta-badge">Roll: <?php echo $rollno; ?></span>
            </div>
        </div>
    </div>

    <!-- MAIN DATA SECTION (COMPLETE FLAT STRATIFICATION) -->
    <div id="main-data">

        <!-- 1. Attendance Profile -->
        <div class="flat-row-section">
            <div class="flat-row-header">
                <div class="flat-row-title" style="color: #6750A4;">Attendance Status</div>
                <a href="attendance_details.php" class="m3-link-icon" title="Details"><i
                        class="bi bi-chevron-right"></i></a>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-bold text-dark">
                    Today Status:
                    <span class="text-success fw-extrabold"><?php echo $attendance_summary['status_today']; ?></span>
                </span>
                <span class="text-muted" style="font-size: 0.75rem; font-weight: 600;">
                    P:<?php echo $attendance_summary['present_count']; ?>
                    A:<?php echo $attendance_summary['absent_count']; ?>
                    L:<?php echo $attendance_summary['leave_count']; ?>
                </span>
            </div>

            <div class="attendance-grid-flat">
                <?php foreach ($attendance_summary['monthly_dots'] as $dot):
                    $dot_class = 'att-future';
                    if ($dot === 'P')
                        $dot_class = 'att-present';
                    if ($dot === 'A')
                        $dot_class = 'att-absent';
                    if ($dot === 'L')
                        $dot_class = 'att-leave';
                    ?>
                    <div class="flat-dot <?php echo $dot_class; ?>"></div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 2. Financial Context -->
        <div class="flat-row-section">
            <div class="flat-row-header">
                <div class="flat-row-title" style="color: #B3261E;">Ledger & Fees</div>
                <a href="payment_history.php" class="m3-link-icon" style="color: #B3261E;"><i
                        class="bi bi-chevron-right"></i></a>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted" style="font-size: 0.65rem; font-weight: 700; display:block;">NET PAYABLE
                        DUE</span>
                    <span class="fs-4 fw-black text-danger"
                        style="font-weight: 900;"><?php echo $financial_summary['currency'] . number_format($financial_summary['total_due']); ?></span>
                </div>
                <div class="text-end text-muted small fw-bold">
                    Cleared: <span
                        class="text-dark"><?php echo $financial_summary['currency'] . number_format($financial_summary['paid_amount']); ?></span>
                </div>
            </div>
        </div>

        <!-- 3. Academic Evaluation -->
        <div class="flat-row-section">
            <div class="flat-row-header">
                <div class="flat-row-title" style="color: #00639B;">Latest Academic Result</div>
                <a href="report_card.php" class="m3-link-icon" style="color: #00639B;"><i
                        class="bi bi-chevron-right"></i></a>
            </div>
            <div class="d-flex justify-content-between align-items-top">
                <div class="w-100 me-3">
                    <span class="fw-bold text-dark d-block mb-1"
                        style="font-size: 0.85rem;"><?php echo $exam_summary['exam_name']; ?></span>
                    <span class="text-muted d-block" style="font-size: 0.65rem; font-weight: 600;">Overall Exam Mark
                        Ratio</span>
                    <div class="flat-progress-container w-75">
                        <div class="flat-progress-fill"
                            style="width: <?php echo $exam_summary['total_marks']; ?>; background-color: #00639B;">
                        </div>
                    </div>
                </div>
                <div class="text-end style-gpa-box">
                    <span class="text-muted d-block" style="font-size: 0.6rem; font-weight: 800;">GPA</span>
                    <span class="fs-3 fw-black text-primary"
                        style="color: #00639B !important; font-weight:900; line-height:1;"><?php echo $exam_summary['gpa']; ?></span>
                </div>
            </div>
        </div>

        <!-- 4. Extra-Curricular Profiling -->
        <div class="flat-row-section">
            <div class="flat-row-header">
                <div class="flat-row-title" style="color: #006A60;">Talent & Activities</div>
                <a href="activities_details.php" class="m3-link-icon" style="color: #006A60;"><i
                        class="bi bi-chevron-right"></i></a>
            </div>
            <div class="d-flex flex-wrap gap-1 mt-1">
                <?php if (!empty($activities_summary)): ?>
                    <?php foreach ($activities_summary as $act): ?>
                        <div class="flat-tag" style="background: #E0F2F1; color: #004D40; border-color: #B2DFDB;">
                            <i class="bi bi-award-fill text-success"></i>
                            <span><?php echo htmlspecialchars($act['name']); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="text-muted small" style="font-size: 0.7rem; font-style: italic;">No active tags
                        found.</span>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- DAILY TASKS CONTAINER -->
    <div class="px-3 pt-3 pb-2 d-flex justify-content-between align-items-center bg-white">
        <span class="fw-extrabold text-dark small text-uppercase"
            style="letter-spacing: 0.8px; font-size: 0.75rem;">Daily Progress Checklist</span>
        <?php if (!empty($student_dashboard_data['daily_tasks'])): ?>
            <span class="text-primary fw-bold"
                style="font-size: 0.65rem;"><?php echo count($student_dashboard_data['daily_tasks']); ?> ASYNC TASKS</span>
        <?php endif; ?>
    </div>

    <!-- CARDLESS LIST VIEW FOR TASKS -->
    <div class="bg-white">
        <?php if (!empty($student_dashboard_data['daily_tasks'])): ?>
            <?php foreach ($student_dashboard_data['daily_tasks'] as $task):
                $is_ok = ($task['responsetime'] !== null);
                ?>
                <div class="flat-task-item">
                    <div class="flat-task-icon <?php echo $is_ok ? 'text-success' : 'text-warning'; ?>">
                        <i class="bi <?php echo $is_ok ? 'bi-check-circle-fill' : 'bi-dash-circle'; ?>"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="task-txt-title text-truncate"><?php echo htmlspecialchars($task['subject_name_en']); ?>
                        </div>
                        <div class="task-txt-meta">
                            <?php echo $is_ok ? 'Archived at ' . date('g:i a', strtotime($task['responsetime'])) : 'Pending operational check'; ?>
                        </div>
                    </div>
                    <?php if (!$is_ok): ?>
                        <form action="updtracking.php" method="POST" class="ms-2 m-0">
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                            <button type="submit" class="btn-flat-action">OK</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 opacity-50">
                <i class="bi bi-clipboard-check display-4 text-muted"></i>
                <p class="fw-bold mt-2 small text-muted">All active instances resolved.</p>
            </div>
        <?php endif; ?>
    </div>




    <div id="blocksContainer" class="mt-1">
        <?php
        foreach ($blocks as $id => $info):
            $valid_user = $info['role'] ?? '';
            $roles = array_map('trim', explode('|', $valid_user));

            if (in_array($userlevel, $roles)) {
                ?>
                <div class="block-unit shadow-sm" id="block-<?php echo $id; ?>" data-id="<?php echo $id; ?>">
                    <?php
                    // ফাইলটি লোড করার আগে চেক করে নিন সেটি সঠিক কি না
                    include 'front-page-block/' . $info['link'];
                    ?>
                </div>
                <?php
            }
        endforeach;
        ?>
    </div>









</main>