<?php
// ফাইল: front-page-block/admin-st-attnd.php

$admin_summary = ['total' => 0, 'present' => 0, 'rate' => 0];
$has_areas = false;

if (isset($conn, $sccode, $sy, $td, $rootuser)) {
    $sy_like = "%$sy%";

    // অপ্টিমাইজড কোয়েরি: JOIN ব্যবহার করে সরাসরি সামারি বের করা
    $sql = "SELECT SUM(s.totalstudent) as total_st, SUM(s.attndstudent) as present_st 
            FROM areas a
            INNER JOIN stattndsummery s ON a.areaname = s.classname AND a.subarea = s.sectionname
            WHERE a.sessionyear LIKE ? AND a.user = ? AND s.sccode = ? AND s.date = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $sy_like, $rootuser, $sccode, $td);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();

    if ($data && $data['total_st'] > 0) {
        $has_areas = true;
        $admin_summary['total'] = (int)$data['total_st'];
        $admin_summary['present'] = (int)$data['present_st'];
        $admin_summary['rate'] = round(($admin_summary['present'] * 100) / $admin_summary['total']);
    }
    $stmt->close();
}

if ($has_areas):
?>

<div class="m-card elevation-1 border-0 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="fw-bold mb-1 text-secondary small text-uppercase tracking-wide">Student Attendance</h6>
            <span class="text-muted small"><?php echo date('F j, Y'); ?></span>
        </div>
        <div class="rounded-circle bg-info-subtle p-2">
            <i class="bi bi-person-check-fill text-info fs-4"></i>
        </div>
    </div>

    <div class="row align-items-center mb-3">
        <div class="col-7">
            <h2 class="display-6 fw-bold mb-0"><?php echo $admin_summary['present']; ?></h2>
            <p class="text-muted mb-0">Present of <?php echo $admin_summary['total']; ?></p>
        </div>
        <div class="col-5 text-end">
            <div class="h4 fw-bold text-info mb-0"><?php echo $admin_summary['rate']; ?>%</div>
            <div class="small text-muted">Attendance</div>
        </div>
    </div>

    <div class="progress rounded-pill mb-4" style="height: 10px; background-color: #E7E0EC;">
        <div class="progress-bar bg-info rounded-pill" 
             role="progressbar" 
             style="width: <?php echo $admin_summary['rate']; ?>%;" 
             aria-valuenow="<?php echo $admin_summary['rate']; ?>" 
             aria-valuemin="0" 
             aria-valuemax="100">
        </div>
    </div>

    <div class="d-grid">
        <a href="st-attnd-register.php" class="btn btn-light rounded-pill fw-medium py-2 border">
            <i class="bi bi-grid-3x3-gap me-2"></i> View Full Register
        </a>
    </div>
</div>

<?php endif; ?>