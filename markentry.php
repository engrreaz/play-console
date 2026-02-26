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
if ($row = $stmt_sub->get_result()->fetch_assoc()) {
    $sname = $row["subject"];
}
$stmt_sub->close();

$fullmark = $subj_full = $obj_full = $pra_full = $ca_full = 0;
$stmt_setup = $conn->prepare("SELECT * FROM subsetup WHERE classname = ? AND sectionname = ? AND subject = ? AND sccode = ? LIMIT 1");
$stmt_setup->bind_param("ssss", $classname, $sectionname, $subj, $sccode);
$stmt_setup->execute();
$res_setup = $stmt_setup->get_result();
if ($row = $res_setup->fetch_assoc()) {
    $fullmark = $row["fullmarks"];
    $subj_full = $row["subj"];
    $obj_full = $row["obj"];
    $pra_full = $row["pra"];
    $ca_full = $row["ca"];
}
$stmt_setup->close();

// ৫. বিদ্যমান নম্বর (অপরিবর্তিত)
$existing_marks = [];
$stmt_m = $conn->prepare("SELECT * FROM stmark WHERE sccode = ? AND exam = ? AND classname = ? AND sectionname = ? AND sessionyear LIKE ? AND subject = ?");
$stmt_m->bind_param("ssssss", $sccode, $exam, $classname, $sectionname, $sessionyear_param, $subj);
$stmt_m->execute();
$res_m = $stmt_m->get_result();
while ($row = $res_m->fetch_assoc()) {
    $existing_marks[$row['stid']] = $row;
}
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

    body {
        background-color: var(--m3-surface);
        font-family: 'Inter', sans-serif;
    }

    /* M3 Hero Header */
    .mark-hero {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white;
        padding: 40px 20px 80px;
        border-radius: 0 0 32px 32px;
        text-align: center;
        position: relative;
    }

    .back-fab {
        position: absolute;
        left: 16px;
        top: 16px;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        backdrop-filter: blur(4px);
    }

    /* Distribution Bar as Tonal Chips */
    .dist-chips {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: -50px;
        padding: 0 16px;
        position: relative;
        z-index: 10;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
    }

    .m3-chip {
        background: white;
        border: 1px solid #E7E0EC;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 800;
        color: #49454F;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Student Mark Card */
    .mark-card {
        background: #fff;
        border-radius: 20px;
        padding: 16px;
        margin: 12px 12px;
        border: 1px solid #E7E0EC;
        transition: 0.3s cubic-bezier(0.2, 0, 0, 1);
    }

    .mark-card:focus-within {
        border-color: var(--m3-primary);
        box-shadow: 0 8px 16px rgba(103, 80, 164, 0.08);
    }

    .mark-card.locked {
        opacity: 0.6;
        pointer-events: none;
        background: #f1f1f1;
        border-style: dashed;
    }

    /* Roll Circle */
    .roll-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--m3-primary-container);
        color: #21005D;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 0.8rem;
    }

    /* M3 Clean Inputs */
    .input-box-tonal {
        background: var(--m3-secondary-container);
        border-radius: 12px;
        padding: 6px;
        text-align: center;
        border: 1px solid transparent;
        transition: 0.2s;
    }

    .input-box-tonal:focus-within {
        background: #fff;
        border-color: var(--m3-primary);
    }

    .m3-input-clean {
        border: none;
        background: transparent;
        width: 100%;
        text-align: center;
        font-weight: 900;
        font-size: 1.1rem;
        color: #1C1B1F;
        outline: none;
    }

    .m3-input-label {
        font-size: 0.6rem;
        font-weight: 800;
        color: var(--m3-primary);
        text-transform: uppercase;
        margin-top: 2px;
    }

    /* Obtained Score Box */
    .obt-container {
        background: var(--m3-primary);
        color: white;
        border-radius: 12px;
        min-width: 50px;
        height: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(103, 80, 164, 0.2);
    }
</style>



