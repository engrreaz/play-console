<?php
// ডাটাবেজ থেকে আজকের রুটিন ফেচ করার অপ্টিমাইজড কোড (এটি ফাংশন ফাইলে রাখলে ভালো)
$day_today = date('l'); // e.g., 'Monday'
$now_time = date('H:i:s');

// ১. বর্তমান ক্লাস এবং পরবর্তী ক্লাস একসাথে বের করার কোয়েরি
// নোট: আপনার টেবিলের নাম 'routine' এবং কলাম 'period_start', 'period_end' ধরে নিচ্ছি।
$stmt_sched = $conn->prepare("
    (SELECT *, 'current' as status FROM classroutine 
     WHERE sccode = ? AND day = ? AND ? BETWEEN periodtime AND periodtimeend LIMIT 1)
    UNION
    (SELECT *, 'next' as status FROM classroutine 
     WHERE sccode = ? AND day = ? AND periodtime > ? 
     ORDER BY periodtime ASC LIMIT 1)
");

$stmt_sched->bind_param("isssis", $sccode, $day_today, $now_time, $sccode, $day_today, $now_time);
$stmt_sched->execute();
$res_sched = $stmt_sched->get_result();

$current_class = null;
$next_class = null;

while ($row = $res_sched->fetch_assoc()) {
    if ($row['status'] == 'current') $current_class = $row;
    else $next_class = $row;
}
?>

<div class="m-card elevation-1 mb-4 border-0" style="background-color: var(--md-surface-variant, #f0f0f0);">
    <div class="d-flex align-items-center mb-3">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
            <i class="bi bi-clock-fill fs-6"></i>
        </div>
        <h6 class="ms-2 mb-0 fw-bold">Class Schedule</h6>
    </div>

    <?php if ($current_class): ?>
        <div class="p-3 rounded-4 bg-white border border-primary-subtle position-relative overflow-hidden shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge rounded-pill text-bg-success mb-2 px-3 py-1">
                        <span class="spinner-grow spinner-grow-sm me-1" role="status"></span> ONGOING
                    </span>
                    <h4 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($current_class['subject']); ?></h4>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-geo-alt me-1"></i> Room: <?php echo htmlspecialchars($current_class['room_no'] ?? 'N/A'); ?>
                    </p>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-primary"><?php echo date('h:i A', strtotime($current_class['period_end'])); ?></div>
                    <div class="small text-muted">Ends at</div>
                </div>
            </div>
            
            <div class="progress mt-3" style="height: 4px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 65%;"></div>
            </div>
        </div>

        <?php if ($next_class): ?>
            <div class="mt-3 d-flex align-items-center p-2 rounded-3" style="background: rgba(0,0,0,0.03);">
                <i class="bi bi-arrow-right-circle me-2 text-secondary"></i>
                <div class="small flex-grow-1">
                    <span class="text-muted">Next up: </span>
                    <span class="fw-medium"><?php echo htmlspecialchars($next_class['subject']); ?></span>
                </div>
                <div class="small fw-bold text-secondary">
                    <?php echo date('h:i A', strtotime($next_class['period_start'])); ?>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($next_class): ?>
        <div class="text-center py-3">
            <div class="mb-2 text-primary">
                <i class="bi bi-hourglass-split display-6"></i>
            </div>
            <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($next_class['subject']); ?></h5>
            <p class="text-muted small mb-3">Starting at <?php echo date('h:i A', strtotime($next_class['period_start'])); ?></p>
            <button class="btn btn-outline-primary btn-sm rounded-pill px-4">View Full Routine</button>
        </div>

    <?php else: ?>
        <div class="text-center py-4 text-muted">
            <i class="bi bi-calendar-check fs-1 opacity-25"></i>
            <p class="mt-2 mb-0 small">Great! All classes finished for today.</p>
        </div>
    <?php endif; ?>
</div>