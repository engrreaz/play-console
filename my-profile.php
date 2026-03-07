<?php
/**
 * User Profile View & Edit - M3-EIM-Floating Style
 * Standards: 8px Radius | Tonal Containers | Modal Edit | Android WebView Optimized
 */
$page_title = "My Profile";
include 'inc.php';

// ১. ডাটা ফেচিং (Prepared Statement)
$stmt = $conn->prepare("SELECT * FROM usersapp WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $usr); // $usr আসে inc.php থেকে
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    die("<div class='text-center mt-5'><p class='fw-bold'>User session expired. Please login again.</p></div>");
}



// ফটো পাথ হ্যান্ডলিং
$photo_path = (empty($user['photourl']) || strlen($user['photourl']) < 10)
    ? "https://eimbox.com/teacher/no-img.jpg"
    : $user['photourl'];
?>

<style>
    /* Profile Hero Card */
    .profile-hero {
        background: #fff;
        padding: 32px 16px;
        text-align: center;
        border-radius: 0 0 40px 40px;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .profile-pic-box {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        /* Strict 8px */
        background: #F3EDF7;
        border: 3px solid #EADDFF;
        margin: 0 auto 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.15);
    }

    .profile-pic-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-name {
        font-size: 1.2rem;
        font-weight: 900;
        color: #1C1B1F;
        margin-bottom: 2px;
    }

    .user-level-badge {
        font-size: 0.65rem;
        background: #EADDFF;
        color: #21005D;
        padding: 3px 12px;
        border-radius: 6px;
        font-weight: 800;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Info Card Design */
    .m3-info-card {
        background: #fff;
        border-radius: 16px;
        padding: 16px;
        margin: 0 16px 12px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .card-label {
        font-size: 0.65rem;
        font-weight: 800;
        color: #6750A4;
        text-transform: uppercase;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .data-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px dashed #F3EDF7;
    }

    .data-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .data-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: #F7F2FA;
        color: #6750A4;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .data-key {
        font-size: 0.65rem;
        color: #79747E;
        font-weight: 700;
    }

    .data-val {
        font-size: 0.85rem;
        color: #1C1B1F;
        font-weight: 700;
        word-break: break-all;
    }

    /* Modal Floating Label Overrides */
    .modal-content {
        border-radius: 8px !important;
    }

    .modal-body {
        max-height: 65vh;
        overflow-y: auto;
        padding: 24px 20px;
    }

    .m3-floating-group {
        position: relative;
        margin-bottom: 20px;
    }

    .m3-field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #6750A4;
        font-size: 1.2rem;
    }

    .m3-floating-label {
        position: absolute;
        left: 44px;
        top: -10px;
        background: #fff;
        padding: 0 6px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6750A4;
        z-index: 15;
        text-transform: uppercase;
    }

    .m3-input-floating {
        width: 100%;
        height: 52px;
        padding: 12px 16px 12px 48px;
        font-size: 0.95rem;
        font-weight: 600;
        border: 2px solid #CAC4D0;
        border-radius: 8px !important;
    }

    .btn-fab {
        position: fixed;
        bottom: 85px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #6750A4;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
        z-index: 1000;
        border: none;
    }
</style>

