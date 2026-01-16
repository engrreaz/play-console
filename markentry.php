<?php
include 'inc.php'; // এটি header.php এবং DB কানেকশন লোড করবে

// ১. ইনপুট প্যারামিটার হ্যান্ডলিং
$classname = $_GET['cls'] ?? '';
$sectionname = $_GET['sec'] ?? '';
$exam = $_GET['exam'] ?? '';
$subj = $_GET['sub'] ?? '';
$assess = $_GET['assess'] ?? '';

// ২. লক স্ট্যাটাস চেক (Prepared Statement)
$lock = 0;
$stmt_lock = $conn->prepare("SELECT halfdone, fulldone FROM areas WHERE areaname = ? AND subarea = ? AND user = ?");
$stmt_lock->bind_param("sss", $classname, $sectionname, $rootuser);
$stmt_lock->execute();
$res_lock = $stmt_lock->get_result();
if ($row = $res_lock->fetch_assoc()) {
    $lock = ($exam == 'Half Yearly') ? $row["halfdone"] : $row["fulldone"];
}
$stmt_lock->close();

// ৩. সাবজেক্ট নেম ফেচ করা
$sname = "";
$stmt_sub = $conn->prepare("SELECT subject FROM subjects WHERE subcode = ? AND sccategory = ? LIMIT 1");
$stmt_sub->bind_param("ss", $subj, $sctype);
$stmt_sub->execute();
$res_sub = $stmt_sub->get_result();
if ($row = $res_sub->fetch_assoc()) { $sname = $row["subject"]; }
$stmt_sub->close();

// ৪. সাবজেক্ট সেটআপ (Full Marks, Distribution)
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

