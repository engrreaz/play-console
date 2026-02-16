<?php 
$page_title = "Student Profile";
include 'inc.php'; 

// ১. প্যারামিটার হ্যান্ডলিং
$stid = $_GET['stid'] ?? 0;
$sy_param = "%" . $sy . "%";

// ২. ডাটা ফেচিং (Prepared Statement)
$std = [];
$sql = "SELECT s.*, si.classname, si.sectionname, si.rollno, si.groupname 
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

if (empty($std)) die("<div class='p-5 text-center'>Student record not found.</div>");

// ভেরিয়েবল সেটআপ
$photo_path = student_profile_image_path($stid);
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-on-primary: #FFFFFF;
        --m3-primary-container: #EADDFF;
        --m3-secondary-container: #E8DEF8;
    }

    body { background-color: var(--m3-surface); font-family: 'Segoe UI', Roboto, sans-serif; }

    /* Hero Profile Section */
    .profile-hero {
        background: linear-gradient(180deg, var(--m3-primary) 0%, #4F378B 100%);
        padding: 40px 20px 60px;
        text-align: center;
        border-radius: 0 0 32px 32px;
        color: white;
    }

    .profile-photo-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 16px;
    }

    .large-profile-pic {
        width: 120px;
        height: 150px;
        border-radius: 16px;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .status-dot {
        position: absolute;
        bottom: 5px;
        right: -5px;
        width: 20px;
        height: 20px;
        background: #4CAF50;
        border: 3px solid white;
        border-radius: 50%;
    }

    /* M3 Information Cards */
    .m3-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        margin: -30px 16px 20px;
        border: 1px solid #E7E0EC;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .m3-list-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #F4EFF4;
    }

    .m3-list-item:last-child { border-bottom: none; }

    .m3-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--m3-secondary-container);
        color: var(--m3-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        font-size: 1.2rem;
    }

    .item-label { font-size: 0.7rem; font-weight: 800; color: #79747E; text-transform: uppercase; display: block; }
    .item-value { font-size: 0.95rem; font-weight: 600; color: #1D1B20; }

    .badge-m3 {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
    }
</style>

<main class="pb-5">
    <div class="profile-hero shadow">
        <div class="profile-photo-wrapper">
            <img src="<?= $photo_path ?>" class="large-profile-pic" onerror="this.src='https://eimbox.com/students/noimg.jpg';">
            <div class="status-dot"></div>
        </div>
        <h4 class="fw-black m-0"><?= $std['stnameeng'] ?></h4>
        <p class="small opacity-75 mb-3"><?= $std['stnameben'] ?></p>
        
        <div class="d-flex justify-content-center gap-2">
            <div class="badge-m3">ID: <?= $stid ?></div>
            <div class="badge-m3">ROLL: <?= $std['rollno'] ?? 'N/A' ?></div>
        </div>
    </div>

    <div class="m3-card shadow-sm">
        <div class="row text-center">
            <div class="col-4 border-end">
                <div class="item-label">Class</div>
                <div class="item-value"><?= $std['classname'] ?? 'N/A' ?></div>
            </div>
            <div class="col-4 border-end">
                <div class="item-label">Section</div>
                <div class="item-value"><?= $std['sectionname'] ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <div class="item-label">Blood</div>
                <div class="item-value text-danger"><?= $std['bloodgroup'] ?? 'N/A' ?></div>
            </div>
        </div>
    </div>

    <div class="px-3 mb-2 mt-4">
        <span class="fw-black text-muted small text-uppercase" style="letter-spacing: 1px;">Guardian & Contact</span>
    </div>
    <div class="m3-card shadow-sm mt-0" style="margin-top: 0;">
        <div class="m3-list-item">
            <div class="m3-icon-box"><i class="bi bi-person-heart"></i></div>
            <div class="flex-grow-1">
                <span class="item-label">Father's Name</span>
                <span class="item-value"><?= $std['fname'] ?? 'Not Specified' ?></span>
            </div>
        </div>
        <div class="m3-list-item">
            <div class="m3-icon-box"><i class="bi bi-person-fill"></i></div>
            <div class="flex-grow-1">
                <span class="item-label">Mother's Name</span>
                <span class="item-value"><?= $std['mname'] ?? 'Not Specified' ?></span>
            </div>
        </div>
        <div class="m3-list-item">
            <div class="m3-icon-box"><i class="bi bi-phone-vibrate"></i></div>
            <div class="flex-grow-1">
                <span class="item-label">Emergency Mobile</span>
                <span class="item-value"><?= $std['guarmobile'] ?? $std['guarphone'] ?? 'N/A' ?></span>
            </div>
            <a href="tel:<?= $std['guarmobile'] ?>" class="btn btn-primary rounded-circle p-2 shadow-sm">
                <i class="bi bi-telephone-fill"></i>
            </a>
        </div>
    </div>

    <div class="px-3 mb-2 mt-4">
        <span class="fw-black text-muted small text-uppercase" style="letter-spacing: 1px;">Residential Address</span>
    </div>
    <div class="m3-card shadow-sm mt-0">
        <div class="m3-list-item">
            <div class="m3-icon-box"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
                <span class="item-label">Current / Permanent Address</span>
                <span class="item-value">
                    <?= ($std['previll'] ?? '') . ", " . ($std['prepo'] ?? '') . ", " . ($std['preps'] ?? '') . ", " . ($std['predist'] ?? '') ?>
                </span>
            </div>
        </div>
    </div>

    <div class="px-3 mb-2 mt-4">
        <span class="fw-black text-muted small text-uppercase" style="letter-spacing: 1px;">General Information</span>
    </div>
    <div class="m3-card shadow-sm mt-0">
        <div class="row">
            <div class="col-6">
                <span class="item-label">Gender</span>
                <span class="item-value"><?= $std['gender'] ?? 'N/A' ?></span>
            </div>
            <div class="col-6">
                <span class="item-label">Religion</span>
                <span class="item-value"><?= $std['religion'] ?? 'N/A' ?></span>
            </div>
            <div class="col-12 mt-3">
                <span class="item-label">Date of Birth</span>
                <span class="item-value"><i class="bi bi-calendar-event me-2"></i><?= date('d M, Y', strtotime($std['dob'] ?? '')) ?></span>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>