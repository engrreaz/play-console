<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

$stid = $_GET['stid'] ?? 0;

// ১. স্টুডেন্ট এবং সেশন ইনফো ফেচ করা (Prepared Statement)
$std_data = [];
$stmt = $conn->prepare("SELECT s.*, si.classname, si.sectionname, si.rollno 
                        FROM students s 
                        JOIN sessioninfo si ON s.stid = si.stid 
                        WHERE s.stid = ? AND si.sessionyear = ? LIMIT 1");
$stmt->bind_param("ss", $stid, $sy);
$stmt->execute();
$res = $stmt->get_result();
if($row = $res->fetch_assoc()) {
    $std_data = $row;
}
$stmt->close();

$stnameeng = $std_data['stnameeng'] ?? 'N/A';
$stnameben = $std_data['stnameben'] ?? '';
$cls = $std_data['classname'] ?? '';
$sec = $std_data['sectionname'] ?? '';
$roll = $std_data['rollno'] ?? '';
$stdid = $std_data['stid'] ?? $stid;

// প্রোফাইল পিকচার পাথ
$photo_path = "https://eimbox.com/students/" . $stdid . ".jpg";
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Header Profile Card */
    .profile-hero {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 0 0 32px 32px;
        padding: 30px 20px 50px;
        color: white;
        text-align: center;
        margin-bottom: 40px;
        position: relative;
    }

    .avatar-container {
        width: 90px; height: 90px;
        border-radius: 50%;
        border: 4px solid white;
        background: white;
        margin: 0 auto 12px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .avatar-container img { width: 100%; height: 100%; object-fit: cover; }

    /* Result Card Styling */
    .result-card {
        background: white;
        border-radius: 28px;
        padding: 20px;
        margin-bottom: 16px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .subject-title { font-weight: 700; color: #1C1B1F; font-size: 1rem; }
    .mark-badge {
        background: #EADDFF;
        color: #21005D;
        font-weight: 800;
        font-size: 1.2rem;
        width: 56px; height: 56px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
    }

    .info-label { font-size: 0.7rem; color: #49454F; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
    
    /* Stats Chips */
    .status-chip {
        padding: 4px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 600;
    }
    .bg-gpa { background: #E8F5E9; color: #2E7D32; }
</style>

<main class="pb-5">
    <div class="profile-hero shadow">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="index.php" class="text-white"><i class="bi bi-arrow-left fs-4"></i></a>
            <h6 class="fw-bold mb-0">Examination Report</h6>
            <div style="width: 24px;"></div>
        </div>

        <div class="avatar-container">
            <img src="<?php echo $photo_path; ?>" onerror="this.src='https://eimbox.com/students/noimg.jpg';">
        </div>
        
        <h5 class="fw-bold mb-0"><?php echo $stnameeng; ?></h5>
        <div class="small opacity-75 mb-3"><?php echo $stnameben; ?></div>
        
        <div class="d-flex justify-content-center gap-2">
            <span class="badge rounded-pill bg-white text-primary px-3 py-2">Class: <?php echo $cls; ?></span>
            <span class="badge rounded-pill bg-white text-primary px-3 py-2">Roll: <?php echo $roll; ?></span>
        </div>
    </div>

    <div class="container px-3 mt-n4">
        <div class="result-card shadow-sm mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="info-label">Active Report</div>
                    <h6 class="fw-bold mb-0">Annual Examination <?php echo $sy; ?></h6>
                </div>
                <div class="status-chip bg-gpa">Released</div>
            </div>
        </div>

        <h6 class="ms-2 mb-3 text-secondary fw-bold small text-uppercase">Subject Wise Breakdown</h6>

        <?php
        // ৩. স্টুডেন্টের মার্কস ফেচ করা (Prepared Statement)
        // নোট: আমি আপনার ডাটাবেজ টেবিল 'stmark' ব্যবহার করছি
        $stmt_marks = $conn->prepare("SELECT m.*, s.subject as subname 
                                      FROM stmark m 
                                      JOIN subjects s ON m.subject = s.subcode 
                                      WHERE m.stid = ? AND m.sessionyear LIKE ? AND s.sccategory = ?
                                      ORDER BY s.subcode ASC");
        $sy_like = "%$sy%";
        $stmt_marks->bind_param("sss", $stid, $sy_like, $sctype);
        $stmt_marks->execute();
        $res_marks = $stmt_marks->get_result();

        if($res_marks->num_rows > 0):
            while($m = $res_marks->fetch_assoc()):
                $total_obt = $m['markobt'];
                $grade = $m['gl'];
                $gp = $m['gp'];
        ?>
            <div class="result-card shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="mark-badge shadow-sm me-3">
                        <?php echo $grade; ?>
                    </div>
                    <div class="flex-grow-1">
                        <div class="subject-title"><?php echo $m['subname']; ?></div>
                        <div class="d-flex gap-3 mt-1">
                            <div><span class="info-label">Marks:</span> <span class="fw-bold small"><?php echo $total_obt; ?></span></div>
                            <div><span class="info-label">Point:</span> <span class="fw-bold small"><?php echo $gp; ?></span></div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted opacity-50"></i>
                </div>
                
                <div class="mt-3 pt-2 border-top">
                    <div class="row g-1 text-center">
                        <?php if($m['ca'] > 0): ?><div class="col"><div class="info-label">CA</div><div class="small fw-bold"><?php echo $m['ca']; ?></div></div><?php endif; ?>
                        <?php if($m['subj'] > 0): ?><div class="col"><div class="info-label">Sub</div><div class="small fw-bold"><?php echo $m['subj']; ?></div></div><?php endif; ?>
                        <?php if($m['obj'] > 0): ?><div class="col"><div class="info-label">Obj</div><div class="small fw-bold"><?php echo $m['obj']; ?></div></div><?php endif; ?>
                        <?php if($m['pra'] > 0): ?><div class="col"><div class="info-label">Pra</div><div class="small fw-bold"><?php echo $m['pra']; ?></div></div><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php 
            endwhile; 
        else:
        ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-clipboard-x display-1"></i>
                <p class="mt-2 fw-bold">Result not published yet.</p>
            </div>
        <?php 
        endif; 
        $stmt_marks->close();
        ?>
    </div>
</main>

<div style="height: 60px;"></div>

<?php include 'footer.php'; ?>