<?php
include 'inc.php';
include 'datam/datam-stprofile.php';

$extra = $h2 = 0;
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

$cnt = 0;
$stslist = array();
$sql0 = "SELECT * FROM sessioninfo where sessionyear LIKE '%$sessionyear_param%'  and sccode='$sccode' and classname='$classname' and sectionname = '$sectionname' order by rollno";
$result0 = $conn->query($sql0);
if ($result0->num_rows > 0) {
    while ($row0 = $result0->fetch_assoc()) {
        $stslist[] = $row0;
    }
}
$cnt = count($stslist);
?>

<main>
    <div class="container-fluidx">
        <div class="hero-container">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span class="session-pill" style="background: rgba(255,255,255,0.2); color: #fff; border:none;">
                        CLASS <?php echo strtoupper($classname); ?>
                    </span>
                    <div style="font-size: 1.6rem; font-weight: 900; margin-top: 8px; letter-spacing: -0.5px;">
                        Section: <?php echo strtoupper($sectionname); ?>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div id="cnt<?php echo $h2; ?>" style="font-size: 2.5rem; font-weight: 900; line-height: 1;">0</div>
                    <div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; opacity: 0.9;">Total Students</div>
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; align-items: center; justify-content: space-between; background: rgba(0,0,0,0.12); padding: 10px 16px; border-radius: 12px; backdrop-filter: blur(10px);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-sliders" style="font-size: 1.1rem;"></i>
                    <span style="font-size: 0.8rem; font-weight: 700;">ADVANCED SETTINGS</span>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="myswitch" onclick="more();">
                </div>
            </div>
        </div>

        <div class="widget-grid" style="margin-top: 12px;">
            <?php
            foreach ($stslist as $stdata) {
                $stid = $stdata["stid"];
                $rollno = $stdata["rollno"];
                $dtid = $stdata["id"];
                $status = $stdata["status"];
                $rel = $stdata["religion"];
                $four = $stdata["fourth_subject"];
                $grname = $stdata["groupname"];

                $grnametxt = ($classname == 'Six' || $classname == 'Seven') ? " | <b>$grname</b>" : "";

                $pth = student_profile_image_path($stid);
                $st_ind = array_search($stid, array_column($datam_st_profile, 'stid'));
                $neng = $datam_st_profile[$st_ind]["stnameeng"];
                $nben = $datam_st_profile[$st_ind]["stnameben"];
                $vill = $datam_st_profile[$st_ind]["previll"];
                $guarmobile = $datam_st_profile[$st_ind]["guarmobile"];

                $is_absent = ($status == 0);
                $bg_style = $is_absent ? 'style="opacity: 0.75;"' : '';
                $gip = $is_absent ? '' : 'checked';
            ?>

            <div class="m3-list-item" id="block<?php echo $stid; ?>" <?php echo $bg_style; ?> style="flex-direction: column; align-items: stretch; padding: 0; overflow: hidden; margin-bottom: 8px;">
                
                <div style="display: flex; align-items: center; padding: 12px;" onclick="show_extra(<?php echo $stid; ?>)">
                    
                    <div class="icon-box" style="width: 56px; height: 56px; margin-right: 14px; background: #f0f0f0; border: 1px solid #eee;">
                        <img src="<?php echo $pth; ?>" style="width: 100%; height: 100%; border-radius: 8px; object-fit: cover;" />
                    </div>

                    <div class="item-info" style="flex-grow: 1;">
                        <div class="st-title" style="font-size: 0.95rem; font-weight: 800; line-height: 1.2;"><?php echo $neng; ?></div>
                        <div class="st-desc" style="color: #444; font-weight: 600; font-size: 0.85rem;"><?php echo $nben; ?></div>
                        <div style="font-size: 0.65rem; color: var(--m3-outline); font-weight: 700; margin-top: 2px;">
                            ID: <?php echo $stid . $grnametxt; ?> • <?php echo $vill; ?>
                        </div>
                        <?php if($is_absent): ?>
                            <span style="font-size: 0.6rem; font-weight: 900; color: #B3261E; letter-spacing: 0.5px;">● ABSENT</span>
                        <?php endif; ?>
                    </div>

                    <div style="min-width: 50px; text-align: right;">
                        <div style="font-size: 0.6rem; font-weight: 800; color: var(--m3-outline); line-height: 1;">ROLL</div>
                        <div style="font-size: 1.6rem; font-weight: 900; color: var(--m3-primary); line-height: 1;"><?php echo $rollno; ?></div>
                    </div>
                </div>

                <div id="extra<?php echo $stid; ?>" style="display: none; padding: 12px; background: var(--m3-tonal-surface); border-top: 1px dashed var(--m3-tonal-container);">
                    <div style="display: flex; justify-content: space-around;">
                        <button class="tonal-icon-btn c-info" onclick="send_absent_notice(<?php echo $stid; ?>, 0, '<?php echo $guarmobile; ?>');"><i class="bi bi-telephone-fill"></i></button>
                        <button class="tonal-icon-btn" style="background:#FFEBEE; color:#B3261E;" onclick="send_absent_notice(<?php echo $stid; ?>, 1, '<?php echo $guarmobile; ?>');"><i class="bi bi-chat-left-text-fill"></i></button>
                        <button class="tonal-icon-btn" style="background:#FFF3E0; color:#E65100;" onclick="send_absent_notice(<?php echo $stid; ?>, 2, '<?php echo $guarmobile; ?>');"><i class="bi bi-bell-fill"></i></button>
                        <button class="tonal-icon-btn" style="background:#E1F5FE; color:#0288D1;" onclick="send_absent_notice(<?php echo $stid; ?>, 3);"><i class="bi bi-envelope-at-fill"></i></button>
                        <button class="tonal-icon-btn" style="background:#E8F5E9; color:#2E7D32;" onclick="go(<?php echo $stid; ?>);"><i class="bi bi-person-bounding-box"></i></button>
                    </div>
                </div>

                <div class="sele" id="blocksel<?php echo $dtid; ?>" style="display: none; padding: 16px; background: #fff; border-top: 1px solid #f0f0f0;">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="m3-floating-group">
                                <select class="m3-select-floating" id="sel<?php echo $dtid; ?>" onchange="grp(<?php echo $dtid; ?>);">
                                    <option value=""></option>
                                    <?php
                                    if ($classname == 'Six' || $classname == 'Seven' || $classname == 'Eight' || $classname == 'Nine') {
                                        $sql00g = "SELECT * FROM pibigroup where sccode='$sccode' and classname='$classname' and sectionname = '$sectionname' order by id";
                                        $result00g = $conn->query($sql00g);
                                        if ($result00g->num_rows > 0) {
                                            while ($row00g = $result00g->fetch_assoc()) {
                                                $ggg = $row00g["groupname"];
                                                $chk = ($ggg == $grname) ? "selected" : "";
                                                echo '<option value="' . $ggg . '" ' . $chk . '>' . $ggg . '</option>';
                                            }
                                        }
                                    } else {
                                        $sql00g = "SELECT * FROM subjects where fourth=1 order by subcode";
                                        $result00g = $conn->query($sql00g);
                                        if ($result00g->num_rows > 0) {
                                            while ($row00g = $result00g->fetch_assoc()) {
                                                $ggg = $row00g["subcode"]; $gggx = $row00g["subject"];
                                                $chk = ($ggg == $four) ? "selected" : "";
                                                echo '<option value="' . $ggg . '" ' . $chk . '>' . $gggx . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <label class="m3-floating-label">GROUP/SUB</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="m3-floating-group">
                                <select class="m3-select-floating" id="rel<?php echo $stid; ?>" onchange="grps(<?php echo $stid; ?>);">
                                    <option value="" <?php echo ($rel == '') ? 'selected' : ''; ?>></option>
                                    <option value="Islam" <?php echo ($rel == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                                    <option value="Hindu" <?php echo ($rel == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
                                    <option value="Christian" <?php echo ($rel == 'Christian') ? 'selected' : ''; ?>>Christian</option>
                                    <option value="Buddist" <?php echo ($rel == 'Buddist') ? 'selected' : ''; ?>>Buddist</option>
                                </select>
                                <label class="m3-floating-label">RELIGION</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div style="display: flex; align-items: center; justify-content: space-between; background: var(--m3-tonal-container); padding: 12px; border-radius: 8px;">
                                <span style="font-size: 0.8rem; font-weight: 800; color: var(--m3-on-tonal-container);">STUDENT IS PRESENT</span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sta<?php echo $stid; ?>" onchange="grpss(<?php echo $stid; ?>);" <?php echo $gip; ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="upd<?php echo $stid; ?>" style="text-align:center; font-size:10px; margin-top:8px;"></div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</main>

<div style="height:80px;"></div>

<?php include_once 'footer.php'; ?>
<script>
    // জাভাস্ক্রিপ্ট লজিক
    function show_extra(id) {
        document.querySelectorAll('[id^="extra"]').forEach(el => {
            if (el.id !== "extra" + id) el.style.display = "none";
        });
        let el = document.getElementById("extra" + id);
        el.style.display = (el.style.display === "none") ? "block" : "none";
    }

    function more() {
        let val = document.getElementById("myswitch").checked;
        $(".sele").css("display", val ? "block" : "none");
    }



    // (বাকি AJAX ফাংশনগুলো আগের মতোই থাকবে)
    document.getElementById("cnt<?php echo $h2; ?>").innerHTML = "<?php echo $cnt; ?>";
</script>