<?php
include 'inc.php'; // এটি header.php এবং DB কানেকশন লোড করবে

// ১. ডাটা ফেচিং (Prepared Statement - Secure)
$user_data = [];
$stmt = $conn->prepare("SELECT * FROM usersapp WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $usr);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $user_data = $row;
}
$stmt->close();

// ফটো পাথ হ্যান্ডলিং
$photo_path = $user_data['photourl'] ?? "";
if (strlen($photo_path) < 10) {
    $photo_path = "https://eimbox.com/teacher/no-img.jpg";
}
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Profile Header Styling */
    .profile-hero {
        background: linear-gradient(180deg, #6750A4 0%, #9581CD 100%);
        padding: 40px 20px 80px;
        border-radius: 0 0 32px 32px;
        text-align: center;
        color: white;
        margin-bottom: -40px;
        position: relative;
    }

    .profile-pic-frame {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #fff;
        background: #fff;
        margin: 0 auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        overflow: hidden;
        position: relative;
        z-index: 5;
    }
    .profile-pic-frame img { width: 100%; height: 100%; object-fit: cover; }

    .photo-edit-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #6750A4;
        color: white;
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid white;
    }

    /* M3 Input Group Style */
    .m3-card {
        background: #fff;
        border-radius: 28px;
        padding: 24px;
        margin-top: 15px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

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
        padding: 14px 24px;
        font-weight: 600;
        width: 100%;
        border: none;
        box-shadow: 0 2px 6px rgba(103, 80, 164, 0.3);
        transition: transform 0.2s;
    }
    .btn-update:active { transform: scale(0.96); }

    .input-icon {
        position: absolute;
        right: 15px;
        top: 18px;
        color: #6750A4;
        z-index: 5;
    }
</style>

<main class="pb-5">
    <div class="profile-hero shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="build.php" class="text-white"><i class="bi bi-arrow-left fs-4"></i></a>
            <h5 class="fw-bold mb-0">Personal Profile</h5>
            <div style="width: 24px;"></div>
        </div>

        <div class="profile-pic-frame shadow-lg">
            <img src="<?php echo $photo_path; ?>" onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
            <div class="photo-edit-btn"><i class="bi bi-camera-fill small"></i></div>
        </div>
        
        <h4 class="mt-3 fw-bold mb-0"><?php echo $user_data['profilename']; ?></h4>
        <p class="small opacity-75"><?php echo $usr; ?></p>
    </div>

    <div class="container-fluid px-3 pt-5">
        <div class="m3-card shadow-sm mt-4">
            <h6 class="text-secondary fw-bold small text-uppercase mb-4">Account Settings</h6>
            
            <form id="profileForm">
                <div class="form-floating mb-3 position-relative">
                    <input type="text" id="dispname" class="form-control" placeholder="Full Name" 
                           value="<?php echo $user_data['profilename']; ?>">
                    <label for="dispname">Display Name</label>
                    <i class="bi bi-person-circle input-icon"></i>
                </div>

                <div class="form-floating mb-4 position-relative">
                    <input type="tel" id="mobile" class="form-control" placeholder="Mobile" 
                           value="<?php echo $user_data['mobile']; ?>">
                    <label for="mobile">Mobile Number</label>
                    <i class="bi bi-telephone-fill input-icon"></i>
                </div>

                <button type="button" class="btn-update shadow-sm" 
                        onclick="update_user_profile_info(<?php echo $user_data['id']; ?>);">
                    <i class="bi bi-cloud-check-fill me-2"></i> Update Profile Info
                </button>
                
                <div id="px" class="text-center mt-3 fw-bold text-primary small"></div>
            </form>
        </div>

        <div class="px-4 mt-4">
            <div class="d-flex align-items-start text-muted">
                <i class="bi bi-info-circle me-2 mt-1"></i>
                <p style="font-size: 0.75rem; line-height: 1.4;">
                    Your profile information is visible to the institution administration. Keep your contact number updated for system alerts.
                </p>
            </div>
        </div>
    </div>
</main>

<div style="height: 60px;"></div>



<script>
    function update_user_profile_info(id) {
        const nameeng = document.getElementById("dispname").value;
        const mno = document.getElementById("mobile").value;

        if(!nameeng) {
            Swal.fire('Required', 'Display Name cannot be empty.', 'warning');
            return;
        }

        const infor = `dispname=${encodeURIComponent(nameeng)}&mno=${encodeURIComponent(mno)}&id=${id}`;

        $.ajax({
            type: "POST",
            url: "backend/update-user-profile.php",
            data: infor,
            beforeSend: function () {
                $('#px').html('<div class="spinner-border spinner-border-sm me-1"></div> Syncing Data...');
            },
            success: function (html) {
                $("#px").html(html);
                Swal.fire({
                    title: 'Profile Updated',
                    text: 'Your information has been saved successfully.',
                    icon: 'success',
                    confirmButtonColor: '#6750A4'
                });
            },
            error: function() {
                Swal.fire('Error', 'Update failed. Check your connection.', 'error');
            }
        });
    }
</script>

<?php include 'footer.php'; ?>