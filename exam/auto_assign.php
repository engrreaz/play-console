<?php
include '../inc.light.php';

$session = $_GET['session'];
$planid = $_GET['planid'];
$examname = $_GET['examname'];


// exam info (important)
$examInfo = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT examtitle, exam_date, sccode, slot
FROM seat_plans 
WHERE sccode='$sccode' AND sessionyear='$session' AND id='$planid'
LIMIT 1
"));

$examname = $examInfo['examtitle'];
$slot = $examInfo['slot'];


/* -----------------------------
   TEACHERS POOL
------------------------------*/
$teachers = [];
$q = mysqli_query($conn, "SELECT tid FROM teacher WHERE sccode='$sccode'");
while ($t = mysqli_fetch_assoc($q)) {
    $teachers[] = $t['tid'];
}

if (count($teachers) == 0) {
    exit("No teachers found");
}

/* -----------------------------
   EXAM ROUTINE (ALL DAYS)
------------------------------*/
$routine = mysqli_query($conn, "
    SELECT date, time, clsname, secname 
    FROM examroutine 
    WHERE sccode='$sccode' 
      AND sessionyear='$session' 
      AND examname='$examname'
    ORDER BY date ASC, time ASC
");

echo "
    SELECT date, time, clsname, secname 
    FROM examroutine 
    WHERE sccode='$sccode' 
      AND sessionyear='$session' 
      AND examname='$examname'
    ORDER BY date ASC, time ASC
";
/* -----------------------------
   LOAD ROOMS
------------------------------*/
$rooms = [];
$rq = mysqli_query($conn, "
    SELECT DISTINCT room_id 
    FROM seat_plan_allocations 
    WHERE plan_id='$planid'
");
while ($r = mysqli_fetch_assoc($rq)) {
    $rooms[] = $r['room_id'];
}

/* -----------------------------
   TEACHER USAGE TRACKER
------------------------------*/
$teacherLoad = [];
$teacherDaily = [];

$i = 0;

/* -----------------------------
   PROCESS ROUTINE
------------------------------*/
while ($row = mysqli_fetch_assoc($routine)) {

    $date = $row['date'];
    $time = $row['time'];

    /* SHIFT DETECT */
    $shift = (strtotime($time) < strtotime("12:00:00")) ? "Morning" : "Day";

    foreach ($rooms as $room) {

        /* -------------------------
           CHECK EXISTING
        --------------------------*/
        $check = mysqli_query($conn, "
            SELECT id FROM invigilators
            WHERE sessionyear='$session'
            AND examname='$examname'
            AND exam_date='$date'
            AND room_id='$room'
            AND shift='$shift'
            LIMIT 1
        ");

        if (mysqli_num_rows($check) > 0) {
            continue;
        }

        /* -------------------------
           FIND NEXT AVAILABLE TEACHER
        --------------------------*/
        $tid = null;

        for ($x = 0; $x < count($teachers); $x++) {

            $candidate = $teachers[$i % count($teachers)];
            $i++;

            /* avoid same teacher same date */
            if (isset($teacherDaily[$date][$candidate])) {
                continue;
            }

            $tid = $candidate;
            break;
        }

        if (!$tid) {
            $tid = $teachers[$i % count($teachers)];
        }

        /* -------------------------
           TRACK USAGE
        --------------------------*/
        $teacherDaily[$date][$tid] = true;
        $teacherLoad[$tid] = ($teacherLoad[$tid] ?? 0) + 1;

        /* -------------------------
           INSERT
        --------------------------*/

        $qr = "
            INSERT INTO invigilators 
            (
                sccode,
                sessionyear,
                examname,
                exam_date,
                slot,
                room_id,
                shift,
                tid
            )
            VALUES
            (
                '$sccode',
                '$session',
                '$examname',
                '$date',
                '$slot',
                '$room',
                '$shift',
                '$tid'
            )
        ";
        echo $qr . "<br><br>";
        mysqli_query($conn, $qr);
    }
}

echo "done";