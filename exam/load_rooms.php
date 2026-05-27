<?php
include '../inc.light.php';

$session = $_GET['session'] ?? '';
$planid = $_GET['planid'] ?? 0;
$type = $_GET['type'] ?? 'room';
$params = $_GET['params'] ?? '';

// exam info
$examInfo = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT examtitle, exam_date, sccode, slot
    FROM seat_plans 
    WHERE id='$planid'
      AND sessionyear='$session'
      AND sccode='$sccode'
    LIMIT 1
"));

$examname = $examInfo['examtitle'] ?? '';
$slot = $examInfo['slot'] ?? 'School';

/* -----------------------------
   MAIN QUERY
------------------------------*/
$mainSQL = "
    SELECT * 
    FROM invigilators 
    WHERE sessionyear='$session'
      AND slot='$slot'
      AND sccode='$sccode'
      AND examname='$examname'
    ORDER BY exam_date ASC, room_id ASC, shift ASC
";

$result = mysqli_query($conn, $mainSQL);

$data = [];

/* -----------------------------
   GROUPING LOGIC
------------------------------*/
while ($row = mysqli_fetch_assoc($result)) {

    $date = $row['exam_date'];
    $room = $row['room_id'];
    $tid = $row['tid'];

    $data[$date][$room][] = $row;
}

echo "<div class='grid'>";

/* =========================================================
   TYPE = ROOM (ROOM WISE DATE CARDS)
=========================================================*/
if ($type == 'room') {

    $roomGroup = [];

    foreach ($data as $date => $rooms) {
        foreach ($rooms as $room => $rows) {
            if ($params && $room != $params)
                continue;

            $roomGroup[$room][$date] = $rows;
        }
    }

    foreach ($roomGroup as $room_id => $dates) {

        foreach ($dates as $date => $rows) {

            echo "<div class='card ton-card'>";
            echo "<div class='card-header d-flex'>";
            echo "<h3 class='flex-grow-1'>📅  $date</h3>";
            echo "<span class='sub text-right'>🏫 Room $room_id</span>";
            echo "</div>";

            echo "<div class='card-body'>";

            echo "<table class='table'>";
            echo "<tr><th>Shift</th><th>Teacher</th></tr>";

            foreach (['Morning', 'Day'] as $shift) {

                $row = null;
                foreach ($rows as $r) {
                    if ($r['shift'] == $shift) {
                        $row = $r;
                        break;
                    }
                }

                $tid = $row['tid'] ?? '';

                $tname = '';
                if ($tid) {
                    $tq = mysqli_fetch_assoc(mysqli_query($conn, "
                        SELECT tname FROM teacher WHERE tid='$tid'
                    "));
                    $tname = $tq['tname'] ?? '';
                }

                echo "<tr>";

                echo "<td><b>$shift</b></td>";

                echo "<td>";

                // VIEW MODE
                echo "<div class='view-box d-flex' id='view-$room_id-$date-$shift'>";
                echo "<span class='flex-grow-1'>$tname</span>";
                echo "<button class='btn btn-link btn-outline-primary' onclick=\"editMode('$room_id','$date','$shift')\"><i class='bi bi-pencil'></i></button>";
                echo "</div>";

                // EDIT MODE (hidden)
                echo "<div class='edit-box' id='edit-$room_id-$date-$shift' style='display:none;'>";

                echo "<select onchange=\"saveAssign('$room_id','$date','$shift',this.value)\">";

                $teachers = mysqli_query($conn, "SELECT tid,tname FROM teacher WHERE sccode='$sccode'");
                while ($t = mysqli_fetch_assoc($teachers)) {
                    $sel = ($t['tid'] == $tid) ? "selected" : "";
                    echo "<option value='{$t['tid']}' $sel>{$t['tname']}</option>";
                }

                echo "</select>";

                echo "</div>";

                echo "</td>";

                echo "</tr>";
            }

            echo "</table>";
            echo "</div></div>";
        }
    }
}

/* =========================================================
   TYPE = DAY (DATE WISE ROOM VIEW)
=========================================================*/ elseif ($type == 'day') {

    foreach ($data as $date => $rooms) {

        if ($params && $date != $params)
            continue;

        echo "<div class='card ton-card'>";
        echo "<div class='card-header'><h3>📅 $date</h3></div>";
        echo "<div class='card-body'>";

        foreach ($rooms as $room_id => $rows) {

            echo "<h4>🏫 Room $room_id</h4>";

            foreach (['Morning', 'Day'] as $shift) {

                $row = null;
                foreach ($rows as $r) {
                    if ($r['shift'] == $shift) {
                        $row = $r;
                        break;
                    }
                }

                $tid = $row['tid'] ?? '';

                $tname = '';
                if ($tid) {
                    $tq = mysqli_fetch_assoc(mysqli_query($conn, "
                        SELECT tname FROM teacher WHERE tid='$tid'
                    "));
                    $tname = $tq['tname'] ?? '';
                }

                echo "<div class='inline-row'>";
                echo "<b>$shift</b> — $tname";
                echo "<button onclick=\"editMode('$room_id','$date','$shift')\">✏️</button>";
                echo "</div>";
            }
        }

        echo "</div></div>";
    }
}

/* =========================================================
   TYPE = TEACHER (TEACHER WISE DUTY VIEW)
=========================================================*/ elseif ($type == 'teacher') {

    $teacherMap = [];

    foreach ($data as $date => $rooms) {
        foreach ($rooms as $room_id => $rows) {
            foreach ($rows as $r) {
                if ($params && $r['tid'] != $params)
                    continue;
                $teacherMap[$r['tid']][$date][] = $r;
            }
        }
    }

    foreach ($teacherMap as $tid => $dates) {

        $tq = mysqli_fetch_assoc(mysqli_query($conn, "
            SELECT tname FROM teacher WHERE tid='$tid'
        "));
        $tname = $tq['tname'] ?? '';

        echo "<div class='card ton-card'>";
        echo "<div class='card-header'><h3>👨‍🏫 $tname</h3></div>";
        echo "<div class='card-body'>";

        foreach ($dates as $date => $rows) {

            echo "<h4>📅 $date</h4>";

            foreach ($rows as $r) {
                echo "<div class='inline-row'>";
                echo "<span>{$r['shift']} - Room {$r['room_id']}</span>";
                echo "<button onclick=\"editMode('{$r['room_id']}','$date','{$r['shift']}')\">✏️</button>";
                echo "</div>";
            }
        }

        echo "</div></div>";
    }
}

echo "</div>";
?>