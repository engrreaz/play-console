<?php
include_once '../inc.light.php';

$slot = $_POST['slot_name'];
$session = $_POST['session'];
$class = $_POST['classname'];
$days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];

// ১. ওই স্লটের সব পিরিয়ড নিয়ে আসা
$sched_res = $conn->query("SELECT * FROM classschedule WHERE sccode='$sccode' AND slots='$slot' AND sessionyear='$session'");

$count = 0;
while($s = $sched_res->fetch_assoc()) {
    $period = $s['period'];
    $start = $s['timestart'];
    $end = $s['timeend'];

    foreach($days as $day) {
        // ২. চেক করা এই রুটিন আগে থেকে আছে কি না
        $check = $conn->query("SELECT id FROM classroutine WHERE sccode='$sccode' AND classname='$class' AND day='$day' AND period='$period' AND sessionyear='$session'");
        
        if($check->num_rows == 0) {
            // ৩. নেই তাই নতুন ইনসার্ট (Subject placeholders সহ)
            $conn->query("INSERT INTO classroutine (sccode, sessionyear, classname, day, period, periodtime, periodtimeend, subject) 
                          VALUES ('$sccode', '$session', '$class', '$day', '$period', '$start', '$end', 'TBD')");
            $count++;
        } else {
            // ৪. থাকলে শুধু সময় আপডেট করে দেওয়া
            $conn->query("UPDATE classroutine SET periodtime='$start', periodtimeend='$end' 
                          WHERE sccode='$sccode' AND classname='$class' AND day='$day' AND period='$period'");
        }
    }
}

echo "Successfully synchronized routine slots.";
?>