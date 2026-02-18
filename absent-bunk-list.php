<?php
$page_title = "Absent &mdash; Bunk List";
include 'inc.php';
include 'datam/datam-stprofile.php';

$td_attnd = $_GET['date'] ?? date('Y-m-d');

// ১. ক্লাস হ্যান্ডলিং ও সর্টিং
if (!$cteacher_data)
    $cteacher_data = array();
$extra = 0;
if (isset($_GET['cls']) && isset($_GET['sec'])) {
    $extra = 1;
    $classname = $_GET['cls'];
    $sectionname = $_GET['sec'];
    foreach ($cteacher_data as $ctas) {
        if ($classname == $ctas['cteachercls'] && $sectionname == $ctas['cteachersec']) {
            $extra = 0;
            break;
        }
    }
    if ($extra == 1) {
        array_unshift($cteacher_data, [
            'cteachercls' => $classname,
            'cteachersec' => $sectionname
        ]);
    }
}
$count_class = count($cteacher_data);
?>

<style>
    /* Date Selection Pill Styling - Improved */
    .m3-date-pill {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 100px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
        cursor: pointer;
        position: relative;
    }

    .m3-date-pill input[type="date"] {
        position: absolute;
        opacity: 0;
        /* ইনপুটটি ইনভিজিবল কিন্তু ক্লিকযোগ্য থাকবে */
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        cursor: pointer;
    }

    .display-date {
        color: white;
        font-size: 0.85rem;
        font-weight: 800;
    }

    /* Tab & Grid Styling */
    .m3-tab-container {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding: 0 12px 12px;
        scrollbar-width: none;
    }

    .m3-tab {
        padding: 10px 20px;
        border-radius: 100px;
        border: none;
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
        background: #F3EDF7;
        color: #49454F;
    }

    .m3-tab.active {
        background: var(--m3-primary);
        color: #fff;
        box-shadow: 0 4px 10px rgba(103, 80, 164, 0.2);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-top: 20px;
    }

    .summary-item {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 10px 5px;
        text-align: center;
    }

    .summary-val {
        font-size: 1.2rem;
        font-weight: 900;
        line-height: 1;
    }

    .summary-lbl {
        font-size: 0.55rem;
        font-weight: 700;
        text-transform: uppercase;
        opacity: 0.8;
    }

    /* বাঙ্ক এবং অনুপস্থিতদের জন্য ভিজ্যুয়াল মার্কিং */
    .st-bunk {
        background: #FFF9C4 !important;
        border-left: 6px solid #FBC02D !important;
    }

    /* হালকা হলুদ আভা */
    .st-absent {
        background: #FFEBEE !important;
        border-left: 6px solid #D32F2F !important;
    }

    /* হালকা লাল আভা */

    .status-badge {
        font-size: 0.6rem;
        font-weight: 900;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
    }

    .badge-bunk {
        background: #FFB300;
        color: #fff;
    }

    .badge-absent {
        background: #D32F2F;
        color: #fff;
    }
</style>

