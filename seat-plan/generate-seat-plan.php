<?php
include '../inc.light.php';

$slot = $_POST['slot'] ?? 'School';
$exam = $_POST['exam'] ?? '';
$class_sections = $_POST['class_sections'] ?? [];
$shift = $_POST['shift'] ?? '';
$rooms = $_POST['rooms'] ?? [];
$layout = $_POST['layout'] ?? 'sequential';
$mixing = $_POST['mixing'] ?? 'separate';

var_dump($class_sections);

if (empty($exam) || empty($shift) || empty($class_sections) || empty($rooms)) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

$room_ids_str = implode(',', array_map('intval', $rooms));

$students = [];
$grouped_students = [];

// 1. Fetch Students
foreach ($class_sections as $cs) {
    list($class_name, $section_name) = explode('|', $cs);

    $student_query = "SELECT stid, rollno, classname, sectionname FROM sessioninfo WHERE sccode = ? AND sessionyear = ? AND slot = ? AND classname = ? AND sectionname = ? ORDER BY rollno";

    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("issss", $sccode, $sessionyear, $slot, $class_name, $section_name);
    $stmt->execute();
    $res_st = $stmt->get_result();
    $group = [];
    while ($row = $res_st->fetch_assoc()) {
        $group[] = $row;
    }
    if (count($group) > 0) {
        $grouped_students[] = $group;
    }
    $stmt->close();
}

if ($mixing === 'mixed_interleaved') {
    $max_len = 0;
    foreach ($grouped_students as $g) {
        if (count($g) > $max_len)
            $max_len = count($g);
    }
    for ($i = 0; $i < $max_len; $i++) {
        foreach ($grouped_students as $g) {
            if (isset($g[$i])) {
                $students[] = $g[$i];
            }
        }
    }
} else {
    foreach ($grouped_students as $g) {
        $students = array_merge($students, $g);
    }
}

if (count($students) == 0) {
    echo json_encode(["success" => false, "message" => "No students found for the selected classes/sections."]);
    exit;
}

// 2. Fetch Available Benches based on Layout Pattern
$order_by = "b.room_id, b.row_no, b.col_no";
if ($layout === 'column_wise') {
    $order_by = "b.room_id, b.col_no, b.row_no";
} elseif ($layout === 'zigzag') {
    $order_by = "b.room_id, b.row_no, IF(b.row_no % 2 = 1, b.col_no, -b.col_no)";
}

$benches_query = "SELECT b.id, b.room_id, b.capacity 
                  FROM seat_room_benches b 
                  WHERE b.room_id IN ($room_ids_str) AND b.is_blocked = 0 
                  ORDER BY $order_by";
$res_bn = $conn->query($benches_query);
$benches = [];
while ($row = $res_bn->fetch_assoc()) {
    $cap = intval($row['capacity']);
    for ($i = 1; $i <= $cap; $i++) {
        $benches[] = [
            'bench_id' => $row['id'],
            'room_id' => $row['room_id'],
            'seat_no' => $i
        ];
    }
}

if (count($students) > count($benches)) {
    echo json_encode(["success" => false, "message" => "Not enough seats. Students: " . count($students) . ", Seats: " . count($benches)]);
    exit;
}

// 3. Create Plan Record
$unique_classes = array_unique(array_column($students, 'classname'));
$combined_class = substr(implode(', ', $unique_classes), 0, 100);

$unique_sections = array_unique(array_column($students, 'sectionname'));
$combined_section = substr(implode(', ', $unique_sections), 0, 100);

$insert_plan = $conn->prepare("INSERT INTO seat_plans (sccode, sessionyear, slot, examtitle, class_name, section_name, shift) VALUES (?, ?, ?, ?, ?, ?, ?)");
$insert_plan->bind_param("issssss", $sccode, $sessionyear, $slot, $exam, $combined_class, $combined_section, $shift);
$insert_plan->execute();
$plan_id = $insert_plan->insert_id;
$insert_plan->close();






$delete_old_plan = $conn->prepare("
    DELETE p, a
    FROM seat_plans p
    LEFT JOIN seat_plan_allocations a ON p.id = a.plan_id
    WHERE p.sccode = ?
    AND p.sessionyear = ?
    AND p.slot = ?
    AND p.examtitle = ?
    AND p.shift = ?
");
$delete_old_plan->bind_param("issss", $sccode, $sessionyear, $slot, $exam, $shift);
$delete_old_plan->execute();
$delete_old_plan->close();









// 4. Allocate Students
$allocated_count = 0;
$insert_alloc = $conn->prepare("INSERT INTO seat_plan_allocations (plan_id, sccode, sessionyear, slot, room_id, bench_id, seat_no, stid, rollno, classname, sectionname, shift) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($students as $index => $st) {
    $seat = $benches[$index];
    $insert_alloc->bind_param(
        "isssiiisisss",
        $plan_id,
        $sccode,
        $sessionyear,
        $slot,
        $seat['room_id'],
        $seat['bench_id'],
        $seat['seat_no'],
        $st['stid'],
        $st['rollno'],
        $st['classname'],
        $st['sectionname'],
        $shift
    );
    $insert_alloc->execute();
    $allocated_count++;
}
$insert_alloc->close();

echo json_encode(["success" => true, "allocated" => $allocated_count]);
?>