<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// --- ১. ফিল্টার হ্যান্ডলিং (Secure) ---
$report_date = $_GET['report_date'] ?? date('Y-m-d');
$classname = $_GET['cls'] ?? ($cteacher_data[0]['cteachercls'] ?? '');
$sectionname = $_GET['sec'] ?? ($cteacher_data[0]['cteachersec'] ?? '');

// --- ২. ডাটা ফেচিং (Prepared Statement - Secure) ---
$absent_list = [];
$absent_count = 0;
$bunk_count = 0;

$sql = "SELECT si.stid, si.rollno, sa.yn AS present_status, sa.bunk
        FROM sessioninfo si
        LEFT JOIN stattnd sa ON si.stid = sa.stid AND sa.adate = ? AND sa.sccode = ?
        WHERE si.sessionyear = ?
          AND si.sccode = ?
          AND si.classname = ?
          AND si.sectionname = ?
          AND si.status = '1'
          AND (sa.yn = '0' OR sa.bunk = '1' OR sa.stid IS NULL)
        ORDER BY si.rollno";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $report_date, $sccode, $sy, $sccode, $classname, $sectionname);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $absent_list[] = $row;
    if ($row['bunk'] == '1') {
        $bunk_count++;
    } else {
        $absent_count++;
    }
}
$stmt->close();
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Top App Bar */
    .m3-app-bar {
        background-color: #FFFFFF;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    /* Hero Summary Card */
    .hero-stats {
        background: #F3EDF7;
        border-radius: 28px;
        padding: 24px;
        margin: 16px;
        display: flex;
        justify-content: space-around;
        text-align: center;
    }
    .stat-val { font-size: 1.8rem; font-weight: 800; line-height: 1; }
    .stat-lbl { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-top: 5px; opacity: 0.8; }

    /* Filter Form Styling */
    .filter-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        margin: 0 16px 24px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .form-floating > .form-control, .form-floating > .form-select {
        border-radius: 12px;
        border: 1px solid #79747E;
    }

    /* M3 List Item (Student Card) */
    .student-card {
        background: white;
        border-radius: 20px;
        padding: 12px 16px;
        margin: 0 16px 10px;
        display: flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        border: none;
    }
    .student-pic {
        width: 52px; height: 52px;
        border-radius: 12px;
        object-fit: cover;
        margin-right: 15px;
        border: 1px solid #E7E0EC;
    }

    /* Status Badges (Chips) */
    .chip {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        text-transform: uppercase;
    }
    .chip-absent { background: #FFEBEE; color: #B3261E; }
    .chip-bunk { background: #FFF3E0; color: #E65100; }

    /* Action Buttons */
    .btn-action {
        width: 40px; height: 40px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-call { background: #E8F5E9; color: #2E7D32; }
    .btn-sms { background: #E3F2FD; color: #1976D2; }
    .btn-action:active { transform: scale(0.9); opacity: 0.8; }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="reporthome.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h5 class="fw-bold mb-0">Absent & Bunk List</h5>
                <small class="text-muted"><?php echo date('d M, Y', strtotime($report_date)); ?></small>
            </div>
        </div>
    </div>

    <div class="hero-stats shadow-sm">
        <div style="color: #B3261E;">
            <div class="stat-val"><?php echo $absent_count; ?></div>
            <div class="stat-lbl">Absent</div>
        </div>
        <div class="vr mx-3 opacity-25"></div>
        <div style="color: #E65100;">
            <div class="stat-val"><?php echo $bunk_count; ?></div>
            <div class="stat-lbl">Bunked</div>
        </div>
    </div>

    <div class="filter-card shadow-sm">
        <form method="GET" class="row g-2">
            <div class="col-12">
                <div class="form-floating mb-2">
                    <input type="date" name="report_date" class="form-control" id="dateInput" value="<?php echo $report_date; ?>">
                    <label for="dateInput">Report Date</label>
                </div>
            </div>
            <div class="col-6">
                <div class="form-floating mb-2">
                    <select name="cls" class="form-select" id="clsSelect">
                        <?php foreach ($cteacher_data as $c): ?>
                            <option value="<?php echo $c['cteachercls']; ?>" <?php echo ($c['cteachercls'] == $classname) ? 'selected' : ''; ?>><?php echo $c['cteachercls']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="clsSelect">Class</label>
                </div>
            </div>
            <div class="col-6">
                <div class="form-floating mb-2">
                    <select name="sec" class="form-select" id="secSelect">
                        <?php foreach ($cteacher_data as $c): ?>
                            <option value="<?php echo $c['cteachersec']; ?>" <?php echo ($c['cteachersec'] == $sectionname) ? 'selected' : ''; ?>><?php echo $c['cteachersec']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="secSelect">Section</label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                    <i class="bi bi-search me-2"></i> UPDATE LIST
                </button>
            </div>
        </form>
    </div>

    <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase tracking-wider">Defaulting Students</h6>

    <div class="list-container">
        <?php if (count($absent_list) > 0): ?>
            <?php foreach ($absent_list as $student): 
                $stid = $student['stid'];
                $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                if ($st_idx === false) continue;

                $p = $datam_st_profile[$st_idx];
                $photo = "https://eimbox.com/students/noimg.jpg";
                if (file_exists('../students/' . $stid . '.jpg')) {
                    $photo = $BASE_PATH_URL_FILE . 'students/' . $stid . '.jpg';
                }
            ?>
                <div class="student-card shadow-sm">
                    <img src="<?php echo $photo; ?>" class="student-pic shadow-sm" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                    
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold text-dark text-truncate small"><?php echo $p['stnameeng']; ?></div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge rounded-pill bg-light text-dark border small" style="font-size: 0.6rem;">Roll: <?php echo $student['rollno']; ?></span>
                            <?php if ($student['bunk'] == '1'): ?>
                                <span class="chip chip-bunk">Bunk</span>
                            <?php else: ?>
                                <span class="chip chip-absent">Absent</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2 ms-2">
                        <a href="tel:<?php echo $p['guarmobile']; ?>" class="btn-action btn-call shadow-sm">
                            <i class="bi bi-telephone-fill small"></i>
                        </a>
                        <a href="sms:<?php echo $p['guarmobile']; ?>" class="btn-action btn-sms shadow-sm">
                            <i class="bi bi-chat-left-text-fill small"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 mx-4 bg-white rounded-4 shadow-sm border border-success-subtle">
                <i class="bi bi-check-circle-fill display-4 text-success opacity-50"></i>
                <p class="text-success fw-bold mt-2 mb-0">Excellent!</p>
                <p class="text-muted small">No absences or bunks for this class today.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<div style="height: 70px;"></div>

<?php include 'footer.php'; ?>