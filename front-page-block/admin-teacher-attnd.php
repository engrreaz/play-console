<?php
/**
 * Staff Attendance Widget - M3 Tonal Design
 * Optimized Query & Error Handling
 */

// ১. তারিখ নির্ধারণ
if (!isset($td)) {
    $td = date('Y-m-d');
}

$teacher_att_summary = [
    'total' => 0,
    'present' => 0,
    'on_leave' => 0,
    'absent' => 0,
    'rate' => 0,
    'absent_list' => []
];

// ২. ডাটাবেস কোয়েরি এবং প্রসেসিং
if (isset($conn, $sccode)) {
    // শুধুমাত্র একটি প্রিপেয়ার্ড স্টেটমেন্ট ব্যবহার করা হয়েছে নিরাপত্তার জন্য
    $sql = "SELECT t.tid, t.tname, t.position, ta.statusin, tl.tid as leave_id
            FROM teacher t
            LEFT JOIN teacherattnd ta ON t.tid = ta.tid AND ta.sccode = ? AND ta.adate = ?
            LEFT JOIN teacher_leave_app tl ON t.tid = tl.tid AND tl.sccode = ? AND tl.status = 1 
                 AND ? BETWEEN tl.date_from AND tl.date_to
            WHERE t.sccode = ? 
            ORDER BY t.ranks ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $sccode, $td, $sccode, $td, $sccode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $all_teachers = $result->fetch_all(MYSQLI_ASSOC);
        $teacher_att_summary['total'] = count($all_teachers);

        foreach ($all_teachers as $teacher) {
            if ($teacher['statusin'] !== null) {
                // উপস্থিত
                $teacher_att_summary['present']++;
            } elseif ($teacher['leave_id'] !== null) {
                // ছুটিতে আছেন
                $teacher_att_summary['on_leave']++;
                $teacher['status_label'] = 'On Leave';
                $teacher_att_summary['absent_list'][] = $teacher;
            } else {
                // অনুপস্থিত
                $teacher_att_summary['absent']++;
                $teacher['status_label'] = 'Absent';
                $teacher_att_summary['absent_list'][] = $teacher;
            }
        }

        // উপস্থিতির হার ক্যালকুলেশন
        if ($teacher_att_summary['total'] > 0) {
            $teacher_att_summary['rate'] = round(($teacher_att_summary['present'] * 100) / $teacher_att_summary['total']);
        }
    }
    $stmt->close();
}

// ৩. UI রেন্ডারিং (যদি স্টাফ ডাটা থাকে)
if ($teacher_att_summary['total'] > 0):
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-on-primary: #FFFFFF;
        --m3-surface: #FDFBFF;
        --m3-surface-container: #F3EDF7;
        --m3-on-surface: #1C1B1F;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-outline-variant: #EADDFF;
    }

    .att-widget-card {
        background: var(--m3-surface);
        border: 1px solid var(--m3-outline-variant);
        border-radius: 16px; /* M3 Medium Shape */
        padding: 16px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .att-header-lbl {
        font-size: 11px;
        font-weight: 700;
        color: var(--m3-primary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .metric-val {
        font-size: 28px;
        font-weight: 700;
        color: var(--m3-on-surface);
        line-height: 1.2;
    }

    .metric-unit {
        font-size: 13px;
        color: #49454F;
    }

    .m3-progress-thin {
        background: var(--m3-outline-variant);
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
    }

    .m3-progress-bar-fill {
        background: var(--m3-primary);
        height: 100%;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .absent-row {
        background: var(--m3-surface-container);
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        transition: background-color 0.2s;
    }

    .squircle-avatar {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        object-fit: cover;
        background: #E0E2EC;
    }

    .st-chip-m3 {
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        text-transform: uppercase;
    }

    .chip-absent { background: #F9DEDC; color: #B3261E; }
    .chip-leave { background: #FFDCC3; color: #301400; }

    .m3-btn-report {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 10px;
        border: 1px solid var(--m3-outline-variant);
        border-radius: 100px;
        background: transparent;
        color: var(--m3-primary);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        margin-top: 12px;
        transition: all 0.2s;
    }

    .m3-btn-report:active {
        background-color: var(--m3-primary-container);
    }
</style>

<div class="att-widget-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="att-header-lbl">
            <i class="bi bi-person-check-fill me-1"></i> Staff Attendance
        </span>
        <span class="badge" style="background: var(--m3-primary-container); color: var(--m3-on-primary-container); font-size: 10px; border-radius: 6px; padding: 4px 8px;">
            TODAY
        </span>
    </div>

    <div class="d-flex align-items-baseline justify-content-between mb-2">
        <div>
            <span class="metric-val"><?php echo $teacher_att_summary['present']; ?></span>
            <span class="metric-unit">/ <?php echo $teacher_att_summary['total']; ?> Present</span>
        </div>
        <div class="fw-bold" style="color: var(--m3-primary); font-size: 18px;">
            <?php echo $teacher_att_summary['rate']; ?>%
        </div>
    </div>

    <div class="m3-progress-thin mb-4">
        <div class="m3-progress-bar-fill" style="width: <?php echo $teacher_att_summary['rate']; ?>%"></div>
    </div>

    <?php if (!empty($teacher_att_summary['absent_list'])): ?>
        <div class="mt-2">
            <div class="mb-2 fw-bold text-muted" style="font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase;">
                Not On Duty (<?php echo count($teacher_att_summary['absent_list']); ?>)
            </div>

            <?php 
            // লুপ চালিয়ে সর্বোচ্চ ৩ জনকে দেখানো হচ্ছে
            foreach (array_slice($teacher_att_summary['absent_list'], 0, 3) as $teacher):
                $photo = teacher_profile_image_path($teacher['tid']);
                $is_leave = ($teacher['status_label'] == 'On Leave');
            ?>
                <div class="absent-row">
                    <img src="<?php echo $photo; ?>" class="squircle-avatar me-3" onerror="this.src='assets/default-user.png'">

                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold text-dark text-truncate" style="font-size: 14px;">
                            <?php echo htmlspecialchars($teacher['tname']); ?>
                        </div>
                        <div class="text-muted text-truncate" style="font-size: 11px;">
                            <?php echo htmlspecialchars($teacher['position']); ?>
                        </div>
                    </div>

                    <span class="st-chip-m3 <?php echo $is_leave ? 'chip-leave' : 'chip-absent'; ?>">
                        <?php echo $is_leave ? 'Leave' : 'Absent'; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="teacher-attnd-report.php" class="m3-btn-report">
        View Detailed Report <i class="bi bi-arrow-right-short ms-1"></i>
    </a>
</div>

<?php endif; ?>