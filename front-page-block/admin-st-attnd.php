<?php
// ফাইল: front-page-block/admin-st-attnd.php

// ১. সেশন হ্যান্ডলিং (ড্যাশবোর্ড থেকে পাস করা না থাকলে ব্যাকআপ)
$current_session = $current_session ?? $sy;

$admin_summary = ['total' => 0, 'present' => 0, 'rate' => 0];
$has_areas = false;

if (isset($conn, $sccode, $sy, $td, $rootuser)) {
    $sy_like = "%$current_session%";

    // অপ্টিমাইজড কোয়েরি: JOIN ব্যবহার করে সরাসরি সামারি ক্যালকুলেশন
    $sql = "SELECT SUM(s.totalstudent) as total_st, SUM(s.attndstudent) as present_st 
            FROM areas a
            INNER JOIN stattndsummery s ON a.areaname = s.classname AND a.subarea = s.sectionname
            WHERE a.sessionyear LIKE ? AND a.user = ? AND s.sccode = ? AND s.date = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $sy_like, $rootuser, $sccode, $td);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();

    if ($data && $data['total_st'] > 0) {
        $has_areas = true;
        $admin_summary['total'] = (int)$data['total_st'];
        $admin_summary['present'] = (int)$data['present_st'];
        $admin_summary['rate'] = round(($admin_summary['present'] * 100) / $admin_summary['total']);
    }
    $stmt->close();
}

if ($has_areas):
?>

<style>
    .st-att-widget { background: #fff; border-radius: 8px; padding: 12px; }
    
    .lbl-header { font-size: 0.65rem; font-weight: 800; color: #006493; text-transform: uppercase; letter-spacing: 0.8px; }
    
    .st-metric-val { font-size: 1.6rem; font-weight: 800; color: #1C1B1F; line-height: 1.1; }
    .st-metric-sub { font-size: 0.7rem; font-weight: 600; color: #49454F; }
    
    .m3-progress-st { background: #D1E5F4; height: 8px; border-radius: 4px; overflow: hidden; }
    .m3-progress-bar-st { background: #006493; height: 100%; transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1); }

    .btn-m3-tonal-info {
        background-color: #D1E5F4; color: #001E2E; border-radius: 8px;
        padding: 8px; font-size: 0.7rem; font-weight: 800; border: none;
        text-decoration: none !important; display: block; text-align: center;
        transition: 0.2s; width: 100%; text-transform: uppercase;
    }
    .btn-m3-tonal-info:active { background-color: #B2D5EE; transform: scale(0.98); }
</style>

<div class="st-att-widget shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="lbl-header"><i class="bi bi-person-check-fill me-1"></i> Student Attendance</span>
        <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-2" style="font-size: 0.6rem;">LIVE</span>
    </div>

    <div class="d-flex align-items-end justify-content-between mb-2">
        <div>
            <span class="st-metric-val"><?php echo number_format($admin_summary['present']); ?></span>
            <span class="st-metric-sub">/ <?php echo number_format($admin_summary['total']); ?></span>
        </div>
        <div class="text-info fw-bold" style="font-size: 1rem;"><?php echo $admin_summary['rate']; ?>%</div>
    </div>

    <div class="m3-progress-st mb-3">
        <div class="m3-progress-bar-st" style="width: <?php echo $admin_summary['rate']; ?>%"></div>
    </div>

    <div class="pt-1">
        <a href="st-attnd-register.php?year=<?php echo $current_session; ?>" class="btn-m3-tonal-info shadow-sm">
            <i class="bi bi-journal-text me-1"></i> View Full Register
        </a>
    </div>
</div>

<?php endif; ?>