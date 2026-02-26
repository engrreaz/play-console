<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে



// ২. ইনপুট প্যারামিটার
$classname = $_GET['cls'] ?? '';
$sectionname = $_GET['sec'] ?? '';
$exam = $_GET['exam'] ?? '';
$subj = $_GET['sub'] ?? '';
$page_title = "Mark Entry";

// ৩. লক স্ট্যাটাস চেক (Prepared Statement)
$lock = 0;
$stmt_lock = $conn->prepare("SELECT halfdone, fulldone FROM areas WHERE areaname = ? AND subarea = ? AND user = ?");
$stmt_lock->bind_param("sss", $classname, $sectionname, $rootuser);
$stmt_lock->execute();
$res_lock = $stmt_lock->get_result();
if ($row = $res_lock->fetch_assoc()) {
    $lock = ($exam == 'Half Yearly') ? $row["halfdone"] : $row["fulldone"];
}
$stmt_lock->close();

// ৪. সাবজেক্ট এবং সেটআপ ডাটা ফেচিং
$sname = "";
$stmt_sub = $conn->prepare("SELECT subject FROM subjects WHERE subcode = ? AND sccategory = ? LIMIT 1");
$stmt_sub->bind_param("ss", $subj, $sctype);
$stmt_sub->execute();
if ($row = $stmt_sub->get_result()->fetch_assoc()) { $sname = $row["subject"]; }
$stmt_sub->close();

$fullmark = $subj_full = $obj_full = $pra_full = $ca_full = 0;
$stmt_setup = $conn->prepare("SELECT * FROM subsetup WHERE classname = ? AND sectionname = ? AND subject = ? AND sccode = ? LIMIT 1");
$stmt_setup->bind_param("ssss", $classname, $sectionname, $subj, $sccode);
$stmt_setup->execute();
$res_setup = $stmt_setup->get_result();
if ($row = $res_setup->fetch_assoc()) {
    $fullmark  = $row["fullmarks"];
    $subj_full = $row["subj"];
    $obj_full  = $row["obj"];
    $pra_full  = $row["pra"];
    $ca_full   = $row["ca"];
}
$stmt_setup->close();

