<?php
$page_title = "Class Attendance Summary";
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. ডাটা ফেচিং লজিক (অপরিবর্তিত)
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

<main>
    <div class="hero-container">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div>
                <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;">Attendance Status</div>
                <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 600; margin-top: 4px;">
                    <i class="bi bi-calendar3 me-1"></i> <?php echo date('d F, Y', strtotime($td)); ?>
                </div>
            </div>
        </div>

        <div style="margin-top: 22px; display: flex; gap: 10px;">
            <span class="session-pill" style="background: rgba(255,255,255,0.15); color: #fff; border: none;">
                SESSION <?php echo $sessionyear; ?>
            </span>
            <span class="session-pill" style="background: rgba(255,255,255,0.15); color: #fff; border: none;">
                TODAY
            </span>
        </div>
    </div>

    <div class="widget-grid" style="margin-top: 15px; padding: 0;">
        <div class="m3-section-title" style="margin-left: 4px;">Academic Sections</div>

        <?php
        // ক্লাসের লিস্ট ফেচ করা
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

                $sdata = $summary_map[$key] ?? null;
                $rate = $sdata["attndrate"] ?? 0;
                $fnd  = $sdata["attndstudent"] ?? 0;
                $cnt  = $sdata["totalstudent"] ?? 0;
                $bunk = $sdata["bunk"] ?? 0;
                
                $bunk_rate = ($cnt > 0) ? ceil($bunk * 100 / $cnt) : 0;
                $effective_rate = max(0, $rate - $bunk_rate);

                // কালার লজিক
                $status_class = ($effective_rate >= 85) ? 'c-fina' : (($effective_rate >= 60) ? 'c-info' : 'c-exit');
        ?>

        <div class="m3-list-item" onclick="go('<?php echo $lnk; ?>')" style="flex-direction: column; align-items: stretch; padding: 16px; margin-bottom: 12px; border: 1px solid rgba(0,0,0,0.04);">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div style="display: flex; align-items: center;">
                    <div class="icon-box <?php echo $status_class; ?>" style="width: 46px; height: 46px; font-weight: 900; font-size: 1.2rem;">
                        <?php echo substr($cls, 0, 1); ?>
                    </div>
                    <div style="margin-left: 14px;">
                        <div class="st-title" style="font-size: 1.05rem; letter-spacing: -0.3px;">
                            <?php echo strtoupper($cls); ?> <span style="color: var(--m3-outline); font-weight: 300; margin: 0 4px;">|</span> <?php echo $sec; ?>
                        </div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #666; display: flex; align-items: center; gap: 5px;">
                            <i class="bi bi-people-fill" style="color: var(--m3-primary);"></i>
                            <?php echo $fnd; ?> <span style="font-weight: 400; opacity: 0.7;">Present out of</span> <?php echo $cnt; ?>
                        </div>
                    </div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 1.8rem; font-weight: 900; color: #1C1B1F; line-height: 1;">
                        <?php echo number_format($effective_rate, 0); ?><span style="font-size: 0.9rem; font-weight: 700;">%</span>
                    </div>
                    <?php if($bunk > 0): ?>
                        <div style="font-size: 0.65rem; color: #B3261E; font-weight: 900; margin-top: 2px;">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $bunk; ?> BUNK
                        </div>
                    <?php else: ?>
                        <div style="font-size: 0.6rem; color: #4CAF50; font-weight: 900; text-transform: uppercase;">Stable</div>
                    <?php endif; ?>
                </div>
            </div>

            <div style="height: 8px; background: #EADDFF; border-radius: 100px; overflow: hidden; display: flex; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);">
                <div style="width: <?php echo $effective_rate; ?>%; background: var(--m3-primary); transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);"></div>
                <div style="width: <?php echo $bunk_rate; ?>%; background: #FFB900; transition: width 0.8s ease;"></div>
            </div>

            <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                <span style="font-size: 0.65rem; font-weight: 800; color: var(--m3-primary); opacity: 0.8;">ATTENDANCE RATE</span>
                <?php if($bunk_rate > 0): ?>
                    <span style="font-size: 0.65rem; font-weight: 800; color: #E65100;">CLASS BUNK: <?php echo $bunk_rate; ?>%</span>
                <?php endif; ?>
            </div>
        </div>

        <?php 
            endwhile; 
        else:
            echo '<div style="text-align:center; padding: 60px 20px; opacity:0.4;">
                    <i class="bi bi-clipboard-x" style="font-size: 3.5rem;"></i>
                    <div style="font-weight:700; margin-top:10px;">No Attendance Records Found</div>
                  </div>';
        endif; 
        $stmt_areas->close();
        ?>
    </div>
</main>

<div style="height: 80px;"></div>
<?php include 'footer.php'; ?>
<script>
    function go(params) {
        window.location.href = "st-attnd-register.php?" + params;
    }
</script>

