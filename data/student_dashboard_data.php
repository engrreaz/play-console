<?php
// File: data/student_dashboard_data.php

// Ensure this script is not accessed directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die('Access denied.');
}

// The $conn variable is expected to be available from the calling script (e.g., index.php)
if (!isset($conn)) {
    // Or connect here if it's not available, but for now we assume it is.
    // include_once __DIR__ . '/../db.php'; 
    die('Database connection not available.');
}

// The $userid and $td (today's date) variables are available from index.php
$student_dashboard_data = [
    'daily_tasks' => []
];

// Fetch daily tracking tasks for the logged-in student for today
// We join with a presumed 'subjects' table to get the subject name.
// This query might need adjustment based on the actual DB schema.
$stmt = $conn->prepare(
    "SELECT st.id, st.responsetime, s.subject_name_en 
     FROM sttracking st
     JOIN subjects s ON st.subid = s.id
     WHERE st.stid = ? AND st.date = ?"
);

if ($stmt) {
    $stmt->bind_param("ss", $userid, $td);
    $stmt->execute();
    $result = $stmt->get_result();
    $student_dashboard_data['daily_tasks'] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
// If the query fails or returns no rows, 'daily_tasks' will remain an empty array.