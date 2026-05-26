<?php
include '../inc.light.php';

$examtitle = $_POST['examtitle'] ?? '';

if(empty($examtitle)) {
    echo "No exam selected.";
    exit;
}

$examtitle_esc = $conn->real_escape_string($examtitle);

// Get distinct rooms allocated for this exam
$query = "
    SELECT
        r.id,
        r.room_name,
        f.floor_name,
        f.floor_no,
        b.building_name

    FROM seat_plan_allocations a

    JOIN seat_plans p
        ON a.plan_id = p.id

    JOIN seat_rooms r
        ON a.room_id = r.id

    JOIN seat_floors f
        ON r.floor_id = f.id

    JOIN seat_buildings b
        ON f.building_id = b.id

    WHERE
        p.examtitle = '$examtitle_esc'
        AND p.sccode = '$sccode'

    GROUP BY r.id

    ORDER BY
        b.building_name,
        f.floor_no,
        r.room_name
";

$result = $conn->query($query);

if($result && $result->num_rows > 0) {
    echo '<div class="list-group">';
    while($row = $result->fetch_assoc()) {
        $room_id = $row['id'];
        $name = htmlspecialchars($row['building_name'] . ' - ' . $row['floor_name'] . ' - ' . $row['room_name']);
        
        // Count students in this room for this exam
        $count_q = $conn->query("SELECT COUNT(*) as total FROM seat_plan_allocations a JOIN seat_plans p ON a.plan_id = p.id WHERE p.examtitle = '$examtitle_esc' AND a.room_id = '$room_id'");
        $count_row = $count_q->fetch_assoc();
        $total_students = $count_row['total'];

        echo "<div class=\"list-group-item list-group-item-action d-flex justify-content-between align-items-center\">
                <a href=\"javascript:void(0)\" onclick=\"loadAllocatedRoomMap({$room_id}, '{$examtitle}')\" style=\"flex-grow: 1; text-decoration: none; color: inherit;\">
                    {$name}
                </a>
                <div >
                    <span class=\"badge bg-primary rounded-pill me-2\">{$total_students} Students</span>
                    <a href=\"seat-plan/download-room-allocation-pdf.php?room_id={$room_id}&examtitle=" . urlencode($examtitle) . "\" target=\"_blank\" class=\"btn btn-sm btn-danger\" title=\"Download PDF\" 
                    style=\"bordeer-radius:50%;\"
                    >
                        <i class=\"bi bi-file-earmark-pdf-fill\"></i>
                    </a>
       
                    <a href=\"seat-plan/download-attendance-sheet-pdf.php?room_id={$room_id}&examtitle=" . urlencode($examtitle) . "\" target=\"_blank\" class=\"btn btn-sm btn-info\" title=\"Download PDF\" 
                    style=\"bordeer-radius:50%;\"
                    >
                        <i class=\"bi bi-file-earmark-pdf-fill\"></i>
                    </a>
                </div>


              </div>";
    }
    echo '</div>';
} else {
    echo "<div class='alert alert-warning'>No rooms allocated for this exam yet.</div>";
}
?>