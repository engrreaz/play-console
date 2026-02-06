<?php
include 'inc.php';
include 'datam/datam-stprofile.php';

// ১. ক্লাস হ্যান্ডলিং লজিক (অপরিবর্তিত)
$extra = 0;
if (isset($_GET['cls']) && isset($_GET['sec'])) {
    $extra = 1;
    $classname = $_GET['cls'];
    $sectionname = $_GET['sec'];
    foreach ($cteacher_data as $ctas) {
        $cx = $ctas['cteachercls'];
        $sx = $ctas['cteachersec'];
        if ($classname == $cx && $sectionname == $sx) {
            $extra = 0;
            break;
        }
    }
    if ($extra == 1) {
        $cteacher_data[] = ['cteachercls' => $classname, 'cteachersec' => $sectionname];
    }
}
$count_class = count($cteacher_data);
?>

<style>
    /* Tab System Styling */
    .m3-tab-container {
        display: flex; gap: 8px; overflow-x: auto; padding: 0 12px 12px;
        scrollbar-width: none; -ms-overflow-style: none;
    }
    .m3-tab-container::-webkit-scrollbar { display: none; }
    
    .m3-tab {
        padding: 10px 20px; border-radius: 100px; border: none;
        font-size: 0.8rem; font-weight: 700; white-space: nowrap;
        background: #F3EDF7; color: #49454F; transition: 0.2s;
    }
    .m3-tab.active { background: var(--m3-primary); color: #fff; box-shadow: 0 4px 10px rgba(103, 80, 164, 0.2); }

    /* Summary Grid inside Hero */
    .summary-grid {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 8px; margin-top: 20px;
    }
    .summary-item {
        background: rgba(255, 255, 255, 0.15); border-radius: 12px;
        padding: 10px 5px; text-align: center; backdrop-filter: blur(5px);
    }
    .summary-val { font-size: 1.3rem; font-weight: 900; line-height: 1; }
    .summary-lbl { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; margin-top: 4px; }

    /* Status Specific Colors */
    .st-bunk { background: #FFF3E0 !important; border-left: 5px solid #E65100 !important; }
    .st-absent { background: #FFEBEE !important; border-left: 5px solid #B3261E !important; }
</style>

<main>
    <?php for ($h2 = 0; $h2 < $count_class; $h2++) { 
        $classname = $cteacher_data[$h2]['cteachercls'];
        $sectionname = $cteacher_data[$h2]['cteachersec'];
        $ddss = ($h2 == 0) ? 'block' : 'none';
    ?>
    <div id="hero_summary_<?php echo $h2; ?>" class="hero-container" style="display:<?php echo $ddss; ?>; padding-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff;">
                    <i class="bi bi-slash-circle"></i>
                </div>
                <div>
                    <div style="font-size: 1.4rem; font-weight: 900; line-height: 1.1;">Absent - Bunk List</div>
                    <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 700;"><?php echo strtoupper($classname . ' : ' . $sectionname); ?></div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.85rem; font-weight: 800;"><?php echo date('d M, Y'); ?></div>
                <div style="font-size: 0.6rem; opacity: 0.8; font-weight: 700; text-transform: uppercase;">Real-time Status</div>
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-item"><div class="summary-val" id="cnt<?php echo $h2; ?>">0</div><div class="summary-lbl">Total</div></div>
            <div class="summary-item"><div class="summary-val" id="cnt_pre<?php echo $h2; ?>">0</div><div class="summary-lbl">Present</div></div>
            <div class="summary-item" style="color: #FFB300;"><div class="summary-val" id="cnt_bunk<?php echo $h2; ?>">0</div><div class="summary-lbl">Bunk</div></div>
            <div class="summary-item" style="color: #FFCDD2;"><div class="summary-val" id="cnt_abs<?php echo $h2; ?>">0</div><div class="summary-lbl">Absent</div></div>
        </div>
    </div>
    <?php } ?>

    <?php if ($count_class > 1) { ?>
        <div class="m3-tab-container mt-3">
            <?php for ($h = 0; $h < $count_class; $h++) {
                $cls_btn = $cteacher_data[$h]['cteachercls'];
                $sec_btn = $cteacher_data[$h]['cteachersec'];
                $active_class = ($h == 0) ? 'active' : '';
            ?>
                <button id="btn<?php echo $h; ?>" class="m3-tab <?php echo $active_class; ?>" 
                        onclick="myclass('<?php echo $h; ?>', '<?php echo $count_class; ?>');">
                    <?php echo $cls_btn . ' • ' . $sec_btn; ?>
                </button>
            <?php } ?>
        </div>
    <?php } ?>

    <?php for ($h2 = 0; $h2 < $count_class; $h2++) {
        $classname = $cteacher_data[$h2]['cteachercls'];
        $sectionname = $cteacher_data[$h2]['cteachersec'];
        $ddss = ($h2 == 0) ? 'block' : 'none';

        $datam = array();
        $sql00 = "SELECT * FROM stattnd where adate = '$td' and sccode='$sccode' and sessionyear LIKE '%$sy%' and classname = '$classname' and sectionname='$sectionname' order by rollno";
        $result00gt = $conn->query($sql00);
        if ($result00gt->num_rows > 0) {
            while ($row00 = $result00gt->fetch_assoc()) { $datam[] = $row00; }
        }
    ?>
    <div id="clssecblock<?php echo $h2; ?>" style="display:<?php echo $ddss; ?>; margin-top: 10px;">
        <div class="widget-grid">
            <?php
            $cnt = $absent_cnt = $bunk_cnt = 0;
            $sql0 = "SELECT * FROM sessioninfo where sessionyear LIKE '%$sy%' and sccode='$sccode' and classname='$classname' and sectionname = '$sectionname' order by rollno";
            $result0 = $conn->query($sql0);
            
            if ($result0->num_rows > 0) {
                while ($row0 = $result0->fetch_assoc()) {
                    $stid = $row0["stid"];
                    $rollno = $row0["rollno"];
                    $grname = $row0["groupname"];
                    $grnametxt = ($classname == 'Six' || $classname == 'Seven') ? " • $grname" : "";

                    $pth = student_profile_image_path($stid);
                    $st_ind = array_search($stid, array_column($datam_st_profile, 'stid'));
                    $neng = $datam_st_profile[$st_ind]["stnameeng"];
                    $nben = $datam_st_profile[$st_ind]["stnameben"];
                    $guarmobile = $datam_st_profile[$st_ind]["guarmobile"];

                    $status = 0; $bunk = 0;
                    $st_att_ind = array_search($stid, array_column($datam, 'stid'));
                    if ($st_att_ind !== false) {
                        $status = $datam[$st_att_ind]["yn"];
                        $bunk = $datam[$st_att_ind]["bunk"];
                    }

                    if ($status == 0 || $bunk == 1) {
                        $cnt++;
                        $card_class = ($bunk == 1) ? 'st-bunk' : 'st-absent';
                        if ($status == 0) $absent_cnt++;
                        if ($bunk == 1) $bunk_cnt++;
            ?>
                <div class="m3-list-item <?php echo $card_class; ?>" id="block<?php echo $stid; ?>" style="flex-direction: column; align-items: stretch; padding: 0;" onclick="show_extra(<?php echo $stid; ?>)">
                    <div style="display: flex; align-items: center; padding: 12px;">
                        <div class="icon-box" style="width: 52px; height: 52px; margin-right: 14px; background: #fff; border: 1px solid rgba(0,0,0,0.05);">
                            <img src="<?php echo $pth; ?>" style="width: 100%; height: 100%; border-radius: 8px; object-fit: cover;" />
                        </div>

                        <div class="item-info">
                            <div class="st-title" style="font-size: 0.95rem; font-weight: 800;"><?php echo $neng; ?></div>
                            <div class="st-desc" style="color: #444; font-weight: 600; font-size: 0.85rem;"><?php echo $nben; ?></div>
                            <div style="font-size: 0.65rem; color: #777; font-weight: 700;">ID: <?php echo $stid . $grnametxt; ?></div>
                            <div style="font-size: 0.65rem; font-weight: 800; margin-top: 2px;">
                                <?php echo ($bunk == 1) ? '<span style="color:#E65100;">● BUNKED CLASS</span>' : '<span style="color:#B3261E;">● ABSENT TODAY</span>'; ?>
                            </div>
                        </div>

                        <div style="min-width: 50px; text-align: right;">
                            <div style="font-size: 0.6rem; font-weight: 800; color: #777; line-height: 1;">ROLL</div>
                            <div style="font-size: 1.6rem; font-weight: 900; color: var(--m3-primary); line-height: 1;"><?php echo $rollno; ?></div>
                        </div>
                    </div>

                    <div id="extra<?php echo $stid; ?>" style="display: none; padding: 12px; background: rgba(0,0,0,0.03); border-top: 1px dashed rgba(0,0,0,0.1);">
                        <div style="display: flex; justify-content: space-around;">
                            <button class="tonal-icon-btn c-info" onclick="event.stopPropagation(); send_absent_notice(<?php echo $stid; ?>, 0, '<?php echo $guarmobile; ?>');"><i class="bi bi-telephone-fill"></i></button>
                            <button class="tonal-icon-btn" style="background:#FFEBEE; color:#B3261E;" onclick="event.stopPropagation(); send_absent_notice(<?php echo $stid; ?>, 1, '<?php echo $guarmobile; ?>');"><i class="bi bi-chat-left-text-fill"></i></button>
                            <button class="tonal-icon-btn" style="background:#FFF3E0; color:#E65100;" onclick="event.stopPropagation(); send_absent_notice(<?php echo $stid; ?>, 2, '<?php echo $guarmobile; ?>');"><i class="bi bi-bell-fill"></i></button>
                            <button class="tonal-icon-btn" style="background:#E1F5FE; color:#0288D1;" onclick="event.stopPropagation(); send_absent_notice(<?php echo $stid; ?>, 3);"><i class="bi bi-envelope-at-fill"></i></button>
                            <button class="tonal-icon-btn" style="background:#E8F5E9; color:#2E7D32;" onclick="event.stopPropagation(); go(<?php echo $stid; ?>);"><i class="bi bi-file-text-fill"></i></button>
                        </div>
                    </div>
                </div>
            <?php
                    }
                }
            }
            $present = $cnt - $absent_cnt; // This count needs careful check based on your logic
            ?>
        </div>
    </div>
    <script>
        document.getElementById("cnt<?php echo $h2; ?>").innerHTML = "<?php echo $cnt; ?>";
        document.getElementById("cnt_abs<?php echo $h2; ?>").innerHTML = "<?php echo $absent_cnt; ?>";
        document.getElementById("cnt_bunk<?php echo $h2; ?>").innerHTML = "<?php echo $bunk_cnt; ?>";
        document.getElementById("cnt_pre<?php echo $h2; ?>").innerHTML = "<?php echo $present; ?>";
    </script>
    <?php } ?>
</main>

<div style="height:80px;"></div>

<?php include 'footer.php'; ?>
<script>
    // ক্লাসভিত্তিক সেকশন এবং হিরো সামারি সুইচ করা
    function myclass(cur, mot) {
        for (var i = 0; i < mot; i++) {
            document.getElementById('clssecblock' + i).style.display = 'none';
            document.getElementById('hero_summary_' + i).style.display = 'none';
            document.getElementById('btn' + i).classList.remove("active");
        }
        document.getElementById('clssecblock' + cur).style.display = 'block';
        document.getElementById('hero_summary_' + cur).style.display = 'block';
        document.getElementById('btn' + cur).classList.add("active");
    }

    function show_extra(id) {
        var elem = document.getElementById("extra" + id);
        elem.style.display = (elem.style.display === 'block') ? 'none' : 'block';
    }

    function go(id) { window.location.href = "student.php?id=" + id; }
</script>