<?php
include 'inc.php';
include 'datam/datam-stprofile.php';

$stid = $_GET['stid'] ?? '';
$st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
$stname = ($st_idx !== false) ? $datam_st_profile[$st_idx]['stnameeng'] : "Student ID: $stid";

$q = $conn->query(
    "SELECT s.*, a.title, a.category as cat_name
     FROM student_activities s
     JOIN activities_master a ON a.id=s.activity_id
     WHERE s.stid='$stid'
     ORDER BY s.id DESC"
);
?>

<style>
    .activity-item { padding: 16px; margin-bottom: 12px; border: 1px solid rgba(0,0,0,0.04); display: flex; flex-direction: column; gap: 10px; }
    .award-badge { background: #FFF8E1; color: #FF8F00; padding: 4px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; border: 1px solid #FFECB3; }
    .meta-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 5px; }
    .meta-box { background: var(--m3-tonal-surface); padding: 6px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; color: #555; }
    
    /* নতুন অ্যাকশন বাটন স্টাইল */
    .action-row { display: flex; justify-content: flex-end; gap: 10px; margin-top: 8px; padding-top: 10px; border-top: 1px dashed rgba(0,0,0,0.08); }
    .btn-certificate { background: #EADDFF; color: #21005D; border: none; padding: 6px 14px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; display: flex; align-items: center; gap: 6px; transition: 0.2s; }
    .btn-certificate:active { transform: scale(0.95); background: #D0BCFF; }
</style>

<main>
    <div class="hero-container" style="padding-bottom: 30px; border-radius: 0 0 24px 24px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" onclick="history.back()"><i class="bi bi-arrow-left"></i></div>
                <div>
                    <div style="font-size: 1.3rem; font-weight: 950; line-height: 1.1;"><?php echo strtoupper($stname); ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 600;">Co-Curricular Portfolio</div>
                </div>
            </div>
            <div class="session-pill" style="background: rgba(255,255,255,0.15); border: none; color: #fff;">ID: <?php echo $stid; ?></div>
        </div>
    </div>

    <div class="widget-grid" style="margin-top: 15px; padding: 0 12px 100px;">
        <div class="m3-section-title" style="margin-left: 4px;">Achievement Records</div>

        <?php if ($q->num_rows > 0): ?>
            <?php while($r = $q->fetch_assoc()): ?>
                <div class="m3-list-item activity-item shadow-sm">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <div class="icon-box c-acad" style="width: 44px; height: 44px;"><i class="bi bi-trophy-fill"></i></div>
                            <div>
                                <div class="st-title" style="font-size: 1rem; color: #1C1B1F;"><?php echo $r['title']; ?></div>
                                <div class="st-desc" style="font-size: 0.75rem; font-weight: 700; color: var(--m3-primary);"><?php echo $r['sessionyear']; ?> Session</div>
                            </div>
                        </div>
                        <?php if(!empty($r['award'])): ?><div class="award-badge shadow-sm"><i class="bi bi-patch-check-fill"></i> <?php echo strtoupper($r['award']); ?></div><?php endif; ?>
                    </div>

                    <div class="meta-row">
                        <div class="meta-box"><i class="bi bi-bar-chart-steps me-1"></i> Level: <b><?php echo $r['level']; ?></b></div>
                        <div class="meta-box"><i class="bi bi-person-badge me-1"></i> Role: <b><?php echo $r['role']; ?></b></div>
                    </div>

                    <div class="action-row">
                        <button class="btn-certificate" onclick="downloadCertificate('<?php echo $r['id']; ?>')">
                            <i class="bi bi-file-earmark-pdf-fill"></i> DOWNLOAD CERTIFICATE
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 80px 20px; opacity: 0.4;">
                <i class="bi bi-journal-x" style="font-size: 4rem;"></i>
                <div style="font-weight: 800; margin-top: 10px;">No Activities Recorded</div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    function downloadCertificate(recordId) {
        // এখানে আপনি আপনার সার্টিফিকেট জেনারেশন পেজের লিঙ্ক দিবেন
        // যেমন: window.open('generate_pdf.php?id=' + recordId, '_blank');
        
        Swal.fire({
            title: 'Generating PDF...',
            text: 'Please wait while we prepare your certificate.',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false,
            willClose: () => {
                console.log('Downloading certificate for ID:', recordId);
                window.open('generate_pdf.php?id=' + recordId, '_blank');
                // আপনার ডাউনলোড লজিক এখানে কল হবে
            }
        });
    }
</script>

<?php include 'footer.php'; ?>