<?php
session_start();
include_once 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// --- ১. সেটিংস এবং পারমিশন হ্যান্ডলিং ---
$sy_param = '%' . $sy  . '%';
$month = date('m');

if (isset($_GET['cls']) && isset($_GET['sec'])) {
    $classname = $_GET['cls'];
    $sectionname = $_GET['sec'];
    $cteacher_data = [['cteachercls' => $classname, 'cteachersec' => $sectionname]];
}

$count_class = count($cteacher_data);
$collection_permission = 0;
$profile_entry_permission = 0;

$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
if (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) $collection_permission = 1;
if (isset($settings_map['Profile Entry']) && strpos($settings_map['Profile Entry'], $userlevel) !== false) $profile_entry_permission = 1;

$page_title = "Class Students";
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Tab Styling (M3 Chips/Segmented Control) */
    .m3-tab-container { overflow-x: auto; white-space: nowrap; padding: 10px 16px; border-bottom: 1px solid #E7E0EC; }
    .nav-pills .nav-link {
        border-radius: 100px; border: 1px solid #79747E; color: #49454F;
        padding: 6px 16px; font-size: 0.85rem; font-weight: 600; margin-right: 8px; background: transparent;
    }
    .nav-pills .nav-link.active { background-color: #EADDFF !important; color: #21005D !important; border-color: #6750A4; }

    /* Summary Dashboard Card */
    .summary-card { background: #F3EDF7; border-radius: 28px; padding: 20px; margin: 16px; display: flex; justify-content: space-around; text-align: center; }
    .stat-val { font-size: 1.5rem; font-weight: 800; color: #6750A4; line-height: 1; }
    .stat-lbl { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #49454F; margin-top: 5px; }

    /* Student List Card */
    .st-card {
        background: #FFFFFF; border-radius: 24px; padding: 16px; margin: 0 16px 12px;
        display: flex; flex-direction: column; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: transform 0.2s;
    }
    .st-card:active { transform: scale(0.98); background: #F7F2FA; }
    .st-card.inactive { opacity: 0.6; filter: grayscale(0.8); }

    .st-avatar { width: 56px; height: 56px; border-radius: 12px; object-fit: cover; background: #eee; border: 1px solid #E7E0EC; }

    /* Action Buttons Bar */
    .action-bar { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 15px; }
    .action-item { background: #F7F2FA; border-radius: 12px; padding: 8px; border: none; text-align: center; text-decoration: none !important; }
    .action-item:active { background: #EADDFF; }
    .action-item i { font-size: 1.2rem; color: #6750A4; display: block; }
    .action-lbl { font-size: 0.6rem; font-weight: 700; color: #49454F; margin-top: 4px; text-transform: uppercase; }
    .text-danger-m3 { color: #B3261E !important; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons"><i class="bi bi-search"></i></div>
</header>

<main class="pb-5">
    <?php if ($count_class > 0): ?>
    <div class="m3-tab-container scroll-hide">
        <ul class="nav nav-pills flex-nowrap" id="classTabs">
            <?php for ($i = 0; $i < $count_class; $i++): ?>
                <li class="nav-item">
                    <button class="nav-link <?php echo ($i == 0) ? 'active' : ''; ?>" data-bs-toggle="pill" data-bs-target="#tab-<?php echo $i; ?>">
                        <?php echo htmlspecialchars($cteacher_data[$i]['cteachercls'] . ' - ' . $cteacher_data[$i]['cteachersec']); ?>
                    </button>
                </li>
            <?php endfor; ?>
        </ul>
    </div>

    <div class="tab-content" id="classTabsContent">
        <?php for ($h = 0; $h < $count_class; $h++):
            $cls = $cteacher_data[$h]['cteachercls'];
            $sec = $cteacher_data[$h]['cteachersec'];
        ?>
        <div class="tab-pane fade <?php echo ($h == 0) ? 'show active' : ''; ?>" id="tab-<?php echo $h; ?>">
            
            <div class="summary-card shadow-sm">
                <div>
                    <span class="stat-val" id="cnt-<?php echo $h; ?>">--</span>
                    <span class="stat-lbl">Active Students</span>
                </div>
                <?php if ($collection_permission): ?>
                <div class="vr opacity-10"></div>
                <div>
                    <span class="stat-val text-danger-m3" id="cntamt-<?php echo $h; ?>">--</span>
                    <span class="stat-lbl">Total Dues</span>
                </div>
                <?php endif; ?>
            </div>
fffffffffff
            <div class="list-container">
                <?php
                $cnt = 0; $cntamt = 0;

                // বকেয়া তথ্য একবারে ফেচ করা (Optimization)
                $dues_map = [];
                // $stmt_d = $conn->prepare("SELECT stid, SUM(dues) as td FROM stfinance WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND month <= ? GROUP BY stid");
                // $stmt_d->bind_param("sssss", $sy_param, $sccode, $cls, $sec, $month);
                // $stmt_d->execute();
                // $res_d = $stmt_d->get_result();
                // while($rd = $res_d->fetch_assoc()) $dues_map[$rd['stid']] = $rd['td'];
                // $stmt_d->close();

                // স্টুডেন্ট লিস্ট
                echo $sy_param . '/' . $sccode . '/' . $cls . '/' . $sec . '/';
                $stmt_s = $conn->prepare("SELECT * FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? ORDER BY rollno ASC");
                $stmt_s->bind_param("ssss", $sy_param, $sccode, $cls, $sec);
                $stmt_s->execute();
                $res_s = $stmt_s->get_result();

                while ($row = $res_s->fetch_assoc()):
                    $stid = $row["stid"];
                    $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                    if($st_idx === false) continue;

                    $p = $datam_st_profile[$st_idx];
                    // $due = $dues_map[$stid] ?? 0;
                    $due = 0;
                    $is_active = ($row["status"] == '1');
                    
                    if($is_active) { $cnt++; $cntamt += $due; }
                ?>
                    <div class="st-card shadow-sm <?php echo $is_active ? '' : 'inactive'; ?>">
                        <div class="d-flex align-items-center">
                            <img src="https://eimbox.com/students/<?php echo $stid; ?>.jpg" class="st-avatar shadow-sm" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                            <div class="ms-3 overflow-hidden">
                                <div class="fw-bold text-dark text-truncate small"><?php echo $p["stnameeng"]; ?></div>
                                <div class="text-muted" style="font-size: 0.65rem;"><?php echo $p["stnameben"]; ?></div>
                                <div class="d-flex gap-2 mt-1">
                                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3">Roll: <?php echo $row["rollno"]; ?></span>
                                    <span class="badge rounded-pill bg-light text-muted px-2">ID: <?php echo $stid; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="action-bar">
                            <button class="action-item" onclick="st_attnd('<?php echo $stid; ?>')">
                                <i class="bi bi-fingerprint"></i>
                                <div class="action-lbl">Attend</div>
                            </button>
                            
                            <?php if ($collection_permission): ?>
                            <button class="action-item" onclick="st_pay('<?php echo $stid; ?>')">
                                <i class="bi bi-coin <?php echo ($due > 0) ? 'text-danger' : ''; ?>"></i>
                                <div class="action-lbl <?php echo ($due > 0) ? 'text-danger' : ''; ?>">৳<?php echo number_format($due); ?></div>
                            </button>
                            <?php endif; ?>

                            <button class="action-item" onclick="st_res('<?php echo $stid; ?>')">
                                <i class="bi bi-file-earmark-ruled"></i>
                                <div class="action-lbl">Result</div>
                            </button>

                            <?php if ($profile_entry_permission): ?>
                            <button class="action-item" onclick="st_prof('<?php echo $stid; ?>')">
                                <i class="bi bi-pencil-square"></i>
                                <div class="action-lbl">Edit</div>
                            </button>
                            <?php else: ?>
                            <button class="action-item" onclick="st_prof('<?php echo $stid; ?>')">
                                <i class="bi bi-person-bounding-box"></i>
                                <div class="action-lbl">Profile</div>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; $stmt_s->close(); ?>
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
        <div class="text-center py-5 opacity-25">
            <i class="bi bi-person-x display-1"></i>
            <p class="fw-bold mt-2">No assigned classes found.</p>
        </div>
    <?php endif; ?>
</main>

<div style="height: 60px;"></div> <script>
    function st_attnd(id) { window.location.href = "stguarattnd.php?stid=" + id; }
    function st_pay(id) { window.location.href = "stfinancedetails.php?id=" + id; }
    function st_res(id) { window.location.href = "stguarresult.php?stid=" + id; }
    function st_prof(id) { window.location.href = "student-my-profile.php?stid=" + id; }
</script>

<?php include 'footer.php'; ?>