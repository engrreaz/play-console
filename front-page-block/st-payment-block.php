<?php
// ফাইল: front-page-block/st-payment-block.php

$is_collection_user = false;
$fin_summary = ['dues' => 0, 'payable' => 0, 'paid' => 0, 'percent' => 0];

if (isset($ins_all_settings, $userlevel, $conn, $sccode, $sy)) {
    // ১. পারমিশন চেক - রিফ্যাক্টরড লজিক
    $collection_keys = array_column($ins_all_settings, 'settings_value', 'setting_title');
    if (isset($collection_keys['Collection']) && strpos($collection_keys['Collection'], $userlevel) !== false) {
        $is_collection_user = true;
    }

    if ($is_collection_user) {
        $current_month = (int)date('m');
        $sy_param = "%$sy%";

        // ২. অপ্টিমাইজড কোয়েরি - ফাস্ট এক্সিকিউশন
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

<div class="m-card elevation-1 border-0 mb-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h6 class="fw-bold mb-1 text-secondary small text-uppercase">Collection Status</h6>
            <h4 class="fw-bold text-dark"><?php echo $fin_summary['percent']; ?>% Collected</h4>
        </div>
        <div class="rounded-circle bg-success-subtle p-2">
            <i class="bi bi-wallet2 text-success fs-4"></i>
        </div>
    </div>

    <div class="progress rounded-pill mb-4" style="height: 12px; background-color: var(--md-surface-variant);">
        <div class="progress-bar bg-success rounded-pill progress-bar-striped progress-bar-animated" 
             role="progressbar" 
             style="width: <?php echo $fin_summary['percent']; ?>%"></div>
    </div>

    <div class="row g-2 text-center mb-3">
        <div class="col-4">
            <div class="p-2 rounded-3 bg-light">
                <div class="text-muted" style="font-size: 0.65rem;">PAID</div>
                <div class="fw-bold text-success small"><?php echo number_format($fin_summary['paid']); ?></div>
            </div>
        </div>
        <div class="col-4">
            <div class="p-2 rounded-3 bg-light">
                <div class="text-muted" style="font-size: 0.65rem;">PAYABLE</div>
                <div class="fw-bold text-dark small"><?php echo number_format($fin_summary['payable']); ?></div>
            </div>
        </div>
        <div class="col-4">
            <div class="p-2 rounded-3 bg-danger-subtle">
                <div class="text-danger-emphasis fw-bold" style="font-size: 0.65rem;">DUES</div>
                <div class="fw-bold text-danger small"><?php echo number_format($fin_summary['dues']); ?></div>
            </div>
        </div>
    </div>

    <div class="d-grid">
        <a href="finclssec.php" class="btn btn-primary rounded-pill py-2 shadow-sm fw-medium">
            <i class="bi bi-graph-up-arrow me-2"></i> Detailed Report
        </a>
    </div>
</div>

<?php endif; ?>