<?php
include '../inc.light.php';

// ডাটা রিসিভ
$slot = $_POST['slot'];
$session = $_POST['session'];
$class = $_POST['class'];
$section = $_POST['section'];
$stids = $_POST['stids'];

$subjects = '';
$fourth = '';

if (!empty($stids) && is_array($stids)) {
    $escaped_ids = array_map(fn($id) => "'" . $conn->real_escape_string($id) . "'", $stids);
    $id_list = implode(',', $escaped_ids);

    $sql = "SELECT subject_list, fourth_subject FROM sessioninfo 
            WHERE stid IN ($id_list) AND sccode = '$sccode' AND sessionyear = '$session' 
            AND slot = '$slot' AND classname = '$class' AND sectionname = '$section'";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $subjects .= ($row['subject_list'] ?? '') . '.';
            $fourth .= ($row['fourth_subject'] ?? '') . '.';
        }
    }
}

function clean_to_array($str) {
    $arr = preg_split('/[.,]+/', $str, -1, PREG_SPLIT_NO_EMPTY);
    return array_unique(array_map('trim', $arr));
}

$main_selected_arr = clean_to_array($subjects);
$fourth_selected_arr = clean_to_array($fourth);


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
                $subjects_array[] = $r; 
                // চেক করা হচ্ছে এই সাবজেক্টটি কি অলরেডি সিলেক্টেড?
                $is_selected = in_array($r['subcode'], $main_selected_arr);
            ?>
                <div class="subject-item sub-<?= $r['subcode'] ?> <?= $is_selected ? 'selected' : '' ?>" 
                     onclick="toggleSubject('<?= $r['subcode'] ?>')">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi <?= $is_selected ? 'bi-check-circle-fill' : 'bi-circle' ?> check-box-icon"></i>
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
            <?php foreach ($subjects_array as $sub): 
                $is_fourth_selected = in_array($sub['subcode'], $fourth_selected_arr);
            ?>
                <div class="m3-filter-chip chip-<?= $sub['subcode'] ?> <?= $is_fourth_selected ? 'selected' : '' ?>" 
                     onclick="toggleFourthSubject('<?= $sub['subcode'] ?>')">
                    <i class="bi <?= $is_fourth_selected ? 'bi-check-lg' : 'bi-plus-lg' ?> chip-icon me-1"></i>
                    <span><?= $sub['subname'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        selectedSubjects = <?= json_encode(array_values($main_selected_arr)) ?>;
        selectedFourthSubjects = <?= json_encode(array_values($fourth_selected_arr)) ?>;
    </script>

<?php else: ?>
    <div class="text-center py-4 text-danger fw-bold">No subjects found in setup!</div>
<?php endif; ?>


<script>
    window.selectedSubjects = <?= json_encode(array_values($main_selected_arr)) ?>;
    window.selectedFourthSubjects = <?= json_encode(array_values($fourth_selected_arr)) ?>;
    
    console.log("Pre-loaded Main:", window.selectedSubjects);
    console.log("Pre-loaded Fourth:", window.selectedFourthSubjects);
</script>