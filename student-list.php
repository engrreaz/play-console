<?php
session_start();
include_once 'inc.php'; // এটি inc.inc.php এবং header.php লোড করবে

$sy = '%' . $sy  . '%';
// --- Permission and Settings Fetching ---
if (isset($_GET['cls']) && isset($_GET['sec'])) {
    $classname = $_GET['cls'];
    $sectionname = $_GET['sec'];
    $cteacher_data[] = ['cteachercls' => $classname, 'cteachersec' => $sectionname];
}

$count_class = count($cteacher_data);
$collection_permission = 0;
$profile_entry_permission = 0;

$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');

if (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) {
    $collection_permission = 1;
}
if (isset($settings_map['Profile Entry']) && strpos($settings_map['Profile Entry'], $userlevel) !== false) {
    $profile_entry_permission = 1;
}
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Tab Styling (M3 Segmented Buttons Style) */
    .nav-pills .nav-link {
        border-radius: 100px;
        color: #6750A4;
        font-weight: 500;
        margin-right: 8px;
        padding: 8px 16px;
        border: 1px solid #79747E;
        background: transparent;
    }
    .nav-pills .nav-link.active {
        background-color: #EADDFF !important;
        color: #21005D !important;
        border: 1px solid #6750A4;
    }

    /* Student Card Styling (Material 3) */
    .st-card {
        background: #fff;
        border-radius: 24px;
        border: none;
        transition: transform 0.2s;
        overflow: hidden;
    }
    .st-card:active { transform: scale(0.98); }
    .st-card.disabled { opacity: 0.6; filter: grayscale(1); }

    .st-photo {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        object-fit: cover;
        background: #f0f0f0;
    }

    .action-btn {
        background: #F7F2FA;
        border-radius: 12px;
        padding: 8px;
        transition: background 0.2s;
        border: none;
        width: 100%;
    }
    .action-btn:active { background: #EADDFF; }
    .action-btn i { font-size: 1.25rem; color: #6750A4; }
    .action-label { font-size: 0.65rem; color: #49454F; font-weight: 600; margin-top: 4px; }

    .summary-card {
        background: #EADDFF;
        border-radius: 20px;
        color: #21005D;
    }
</style>

<main class="container mt-3 pb-5">

    <div class="d-flex align-items-center mb-4">
        <a href="reporthome.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
        <h4 class="fw-bold mb-0">Class Students</h4>
    </div>

    <?php if ($count_class > 0): ?>
        <div class="overflow-auto mb-4" style="white-space: nowrap;">
            <ul class="nav nav-pills flex-nowrap" id="classTabs" role="tablist">
                <?php for ($i = 0; $i < $count_class; $i++): ?>
                    <li class="nav-item">
                        <button class="nav-link <?php echo ($i == 0) ? 'active' : ''; ?>" 
                                id="tab-btn-<?php echo $i; ?>" 
                                data-bs-toggle="pill" 
                                data-bs-target="#tab-<?php echo $i; ?>" 
                                type="button">
                            <?php echo htmlspecialchars($cteacher_data[$i]['cteachercls'] . ' - ' . $cteacher_data[$i]['cteachersec']); ?>
                        </button>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>

        <div class="tab-content" id="classTabsContent">
            <?php for ($h = 0; $h < $count_class; $h++):
                $classname = $cteacher_data[$h]['cteachercls'];
                $sectionname = $cteacher_data[$h]['cteachersec'];
            ?>
                <div class="tab-pane fade <?php echo ($h == 0) ? 'show active' : ''; ?>" id="tab-<?php echo $h; ?>" role="tabpanel">

                    <div class="summary-card p-3 mb-4 shadow-sm">
                        <div class="row text-center">
                            <div class="col border-end border-white">
                                <h3 class="fw-bold mb-0" id="cnt-<?php echo $h; ?>"><span class="spinner-border spinner-border-sm"></span></h3>
                                <div class="small fw-medium opacity-75">Students</div>
                            </div>
                            <?php if ($collection_permission == 1): ?>
                                <div class="col">
                                    <h3 class="fw-bold mb-0" id="cntamt-<?php echo $h; ?>"><span class="spinner-border spinner-border-sm"></span></h3>
                                    <div class="small fw-medium opacity-75">Total Dues</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <?php
                        $month = date('m');
                        $cnt = 0; $cntamt = 0;

                        // ১. বকেয়া হিসেবের অপ্টিমাইজড কোয়েরি
                        $dues_data = [];
                        $stmt_dues = $conn->prepare("SELECT stid, SUM(dues) as total_dues FROM stfinance WHERE sessionyear = ? AND sccode = ? AND classname = ? AND sectionname = ? AND month <= ? GROUP BY stid");
                        $stmt_dues->bind_param("sssss", $sy, $sccode, $classname, $sectionname, $month);
                        $stmt_dues->execute();
                        $res_dues = $stmt_dues->get_result();
                        while($rd = $res_dues->fetch_assoc()) { $dues_data[$rd['stid']] = $rd['total_dues']; }

                        // ২. স্টুডেন্ট লিস্ট কোয়েরি
                        $stmt_st = $conn->prepare("SELECT * FROM sessioninfo WHERE sessionyear = ? AND sccode = ? AND classname = ? AND sectionname = ? ORDER BY rollno ASC");
                        $stmt_st->bind_param("ssss", $sy, $sccode, $classname, $sectionname);
                        $stmt_st->execute();
                        $res_st = $stmt_st->get_result();

                        if ($res_st->num_rows > 0):
                            while ($row = $res_st->fetch_assoc()):
                                $stid = $row["stid"];
                                $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                                if($st_idx === false) continue;

                                $st_profile = $datam_st_profile[$st_idx];
                                $total_dues = $dues_data[$stid] ?? 0;
                                $is_active = ($row["status"] != '0');
                                
                                $cnt++;
                                $cntamt += $total_dues;

                                // ফটো লজিক
                                $photo_url = "https://eimbox.com/images/no-image.png";
                                $local_photo = $BASE_PATH_URL . 'students/' . $stid . ".jpg";
                                if (file_exists($local_photo)) {
                                    $photo_url = $BASE_PATH_URL_FILE . 'students/' . $stid . ".jpg";
                                }
                        ?>
                            <div class="col-12 col-md-6">
                                <div class="st-card shadow-sm p-3 <?php echo $is_active ? '' : 'disabled'; ?>">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?php echo $photo_url; ?>" class="st-photo me-3 border elevation-1" onerror="this.src='https://eimbox.com/images/no-image.png'">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($st_profile["stnameeng"]); ?></h6>
                                            <div class="text-muted small mb-2"><?php echo htmlspecialchars($st_profile["stnameben"]); ?></div>
                                            <div class="d-flex gap-2">
                                                <span class="badge rounded-pill bg-primary px-3">Roll: <?php echo $row["rollno"]; ?></span>
                                                <span class="badge rounded-pill bg-secondary px-3">ID: <?php echo $stid; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 text-center">
                                        <div class="col">
                                            <button class="action-btn" onclick="st_attnd('<?php echo $stid; ?>')">
                                                <i class="bi bi-fingerprint"></i>
                                                <div class="action-label">Attendance</div>
                                            </button>
                                        </div>
                                        <?php if ($collection_permission): ?>
                                        <div class="col">
                                            <button class="action-btn" onclick="st_pay('<?php echo $stid; ?>')">
                                                <i class="bi bi-coin"></i>
                                                <div class="action-label text-danger">৳<?php echo number_format($total_dues); ?></div>
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                        <div class="col">
                                            <button class="action-btn" onclick="st_res('<?php echo $stid; ?>')">
                                                <i class="bi bi-file-earmark-ruled"></i>
                                                <div class="action-label">Results</div>
                                            </button>
                                        </div>
                                        <?php if ($profile_entry_permission): ?>
                                        <div class="col">
                                            <button class="action-btn" onclick="st_prof('<?php echo $stid; ?>')">
                                                <i class="bi bi-pencil-square"></i>
                                                <div class="action-label">Edit</div>
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <i class="bi bi-emoji-frown fs-1 opacity-25"></i>
                                <p class="text-muted">No students found.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <script>
                    document.getElementById("cnt-<?php echo $h; ?>").innerText = "<?php echo $cnt; ?>";
                    <?php if ($collection_permission): ?>
                    document.getElementById("cntamt-<?php echo $h; ?>").innerText = "৳<?php echo number_format($cntamt); ?>";
                    <?php endif; ?>
                </script>
            <?php endfor; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-soft-warning rounded-4 border-0">Assign a class teacher to view this report.</div>
    <?php endif; ?>
</main>

<script>
    function st_attnd(id) { window.location.href = "stattnd_individual.php?stid=" + id; }
    function st_pay(id) { window.location.href = "stfinancedetails.php?id=" + id; }
    function st_res(id) { window.location.href = "st_result_report.php?stid=" + id; }
    function st_prof(id) { window.location.href = "student-my-profile.php?stid=" + id; }
</script>

<?php include 'footer.php'; ?>