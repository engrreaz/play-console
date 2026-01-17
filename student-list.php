<?php
session_start();
$page_title = "Class Students";
include_once 'inc.php'; 
include_once 'datam/datam-stprofile.php';

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


?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* Condensed M3 Tab Container */
    .m3-tab-container { overflow-x: auto; white-space: nowrap; padding: 6px 12px; border-bottom: 1px solid #E7E0EC; background: #fff; }
    .nav-pills .nav-link {
        border-radius: 8px; border: 1px solid #79747E; color: #49454F;
        padding: 4px 12px; font-size: 0.75rem; font-weight: 600; margin-right: 6px; background: transparent;
    }
    .nav-pills .nav-link.active { background-color: #EADDFF !important; color: #21005D !important; border-color: #6750A4; }

    /* Compact Summary Card */
    .summary-card { background: #F3EDF7; border-radius: 12px; padding: 12px; margin: 8px 12px; display: flex; justify-content: space-around; text-align: center; }
    .stat-val { font-size: 1.2rem; font-weight: 800; color: #6750A4; }
    .stat-lbl { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; color: #49454F; }

    /* Expandable Student List Item */
    .st-card {
        background: #FFFFFF; border-radius: 8px; padding: 8px 12px; margin: 0 8px 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #eee; transition: all 0.2s ease;
    }
    .st-card.inactive { opacity: 0.5; filter: grayscale(1); }
    
    .st-avatar { width: 44px; height: 44px; border-radius: 8px; object-fit: cover; background: #eee; }

    .due-pill { font-size: 0.7rem; font-weight: 800; color: #B3261E; background: #FFEBEE; padding: 2px 8px; border-radius: 6px; }

    /* Hidden Action Bar */
    .action-bar { 
        display: none; /* Default hidden */
        grid-template-columns: repeat(4, 1fr); gap: 6px; 
        margin-top: 10px; padding-top: 10px; border-top: 1px dashed #E7E0EC;
    }
    .st-card.expanded .action-bar { display: grid; }
    .st-card.expanded { border-color: #6750A4; background-color: #F7F2FA; }

    .action-item {  border-radius: 8px; padding: 6px; border: 0px solid #E7E0EC; text-align: center; text-decoration: none !important; }
    .action-item:hover { background: #F7F2FA; color:#6750A4;}
    .action-item i { font-size: 1.5rem;  display: block; }
    .action-lbl { font-size: 0.55rem; font-weight: 700; color: #49454F; margin-top: 2px; text-transform: uppercase; }

    .scroll-hide::-webkit-scrollbar { display: none; }
</style>

<header class="m3-app-bar shadow-sm" style="height: 56px; border-radius: 0 0 12px 12px;">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    <h1 class="page-title" style="font-size: 1.1rem;"><?php echo $page_title; ?></h1>
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
                <div><span class="stat-val" id="cnt-<?php echo $h; ?>">0</span> <span class="stat-lbl">Students</span></div>
                <?php if ($collection_permission): ?>
                <div class="vr opacity-10"></div>
                <div><span class="stat-val text-danger" id="cntamt-<?php echo $h; ?>">0</span> <span class="stat-lbl">Dues</span></div>
                <?php endif; ?>
            </div>

            <div class="list-container px-1">
                <?php
                $cnt = 0; $cntamt = 0;
                $dues_map = [];
                $stmt_d = $conn->prepare("SELECT stid, SUM(dues) as td FROM stfinance WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND month <= ? GROUP BY stid");
                $stmt_d->bind_param("sssss", $sy_param, $sccode, $cls, $sec, $month);
                $stmt_d->execute();
                $res_d = $stmt_d->get_result();
                while($rd = $res_d->fetch_assoc()) $dues_map[$rd['stid']] = $rd['td'];
                $stmt_d->close();

                $stmt_s = $conn->prepare("SELECT * FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? ORDER BY rollno ASC");
                $stmt_s->bind_param("ssss", $sy_param, $sccode, $cls, $sec);
                $stmt_s->execute();
                $res_s = $stmt_s->get_result();

                while ($row = $res_s->fetch_assoc()):
                    $stid = $row["stid"];
                    $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                    if($st_idx === false) continue;
                    $p = $datam_st_profile[$st_idx];
                    $due = $dues_map[$stid] ?? 0;
                    $is_active = ($row["status"] == '1');
                    if($is_active) { $cnt++; $cntamt += $due; }
                ?>
                    <div class="st-card shadow-sm <?php echo $is_active ? '' : 'inactive'; ?>" onclick="this.classList.toggle('expanded')">
                        <div class="d-flex align-items-center">
                            <img src="https://eimbox.com/students/<?php echo $stid; ?>.jpg" class="st-avatar shadow-sm" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                            <div class="ms-3 flex-grow-1 overflow-hidden">
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;"><?php echo $p["stnameeng"]; ?></div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="fw-bold text-primary" style="font-size: 0.75rem;">Roll: <?php echo $row["rollno"]; ?></span>
                                    <span class="text-muted" style="font-size: 0.65rem;">ID: <?php echo $stid; ?></span>
                                    <?php if($due > 0): ?>
                                        <span class="due-pill ms-auto">৳<?php echo number_format($due); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <i class="bi bi-chevron-expand text-muted ms-2 opacity-50"></i>
                        </div>

                        <div class="action-bar">
                            <div class="action-item" onclick="event.stopPropagation(); st_attnd('<?php echo $stid; ?>')">
                                <i class="bi bi-fingerprint"></i><div class="action-lbl">Attend</div>
                            </div>
                            <?php if ($collection_permission): ?>
                            <div class="action-item" onclick="event.stopPropagation(); st_pay('<?php echo $stid; ?>')">
                                <i class="bi bi-coin"></i><div class="action-lbl">Payment</div>
                            </div>
                            <?php endif; ?>
                            <div class="action-item" onclick="event.stopPropagation(); st_res('<?php echo $stid; ?>')">
                                <i class="bi bi-file-earmark-ruled"></i><div class="action-lbl">Result</div>
                            </div>
                            <div class="action-item" onclick="event.stopPropagation(); st_prof('<?php echo $stid; ?>')">
                                <i class="bi bi-person-bounding-box"></i><div class="action-lbl">Profile</div>
                            </div>
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
    <?php endif; ?>
</main>

<div style="height: 65px;"></div>

<script>
    function st_attnd(id) { window.location.href = "stguarattnd.php?stid=" + id; }
    function st_pay(id) { window.location.href = "stfinancedetails.php?id=" + id; }
    function st_res(id) { window.location.href = "stguarresult.php?stid=" + id; }
    function st_prof(id) { window.location.href = "student-my-profile.php?stid=" + id; }
</script>

<?php include 'footer.php'; ?>