<?php
include_once '../inc.light.php';
$res = $conn->query("SELECT * FROM slots WHERE sccode='$sccode' ORDER BY id ASC");

if ($res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        $merit_txt = ($r['merit'] == 1) ? 'GPA' : 'Total Marks';
        echo '
        <div class="slot-card shadow-sm">
            <div>
                <div class="slot-title">'.$r['slotname'].'</div>
                <div class="slot-meta">
                    <span class="merit-badge">'.$merit_txt.'</span>
                    <span><i class="bi bi-file-earmark-text"></i> '.$r['cus_report'].'</span>
                    <span><i class="bi bi-clock"></i> '.($r['reqin'] ?: '--:--').'</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light rounded-circle shadow-sm" onclick="editSlot('.$r['id'].')">
                    <i class="bi bi-pencil-square text-primary"></i>
                </button>
                <button class="btn btn-light rounded-circle shadow-sm" onclick="deleteSlot('.$r['id'].')">
                    <i class="bi bi-trash text-danger"></i>
                </button>
            </div>
        </div>';
    }
} else {
    echo '<div class="text-center py-5 text-muted">No slots configured yet.</div>';
}
?>