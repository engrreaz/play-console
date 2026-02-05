<?php
$page_title = "My Class";
include_once 'inc.php';
include_once 'datam/datam-stprofile.php';


$month = date('m');

// ২. ক্লাস ডাটা প্রিপারেশন
if (isset($_GET['cls']) && isset($_GET['sec'])) {
    $classname = $_GET['cls'];
    $sectionname = $_GET['sec'];
    $cteacher_data = [['cteachercls' => $classname, 'cteachersec' => $sectionname]];
}

$count_class = count($cteacher_data);

// ৩. পারমিশন ম্যাপ
$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
$collection_permission = (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) ? 1 : 0;
$profile_permission = (isset($settings_map['Profile Entry']) && strpos($settings_map['Profile Entry'], $userlevel) !== false) ? 1 : 0;
?>

<style>
    body {
        background-color: #FEF7FF;
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
    }

    /* Full Width M3 Top Bar - Flipped to 100% width */
    .m3-app-bar {
        width: 100%;
        position: sticky;
        top: 0;
        z-index: 1050;
        background: #fff;
        height: 56px;
        display: flex;
        align-items: center;
        padding: 0 16px;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .m3-app-bar .page-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1C1B1F;
        flex-grow: 1;
        margin: 0;
    }

    /* Scrollable M3 Tabs (8px radius) */
    .m3-tab-bar {
        overflow-x: auto;
        white-space: nowrap;
        background: #fff;
        padding: 8px 12px;
        border-bottom: 1px solid #E7E0EC;
        position: sticky;
        top: 56px;
        z-index: 1040;
    }

    .nav-pills .nav-link {
        border-radius: 8px;
        border: 1px solid #79747E;
        color: #49454F;
        padding: 6px 16px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-right: 8px;
        transition: 0.2s;
    }

    .nav-pills .nav-link.active {
        background-color: #6750A4 !important;
        color: #fff !important;
        border-color: #6750A4;
    }

    /* Condensed Summary Card */
    .summary-chip-row {
        display: flex;
        gap: 8px;
        padding: 12px;
    }

    .m3-stat-chip {
        flex: 1;
        background: #F3EDF7;
        border-radius: 8px;
        padding: 8px;
        text-align: center;
        border: 1px solid #EADDFF;
    }

    .stat-val {
        font-size: 1.1rem;
        font-weight: 800;
        color: #6750A4;
        display: block;
    }

    .stat-lbl {
        font-size: 0.55rem;
        font-weight: 700;
        color: #49454F;
        text-transform: uppercase;
    }

    /* Expandable Student Card */
    .st-card {
        background: #fff;
        border-radius: 8px;
        padding: 10px 12px;
        margin: 0 8px 8px;
        border: 1px solid #eee;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .st-card:active {
        background: #F7F2FA;
    }

    .st-card.expanded {
        border-color: #6750A4;
    }

    .st-avatar {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #E7E0EC;
    }

    .due-badge {
        font-size: 0.65rem;
        font-weight: 800;
        color: #B3261E;
        background: #FFEBEE;
        padding: 2px 6px;
        border-radius: 4px;
    }

    /* Action Grid - Appears on Click */
    .action-grid {
        display: none;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px dashed #EADDFF;
    }

    .st-card.expanded .action-grid {
        display: grid;
    }

    .act-item {
        text-align: center;
        color: #49454F;
        text-decoration: none !important;
        transition: 0.2s;
    }

    .act-item i {
        font-size: 1.4rem;
        display: block;
        color: #6750A4;
    }

    .act-item span {
        font-size: 0.55rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-top: 4px;
        display: block;
    }

    .act-item:active {
        transform: scale(0.9);
    }

    .scroll-hide::-webkit-scrollbar {
        display: none;
    }

    .m3-tab-bar-container {
        background: #fff;
        padding: 8px;
        margin-bottom: 12px;
        border-radius: 0 0 8px 8px;
        /* নিচের দিকে হালকা রাউন্ড */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* ট্যাব বাটনগুলোকে সমানভাবে ভাগ করা */
    #classTabs {
        gap: 6px;
        /* বাটনগুলোর মাঝের গ্যাপ */
    }

    #classTabs .nav-item {
        flex: 1;
        /* প্রতিটি ট্যাবকে সমান জায়গা নিতে বাধ্য করবে */
        text-align: center;
    }

    #classTabs .nav-link {
        width: 100%;
        border-radius: 8px;
        /* M3 চিপ স্টাইল */
        font-weight: 700;
        font-size: 0.85rem;
        padding: 10px 4px;
        color: #49454F;
        background: transparent;
        border: 1px solid transparent;
        transition: 0.3s;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        /* নাম বড় হলে ডট ডট দেখাবে */
    }

    /* একটিভ ট্যাবের প্রিমিয়াম লুক */
    #classTabs .nav-link.active {
        background: var(--m3-tonal-container) !important;
        color: var(--m3-primary) !important;
        border: 1px solid var(--m3-tonal-container) !important;
        box-shadow: 0 2px 6px rgba(103, 80, 164, 0.1);
    }
</style>


