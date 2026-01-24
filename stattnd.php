<?php
include 'inc.php'; 
include 'datam/datam-stprofile.php';

// ২. প্যারামিটার হ্যান্ডলিং
$classname = $_GET['cls'] ?? '';
$sectionname = $_GET['sec'] ?? '';
$td = $_GET['dt'] ?? date('Y-m-d');
$current_session = $_GET['year'] ?? $sy;
$sessionyear_param = '%' . $current_session . '%';
$page_title = "Class Attendance";

// ৩. বর্তমান পিরিয়ড নির্ধারণ (Prepared Statement)
$ccur = date('H:i:s');
$period = 1;
$stmt_sc = $conn->prepare("SELECT period FROM classschedule WHERE sccode = ? AND sessionyear LIKE ? AND timestart <= ? AND timeend >= ? LIMIT 1");
$stmt_sc->bind_param("ssss", $sccode, $sessionyear_param, $ccur, $ccur);
$stmt_sc->execute();
$res_sc = $stmt_sc->get_result();
if ($r = $res_sc->fetch_assoc()) { $period = $r["period"]; }
$stmt_sc->close();

// ৪. আজকের উপস্থিতির ডাটা লোড করা
$datam = [];
$stmt_att = $conn->prepare("SELECT * FROM stattnd WHERE adate = ? AND sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ?");
$stmt_att->bind_param("sssss", $td, $sccode, $sessionyear_param, $classname, $sectionname);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
while($r = $res_att->fetch_assoc()) { $datam[$r['stid']] = $r; }
$stmt_att->close();

// ৫. গত ৭ দিনের হিস্ট্রি (History Dots)
$from_date = date("Y-m-d", strtotime("-7 days", strtotime($td)));
$hist_map = [];
$stmt_h = $conn->prepare("SELECT stid, adate, yn, bunk FROM stattnd WHERE adate BETWEEN ? AND ? AND sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ?");
$stmt_h->bind_param("ssssss", $from_date, $td, $sccode, $sessionyear_param, $classname, $sectionname);
$stmt_h->execute();
$res_h = $stmt_h->get_result();
while($r = $res_h->fetch_assoc()) { $hist_map[$r['stid']][$r['adate']] = $r; }
$stmt_h->close();

// ৬. সাবমিশন স্ট্যাটাস চেক
$subm = 0;
$stmt_sum = $conn->prepare("SELECT attndrate FROM stattndsummery WHERE date = ? AND sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ?");
$stmt_sum->bind_param("sssss", $td, $sccode, $sessionyear_param, $classname, $sectionname);
$stmt_sum->execute();
if ($stmt_sum->get_result()->num_rows > 0) { $subm = 1; }
$stmt_sum->close();

if($subm == 1 && $period < 2) $period = 2;
$fun = ($subm == 1) ? 'grpssx0' : (($period >= 2) ? 'grpssx2' : 'grpssx');
?>

