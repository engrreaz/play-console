<?php
// ফাইল: front-page-block/task-teacher.php

$todo_list_today = [];

if (isset($conn, $sccode, $td, $usr, $cur)) {
    // ১. হাজিরা টাস্ক চেক (Prepared Statement - Secure)
    $stmt_check = $conn->prepare("SELECT id FROM todolist WHERE date = ? AND sccode = ? AND user = ? AND todotype = 'attendance'");
    $stmt_check->bind_param("sss", $td, $sccode, $usr);
    $stmt_check->execute();
    $attendance_task_exists = ($stmt_check->get_result()->num_rows > 0);
    $stmt_check->close();

    // টাস্ক না থাকলে তৈরি করা (Daily Auto-Generation)
    if (!$attendance_task_exists) {
        $stmt_insert = $conn->prepare("INSERT INTO todolist (sccode, date, user, todotype, status, creationtime, response, responsetxt) VALUES (?, ?, ?, 'Attendance', 0, ?, 'geoattnd', 'Submit')");
        $stmt_insert->bind_param("ssss", $sccode, $td, $usr, $cur);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    // আজকের সব টাস্ক ফেচ করা
    $stmt_fetch = $conn->prepare("SELECT * FROM todolist WHERE date = ? AND sccode = ? AND user = ? ORDER BY status ASC");
    $stmt_fetch->bind_param("sss", $td, $sccode, $usr);
    $stmt_fetch->execute();
    $todo_list_today = $stmt_fetch->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_fetch->close();
}

if (!empty($todo_list_today)):
?>

<style>
    .m3-task-card { background: #fff; border-radius: 8px; padding: 12px; }
    
    .task-item-m3 {
        background-color: #F7F2FA; border-radius: 8px; padding: 10px 12px;
        margin-bottom: 8px; border: 1px solid #EADDFF; transition: transform 0.2s;
    }
    .task-item-m3:last-child { margin-bottom: 0; }
    
    .task-icon-box {
        width: 36px; height: 36px; border-radius: 8px; /* আপনার নির্দেশিত ৮ পিক্সেল */
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;
    }
    .bg-wait { background: #FFFBFE; color: #6750A4; border: 1px solid #CAC4D0; }
    .bg-done { background: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }

    .btn-m3-tonal {
        background-color: #6750A4; color: #fff; border-radius: 8px;
        padding: 6px 14px; font-size: 0.75rem; font-weight: 700; border: none;
    }
    .btn-m3-tonal:active { transform: scale(0.95); opacity: 0.9; }
</style>

<div class="m3-task-card shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="small fw-bold text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.8px;">
            <i class="bi bi-list-check me-1 text-primary"></i> Daily Assignments
        </span>
        <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.6rem;">
            <?php echo count($todo_list_today); ?> Tasks
        </span>
    </div>

    <div class="d-flex flex-column">
        <?php foreach ($todo_list_today as $task):
            $is_ok = ($task['status'] == 1);
            $msg = '';
            $can_submit = false;

            if ($task['todotype'] == 'Attendance') {
                if (!$is_ok) {
                    if (empty($geolat) || empty($geolon)) {
                        $msg = 'GPS Location Error! Please enable location.';
                    } else if (isset($distance, $tattndradius) && $distance < $tattndradius) {
                        $msg = 'Inside Institute Area. Ready to check-in.';
                        $can_submit = true;
                    } else {
                        $msg = 'Away: ' . ($distance ?? '??') . 'm. Reach within ' . ($tattndradius ?? '??') . 'm.';
                    }
                } else {
                    $msg = 'Successfully checked-in at ' . date('h:i A', strtotime($task['responsetime'] ?? $cur));
                }
            } else {
                $msg = $task['descrip1'];
            }
        ?>
            <div class="task-item-m3 shadow-sm d-flex align-items-center">
                <div class="task-icon-box <?php echo $is_ok ? 'bg-done' : 'bg-wait'; ?> me-3">
                    <i class="bi <?php echo $is_ok ? 'bi-check2-all' : 'bi-hourglass-split'; ?>"></i>
                </div>
                
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;">
                        <?php echo htmlspecialchars($task['todotype']); ?>
                    </div>
                    <div class="text-muted text-truncate" style="font-size: 0.7rem; font-weight: 500;">
                        <?php echo htmlspecialchars($msg); ?>
                    </div>
                </div>

                <?php if ($can_submit && !$is_ok): ?>
                    <a href="tattnd.php?id=<?php echo $task['id']; ?>" class="btn-m3-tonal ms-2 shadow-sm">
                        SUBMIT
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>