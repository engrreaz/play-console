<?php
$page_title = "Attendance Status";
include 'inc.php';

// ১. সেটিংস থেকে ক্লাসের তালিকা এবং ডাইনামিক সর্টিং অর্ডার বের করা
$class_order_raw = "";
foreach ($ins_all_settings as $setting) {
    if ($setting['setting_title'] === 'Classes') {
        $class_order_raw = $setting['settings_value'];
        break;
    }
}
$class_order_arr = explode(',', $class_order_raw);
$sql_field_order = "'" . implode("','", $class_order_arr) . "'";

// ২. আজকের হাজিরার সামারি ডাটা ফেচ করা (stattndsummery)
$summary_map = [];
$sy_param = "%" . $sy . "%";
$stmt_sum = $conn->prepare("SELECT * FROM stattndsummery WHERE sccode = ? AND date = ?");
$stmt_sum->bind_param("is", $sccode, $td);
$stmt_sum->execute();
$res_sum = $stmt_sum->get_result();
while ($row = $res_sum->fetch_assoc()) {
    $key = $row['classname'] . '|' . $row['sectionname'];
    $summary_map[$key] = $row;
}
$stmt_sum->close();
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-surface: #FEF7FF;
        --m3-outline: #79747E;
    }

    body {
        background-color: var(--m3-surface);
    }

    .hero-attendance {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        margin: 12px;
        padding: 24px 20px;
        border-radius: 16px;
        color: white;
    }

    .attnd-card {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        margin: 0 12px 12px;
        border: 1px solid #E7E0EC;
        transition: 0.2s;
        cursor: pointer;
    }

    .attnd-card:active {
        transform: scale(0.98);
        background: #F3EDF7;
    }

    .icon-alpha {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #F3EDF7;
        color: #6750A4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.2rem;
    }

    /* প্রোগ্রেস বার কালার */
    .progress-main {
        background: #6750A4;
    }

    /* উপস্থিত */
    .progress-bunk {
        background: #FFB900;
    }

    /* বাংকিং */
    .progress-track {
        background: #EADDFF;
        height: 8px;
        border-radius: 100px;
        overflow: hidden;
        display: flex;
    }
</style>

<main class="pb-5">
    <div class="hero-attendance shadow-sm">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="tonal-icon-btn"
                style="background: rgba(255,255,255,0.2); color: #fff; border-radius: 12px; padding: 10px;">
                <i class="bi bi-calendar-check-fill fs-4"></i>
            </div>
            <div>
                <div style="font-size: 1.3rem; font-weight: 900; line-height: 1.1;">Attendance Summary</div>
                <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 600; margin-top: 4px;">
                    <i class="bi bi-clock-history me-1"></i> <?php echo date('d F, Y', strtotime($td)); ?>
                </div>
            </div>
        </div>

        <div style="margin-top: 22px; display: flex; gap: 8px;">
            <span class="session-pill"
                style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); color:white; padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; font-weight: 700;">
                SESSION <?php echo $sessionyear; ?>
            </span>
            <span class="session-pillx"
                style="background: rgba(255,255,255,0.25); border: 1px solid rgba(255,255,255,0.3); color:white; padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; font-weight: 700;">
                TODAY
            </span>
        </div>
    </div>

    <div class="list-container mt-3">
        <div class="m3-section-title"
            style="padding: 0 16px 8px; font-weight: 900; color: var(--m3-primary); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">
            Class-wise Statistics
        </div>

        <?php
        // ৩. সেটিংসের ক্রম অনুযায়ী এরিয়া/সেকশন ফেচ করা
        $stmt_areas = $conn->prepare("SELECT areaname, subarea FROM areas 
                                      WHERE sessionyear LIKE ? AND user = ? 
                                      ORDER BY FIELD(areaname, $sql_field_order), subarea, idno");
        $stmt_areas->bind_param("ss", $sessionyear_param, $rootuser);
        $stmt_areas->execute();
        $res_areas = $stmt_areas->get_result();

        if ($res_areas->num_rows > 0):
            while ($row = $res_areas->fetch_assoc()):
                $cls = $row["areaname"];
                $sec = $row["subarea"];
                $key = $cls . '|' . $sec;
                $lnk = "cls=" . urlencode($cls) . '&sec=' . urlencode($sec);

                // ডাটা প্রসেসিং
                $sdata = $summary_map[$key] ?? null;
                $rate = $sdata["attndrate"] ?? 0;
                $fnd = $sdata["attndstudent"] ?? 0;
                $cnt = $sdata["totalstudent"] ?? 0;
                $bunk = $sdata["bunk"] ?? 0;

                $bunk_rate = ($cnt > 0) ? ceil($bunk * 100 / $cnt) : 0;
                $effective_rate = max(0, $rate - $bunk_rate);

                // কালার ইনডিকেটর
                $status_color = ($effective_rate >= 85) ? '#2E7D32' : (($effective_rate >= 60) ? '#6750A4' : '#B3261E');
                ?>

                <div class="attnd-card shadow-sm" onclick="go('<?php echo $lnk; ?>')">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center;">
                            <div class="icon-alpha shadow-sm">
                                <?php echo strtoupper(substr($cls, 0, 1)); ?>
                            </div>
                            <div style="margin-left: 14px;">
                                <div style="font-size: 1.05rem; font-weight: 800; color: #1C1B1F;">
                                    <?php echo strtoupper($cls); ?> <span
                                        style="color: #CAC4D0; font-weight: 300; margin: 0 4px;">|</span> <?php echo $sec; ?>
                                </div>
                                <div
                                    style="font-size: 0.75rem; font-weight: 700; color: var(--m3-outline); display: flex; align-items: center; gap: 4px;">
                                    <i class="bi bi-people-fill text-primary"></i>
                                    <span class="text-dark"><?php echo $fnd; ?></span> / <?php echo $cnt; ?> Students
                                </div>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <div
                                style="font-size: 1.8rem; font-weight: 900; color: <?php echo $status_color; ?>; line-height: 1;">
                                <?php echo number_format($effective_rate, 0); ?><span style="font-size: 0.9rem;">%</span>
                            </div>
                            <?php if ($bunk > 0): ?>
                                <div
                                    style="font-size: 0.6rem; color: #E65100; font-weight: 900; text-transform: uppercase; margin-top: 2px;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $bunk; ?> Bunked
                                </div>
                            <?php else: ?>
                                <div style="font-size: 0.6rem; color: #4CAF50; font-weight: 900; text-transform: uppercase;">Stable
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="progress-track">
                        <div style="width: <?php echo $effective_rate; ?>%;" class="progress-main"></div>
                        <div style="width: <?php echo $bunk_rate; ?>%;" class="progress-bunk"></div>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                        <span style="font-size: 0.6rem; font-weight: 800; color: var(--m3-primary); opacity: 0.7;">PRESENCE
                            RATE</span>
                        <?php if ($bunk_rate > 0): ?>
                            <span style="font-size: 0.6rem; font-weight: 800; color: #E65100;">BUNK:
                                <?php echo $bunk_rate; ?>%</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
            endwhile;
        else:
            echo '<div style="text-align:center; padding: 80px 20px; color: var(--m3-outline); opacity:0.5;">
                    <i class="bi bi-clipboard-x" style="font-size: 4rem;"></i>
                    <div style="font-weight:800; margin-top:10px; text-transform:uppercase; font-size:0.7rem;">No Attendance Found</div>
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