<?php
/**
 * Teacher Profile View & Edit - M3-EIM-Floating Style
 * Standards: 8px Radius | Modal Edit | AJAX Update | Android WebView Optimized
 */
include 'inc.php'; 

// ১. ডাটা ফেচিং
$tid_req = $_GET['tid'] ?? 0;
$sccode_req = $sccode; 

$stmt = $conn->prepare("SELECT * FROM teacher WHERE tid = ? AND sccode = ? LIMIT 1");
$stmt->bind_param("is", $tid_req, $sccode_req);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    die("<div class='text-center mt-5 py-5'><i class='bi bi-search display-1 opacity-25'></i><p class='fw-bold'>Teacher Not Found!</p></div>");
}

$page_title = "Staff Profile";

// ফটোর পাথ লজিক
$photo_dir = "../../photos/staff/"; 
$photo_path = $photo_dir . $tid_req . ".jpg";
$display_photo = (file_exists($photo_path)) ? $photo_path : "https://eimbox.com/images/no-image.png";
?>

<style>
    body { background-color: #FEF7FF; margin: 0; padding: 0; }

    /* M3 App Bar */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Profile UI */
    .profile-hero { background: #fff; padding: 30px 16px; text-align: center; border-radius: 0 0 16px 16px; margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .profile-img-box { width: 110px; height: 110px; border-radius: 8px; background: #F3EDF7; border: 3px solid #EADDFF; margin: 0 auto 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(103, 80, 164, 0.15); }
    .profile-img-box img { width: 100%; height: 100%; object-fit: cover; }

    .info-card { background: #fff; border-radius: 8px; padding: 16px; margin: 0 16px 12px; border: 1px solid #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
    .info-label { font-size: 0.65rem; font-weight: 800; color: #79747E; text-transform: uppercase; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    
    .detail-row { display: flex; align-items: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px dashed #F3EDF7; }
    .detail-row:last-child { border-bottom: none; margin-bottom: 0; }
    .detail-icon { width: 32px; height: 32px; border-radius: 6px; background: #F7F2FA; color: #6750A4; display: flex; align-items: center; justify-content: center; margin-right: 12px; }
    .detail-key { font-size: 0.7rem; color: #79747E; font-weight: 700; }
    .detail-val { font-size: 0.9rem; color: #1C1B1F; font-weight: 700; }

    /* FAB (Floating Action Button) */
    .btn-edit-float { position: fixed; bottom: 85px; right: 20px; width: 56px; height: 56px; border-radius: 16px; background: #6750A4; color: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3); z-index: 1000; border: none; }

    /* M3-EIM-Floating Modal Styles */
    .modal-content { border-radius: 8px !important; border: none; }
    .modal-body { max-height: 70vh; overflow-y: auto; padding: 24px 20px; }
    .m3-floating-group { position: relative; margin-bottom: 20px; }
    .m3-field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #6750A4; font-size: 1.2rem; }
    .m3-floating-label { position: absolute; left: 44px; top: -10px; background: #fff; padding: 0 6px; font-size: 0.75rem; font-weight: 700; color: #6750A4; z-index: 15; text-transform: uppercase; }
    .m3-input-floating { width: 100%; height: 52px; padding: 12px 16px 12px 48px; font-size: 0.95rem; font-weight: 600; border: 2px solid #CAC4D0; border-radius: 8px !important; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="teacher-manager.php" class="text-dark me-3"><i class="bi bi-arrow-left fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
</header>

<main class="pb-5">
    <div class="profile-hero shadow-sm">
        <div class="profile-img-box">
            <img src="<?php echo $display_photo; ?>?t=<?php echo time(); ?>" alt="Teacher">
        </div>
        <div class="staff-name h4 fw-black mb-1" id="disp_tname"><?php echo $data['tname']; ?></div>
        <div class="staff-desc text-primary fw-bold small" id="disp_pos_hero"><?php echo $data['position']; ?></div>
        
        <div class="d-flex justify-content-center gap-2 mt-3">
            <a href="tel:<?php echo $data['mobile']; ?>" class="btn btn-sm btn-primary rounded-pill px-3"><i class="bi bi-telephone-fill me-1"></i> CALL</a>
            <a href="https://wa.me/88<?php echo $data['mobile']; ?>" class="btn btn-sm btn-success rounded-pill px-3"><i class="bi bi-whatsapp me-1"></i> WHATSAPP</a>
        </div>
    </div>

    <div class="info-card shadow-sm">
        <div class="info-label"><i class="bi bi-briefcase-fill"></i> Job Profile</div>
        <div class="detail-row">
            <div class="detail-icon"><i class="bi bi-hash"></i></div>
            <div><div class="detail-key">Employee ID</div><div class="detail-val"><?php echo $data['tid']; ?></div></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="bi bi-award"></i></div>
            <div><div class="detail-key">Designation</div><div class="detail-val" id="disp_pos"><?php echo $data['position']; ?></div></div>
        </div>
    </div>

    <div class="info-card shadow-sm">
        <div class="info-label"><i class="bi bi-person-lines-fill"></i> Contact Information</div>
        <div class="detail-row">
            <div class="detail-icon"><i class="bi bi-phone"></i></div>
            <div><div class="detail-key">Mobile</div><div class="detail-val" id="disp_mno"><?php echo $data['mobile']; ?></div></div>
        </div>
    </div>

    <button class="btn-edit-float" onclick="openEditModal();">
        <i class="bi bi-pencil-fill fs-4"></i>
    </button>
</main>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold">Edit Profile</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="tid" value="<?php echo $data['tid']; ?>">
                
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Full Name</label>
                    <i class="bi bi-person m3-field-icon"></i>
                    <input type="text" id="tname" class="m3-input-floating" value="<?php echo $data['tname']; ?>">
                </div>

                <div class="m3-floating-group">
                    <label class="m3-floating-label">Position</label>
                    <i class="bi bi-briefcase m3-field-icon"></i>
                    <select id="pos" class="m3-input-floating">
                        <option value="Head Teacher" <?php if($data['position']=='Head Teacher') echo 'selected'; ?>>Head Teacher</option>
                        <option value="Asstt. Head Teacher" <?php if($data['position']=='Asstt. Head Teacher') echo 'selected'; ?>>Asstt. Head Teacher</option>
                        <option value="Asstt. Teacher" <?php if($data['position']=='Asstt. Teacher') echo 'selected'; ?>>Asstt. Teacher</option>
                        <option value="Office Assistant" <?php if($data['position']=='Office Assistant') echo 'selected'; ?>>Office Assistant</option>
                    </select>
                </div>

                <div class="m3-floating-group">
                    <label class="m3-floating-label">Mobile</label>
                    <i class="bi bi-phone m3-field-icon"></i>
                    <input type="tel" id="mno" class="m3-input-floating" value="<?php echo $data['mobile']; ?>">
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-light m3-8px fw-bold" data-bs-dismiss="modal">CANCEL</button>
                <button class="btn btn-primary m3-8px fw-bold px-4" onclick="updateProfile();">SAVE CHANGES</button>
            </div>
        </div>
    </div>
</div>

<div style="height: 80px;"></div>

<?php include 'footer.php'; ?>

<script>
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));

    function openEditModal() {
        editModal.show();
    }

    function updateProfile() {
        const payload = {
            tid: $('#tid').val(),
            tname: $('#tname').val(),
            pos: $('#pos').val(),
            mno: $('#mno').val(),
            rootuser: '<?php echo $sccode; ?>',
            action: 1
        };

        $.ajax({
            type: "POST",
            url: "settings/addeditteacher.php",
            data: payload,
            beforeSend: function() {
                Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            },
            success: function(res) {
                // UI ডাইনামিকভাবে আপডেট করা হচ্ছে
                $('#disp_tname').text(payload.tname);
                $('#disp_pos_hero').text(payload.pos);
                $('#disp_pos').text(payload.pos);
                $('#disp_mno').text(payload.mno);

                editModal.hide();
                Swal.fire({ icon: 'success', title: 'Profile Updated', timer: 1500, showConfirmButton: false });
            }
        });
    }
</script>