<main>
    <?php for ($h2 = 0; $h2 < $count_class; $h2++) {
        $c_cls = $cteacher_data[$h2]['cteachercls'];
        $c_sec = $cteacher_data[$h2]['cteachersec'];
        $display_style = ($h2 == 0) ? 'block' : 'none';
        ?>
        <div id="hero_summary_<?php echo $h2; ?>" class="hero-container shadow-sm"
            style="display:<?php echo $display_style; ?>; padding-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 1.2rem; font-weight: 900; line-height: 1.1;"><i
                            class="bi bi-person-x-fill me-2"></i>Absent & Bunk</div>
                    <div style="font-size: 0.75rem; opacity: 0.9; font-weight: 700; margin-top: 5px;">
                        <?php echo strtoupper($c_cls . ' : ' . $c_sec); ?>
                    </div>
                </div>

                <div class="text-end">
                    <div class="m3-date-pill" onclick="this.querySelector('input').showPicker()" style="z-index:2000;">
                        <i class="bi bi-calendar3" style="color: #fff; font-size: 0.85rem;"></i>
                        <span class="display-date"><?php echo date('d M, Y', strtotime($td_attnd)); ?></span>
                        <input type="date" value="<?php echo $td_attnd; ?>" onchange="updatePageDate(this.value)">
                    </div>
                </div>
            </div>

            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-val" id="cnt<?php echo $h2; ?>">0</div>
                    <div class="summary-lbl">Enroll</div>
                </div>
                <div class="summary-item" style="color: #C8E6C9;">
                    <div class="summary-val" id="cnt_pre<?php echo $h2; ?>">0</div>
                    <div class="summary-lbl">Present</div>
                </div>
                <div class="summary-item" style="color: #FFEB3B;">
                    <div class="summary-val" id="cnt_bunk<?php echo $h2; ?>">0</div>
                    <div class="summary-lbl">Bunk</div>
                </div>
                <div class="summary-item" style="color: #FFCDD2;">
                    <div class="summary-val" id="cnt_abs<?php echo $h2; ?>">0</div>
                    <div class="summary-lbl">Absent</div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($count_class > 1) { ?>
        <div class="m3-tab-container mt-3">
            <?php for ($h = 0; $h < $count_class; $h++) {
                $active_class = ($h == 0) ? 'active' : '';
                ?>
                <button id="btn<?php echo $h; ?>" class="m3-tab <?php echo $active_class; ?>"
                    onclick="switchClass('<?php echo $h; ?>', '<?php echo $count_class; ?>');">
                    <?php echo $cteacher_data[$h]['cteachercls'] . ' • ' . $cteacher_data[$h]['cteachersec']; ?>
                </button>
            <?php } ?>
        </div>
    <?php } ?>

    <?php for ($h2 = 0; $h2 < $count_class; $h2++) {
        $c_cls = $cteacher_data[$h2]['cteachercls'];
        $c_sec = $cteacher_data[$h2]['cteachersec'];
        $display_style = ($h2 == 0) ? 'block' : 'none';

        // Attendance Data Fetching
        /* ===============================
   1️⃣ Students Fetch First
================================ */
        $students_in_class = array();
        $stid_list = array();

        $sql_st = "SELECT * FROM sessioninfo 
           WHERE sessionyear LIKE '$sessionyear_param'
           AND sccode='$sccode'
           AND classname='$c_cls'
           AND sectionname='$c_sec'
           ORDER BY rollno";

        $res_st = $conn->query($sql_st);
        while ($row_st = $res_st->fetch_assoc()) {
            $students_in_class[] = $row_st;
            $stid_list[] = $row_st['stid'];
        }

        $total_enroll = count($students_in_class);


        /* ===============================
           2️⃣ Attendance Fetch by STID
        ================================ */
        $att_data = array();

        if (!empty($stid_list)) {

            $stid_csv = implode(",", array_map('intval', $stid_list));

            $sql_att = "SELECT stid, yn, bunk
                FROM stattnd
                WHERE adate='$td_attnd'
                AND sccode='$sccode'
                AND sessionyear LIKE '$sessionyear_param'
                AND stid IN ($stid_csv)";

            $res_att = $conn->query($sql_att);

            while ($row_att = $res_att->fetch_assoc()) {
                $att_data[$row_att['stid']] = $row_att; // KEYED
            }
        }


        // Students Fetching
        $students_in_class = array();
        $sql_st = "SELECT * FROM sessioninfo WHERE sessionyear LIKE '$sessionyear_param' AND sccode='$sccode' AND classname='$c_cls' AND sectionname = '$c_sec' ORDER BY rollno";
        $res_st = $conn->query($sql_st);
        while ($row_st = $res_st->fetch_assoc()) {
            $students_in_class[] = $row_st;
        }

        $total_enroll = count($students_in_class);
        $absent_cnt = $bunk_cnt = 0;
        ?>
        <div id="clssecblock<?php echo $h2; ?>" style="display:<?php echo $display_style; ?>; margin-top: 10px;">
            <div class="widget-grid">
                <?php foreach ($students_in_class as $st):
                    $stid = $st['stid'];

                    $status = isset($att_data[$stid]) ? (int) $att_data[$stid]['yn'] : 0;
                    $bunk = isset($att_data[$stid]) ? (int) $att_data[$stid]['bunk'] : 0;





                    if ($status == 0 || $bunk == 1) {
                        if ($status == 0)
                            $absent_cnt++;
                        if ($bunk == 1)
                            $bunk_cnt++;

                        $card_type = ($bunk == 1) ? 'st-bunk' : 'st-absent';
                        $st_ind = array_search($stid, array_column($datam_st_profile, 'stid'));
                        $neng = $datam_st_profile[$st_ind]["stnameeng"];
                        $guarmobile = $datam_st_profile[$st_ind]["guarmobile"];
                        $pth = student_profile_image_path($stid);
                        ?>
                        <div class="m3-list-item <?php echo $card_type; ?>" id="block<?php echo $stid; ?>"
                            style="flex-direction: column; align-items: stretch; padding: 0;"
                            onclick="toggleExtra('<?php echo $stid; ?>')">
                            <div style="display: flex; align-items: center; padding: 12px;">
                                <img src="<?php echo $pth; ?>"
                                    style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover; margin-right: 12px; background: #fff; border: 1px solid #eee;" />
                                <div class="flex-grow-1">
                                    <div class="fw-bold" style="font-size: 0.9rem;"><?php echo $neng; ?></div>
                                    <div style="margin-top: 2px;">
                                        <?php if ($bunk == 1): ?>
                                            <span class="status-badge badge-bunk"><i class="bi bi-exclamation-triangle-fill"></i>
                                                Bunked</span>
                                        <?php else: ?>
                                            <span class="status-badge badge-absent"><i class="bi bi-x-circle-fill"></i> Absent</span>
                                        <?php endif; ?>
                                        <span class="ms-1 text-muted small fw-bold">ID: <?php echo $stid; ?></span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div style="font-size: 0.55rem; font-weight: 800; color: #888;">ROLL</div>
                                    <div style="font-size: 1.4rem; font-weight: 900; color: var(--m3-primary); line-height: 1;">
                                        <?php echo $st['rollno']; ?>
                                    </div>
                                </div>
                            </div>

                            <div id="extra<?php echo $stid; ?>"
                                style="display: none; padding: 10px; background: rgba(0,0,0,0.03); border-top: 1px dashed rgba(0,0,0,0.1);">
                                <div class="d-flex justify-content-around">
                                    <button class="tonal-icon-btn"
                                        onclick="event.stopPropagation(); send_absent_notice('<?php echo $stid; ?>', 0, '<?php echo $guarmobile; ?>')"><i
                                            class="bi bi-telephone-fill text-primary"></i></button>
                                    <button class="tonal-icon-btn"
                                        onclick="event.stopPropagation(); send_absent_notice('<?php echo $stid; ?>', 1, '<?php echo $guarmobile; ?>')"><i
                                            class="bi bi-chat-dots-fill text-danger"></i></button>
                                    <button class="tonal-icon-btn"
                                        onclick="event.stopPropagation(); send_absent_notice('<?php echo $stid; ?>', 2, '<?php echo $guarmobile; ?>')"><i
                                            class="bi bi-bell-fill text-warning"></i></button>
                                    <button class="tonal-icon-btn" onclick="event.stopPropagation(); go('<?php echo $stid; ?>')"><i
                                            class="bi bi-person-badge-fill text-success"></i></button>
                                </div>
                            </div>
                        </div>
                    <?php }endforeach; ?>
            </div>
        </div>

        <script>
            document.getElementById("cnt<?php echo $h2; ?>").innerHTML = "<?php echo $total_enroll; ?>";
            document.getElementById("cnt_abs<?php echo $h2; ?>").innerHTML = "<?php echo $absent_cnt; ?>";
            document.getElementById("cnt_bunk<?php echo $h2; ?>").innerHTML = "<?php echo $bunk_cnt; ?>";
            document.getElementById("cnt_pre<?= $h2 ?>").innerHTML =
                "<?= ($total_enroll - $absent_cnt - $bunk_cnt) ?>";
        </script>
    <?php } ?>
</main>

<div style="height:80px;"></div>
<?php include 'footer.php'; ?>

<script>
    // তারিখ পরিবর্তনের লজিক
    function updatePageDate(newDate) {
        if (!newDate) return;
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('date', newDate);
        window.location.search = urlParams.toString();
    }

    // ক্লাস সুইচ লজিক
    function switchClass(cur, mot) {
        for (var i = 0; i < mot; i++) {
            document.getElementById('clssecblock' + i).style.display = 'none';
            document.getElementById('hero_summary_' + i).style.display = 'none';
            document.getElementById('btn' + i).classList.remove("active");
        }
        document.getElementById('clssecblock' + cur).style.display = 'block';
        document.getElementById('hero_summary_' + cur).style.display = 'block';
        document.getElementById('btn' + cur).classList.add("active");
    }

    function toggleExtra(id) {
        document.querySelectorAll('[id^="extra"]').forEach(el => {
            if (el.id !== 'extra' + id) el.style.display = 'none';
        });
        var elem = document.getElementById("extra" + id);
        elem.style.display = (elem.style.display === 'block') ? 'none' : 'block';
    }

    function go(id) { window.location.href = "student-my-profile.php?stid=" + id; }
</script>