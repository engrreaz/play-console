<?php
include '../inc.light.php';

$session  = $_GET['session'] ?? '';
$examname = $_GET['examname'] ?? '';
$slot = '';

if (!$session || !$examname) {
    exit("Missing session or examname");
}

/* -----------------------------
   NORMALIZE SHIFT FUNCTION
------------------------------*/
function normalizeShift($shift) {
    $shift = strtolower(trim($shift));

    if ($shift === 'morning') return 'Morning';
    if ($shift === 'day') return 'Day';

    return $shift;
}

/* -----------------------------
   TEACHER POOL
------------------------------*/
$teachers = [];

if (isset($_GET['tids']) && !empty($_GET['tids'])) {
    $tids = explode(',', $_GET['tids']);
    foreach ($tids as $tid) {
        $teachers[] = trim($tid);
    }
} else {
    $q = mysqli_query($conn, "
        SELECT tid 
        FROM teacher 
        WHERE sccode='$sccode'
    ");

    while ($t = mysqli_fetch_assoc($q)) {
        $teachers[] = $t['tid'];
    }
}


if (empty($teachers)) {
    exit("No teachers found");
}

/* -----------------------------
   ROUTINE
------------------------------*/
$routine = mysqli_query($conn, "
    SELECT date, time 
    FROM examroutine
    WHERE sccode='$sccode'
      AND sessionyear='$session'
      AND examname='$examname'
    ORDER BY date ASC, time ASC
");

/* -----------------------------
   SEAT PLAN → SHIFT ROOM MAP
------------------------------*/
$roomsByShift = [];

$q1 = mysqli_query($conn, "
    SELECT sp.shift, spa.room_id, sp.slot
    FROM seat_plans sp
    JOIN seat_plan_allocations spa ON sp.id = spa.plan_id
    WHERE sp.sccode='$sccode'
      AND sp.sessionyear='$session'
      AND sp.examtitle='$examname'
");

while ($r = mysqli_fetch_assoc($q1)) {

    $shift = normalizeShift($r['shift']);
    $room  = $r['room_id'];
    $slot = $r['slot'];

    $roomsByShift[$shift][$room] = true;
}

/* convert to simple array */
foreach ($roomsByShift as $shift => $rooms) {
    $roomsByShift[$shift] = array_keys($rooms);
}

/* -----------------------------
   TRACKERS
------------------------------*/
$teacherDaily = [];
$teacherIndex = 0;

/* -----------------------------
   PROCESS ROUTINE
------------------------------*/
while ($row = mysqli_fetch_assoc($routine)) {

    $date = $row['date'];
    $time = $row['time'];

    /* -----------------------------
       SHIFT DETECT (TIME BASED)
    ------------------------------*/
    if (strtotime($time) >= strtotime("10:00:00") && strtotime($time) <= strtotime("11:59:59")) {
        $shift = "Morning";
    } elseif (strtotime($time) >= strtotime("13:00:00") && strtotime($time) <= strtotime("15:00:00")) {
        $shift = "Day";
    } else {
        continue; // outside exam window
    }

    $rooms = $roomsByShift[$shift] ?? [];

    if (empty($rooms)) {
        continue;
    }

    foreach ($rooms as $room) {

        /* -------------------------
           SKIP IF ALREADY ASSIGNED
        --------------------------*/
        $check = mysqli_query($conn, "
            SELECT id 
            FROM invigilators
            WHERE sccode='$sccode'
              AND sessionyear='$session'
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
           SELECT TEACHER (ROUND ROBIN)
        --------------------------*/
        $tid = null;

        for ($i = 0; $i < count($teachers); $i++) {

            $candidate = $teachers[$teacherIndex % count($teachers)];
            $teacherIndex++;

            if (isset($teacherDaily[$date][$shift][$candidate])) {
                continue;
            }

            $tid = $candidate;
            break;
        }

        if (!$tid) {
            $tid = $teachers[$teacherIndex % count($teachers)];
        }

        /* -------------------------
           TRACK TEACHER
        --------------------------*/
        $teacherDaily[$date][$shift][$tid] = true;

        /* -------------------------
           INSERT
        --------------------------*/
        mysqli_query($conn, "
            INSERT INTO invigilators (
                sccode,
                sessionyear,
                examname,
                exam_date,
                slot,
                room_id,
                shift,
                tid
            ) VALUES (
                '$sccode',
                '$session',
                '$examname',
                '$date',
                '$slot',
                '$room',
                '$shift',
                '$tid'
            )
        ");
    }
}

echo "done";
?>