<style>
    /* M3 Modal Main Container */
    .m3-modal-main {
        background-color: #FEF7FF;
        border-radius: 28px !important;
    }

    /* Tonal Input Box */
    .m3-input-box {
        background-color: #F3EDF7;
        /* Secondary Container Color */
        border-radius: 12px;
        padding: 10px 16px;
        border: 1px solid transparent;
        transition: 0.3s cubic-bezier(0.2, 0, 0, 1);
    }

    .m3-input-box:focus-within {
        background-color: #FFFFFF;
        border-color: #6750A4;
        /* Primary Color */
        box-shadow: 0 0 0 1px #6750A4;
    }

    .m3-field-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 800;
        color: #6750A4;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .m3-field-input {
        width: 100%;
        border: none;
        background: transparent;
        font-weight: 700;
        color: #1C1B1F;
        outline: none;
        padding: 0;
    }

    /* Info Banner */
    .m3-info-banner {
        background-color: #E7E0EC;
        color: #49454F;
        padding: 12px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.4;
    }

    /* Buttons */
    .btn-m3-text {
        color: #6750A4;
        border: none;
        background: transparent;
    }

    .btn-m3-text:hover {
        background-color: rgba(103, 80, 164, 0.08);
    }

    .m3-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<style>
    .profile-pic-box {
        position: relative;
        /* কোণায় বাটন বসানোর জন্য জরুরি */
    }

    .photo-edit-overlay {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 24px;
        height: 24px;
        background: #6750A4;
        /* M3 Primary Color */
        color: white;
        border-radius: 8px;
        /* Strict 8px Radius */
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        transition: 0.3s;
    }

    .photo-edit-overlay:hover {
        background: #21005D;
        transform: scale(1.1);
    }
</style>

<main class="pb-5">
    <div class="profile-hero shadow-sm">
        <div class="profile-pic-box shadow-sm position-relative">
            <img src="<?php echo $photo_path; ?>" id="user_profile_img" alt="User Photo">

            <div class="photo-edit-overlay" onclick="document.getElementById('photo_upload').click();">
                <i class="bi bi-camera-fill"></i>
            </div>

            <input type="file" id="photo_upload" style="display:none;" accept="image/*"
                onchange="uploadProfilePhoto(this)">
        </div>


        <div class="user-name" id="disp_profilename"><?php echo $user['profilename']; ?></div>
        <div class="user-level-badge mb-3"><?php echo $user['userlevel']; ?></div>

        <div class="d-flex justify-content-center gap-2">
            <div class="small fw-bold text-muted"><i class="bi bi-envelope-fill me-1"></i><?php echo $user['email']; ?>
            </div>
        </div>
    </div>

    <div class="m3-info-card shadow-sm">
        <div class="card-label"><i class="bi bi-shield-lock-fill"></i> Administrative Context</div>
        <div class="data-row">
            <div class="data-icon"><i class="bi bi-building"></i></div>
            <div>
                <div class="data-key">Institution Code (EIIN)</div>
                <div class="data-val"><?php echo $user['sccode']; ?></div>
            </div>
        </div>
        <div class="data-row">
            <div class="data-icon"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="data-key">System User ID</div>
                <div class="data-val"><?php echo $user['userid']; ?></div>
            </div>
        </div>
    </div>

    <div class="m3-info-card shadow-sm">
        <div class="card-label"><i class="bi bi-person-lines-fill"></i> Personal & Contact</div>
        <div class="data-row">
            <div class="data-icon"><i class="bi bi-phone"></i></div>
            <div>
                <div class="data-key">Mobile Number</div>
                <div class="data-val" id="disp_mobile"><?php echo $user['mobile']; ?></div>
            </div>
        </div>
        <div class="data-row">
            <div class="data-icon"><i class="bi bi-geo-alt"></i></div>
            <div>
                <div class="data-key">Address / Area</div>
                <div class="data-val"><?php echo $user['area'] . ", " . $user['ps'] . ", " . $user['dist']; ?></div>
            </div>
        </div>
    </div>

    <div class="m3-info-card shadow-sm" style="background: #F7F2FA; border: none;">
        <div class="card-label"><i class="bi bi-clock-history"></i> Recent Activity</div>
        <div class="data-row" style="border-bottom-color: #EADDFF;">
            <div class="data-icon" style="background: #fff;"><i class="bi bi-box-arrow-in-right"></i></div>
            <div>
                <div class="data-key">Last Login</div>
                <div class="data-val small"><?php echo $user['lastlogin']; ?></div>
            </div>
        </div>
    </div>

    <button class="btn-fab" onclick="openEditModal();">
        <i class="bi bi-pencil-fill fs-4"></i>
    </button>
</main>




