<?php
include 'inc.php'; // এটি header.php এবং DB কানেকশন লোড করবে

$stid = $_GET['stid'] ?? 0;

// ১. স্টুডেন্ট এবং সেশন ইনফো ফেচ করা (Prepared Statement)
$std_data = [];
$stmt = $conn->prepare("SELECT s.*, si.classname, si.sectionname, si.rollno 
                        FROM students s 
                        JOIN sessioninfo si ON s.stid = si.stid 
                        WHERE s.stid = ? AND si.sessionyear = ? LIMIT 1");
$stmt->bind_param("ss", $stid, $sy);
$stmt->execute();
$res = $stmt->get_result();
if($row = $res->fetch_assoc()) {
    $std_data = $row;
}
$stmt->close();

$stnameeng = $std_data['stnameeng'] ?? 'N/A';
$cls = $std_data['classname'] ?? '';
$sec = $std_data['sectionname'] ?? '';
$roll = $std_data['rollno'] ?? '';
$stdid = $std_data['stid'] ?? $stid;

// ২. উপস্থিতির ডাটা এবং সামারি ফেচ করা
$att_data_list = [];
$present_count = $absent_count = $late_count = 0;

$stmt_att = $conn->prepare("SELECT adate, yn, statusin FROM stattnd WHERE stid = ? AND sessionyear LIKE ? ORDER BY adate DESC");
$sy_param = "%$sy%";
$stmt_att->bind_param("ss", $stid, $sy_param);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
while($row = $res_att->fetch_assoc()){
    $att_data_list[] = $row;
    if($row['yn'] == 1) {
        $present_count++;
        if($row['statusin'] == 'Late') $late_count++;
    } else {
        $absent_count++;
    }
}
$stmt_att->close();

// প্রোফাইল পিকচার পাথ
$photo_path = "https://eimbox.com/students/" . $stdid . ".jpg";
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Profile Hero Section */
    .profile-hero {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 0 0 32px 32px;
        padding: 30px 20px 40px;
        color: white;
        text-align: center;
        margin-bottom: 20px;
    }
    .avatar-frame {
        width: 80px; height: 80px;
        border-radius: 50%;
        border: 3px solid white;
        margin: 0 auto 12px;
        overflow: hidden;
    }
    .avatar-frame img { width: 100%; height: 100%; object-fit: cover; }

    /* Stats Dashboard */
    .stats-card {
        background: #F3EDF7;
        border-radius: 28px;
        padding: 20px;
        margin: 0 16px 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .stat-box { text-align: center; padding: 10px; border-radius: 16px; background: #fff; }
    .stat-val { font-size: 1.3rem; font-weight: 800; color: #1C1B1F; line-height: 1; }
    .stat-lbl { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; margin-top: 4px; opacity: 0.7; }

    /* Attendance List Rows */
    .att-row {
        background: white;
        border-radius: 20px;
        padding: 14px 16px;
        margin: 0 16px 10px;
        display: flex;
        align-items: center;
        border: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    
    .status-chip {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 15px; font-size: 1.2rem;
    }
    .bg-present { background-color: #E8F5E9; color: #2E7D32; }
    .bg-absent { background-color: #FFEBEE; color: #D32F2F; }
    .bg-late { background-color: #FFF3E0; color: #E65100; }

    .date-main { font-weight: 700; color: #1C1B1F; font-size: 0.95rem; }
    .date-sub { font-size: 0.75rem; color: #49454F; }
</style>

<main class="pb-5">
    <div class="profile-hero shadow">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="index.php" class="text-white"><i class="bi bi-arrow-left fs-4"></i></a>
            <h6 class="fw-bold mb-0">Attendance Report</h6>
            <div style="width: 24px;"></div>
        </div>

        <div class="avatar-frame shadow-sm">
            <img src="<?php echo $photo_path; ?>" onerror="this.src='https://eimbox.com/students/noimg.jpg';">
        </div>
        
        <h5 class="fw-bold mb-1"><?php echo $stnameeng; ?></h5>
        <div class="d-flex justify-content-center gap-2 mt-2">
            <span class="badge rounded-pill bg-white text-primary px-3">Class: <?php echo $cls; ?></span>
            <span class="badge rounded-pill bg-white text-primary px-3">Roll: <?php echo $roll; ?></span>
        </div>
    </div>

    <div class="stats-card">
        <div class="row g-2">
            <div class="col-4">
                <div class="stat-box shadow-sm" style="color: #2E7D32;">
                    <div class="stat-val"><?php echo $present_count; ?></div>
                    <div class="stat-label">Present</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-box shadow-sm" style="color: #D32F2F;">
                    <div class="stat-val"><?php echo $absent_count; ?></div>
                    <div class="stat-label">Absent</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-box shadow-sm" style="color: #E65100;">
                    <div class="stat-val"><?php echo $late_count; ?></div>
                    <div class="stat-label">Late</div>
                </div>
            </div>
        </div>
    </div>

    <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase tracking-wider">Attendance History</h6>

    <div class="px-1">
        <?php if(!empty($att_data_list)): ?>
            <?php foreach($att_data_list as $att): 
                $is_present = ($att['yn'] == 1);
                $is_late = ($att['statusin'] == 'Late');
                
                $row_class = $is_present ? ($is_late ? 'bg-late' : 'bg-present') : 'bg-absent';
                $row_icon = $is_present ? ($is_late ? 'bi-clock-history' : 'bi-check2-circle') : 'bi-x-circle';
                $status_text = $is_present ? ($is_late ? 'Present (Late Entry)' : 'Present') : 'Absent';
            ?>
                <div class="att-row shadow-sm">
                    <div class="status-chip <?php echo $row_class; ?>">
                        <i class="bi <?php echo $row_icon; ?>"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="date-main"><?php echo date('d F, Y', strtotime($att['adate'])); ?></div>
                        <div class="date-sub"><?php echo date('l', strtotime($att['adate'])); ?> <i class="bi bi-dot"></i> <?php echo $status_text; ?></div>
                    </div>
                    <i class="bi bi-chevron-right text-muted opacity-25"></i>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-calendar-x display-1"></i>
                <p class="mt-2 fw-bold">No attendance records found.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<div style="height: 60px;"></div>

<?php include 'footer.php'; ?>