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
