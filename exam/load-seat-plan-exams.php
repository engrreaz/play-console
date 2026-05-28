<?php
include '../inc.light.php';

$sql = "SELECT MAX(id) as id, slot, examtitle
        FROM seat_plans
        WHERE sccode='$sccode' AND sessionyear='$sessionyear'
        GROUP BY slot, examtitle
        ORDER BY id DESC";

echo $sql;

$res = mysqli_query($conn, $sql);

echo '<option value="">-- Select Exam --</option>';

while ($row = mysqli_fetch_assoc($res)) {

    $value = $row['examtitle'] . '||' . $row['slot'];

    echo "<option value='{$value}'>
            {$row['examtitle']} - Slot {$row['slot']}
          </option>";
}
?>