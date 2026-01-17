<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

$stid = $_GET['stid'] ?? 0;
$page_title = "Academic Result";

// ২. স্টুডেন্ট এবং সেশন ইনফো ফেচ করা (Prepared Statement)
$std_data = [];
$stmt = $conn->prepare("SELECT s.*, si.classname, si.sectionname, si.rollno 
                        FROM students s 
                        JOIN sessioninfo si ON s.stid = si.stid 
                        WHERE s.stid = ? AND si.sessionyear LIKE ? LIMIT 1");
$stmt->bind_param("ss", $stid, $sy_param);
$stmt->execute();
$res = $stmt->get_result();
if($row = $res->fetch_assoc()) {
    $std_data = $row;
}
$stmt->close();

$stnameeng = $std_data['stnameeng'] ?? 'N/A';
$cls = $std_data['classname'] ?? '';
$sec = $std_data['sectionname'] ?? '';
$roll = $std_data['rollno'] ?? '';
$stdid = $std_data['stid'] ?? $stid;

// প্রোফাইল পিকচার পাথ
$photo_path = "https://eimbox.com/students/" . $stdid . ".jpg";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* M3 App Bar (Full Width) */
    .m3-app-bar {
        width: 100%; height: 56px; background: #fff; display: flex; align-items: center; 
        padding: 0 16px; position: sticky; top: 0; z-index: 1050; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Large Photo Profile Hero */
    .result-hero {
        background: #fff; padding: 30px 16px; text-align: center;
        margin-bottom: 12px; border-radius: 0 0 8px 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .large-student-photo {
        width: 130px; height: 130px; border-radius: 8px; /* গাইডলাইন অনুযায়ী ৮ পিক্সেল */
        object-fit: cover; border: 4px solid #F3EDF7;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.12);
        margin-bottom: 16px;
    }

    /* M3 Result Card (8px Radius) */
    .res-card {
        background: #fff; border-radius: 8px; padding: 12px;
        margin: 0 12px 10px; border: 1px solid #eee;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .grade-box {
        width: 48px; height: 48px; border-radius: 8px; /* ৮ পিক্সেল */
        background: #EADDFF; color: #21005D;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.2rem; margin-right: 12px;
    }

    .sub-title { font-weight: 700; color: #1D1B20; font-size: 0.9rem; margin-bottom: 2px; }
    .mark-info { font-size: 0.7rem; font-weight: 700; color: #6750A4; text-transform: uppercase; }
    
    .dist-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px;
        margin-top: 10px; padding-top: 10px; border-top: 1px dashed #E7E0EC;
    }
    .dist-item { text-align: center; }
    .dist-label { font-size: 0.55rem; color: #79747E; font-weight: 700; display: block; }
    .dist-val { font-size: 0.8rem; font-weight: 800; color: #1C1B1F; }

    .session-badge {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="index.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="session-badge"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5">
    <div class="result-hero shadow-sm">
        <img src="<?php echo $photo_path; ?>" class="large-student-photo" onerror="this.src='https://eimbox.com/students/noimg.jpg';">
        <div class="h5 fw-bold text-dark mb-1"><?php echo $stnameeng; ?></div>
        <div class="d-flex justify-content-center gap-2 mt-2">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Class <?php echo $cls; ?></span>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Roll <?php echo $roll; ?></span>
        </div>
    </div>

    <div class="res-card bg-primary text-white border-0 shadow-sm d-flex justify-content-between align-items-center">
        <div>
            <div class="small opacity-75 fw-bold">Current Assessment</div>
            <div class="fw-bold">Annual Examination</div>
        </div>
        <div class="badge bg-white text-primary rounded-pill px-3">PUBLISHED</div>
    </div>

    <div class="px-3 mb-2 mt-3">
        <span class="fw-bold text-muted small text-uppercase" style="letter-spacing: 1px;">Academic Performance</span>
    </div>

    <div class="list-container px-1">
        <?php
        // ৩. মার্কস ফেচ করা
        $stmt_m = $conn->prepare("SELECT m.*, s.subject as subname 
                                  FROM stmark m 
                                  JOIN subjects s ON m.subject = s.subcode 
                                  WHERE m.stid = ? AND m.sessionyear LIKE ? AND s.sccategory = ?
                                  ORDER BY s.subcode ASC");
        $stmt_m->bind_param("sss", $stid, $sy_param, $sctype);
        $stmt_m->execute();
        $res_m = $stmt_m->get_result();

        if($res_m->num_rows > 0):
            while($m = $res_m->fetch_assoc()):
        ?>
            <div class="res-card shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="grade-box shadow-sm">
                        <?php echo $m['gl']; ?>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="sub-title text-truncate"><?php echo $m['subname']; ?></div>
                        <div class="mark-info">
                            Obtained: <?php echo $m['markobt']; ?> <i class="bi bi-dot"></i> Point: <?php echo $m['gp']; ?>
                        </div>
                    </div>
                    <i class="bi bi-chevron-expand text-muted opacity-25"></i>
                </div>
                
                <div class="dist-grid">
                    <?php if($m['ca'] > 0): ?>
                        <div class="dist-item"><span class="dist-label">CA</span><span class="dist-val"><?php echo $m['ca']; ?></span></div>
                    <?php endif; ?>
                    <?php if($m['subj'] > 0): ?>
                        <div class="dist-item"><span class="dist-label">SUB</span><span class="dist-val"><?php echo $m['subj']; ?></span></div>
                    <?php endif; ?>
                    <?php if($m['obj'] > 0): ?>
                        <div class="dist-item"><span class="dist-label">OBJ</span><span class="dist-val"><?php echo $m['obj']; ?></span></div>
                    <?php endif; ?>
                    <?php if($m['pra'] > 0): ?>
                        <div class="dist-item"><span class="dist-label">PRA</span><span class="dist-val"><?php echo $m['pra']; ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php 
            endwhile; 
        else:
        ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-clipboard-x display-1"></i>
                <p class="fw-bold mt-2">Result not processed yet.</p>
            </div>
        <?php 
        endif; $stmt_m->close();
        ?>
    </div>
</main>

<div style="height: 65px;"></div> <?php include 'footer.php'; ?>