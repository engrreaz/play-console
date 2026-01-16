<?php
// File: front-page-block/task-teacher.php

// --- Data Fetching & Logic ---
$todo_list_today = [];

if (isset($conn, $sccode, $td, $usr, $cur)) {
    // Check if the daily attendance task already exists
    $stmt_check = $conn->prepare("SELECT id FROM todolist WHERE date = ? AND sccode = ? AND user = ? AND todotype = 'attendance'");
    $stmt_check->bind_param("sss", $td, $sccode, $usr);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $attendance_task_exists = ($result_check->num_rows > 0);
    $stmt_check->close();

    // If it doesn't exist, create it.
    // TODO: This logic should be moved to a better location, like a daily cron job or a function called upon login.
    if (!$attendance_task_exists) {
        $stmt_insert = $conn->prepare("
            INSERT INTO todolist (sccode, date, user, todotype, status, creationtime, response, responsetxt) 
            VALUES (?, ?, ?, 'Attendance', 0, ?, 'geoattnd', 'Submit')
        ");
        $stmt_insert->bind_param("ssss", $sccode, $td, $usr, $cur);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    // Fetch all of today's tasks
    $stmt_fetch = $conn->prepare("SELECT * FROM todolist WHERE date = ? AND sccode = ? AND user = ?");
    $stmt_fetch->bind_param("sss", $td, $sccode, $usr);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();
    $todo_list_today = $result_fetch->fetch_all(MYSQLI_ASSOC);
    $stmt_fetch->close();
}


// --- Presentation ---
if (!empty($todo_list_today)):
?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h6 class="card-title fw-bold mb-3">Today's To-Do List</h6>
        <ul class="list-group list-group-flush">
            <?php foreach ($todo_list_today as $task):
                $is_complete = ($task['status'] == 1);
                $task_message = '';
                $action_available = false;

                // Logic specific to the Attendance task
                if ($task['todotype'] == 'Attendance') {
                    if (!$is_complete) {
                        if (empty($geolat) || empty($geolon)) {
                            $task_message = 'GPS location not found. Please enable GPS and try again.';
                        } else if (isset($distance, $tattndradius) && $distance < $tattndradius) {
                            $task_message = 'You are in the institute area. You can submit your attendance now.';
                            $action_available = true;
                        } else {
                             $task_message = 'You are ' . ($distance ?? 'an unknown') . 'm away. Please be within ' . ($tattndradius ?? '?') . 'm of the institute.';
                        }
                    } else {
                        $task_message = 'Your attendance has been submitted successfully.';
                    }
                } else {
                    // For other task types
                    $task_message = $task['descrip1'];
                }
            ?>
            <li class="list-group-item px-0">
                <div class="d-flex w-100">
                    <div class="me-3 pt-1">
                        <i class="bi <?php echo $is_complete ? 'bi-check-circle-fill text-success' : 'bi-exclamation-circle-fill text-warning'; ?>" style="font-size: 1.25rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($task['todotype']); ?></h6>
                        <p class="mb-1 small text-muted"><?php echo htmlspecialchars($task_message); ?></p>
                    </div>
                    <?php if ($action_available && !$is_complete): ?>
                    <div class="ms-3 align-self-center">
                        <a href="tattnd.php?id=<?php echo htmlspecialchars($task['id']); ?>" class="btn btn-primary btn-sm">Submit</a>
                    </div>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php 
endif; 
?>
