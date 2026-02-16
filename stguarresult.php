<?php
$page_title = "Academic Results";
include 'inc.php'; 

$stid = $_GET['stid'] ?? 0;
$sy_param = $sessionyear_param;

// ১. স্টুডেন্ট বেসিক ডাটা ফেচ করা
$stmt_st = $conn->prepare("SELECT s.stnameeng, si.classname, si.rollno FROM students s JOIN sessioninfo si ON s.stid = si.stid WHERE s.stid = ? AND si.sessionyear LIKE ? LIMIT 1");
$stmt_st->bind_param("ss", $stid, $sy_param);
$stmt_st->execute();
$st = $stmt_st->get_result()->fetch_assoc();

// ২. চলমান সেশনের পরীক্ষার তালিকা ফেচ করা
$exams = [];
$stmt_ex = $conn->prepare("SELECT * FROM examlist WHERE sccode = ? AND sessionyear LIKE ? AND (classname = ? OR classname IS NULL OR classname = '') ORDER BY datestart DESC");
$stmt_ex->bind_param("iss", $sccode, $sy_param, $st['classname']);
$stmt_ex->execute();
$res_ex = $stmt_ex->get_result();
while($row = $res_ex->fetch_assoc()) $exams[] = $row;
?>

<style>
    body { background-color: #FEF7FF; }
    .hero-profile {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        margin: 12px; padding: 24px; border-radius: 28px; color: white;
    }
    
    /* Exam Card M3 Style */
    .exam-card {
        background: #fff; border-radius: 20px; padding: 16px;
        margin: 0 12px 12px; border: 1px solid #E7E0EC;
        transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;
        display: flex; align-items: center; justify-content: space-between;
    }
    .exam-card:active { transform: scale(0.97); background: #F3EDF7; }
    
    .exam-icon {
        width: 48px; height: 48px; border-radius: 12px;
        background: #EADDFF; color: #21005D;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin-right: 16px;
    }
    
    .status-badge {
        font-size: 0.65rem; font-weight: 800; padding: 4px 12px;
        border-radius: 100px; text-transform: uppercase;
    }
</style>

<main class="pb-5">
    <div class="hero-profile shadow-sm">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <img src="<?= student_profile_image_path($stid) ?>" style="width: 65px; height: 65px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.3);">
            </div>
            <div>
                <h5 class="fw-black m-0"><?= $st['stnameeng'] ?></h5>
                <p class="small m-0 opacity-75">Class: <?= $st['classname'] ?> | Roll: <?= $st['rollno'] ?></p>
            </div>
        </div>
    </div>

    <div class="px-3 mt-4 mb-3">
        <span class="fw-black text-muted small text-uppercase" style="letter-spacing: 1px;">Available Assessments</span>
    </div>

    <div id="exam-list-container">
        <?php foreach($exams as $ex): 
            $publish_status = ($ex['status'] == 1) ? 'Published' : 'Pending';
            $status_cls = ($ex['status'] == 1) ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning';
        ?>
        <div class="exam-card shadow-sm" onclick="loadResult('<?= $ex['examcode'] ?>', '<?= $stid ?>')">
            <div class="d-flex align-items-center">
                <div class="exam-icon"><i class="bi bi-journal-check"></i></div>
                <div>
                    <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?= $ex['examtitle'] ?></div>
                    <div class="small text-muted"><?= date('d M, Y', strtotime($ex['datestart'])) ?></div>
                </div>
            </div>
            <div class="text-end">
                <span class="status-badge <?= $status_cls ?>"><?= $publish_status ?></span>
                <div class="mt-1"><i class="bi bi-chevron-right text-muted opacity-50"></i></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0" style="border-radius: 28px; background: #FEF7FF;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-black text-primary"><i class="bi bi-trophy me-2"></i>Performance Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="result_body" style="max-height: 70vh; overflow-y: auto;">
                </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" id="fullResultBtn">
                    VIEW FULL REPORT CARD
                </button>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>
<script>
function loadResult(examcode, stid) {
    var myModal = new bootstrap.Modal(document.getElementById('resultModal'));
    myModal.show();
    
    $("#result_body").html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Processing scores...</p></div>');
    
    $.ajax({
        url: 'ajax/get-exam-result.php',
        type: 'POST',
        data: { examcode: examcode, stid: stid },
        success: function(res) {
            $("#result_body").html(res);
            // পূর্ণ ফলাফল বাটনের লিংক আপডেট
            $("#fullResultBtn").attr("onclick", `window.location.href='full-report.php?stid=${stid}&exam=${examcode}'`);
        }
    });
}
</script>

