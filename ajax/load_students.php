<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once '../inc.light.php';
include_once '../datam/datam-stprofile.php';


$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
$collection_permission = (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) ? 1 : 0;
$profile_permission = (isset($settings_map['Profile Entry']) && strpos($settings_map['Profile Entry'], $userlevel) !== false) ? 1 : 0;


$cls = $_POST['cls'];
$sec = $_POST['sec'];
$month = date('m');

$cnt = 0;
$total_due = 0;
$dues_map = [];

/* dues query */
$stmt_d = $conn->prepare("
    SELECT stid, SUM(dues) td
    FROM stfinance
    WHERE sessionyear LIKE ?
      AND sccode = ?
      AND classname = ?
      AND sectionname = ?
      AND month <= ?
    GROUP BY stid
");
$stmt_d->bind_param("sssss", $sessionyear_param, $sccode, $cls, $sec, $month);
$stmt_d->execute();
$res_d = $stmt_d->get_result();

while ($r = $res_d->fetch_assoc()) {
    $dues_map[$r['stid']] = $r['td'];
}

/* students */
$stmt_s = $conn->prepare("
    SELECT *
    FROM sessioninfo
    WHERE sessionyear LIKE ?
      AND sccode = ?
      AND classname = ?
      AND sectionname = ?
    ORDER BY rollno ASC
");
$stmt_s->bind_param("ssss", $sessionyear_param, $sccode, $cls, $sec);
$stmt_s->execute();
$res_s = $stmt_s->get_result();

ob_start();

while ($row = $res_s->fetch_assoc()) {

    $stid = $row['stid'];
    $atx = get_student_info_by_id($stid);

    $due = $dues_map[$stid] ?? 0;

    $cnt++;
    $total_due += $due;
    ?>

    <div class="st-card shadow-sm <?php echo $is_active ? '' : 'opacity-75 grayscale'; ?>"
        onclick="this.classList.toggle('expanded')">
        <div class="d-flex align-items-center">
            <img src="<?= student_profile_image_path($stid) ?>" class="st-avatar shadow-sm">
            <div class="ms-3 flex-grow-1 overflow-hidden">
                <div class="fw-bold text-dark text-truncate" style="font-size: 0.75rem;">
                    <?= $atx['stnameeng']; ?>
                </div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.65rem;">Roll:
                        <?php echo $row["rollno"]; ?></span>
                    <span class="text-muted" style="font-size: 0.65rem;">ID: <?php echo $stid; ?></span>

                    <?php if ($due > 0): ?>
                        <span class="due-badge ms-auto">৳<?php echo number_format($due); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <i class="bi bi-three-dots-vertical text-muted ms-1 opacity-25"></i>
        </div>

        <div class="action-grid">
            <a href="stguarattnd.php?stid=<?php echo $stid; ?>" class="act-item" onclick="event.stopPropagation();">
                <i class="bi bi-calendar2-check"></i><span>Attend</span>
            </a>
            <?php if ($collection_permission): ?>
                <a href="stfinancedetails.php?id=<?php echo $stid; ?>" class="act-item" onclick="event.stopPropagation();">
                    <i class="bi bi-wallet2"></i><span>Fees</span>
                </a>
            <?php endif; ?>
            <a href="stguarresult.php?stid=<?php echo $stid; ?>" class="act-item" onclick="event.stopPropagation();">
                <i class="bi bi-award"></i><span>Result</span>
            </a>
            <a href="student-my-profile.php?stid=<?php echo $stid; ?>" class="act-item" onclick="event.stopPropagation();">
                <i class="bi bi-person-badge"></i><span>Profile</span>
            </a>
        </div>
    </div>

<?php }

// $html = ob_get_clean();

echo json_encode([
    'html' => $html,
    'count' => $cnt,
    'due' => $total_due
]);

