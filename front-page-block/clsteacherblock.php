<?php
// File: front-page-block/clsteacherblock.php

$is_collection_user = false;
$class_teacher_payment_data = [];

if (isset($ins_all_settings, $userlevel, $cteacher_data, $conn, $sccode, $sy)) {
    // ১. পারমিশন চেক (Optimized)
    $settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
    if (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) {
        $is_collection_user = true;
    }

    // ২. ডাটা ফেচিং (Single Query Optimization)
    if ($is_collection_user && !empty($cteacher_data)) {
        $current_month = (int)date('m');
        $sy_param = "%$sy%";

        // ক্লাস এবং সেকশনের লিস্ট তৈরি করা কোয়েরির জন্য
        $placeholders = [];
        $types = "ssi"; // sessionyear, sccode, month
        $params = [$sy_param, $sccode, $current_month];

        foreach ($cteacher_data as $class) {
            $placeholders[] = "(?, ?)";
            $types .= "ss";
            $params[] = $class['cteachercls'];
            $params[] = $class['cteachersec'];
        }
        
        $sql = "SELECT classname, sectionname, SUM(dues) as d, SUM(payableamt) as py, SUM(paid) as pd 
                FROM stfinance 
                WHERE sessionyear LIKE ? AND sccode = ? AND month <= ? 
                AND (classname, sectionname) IN (" . implode(',', $placeholders) . ")
                GROUP BY classname, sectionname";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $row['collection_percent'] = ($row['py'] > 0) ? round(($row['pd'] * 100) / $row['py']) : 0;
            $class_teacher_payment_data[] = $row;
        }
        $stmt->close();
    }
}

// --- Presentation (Material 3 UI) ---
if (!empty($class_teacher_payment_data)):
?>

<div class="m-card elevation-1 border-0 mb-4">
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle bg-secondary-subtle p-2 me-3">
            <i class="bi bi-collection-play text-secondary fs-4"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0">My Class Collections</h6>
            <small class="text-muted">Summary per assigned section</small>
        </div>
    </div>

    <div class="d-flex flex-column gap-4">
        <?php foreach ($class_teacher_payment_data as $summary): ?>
            <div class="p-3 rounded-4" style="background-color: var(--md-surface-variant, #f4f4f4);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge rounded-pill bg-white text-dark border px-3 py-2 fw-bold">
                        <i class="bi bi-bookmark-star me-1 text-primary"></i>
                        <?php echo htmlspecialchars($summary['classname'] . ' : ' . $summary['sectionname']); ?>
                    </span>
                    <span class="fw-bold text-primary"><?php echo $summary['collection_percent']; ?>%</span>
                </div>

                <div class="progress rounded-pill mb-3" style="height: 8px; background-color: rgba(0,0,0,0.05);">
                    <div class="progress-bar bg-primary rounded-pill" role="progressbar" 
                         style="width: <?php echo $summary['collection_percent']; ?>%"></div>
                </div>

                <div class="row g-2 text-center small mb-3">
                    <div class="col-4">
                        <div class="text-muted mb-1" style="font-size: 0.6rem;">PAID</div>
                        <div class="fw-bold"><?php echo number_format($summary['pd']); ?></div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted mb-1" style="font-size: 0.6rem;">PAYABLE</div>
                        <div class="fw-bold"><?php echo number_format($summary['py']); ?></div>
                    </div>
                    <div class="col-4 text-danger">
                        <div class="text-danger-emphasis mb-1" style="font-size: 0.6rem;">DUES</div>
                        <div class="fw-bold"><?php echo number_format($summary['d']); ?></div>
                    </div>
                </div>

                <div class="d-grid">
                    <a href="finstudents.php?cls=<?php echo urlencode($summary['classname']); ?>&sec=<?php echo urlencode($summary['sectionname']); ?>" 
                       class="btn btn-white btn-sm rounded-pill border shadow-sm py-2 fw-medium">
                        <i class="bi bi-person-lines-fill me-2"></i> Student Details
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>