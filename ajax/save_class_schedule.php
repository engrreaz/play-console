<?php
include_once '../inc.light.php';

// POST ডাটা রিসিভ করা
$id         = $_POST['id']; // এডিট হলে আইডি থাকবে, নতুন হলে খালি
$period     = mysqli_real_escape_string($conn, $_POST['period']);
$timestart  = mysqli_real_escape_string($conn, $_POST['timestart']);
$timeend    = mysqli_real_escape_string($conn, $_POST['timeend']);
$duration   = mysqli_real_escape_string($conn, $_POST['duration']);
$session    = mysqli_real_escape_string($conn, $_POST['sessionyear']);
$slots      = mysqli_real_escape_string($conn, $_POST['slots']);

if (empty($id)) {
    // নতুন রেকর্ড তৈরি (INSERT)
    $sql = "INSERT INTO classschedule (sccode, sessionyear, slots, period, timestart, timeend, duration) 
            VALUES ('$sccode', '$session', '$slots', '$period', '$timestart', '$timeend', '$duration')";
} else {
    // বিদ্যমান রেকর্ড আপডেট (UPDATE)
    $sql = "UPDATE classschedule SET 
            period = '$period', 
            timestart = '$timestart', 
            timeend = '$timeend', 
            duration = '$duration' 
            WHERE id = '$id' AND sccode = '$sccode'";
}

if ($conn->query($sql)) {
    echo 'success';
} else {
    echo 'Database Error: ' . $conn->error;
}
?>