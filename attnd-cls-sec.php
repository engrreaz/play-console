<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. অপ্টিমাইজেশন: সব ক্লাসের আজকের সামারি ডাটা একবারেই ফেচ করা (N+1 Query সমাধান)
$summary_map = [];
$sy_param = "%$sy%";
$stmt_sum = $conn->prepare("SELECT * FROM stattndsummery WHERE sessionyear LIKE ? AND sccode = ? AND date = ?");
$stmt_sum->bind_param("sss", $sy_param, $sccode, $td);
$stmt_sum->execute();
$res_sum = $stmt_sum->get_result();
while ($row = $res_sum->fetch_assoc()) {
    $key = $row['classname'] . '|' . $row['sectionname'];
    $summary_map[$key] = $row;
}
$stmt_sum->close();
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Top App Bar Custom Style */
    .m3-app-bar {
        background: #fff;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    /* Attendance Card Styling */
    .att-card {
        background: #FFFFFF;
        border-radius: 28px;
        border: none;
        padding: 20px;
        margin-bottom: 12px;
        transition: transform 0.2s, background-color 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        cursor: pointer;
    }
    .att-card:active { transform: scale(0.97); background-color: #F3EDF7; }

    /* Custom Progress Bar M3 Style */
    .m3-progress-container {
        height: 8px;
        border-radius: 100px;
        background-color: #E7E0EC;
        overflow: hidden;
        display: flex;
    }
    .bar-present { background-color: #4CAF50; transition: width 0.5s ease; }
    .bar-bunk { background-color: #FFB900; transition: width 0.5s ease; }

    .percentage-text { font-size: 1.5rem; font-weight: 800; color: #1C1B1F; line-height: 1; }
    .class-label { font-weight: 700; color: #6750A4; font-size: 1rem; }
    
    .stats-chip {
        background: #F3EDF7;
        color: #49454F;
        border-radius: 100px;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-4 shadow-sm">
        <div class="d-flex align-items-center">
            <a href="reporthome.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h4 class="fw-bold mb-0">Attendance Status</h4>
                <div class="small text-muted fw-medium">
                    <i class="bi bi-calendar3 me-1"></i> <?php echo date('d F, Y', strtotime($td)); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-3">
        <?php
        // ২. ক্লাসের লিস্ট ফেচ করা (Prepared Statement)
        $stmt_areas = $conn->prepare("SELECT areaname, subarea FROM areas WHERE sessionyear LIKE ? AND user = ? 
                                      ORDER BY FIELD(areaname,'Six', 'Seven', 'Eight', 'Nine', 'Ten'), idno, subarea");
        $stmt_areas->bind_param("ss", $sy_param, $rootuser);
        $stmt_areas->execute();
        $res_areas = $stmt_areas->get_result();

        if ($res_areas->num_rows > 0):
            while ($row = $res_areas->fetch_assoc()):
                $cls = $row["areaname"];
                $sec = $row["subarea"];
                $key = $cls . '|' . $sec;
                $lnk = "cls=" . urlencode($cls) . '&sec=' . urlencode($sec);

                // ম্যাপ থেকে ডাটা নেওয়া
                $sdata = $summary_map[$key] ?? null;
                $rate = $sdata["attndrate"] ?? 0;
                $fnd  = $sdata["attndstudent"] ?? 0;
                $cnt  = $sdata["totalstudent"] ?? 0;
                $bunk = $sdata["bunk"] ?? 0;
                
                $bunk_rate = ($cnt > 0) ? ceil($bunk * 100 / $cnt) : 0;
                $effective_rate = $rate - $bunk_rate;
                if($effective_rate < 0) $effective_rate = 0;
        ?>
            <div class="att-card shadow-sm" onclick="go('<?php echo $lnk; ?>')">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="class-label"><?php echo $cls; ?> <i class="bi bi-dot"></i> <?php echo $sec; ?></div>
                        <div class="mt-2">
                            <span class="stats-chip">
                                <i class="bi bi-people-fill me-1"></i> <?php echo $fnd; ?> / <?php echo $cnt; ?> Present
                            </span>
                            <?php if($bunk > 0): ?>
                            <span class="stats-chip ms-1 border border-warning-subtle text-warning-emphasis">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo $bunk; ?> Bunk
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="percentage-text"><?php echo number_format($effective_rate, 0); ?><small class="fs-6">%</small></div>
                        <div class="small text-muted fw-bold">Present</div>
                    </div>
                </div>

                <div class="m3-progress-container mb-1 shadow-sm-sm">
                    <div class="bar-present" style="width: <?php echo $effective_rate; ?>%;"></div>
                    <div class="bar-bunk" style="width: <?php echo $bunk_rate; ?>%;"></div>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size: 0.65rem;">
                    <span class="text-success fw-bold">Attendance</span>
                    <?php if($bunk_rate > 0): ?><span class="text-warning fw-bold">Class Bunk: <?php echo $bunk_rate; ?>%</span><?php endif; ?>
                </div>
            </div>

        <?php 
            endwhile; 
        else:
            echo '<div class="text-center py-5 opacity-50"><i class="bi bi-folder-x fs-1"></i><br>No classes found.</div>';
        endif; 
        $stmt_areas->close();
        ?>
    </div>
</main>

<div style="height: 70px;"></div>

<script>
    function go(params) {
        // সরাসরি অ্যাটেনডেন্স রেজিস্টার পেজে নিয়ে যাবে
        window.location.href = "st-attnd-register.php?" + params;
    }
</script>

<?php include 'footer.php'; ?>