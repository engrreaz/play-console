<?php 
$page_title = "Faculty & Staff";
include 'inc.guest.php'; 

include_once 'datam/datam-teacher.php';

$designation_map = [];
$res_desig = $conn->query("SELECT title, ranks FROM designation");
if ($res_desig) {
    while ($d = $res_desig->fetch_assoc()) {
        $designation_map[$d['title']] = $d['ranks'];
    }
}

$faculty_list = [];
$support_staff_list = [];

if(isset($datam_teacher_profile) && is_array($datam_teacher_profile)) {
    foreach ($datam_teacher_profile as $t) {
        if(isset($t['status']) && $t['status'] == 0) continue;

        $eff_rank = $t['ranks'] ?? '';
        if (empty($eff_rank)) {
            $pos = $t['position'] ?? '';
            $eff_rank = $designation_map[$pos] ?? 99; 
        }

        $t['effective_rank'] = (int) $eff_rank;

        if ($t['effective_rank'] < 40) {
            $faculty_list[] = $t;
        } else {
            $support_staff_list[] = $t;
        }
    }
}


usort($faculty_list, fn($a, $b) => $a['effective_rank'] <=> $b['effective_rank']);
usort($support_staff_list, fn($a, $b) => $a['effective_rank'] <=> $b['effective_rank']);

?>

<main class="pb-5">
    <!-- HERO BANNER -->
    <div class="guest-hero-banner text-center" style="background: #E3F2FD; color: #0D47A1; border-bottom: 1px solid #CAC4D0;">
        <div class="mb-3">
            <div class="icon-box-flat mx-auto" style="background: #90CAF9; color: #0D47A1; width: 72px; height: 72px; font-size: 2rem;">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div class="inst-title">Our Faculty & Staff</div>
        <div class="inst-meta"><?php echo htmlspecialchars($scinfo['scname'] ?? $institution_name); ?></div>
        <div class="inst-desc mt-2">Meet the dedicated educators and professionals guiding our students.</div>
    </div>

    <!-- FACULTY DIRECTORY -->
    <div class="section-lbl">Academic Faculty</div>
    <div class="m3-flat-list-group">
        <?php if(empty($faculty_list)): ?>
            <div class="p-4 text-center text-muted" style="font-weight: 600;">No faculty records found.</div>
        <?php else: ?>
            <?php foreach($faculty_list as $t): 
                $tid = $t['tid'];
    $image_path = 'https://eimbox.com/teacher/' . $tid . '.jpg';            ?>
            <div class="m3-list-flat-item">
                <div class="icon-box-flat p-0 overflow-hidden" style="background: #E8DEF8; border: 1px solid #D0BCFF;">
                    <img src="<?= htmlspecialchars($image_path) ?>" alt="Teacher" style="width: 100%; height: 100%; object-fit: cover;" >
                </div>
                <div class="item-info-block">
                    <div class="st-flat-title"><?= htmlspecialchars($t['tname'] ?? 'N/A') ?></div>
                    <div class="st-flat-desc"><?= htmlspecialchars($t['position'] ?? 'Faculty Member') ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- ADMINISTRATION & SUPPORT -->
    <div class="section-lbl">Administration & Support</div>
    <div class="m3-flat-list-group">
        <?php if(empty($support_staff_list)): ?>
            <div class="p-4 text-center text-muted" style="font-weight: 600;">No support staff records found.</div>
        <?php else: ?>
            <?php foreach($support_staff_list as $s): 
                $tid = $s['tid'];
                $image_path = 'https://eimbox.com/teacher/' . $tid . '.jpg';
            ?>
            <div class="m3-list-flat-item">
                <div class="icon-box-flat p-0 overflow-hidden" style="background: #FFF3E0; border: 1px solid #FFCC80;">
                    <img src="<?= htmlspecialchars($image_path) ?>" alt="Staff" style="width: 100%; height: 100%; object-fit: cover;" >
                </div>
                <div class="item-info-block">
                    <div class="st-flat-title"><?= htmlspecialchars($s['tname'] ?? 'N/A') ?></div>
                    <div class="st-flat-desc"><?= htmlspecialchars($s['position'] ?? 'Support Staff') ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer-guest.php'; ?>
