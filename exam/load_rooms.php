<?php
include '../inc.light.php';

$session = $_GET['session'];
$planid = $_GET['planid'];

// exam info (important)
$examInfo = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT examtitle, exam_date, sccode, slot
FROM seat_plans 
WHERE sccode='$sccode' AND sessionyear='$session' AND id='$planid'
LIMIT 1
"));

$examname = $examInfo['examtitle'] ?? "";
$exam_date = $examInfo['exam_date'] ?? date('Y-m-d');
$slot = $examInfo['slot'] ?? "School";

// rooms
$rooms = mysqli_query($conn,"
SELECT DISTINCT room_id 
FROM seat_plan_allocations 
WHERE sessionyear='$session'
AND plan_id='$planid'
");

while($room = mysqli_fetch_assoc($rooms)){

    $room_id = $room['room_id'];

    echo "<div class='card'>";
    echo "<h3>Room: {$room_id}</h3>";

    echo "<p><b>Date:</b> $exam_date | <b>Exam:</b> $examname</p>";

    echo "<table class='table'>
    <tr>
        <th>Shift</th>
        <th>Teacher</th>
    </tr>";

    $shifts = ['Morning','Day'];

    foreach($shifts as $shift){

        // existing assignment fetch
        $exist = mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT tid 
            FROM invigilators 
            WHERE sessionyear='$session'
            AND slot='$slot'
            AND room_id='$room_id'
            AND shift='$shift'
            LIMIT 1
        "));

        $exist_tid = $exist['tid'] ?? 0;

        echo "<tr>";
        echo "<td>$shift</td>";

        echo "<td>";
        echo "<select name='teacher[{$room_id}][$shift]'>";

        echo "<option value=''>Select Teacher</option>";

        $teachers = mysqli_query($conn,"
            SELECT tid, tname 
            FROM teacher 
            WHERE sccode='$sccode'
        ");

        while($t = mysqli_fetch_assoc($teachers)){
            $selected = ($t['tid'] == $exist_tid) ? "selected" : "";
            echo "<option value='{$t['tid']}' $selected>{$t['tname']}</option>";
        }

        echo "</select>";
        echo "</td>";

        echo "</tr>";
    }

    echo "</table>";

    echo "<button class='primary' onclick='saveAssign($room_id)'>Save</button>";

    echo "</div>";
}
?>