<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 m3-modal-main shadow-lg">

            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="m3-icon-circle bg-primary-container">
                        <i class="bi bi-person-gear fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-black m-0 text-dark">Edit Personal Info</h5>
                        <p class="small text-muted fw-bold mb-0">Update your account details</p>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-4">
                <input type="hidden" id="edit_id" value="<?php echo $user['id']; ?>">

                <div class="m3-input-box mb-3">
                    <label class="m3-field-label">DISPLAY NAME</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person me-2 text-muted"></i>
                        <input type="text" id="edit_profilename" class="m3-field-input"
                            value="<?php echo $user['profilename']; ?>" placeholder="Full Name">
                    </div>
                </div>

                <div class="m3-input-box mb-3">
                    <label class="m3-field-label">MOBILE NUMBER</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-phone me-2 text-muted"></i>
                        <input type="tel" id="edit_mobile" class="m3-field-input" value="<?php echo $user['mobile']; ?>"
                            placeholder="017xxxxxxxx">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="m3-input-box">
                            <label class="m3-field-label">AREA / LOCALITY</label>
                            <input type="text" id="edit_area" class="m3-field-input"
                                value="<?php echo $user['area']; ?>" placeholder="e.g. Uttara">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="m3-input-box">
                            <label class="m3-field-label">DISTRICT</label>
                            <input type="text" id="edit_dist" class="m3-field-input"
                                value="<?php echo $user['dist']; ?>" placeholder="e.g. Dhaka">
                        </div>
                    </div>
                </div>

                <div class="m3-info-banner d-flex gap-2">
                    <i class="bi bi-info-circle-fill"></i>
                    <span></span>
                </div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button class="btn btn-m3-text fw-bold flex-grow-1" data-bs-dismiss="modal">CANCEL</button>
                <button class="btn btn-primary fw-bold px-4 rounded-pill py-2 flex-grow-1"
                    onclick="saveProfileChanges();">
                    <i class="bi bi-cloud-check-fill me-2"></i>SAVE CHANGES
                </button>
            </div>
        </div>
    </div>
</div>


<?php
// আপনার নির্দেশ অনুযায়ী JS স্ক্রিপ্ট শুরু করার আগে footer.php ইনক্লুড করা হলো
include 'footer.php';
?>

<script>
    const editModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

    function openEditModal() {
        editModal.show();
    }

    function saveProfileChanges() {
        const payload = {
            id: $('#edit_id').val(),
            profilename: $('#edit_profilename').val(),
            mobile: $('#edit_mobile').val(),
            area: $('#edit_area').val(),
            dist: $('#edit_dist').val()
        };
        // alert(JSON.stringify(payload));
        if (!payload.profilename) {
            Swal.fire('Required', 'Display Name cannot be empty.', 'warning');
            return;
        }

        $.ajax({
            type: "POST",
            url: "backend/update-user-profile.php",
            data: payload,
            beforeSend: function () {
                Swal.fire({ title: 'Syncing...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            },
            success: function (res) {
                // UI ডাইনামিকভাবে আপডেট
                $('#disp_profilename').text(payload.profilename);
                $('#disp_mobile').text(payload.mobile);

                editModal.hide();
                Swal.fire({
                    title: 'Profile Updated',
                    text: 'Your changes have been saved successfully.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false

                });
                location.reload();
            },
            error: function () {
                Swal.fire('Error', 'Update failed. Check your connection.', 'error');
            }
        });
    }
</script>

<script>
    function uploadProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('userid', '<?php echo $user['userid']; ?>');

            $.ajax({
                url: "backend/upload-profile-photo.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                },
                success: function (res) {
                    const data = JSON.parse(res);
                    if (data.status === 'success') {
                        // ডাইনামিকভাবে ইমেজ সোর্স আপডেট
                        $('#user_profile_img').attr('src', data.new_url + '?t=' + new Date().getTime());
                        Swal.fire({ icon: 'success', title: 'Photo Updated', timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Server error. Try again.', 'error');
                }
            });
        }
    }
</script>