<?php
// File: index_student.php
// Refactored for Android WebView with Material 3 Design

@include_once 'data/student_dashboard_data.php'; 
// Note: $stnameeng, $stnameben, $cls, $sec, $rollno, $userid, $pth are provided by index.php
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-secondary-container: #E8DEF8;
    }

    body { background-color: var(--m3-surface); }

    /* Profile Hero Section */
    .profile-hero {
        background-color: var(--m3-primary-container);
        border-radius: 28px;
        padding: 24px;
        margin-bottom: 20px;
        border: none;
    }

    .hero-avatar {
        width: 72px;
        height: 72px;
        border-radius: 20px; /* Squircle style */
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Quick Action Chips */
    .action-card {
        background: white;
        border-radius: 20px;
        padding: 16px;
        text-align: center;
        transition: transform 0.2s;
        border: 1px solid #eee;
        height: 100%;
    }
    .action-card:active { transform: scale(0.95); background-color: #f0f0f0; }
    .action-card i { font-size: 1.75rem; margin-bottom: 8px; display: block; }
    .action-label { font-size: 0.8rem; font-weight: 600; color: #49454F; }

    /* Task List Styling */
    .task-item {
        background: white;
        border-radius: 16px;
        margin-bottom: 10px;
        padding: 12px 16px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .task-status-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
</style>

<div class="container px-3 pb-5">
    
    <div class="profile-hero shadow-sm mt-3">
        <div class="d-flex align-items-center">
            <img src="<?php echo htmlspecialchars($pth); ?>" class="hero-avatar me-3" 
                 onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($stnameeng); ?></h5>
                <div class="text-muted small mb-2"><?php echo htmlspecialchars($stnameben); ?></div>
                <div class="d-flex gap-2">
                    <span class="badge rounded-pill bg-white text-dark border px-2 py-1">Roll: <?php echo $rollno; ?></span>
                    <span class="badge rounded-pill bg-white text-dark border px-2 py-1"><?php echo $cls; ?> - <?php echo $sec; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2 mb-4">
        <div class="col-4">
            <a href="stguarresult.php" class="text-decoration-none">
                <div class="action-card shadow-sm">
                    <i class="bi bi-mortarboard-fill text-primary"></i>
                    <span class="action-label">Results</span>
                </div>
            </a>
        </div>
        <div class="col-4">
            <a href="stguarattnd.php" class="text-decoration-none">
                <div class="action-card shadow-sm">
                    <i class="bi bi-calendar-check-fill text-success"></i>
                    <span class="action-label">Attendance</span>
                </div>
            </a>
        </div>
        <div class="col-4">
            <a href="stguarmore.php?stid=<?php echo htmlspecialchars($userid); ?>" class="text-decoration-none">
                <div class="action-card shadow-sm">
                    <i class="bi bi-grid-3x3-gap-fill text-secondary"></i>
                    <span class="action-label">More</span>
                </div>
            </a>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0 text-secondary small text-uppercase tracking-wider">My Daily Tasks</h6>
        <?php if(!empty($student_dashboard_data['daily_tasks'])): ?>
            <span class="badge bg-secondary-subtle text-secondary rounded-pill"><?php echo count($student_dashboard_data['daily_tasks']); ?> Active</span>
        <?php endif; ?>
    </div>

    <?php if(!empty($student_dashboard_data['daily_tasks'])): ?>
        <div class="d-flex flex-column gap-1">
            <?php foreach($student_dashboard_data['daily_tasks'] as $task): 
                $is_complete = ($task['responsetime'] !== null);
                $status_bg = $is_complete ? 'bg-success-subtle' : 'bg-warning-subtle';
                $status_color = $is_complete ? 'text-success' : 'text-warning';
            ?>
            <div class="task-item d-flex align-items-center shadow-sm">
                <div class="task-status-icon <?php echo $status_bg . ' ' . $status_color; ?> me-3">
                    <i class="bi <?php echo $is_complete ? 'bi-check-lg' : 'bi-clock-history'; ?>"></i>
                </div>
                
                <div class="flex-grow-1">
                    <div class="fw-bold text-dark small"><?php echo htmlspecialchars($task['subject_name_en']); ?></div>
                    <?php if($is_complete): ?>
                        <div class="text-success" style="font-size: 0.7rem;">Done at <?php echo date('g:i a', strtotime($task['responsetime'])); ?></div>
                    <?php else: ?>
                        <div class="text-muted" style="font-size: 0.7rem;">Waiting for submission</div>
                    <?php endif; ?>
                </div>

                <?php if(!$is_complete): ?>
                <div class="ms-2">
                    <form action="updtracking.php" method="POST" class="m-0">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id']); ?>">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" style="font-size: 0.7rem;">MARK DONE</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-4 bg-white rounded-4 shadow-sm">
            <i class="bi bi-emoji-smile fs-2 text-muted opacity-50"></i>
            <p class="text-muted small mt-2">No tasks assigned for today!</p>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <?php @include_once 'front-page-block/schedule.php'; ?>
        <?php @include_once 'front-page-block/notice.php'; ?>
    </div>

</div>

<div style="height: 60px;"></div>