<?php
// ফাইল: front-page-block/admin-teacher-attnd.php

// সেশন হ্যান্ডলিং (ড্যাশবোর্ড থেকে পাস করা না থাকলে ব্যাকআপ)
$current_session = $current_session ?? $sy;

$teacher_att_summary = [
    'total' => 0, 'present' => 0, 'on_leave' => 0, 'absent' => 0, 'rate' => 0,
    'absent_list' => []
];

if (isset($conn, $sccode, $td)) {
    // শিক্ষকদের উপস্থিতি এবং ছুটির তথ্য একসাথে আনার দক্ষ কুয়েরি
    $sql = "SELECT t.tid, t.tname, t.position, ta.statusin, tl.tid AS on_leave
            FROM teacher t
            LEFT JOIN teacherattnd ta ON t.tid = ta.tid AND ta.sccode = ? AND ta.adate = ?
            LEFT JOIN teacher_leave_app tl ON t.tid = tl.tid AND tl.sccode = ? AND tl.status = 1 
                 AND ? BETWEEN tl.date_from AND tl.date_to
            WHERE t.sccode = ?
            ORDER BY t.ranks ASC, t.id ASC";

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

<style>
    .att-widget-card { background: #fff; border-radius: 8px; padding: 12px; }
    
    .att-header-lbl { font-size: 0.65rem; font-weight: 800; color: #6750A4; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .metric-val { font-size: 1.5rem; font-weight: 800; color: #1C1B1F; line-height: 1.2; }
    .metric-unit { font-size: 0.7rem; font-weight: 600; color: #49454F; }
    
    .m3-progress-thin { background: #EADDFF; height: 6px; border-radius: 3px; overflow: hidden; }
    .m3-progress-bar-fill { background: #6750A4; height: 100%; transition: width 0.6s ease; }

    .absent-row { 
        background: #F3EDF7; border-radius: 8px; padding: 8px; 
        margin-bottom: 6px; border: 1px solid #EADDFF;
    }
    .squircle-avatar { 
        width: 36px; height: 36px; border-radius: 8px; /* আপনার নির্দেশিত ৮ পিক্সেল */
        object-fit: cover; border: 1px solid #fff;
    }
    
    .st-chip-m3 { font-size: 0.55rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; }
    .chip-absent { background: #F9DEDC; color: #B3261E; }
    .chip-leave { background: #FFECB3; color: #E46C0A; }
</style>

<div class="att-widget-card shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="att-header-lbl"><i class="bi bi-people-fill me-1"></i> Staff Attendance</span>
        <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.6rem;">TODAY</span>
    </div>

    <div class="d-flex align-items-end justify-content-between mb-2">
        <div>
            <span class="metric-val"><?php echo $teacher_att_summary['present']; ?></span>
            <span class="metric-unit">/ <?php echo $teacher_att_summary['total']; ?> Present</span>
        </div>
        <div class="fw-bold text-primary" style="font-size: 0.9rem;"><?php echo $teacher_att_summary['rate']; ?>%</div>
    </div>

    <div class="m3-progress-thin mb-3">
        <div class="m3-progress-bar-fill" style="width: <?php echo $teacher_att_summary['rate']; ?>%"></div>
    </div>

    <?php if (!empty($teacher_att_summary['absent_list'])): ?>
        <div class="mt-2">
            <div class="mb-2 small fw-bold text-muted" style="font-size: 0.65rem;">OFF-DUTY STAFF (<?php echo count($teacher_att_summary['absent_list']); ?>)</div>
            
            <?php foreach (array_slice($teacher_att_summary['absent_list'], 0, 3) as $teacher): 
                $photo = "teacher/" . $teacher['tid'] . ".jpg";
                $is_leave = ($teacher['status'] == 'On Leave');
            ?>
                <div class="absent-row d-flex align-items-center shadow-sm">
                    <img src="<?php echo $photo; ?>" class="squircle-avatar me-2" 
                         onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
                    
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.75rem;"><?php echo htmlspecialchars($teacher['tname']); ?></div>
                        <div class="text-muted" style="font-size: 0.6rem;"><?php echo htmlspecialchars($teacher['position']); ?></div>
                    </div>
                    
                    <span class="st-chip-m3 <?php echo $is_leave ? 'chip-leave' : 'chip-absent'; ?>">
                        <?php echo $is_leave ? 'Leave' : 'Absent'; ?>
                    </span>
                </div>
            <?php endforeach; ?>
            
            <?php if (count($teacher_att_summary['absent_list']) > 3): ?>
                <div class="text-center small text-muted opacity-75 fw-bold mt-1" style="font-size: 0.6rem;">
                    + <?php echo count($teacher_att_summary['absent_list']) - 3; ?> MORE STAFF
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="mt-2 pt-2 border-top">
        <a href="teacher-attnd-report.php?year=<?php echo $current_session; ?>" 
           class="btn btn-link btn-sm w-100 text-decoration-none fw-bold p-0" style="font-size: 0.7rem;">
            FULL LOGS <i class="bi bi-arrow-right-short"></i>
        </a>
    </div>
</div>

<?php endif; ?>