<style>
    /* Attendance Specific M3 Enhancements */
    .hero-container { padding-bottom: 30px; margin-bottom: 0; border-radius: 0 0 24px 24px; }
    
    .stats-card-overlay {
        margin: -25px 16px 16px;
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
    }

    .att-item-card {
        background: #fff;
        border-radius: var(--m3-radius) !important;
        margin: 0 12px 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        border: 1px solid #f0f0f0;
        transition: transform 0.1s;
    }
    .att-item-card.present { border-left: 5px solid #4CAF50; background: #fff; }
    .att-item-card.absent { border-left: 5px solid #eee; background: #fafafa; opacity: 0.8; }
    .att-item-card:active { transform: scale(0.98); }

    /* M3 Custom Checkbox Look */
    .m3-checkbox-box {
        width: 24px; height: 24px;
        border-radius: 6px;
        border: 2px solid var(--m3-primary);
        margin-right: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }
    .present .m3-checkbox-box { background: var(--m3-primary); border-color: var(--m3-primary); }
    .present .m3-checkbox-box::after {
        content: '\F26E'; font-family: 'bootstrap-icons';
        color: white; font-size: 14px; font-weight: 900;
    }

    /* History Dots */
    .dot-box { display: flex; gap: 3px; margin-top: 4px; }
    .dot { width: 7px; height: 7px; border-radius: 50%; }
    .dot-p { background: #4CAF50; } 
    .dot-a { background: #F44336; } 
    .dot-b { background: #FF9800; } 
    .dot-g { background: #EADDFF; }

    .submit-bar {
        position: fixed; bottom: 64px; left: 0; right: 0;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        padding: 12px 16px;
        border-top: 1px solid #eee;
        z-index: 1000;
    }
</style>

<main>
    <div class="hero-container" style="<?php echo ($subm == 1) ? 'background: linear-gradient(135deg, #B3261E 0%, #E53935 100%);' : ''; ?>">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff;" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </div>
                <div>
                    <div style="font-size: 1.3rem; font-weight: 900; line-height: 1;"><?php echo "$classname ($sectionname)"; ?></div>
                    <div style="font-size: 0.75rem; opacity: 0.8; font-weight: 700; margin-top: 4px;">
                        <i class="bi bi-clock-history"></i> Period: <?php echo $period; ?>
                    </div>
                </div>
            </div>
            <div style="text-align: right;">
                <input type="date" id="xp" class="form-control form-control-sm" 
                       style="border-radius: 8px; border: none; font-weight: 800; font-size: 0.8rem; width: 135px;" 
                       value="<?php echo $td; ?>" onchange="dtcng();" <?php if($period > 1) echo 'disabled'; ?>>
            </div>
        </div>
    </div>

    <div class="stats-card-overlay shadow-sm">
        <div style="text-align: center; flex: 1; border-right: 1px solid #eee;">
            <div style="font-size: 1.5rem; font-weight: 900; color: var(--m3-primary);" id="att_count">0</div>
            <div style="font-size: 0.65rem; font-weight: 800; color: #777; text-transform: uppercase;">Present</div>
        </div>
        <div style="text-align: center; flex: 1; border-right: 1px solid #eee;">
            <div style="font-size: 1.5rem; font-weight: 900; color: #B3261E;" id="bunk_count">0</div>
            <div style="font-size: 0.65rem; font-weight: 800; color: #777; text-transform: uppercase;">Bunked</div>
        </div>
        <div style="text-align: center; flex: 1;">
            <div style="font-size: 1.5rem; font-weight: 900; color: #1C1B1F;" id="total_count">0</div>
            <div style="font-size: 0.65rem; font-weight: 800; color: #777; text-transform: uppercase;">Total</div>
        </div>
    </div>

    <div class="widget-grid" style="padding-bottom: 120px;">
        <?php
        $total = 0; $present = 0; $bunks = 0;
        $stmt_st = $conn->prepare("SELECT stid, rollno FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND status='1' ORDER BY rollno ASC");
        $stmt_st->bind_param("ssss", $sessionyear_param, $sccode, $classname, $sectionname);
        $stmt_st->execute();
        $res_st = $stmt_st->get_result();

        while ($row = $res_st->fetch_assoc()):
            $stid = $row["stid"];
            $roll = $row["rollno"];
            $total++;

            $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
            $neng = $datam_st_profile[$st_idx]["stnameeng"] ?? 'ID: '.$stid;
            
            $att = $datam[$stid] ?? null;
            $is_p = ($att && $att['yn'] == 1);
            $has_bunked = ($att && $att['bunk'] == 1);
            
            if ($is_p && !$has_bunked) $present++;
            if ($has_bunked) $bunks++;
        ?>
            <div class="att-item-card shadow-sm <?php echo ($is_p && !$has_bunked) ? 'present' : 'absent'; ?>" 
                 id="block_<?php echo $stid; ?>" onclick="<?php echo $fun; ?>('<?php echo $stid; ?>', '<?php echo $roll; ?>', <?php echo (int)$has_bunked; ?>)">
                
                <div class="m3-checkbox-box" id="box_<?php echo $stid; ?>"></div>
                <input type="checkbox" id="chk_<?php echo $stid; ?>" <?php echo $is_p ? 'checked' : ''; ?> hidden>
                
                <img src="<?php student_profile_image_path($stid); ?>" class="st-avatar-tiny" 
                     style="width: 44px; height: 44px; border-radius: 8px; margin-right: 12px; object-fit: cover;"
                     onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                
                <div style="flex-grow: 1; overflow: hidden;">
                    <div style="font-size: 0.9rem; font-weight: 800; color: #1C1B1F; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?php echo $neng; ?>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: var(--m3-primary);">ROLL: <?php echo $roll; ?></span>
                        <div class="dot-box">
                            <?php 
                            for ($i = 6; $i >= 0; $i--) {
                                $cd = date('Y-m-d', strtotime("-$i days", strtotime($td)));
                                $h = $hist_map[$stid][$cd] ?? null;
                                $dot_c = 'dot-g';
                                if($h) $dot_c = ($h['yn'] == 1) ? ($h['bunk'] == 1 ? 'dot-b' : 'dot-p') : 'dot-a';
                                echo "<span class='dot $dot_c'></span>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div id="sync_<?php echo $stid; ?>" style="margin-left: 8px;">
                    <i class="bi bi-cloud-check" style="opacity: 0.2; font-size: 1.2rem;"></i>
                </div>
            </div>
        <?php endwhile; $stmt_st->close(); ?>
    </div>

    <?php if ($subm == 0): ?>
        <div class="submit-bar shadow-lg">
            <button class="btn-m3-submit" style="margin: 0; width: 100%; height: 56px;" onclick="submitFinal();">
                <i class="bi bi-cloud-arrow-up-fill" style="font-size: 1.3rem;"></i> 
                <span>SUBMIT FINAL ATTENDANCE</span>
            </button>
        </div>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>

<script>
    // স্ক্রিপ্ট ডাটা ইনিশিয়ালাইজেশন
    document.getElementById("att_count").innerText = "<?php echo $present; ?>";
    document.getElementById("total_count").innerText = "<?php echo $total; ?>";
    document.getElementById("bunk_count").innerText = "<?php echo $bunks; ?>";

    function dtcng() {
        const d = document.getElementById("xp").value;
        window.location.href = `stattnd.php?cls=<?php echo urlencode($classname); ?>&sec=<?php echo urlencode($sectionname); ?>&dt=${d}&year=<?php echo $current_session; ?>`;
    }

    let attLock = false;

    function toggleAtt(id, roll, per) {
        if (attLock) return;
        attLock = true;

        const chk = document.getElementById("chk_" + id);
        const card = document.getElementById("block_" + id);
        const sync = document.getElementById("sync_" + id);
        const cnt = document.getElementById("att_count");

        if (!chk || !card || !sync || !cnt) { attLock = false; return; }

        chk.checked = !chk.checked;
        let count = parseInt(cnt.innerText);

        if (chk.checked) {
            count++;
            card.classList.add('present'); card.classList.remove('absent');
        } else {
            count--;
            card.classList.add('absent'); card.classList.remove('present');
        }
        cnt.innerText = count;

        sync.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" style="width: 1rem; height: 1rem;"></div>';

        fetch('backend/save-st-attnd.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                stid: id, roll: roll, val: chk.checked ? 1 : 0, opt: 2,
                cls: '<?= $classname ?>', sec: '<?= $sectionname ?>',
                per: per, adate: '<?= $td ?>'
            })
        })
        .then(res => res.text())
        .then(txt => {
            if (txt.trim() === "OK") {
                sync.innerHTML = '<i class="bi bi-check-circle-fill text-success" style="font-size: 1.2rem;"></i>';
            } else {
                sync.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-warning"></i>';
            }
            attLock = false;
        })
        .catch(err => {
            sync.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
            attLock = false;
        });
    }

    function submitFinal() {
        if (attLock) return;
        if (!confirm("Are you sure to submit final attendance?")) return;
        attLock = true;

        const cnt = document.getElementById("total_count").innerText;
        const fnd = document.getElementById("att_count").innerText;

        fetch('backend/save-st-attnd.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                opt: 5, cnt: cnt, fnd: fnd,
                cls: '<?= $classname ?>', sec: '<?= $sectionname ?>',
                adate: '<?= $td ?>', sy:'<?= $current_session ?>'
            })
        })
        .then(res => res.text())
        .then(txt => {
            if (txt.trim() === "SUBMITTED") {
                alert("Attendance Submitted!");
                history.back();
            } else {
                alert("Submission Failed: " + txt);
            }
            attLock = false;
        })
        .catch(err => {
            alert("Network Error.");
            attLock = false;
        });
    }

    // পিরিয়ড অনুযায়ী ফাংশন কল
    function grpssx(id, roll, bunk) { if(bunk == 1) return; toggleAtt(id, roll, 1); }
    function grpssx0(id, roll, bunk) { toggleAtt(id, roll, 1); }
    function grpssx2(id, roll, bunk) { if(bunk == 1) return; toggleAtt(id, roll, <?= $period ?>); }
</script>