<main class="pb-0">
    <?php if ($count_class > 0): ?>
        <div class="m3-tab-bar-container">
            <div class="m3-section-title ">Class(es)</div>
            <ul class="nav nav-pills w-100 nav-justified" style="margin:0 8px;" id="classTabs" role="tablist">
                <?php for ($i = 0; $i < $count_class; $i++):
                    $c_name = $cteacher_data[$i]['cteachercls'];
                    $s_name = $cteacher_data[$i]['cteachersec'];
                    ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo ($i == 0) ? 'active' : ''; ?>" id="tab-btn-<?php echo $i; ?>"
                            data-bs-toggle="pill" data-bs-target="#pane-<?php echo $i; ?>" type="button" role="tab">
                            <?php echo "$c_name - $s_name"; ?>
                        </button>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>

        <div class="tab-content mt-2">
            <?php for ($h = 0; $h < $count_class; $h++):
                $cls = $cteacher_data[$h]['cteachercls'];
                $sec = $cteacher_data[$h]['cteachersec'];
                ?>
                <div class="tab-pane fade <?php echo ($h == 0) ? 'show active' : ''; ?>" id="pane-<?php echo $h; ?>">

                    <div class="summary-chip-row">
                        <div class="m3-stat-chip shadow-sm">
                            <span class="stat-lbl">Total Students</span>
                            <span class="stat-val" id="cnt-<?php echo $h; ?>">0</span>
                        </div>
                        <?php if ($collection_permission): ?>
                            <div class="m3-stat-chip shadow-sm">
                                <span class="stat-lbl">Pending Dues</span>
                                <span class="stat-val text-danger" id="due-<?php echo $h; ?>">৳ 0</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="student-list px-1">
                        <?php
                        $cnt = 0;
                        $total_due = 0;
                        $dues_map = [];

                        // অপ্টিমাইজড কুয়েরি (বকেয়া ডাটা)
                        $stmt_d = $conn->prepare("SELECT stid, SUM(dues) as td FROM stfinance WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND month <= ? GROUP BY stid");
                        $stmt_d->bind_param("sssss", $sessionyear_param, $sccode, $cls, $sec, $month);
                        $stmt_d->execute();
                        $res_d = $stmt_d->get_result();
                        while ($rd = $res_d->fetch_assoc())
                            $dues_map[$rd['stid']] = $rd['td'];

                        // স্টুডেন্ট সেশন ডাটা
                        $stmt_s = $conn->prepare("SELECT * FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? ORDER BY rollno ASC");
                        $stmt_s->bind_param("ssss", $sessionyear_param, $sccode, $cls, $sec);
                        $stmt_s->execute();
                        $res_s = $stmt_s->get_result();

                        while ($row = $res_s->fetch_assoc()):
                            $stid = $row["stid"];
                            $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                            if ($st_idx === false)
                                continue;

                            $p = $datam_st_profile[$st_idx];
                            $due = $dues_map[$stid] ?? 0;
                            $is_active = ($row["status"] == '1');
                            if ($is_active) {
                                $cnt++;
                                $total_due += $due;
                            }

                            $atx = get_student_info_by_id($stid);


                            ?>
                            <div class="st-card shadow-sm <?php echo $is_active ? '' : 'opacity-50 grayscale'; ?>"
                                onclick="this.classList.toggle('expanded')">
                                <div class="d-flex align-items-center">
                                    <img src="<?= student_profile_image_path($stid) ?>" class="st-avatar shadow-sm">
                                    <div class="ms-3 flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.75rem;">
                                            <?= $atx['stnameeng']; ?>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2"
                                                style="font-size: 0.65rem;">Roll: <?php echo $row["rollno"]; ?></span>
                                            <span class="text-muted" style="font-size: 0.65rem;">ID: <?php echo $stid; ?></span>

                                            <?php if ($due > 0): ?>
                                                <span class="due-badge ms-auto">৳<?php echo number_format($due); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <i class="bi bi-three-dots-vertical text-muted ms-1 opacity-25"></i>
                                </div>

                                <div class="action-grid">
                                    <a href="stguarattnd.php?stid=<?php echo $stid; ?>" class="act-item"
                                        onclick="event.stopPropagation();">
                                        <i class="bi bi-calendar2-check"></i><span>Attend</span>
                                    </a>
                                    <?php if ($collection_permission): ?>
                                        <a href="stfinancedetails.php?id=<?php echo $stid; ?>" class="act-item"
                                            onclick="event.stopPropagation();">
                                            <i class="bi bi-wallet2"></i><span>Fees</span>
                                        </a>
                                    <?php endif; ?>
                                    <a href="stguarresult.php?stid=<?php echo $stid; ?>" class="act-item"
                                        onclick="event.stopPropagation();">
                                        <i class="bi bi-award"></i><span>Result</span>
                                    </a>
                                    <a href="student-my-profile.php?stid=<?php echo $stid; ?>" class="act-item"
                                        onclick="event.stopPropagation();">
                                        <i class="bi bi-person-badge"></i><span>Profile</span>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile;
                        $stmt_s->close(); ?>
                    </div>
                </div>
                <script>
                    // ডাইনামিক কাউন্ট আপডেট
                    document.getElementById("cnt-<?php echo $h; ?>").innerText = "<?php echo $cnt; ?>";
                    <?php if ($collection_permission): ?>
                        document.getElementById("due-<?php echo $h; ?>").innerText = "৳<?php echo number_format($total_due); ?>";
                    <?php endif; ?>
                </script>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</main>



</div> <?php include 'footer.php'; ?>