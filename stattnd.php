<?php
include 'inc.php'; 
include 'datam/datam-stprofile.php';

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

// ২. প্যারামিটার হ্যান্ডলিং
$classname = $_GET['cls'] ?? '';
$sectionname = $_GET['sec'] ?? '';
$td = $_GET['dt'] ?? date('Y-m-d');
$page_title = "Class Attendance";

// ৩. বর্তমান পিরিয়ড নির্ধারণ (Prepared Statement)
$ccur = date('H:i:s');
$period = 1;
$stmt_sc = $conn->prepare("SELECT period FROM classschedule WHERE sccode = ? AND sessionyear LIKE ? AND timestart <= ? AND timeend >= ? LIMIT 1");
$stmt_sc->bind_param("ssss", $sccode, $sy_param, $ccur, $ccur);
$stmt_sc->execute();
$res_sc = $stmt_sc->get_result();
if ($r = $res_sc->fetch_assoc()) { $period = $r["period"]; }
$stmt_sc->close();

// ৪. আজকের উপস্থিতির ডাটা লোড করা
$datam = [];
$stmt_att = $conn->prepare("SELECT * FROM stattnd WHERE adate = ? AND sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ?");
$stmt_att->bind_param("sssss", $td, $sccode, $sy_param, $classname, $sectionname);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
while($r = $res_att->fetch_assoc()) { $datam[$r['stid']] = $r; }
$stmt_att->close();

// ৫. গত ৭ দিনের হিস্ট্রি (History Dots)
$from_date = date("Y-m-d", strtotime("-7 days"));
$hist_map = [];
$stmt_h = $conn->prepare("SELECT stid, adate, yn, bunk FROM stattnd WHERE adate BETWEEN ? AND ? AND sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ?");
$stmt_h->bind_param("ssssss", $from_date, $td, $sccode, $sy_param, $classname, $sectionname);
$stmt_h->execute();
$res_h = $stmt_h->get_result();
while($r = $res_h->fetch_assoc()) { $hist_map[$r['stid']][$r['adate']] = $r; }
$stmt_h->close();

// ৬. সাবমিশন স্ট্যাটাস চেক
$subm = 0;
$stmt_sum = $conn->prepare("SELECT attndrate FROM stattndsummery WHERE date = ? AND sccode = ? AND sessionyear = ? AND classname = ? AND sectionname = ?");
$stmt_sum->bind_param("sssss", $td, $sccode, $current_session, $classname, $sectionname);
$stmt_sum->execute();
if ($stmt_sum->get_result()->num_rows > 0) { $subm = 1; }
$stmt_sum->close();