// ৫. অপ্টিমাইজড ডাটা ফেচিং (লুপের বাইরে সব ডাটা একসাথে আনা)
// সব স্টুডেন্টের নম্বর একটি অ্যারেতে রাখা
$existing_marks = [];
$stmt_m = $conn->prepare("SELECT * FROM stmark WHERE sccode = ? AND exam = ? AND classname = ? AND sectionname = ? AND sessionyear LIKE ? AND subject = ?");
$sy_like = "%$sy%";
$stmt_m->bind_param("ssssss", $sccode, $exam, $classname, $sectionname, $sy_like, $subj);
$stmt_m->execute();
$res_m = $stmt_m->get_result();
while($row = $res_m->fetch_assoc()) { $existing_marks[$row['stid']] = $row; }
$stmt_m->close();
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface */
    .m3-app-bar { position: sticky; top: 0; z-index: 1020; background: #fff; border-bottom: 1px solid #EADDFF; }
    
    .st-mark-card {
        border-radius: 24px; border: none; background: #fff;
        margin-bottom: 12px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .st-mark-card.locked { opacity: 0.7; pointer-events: none; background: #f5f5f5; }

    /* Input Styling */
    .mark-input {
        width: 100%; border: 1px solid #79747E; border-radius: 8px;
        padding: 8px; text-align: center; font-weight: 700; font-size: 1.1rem;
    }
    .mark-input:focus { border-color: #6750A4; outline: none; background: #F3EDF7; }
    .mark-label { font-size: 0.65rem; color: #49454F; text-align: center; margin-top: 4px; font-weight: 600; }
    
    .obt-display {
        background: #EADDFF; color: #21005D; border-radius: 12px;
        padding: 10px; text-align: center; min-width: 60px;
    }
    .sync-indicator { font-size: 1.2rem; }
    
    .badge-dist { background: #F3EDF7; color: #6750A4; border-radius: 6px; padding: 2px 8px; font-size: 0.7rem; }
</style>

<main class="pb-5">
    <div class="m3-app-bar shadow-sm p-3">
        <div class="d-flex align-items-center mb-2">
            <a href="markentryselect.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-0 text-truncate"><?php echo $sname; ?></h6>
                <span class="badge-dist"><?php echo $exam; ?> | <?php echo $classname; ?>-<?php echo $sectionname; ?></span>
            </div>
            <?php if ($lock == 1): ?>
                <i class="bi bi-lock-fill text-danger fs-4"></i>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-between small text-muted border-top pt-2">
            <span>CA: <b><?php echo $ca_full; ?>%</b></span>
            <span>Sub: <b><?php echo $subj_full; ?></b></span>
            <span>Obj: <b><?php echo $obj_full; ?></b></span>
            <span>Pra: <b><?php echo $pra_full; ?></b></span>
            <span class="text-primary">Full: <b><?php echo $fullmark; ?></b></span>
        </div>
    </div>

    <div class="container-fluid mt-3 px-3">
        <div id="wait" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 small fw-bold">Initializing Entry Interface...</p>
        </div>

        <div id="entry-container" style="display:none;">
            <?php
            // স্টুডেন্ট লিস্ট ফেচিং (Prepared Statement)
            $stmt_st = $conn->prepare("SELECT s.stid, s.rollno, st.stnameeng, st.religion FROM sessioninfo s 
                                       JOIN students st ON s.stid = st.stid 
                                       WHERE s.sccode = ? AND s.classname = ? AND s.sectionname = ? AND s.sessionyear LIKE ? 
                                       ORDER BY s.rollno ASC");
            $stmt_st->bind_param("ssss", $sccode, $classname, $sectionname, $sy_like);
            $stmt_st->execute();
            $res_st = $stmt_st->get_result();

            while ($row = $res_st->fetch_assoc()):
                $stid = $row['stid'];
                $roll = $row['rollno'];
                $is_eligible = true;
                
                // ধর্মভিত্তিক ফিল্টার (আপনার আগের লজিক)
                if($row['religion'] == 'Islam' && $subj == 112) $is_eligible = false;
                if($row['religion'] == 'Hindu' && $subj == 111) $is_eligible = false;

                $m = $existing_marks[$stid] ?? null;
                $ca_val  = $m['ca'] ?? '';
                $sub_val = $m['subj'] ?? '';
                $obj_val = $m['obj'] ?? '';
                $pra_val = $m['pra'] ?? '';
                $obt_val = $m['markobt'] ?? '0';
            ?>

            <div class="st-mark-card shadow-sm <?php echo (!$is_eligible || $lock == 1) ? 'locked' : ''; ?>">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width:32px; height:32px; font-size: 0.8rem;">
                            <?php echo $roll; ?>
                        </div>
                        <div class="fw-bold text-dark small text-truncate" style="max-width: 180px;"><?php echo $row['stnameeng']; ?></div>
                    </div>
                    <div id="status-<?php echo $stid; ?>" class="sync-indicator">
                        <i class="bi bi-check2-circle text-muted opacity-25"></i>
                    </div>
                </div>

                <div class="row g-2 align-items-center">
                    <?php if($ca_full > 0): ?>
                    <div class="col">
                        <input type="number" id="ca-<?php echo $stid; ?>" class="mark-input" value="<?php echo $ca_val; ?>" 
                               onfocus="onFoc(this)" onblur="onBlu('<?php echo $stid; ?>', 0)">
                        <div class="mark-label">CA</div>
                    </div>
                    <?php endif; ?>

                    <?php if($subj_full > 0): ?>
                    <div class="col">
                        <input type="number" id="sub-<?php echo $stid; ?>" class="mark-input" value="<?php echo $sub_val; ?>" 
                               onfocus="onFoc(this)" onblur="onBlu('<?php echo $stid; ?>', 1)">
                        <div class="mark-label">SUB</div>
                    </div>
                    <?php endif; ?>

                    <?php if($obj_full > 0): ?>
                    <div class="col">
                        <input type="number" id="obj-<?php echo $stid; ?>" class="mark-input" value="<?php echo $obj_val; ?>" 
                               onfocus="onFoc(this)" onblur="onBlu('<?php echo $stid; ?>', 2)">
                        <div class="mark-label">OBJ</div>
                    </div>
                    <?php endif; ?>

                    <?php if($pra_full > 0): ?>
                    <div class="col">
                        <input type="number" id="pra-<?php echo $stid; ?>" class="mark-input" value="<?php echo $pra_val; ?>" 
                               onfocus="onFoc(this)" onblur="onBlu('<?php echo $stid; ?>', 3)">
                        <div class="mark-label">PRA</div>
                    </div>
                    <?php endif; ?>

                    <div class="col-auto ps-2">
                        <div class="obt-display shadow-sm">
                            <div style="font-size: 0.6rem; font-weight: 700; opacity: 0.6;">OBT</div>
                            <div class="fw-bold h5 mb-0" id="obt-<?php echo $stid; ?>"><?php echo $obt_val; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php endwhile; $stmt_st->close(); ?>
        </div>
    </div>
</main>

<script>
    // ডিস্ট্রিবিউশন লিমিট
    const limits = { ca: <?php echo $ca_full; ?>, sub: <?php echo $subj_full; ?>, obj: <?php echo $obj_full; ?>, pra: <?php echo $pra_full; ?> };
    const calcFactor = (100 - limits.ca) / 100;

    window.onload = () => {
        document.getElementById("entry-container").style.display = 'block';
        document.getElementById("wait").style.display = 'none';
    };

    function onFoc(el) {
        el.select();
        el.closest('.st-mark-card').style.boxShadow = "0 4px 12px rgba(103, 80, 164, 0.15)";
    }

    function onBlu(stid, type) {
        const val = parseFloat(document.getElementById(getTypeKey(type) + stid).value) || 0;
        const limit = limits[getTypeKey(type)];

        if (val > limit) {
            Swal.fire('Invalid!', 'Value exceeds full marks (' + limit + ')', 'error');
            document.getElementById(getTypeKey(type) + stid).focus();
            return;
        }

        calculateAndSave(stid);
    }

    function getTypeKey(type) {
        return type === 0 ? 'ca-' : (type === 1 ? 'sub-' : (type === 2 ? 'obj-' : 'pra-'));
    }

    function calculateAndSave(stid) {
        const ca = parseFloat(document.getElementById("ca-" + stid)?.value) || 0;
        const sub = parseFloat(document.getElementById("sub-" + stid)?.value) || 0;
        const obj = parseFloat(document.getElementById("obj-" + stid)?.value) || 0;
        const pra = parseFloat(document.getElementById("pra-" + stid)?.value) || 0;

        // আপনার আগের ক্যালকুলেশন লজিক
        const subx = sub * calcFactor;
        const objx = obj * calcFactor;
        const prax = pra * calcFactor;
        const total = Math.round(subx + objx + prax + ca);

        document.getElementById("obt-" + stid).innerText = total;

        // AJAX সেভ
        const statusIcon = document.getElementById("status-" + stid);
        statusIcon.innerHTML = '<i class="bi bi-arrow-repeat spin text-primary"></i>';

        $.ajax({
            type: "POST",
            url: "backend/save-st-mark.php",
            data: {
                sccode: '<?php echo $sccode; ?>', cls: '<?php echo $classname; ?>', sec: '<?php echo $sectionname; ?>',
                exam: '<?php echo $exam; ?>', sub: '<?php echo $subj; ?>', stid: stid, fm: 100,
                ca: ca, subj: sub, obj: obj, pra: pra, usr: '<?php echo $usr; ?>'
            },
            success: function(response) {
                statusIcon.innerHTML = '<i class="bi bi-cloud-check-fill text-success"></i>';
                document.getElementById("block" + stid).style.boxShadow = "none";
            }
        });
    }
</script>

<style>
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .spin { animation: spin 1s linear infinite; display: inline-block; }
</style>

<?php include 'footer.php'; ?>