// ৫. বিদ্যমান নম্বরগুলো অ্যারেতে নিয়ে আসা (Performance Optimization)
$existing_marks = [];
$stmt_m = $conn->prepare("SELECT * FROM stmark WHERE sccode = ? AND exam = ? AND classname = ? AND sectionname = ? AND sessionyear LIKE ? AND subject = ?");
$stmt_m->bind_param("ssssss", $sccode, $exam, $classname, $sectionname, $sessionyear_param, $subj);
$stmt_m->execute();
$res_m = $stmt_m->get_result();
while($row = $res_m->fetch_assoc()) { $existing_marks[$row['stid']] = $row; }
$stmt_m->close();
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.85rem; }

    /* M3 Standard App Bar (8px Bottom Radius) */
    .m3-app-bar {
        background: #fff; height: 56px; display: flex; align-items: center; padding: 0 16px;
        position: sticky; top: 0; z-index: 1050; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Distribution Summary Bar */
    .dist-bar { background: #F3EDF7; border-radius: 8px; padding: 8px 12px; margin: 8px; display: flex; justify-content: space-between; font-size: 0.7rem; font-weight: 700; color: #6750A4; }

    /* Condensed Mark Card (8px Radius) */
    .mark-card {
        background: #fff; border-radius: 8px; padding: 10px; margin: 0 8px 6px;
        border: 1px solid #eee; transition: 0.2s;
    }
    .mark-card.locked { opacity: 0.6; pointer-events: none; background: #f9f9f9; }
    .mark-card:focus-within { border-color: #6750A4; box-shadow: 0 2px 8px rgba(103, 80, 164, 0.1); }

    /* Compact Inputs */
    .m3-input {
        width: 100%; border: 1px solid #79747E; border-radius: 6px;
        padding: 6px 2px; text-align: center; font-weight: 800; font-size: 1rem; color: #1C1B1F;
    }
    .m3-input:focus { border-color: #6750A4; background: #F7F2FA; outline: none; }
    .input-label { font-size: 0.55rem; font-weight: 700; text-transform: uppercase; color: #49454F; text-align: center; margin-top: 2px; }

    .obt-box {
        background: #EADDFF; color: #21005D; border-radius: 6px;
        min-width: 45px; height: 45px; display: flex; flex-direction: column; 
        align-items: center; justify-content: center;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="markentryselect.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <div class="page-title text-truncate"><?php echo $sname; ?></div>
    <?php if($lock == 1): ?><i class="bi bi-lock-fill text-danger ms-2"></i><?php endif; ?>
</header>

<main class="pb-5 mt-2">
    <div class="dist-bar shadow-sm">
        <span>CA: <?php echo $ca_full; ?>%</span>
        <span>SUB: <?php echo $subj_full; ?></span>
        <span>OBJ: <?php echo $obj_full; ?></span>
        <span>PRA: <?php echo $pra_full; ?></span>
        <span class="text-dark">FULL: <?php echo $fullmark; ?></span>
    </div>

    <div class="list-container px-1">
        <?php
        // স্টুডেন্ট লিস্ট ফেচিং
        $stmt_st = $conn->prepare("SELECT s.stid, s.rollno, st.stnameeng, st.religion FROM sessioninfo s 
                                   JOIN students st ON s.stid = st.stid 
                                   WHERE s.sccode = ? AND s.classname = ? AND s.sectionname = ? AND s.sessionyear LIKE ? 
                                   ORDER BY s.rollno ASC");
        $stmt_st->bind_param("ssss", $sccode, $classname, $sectionname, $sessionyear_param);
        $stmt_st->execute();
        $res_st = $stmt_st->get_result();

        while ($row = $res_st->fetch_assoc()):
            $stid = $row['stid'];
            $is_eligible = true;
            if($row['religion'] == 'Islam' && $subj == 112) $is_eligible = false;
            if($row['religion'] == 'Hindu' && $subj == 111) $is_eligible = false;

            $m = $existing_marks[$stid] ?? null;
            $obt_val = $m['markobt'] ?? '0';
        ?>
            <div class="mark-card shadow-sm <?php echo (!$is_eligible || $lock == 1) ? 'locked' : ''; ?>">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center overflow-hidden">
                        <span class="badge bg-primary rounded-pill me-2"><?php echo $row['rollno']; ?></span>
                        <div class="fw-bold text-dark text-truncate small"><?php echo $row['stnameeng']; ?></div>
                    </div>
                    <div id="sync-<?php echo $stid; ?>"><i class="bi bi-check2-circle opacity-25"></i></div>
                </div>

                <div class="row gx-2 align-items-center">
                    <?php if($ca_full > 0): ?>
                    <div class="col">
                        <input type="number" id="ca-<?php echo $stid; ?>" class="m3-input" value="<?php echo $m['ca'] ?? ''; ?>" onblur="saveMark('<?php echo $stid; ?>', 'ca')">
                        <div class="input-label">CA</div>
                    </div>
                    <?php endif; ?>

                    <?php if($subj_full > 0): ?>
                    <div class="col">
                        <input type="number" id="sub-<?php echo $stid; ?>" class="m3-input" value="<?php echo $m['subj'] ?? ''; ?>" onblur="saveMark('<?php echo $stid; ?>', 'sub')">
                        <div class="input-label">Sub</div>
                    </div>
                    <?php endif; ?>

                    <?php if($obj_full > 0): ?>
                    <div class="col">
                        <input type="number" id="obj-<?php echo $stid; ?>" class="m3-input" value="<?php echo $m['obj'] ?? ''; ?>" onblur="saveMark('<?php echo $stid; ?>', 'obj')">
                        <div class="input-label">Obj</div>
                    </div>
                    <?php endif; ?>

                    <?php if($pra_full > 0): ?>
                    <div class="col">
                        <input type="number" id="pra-<?php echo $stid; ?>" class="m3-input" value="<?php echo $m['pra'] ?? ''; ?>" onblur="saveMark('<?php echo $stid; ?>', 'pra')">
                        <div class="input-label">Pra</div>
                    </div>
                    <?php endif; ?>

                    <div class="col-auto">
                        <div class="obt-box shadow-sm">
                            <span style="font-size: 0.5rem; font-weight: 700;">OBT</span>
                            <span class="fw-bold" id="obt-<?php echo $stid; ?>"><?php echo $obt_val; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; $stmt_st->close(); ?>
    </div>
</main>

<script>
    const limits = { ca: <?php echo $ca_full; ?>, sub: <?php echo $subj_full; ?>, obj: <?php echo $obj_full; ?>, pra: <?php echo $pra_full; ?> };
    const factor = (100 - limits.ca) / 100;

    function saveMark(stid, field) {
        const val = parseFloat(document.getElementById(field + '-' + stid).value) || 0;
        if (val > (field === 'ca' ? 100 : limits[field])) {
            Swal.fire('Invalid!', 'Exceeds limit', 'error');
            return;
        }

        const ca = parseFloat(document.getElementById('ca-' + stid)?.value) || 0;
        const sub = parseFloat(document.getElementById('sub-' + stid)?.value) || 0;
        const obj = parseFloat(document.getElementById('obj-' + stid)?.value) || 0;
        const pra = parseFloat(document.getElementById('pra-' + stid)?.value) || 0;

        const total = Math.round((sub * factor) + (obj * factor) + (pra * factor) + ca);
        document.getElementById('obt-' + stid).innerText = total;

        const sync = document.getElementById('sync-' + stid);
        sync.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div>';

        $.ajax({
            type: "POST",
            url: "backend/save-st-mark.php",
            data: {
                sccode: '<?php echo $sccode; ?>', cls: '<?php echo $classname; ?>', sec: '<?php echo $sectionname; ?>',
                exam: '<?php echo $exam; ?>', sub: '<?php echo $subj; ?>', stid: stid, session: '<?php echo $sessionyear; ?>',
                ca: ca, subj: sub, obj: obj, pra: pra, total: total
            },
            success: function() { sync.innerHTML = '<i class="bi bi-cloud-check-fill text-success"></i>'; }
        });
    }
</script>

<?php include 'footer.php'; ?>