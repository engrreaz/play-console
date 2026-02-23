<?php
$page_title = 'Security Settings';
include 'inc.php';

// ১. টোকেন আপডেট লজিক (অপরিবর্তিত)
if (isset($_GET['token'])) {
    $devicetoken = $_GET['token'];
    if ($token != $devicetoken) {
        $query33px = "UPDATE usersapp SET token='$devicetoken' WHERE email='$usr' LIMIT 1";
        $conn->query($query33px);
    }
} else {
    $devicetoken = $token;
}
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-on-surface: #1C1B1F;
        --m3-secondary-container: #E8DEF8;
        --m3-on-secondary-container: #1D192B;
    }

    body {
        background-color: var(--m3-surface);
        font-family: 'Inter', sans-serif;
    }

    /* Hero Section */
    .m3-hero {
        background: linear-gradient(135deg, #6750A4 0%, #4527A0 100%);
        color: white;
        padding: 40px 20px 60px;
        border-radius: 0 0 32px 32px;
        text-align: center;
    }

    .m3-avatar-squircle {
        width: 90px;
        height: 90px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(12px);
        border-radius: 28px;
        /* Material 3 Squircle */
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        font-size: 3rem;
    }

    .user-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
        margin-top: 10px;
        text-transform: uppercase;
    }

    /* List Items */
    .m3-card-list {
        background: white;
        border-radius: 16px;
        margin: -30px 16px 20px;
        padding: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .m3-item {
        display: flex;
        align-items: center;
        padding: 16px;
        gap: 16px;
        border-bottom: 1px solid #F4F4F4;
    }

    .m3-item:last-child {
        border-bottom: none;
    }

    .m3-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Block Styles */
    .setup-card {
        background: #fff;
        border-radius: 20px;
        padding: 16px;
        margin: 12px 16px;
        border: 1px solid #E0E0E0;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: 0.3s;
    }

    .setup-card:active {
        transform: scale(0.98);
        background: #F8F8F8;
    }

    .token-box {
        background: #F3EDF7;
        border: 1px dashed #6750A4;
        padding: 12px;
        border-radius: 12px;
        font-family: 'Roboto Mono', monospace;
        font-size: 0.65rem;
        word-break: break-all;
        color: #49454F;
    }
</style>

<style>
    .m3-modal-content {
        border-radius: 28px;
        border: none;
        padding: 10px;
    }

    .m3-input-group {
        background: #F7F2FA;
        border-radius: 12px;
        padding: 5px 15px;
        margin-bottom: 12px;
        border: 1px solid #E7E0EC;
    }

    .m3-input-group input {
        border: none;
        background: transparent;
        padding: 10px;
        font-weight: 600;
        width: 100%;
    }

    .m3-input-group i {
        color: #6750A4;
    }

    .m3-btn-verify {
        background: #6750A4;
        color: white;
        border-radius: 100px;
        padding: 12px 24px;
        font-weight: 700;
        border: none;
        width: 100%;
    }
</style>

<div class="modal fade" id="setstudentbox" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-modal-content">
            <div class="modal-header border-0">
                <h5 class="fw-black m-0">Identity Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger fw-bold" style="font-size: 11px;">Follow your ID Card details carefully:</p>

                <div class="m3-input-group d-flex align-items-center">
                    <i class="bi bi-hash"></i>
                    <input type="number" id="studentid" placeholder="Your ID Number">
                </div>
                <div class="m3-input-group d-flex align-items-center">
                    <i class="bi bi-person"></i>
                    <input type="text" id="studentname" placeholder="Name in English">
                </div>
                <div class="m3-input-group d-flex align-items-center">
                    <i class="bi bi-phone"></i>
                    <input type="tel" id="studentmobile" placeholder="Registered Mobile">
                </div>

                <div id="check2" class="text-center mt-2"></div>
                <button type="button" class="m3-btn-verify mt-3" onclick="submitstudent();">Verify & Sync
                    Profile</button>
            </div>
        </div>
    </div>
</div>


<main class="pb-5">
    <div class="m3-hero">
        <div class="m3-avatar-squircle shadow">
            <i class="bi bi-person-circle"></i>
        </div>
        <h3 class="fw-black mb-0"><?php echo $fullname; ?></h3>
        <div class="user-badge"><?php echo $userlevel; ?> Account</div>
    </div>

    <div class="m3-card-list shadow-sm">
        <div class="m3-item">
            <div class="m3-icon-box" style="background: #E3F2FD; color: #1565C0;"><i class="bi bi-envelope"></i></div>
            <div>
                <small class="text-muted fw-bold text-uppercase" style="font-size: 10px;">Email Address</small>
                <div class="fw-bold"><?php echo $usr; ?></div>
            </div>
        </div>
        <div class="m3-item">
            <div class="m3-icon-box" style="background: #E8F5E9; color: #2E7D32;"><i class="bi bi-telephone"></i></div>
            <div>
                <small class="text-muted fw-bold text-uppercase" style="font-size: 10px;">Mobile Number</small>
                <div class="fw-bold"><?php echo $usrmobile; ?></div>
            </div>
        </div>
    </div>

    <div class="m3-section-title px-4 mb-2">Academic & Roles</div>
    <div class="blocks-container">
        <?php
        // আপনার আগের কন্ডিশনাল লজিক
        if (in_array($userlevel, ['Administrator', 'Super Administrator', 'Teacher', 'Guardian'])) {
            include 'globalblock1.php';
            include 'globalblock2.php';
        } else if ($userlevel == 'Student') {
            include 'globalblock3.php';
        } else {
            include 'globalblock1.php';
            include 'globalblock2.php';
            include 'globalblock3.php';
            include 'globalblock4.php';
        }
        ?>
    </div>

    <div class="m3-section-title px-4 mt-4 mb-2">Device Security</div>
    
    <div class="m3-card-list shadow-sm mx-3 mt-5">
        <div class="p-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="m3-icon-box" style="background: #F1F3F4; color: #4285F4;"><i class="bi bi-google"></i></div>
                <div>
                    <div class="fw-black" style="font-size: 0.9rem;">Google Security Key</div>
                    <small class="text-muted">Verified device synchronization</small>
                </div>
            </div>
            <small class="fw-bold text-primary mb-1 d-block" style="font-size: 10px;">ACTIVE DEVICE TOKEN</small>
            <div class="token-box"><?php echo $devicetoken; ?></div>

            <div class="mt-3 d-flex align-items-center gap-2 text-success fw-bold" style="font-size: 0.75rem;">
                <i class="bi bi-patch-check-fill"></i>
                <span>Secure connection established</span>
            </div>
        </div>
    </div>




    <div class="m3-list-item shadow-sm mt-3" style="background: #FFFBFF; border: 1px solid #79747E;"
        data-bs-toggle="modal" data-bs-target="#changePasswordModal">
        <div class="m3-icon-box" style="background: #F9DEDC; color: #B3261E;"><i class="bi bi-key"></i></div>
        <div class="item-info">
            <div class="st-desc" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; opacity: 0.6;">
                Account Security</div>
            <div class="st-title" style="font-size: 0.95rem;">Change Password</div>
        </div>
        <i class="bi bi-chevron-right text-muted"></i>
    </div>

</main>



<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-modal-content">
            <div class="modal-header border-0">
                <h5 class="fw-black m-0">Secure Password Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">হালনাগাদ করতে আপনার বর্তমান এবং নতুন পাসওয়ার্ড দিন।</p>
                <form id="passForm">
                    <div class="m3-input-group d-flex align-items-center">
                        <i class="bi bi-shield-lock"></i>
                        <input type="password" name="old_pass" placeholder="Current Password" required>
                    </div>
                    <div class="m3-input-group d-flex align-items-center">
                        <i class="bi bi-shield-plus"></i>
                        <input type="password" name="new_pass" id="new_pass" placeholder="New Password" required>
                    </div>
                    <div id="pass_msg" class="text-center mt-2"></div>
                    <button type="submit" class="m3-btn-verify mt-3">Update with Argon2id</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('passForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let fd = new FormData(this);
        fd.append('update_pass', 1);

        fetch('security/update-security.php', { method: 'POST', body: fd })
            .then(r => r.text())
            .then(t => {
                if (t.trim() == "1") {
                    Swal.fire('Success', 'Password updated securely!', 'success').then(() => location.reload());
                } else {
                    document.getElementById('pass_msg').innerHTML = `<span class="text-danger small fw-bold">${t}</span>`;
                }
            });
    });
</script>

<?php include 'footer.php'; ?>