<?php 
$page_title = "Student Profile";
include 'inc.php'; // এটি header.php এবং DB কানেকশন লোড করবে


$stid = $_GET['stid'] ?? 0;


// ২. ডাটা ফেচিং (Prepared Statement - Secure)
// ছাত্রের ব্যক্তিগত তথ্য এবং বর্তমান সেশনের একাডেমিক তথ্য একসাথে আনা হচ্ছে
$std = [];
$sql = "SELECT s.*, si.classname, si.sectionname, si.rollno
        FROM students s 
        LEFT JOIN sessioninfo si ON s.stid = si.stid AND si.sessionyear LIKE ?
        WHERE s.stid = ? AND s.sccode = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $sy_param, $stid, $sccode);
$stmt->execute();
$res = $stmt->get_result();
if($row = $res->fetch_assoc()) {
    $std = $row;
}
$stmt->close();

// ভেরিয়েবল সেটআপ
$photo_path = student_profile_image_path($stid );
$stnameeng = $std['stnameeng'] ?? 'N/A';
$stnameben = $std['stnameben'] ?? '';
?>

<style>


    /* Profile Hero Section (M3 Look) */
  

    .large-profile-pic {
        width: 110px; height: 140px; /* আপনার চাহিদা অনুযায়ী বড় ছবি */
        border-radius: 8px; object-fit: cover;
        border: 4px solid #F3EDF7;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.15);
        margin-bottom: 16px;
    }

    .st-name-eng { font-size: 1.3rem; font-weight: 800; color: #cbc4e0; margin-bottom: 2px; }
    .st-name-ben { font-size: 1rem; font-weight: 500; color: #fafafa; margin-bottom: 12px; }

    /* Information Card (8px Radius) */
    .info-card {
        background: #fff; border-radius: 8px; padding: 16px;
        margin: 0 12px 10px; border: 1px solid #eee;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .info-row {
        display: flex; align-items: center; padding: 8px 0;
        border-bottom: 1px solid #F7F2FA;
    }
    .info-row:last-child { border-bottom: none; }

    .info-icon {
        width: 36px; height: 36px; border-radius: 8px;
        background: #F3EDF7; color: #6750A4;
        display: flex; align-items: center; justify-content: center;
        margin-right: 12px; font-size: 1.1rem;
    }

    .info-label { font-size: 0.65rem; font-weight: 700; color: #79747E; text-transform: uppercase; display: block; }
    .info-value { font-size: 0.9rem; font-weight: 700; color: #1D1B20; }

    .session-badge {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>



<main class="pb-5">
    <div class="hero-container shadow-sm">
        <img src="<?php echo $photo_path; ?>" class="large-profile-pic shadow" onerror="this.src='https://eimbox.com/students/noimg.jpg';">
        <div class="st-name-eng"><?php echo $stnameeng; ?></div>
        <div class="st-name-ben"><?php echo $stnameben; ?></div>
        
        <div class="d-flex justify-content-center gap-2 mt-2">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">ID: <?php echo $stid; ?></span>
            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Roll: <?php echo $std['rollno'] ?? 'N/A'; ?></span>
        </div>
    </div>

    <div class="m-3 mb-2 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Academic Identity</div>
    <div class="info-card shadow-sm">
        <div class="info-row">
            <div class="info-icon"><i class="bi bi-mortarboard"></i></div>
            <div>
                <span class="info-label">Class & Section</span>
                <span class="info-value"><?php echo ($std['classname'] ?? 'N/A') . " (" . ($std['sectionname'] ?? 'N/A') . ")"; ?></span>
            </div>
        </div>
        <?php if(!empty($std['groupname'])): ?>
        <div class="info-row">
            <div class="info-icon"><i class="bi bi-layers"></i></div>
            <div>
                <span class="info-label">Group / Category</span>
                <span class="info-value"><?php echo $std['groupname']; ?></span>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="m-3 mb-2 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Guardian Information</div>
    <div class="info-card shadow-sm">
        <div class="info-row">
            <div class="info-icon"><i class="bi bi-person-heart"></i></div>
            <div>
                <span class="info-label">Father's Name</span>
                <span class="info-value"><?php echo $std['fname'] ?? 'Not Specified'; ?></span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-icon"><i class="bi bi-person-fill"></i></div>
            <div>
                <span class="info-label">Mother's Name</span>
                <span class="info-value"><?php echo $std['mname'] ?? 'Not Specified'; ?></span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-icon"><i class="bi bi-telephone-outbound"></i></div>
            <div>
                <span class="info-label">Emergency Contact</span>
                <span class="info-value"><?php echo $std['guarphone'] ?? 'N/A'; ?></span>
            </div>
        </div>
    </div>

    <div class="m-3 mb-2 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Home Address</div>
    <div class="info-card shadow-sm">
        <div class="info-row">
            <div class="info-icon"><i class="bi bi-geo-alt"></i></div>
            <div>
                <span class="info-label">Permanent Residence</span>
                <span class="info-value"><?php echo ($std['previll'] ?? '') . ", " . ($std['prepo'] ?? '') . ", " . ($std['preps'] ?? ''); ?></span>
            </div>
        </div>
    </div>
</main>

<div style="height: 65px;"></div> <?php include 'footer.php'; ?>