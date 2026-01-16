<?php
// ফাইল: front-page-block/admin-teacher-attnd.php

$teacher_att_summary = [
    'total' => 0, 'present' => 0, 'on_leave' => 0, 'absent' => 0, 'rate' => 0,
    'absent_list' => []
];

if (isset($conn, $sccode, $td)) {
    // দক্ষ কুয়েরি: শিক্ষকদের উপস্থিতি এবং ছুটির তথ্য একসাথে আনা
    $sql = "SELECT t.tid, t.tname, t.position, ta.statusin, tl.tid AS on_leave
            FROM teacher t
            LEFT JOIN teacherattnd ta ON t.tid = ta.tid AND ta.sccode = ? AND ta.adate = ?
            LEFT JOIN teacher_leave_app tl ON t.tid = tl.tid AND tl.sccode = ? AND tl.status = 1 
                 AND ? BETWEEN tl.date_from AND tl.date_to
            WHERE t.sccode = ?
            ORDER BY t.sl ASC, t.id ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $sccode, $td, $sccode, $td, $sccode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $all_teachers = $result->fetch_all(MYSQLI_ASSOC);
        $teacher_att_summary['total'] = count($all_teachers);

        foreach ($all_teachers as $teacher) {
            if ($teacher['statusin'] !== null) {
                $teacher_att_summary['present']++;
            } else if ($teacher['on_leave'] !== null) {
                $teacher_att_summary['on_leave']++;
                $teacher['status'] = 'On Leave';
                $teacher_att_summary['absent_list'][] = $teacher;
            } else {
                $teacher_att_summary['absent']++;
                $teacher['status'] = 'Absent';
                $teacher_att_summary['absent_list'][] = $teacher;
            }
        }

        if ($teacher_att_summary['total'] > 0) {
            $teacher_att_summary['rate'] = round(($teacher_att_summary['present'] * 100) / $teacher_att_summary['total']);
        }
    }
    $stmt->close();
}

if ($teacher_att_summary['total'] > 0):
?>

<div class="m-card elevation-1 border-0 mb-4 overflow-hidden">
    <div class="p-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-secondary small text-uppercase tracking-wider">Teacher Attendance</h6>
            <div class="rounded-circle bg-primary-subtle p-2">
                <i class="bi bi-briefcase-fill text-primary fs-5"></i>
            </div>
        </div>

        <div class="row align-items-end mb-3">
            <div class="col-8">
                <h2 class="display-6 fw-bold mb-0 text-dark"><?php echo $teacher_att_summary['present']; ?></h2>
                <p class="text-muted small mb-0">Present of <?php echo $teacher_att_summary['total']; ?> Teachers</p>
            </div>
            <div class="col-4 text-end">
                <span class="h5 fw-bold text-primary"><?php echo $teacher_att_summary['rate']; ?>%</span>
            </div>
        </div>

        <div class="progress rounded-pill mb-4" style="height: 8px; background-color: var(--md-surface-variant);">
            <div class="progress-bar bg-primary rounded-pill" role="progressbar" 
                 style="width: <?php echo $teacher_att_summary['rate']; ?>%"></div>
        </div>
    </div>

    <?php if (!empty($teacher_att_summary['absent_list'])): ?>
    <div class="mx-n3 px-3 py-2 bg-light border-top">
        <h6 class="mb-2 mt-1 small fw-bold text-secondary">Absent or On Leave (<?php echo count($teacher_att_summary['absent_list']); ?>)</h6>
        
        <div class="d-flex flex-column gap-2">
            <?php foreach ($teacher_att_summary['absent_list'] as $teacher): 
                $photo = "teacher/" . $teacher['tid'] . ".jpg";
                $status_class = ($teacher['status'] == 'On Leave') ? 'bg-warning-subtle text-warning-emphasis' : 'bg-danger-subtle text-danger-emphasis';
            ?>
            <div class="d-flex align-items-center p-2 rounded-3 bg-white border-bottom-0 shadow-sm-sm">
                <img src="<?php echo $photo; ?>" 
                     class="rounded-circle me-3 border" 
                     style="width: 42px; height: 42px; object-fit: cover;" 
                     onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
                
                <div class="flex-grow-1">
                    <div class="fw-bold small text-dark"><?php echo htmlspecialchars($teacher['tname']); ?></div>
                    <div class="text-muted" style="font-size: 0.7rem;"><?php echo htmlspecialchars($teacher['position']); ?></div>
                </div>
                
                <span class="badge rounded-pill <?php echo $status_class; ?> px-2 py-1" style="font-size: 0.65rem;">
                    <?php echo strtoupper($teacher['status']); ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="p-2 mt-2">
        <a href="teacher-attnd-report.php" class="btn btn-link btn-sm w-100 text-decoration-none fw-bold">
            Full Attendance Report <i class="bi bi-chevron-right ms-1"></i>
        </a>
    </div>
</div>

<?php endif; ?>