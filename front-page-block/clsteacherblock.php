<?php



$is_collection_user = false;
$class_teacher_payment_data = [];

if (isset($ins_all_settings, $userlevel, $cteacher_data, $conn, $sccode)) {
    // ২. পারমিশন চেক (Optimized)
    $settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
    if (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) {
        $is_collection_user = true;
    }

    // ৩. ডাটা ফেচিং (Logic remains robust)
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
    } else {
        echo 'No Collection';
    }
}

if (!empty($class_teacher_payment_data)):
?>

<style>
    /* Main Container (8px Radius) */
    .m3-coll-block { 
        background: #fff; border-radius: 8px; padding: 12px; 
        border: 1px solid #f0f0f0; margin-bottom: 12px;
    }
    
    /* Condensed Item (Tonal Container) */
    .m3-tonal-card {
        background-color: #F7F2FA; border-radius: 8px; padding: 10px;
        margin-bottom: 8px; border: 1px solid #EADDFF;
    }
    .m3-tonal-card:last-child { margin-bottom: 0; }

    /* Tags & Badges */
    .tag-chip {
        font-size: 0.55rem; font-weight: 800; background: #fff; color: #6750A4;
        padding: 2px 8px; border-radius: 4px; border: 1px solid #EADDFF;
        text-transform: uppercase; letter-spacing: 0.5px;
    }

    /* Minimal High-Density Progress */
    .m3-mini-progress { background: #E7E0EC; height: 4px; border-radius: 2px; overflow: hidden; }
    .m3-bar-fill { background: #6750A4; height: 100%; transition: width 0.4s ease; }

    /* Stat Grid (Condensed) */
    .m3-stat-row { display: flex; margin-top: 8px; border-top: 1px dashed #EADDFF; padding-top: 8px; }
    .m3-stat-col { flex: 1; text-align: center; border-right: 1px solid #EADDFF; }
    .m3-stat-col:last-child { border-right: none; }
    
    .lbl-tiny { font-size: 0.5rem; font-weight: 700; color: #79747E; text-transform: uppercase; }
    .val-small { font-size: 0.75rem; font-weight: 800; color: #1D1B20; }

    /* Action Button (8px Radius) */
    .btn-m3-tonal-sm {
        background: #EADDFF; color: #21005D; border-radius: 6px;
        font-size: 0.65rem; font-weight: 800; padding: 6px;
        text-decoration: none !important; display: block; text-align: center;
        margin-top: 10px; transition: background 0.2s; border: none; width: 100%;
    }
    .btn-m3-tonal-sm:active { background: #D0BCFF; transform: scale(0.98); }
</style>

<div class="m3-coll-block shadow-sm">
    <div class="d-flex align-items-center mb-3">
        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 32px; height: 32px;">
            <i class="bi bi-wallet2" style="font-size: 0.9rem;"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Class Collection</h6>
            <div class="small text-muted" style="font-size: 0.6rem; font-weight: 600;">Academic Session: <?php echo $sessionyear; ?></div>
        </div>
    </div>

    <div class="d-flex flex-column">
        <?php foreach ($class_teacher_payment_data as $summary): ?>
            <div class="m3-tonal-card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="tag-chip">
                        <i class="bi bi-bookmark-star-fill me-1"></i>
                        <?php echo htmlspecialchars($summary['classname'] . ' | ' . $summary['sectionname']); ?>
                    </span>
                    <span class="fw-bold text-primary" style="font-size: 0.75rem;"><?php echo $summary['collection_percent']; ?>%</span>
                </div>

                <div class="m3-mini-progress mb-1">
                    <div class="m3-bar-fill" style="width: <?php echo $summary['collection_percent']; ?>%"></div>
                </div>

                <div class="m3-stat-row">
                    <div class="m3-stat-col">
                        <div class="lbl-tiny">Paid</div>
                        <div class="val-small"><?php echo number_format($summary['pd']); ?></div>
                    </div>
                    <div class="m3-stat-col">
                        <div class="lbl-tiny">Target</div>
                        <div class="val-small"><?php echo number_format($summary['py']); ?></div>
                    </div>
                    <div class="m3-stat-col">
                        <div class="lbl-tiny" style="color: #B3261E;">Due</div>
                        <div class="val-small text-danger"><?php echo number_format($summary['d']); ?></div>
                    </div>
                </div>

                <a href="finstudents.php?cls=<?php echo urlencode($summary['classname']); ?>&sec=<?php echo urlencode($summary['sectionname']); ?>&year=<?php echo $sessionyear; ?>" 
                   class="btn-m3-tonal-sm shadow-sm">
                    <i class="bi bi-person-lines-fill me-1"></i> VIEW STUDENT LIST
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>