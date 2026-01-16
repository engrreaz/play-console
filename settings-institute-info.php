<?php
include 'inc.php'; // এটি header.php এবং DB কানেকশন লোড করবে

// ১. ডাটা ফেচিং (Prepared Statement - Secure & Optimized)
$scname = $scadd1 = $scadd2 = $ps = $dist = $logo = $mobile = $rootuser = "";

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
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Institution Identity Card */
    .identity-card {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 28px;
        color: white;
        padding: 30px 20px;
        margin-bottom: 24px;
        text-align: center;
        border: none;
    }
    
    .inst-logo-container {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 24px;
        padding: 10px;
        margin: 0 auto 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .inst-logo-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Form Card Styling */
    .m3-form-card {
        background: white;
        border-radius: 28px;
        padding: 24px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* M3 Floating Labels */
    .form-floating > .form-control {
        border-radius: 12px;
        border: 1px solid #79747E;
        background: transparent;
    }
    .form-floating > .form-control:focus {
        border-color: #6750A4;
        box-shadow: 0 0 0 1px #6750A4;
    }

    .btn-update {
        background-color: #6750A4;
        color: white;
        border-radius: 100px;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        transition: 0.2s;
    }
    .btn-update:active { transform: scale(0.95); opacity: 0.9; }

    .input-icon {
        position: absolute;
        right: 15px;
        top: 18px;
        color: #6750A4;
        z-index: 5;
    }
</style>

<main class="container mt-3 pb-5">
    <div class="d-flex align-items-center mb-4 px-2">
        <a href="settings_admin.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
        <h4 class="fw-bold mb-0">Institution Profile</h4>
    </div>

    <div class="identity-card shadow-lg">
        <div class="inst-logo-container">
            <img src="<?php echo $BASE_PATH_URL . 'logo/' . $sccode . '.png'; ?>" 
                 onerror="this.src='https://eimbox.com/images/no-image.png'">
        </div>
        <h3 class="fw-bold mb-1"><?php echo $scname; ?></h3>
        <div class="badge rounded-pill bg-white text-primary px-3 py-2 mb-2">EIIN: <?php echo $sccode; ?></div>
        <p class="small opacity-75 mb-0">Established Institution of EIMBox Network</p>
    </div>

    <div class="m3-form-card shadow-sm">
        <h6 class="text-secondary fw-bold small text-uppercase mb-4">Edit Basic Information</h6>
        
        <form id="instForm">
            <div class="form-floating mb-3 position-relative">
                <input type="text" id="scname" class="form-control" placeholder="Name" value="<?php echo $scname; ?>">
                <label for="scname">Institution Name</label>
                <i class="bi bi-bank input-icon"></i>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="text" id="add1" class="form-control" placeholder="Address 1" value="<?php echo $scadd1; ?>">
                <label for="add1">Address Line 1</label>
                <i class="bi bi-geo-alt input-icon"></i>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="text" id="add2" class="form-control" placeholder="Address 2" value="<?php echo $scadd2; ?>">
                <label for="add2">Address Line 2 (Optional)</label>
                <i class="bi bi-geo input-icon"></i>
            </div>

            <div class="row g-2">
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
                <i class="bi bi-telephone input-icon"></i>
            </div>

            <div class="text-center mt-2">
                <button type="button" class="btn-update w-100 shadow-sm" onclick="update_institute_info();">
                    <i class="bi bi-cloud-check-fill me-2"></i> Save Changes
                </button>
                <div id="px" class="mt-3"></div>
            </div>
        </form>
    </div>
</main>

<div style="height: 70px;"></div>

<script>
    function update_institute_info() {
        const scname = encodeURIComponent(document.getElementById("scname").value);
        const add1 = document.getElementById("add1").value;
        const add2 = document.getElementById("add2").value;
        const ps = document.getElementById("ps").value;
        const dist = document.getElementById("dist").value;
        const mno = document.getElementById("mno").value;

        const infor = `sccode=<?php echo $sccode; ?>&scname=${scname}&add1=${add1}&add2=${add2}&ps=${ps}&dist=${dist}&mno=${mno}`;

        $.ajax({
            type: "POST",
            url: "backend/update-sc-info.php",
            data: infor,
            beforeSend: function () {
                $('#px').html('<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Updating...');
            },
            success: function (html) {
                Swal.fire({
                    title: 'Updated!',
                    text: 'Institute information saved successfully.',
                    icon: 'success',
                    confirmButtonColor: '#6750A4'
                }).then(() => {
                    window.location.href = 'settings_admin.php';
                });
            },
            error: function() {
                Swal.fire('Error', 'Failed to update information.', 'error');
            }
        });
    }
</script>

<?php include 'footer.php'; ?>