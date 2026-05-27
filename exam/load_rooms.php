<?php
include '../inc.light.php';

$session = $_GET['session'] ?? '';
$planid  = $_GET['planid'] ?? 0;
$type    = $_GET['type'] ?? '';
$params  = $_GET['params'] ?? '';

// exam info
$examInfo = mysqli_fetch_assoc(mysqli_query($conn, "
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
   FILTER SYSTEM
------------------------------*/
$filterSQL = "";

if ($type == "room" && $params) {
    $filterSQL .= " AND room_id='$params' ";
} elseif ($type == "day" && $params) {
    $filterSQL .= " AND exam_date='$params' ";
} elseif ($type == "teacher" && $params) {
    $filterSQL .= " AND tid='$params' ";
}

/* -----------------------------
   MAIN QUERY (IMPORTANT)
------------------------------*/
$mainSQL = "
    SELECT * 
    FROM invigilators 
    WHERE sessionyear='$session'
      AND slot='$slot'
      AND sccode='$sccode'
      AND examname='$examname'
      $filterSQL
    ORDER BY exam_date ASC, room_id ASC, shift ASC
";

$result = mysqli_query($conn, $mainSQL);

/* -----------------------------
   GROUP DATA (ROOM WISE)
------------------------------*/
$data = [];

while($row = mysqli_fetch_assoc($result)){
    $room = $row['room_id'];
    $shift = $row['shift'];

    $data[$room][$shift][] = $row;
}

/* -----------------------------
   UI RENDER (MATERIAL 3 STYLE)
------------------------------*/
echo "<div class='grid'>";

foreach($data as $room_id => $shifts){

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

    foreach(['Morning','Day'] as $shift){

        $tid = $shifts[$shift][0]['tid'] ?? null;

        // teacher name fetch
        $tname = '';
        if($tid){
            $tq = mysqli_fetch_assoc(mysqli_query($conn,"
                SELECT tname FROM teacher 
                WHERE tid='$tid' 
                LIMIT 1
            "));
            $tname = $tq['tname'] ?? 'Unknown';
        }

        echo "<tr>";
        echo "<td><b>$shift</b></td>";

        echo "<td>";

        echo "<select class='md-select'>";
        echo "<option value='$tid'>$tname</option>";

        // optional full list
        $teachers = mysqli_query($conn,"
            SELECT tid, tname 
            FROM teacher 
            WHERE sccode='$sccode'
            ORDER BY tname ASC
        ");

        while($t = mysqli_fetch_assoc($teachers)){
            echo "<option value='{$t['tid']}'>{$t['tname']}</option>";
        }

        echo "</select>";

        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "<div class='actions'>";
    echo "<button class='btn primary' onclick='saveAssign($room_id)'>Update</button>";
    echo "</div>";

    echo "</div>"; // body
    echo "</div>"; // card
}

echo "</div>";
?>