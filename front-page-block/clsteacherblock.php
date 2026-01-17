<?php
// File: front-page-block/clsteacherblock.php

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;
$sy_param = "%" . $current_session . "%";

$is_collection_user = false;
$class_teacher_payment_data = [];

if (isset($ins_all_settings, $userlevel, $cteacher_data, $conn, $sccode)) {
    // ২. পারমিশন চেক (Optimized)
    $settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
    if (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) {
        $is_collection_user = true;
    }

    // ৩. ডাটা ফেচিং
    if ($is_collection_user && !empty($cteacher_data)) {
        $current_month = (int)date('m');

        $placeholders = [];
        $types = "ssi"; 
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

// --- Presentation (M3 UI) ---
if (!empty($class_teacher_payment_data)):
?>

<style>
    .m3-block-card {
        background: #fff; border-radius: 8px; padding: 16px;
        margin-bottom: 12px; border: 1px solid #f0f0f0;
    }
    
    .m3-tonal-row {
        background-color: #F3EDF7; border-radius: 8px; /* আপনার নির্দেশিত ৮ পিক্সেল */
        padding: 12px; margin-bottom: 10px; border: 1px solid #EADDFF;
    }

    .st-badge {
        font-size: 0.65rem; font-weight: 800; background: #fff; color: #6750A4;
        padding: 4px 10px; border-radius: 6px; border: 1px solid #EADDFF;
        text-transform: uppercase; letter-spacing: 0.5px;
    }

    .m3-progress { background: #E7E0EC; height: 6px; border-radius: 3px; overflow: hidden; }
    .m3-progress-bar { background: #6750A4; height: 100%; transition: width 0.4s ease; }

    .stat-box-m3 { text-align: center; flex: 1; }
    .stat-label-m3 { font-size: 0.55rem; font-weight: 700; color: #49454F; text-transform: uppercase; }
    .stat-value-m3 { font-size: 0.85rem; font-weight: 800; color: #1D1B20; }

    .btn-m3-tonal {
        background: #EADDFF; color: #21005D; border-radius: 8px;
        font-size: 0.7rem; font-weight: 800; padding: 8px;
        text-decoration: none !important; display: block; text-align: center;
        transition: 0.2s; border: none; width: 100%;
    }
    .btn-m3-tonal:active { transform: scale(0.97); background: #D0BCFF; }
</style>

<div class="m3-block-card shadow-sm">
    <div class="d-flex align-items-center mb-3">
        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
            <i class="bi bi-pie-chart-fill" style="font-size: 1rem;"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Class Collection</h6>
            <div class="small text-muted" style="font-size: 0.65rem;">Academic Year: <?php echo $current_session; ?></div>
        </div>
    </div>

    <div class="d-flex flex-column">
        <?php foreach ($class_teacher_payment_data as $summary): ?>
            <div class="m3-tonal-row shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="st-badge">
                        <i class="bi bi-bookmark-fill me-1"></i>
                        <?php echo htmlspecialchars($summary['classname'] . ' : ' . $summary['sectionname']); ?>
                    </span>
                    <span class="fw-bold text-primary" style="font-size: 0.8rem;"><?php echo $summary['collection_percent']; ?>%</span>
                </div>

                <div class="m3-progress mb-3">
                    <div class="m3-progress-bar" style="width: <?php echo $summary['collection_percent']; ?>%"></div>
                </div>

                <div class="d-flex mb-3">
                    <div class="stat-box-m3 border-end">
                        <div class="stat-label-m3">Paid</div>
                        <div class="stat-value-m3"><?php echo number_format($summary['pd']); ?></div>
                    </div>
                    <div class="stat-box-m3 border-end">
                        <div class="stat-label-m3">Payable</div>
                        <div class="stat-value-m3"><?php echo number_format($summary['py']); ?></div>
                    </div>
                    <div class="stat-box-m3">
                        <div class="stat-label-m3 text-danger">Dues</div>
                        <div class="stat-value-m3 text-danger"><?php echo number_format($summary['d']); ?></div>
                    </div>
                </div>

                <a href="finstudents.php?cls=<?php echo urlencode($summary['classname']); ?>&sec=<?php echo urlencode($summary['sectionname']); ?>&year=<?php echo $current_session; ?>" 
                   class="btn-m3-tonal shadow-sm">
                    <i class="bi bi-arrow-right-circle me-1"></i> VIEW STUDENT LIST
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>