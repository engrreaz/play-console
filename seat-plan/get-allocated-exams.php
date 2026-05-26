<?php
include '../inc.light.php';

$exams = [];
$exam_query = "SELECT DISTINCT examtitle, sessionyear FROM seat_plans WHERE sccode = '$sccode' AND sessionyear LIKE '$sessionyear_param' ORDER BY examtitle";
$result = $conn->query($exam_query);

echo '<option value="">-- Select Exam --</option>';

if($result) {
    while($row = $result->fetch_assoc()) {
        $title = htmlspecialchars($row['examtitle']);
        $year = htmlspecialchars($row['sessionyear']);
        echo "<option value=\"{$title}\">{$title} ({$year})</option>";
    }
}
?>