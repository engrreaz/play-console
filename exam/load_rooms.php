<?php
include '../inc.light.php';

$session = $_GET['session'] ?? '';
$planid  = $_GET['planid'] ?? 0;
$type    = $_GET['type'] ?? '';
$params  = $_GET['params'] ?? '';

// exam info
$examInfo = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT examtitle, exam_date, sccode, slot
    FROM seat_plans 
    WHERE id='$planid' 
      AND sessionyear='$session'
      AND sccode='$sccode'
    LIMIT 1
"));

$examname  = $examInfo['examtitle'] ?? '';
$exam_date = $examInfo['exam_date'] ?? date('Y-m-d');
$slot      = $examInfo['slot'] ?? 'School';

/* -----------------------------
   BASE QUERY FILTER SYSTEM
------------------------------*/
$filterSQL = "";

/* type based filter hook (future ready) */
if($type == "room" && $params){
    $filterSQL .= " AND room_id='$params' ";
}
elseif($type == "day" && $params){
    $filterSQL .= " AND DATE(exam_date)='$params' ";
}
elseif($type == "teacher" && $params){
    $filterSQL .= " AND tid='$params' ";
}

/* -----------------------------
   ROOMS
------------------------------*/
$rooms = mysqli_query($conn,"
    SELECT DISTINCT room_id 
    FROM seat_plan_allocations 
    WHERE sessionyear='$session'
      AND plan_id='$planid'
");

echo "<div class='grid'>";

while($room = mysqli_fetch_assoc($rooms)){

    $room_id = $room['room_id'];

    echo "<div class='card ton-card'>";

    echo "<div class='card-header'>";
    echo "<h3>🏫 Room $room_id</h3>";
    echo "<span class='sub'>Exam: $examname</span>";
    echo "<span class='sub'>Date: $exam_date</span>";
    echo "</div>";

    echo "<div class='card-body'>";

    echo "<table class='table'>";
    echo "<thead>
            <tr>
                <th>Shift</th>
                <th>Invigilator</th>
            </tr>
          </thead>";

    $shifts = ['Morning','Day'];

    foreach($shifts as $shift){

        // existing assignment
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
        echo "<td><b>$shift</b></td>";

        echo "<td>";

        echo "<select class='md-select' name='teacher[$room_id][$shift]'>";
        echo "<option value=''>Select Teacher</option>";

        $teachers = mysqli_query($conn,"
            SELECT tid, tname 
            FROM teacher 
            WHERE sccode='$sccode'
            ORDER BY tname ASC
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

    echo "<div class='actions'>";
    echo "<button class='btn primary' onclick='saveAssign($room_id)'>Save</button>";
    echo "</div>";

    echo "</div>"; // body
    echo "</div>"; // card
}

echo "</div>";
?>