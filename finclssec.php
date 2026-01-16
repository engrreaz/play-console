<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. পেজ টাইটেল সেট করা (স্ট্যান্ডার্ড হেডার ব্যবহারের জন্য)
$page_title = "Class-wise Dues";
$sy_param = "%$sy%";
$current_month = date('m');
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* M3 Tonal Card Style */
    .m3-tonal-card {
        background-color: #FFFFFF;
        border-radius: 24px;
        padding: 20px;
        margin: 0 16px 12px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s, background-color 0.2s;
        cursor: pointer;
    }
    .m3-tonal-card:active {
        transform: scale(0.97);
        background-color: #F3EDF7;
    }

    /* Progress Bar Style */
    .m3-progress-container {
        height: 6px;
        background-color: #EADDFF;
        border-radius: 100px;
        overflow: hidden;
        margin: 12px 0;
    }
    .m3-progress-bar {
        height: 100%;
        background-color: #6750A4;
        border-radius: 100px;
        transition: width 0.4s ease;
    }

    .today-amt { font-size: 1.6rem; font-weight: 800; color: #1C1B1F; line-height: 1; }
    .label-small { font-size: 0.65rem; font-weight: 700; color: #49454F; text-transform: uppercase; letter-spacing: 0.5px; }
    .class-badge { background: #EADDFF; color: #21005D; padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; }
    
    .stats-row { display: flex; justify-content: space-between; align-items: flex-end; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons"><i class="bi bi-funnel"></i></div>
</header>

<main class="pb-5 mt-3">
    <div class="container-fluid p-0">
        <?php
        // ২. ক্লাস লিস্ট ফেচ করা (Prepared Statement)
        $stmt_areas = $conn->prepare("SELECT areaname, subarea FROM areas WHERE sessionyear LIKE ? AND user = ? ORDER BY FIELD(areaname,'Six', 'Seven', 'Eight', 'Nine', 'Ten'), idno, subarea");
        $stmt_areas->bind_param("ss", $sy_param, $rootuser);
        $stmt_areas->execute();
        $res_areas = $stmt_areas->get_result();

        while ($row = $res_areas->fetch_assoc()) {
            $cls = $row["areaname"];
            $sec = $row["subarea"];
            $lnk = "cls=" . urlencode($cls) . '&sec=' . urlencode($sec);

            // ৩. ফিন্যান্সিয়াল ডাটা ফেচ করা (Prepared Statement)
            $stmt_fin = $conn->prepare("SELECT SUM(dues), SUM(paid) FROM stfinance WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND month <= ?");
            $stmt_fin->bind_param("sssss", $sy_param, $sccode, $cls, $sec, $current_month);
            $stmt_fin->execute();
            $stmt_fin->bind_result($total_dues, $total_paid);
            $stmt_fin->fetch();
            $stmt_fin->close();


            // ৪. আজকের কালেকশন ফেচ করা (Prepared Statement)
            $stmt_today = $conn->prepare("SELECT SUM(amount) FROM stpr WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND prdate = ?");
            $stmt_today->bind_param("sssss", $sy_param, $sccode, $cls, $sec, $td);
            $stmt_today->execute();
            $stmt_today->bind_result($today_coll);
            $today_coll = $today_coll ?? 0;
            $stmt_today->fetch();
            $stmt_today->close();

            // ক্যালকুলেশন
            $total_paid = floatval($total_paid);
            $total_dues = floatval($total_dues);
            $total_receivable = ($total_dues ?? 0) + ($total_paid ?? 0);
            $rate = ($total_receivable > 0) ? ceil(($total_paid * 100) / $total_receivable) : 0.00;
        ?>
            <div class="m3-tonal-card shadow-sm" onclick="go('<?php echo $lnk; ?>')">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="label-small">Today's Collection</span>
                        <div class="today-amt">৳ <?php echo number_format($today_coll, 0); ?></div>
                    </div>
                    <div class="class-badge shadow-sm"><?php echo $cls . " - " . $sec; ?></div>
                </div>

                <div class="m3-progress-container">
                    <div class="m3-progress-bar" style="width: <?php echo $rate; ?>%;"></div>
                </div>

                <div class="stats-row">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted fw-bold" style="font-size: 0.7rem;">COLLECTION RATE</span>
                            <span class="text-primary fw-bold" style="font-size: 0.7rem;"><?php echo $rate; ?>%</span>
                        </div>
                        <div style="font-size: 0.75rem; line-height: 1.4; color: #49454F;">
                            Collected: <span class="fw-bold text-dark">৳<?php echo number_format($total_paid, 0); ?></span><br>
                            Current Dues: <span class="fw-bold text-danger">৳<?php echo number_format($total_dues, 0); ?></span>
                        </div>
                    </div>
                    <div class="ms-3">
                        <i class="bi bi-chevron-right text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
        <?php } $stmt_areas->close(); ?>
    </div>
</main>

<div style="height: 60px;"></div>



<script>
    function go(params) {
        window.location.href = "finstudents.php?" + params;
    }
</script>

<?php include 'footer.php'; ?>