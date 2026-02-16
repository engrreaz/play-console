<?php
include '../inc.light.php';

$examcode = $_POST['examcode'];
$stid = $_POST['stid'];

// ১. মার্কস ফেচ করা
$stmt = $conn->prepare("SELECT m.*, s.subject as subname FROM stmark m JOIN subjects s ON m.subject = s.subcode WHERE m.stid = ? AND m.examcode = ? ORDER BY s.subcode ASC");
$stmt->bind_param("ss", $stid, $examcode);
$stmt->execute();
$marks = $stmt->get_result();

if($marks->num_rows == 0) {
    echo '<div class="text-center py-4 opacity-50"><i class="bi bi-exclamation-circle display-4"></i><p>Results not available yet.</p></div>';
    exit;
}

$total_marks = 0;
$total_gp = 0;
$sub_count = $marks->num_rows;
?>

<div class="marks-summary">
    <?php while($m = $marks->fetch_assoc()): 
        $total_marks += $m['markobt'];
        $total_gp += $m['gp'];
    ?>
    <div class="d-flex align-items-center justify-content-between p-3 mb-2 bg-white rounded-4 border">
        <div class="flex-grow-1">
            <div class="fw-bold text-dark small"><?= $m['subname'] ?></div>
            <div class="text-muted" style="font-size: 0.7rem;">Obtained: <?= $m['markobt'] ?></div>
        </div>
        <div class="text-end">
            <span class="badge rounded-pill <?= ($m['gp'] > 0) ? 'bg-primary' : 'bg-danger' ?>"><?= $m['gl'] ?></span>
            <div class="fw-bold text-primary mt-1" style="font-size: 0.8rem;"><?= $m['gp'] ?></div>
        </div>
    </div>
    <?php endwhile; ?>
    
    <div class="mt-4 p-3 bg-primary-subtle rounded-4 text-center">
        <div class="row">
            <div class="col-6 border-end">
                <div class="small text-muted fw-bold">TOTAL MARKS</div>
                <div class="h4 fw-black text-primary m-0"><?= $total_marks ?></div>
            </div>
            <div class="col-6">
                <div class="small text-muted fw-bold">AVG POINT</div>
                <div class="h4 fw-black text-primary m-0"><?= number_format($total_gp / $sub_count, 2) ?></div>
            </div>
        </div>
    </div>
</div>