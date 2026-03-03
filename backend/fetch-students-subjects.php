<?php
include '../inc.light.php';

$slot = $_POST['slot'];
$session = $_POST['session'];
$class = $_POST['class'];
$section = $_POST['section'];

$sql = "SELECT si.stid, si.rollno, si.subject_list, st.stnameeng, st.stnameben 
        FROM sessioninfo si 
        JOIN students st ON si.stid = st.stid 
        WHERE si.sccode='$sccode' AND si.slot='$slot' AND si.sessionyear='$session' 
        AND si.classname='$class' AND si.sectionname='$section' 
        ORDER BY si.rollno ASC";

$rs = $conn->query($sql);
while($r = $rs->fetch_assoc()): ?>
    <div class="col-md-4 col-lg-3">
        <div class="student-card shadow-sm card-<?= $r['stid'] ?>" onclick="toggleStudent('<?= $r['stid'] ?>')">
            <i class="bi bi-check-circle-fill check-icon fs-5"></i>
            <div class="d-flex align-items-center gap-3">
                <div class="st-avatar"><?= $r['rollno'] ?></div>
                <div class="overflow-hidden">
                    <div class="fw-black text-dark text-truncate"><?= $r['stnameeng'] ?></div>
                    <div class="small text-muted text-truncate"><?= $r['stnameben'] ?></div>
                    <div class="small fw-bold text-primary mt-1" style="font-size: 0.65rem;">
                        Subjects: <?= $r['subject_list'] ?: 'None' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>