<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;
$sy_param = '%' . $current_session . '%';

$page_title = "Class Financial Status";
$current_month = (int)date('m');
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width M3 App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Condensed M3 Card (8px Radius) */
    .m3-fin-card {
        background: #fff; border-radius: 8px; padding: 12px;
        margin: 0 12px 10px; border: 1px solid #f0f0f0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: transform 0.15s ease;
    }
    .m3-fin-card:active { transform: scale(0.98); background-color: #F7F2FA; }

    /* Tonal Badge for Class Name */
    .cls-badge {
        font-size: 0.65rem; font-weight: 800; background: #F3EDF7; color: #6750A4;
        padding: 4px 10px; border-radius: 6px; border: 1px solid #EADDFF;
        text-transform: uppercase; letter-spacing: 0.5px;
    }

    /* Progress Bar (M3 Slim) */
    .m3-progress-thin { background: #E7E0EC; height: 6px; border-radius: 3px; overflow: hidden; }
    .m3-progress-bar-fill { background: #6750A4; height: 100%; transition: width 0.5s ease; }

    .amt-hero { font-size: 1.3rem; font-weight: 900; color: #1C1B1F; letter-spacing: -0.5px; }
    .lbl-tiny { font-size: 0.6rem; font-weight: 700; color: #79747E; text-transform: uppercase; }
    
    .stat-box { flex: 1; border-right: 1px solid #F3EDF7; padding: 0 8px; }
    .stat-box:last-child { border-right: none; }

    .session-indicator {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="session-indicator"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5 mt-2">
    <div class="px-3 mb-2 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Summary per Class</div>

    <div id="finance-list-container">
        <?php
        // ২. ক্লাস লিস্ট ফেচ করা (Prepared Statement)
        $stmt_areas = $conn->prepare("SELECT areaname, subarea FROM areas WHERE sessionyear LIKE ? AND user = ? ORDER BY idno ASC, subarea ASC");
        $stmt_areas->bind_param("ss", $sy_param, $rootuser);
        $stmt_areas->execute();
        $res_areas = $stmt_areas->get_result();

        if ($res_areas->num_rows > 0):
            while ($row = $res_areas->fetch_assoc()):
                $cls = $row["areaname"];
                $sec = $row["subarea"];
                $params = "cls=" . urlencode($cls) . '&sec=' . urlencode($sec) . '&year=' . $current_session;

                // ৩. ফিন্যান্সিয়াল ডাটা (Paid vs Dues)
                $stmt_fin = $conn->prepare("SELECT SUM(dues), SUM(paid) FROM stfinance WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND month <= ?");
                $stmt_fin->bind_param("ssssi", $sy_param, $sccode, $cls, $sec, $current_month);
                $stmt_fin->execute();
                $stmt_fin->bind_result($total_dues, $total_paid);
                $stmt_fin->fetch();
                $stmt_fin->close();

                // ৪. আজকের কালেকশন
                $stmt_today = $conn->prepare("SELECT SUM(amount) FROM stpr WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND prdate = ?");
                $stmt_today->bind_param("sssss", $sy_param, $sccode, $cls, $sec, $td);
                $stmt_today->execute();
                $stmt_today->bind_result($today_coll);
                $today_coll = $today_coll ?? 0;
                $stmt_today->fetch();
                $stmt_today->close();

                // ক্যালকুলেশন
                $total_paid = (float)$total_paid;
                $total_dues = (float)$total_dues;
                $total_receivable = $total_dues + $total_paid;
                $rate = ($total_receivable > 0) ? ceil(($total_paid * 100) / $total_receivable) : 0;
        ?>
            <div class="m3-fin-card shadow-sm" onclick="window.location.href='finstudents.php?<?php echo $params; ?>'">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="lbl-tiny">Today's Collection</div>
                        <div class="amt-hero">৳<?php echo number_format($today_coll, 0); ?></div>
                    </div>
                    <span class="cls-badge shadow-sm"><?php echo $cls . " | " . $sec; ?></span>
                </div>

                <div class="m3-progress-thin mb-3 shadow-sm">
                    <div class="m3-progress-bar-fill" style="width: <?php echo $rate; ?>%;"></div>
                </div>

                <div class="d-flex text-center mt-2">
                    <div class="stat-box">
                        <div class="lbl-tiny">Collected</div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">৳<?php echo number_format($total_paid, 0); ?></div>
                    </div>
                    <div class="stat-box">
                        <div class="lbl-tiny text-danger">Total Dues</div>
                        <div class="fw-bold text-danger" style="font-size: 0.85rem;">৳<?php echo number_format($total_dues, 0); ?></div>
                    </div>
                    <div class="stat-box" style="border:none;">
                        <div class="lbl-tiny text-primary">Progress</div>
                        <div class="fw-bold text-primary" style="font-size: 0.85rem;"><?php echo $rate; ?>%</div>
                    </div>
                </div>
            </div>
        <?php 
            endwhile;
        else:
            echo '<div class="text-center py-5 opacity-25"><i class="bi bi-wallet2 display-1"></i><p class="fw-bold mt-2">No data for '.$current_session.'</p></div>';
        endif; 
        $stmt_areas->close();
        ?>
    </div>
</main>

<div style="height: 75px;"></div> <?php include 'footer.php'; ?>