$fun = ($subm == 1) ? 'grpssx0' : (($period >= 2) ? 'grpssx2' : 'grpssx');
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.85rem; }

    /* M3 Components (8px Radius) */
    .m3-card { background: #fff; border-radius: 8px; padding: 12px; margin: 0 8px 8px; border: 1px solid #eee; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
    .hero-stats { 
        background: <?php echo ($subm == 1) ? '#B3261E' : '#6750A4'; ?>; 
        color: #fff; border-radius: 0 0 8px 8px; padding: 16px; margin-bottom: 12px; 
    }
    
    /* Compact Student Card */
    .att-row { 
        display: flex; align-items: center; padding: 8px 12px; background: #fff; 
        border-radius: 8px; margin: 0 8px 6px; border: 1px solid #f0f0f0; transition: 0.2s; 
    }
    .att-row.present { background-color: #fff; border-left: 4px solid #146C32; }
    .att-row.absent { background-color: #F7F2FA; opacity: 0.7; border-left: 4px solid #eee; }

    .st-avatar-tiny { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; background: #eee; margin-right: 12px; }
    
    /* Attendance Dots */
    .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 2px; }
    .bg-p { background: #4CAF50; } .bg-a { background: #F44336; } .bg-b { background: #FF9800; } .bg-g { background: #eee; }

    /* Checkbox M3 */
    .m3-check { width: 22px; height: 22px; border-radius: 4px; border: 2px solid #6750A4; margin-right: 12px; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="student-list.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <div class="page-title"><?php echo "$classname ($sectionname)"; ?></div>
    <div class="text-end fw-bold text-primary">P-<?php echo $period; ?></div>
</header>

<main class="pb-5">
    <div class="hero-stats shadow-sm">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="h3 fw-bold mb-0"><span id="att_count">0</span>/<span id="total_count">0</span></div>
                <div class="small opacity-80">Present Students</div>
                <?php if($subm == 1): ?>
                    <span class="badge bg-white text-danger mt-2" style="font-size: 0.6rem;">SUBMITTED</span>
                <?php endif; ?>
            </div>
            <div class="text-end">
                <input type="date" id="xp" class="form-control form-control-sm border-0 bg-white text-dark fw-bold mb-1" 
                       style="border-radius: 6px; width: 130px;" value="<?php echo $td; ?>" onchange="dtcng();" 
                       <?php if($period > 1) echo 'disabled'; ?>>
                <div class="small opacity-75">Bunk: <span id="bunk_count" class="fw-bold">0</span></div>
            </div>
        </div>
    </div>

    <div class="list-container px-1">
        <?php
        $total = 0; $present = 0; $bunks = 0;
        $stmt_st = $conn->prepare("SELECT stid, rollno FROM sessioninfo WHERE sessionyear LIKE ? AND sccode = ? AND classname = ? AND sectionname = ? AND status='1' ORDER BY rollno ASC");
        $stmt_st->bind_param("ssss", $sy_param, $sccode, $classname, $sectionname);
        $stmt_st->execute();
        $res_st = $stmt_st->get_result();

        while ($row = $res_st->fetch_assoc()):
            $stid = $row["stid"];
            $roll = $row["rollno"];
            $total++;

            $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
            $neng = $datam_st_profile[$st_idx]["stnameeng"] ?? 'Unknown';
            
            $att = $datam[$stid] ?? null;
            $is_p = ($att && $att['yn'] == 1);
            $has_bunked = ($att && $att['bunk'] == 1);
            
            if ($is_p && !$has_bunked) $present++;
            if ($has_bunked) $bunks++;
        ?>
            <div class="att-row shadow-sm <?php echo ($is_p && !$has_bunked) ? 'present' : 'absent'; ?>" 
                 id="block_<?php echo $stid; ?>" onclick="<?php echo $fun; ?>('<?php echo $stid; ?>', '<?php echo $roll; ?>', <?php echo (int)$has_bunked; ?>)">
                
                <input type="checkbox" class="form-check-input m3-check" id="chk_<?php echo $stid; ?>" <?php echo $is_p ? 'checked' : ''; ?> disabled>
                
                <img src="https://eimbox.com/students/<?php echo $stid; ?>.jpg" class="st-avatar-tiny" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;"><?php echo $neng; ?></div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-extrabold text-primary" style="font-size: 0.75rem;">Roll: <?php echo $roll; ?></span>
                        <div class="ms-1">
                            <?php 
                            for ($i = 6; $i >= 0; $i--) {
                                $cd = date('Y-m-d', strtotime("-$i days", strtotime($td)));
                                $h = $hist_map[$stid][$cd] ?? null;
                                $c = 'bg-g';
                                if($h) $c = ($h['yn'] == 1) ? ($h['bunk'] == 1 ? 'bg-b' : 'bg-p') : 'bg-a';
                                echo "<span class='dot $c'></span>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="ms-2" id="sync_<?php echo $stid; ?>"><i class="bi bi-check2-circle opacity-25"></i></div>
            </div>
        <?php endwhile; $stmt_st->close(); ?>
    </div>

    <?php if ($subm == 0): ?>
        <div class="fixed-bottom p-3 bg-white border-top shadow-lg d-grid" style="bottom: 56px; border-radius: 12px 12px 0 0;">
            <button class="btn btn-danger btn-lg fw-bold" style="border-radius: 8px;" onclick="submitFinal();">
                <i class="bi bi-cloud-upload me-2"></i> SUBMIT ATTENDANCE
            </button>
        </div>
    <?php endif; ?>
</main>

<script>
    document.getElementById("att_count").innerText = "<?php echo $present; ?>";
    document.getElementById("total_count").innerText = "<?php echo $total; ?>";
    document.getElementById("bunk_count").innerText = "<?php echo $bunks; ?>";

    function dtcng() {
        const d = document.getElementById("xp").value;
        window.location.href = `stattnd.php?cls=<?php echo urlencode($classname); ?>&sec=<?php echo urlencode($sectionname); ?>&dt=${d}&year=<?php echo $current_session; ?>`;
    }

    function toggleAtt(id, roll, per) {
        const chk = document.getElementById("chk_" + id);
        const card = document.getElementById("block_" + id);
        const sync = document.getElementById("sync_" + id);
        let count = parseInt(document.getElementById("att_count").innerText);

        chk.checked = !chk.checked;
        if(chk.checked) { count++; card.classList.replace('absent', 'present'); }
        else { count--; card.classList.replace('present', 'absent'); }
        document.getElementById("att_count").innerText = count;

        sync.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div>';
        $.post('backend/save-st-attnd.php', { 
            stid: id, roll: roll, val: chk.checked ? 1 : 0, opt: 2, 
            cls: '<?php echo $classname; ?>', sec: '<?php echo $sectionname; ?>', 
            per: per, adate: '<?php echo $td; ?>' 
        }, function() {
            sync.innerHTML = '<i class="bi bi-check-all text-success fs-5"></i>';
        });
    }

    function grpssx(id, roll, bunk) {
        if(bunk == 1) { Swal.fire('Already Bunked', '', 'warning'); return; }
        toggleAtt(id, roll, 1);
    }

    function grpssx2(id, roll, bunk) {
        if(bunk == 1) { Swal.fire('Already Bunked', '', 'warning'); return; }
        toggleAtt(id, roll, <?php echo $period; ?>);
    }

    function submitFinal() {
        Swal.fire({
            title: 'Confirm Submission?',
            text: 'You cannot change attendance after submission.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6750A4'
        }).then((result) => {
            if (result.isConfirmed) {
                const fnd = document.getElementById("att_count").innerText;
                const cnt = document.getElementById("total_count").innerText;
                $.post('backend/save-st-attnd.php', { 
                    cnt: cnt, fnd: fnd, opt: 5, 
                    cls: '<?php echo $classname; ?>', sec: '<?php echo $sectionname; ?>', 
                    adate: '<?php echo $td; ?>' 
                }, function() { location.reload(); });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>