<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন হ্যান্ডলিং (প্রয়োজনীয় ক্ষেত্রে সেশন ইয়ার যোগ করা যেতে পারে)
$current_session = $_GET['year'] ?? $_COOKIE['query-session'] ?? $sy;

// ২. ডাটা ফেচিং (Prepared Statement)
$scname = $scadd1 = $scadd2 = $ps = $dist = $logo = $mobile = "";

$stmt = $conn->prepare("SELECT * FROM scinfo WHERE sccode = ? LIMIT 1");
$stmt->bind_param("s", $sccode);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $scname = $row["scname"];
    $scadd1 = $row["scadd1"];
    $scadd2 = $row["scadd2"];
    $ps     = $row["ps"];
    $dist   = $row["dist"];
    $logo   = $row["logo"];
    $mobile = $row["mobile"];
}
$stmt->close();

$page_title = "Institution Profile";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width M3 App Bar (8px radius bottom) */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin: 0;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Condensed Identity Section */
    .hero-section {
        background: #fff; padding: 24px 16px; text-align: center;
        border-bottom: 1px solid #E7E0EC; margin-bottom: 16px;
    }
    
    .logo-box {
        width: 80px; height: 80px; background: #F3EDF7;
        border-radius: 8px; /* ৮ পিক্সেল রেডিয়াস */
        padding: 8px; margin: 0 auto 12px;
        border: 1px solid #EADDFF; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .logo-box img { width: 100%; height: 100%; object-fit: contain; }

    .inst-name { font-size: 1.15rem; font-weight: 800; color: #1C1B1F; margin-bottom: 4px; }
    .eiin-chip {
        font-size: 0.7rem; background: #EADDFF; color: #21005D;
        padding: 2px 12px; border-radius: 4px; font-weight: 800; display: inline-block;
    }

    /* M3 Condensed Form (8px Radius) */
    .m3-card-form {
        background: #fff; border-radius: 8px; padding: 16px;
        margin: 0 12px 20px; border: 1px solid #f0f0f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .form-floating > .form-control {
        border-radius: 8px !important; border: 1px solid #79747E;
        font-size: 0.9rem; font-weight: 600; background: transparent;
    }
    .form-floating > label { font-size: 0.75rem; color: #6750A4; font-weight: 700; }
    .form-floating > .form-control:focus { border-color: #6750A4; box-shadow: 0 0 0 1px #6750A4; }

    .btn-m3-save {
        background-color: #6750A4; color: #fff; border-radius: 8px;
        padding: 12px; font-weight: 800; border: none; width: 100%;
        letter-spacing: 0.5px; transition: transform 0.15s ease;
    }
    .btn-m3-save:active { transform: scale(0.97); background-color: #4F378B; }

    .icon-inline { position: absolute; right: 12px; top: 18px; color: #6750A4; opacity: 0.6; z-index: 5; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="settings_admin.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons"><i class="bi bi-shield-check text-success fs-5"></i></div>
</header>

<main class="pb-5">
    <div class="hero-section">
        <div class="logo-box">
            <img src="<?php echo $BASE_PATH_URL . 'logo/' . $sccode . '.png'; ?>" 
                 onerror="this.src='https://eimbox.com/images/no-image.png'">
        </div>
        <div class="inst-name"><?php echo $scname; ?></div>
        <div class="eiin-chip">EIIN: <?php echo $sccode; ?></div>
    </div>

    <div class="m3-card-form shadow-sm">
        <div class="mb-3 small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Basic Information</div>
        
        <form id="instEditForm">
            <div class="form-floating mb-3 position-relative">
                <input type="text" id="scname" class="form-control" placeholder="Name" value="<?php echo $scname; ?>">
                <label for="scname">Full Institution Name</label>
                <i class="bi bi-bank icon-inline"></i>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="text" id="add1" class="form-control" placeholder="Address" value="<?php echo $scadd1; ?>">
                <label for="add1">Primary Address</label>
                <i class="bi bi-geo-alt icon-inline"></i>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="text" id="add2" class="form-control" placeholder="Village" value="<?php echo $scadd2; ?>">
                <label for="add2">Village / Ward</label>
                <i class="bi bi-geo icon-inline"></i>
            </div>

            <div class="row gx-2">
                <div class="col-6">
                    <div class="form-floating mb-3">
                        <input type="text" id="ps" class="form-control" placeholder="Upazila" value="<?php echo $ps; ?>">
                        <label for="ps">Upazila</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-floating mb-3">
                        <input type="text" id="dist" class="form-control" placeholder="District" value="<?php echo $dist; ?>">
                        <label for="dist">District</label>
                    </div>
                </div>
            </div>

            <div class="form-floating mb-4 position-relative">
                <input type="tel" id="mno" class="form-control" placeholder="Mobile" value="<?php echo $mobile; ?>">
                <label for="mno">Official Mobile Number</label>
                <i class="bi bi-phone icon-inline"></i>
            </div>

            <button type="button" class="btn-m3-save shadow-sm" onclick="saveInstituteProfile();">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i> UPDATE INFORMATION
            </button>
            <div id="syncStatus" class="mt-3 text-center small fw-bold text-primary"></div>
        </form>
    </div>
</main>

<div style="height: 75px;"></div> <script>
    function saveInstituteProfile() {
        const data = {
            sccode: '<?php echo $sccode; ?>',
            scname: encodeURIComponent(document.getElementById("scname").value),
            add1: document.getElementById("add1").value,
            add2: document.getElementById("add2").value,
            ps: document.getElementById("ps").value,
            dist: document.getElementById("dist").value,
            mno: document.getElementById("mno").value
        };

        const infor = Object.keys(data).map(key => `${key}=${data[key]}`).join('&');

        $.ajax({
            type: "POST",
            url: "backend/update-sc-info.php",
            data: infor,
            beforeSend: function () {
                $('#syncStatus').html('<div class="spinner-border spinner-border-sm me-2"></div> Syncing data...');
            },
            success: function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Institute profile updated.',
                    icon: 'success',
                    confirmButtonColor: '#6750A4',
                    timer: 2000
                }).then(() => {
                    window.location.href = 'settings_admin.php';
                });
            },
            error: function() {
                Swal.fire('Error', 'Update failed.', 'error');
            }
        });
    }
</script>

<?php include 'footer.php'; ?>