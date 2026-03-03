<?php
include '../inc.light.php';

$slot = $_POST['slot'];
$session = $_POST['session'];
$class = $_POST['class'];
$section = $_POST['section'];

$sql = "SELECT ss.subject as subcode, s.subject as subname 
        FROM subsetup ss 
        JOIN subjects s ON ss.subject = s.subcode 
        WHERE ss.sccode='$sccode' AND ss.slot='$slot' AND ss.sessionyear='$session' 
        AND ss.classname='$class' AND ss.sectionname='$section' 
        AND s.sccategory='$sctype' ORDER BY ss.subject ASC";

$rs = $conn->query($sql);
$subjects_array = []; 

if ($rs->num_rows > 0): ?>
    <div class="mb-3">
        <div class="m3-label-tiny mb-2 opacity-75">Main Subjects (Tap to Select)</div>
        <div class="subject-grid">
            <?php while ($r = $rs->fetch_assoc()): 
                $subjects_array[] = $r; ?>
                <div class="subject-item sub-<?= $r['subcode'] ?>" onclick="toggleSubject('<?= $r['subcode'] ?>')">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-circle check-box-icon"></i>
                        <div class="fw-bold"><?= $r['subname'] ?></div>
                    </div>
                    <div class="small opacity-50"><?= $r['subcode'] ?></div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="fourth-sub-container mt-4 p-3 shadow-xs">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="m3-icon-circle bg-tertiary-container" style="width:32px; height:32px; font-size:0.9rem;">
                <i class="bi bi-star-fill text-warning"></i>
            </div>
            <div class="m3-label-tiny text-dark m-0">Fourth / Optional Subjects</div>
        </div>

        <div class="m3-chip-grid">
            <?php foreach ($subjects_array as $sub): ?>
                <div class="m3-filter-chip chip-<?= $sub['subcode'] ?>" 
                     onclick="toggleFourthSubject('<?= $sub['subcode'] ?>')">
                    <i class="bi bi-plus-lg chip-icon me-1"></i>
                    <span><?= $sub['subname'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <p class="text-muted mt-2 mb-0" style="font-size: 0.65rem;">
            <i class="bi bi-info-circle me-1"></i> Selected items will be stored as fourth subjects.
        </p>
    </div>

<?php else: ?>
    <div class="text-center py-4 text-danger fw-bold">No subjects found in setup!</div>
<?php endif; ?>