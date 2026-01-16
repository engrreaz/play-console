<?php
// File: data/schedule_data.php
// This script fetches all data required for the main schedule/dashboard block.

if (!isset($conn)) {
    // Ensure the script is included by a file that has a DB connection.
    die("Database connection not found.");
}

$schedule_data = [
    'teachers' => [],
    'exam_schedule' => [],
    'subjects' => [],
    'current_period_info' => null,
    'class_routine_today' => [],
    'my_classes_now' => [],
    'status_message' => '',
    'status_type' => 'info', // e.g., 'warning', 'info', 'danger'
    'is_exam_day' => false
];

// 1. Fetch Teachers
$stmt_t = $conn->prepare("SELECT tid, tname FROM teacher WHERE sccode = ? ORDER BY tid");
$stmt_t->bind_param("s", $sccode);
$stmt_t->execute();
$schedule_data['teachers'] = $stmt_t->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_t->close();

// 2. Fetch Subjects
$stmt_sub = $conn->prepare("SELECT subcode, subject FROM subjects WHERE (sccode = ? OR sccode = 0) AND sccategory = ? ORDER BY subcode");
$stmt_sub->bind_param("ss", $sccode, $sctype);
$stmt_sub->execute();
$schedule_data['subjects'] = $stmt_sub->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_sub->close();

// 3. Check for Exams Today
$stmt_exam = $conn->prepare("SELECT * FROM examroutine WHERE sccode = ? AND date = ? ORDER BY time");
$stmt_exam->bind_param("ss", $sccode, $td);
$stmt_exam->execute();
$exam_results = $stmt_exam->get_result();
if ($exam_results->num_rows > 0) {
    $schedule_data['is_exam_day'] = true;
    $schedule_data['exam_schedule'] = $exam_results->fetch_all(MYSQLI_ASSOC);
}
$stmt_exam->close();


if (!$schedule_data['is_exam_day']) {
    // 4. Determine Current/Next Period if no exams
    $ccur = date('H:i:s');
    
    // Check for a currently running period
    $stmt_cp = $conn->prepare("SELECT * FROM classschedule WHERE sccode = ? AND sessionyear LIKE ? AND timestart <= ? AND timeend >= ? ORDER BY period LIMIT 1");
    $sy_like = "%$sy%";
    $stmt_cp->bind_param("ssss", $sccode, $sy_like, $ccur, $ccur);
    $stmt_cp->execute();
    $current_period_result = $stmt_cp->get_result();
    
    if ($current_period_result->num_rows > 0) {
        $schedule_data['current_period_info'] = $current_period_result->fetch_assoc();
    } else {
        // No current period, find the next one
        $stmt_np = $conn->prepare("SELECT * FROM classschedule WHERE sccode = ? AND sessionyear LIKE ? AND timestart >= ? ORDER BY timestart LIMIT 1");
        $stmt_np->bind_param("sss", $sccode, $sy_like, $ccur);
        $stmt_np->execute();
        $next_period_result = $stmt_np->get_result();
        if ($next_period_result->num_rows > 0) {
            $next_period = $next_period_result->fetch_assoc();
            $schedule_data['status_message'] = "Break time. Next class starts at " . date("g:i a", strtotime($next_period['timestart']));
            $schedule_data['status_type'] = 'warning';
        } else {
            $schedule_data['status_message'] = "All classes for today have finished.";
            $schedule_data['status_type'] = 'danger';
        }
    }
    $stmt_cp->close();

    // 5. Fetch Today's Class Routine
    $day_map = ['Sunday' => 1, 'Monday' => 2, 'Tuesday' => 3, 'Wednesday' => 4, 'Thursday' => 5, 'Friday' => 5, 'Saturday' => 5];
    $day_of_week = date('l', strtotime($td));
    $wday = $day_map[$day_of_week] ?? 0;
    
    $current_period_num = $schedule_data['current_period_info']['period'] ?? 0;

    $routine_sql = "SELECT * FROM clsroutine WHERE sccode = ? AND sessionyear LIKE ? AND wday = ?";
    $routine_params = [$sccode, $sy_like, $wday];

    // If user is a teacher, only show their classes for today
    if ($userlevel == 'Teacher' || $userlevel == 'Asstt. Teacher' || $userlevel == 'Class Teacher') {
        $routine_sql .= " AND tid = ?";
        $routine_params[] = $userid;
    } else {
        // For admins/others, only show the currently running period's classes
        if($current_period_num > 0) {
            $routine_sql .= " AND period = ?";
            $routine_params[] = $current_period_num;
        }
    }
    $routine_sql .= " ORDER BY period, classname, sectionname";
    
    $stmt_routine = $conn->prepare($routine_sql);
    $types = str_repeat('s', count($routine_params));
    $stmt_routine->bind_param($types, ...$routine_params);
    $stmt_routine->execute();
    $schedule_data['class_routine_today'] = $stmt_routine->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_routine->close();

    // 6. Determine Teacher's current classes
    if ($userlevel == 'Teacher' || $userlevel == 'Asstt. Teacher' || $userlevel == 'Class Teacher') {
        foreach($schedule_data['class_routine_today'] as $class) {
            if ($class['period'] == $current_period_num) {
                $schedule_data['my_classes_now'][] = ['cls' => $class['classname'], 'sec' => $class['sectionname']];
            }
        }
    }
}
?>
