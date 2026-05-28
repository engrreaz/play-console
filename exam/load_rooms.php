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
            echo "<h3 class='flex-grow-1 fw-bold'><i class='bi bi-calendar3'></i> $date</h3>";
            echo "<span class='sub text-right fw-tiny'><i class='bi bi-building'></i> Room # $room_id</span>";
            echo "</div>";

            echo "<div class='card-body'>";

            echo "<table class='table table-sm'>";
            // echo "<tr><th>Shift</th><th>Teacher</th></tr>";

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

      

                echo "<td>";

                // VIEW MODE
                echo "<div class='fw-bold fs-tiny text-muted'>$shift</div>";
                echo "<div class='view-box d-flex' id='view-$room_id-$date-$shift'>";
                echo "<span class='flex-grow-1' id='tid-$room_id-$date-$shift'>$tname</span>";
                echo "<button class='btn btn-link btn-outline-primary' onclick=\"editMode('$room_id','$date','$shift')\"><i class='bi bi-pencil'></i></button>";
                echo "</div>";

                // EDIT MODE (hidden)
                echo "<div class='edit-box' id='edit-$room_id-$date-$shift' style='display:none;'>";

                echo "<select id='tidx-$room_id-$date-$shift' class='form-select form-select-sm' onchange=\"saveAssign('$room_id','$date','$shift',this.value)\">";

                $teachers = mysqli_query($conn, "SELECT tid,tname FROM teacher WHERE sccode='$sccode'");
                while ($t = mysqli_fetch_assoc($teachers)) {
                    $sel = ($t['tid'] == $tid) ? "selected" : "";
                    echo "<option value='{$t['tid']}' data-tname='{$t['tname']}' $sel>{$t['tname']}</option>";
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
        echo "<div class='card-header'><h5><i class='bi bi-calendar3'></i>$date</h5></div>";
        echo "<div class='card-body'>";

        foreach ($rooms as $room_id => $rows) {

            echo "<h5 class='text-success'><i class='bi bi-building'></i> Room $room_id</h5>";

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

                echo "<div class='inline-row d-flex'>";
                echo "<span class='flex-grow-1'><b>$shift</b> — $tname</span>";
                echo "<button class='float-end btn btn-link' onclick=\"editMode('$room_id','$date','$shift')\"><i class='bi bi-pencil'></i></button>";
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
        $img_path =teacher_profile_image_path($tid);

        echo "<div class='card ton-card'>";
        echo "<div class='card-header d-flex'>";
echo "<img src='$img_path' style='width:25px; height:25px; border-radius: 50%;'>";
        
        echo "<div class='flex-grow-1 fs-5 ms-2 fw-bold'> $tname</div></div>";
        echo "<div class='card-body'>";

        foreach ($dates as $date => $rows) {

            echo "<h6><i class='bi bi-calendar3'></i> $date</h6>";

            foreach ($rows as $r) {
                echo "<div class='inline-row d-flex'>";
                echo "<span class='flex-grow-1'>{$r['shift']} - Room {$r['room_id']}</span>";
                echo "<button class='float-end btn btn-link btn-secondary ' onclick=\"editMode('{$r['room_id']}','$date','{$r['shift']}')\"><i class='bi bi-pencil'></i></button>";
                echo "</div>";
            }
        }

        echo "</div></div>";
    }
}

echo "</div>";
?>