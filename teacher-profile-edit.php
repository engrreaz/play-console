<?php
$page_title = "Edit Teacher Profile";
include 'inc.php';

$tid = $_GET['id'] ?? '';

// ---------- Permission ----------
$profile_entry_permission = 0;
$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
if (isset($settings_map['Profile Entry']) && strpos($settings_map['Profile Entry'], $userlevel) !== false) {
    $profile_entry_permission = 1;
}
if (!$profile_entry_permission) {
    die("Access Denied");
}

// ---------- AJAX SAVE (Unchanged Logic) ----------
if (isset($_POST['ajax_save'])) {
    $tname = $_POST['tname'];
    $tnameb = $_POST['tnameb'];
    $position = $_POST['position'];
    $ranks = $_POST['ranks'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $img = $_POST['cropped_image'];

    $ok = false;
    $upd = $conn->prepare("UPDATE teacher SET tname=?,tnameb=?,position=?,ranks=?,mobile=?,email=? WHERE tid=? AND sccode=?");
    $upd->bind_param("sssssssi", $tname, $tnameb, $position, $ranks, $mobile, $email, $tid, $sccode);
    if ($upd->execute())
        $ok = true;

    if ($ok && !empty($img)) {
        $data = explode(',', $img);
        if (isset($data[1])) {
            $decoded = base64_decode($data[1]);
            if (strlen($decoded) > 2 * 1024 * 1024) {
                echo json_encode(['status' => 0, 'msg' => 'Image Too Large']);
                exit;
            }
            if (!is_dir("teacher"))
                mkdir("teacher", 0777, true);
            $tmp = "teacher/tmp_" . $tid . ".jpg";
            file_put_contents($tmp, $decoded);
            $src = imagecreatefromjpeg($tmp);
            $final = "../teacher/" . $tid . ".jpg";
            imagejpeg($src, $final, 75);
            imagedestroy($src);
            unlink($tmp);
        }
    }
    echo json_encode(['status' => $ok ? 1 : 0, 'msg' => $ok ? 'Profile Updated Successfully' : 'Database Failed']);
    exit;
}

// ---------- Fetch Data ----------
$stmt = $conn->prepare("SELECT * FROM teacher WHERE tid=? AND sccode=? LIMIT 1");
$stmt->bind_param("si", $tid, $sccode);
$stmt->execute();
$tp = $stmt->get_result()->fetch_assoc();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-surface: #FDF7FF;
        --m3-tonal: #EADDFF;
    }

    body {
        background: var(--m3-surface);
        font-family: 'Inter', sans-serif;
    }

    /* Modern Hero Header */
    .edit-hero {
        background: linear-gradient(135deg, #6750A4 0%, #4527A0 100%);
        color: white;
        padding: 30px 20px 80px;
        border-radius: 0 0 32px 32px;
        position: relative;
        overflow: hidden;
    }

    /* Floating Avatar */
    .photo-section {
        margin-top: -65px;
        text-align: center;
        margin-bottom: 25px;
        position: relative;
        z-index: 10;
    }

    .profile-preview-box {
        width: 110px;
        height: 140px;
        margin: auto;
        border-radius: 24px;
        overflow: hidden;
        border: 4px solid #fff;
        background: #eee;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    .profile-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cam-btn-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 6px 0;
        font-size: 0.65rem;
        font-weight: 800;
        cursor: pointer;
        backdrop-filter: blur(4px);
    }

    /* M3 Input Styles */
    .form-container {
        padding: 0 16px 120px;
    }

    .m3-card {
        background: white;
        border-radius: 28px;
        padding: 20px;
        border: 1px solid #E7E0EC;
        margin-bottom: 20px;
    }

    .input-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--m3-primary);
        margin: 0 0 6px 4px;
        display: block;
        text-transform: uppercase;
    }

    .m3-input {
        width: 100%;
        padding: 12px 16px;
        margin-bottom: 18px;
        border-radius: 12px;
        border: 1px solid #79747E;
        font-size: 0.95rem;
        font-weight: 500;
        transition: 0.2s;
    }

    .m3-input:focus {
        border-color: var(--m3-primary);
        border-width: 2px;
        outline: none;
        box-shadow: 0 0 0 4px rgba(103, 80, 164, 0.1);
    }

    /* Extended FAB (Save Button) */
    .save-fab {
        position: fixed;
        bottom: 80px;
        right: 20px;
        padding: 16px 28px;
        background: var(--m3-tonal);
        color: #21005D;
        border: none;
        border-radius: 16px;
        font-weight: 900;
        font-size: 0.9rem;
        box-shadow: 0 8px 20px rgba(103, 80, 164, 0.3);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 1000;
    }

    .save-fab:active {
        transform: scale(0.95);
    }

    /* Modal Styling */
    #cropperModal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
    }

    .m3-dialog {
        width: 92%;
        max-width: 400px;
        margin: 50px auto;
        background: white;
        border-radius: 28px;
        overflow: hidden;
    }

    .crop-area {
        height: 320px;
        background: #222;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<main>
    <div class="edit-hero">
        <div class="d-flex align-items-center">

            <h4 class="fw-black m-0 flex-grow-1">Edit Profile</h4>
            <div class="small opacity-75 fw-bold">ID: <?= $tid ?></div>

        </div>
    </div>

    <form id="profileForm">
        <div class="photo-section">
            <div class="profile-preview-box">
                <img id="main-preview" src="<?= teacher_profile_image_path($tid) ?>"
                    onerror="this.src='iimg/default_teacher.png'">
                <label for="photo-input" class="cam-btn-overlay"><i class="bi bi-camera-fill"></i> CHANGE</label>
            </div>
            <input type="file" id="photo-input" accept="image/*" style="display:none">
            <input type="hidden" name="cropped_image" id="cropped_image_input">
        </div>

        <div class="form-container">
            <div class="m3-card shadow-sm">
                <label class="input-label">Full Name (English)</label>
                <input name="tname" class="m3-input" value="<?= htmlspecialchars($tp['tname']) ?>" required
                    placeholder="Enter full name">

                <label class="input-label">Full Name (Bengali)</label>
                <input name="tnameb" class="m3-input" value="<?= htmlspecialchars($tp['tnameb']) ?>"
                    placeholder="নাম বাংলায়">

                <label class="input-label">Current Position</label>
                <input name="position" class="m3-input" value="<?= htmlspecialchars($tp['position']) ?>"
                    placeholder="e.g. Asst. Teacher">
            </div>

            <div class="m3-card shadow-sm">
                <label class="input-label">Mobile Number</label>
                <input name="mobile" class="m3-input" value="<?= htmlspecialchars($tp['mobile']) ?>"
                    placeholder="017xxxxxxxx">

                <label class="input-label">Email Address</label>
                <input name="email" class="m3-input" value="<?= htmlspecialchars($tp['email']) ?>"
                    placeholder="example@mail.com">

                <input type="hidden" name="ranks" value="<?= $tp['ranks'] ?>">
            </div>
        </div>

        <button type="submit" class="save-fab">
            <i class="bi bi-cloud-arrow-up-fill fs-5"></i> SAVE CHANGES
        </button>
    </form>
</main>

<div id="cropperModal">
    <div class="m3-dialog">
        <div class="px-4 py-3 border-bottom fw-black text-muted" style="font-size:0.8rem; text-transform:uppercase;">
            Adjust Photo</div>
        <div class="crop-area">
            <img id="image-to-crop" style="max-width:100%">
        </div>
        <div class="p-3 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" onclick="closeModal()">Cancel</button>
            <button type="button" id="cropBtn" class="btn btn-primary rounded-pill px-4 fw-bold">Apply Crop</button>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    let cropper;
    const input = document.getElementById('photo-input');
    const modal = document.getElementById('cropperModal');
    const img = document.getElementById('image-to-crop');

    // Select & Prepare Image
    input.addEventListener('change', e => {
        let f = e.target.files[0];
        if (!f) return;
        if (f.size > 2 * 1024 * 1024) { Swal.fire('Max 2MB allowed'); return; }

        let reader = new FileReader();
        reader.onload = x => {
            img.src = x.target.result;
            modal.style.display = 'block';
            if (cropper) cropper.destroy();
            cropper = new Cropper(img, {
                aspectRatio: 150 / 190,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1
            });
        };
        reader.readAsDataURL(f);
    });

    // Apply Crop
    document.getElementById('cropBtn').onclick = () => {
        let canvas = cropper.getCroppedCanvas({ width: 150, height: 190 });
        let base64 = canvas.toDataURL('image/jpeg', 0.9);
        document.getElementById('main-preview').src = base64;
        document.getElementById('cropped_image_input').value = base64;
        closeModal();
    };

    function closeModal() {
        modal.style.display = 'none';
        input.value = '';
    }

    // ---------- AJAX SAVE ----------
    document.getElementById('profileForm').addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Saving...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        let fd = new FormData(this);
        fd.append('ajax_save', 1);

        fetch('', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.status) {
                    Swal.fire({ icon: 'success', title: 'Success', text: d.msg, timer: 1500, showConfirmButton: false })
                        .then(() => history.back());
                } else {
                    Swal.fire('Error', d.msg, 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Connection Failed', 'error'));
    });
</script>