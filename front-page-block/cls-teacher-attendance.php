<?php
/**
 * Class Teacher Attendance Block - Refactored for Android WebView
 * M3 Standards | 8px Radius | Session Sync
 */

// ড্যাশবোর্ড থেকে $current_session এবং $sy_param অটোমেটিক আসবে
$current_session = $current_session ?? $sy;
$sy_param = "%" . $current_session . "%";

if (!empty($cteacher_data) && is_array($cteacher_data)) {
    foreach ($cteacher_data as $cteacher) {
        $x1 = $cteacher['cteachercls'];
        $x2 = $cteacher['cteachersec'];

        // ১. আজকের উপস্থিতির ডাটা ফেচ করা (Secure Query)
        $found_attnd = $found_bunk = 0;
        $stmt_att = $conn->prepare("SELECT SUM(yn) as yn, SUM(bunk) as bunk FROM stattnd WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND adate = ?");
        $stmt_att->bind_param("sssss", $sy_param, $sccode, $x1, $x2, $td);
        $stmt_att->execute();
        $res_att = $stmt_att->get_result();
        if ($row = $res_att->fetch_assoc()) {
            $found_attnd = $row["yn"] ?? 0;
            $found_bunk = $row["bunk"] ?? 0;
        }
        $stmt_att->close();

        // ২. মোট সক্রিয় ছাত্র সংখ্যা ফেচ করা
        $found_stu = 0;
        $stmt_stu = $conn->prepare("SELECT COUNT(*) as cnt FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND status = '1'");
        $stmt_stu->bind_param("ssss", $sy_param, $sccode, $x1, $x2);
        $stmt_stu->execute();
        $res_stu = $stmt_stu->get_result();
        if ($row = $res_stu->fetch_assoc()) { $found_stu = $row["cnt"]; }
        $stmt_stu->close();

        // ৩. ডাইনামিক ক্যালকুলেশন
        $tstu = $found_stu;
        $astu = $found_attnd;
        $dispperc = ($tstu > 0) ? ceil($astu * 100 / $tstu) : 0;
        $bperc_raw = ($tstu > 0) ? ceil($found_bunk * 100 / $tstu) : 0;
        
        // চার্ট ডিগ্রিস
        $present_only_perc = $dispperc - $bperc_raw;
        $present_deg = $present_only_perc * 3.6;
        $bunk_deg = ($present_only_perc + $bperc_raw) * 3.6;
        ?>

        <style>
            .att-block-card {
                background: #FFFFFF; border-radius: 8px; border: 1px solid #f0f0f0;
                margin-bottom: 12px; padding: 12px; position: relative;
            }
            
            /* Circular Chart (M3 Style) */
            .chart-circle {
                width: 64px; height: 64px; border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                background: conic-gradient(
                    #2E7D32 0deg <?php echo $present_deg; ?>deg, 
                    #F9A825 <?php echo $present_deg; ?>deg <?php echo $bunk_deg; ?>deg, 
                    #F2B8B5 <?php echo $bunk_deg; ?>deg 360deg
                );
                position: relative; flex-shrink: 0;
            }
            .chart-circle::after {
                content: "<?php echo $dispperc; ?>%";
                position: absolute; width: 50px; height: 50px;
                background: #fff; border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                font-weight: 800; font-size: 0.85rem; color: #1C1B1F;
            }

            .cls-title { font-size: 0.85rem; font-weight: 800; color: #1C1B1F; line-height: 1.2; }
            .att-count { font-size: 1.1rem; font-weight: 900; color: #6750A4; }
            .att-label { font-size: 0.6rem; font-weight: 700; color: #79747E; text-transform: uppercase; }

            .btn-tonal-sm {
                background: #F3EDF7; color: #6750A4; border-radius: 6px;
                font-size: 0.65rem; font-weight: 800; padding: 6px 12px;
                text-decoration: none !important; display: inline-flex; align-items: center;
                margin-top: 8px; border: 1px solid #EADDFF; transition: 0.2s;
            }
            .btn-tonal-sm:active { background: #EADDFF; transform: scale(0.98); }
        </style>

        <div class="att-block-card shadow-sm">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 28px; height: 28px;">
                    <i class="bi bi-people-fill" style="font-size: 0.8rem;"></i>
                </div>
                <div class="cls-title flex-grow-1 text-truncate">
                    <?php echo $x1; ?> <i class="bi bi-dot"></i> <?php echo $x2; ?>
                </div>
                <div class="text-muted" style="font-size: 0.6rem; font-weight: 700;">LIVE UPDATES</div>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="att-count"><?php echo $astu; ?> <span class="fs-6 fw-bold text-muted">/ <?php echo $tstu; ?></span></div>
                    <div class="att-label mb-1">Present Today</div>
                    
                    <?php if ($found_bunk > 0): ?>
                        <div class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.55rem; font-weight: 800;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo $found_bunk; ?> BUNK DETECTED
                        </div>
                    <?php else: ?>
                        <div class="badge bg-success-subtle text-success-emphasis border border-success-subtle" style="font-size: 0.55rem; font-weight: 800;">
                            <i class="bi bi-shield-check-fill me-1"></i> NO BUNKS REPORTED
                        </div>
                    <?php endif; ?>
                </div>

                <div class="chart-circle shadow-sm"></div>
            </div>

            <div class="d-flex gap-2">
                <a href="stattnd.php?cls=<?php echo urlencode($x1); ?>&sec=<?php echo urlencode($x2); ?>&year=<?php echo $current_session; ?>" 
                   class="btn-tonal-sm shadow-sm flex-grow-1 justify-content-center">
                    <i class="bi bi-plus-circle me-1"></i> NEW ATTND
                </a>
                <a href="st-attnd-register.php?cls=<?php echo urlencode($x1); ?>&sec=<?php echo urlencode($x2); ?>&year=<?php echo $current_session; ?>" 
                   class="btn-tonal-sm shadow-sm flex-grow-1 justify-content-center" style="background: #fff;">
                    <i class="bi bi-journal-text me-1"></i> REGISTER
                </a>
            </div>
        </div>

        <?php
    }
}
?>