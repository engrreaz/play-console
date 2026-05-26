<?php
include '../inc.light.php';

// Fetch Exams
$exams = [];
$exam_query = "SELECT DISTINCT examtitle, sessionyear FROM examlist WHERE sccode = '$sccode' and sessionyear LIKE '$sessionyear_param' ORDER BY examtitle";
$result = $conn->query($exam_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $exams[] = $row;
    }
}

// Fetch Class-Sections with student count
$class_sections = [];
$cs_query = "SELECT classname, sectionname, COUNT(stid) as total_students 
             FROM sessioninfo 
             WHERE sccode = '$sccode' AND sessionyear LIKE '$sessionyear_param' 
             GROUP BY classname, sectionname 
             ORDER BY classname, sectionname";
$result_cs = $conn->query($cs_query);
if ($result_cs) {
    while ($row = $result_cs->fetch_assoc()) {
        $class_sections[] = $row;
    }
}

// Fetch Rooms with actual capacity
$rooms = [];
$room_query = "SELECT r.id, r.room_name, f.floor_name, b.building_name, 
               IFNULL((SELECT SUM(capacity) FROM seat_room_benches WHERE room_id = r.id AND is_blocked = 0), 0) as room_capacity
               FROM seat_rooms r
               JOIN seat_floors f ON r.floor_id = f.id
               JOIN seat_buildings b ON f.building_id = b.id
               WHERE b.sccode = '$sccode'
               ORDER BY b.building_name, f.floor_no, r.room_name";
$result3 = $conn->query($room_query);
if ($result3) {
    while ($row = $result3->fetch_assoc()) {
        $rooms[] = $row;
    }
}

var_dump($rooms);
?>

<div class="row">
    <div class="col-6">
        <div class="m3-input-group">
            <label>Select Exam</label>
            <select id="gen_exam" class="m3-select">
                <option value="">-- Select Exam --</option>
                <?php foreach ($exams as $ex): ?>
                    <option value="<?= htmlspecialchars($ex['examtitle']) ?>"><?= htmlspecialchars($ex['examtitle']) ?>
                        (<?= htmlspecialchars($ex['sessionyear']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="m3-input-group">
            <label>Select Shift</label>
            <select id="gen_shift" class="m3-select">
                <option value="Morning">Morning</option>
                <option value="Day">Day</option>
            </select>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-6">
        <div class="m3-input-group">
            <label>Layout Pattern</label>
            <select id="gen_layout" class="m3-select">
                <option value="sequential">Sequential (Row by Row)</option>
                <option value="column_wise">Column Wise</option>
                <option value="zigzag">Zig-Zag (Snake)</option>
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="m3-input-group">
            <label>Mixing Strategy</label>
            <select id="gen_mixing" class="m3-select">
                <option value="separate">Separate (Complete one class, then next)</option>
                <option value="mixed_interleaved">Mixed (Interleaved A-B-A-B)</option>
            </select>
        </div>
    </div>
</div>







<div class="m3-input-group">
    <label>Select Classes & Sections (Multiple)</label>
    <div
        style="max-height: 200px; overflow-y: auto; border: 1px solid var(--m3-outline); border-radius: 12px; padding: 10px; background: #fff;">
        <?php foreach ($class_sections as $cs): ?>
            <label style="display: block; margin-bottom: 5px; cursor: pointer;">
                <input type="checkbox" name="gen_class_sections[]"
                    value="<?= htmlspecialchars($cs['classname'] . '|' . $cs['sectionname']) ?>"
                    data-students="<?= $cs['total_students'] ?>">
                <?= htmlspecialchars($cs['classname']) ?> - <?= htmlspecialchars($cs['sectionname']) ?>
                <small style="color: #777;">(<?= $cs['total_students'] ?> Students)</small>
            </label>
        <?php endforeach; ?>
    </div>
</div>

<div class="m3-input-group">
    <label>Select Rooms (Multiple)</label>
    <div
        style="max-height: 200px; overflow-y: auto; border: 1px solid var(--m3-outline); border-radius: 12px; padding: 10px; background: #fff;">
        <?php foreach ($rooms as $rm): ?>
            <label style="display: block; margin-bottom: 5px; cursor: pointer;">
                <input type="checkbox" name="gen_rooms[]" value="<?= $rm['id'] ?>"
                    data-capacity="<?= $rm['room_capacity'] ?>">
                <?= htmlspecialchars($rm['building_name'] . ' - ' . $rm['floor_name'] . ' - ' . $rm['room_name']) ?>
                <small style="color: #777;">(Capacity: <?= $rm['room_capacity'] ?>)</small>
            </label>
        <?php endforeach; ?>
    </div>
</div>

<button class="m3-btn m3-btn-success w-100" style="margin-top: 15px;" onclick="generateSeatPlan()">
    <i class="bi bi-magic" style="vertical-align: middle; font-size: 18px;"></i> Generate Plan
</button>