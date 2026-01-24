<?php
$page_title = "Attendance Report";
include 'inc.php'; // এটি header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

$stid = $_GET['stid'] ?? 0;

// ২. স্টুডেন্ট এবং সেশন ইনফো ফেচ করা (Prepared Statement)
$std_data = [];
$stmt = $conn->prepare("SELECT s.*, si.classname, si.sectionname, si.rollno 
                        FROM students s 
                        JOIN sessioninfo si ON s.stid = si.stid 
                        WHERE s.stid = ? AND si.sessionyear LIKE ? LIMIT 1");
$stmt->bind_param("ss", $stid, $sy_param);
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

// ৩. উপস্থিতির ডাটা এবং সামারি ফেচ করা
$att_data_list = [];
$present_count = $absent_count = $bunk_count = 0;

$stmt_att = $conn->prepare("SELECT adate, yn, bunk FROM stattnd WHERE stid = ? AND sessionyear LIKE ? ORDER BY adate DESC");
$stmt_att->bind_param("ss", $stid, $sy_param);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
while($row = $res_att->fetch_assoc()){
    $att_data_list[] = $row;
    if($row['yn'] == 1) {
        $present_count++;
        if($row['bunk'] == '1') $bunk_count++;
    } else {
        $absent_count++;
    }
}
$stmt_att->close();

// প্রোফাইল পিকচার পাথ
$photo_path = student_profile_image_path($stid); ;
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* Profile Hero Section (Large Photo Focus) */
    .profile-header {
        background: #fff;
        padding: 30px 20px;
        text-align: center;
        border-radius: 0 0 8px 8px; /* আপনার নির্দেশিত ৮ পিক্সেল */
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 12px;
    }
    
    .large-avatar-frame {
        width: 110px; height: 130px; /* ছবি বড় করা হয়েছে */
        border-radius: 8px; /* M3 style rounded square */
        border: 4px solid #F3EDF7;
        margin: 0 auto 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.15);
    }
    .large-avatar-frame img { width: 100%; height: 100%; object-fit: cover; }

    .st-name { font-size: 1.2rem; font-weight: 800; color: #1C1B1F; margin-bottom: 4px; }
    .st-meta { font-size: 0.75rem; font-weight: 700; color: #6750A4; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Stats Dashboard (8px Radius) */
    .stats-row { display: flex; gap: 8px; padding: 0 12px; margin-bottom: 20px; }
    .stat-chip {
        flex: 1; background: #fff; border-radius: 8px; padding: 12px 8px;
        text-align: center; border: 1px solid #f0f0f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .stat-val { font-size: 1.2rem; font-weight: 800; display: block; line-height: 1; }
    .stat-lbl { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; margin-top: 4px; color: #49454F; }

    /* History List (8px Radius) */
    .history-card {
        background: #fff; border-radius: 8px; padding: 12px;
        margin: 0 8px 8px; display: flex; align-items: center;
        border: 1px solid #eee; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    
    .status-icon {
        width: 42px; height: 42px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 12px; font-size: 1.2rem; flex-shrink: 0;
    }
    .c-present { background: #E8F5E9; color: #2E7D32; }
    .c-absent { background: #FFEBEE; color: #D32F2F; }
    .c-bunk { background: #FFF3E0; color: #e98752; }

    .hist-date { font-weight: 700; color: #1C1B1F; font-size: 0.85rem; }
    .hist-desc { font-size: 0.7rem; color: #79747E; font-weight: 500; }
</style>



<main class="pb-5">
    <div class="profile-header shadow-sm">
        <div class="large-avatar-frame">
            <img src="<?php echo $photo_path; ?>" onerror="this.src='https://eimbox.com/students/noimg.jpg';">
        </div>
        <div class="st-name"><?php echo $stnameeng; ?></div>
        <div class="st-meta">
            Class <?php echo $cls; ?> <i class="bi bi-dot"></i> Section <?php echo $sec; ?> <i class="bi bi-dot"></i> Roll <?php echo $roll; ?>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-chip">
            <span class="stat-val text-success"><?php echo $present_count; ?></span>
            <span class="stat-lbl">Present</span>
        </div>
        <div class="stat-chip">
            <span class="stat-val text-danger"><?php echo $absent_count; ?></span>
            <span class="stat-lbl">Absent</span>
        </div>
        <div class="stat-chip">
            <span class="stat-val text-warning"><?php echo $bunk_count; ?></span>
            <span class="stat-lbl">Bunk</span>
        </div>
    </div>

    <div class="px-3 mb-2 d-flex justify-content-between align-items-center">
        <span class="fw-bold text-muted small text-uppercase" style="letter-spacing: 1px;">Attendance Log</span>
        <i class="bi bi-filter-right fs-5 text-primary"></i>
    </div>

    <div class="px-1">
        <?php if(!empty($att_data_list)): ?>
            <?php foreach($att_data_list as $att): 
                $is_p = ($att['yn'] == 1);
                $is_l = ($att['bunk'] == '1');
                
                $cls_tag = $is_p ? ($is_l ? 'c-bunk' : 'c-present') : 'c-absent';
                $icon_tag = $is_p ? ($is_l ? 'bi-check-circle-fill' : 'bi-check-circle-fill') : 'bi-x-circle-fill';
                $status_txt = $is_p ? ($is_l ? 'Present (Bunk)' : 'Present') : 'Absent from Institute';
            ?>
                <div class="history-card shadow-sm">
                    <div class="status-icon <?php echo $cls_tag; ?>">
                        <i class="bi <?php echo $icon_tag; ?>"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="hist-date"><?php echo date('d M, Y', strtotime($att['adate'])); ?></div>
                        <div class="hist-desc"><?php echo date('l', strtotime($att['adate'])); ?> <i class="bi bi-dot"></i> <?php echo $status_txt; ?></div>
                    </div>
                    <i class="bi bi-chevron-right text-muted opacity-25"></i>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-calendar-x display-1"></i>
                <p class="mt-2 fw-bold">Records unavailable.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<div style="height: 65px;"></div> <?php include 'footer.php'; ?>