<style>
    /* ১. মডার্ন কার্ড কন্টেইনার */
    .m3-mark-card {
        background: #fff;
        border-radius: 24px;
        /* M3 Large Shape */
        padding: 16px;
        margin: 0 12px 14px;
        border: 1px solid #E7E0EC;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .m3-mark-card:hover {
        border-color: #6750A4;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.08);
    }

    /* ২. স্টুডেন্ট প্রোফাইল হেডার */
    .student-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .roll-pill {
        background: #6750A4;
        color: white;
        padding: 2px 10px;
        border-radius: 8px;
        font-weight: 900;
        font-size: 0.75rem;
    }

    /* ৩. স্মার্ট ইনপুট গ্রিড */
    .mark-entry-grid {
        display: flex;
        gap: 8px;
        align-items: stretch;
    }

    .input-column {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    /* ৪. ইনপুট বক্স ডিজাইন */
    .m3-tonal-input {
        background: #F3EDF7;
        border: 1.5px solid transparent;
        border-radius: 12px;
        padding: 8px 4px;
        text-align: center;
        font-weight: 900;
        font-size: 1.1rem;
        color: #1C1B1F;
        width: 100%;
        transition: 0.2s;
    }

    .m3-tonal-input:focus {
        background: #fff;
        border-color: #6750A4;
        outline: none;
        box-shadow: 0 0 0 3px rgba(103, 80, 164, 0.1);
    }

    .m3-tiny-label {
        font-size: 0.55rem;
        font-weight: 800;
        color: #6750A4;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ৫. ক্যালকুলেটেড রেজাল্ট বক্স (OBT) */
    .obt-summary-box {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white;
        border-radius: 16px;
        min-width: 60px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 6px;
        box-shadow: 0 4px 8px rgba(103, 80, 164, 0.2);
    }

    .obt-score {
        font-size: 1.2rem;
        font-weight: 900;
        line-height: 1;
        margin: 4px 0;
    }

    .grade-chip {
        font-size: 0.55rem;
        font-weight: 800;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 6px;
        border-radius: 4px;
    }

    /* লকড স্টেট */
    .locked {
        opacity: 0.5;
        pointer-events: none;
        filter: grayscale(1);
        border-style: dashed;
    }
</style>


<style>
    /* সাধারণ অবস্থা */
    .m3-tonal-input {
        background: #F3EDF7;
        color: #1C1B1F;
        border: 2px solid transparent;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        caret-color: #6750A4;
        /* কার্সার কালার */
    }

    /* ফোকাস অবস্থা (Inverted) */
    .m3-tonal-input:focus {
        background-color: #6750A4 !important;
        /* M3 Primary Color */
        color: #FFFFFF !important;
        /* White Text */
        border-color: #6750A4;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
        outline: none;
        transform: scale(1.05);
        /* সামান্য বড় হবে ফোকাস করলে */
        z-index: 10;
        caret-color: white;
        /* ফোকাস থাকা অবস্থায় কার্সার সাদা হবে */
    }

    /* নম্বর ইনপুটের স্পিনার বা তীর চিহ্ন লুকানোর জন্য */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>



<main class="pb-5">
    <div class="mark-hero shadow">
        <h4 class="fw-bold m-0"><?php echo $sname; ?></h4>
        <p class="small opacity-75 fw-bold mb-0">
            <?php echo "$classname ($sectionname) • $exam"; ?>
        </p>
        <?php if ($lock == 1): ?>
            <div class="badge bg-danger rounded-pill px-3 mt-2"><i class="bi bi-lock-fill me-1"></i> ENTRY LOCKED</div>
        <?php endif; ?>
    </div>

    <div class="dist-chips">
        <?php if ($ca_full > 0): ?>
            <div class="m3-chip">CA: <?php echo $ca_full; ?>%</div><?php endif; ?>
        <?php if ($subj_full > 0): ?>
            <div class="m3-chip">SUB: <?php echo $subj_full; ?></div><?php endif; ?>
        <?php if ($obj_full > 0): ?>
            <div class="m3-chip">OBJ: <?php echo $obj_full; ?></div><?php endif; ?>
        <?php if ($pra_full > 0): ?>
            <div class="m3-chip">PRA: <?php echo $pra_full; ?></div><?php endif; ?>
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
            if ($row['religion'] == 'Islam' && $subj == 112)
                $is_eligible = false;
            if ($row['religion'] == 'Hindu' && $subj == 111)
                $is_eligible = false;

            $m = $existing_marks[$stid] ?? null;
            $obt_val = $m['markobt'] ?? '0';
            ?>




            <div class="m3-mark-card shadow-sm <?php echo (!$is_eligible || $lock == 1) ? 'lockedd' : ''; ?>">
                <div class="student-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="roll-pill"><?php echo $row['rollno']; ?></span>
                        <div class="fw-black text-dark text-truncate small" style="max-width: 160px;">
                            <?php echo strtoupper($row['stnameeng']); ?>
                        </div>
                    </div>
                    <div id="sync-<?php echo $stid; ?>" class="opacity-50">
                        <i class="bi bi-cloud-check fs-5"></i>
                    </div>
                </div>

                <div class="mark-entry-grid">

                    <?php if ($ca_full > 0): ?>
                        <div class="input-column">
                            <input type="number" id="ca-<?php echo $stid; ?>" class="m3-tonal-input"
                                value="<?php echo $m['ca'] ?? ''; ?>" onfocus="this.select()" onblur="saveMark('<?php echo $stid; ?>', 'ca')">
                            <div class="m3-tiny-label">CA</div>
                        </div>
                    <?php endif; ?>

                    <?php if ($subj_full > 0): ?>
                        <div class="input-column">
                            <input type="number" id="sub-<?php echo $stid; ?>" class="m3-tonal-input"
                                value="<?php echo $m['subj'] ?? ''; ?>" onfocus="this.select()" onblur="saveMark('<?php echo $stid; ?>', 'sub')">
                            <div class="m3-tiny-label">Sub</div>
                        </div>
                    <?php endif; ?>

                    <?php if ($obj_full > 0): ?>
                        <div class="input-column">
                            <input type="number" id="obj-<?php echo $stid; ?>" class="m3-tonal-input"
                                value="<?php echo $m['obj'] ?? ''; ?>" onfocus="this.select()" onblur="saveMark('<?php echo $stid; ?>', 'obj')">
                            <div class="m3-tiny-label">Obj</div>
                        </div>
                    <?php endif; ?>

                    <?php if ($pra_full > 0): ?>
                        <div class="input-column">
                            <input type="number" id="pra-<?php echo $stid; ?>" class="m3-tonal-input"
                                value="<?php echo $m['pra'] ?? ''; ?>" onfocus="this.select()" onblur="saveMark('<?php echo $stid; ?>', 'pra')">
                            <div class="m3-tiny-label">Pra</div>
                        </div>
                    <?php endif; ?>

                    <div class="col-auto">
                        <div class="obt-summary-box">
                            <span style="font-size: 0.5rem; font-weight: 800; opacity: 0.8;">OBTAINED</span>
                            <span class="obt-score" id="obt-<?php echo $stid; ?>"><?php echo $obt_val; ?></span>
                            <div class="grade-chip">
                                <?php echo ($m['gp'] ?? '0.00') . ' | ' . ($m['gl'] ?? 'F'); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        <?php endwhile;
        $stmt_st->close(); ?>
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
            success: function () {
                sync.innerHTML = '<i class="bi bi-cloud-check-fill text-success fs-5"></i>';
            },
            error: function () {
                sync.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>';
            }
        });
    }
</script>