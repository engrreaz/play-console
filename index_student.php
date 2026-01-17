<?php
// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

@include_once 'data/student_dashboard_data.php'; 
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width Top Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; height: 56px; background: #fff; display: flex; align-items: center; 
        padding: 0 16px; position: sticky; top: 0; z-index: 1050; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Large Photo Hero Section (8px Radius) */
    .student-hero {
        background: #fff; padding: 24px 16px; text-align: center;
        margin-bottom: 12px; border-radius: 0 0 8px 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .large-avatar {
        width: 120px; height: 120px; border-radius: 8px; /* গাইডলাইন অনুযায়ী ৮ পিক্সেল */
        object-fit: cover; border: 4px solid #F3EDF7;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.12);
        margin-bottom: 12px;
    }

    .st-name { font-size: 1.1rem; font-weight: 800; color: #1C1B1F; margin-bottom: 2px; }
    .st-meta { font-size: 0.75rem; font-weight: 700; color: #6750A4; text-transform: uppercase; }

    /* Quick Action Row (8px Radius) */
    .action-grid { display: flex; gap: 8px; padding: 0 12px; margin-bottom: 20px; }
    .m3-tonal-card {
        flex: 1; background: #F3EDF7; border-radius: 8px; padding: 12px 4px;
        text-align: center; text-decoration: none !important; color: #6750A4;
        transition: transform 0.15s ease; border: 1px solid #EADDFF;
    }
    .m3-tonal-card:active { transform: scale(0.95); background: #EADDFF; }
    .m3-tonal-card i { font-size: 1.5rem; display: block; margin-bottom: 4px; }
    .m3-tonal-card span { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; }

    /* Task Item (Condensed & 8px Radius) */
    .m3-task-card {
        background: #fff; border-radius: 8px; padding: 10px 12px; margin: 0 12px 8px;
        display: flex; align-items: center; border: 1px solid #f0f0f0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .status-icon-box {
        width: 36px; height: 36px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 12px; font-size: 1.1rem;
    }
    .c-done { background: #E8F5E9; color: #2E7D32; }
    .c-wait { background: #FFF3E0; color: #E65100; }

    .task-title { font-weight: 700; color: #1D1B20; font-size: 0.85rem; }
    .task-sub { font-size: 0.65rem; color: #79747E; font-weight: 600; }

    .btn-done-sm {
        font-size: 0.65rem; font-weight: 800; border-radius: 6px;
        padding: 4px 10px; background: #6750A4; color: #fff; border: none;
    }

    .session-indicator {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
        <i class="bi bi-person-workspace"></i>
    </div>
    <h1 class="page-title">Student Dashboard</h1>
    <div class="action-icons">
        <span class="session-indicator"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5 mt-1">
    <div class="student-hero">
        <img src="<?php echo htmlspecialchars($pth); ?>" class="large-avatar shadow-sm" 
             onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
        <div class="st-name"><?php echo htmlspecialchars($stnameeng); ?></div>
        <div class="st-meta">
            Class <?php echo $cls; ?> <i class="bi bi-dot"></i> Section <?php echo $sec; ?> <i class="bi bi-dot"></i> Roll <?php echo $rollno; ?>
        </div>
    </div>

    <div class="action-grid">
        <a href="stguarresult.php?year=<?php echo $current_session; ?>" class="m3-tonal-card shadow-sm">
            <i class="bi bi-mortarboard-fill"></i>
            <span>Results</span>
        </a>
        <a href="stguarattnd.php?year=<?php echo $current_session; ?>" class="m3-tonal-card shadow-sm">
            <i class="bi bi-fingerprint"></i>
            <span>Attendance</span>
        </a>
        <a href="stguarmore.php?stid=<?php echo $userid; ?>&year=<?php echo $current_session; ?>" class="m3-tonal-card shadow-sm">
            <i class="bi bi-grid-3x3-gap-fill"></i>
            <span>More</span>
        </a>
    </div>

    <div class="px-3 mb-2 d-flex justify-content-between align-items-center">
        <span class="fw-bold text-muted small text-uppercase" style="letter-spacing: 1px;">Daily Tasks</span>
        <?php if(!empty($student_dashboard_data['daily_tasks'])): ?>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.6rem;"><?php echo count($student_dashboard_data['daily_tasks']); ?> ACTIVE</span>
        <?php endif; ?>
    </div>

    <div class="px-1">
        <?php if(!empty($student_dashboard_data['daily_tasks'])): ?>
            <?php foreach($student_dashboard_data['daily_tasks'] as $task): 
                $is_ok = ($task['responsetime'] !== null);
            ?>
                <div class="m3-task-card shadow-sm">
                    <div class="status-icon-box <?php echo $is_ok ? 'c-done' : 'c-wait'; ?>">
                        <i class="bi <?php echo $is_ok ? 'bi-check-all' : 'bi-hourglass-split'; ?>"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="task-title text-truncate"><?php echo htmlspecialchars($task['subject_name_en']); ?></div>
                        <div class="task-sub">
                            <?php echo $is_ok ? 'Completed at ' . date('g:i a', strtotime($task['responsetime'])) : 'Awaiting submission'; ?>
                        </div>
                    </div>
                    <?php if(!$is_ok): ?>
                        <form action="updtracking.php" method="POST" class="ms-2 m-0">
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                            <button type="submit" class="btn-done-sm shadow-sm">MARK OK</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-4 opacity-25">
                <i class="bi bi-emoji-sunglasses display-4"></i>
                <p class="fw-bold mt-2">All caught up!</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-2">
        <?php @include_once 'front-page-block/schedule.php'; ?>
        <?php @include_once 'front-page-block/notice.php'; ?>
    </div>
</main>

<div style="height: 65px;"></div> <?php include 'footer.php'; ?>