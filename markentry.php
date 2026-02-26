<?php
// ... (আপনার সব পিএইচপি লজিক এবং কুয়েরি এখানে থাকবে - কোনো পরিবর্তন নেই)
include 'inc.php';

// ২. ইনপুট প্যারামিটার
$classname = $_GET['cls'] ?? '';
$sectionname = $_GET['sec'] ?? '';
$exam = $_GET['exam'] ?? '';
$subj = $_GET['sub'] ?? '';
$page_title = "Mark Entry";

// ৩. লক স্ট্যাটাস চেক (লজিক অপরিবর্তিত)
$lock = 0;
$stmt_lock = $conn->prepare("SELECT halfdone, fulldone FROM areas WHERE areaname = ? AND subarea = ? AND user = ?");
$stmt_lock->bind_param("sss", $classname, $sectionname, $rootuser);
$stmt_lock->execute();
$res_lock = $stmt_lock->get_result();
if ($row = $res_lock->fetch_assoc()) {
    $lock = ($exam == 'Half Yearly') ? $row["halfdone"] : $row["fulldone"];
}
$stmt_lock->close();

// ৪. সাবজেক্ট এবং সেটআপ ডাটা ফেচিং (লজিক অপরিবর্তিত)
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

// ৫. বিদ্যমান নম্বর (অপরিবর্তিত)
$existing_marks = [];
$stmt_m = $conn->prepare("SELECT * FROM stmark WHERE sccode = ? AND exam = ? AND classname = ? AND sectionname = ? AND sessionyear LIKE ? AND subject = ?");
$stmt_m->bind_param("ssssss", $sccode, $exam, $classname, $sectionname, $sessionyear_param, $subj);
$stmt_m->execute();
$res_m = $stmt_m->get_result();
while($row = $res_m->fetch_assoc()) { $existing_marks[$row['stid']] = $row; }
$stmt_m->close();
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-secondary-container: #F3EDF7;
        --m3-error: #B3261E;
    }

    body { background-color: var(--m3-surface); font-family: 'Inter', sans-serif; }

    /* M3 Hero Header */
    .mark-hero {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white; padding: 40px 20px 80px;
        border-radius: 0 0 32px 32px; text-align: center;
        position: relative;
    }
    
    .back-fab {
        position: absolute; left: 16px; top: 16px;
        width: 40px; height: 40px; border-radius: 12px;
        background: rgba(255,255,255,0.2); color: white;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; backdrop-filter: blur(4px);
    }

    /* Distribution Bar as Tonal Chips */
    .dist-chips {
        display: flex; justify-content: center; gap: 8px;
        margin-top: -50px; padding: 0 16px; position: relative; z-index: 10;
        overflow-x: auto; white-space: nowrap; scrollbar-width: none;
    }
    .m3-chip {
        background: white; border: 1px solid #E7E0EC;
        padding: 6px 14px; border-radius: 12px;
        font-size: 0.7rem; font-weight: 800; color: #49454F;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    /* Student Mark Card */
    .mark-card {
        background: #fff; border-radius: 20px; padding: 16px;
        margin: 12px 12px; border: 1px solid #E7E0EC;
        transition: 0.3s cubic-bezier(0.2, 0, 0, 1);
    }
    .mark-card:focus-within { border-color: var(--m3-primary); box-shadow: 0 8px 16px rgba(103, 80, 164, 0.08); }
    .mark-card.locked { opacity: 0.6; pointer-events: none; background: #f1f1f1; border-style: dashed; }

    /* Roll Circle */
    .roll-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--m3-primary-container); color: #21005D;
        display: flex; align-items: center; justify-content: center;
        font-weight: 900; font-size: 0.8rem;
    }

    /* M3 Clean Inputs */
    .input-box-tonal {
        background: var(--m3-secondary-container);
        border-radius: 12px; padding: 6px; text-align: center;
        border: 1px solid transparent; transition: 0.2s;
    }
    .input-box-tonal:focus-within { background: #fff; border-color: var(--m3-primary); }
    
    .m3-input-clean {
        border: none; background: transparent; width: 100%;
        text-align: center; font-weight: 900; font-size: 1.1rem;
        color: #1C1B1F; outline: none;
    }
    .m3-input-label { font-size: 0.6rem; font-weight: 800; color: var(--m3-primary); text-transform: uppercase; margin-top: 2px; }

    /* Obtained Score Box */
    .obt-container {
        background: var(--m3-primary); color: white;
        border-radius: 12px; min-width: 50px; height: 50px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(103, 80, 164, 0.2);
    }
</style>

<main class="pb-5">
    <div class="mark-hero shadow">
        <a href="markentryselect.php" class="back-fab"><i class="bi bi-arrow-left"></i></a>
        <h4 class="fw-black m-0"><?php echo $sname; ?></h4>
        <p class="small opacity-75 fw-bold mb-0">
            <?php echo "$classname ($sectionname) • $exam"; ?>
        </p>
        <?php if($lock == 1): ?>
            <div class="badge bg-danger rounded-pill px-3 mt-2"><i class="bi bi-lock-fill me-1"></i> ENTRY LOCKED</div>
        <?php endif; ?>
    </div>

    <div class="dist-chips">
        <?php if($ca_full > 0): ?><div class="m3-chip">CA: <?php echo $ca_full; ?>%</div><?php endif; ?>
        <?php if($subj_full > 0): ?><div class="m3-chip">SUB: <?php echo $subj_full; ?></div><?php endif; ?>
        <?php if($obj_full > 0): ?><div class="m3-chip">OBJ: <?php echo $obj_full; ?></div><?php endif; ?>
        <?php if($pra_full > 0): ?><div class="m3-chip">PRA: <?php echo $pra_full; ?></div><?php endif; ?>
        <div class="m3-chip border-primary text-primary">FULL: <?php echo $fullmark; ?></div>
    </div>

    <div class="list-container px-1 mt-4">
        <?php
        // স্টুডেন্ট লিস্ট ফেচিং (লজিক অপরিবর্তিত)
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
            <div class="mark-card shadow-sm <?php echo (!$is_eligible || $lock == 1) ? 'lockedd' : ''; ?>">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="roll-avatar shadow-sm"><?php echo $row['rollno']; ?></div>
                        <div class="fw-black text-dark text-truncate small" style="max-width: 180px; letter-spacing: -0.2px;">
                            <?php echo strtoupper($row['stnameeng']); ?>
                        </div>
                    </div>
                    <div id="sync-<?php echo $stid; ?>" class="opacity-50">
                        <i class="bi bi-cloud-check fs-5"></i>
                    </div>
                </div>

                <div class="row gx-2 align-items-end">
                    <?php if($ca_full > 0): ?>
                    <div class="col">
                        <div class="input-box-tonal">
                            <input type="number" id="ca-<?php echo $stid; ?>" class="m3-input-clean" value="<?php echo $m['ca'] ?? ''; ?>" onblur="saveMark('<?php echo $stid; ?>', 'ca')">
                            <div class="m3-input-label">CA</div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($subj_full > 0): ?>
                    <div class="col">
                        <div class="input-box-tonal">
                            <input type="number" id="sub-<?php echo $stid; ?>" class="m3-input-clean" value="<?php echo $m['subj'] ?? ''; ?>" onblur="saveMark('<?php echo $stid; ?>', 'sub')">
                            <div class="m3-input-label">Sub</div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($obj_full > 0): ?>
                    <div class="col">
                        <div class="input-box-tonal">
                            <input type="number" id="obj-<?php echo $stid; ?>" class="m3-input-clean" value="<?php echo $m['obj'] ?? ''; ?>" onblur="saveMark('<?php echo $stid; ?>', 'obj')">
                            <div class="m3-input-label">Obj</div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($pra_full > 0): ?>
                    <div class="col">
                        <div class="input-box-tonal">
                            <input type="number" id="pra-<?php echo $stid; ?>" class="m3-input-clean" value="<?php echo $m['pra'] ?? ''; ?>" onblur="saveMark('<?php echo $stid; ?>', 'pra')">
                            <div class="m3-input-label">Pra</div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="col-auto">
                        <div class="obt-container shadow">
                            <span style="font-size: 0.5rem; font-weight: 800; opacity: 0.8;">OBT</span>
                            <span class="h5 fw-black m-0" id="obt-<?php echo $stid; ?>"><?php echo $obt_val; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; $stmt_st->close(); ?>
    </div>
</main>


<?php include 'footer.php'; ?>
<script>
    // জাভাস্ক্রিপ্ট লজিক অপরিবর্তিত রাখা হয়েছে
    const limits = { ca: <?php echo $ca_full; ?>, sub: <?php echo $subj_full; ?>, obj: <?php echo $obj_full; ?>, pra: <?php echo $pra_full; ?> };
    const factor = (100 - limits.ca) / 100;

    function saveMark(stid, field) {
        const val = parseFloat(document.getElementById(field + '-' + stid).value) || 0;
        
        // ভ্যালিডেশন
        if (val > (field === 'ca' ? 100 : limits[field])) {
            Swal.fire({
                icon: 'error',
                title: 'Limit Exceeded!',
                text: `Maximum allowed for ${field.toUpperCase()} is ${field === 'ca' ? 100 : limits[field]}`,
                confirmButtonColor: '#6750A4',
                border_radius: '28px'
            });
            document.getElementById(field + '-' + stid).value = '';
            return;
        }

        const ca = parseFloat(document.getElementById('ca-' + stid)?.value) || 0;
        const sub = parseFloat(document.getElementById('sub-' + stid)?.value) || 0;
        const obj = parseFloat(document.getElementById('obj-' + stid)?.value) || 0;
        const pra = parseFloat(document.getElementById('pra-' + stid)?.value) || 0;

        // টোটাল ক্যালকুলেশন
        const total = Math.round((sub * factor) + (obj * factor) + (pra * factor) + ca);
        document.getElementById('obt-' + stid).innerText = total;

        const sync = document.getElementById('sync-' + stid);
        sync.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div>';

        // AJAX সেভ (অপরিবর্তিত)
        $.ajax({
            type: "POST",
            url: "backend/save-st-mark.php",
            data: {
                sccode: '<?php echo $sccode; ?>', cls: '<?php echo $classname; ?>', sec: '<?php echo $sectionname; ?>',
                exam: '<?php echo $exam; ?>', sub: '<?php echo $subj; ?>', stid: stid, session: '<?php echo $sessionyear; ?>',
                ca: ca, subj: sub, obj: obj, pra: pra, total: total
            },
            success: function() { 
                sync.innerHTML = '<i class="bi bi-cloud-check-fill text-success fs-5"></i>'; 
            },
            error: function() {
                sync.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>';
            }
        });
    }
</script>

