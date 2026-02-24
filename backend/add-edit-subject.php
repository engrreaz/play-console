<?php
/**
 * Subject List Item Backend - M3-EIM-Floating Style
 * Standards: 8px Radius | Tonal Containers | Optimized UI
 */
include '../inc.light.php';

$class_id = $_POST['id'] ?? ''; // areas table id
$tail = $_POST['tail'];   // action indicator



if ($tail != 2 || !$class_id) {
   $slot = $_POST['slot'] ?? '';
$session = $_POST['session'] ?? '';
$clsf = $_POST['clsf'] ?? '';
$secf = $_POST['secf'] ?? '';
} else {
// ১. ক্লাস ও সেকশন নাম ফেচ করা
$stmt_cls = $conn->prepare("SELECT areaname, subarea FROM areas WHERE id = ?");
$stmt_cls->bind_param("i", $class_id);
$stmt_cls->execute();
$res_cls = $stmt_cls->get_result()->fetch_assoc();
$clsf = $res_cls["areaname"] ?? '';
$secf = $res_cls["subarea"] ?? '';
$slot = $res_cls["slot"] ?? '';
$session = $res_cls["sessionyear"] ?? '';
$stmt_cls->close();
}


echo $clsf . " (" . $secf . ")";

// ২. নির্ধারিত সাবজেক্টগুলো ফেচ করা
$sql_sub = "SELECT a.*, b.subject as subname, b.subben, b.fourth 
            FROM subsetup a 
            INNER JOIN subjects b ON a.subject = b.subcode 
            WHERE a.classname = ? AND a.sectionname = ? AND a.sccode = ? AND a.sessionyear LIKE ? 
            AND b.sccategory = ? ORDER BY b.subcode ASC";

$stmt_sub = $conn->prepare($sql_sub);
$sy_param = "%$sy%";
$stmt_sub->bind_param("sssss", $clsf, $secf, $sccode, $session, $sctype);
$stmt_sub->execute();
$result_sub = $stmt_sub->get_result();

if ($result_sub->num_rows > 0) {
    while ($row = $result_sub->fetch_assoc()) {
        $sub_setup_id = $row['id'];
        $subcode = $row['subject'];
        $subname = $row['subname'];
        $subben  = $row['subben'];
        
        $marks_info = ($clsf == 'Six' || $clsf == 'Seven') ? "" : 
                      "SUB: <b>{$row['subj']}</b> | OBJ: <b>{$row['obj']}</b> | PRA: <b>{$row['pra']}</b> | TOTAL: <b>{$row['fullmarks']}</b>";
        ?>

        <div class="m3-item-card shadow-sm animated-fade-in mb-2 mx-2">
            <div class="d-flex align-items-start">
                <div class="m3-icon-box tone-primary shadow-sm">
                    <i class="bi bi-journal-text"></i>
                </div>

                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.95rem; line-height: 1.2;">
                        <?php echo $subname; ?>
                    </div>
                    <div class="text-muted fw-bold small mb-1"><?php echo $subben; ?></div>
                    
                    <?php if($marks_info): ?>
                        <div class="m3-marks-pill">
                            <?php echo $marks_info; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($row['fourth'] == 1): ?>
                        <div id="opt-indicator-<?php echo $subcode; ?>" class="mt-2">
                            <button class="btn btn-m3-tonal-sm" onclick="setOptionalSubject(<?php echo $subcode; ?>);">
                                <i class="bi bi-star-fill me-1"></i> SET AS 4TH SUBJECT
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-flex flex-column gap-1 ms-2">
                    <button class="btn-m3-icon text-primary" 
                            onclick="editSubject(<?php echo $subcode; ?>, '<?php echo $subname; ?>', '<?php echo $subcode; ?>');">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn-m3-icon text-danger" onclick="toggleSubject(<?php echo $subcode; ?>, 0);">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </div>
            </div>
        </div>

    <?php }
} else {
    // সাবজেক্ট না থাকলে এম্পটি স্টেট
    ?>
    <div class="m3-empty-card text-center shadow-sm">
        <div class="mb-3 text-warning">
            <i class="bi bi-exclamation-triangle-fill display-4"></i>
        </div>
        <h6 class="fw-black text-dark">No Subjects Configured</h6>
        <p class="small text-muted mb-4">Curriculum for <b><?php echo $clsf . " (" . $secf . ")"; ?></b> is currently empty.</p>
        
        <button class="btn btn-warning fw-bold px-4 m3-8px" onclick="applyDefaultSetup(<?php echo $class_id; ?>);">
            <i class="bi bi-magic me-2"></i> APPLY DEFAULT SETUP
        </button>
    </div>
<?php
}
$stmt_sub->close();
?>

<style>
    /* M3-EIM-Floating Backend Specific Styles */
    .m3-item-card {
        background: #fff;
        border-radius: 8px !important;
        padding: 14px 16px;
        border: 1px solid #f0f0f0;
    }

    .m3-icon-box {
        width: 44px; height: 44px;
        border-radius: 8px !important;
        display: flex; align-items: center; justify-content: center;
        margin-right: 14px; flex-shrink: 0; font-size: 1.3rem;
    }
    .tone-primary { background-color: #F3EDF7; color: #6750A4; border: 1px solid #EADDFF; }

    .m3-marks-pill {
        display: inline-block;
        font-size: 0.65rem;
        background: #F7F2FA;
        color: #49454F;
        padding: 2px 10px;
        border-radius: 6px;
        border: 1px dashed #CAC4D0;
        letter-spacing: 0.3px;
    }

    .btn-m3-tonal-sm {
        font-size: 0.65rem;
        font-weight: 800;
        background: #EADDFF;
        color: #21005D;
        border: none;
        border-radius: 6px;
        padding: 4px 12px;
        text-transform: uppercase;
    }

    .btn-m3-icon {
        width: 34px; height: 34px;
        border-radius: 6px;
        background: #fff;
        border: 1px solid #eee;
        display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
    }
    .btn-m3-icon:active { background: #F3EDF7; transform: scale(0.9); }

    .m3-empty-card {
        background: #fff;
        border-radius: 8px !important;
        padding: 40px 20px;
        border: 1px solid #FFE082;
    }

    .animated-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>