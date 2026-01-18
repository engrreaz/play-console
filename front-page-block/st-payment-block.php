<?php
// ফাইল: front-page-block/st-payment-block.php

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;
$sy_param = "%" . $current_session . "%";

$is_collection_user = false;
$fin_summary = ['dues' => 0, 'payable' => 0, 'paid' => 0, 'percent' => 0];

if (isset($ins_all_settings, $userlevel, $conn, $sccode)) {
    // ২. পারমিশন চেক
    $settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
    if (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) {
        $is_collection_user = true;
    }

    if ($is_collection_user) {
        $current_month = (int)date('m');

        // ৩. অপ্টিমাইজড কোয়েরি - পিরিয়ড ভিত্তিক ক্যালকুলেশন
        $stmt = $conn->prepare("
            SELECT SUM(dues) as d, SUM(payableamt) as py, SUM(paid) as pd 
            FROM stfinance 
            WHERE sessionyear LIKE ? AND sccode = ? AND month <= ?
        ");
        $stmt->bind_param("ssi", $sy_param, $sccode, $current_month);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($row = $res->fetch_assoc()) {
            $fin_summary['dues'] = (float)($row['d'] ?? 0);
            $fin_summary['payable'] = (float)($row['py'] ?? 0);
            $fin_summary['paid'] = (float)($row['pd'] ?? 0);

            if ($fin_summary['payable'] > 0) {
                $fin_summary['percent'] = round(($fin_summary['paid'] * 100) / $fin_summary['payable']);
            }
        }
        $stmt->close();
    }
}

if ($is_collection_user):
?>

<style>
    .m3-payment-card { background: #fff; border-radius: 8px; padding: 12px; }
    
    .pay-lbl-header { font-size: 0.65rem; font-weight: 800; color: #1B5E20; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .pay-metric-val { font-size: 1.6rem; font-weight: 800; color: #1C1B1F; line-height: 1.1; }
    .pay-metric-sub { font-size: 0.75rem; font-weight: 700; color: #1B5E20; }
    
    .m3-progress-slim { background: #E8F5E9; height: 8px; border-radius: 4px; overflow: hidden; }
    .m3-progress-fill-success { 
        background: #2E7D32; height: 100%; transition: width 0.8s ease;
        background-image: linear-gradient(45deg,rgba(255,255,255,.1) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.1) 50%,rgba(255,255,255,.1) 75%,transparent 75%,transparent);
        background-size: 1rem 1rem;
    }

    .tonal-stat-box {
        background: #F1F8E9; border-radius: 8px; padding: 8px; 
        text-align: center; border: 1px solid #DCEDC8; flex: 1;
    }
    .stat-lbl-tiny { font-size: 0.55rem; font-weight: 800; color: #558B2F; text-transform: uppercase; }
    .stat-val-small { font-size: 0.85rem; font-weight: 800; color: #1B5E20; }

    .btn-m3-tonal-success {
        background: #C8E6C9; color: #1B5E20; border-radius: 8px;
        font-size: 0.7rem; font-weight: 800; padding: 10px;
        text-decoration: none !important; display: block; text-align: center;
        transition: 0.2s; border: none; width: 100%;
    }
    .btn-m3-tonal-success:active { transform: scale(0.98); background: #A5D6A7; }
</style>

<div class="m3-payment-card shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="pay-lbl-header"><i class="bi bi-currency-dollar me-1"></i> Institute Collection</span>
        <span class="badge bg-success-subtle text-success rounded-pill px-2" style="font-size: 0.6rem;">YEAR: <?php echo $current_session; ?></span>
    </div>

    <div class="d-flex align-items-end justify-content-between mb-2">
        <div>
            <span class="pay-metric-val"><?php echo $fin_summary['percent']; ?>%</span>
            <span class="pay-metric-sub ms-1">Received</span>
        </div>
        <div class="text-muted small fw-bold" style="font-size: 0.65rem;">Month Up-to: <?php echo date('F'); ?></div>
    </div>

    <div class="m3-progress-slim mb-3">
        <div class="m3-progress-fill-success" style="width: <?php echo $fin_summary['percent']; ?>%"></div>
    </div>

    <div class="d-flex gap-2 mb-3">
        <div class="tonal-stat-box">
            <div class="stat-lbl-tiny">Paid</div>
            <div class="stat-val-small"><?php echo number_format($fin_summary['paid'] / 1000, 1); ?>k</div>
        </div>
        <div class="tonal-stat-box" style="background: #fff; border-color: #eee;">
            <div class="stat-lbl-tiny" style="color: #666;">Payable</div>
            <div class="stat-val-small" style="color: #333;"><?php echo number_format($fin_summary['payable'] / 1000, 1); ?>k</div>
        </div>
        <div class="tonal-stat-box" style="background: #FFEBEE; border-color: #FFCDD2;">
            <div class="stat-lbl-tiny" style="color: #C62828;">Dues</div>
            <div class="stat-val-small" style="color: #B71C1C;"><?php echo number_format($fin_summary['dues'] / 1000, 1); ?>k</div>
        </div>
    </div>

    <a href="finclssec.php?year=<?php echo $current_session; ?>" class="btn-m3-tonal-success shadow-sm">
        <i class="bi bi-bar-chart-line-fill me-1"></i> FINANCIAL CONSOLE
    </a>
</div>

<?php endif; ?>