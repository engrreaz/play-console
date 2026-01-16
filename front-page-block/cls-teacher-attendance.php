<?php
// ১. ডাটা চেক এবং লুপ শুরু
if (!empty($cteacher_data) && is_array($cteacher_data)) {
    foreach ($cteacher_data as $cteacher) {
        $x1 = $cteacher['cteachercls'];
        $x2 = $cteacher['cteachersec'];

        // ২. আজকের উপস্থিতির ডাটা ফেচ করা (Prepared Statement)
        $found_attnd = $found_bunk = 0;
        $sy_param = "%$sy%";
        $stmt_att = $conn->prepare("SELECT SUM(yn) as yn, SUM(bunk) as bunk FROM stattnd WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND adate = ?");
        $stmt_att->bind_param("sssss", $sy_param, $sccode, $x1, $x2, $td);
        $stmt_att->execute();
        $res_att = $stmt_att->get_result();
        if ($row = $res_att->fetch_assoc()) {
            $found_attnd = $row["yn"] ?? 0;
            $found_bunk = $row["bunk"] ?? 0;
        }
        $stmt_att->close();

        // ৩. মোট সক্রিয় ছাত্র সংখ্যা ফেচ করা
        $found_stu = 0;
        $stmt_stu = $conn->prepare("SELECT COUNT(*) as cnt FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND status = '1'");
        $stmt_stu->bind_param("ssss", $sy_param, $sccode, $x1, $x2);
        $stmt_stu->execute();
        $res_stu = $stmt_stu->get_result();
        if ($row = $res_stu->fetch_assoc()) {
            $found_stu = $row["cnt"];
        }
        $stmt_stu->close();

        // ৪. সামারি টেবিল চেক (যদি থাকে)
        $tstu = $found_stu;
        $astu = $found_attnd;
        $stat_color = '#6750A4'; // M3 Primary Color

        $stmt_sum = $conn->prepare("SELECT totalstudent, attndstudent FROM stattndsummery WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND date = ?");
        $stmt_sum->bind_param("sssss", $sy_param, $sccode, $x1, $x2, $td);
        $stmt_sum->execute();
        $res_sum = $stmt_sum->get_result();
        if ($row = $res_sum->fetch_assoc()) {
            $tstu = $row["totalstudent"];
            $astu = $row["attndstudent"];
        } else if ($found_attnd == 0) {
            $stat_color = '#79747E'; // M3 Outline/Gray
        }
        $stmt_sum->close();

        // ৫. পার্সেন্টেজ এবং ডিগ্রি ক্যালকুলেশন
        $tperc = $bperc = $dispperc = 0;
        if ($tstu > 0) {
            $dispperc = ceil($astu * 100 / $tstu);
            $bperc_raw = ceil($found_bunk * 100 / $tstu);
            $present_only_perc = $dispperc - $bperc_raw;
            
            $present_deg = $present_only_perc * 3.6;
            $bunk_deg = ($present_only_perc + $bperc_raw) * 3.6;
        } else {
            $present_deg = 0; $bunk_deg = 0;
        }
        ?>

        <style>
            .m3-att-card {
                background: #FFFFFF; border-radius: 28px; border: none;
                margin-bottom: 16px; padding: 20px; transition: transform 0.2s;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .m3-att-card:active { transform: scale(0.98); background: #F3EDF7; }
            
            .circular-indicator {
                width: 80px; height: 80px; border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                background: conic-gradient(
                    #4CAF50 0deg <?php echo $present_deg; ?>deg, 
                    #FFB900 <?php echo $present_deg; ?>deg <?php echo $bunk_deg; ?>deg, 
                    #F2B8B5 <?php echo $bunk_deg; ?>deg 360deg
                );
                position: relative;
            }
            .circular-indicator::before {
                content: "<?php echo $dispperc; ?>%";
                position: absolute; width: 64px; height: 64px;
                background: #fff; border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                font-weight: 800; font-size: 1rem; color: #1C1B1F;
            }
            .class-info-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #6750A4; letter-spacing: 0.5px; }
            .btn-m3-tonal { background: #EADDFF; color: #21005D; border-radius: 100px; font-weight: 600; border: none; padding: 10px 20px; width: 100%; transition: 0.2s; }
            .btn-m3-tonal:active { background: #D0BCFF; }
        </style>

        <div class="m3-att-card shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="class-info-label">
                    <i class="bi bi-calendar-check me-1"></i> Student Attendance
                </div>
                <div class="text-muted small fw-bold"><?php echo date('d M, Y', strtotime($td)); ?></div>
            </div>

            <div class="row align-items-center" onclick="home_goclsatt('<?php echo $x1; ?>','<?php echo $x2; ?>');">
                <div class="col-8">
                    <h3 class="fw-extrabold mb-1" style="color: <?php echo $stat_color; ?>;">
                        <?php echo $astu; ?> <span class="fs-6 fw-medium text-muted">/ <?php echo $tstu; ?> Present</span>
                    </h3>
                    <div class="d-flex flex-column gap-1">
                        <div class="small fw-bold text-dark"><?php echo $x1; ?> <i class="bi bi-dot"></i> <?php echo $x2; ?></div>
                        <div class="small text-danger fw-medium">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Bunk: <?php echo $found_bunk; ?> Students
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <div class="circular-indicator shadow-sm"></div>
                </div>
            </div>

            <div class="mt-4">
                <a href="st-attnd-register.php?cls=<?php echo urlencode($x1); ?>&sec=<?php echo urlencode($x2); ?>" 
                   class="btn btn-m3-tonal text-decoration-none d-block text-center">
                   <i class="bi bi-journal-text me-2"></i> Attendance Register
                </a>
            </div>
        </div>

        <?php
    }
}
?>