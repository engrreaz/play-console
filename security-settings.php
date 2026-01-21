<?php
/**
 * Login Security Settings - M3-EIM-Floating Style
 * Standards: 8px Radius | Tonal Containers | Floating Labels | Webview Optimized
 */
$page_title = "Login Security";
include 'inc.php'; 

// ১. ওটিপি এক্সপায়ারি লজিক
$diff = strtotime($cur) - strtotime($otptime);
if ($diff > 120) {
    $otp = '';
}
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

    /* Security Item Card */
    .m3-security-card {
        background: #fff; border-radius: 8px; padding: 16px;
        margin: 12px 16px; border: 1px solid #f0f0f0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: 0.2s;
    }
    .m3-security-card:active { background-color: #F7F2FA; }

    .sec-icon-box {
        width: 44px; height: 44px; border-radius: 8px;
        background: #F3EDF7; color: #6750A4;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0; margin-right: 16px;
    }

    .sec-title { font-size: 0.95rem; font-weight: 800; color: #1C1B1F; margin-bottom: 2px; }
    .sec-subtitle { font-size: 0.75rem; color: #79747E; font-weight: 500; }

    /* OTP Display Area */
    .otp-display-box {
        background: #F3EDF7; border-radius: 8px; padding: 12px;
        margin-top: 12px; text-align: center; border: 1px dashed #6750A4;
    }
    .otp-code {
        font-size: 2rem; color: #21005D; letter-spacing: 8px;
        font-weight: 900; font-family: 'Courier New', Courier, monospace;
    }

    /* Custom Switch Styling */
    .form-check-input:checked { background-color: #6750A4; border-color: #6750A4; }
    .form-check-input { width: 3em !important; height: 1.5em !important; cursor: pointer; }

    /* M3-EIM-Floating Input overrides for compact display */
    .compact-group { margin-top: 15px; margin-bottom: 5px; }
</style>


<main class="pb-5 pt-2">

    <div class="m3-security-card shadow-sm" onclick="generate_otp();" style="cursor:pointer;">
        <div class="d-flex align-items-center">
            <div class="sec-icon-box"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="flex-grow-1">
                <div class="sec-title">Web Login Token</div>
                <div class="sec-subtitle">Generate a 2-minute temporary token</div>
            </div>
            <i class="bi bi-chevron-right text-muted"></i>
        </div>
        
        <div id="keykey">
            <?php if ($otp != '') { ?>
                <div class="otp-display-box">
                    <small class="text-uppercase fw-bold text-primary" style="font-size: 0.6rem;">Active Web Token</small>
                    <div class="otp-code"><?php echo $otp; ?></div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="m3-security-card shadow-sm">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="sec-icon-box"><i class="bi bi-key-fill"></i></div>
            <div>
                <div class="sec-title">Fixed Password</div>
                <div class="sec-subtitle">Secure login via PIN/Password</div>
            </div>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="passswitch" onclick="togglePassBox();" checked>
        </div>
    </div>

    <div id="passbox" class="mt-3">
        <div class="m3-floating-group mb-3">
            <label class="m3-floating-label">New Password</label>
            <i class="bi bi-lock m3-field-icon"></i>
            <input type="password" id="password" 
                   class="m3-input-floating" 
                   placeholder="Enter new password"
                   style="border-radius: 8px !important;">
        </div>

        <button class="btn-m3-submit shadow-sm" 
                type="button" 
                onclick="update_password();"
                style="height: 52px; font-size: 0.9rem; letter-spacing: 1px;" disabled>
            <i class="bi bi-check2-circle me-2"></i> UPDATE PASSWORD
        </button>
    </div>
</div>

    <div class="m3-security-card shadow-sm">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="sec-icon-box" style="background: #fff; border: 1px solid #eee;"><i class="bi bi-google text-danger"></i></div>
                <div>
                    <div class="sec-title">Google Authentication</div>
                    <div class="sec-subtitle">Sign in using your Gmail account</div>
                </div>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="googleSwitch" checked>
            </div>
        </div>
    </div>

    <div class="m3-security-card shadow-sm">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="sec-icon-box"><i class="bi bi-qr-code-scan"></i></div>
                <div>
                    <div class="sec-title">QR Code Access</div>
                    <div class="sec-subtitle">Instant login via mobile scan</div>
                </div>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="qrSwitch" checked>
            </div>
        </div>
    </div>

</main>

<div style="height: 60px;"></div>

<?php 
// আপনার নির্দেশ অনুযায়ী JS স্ক্রিপ্ট শুরু করার আগে footer.php ইনক্লুড করা হলো
include 'footer.php'; 
?>

<script>
    /**
     * পাসওয়ার্ড বক্স হাইড/শো লজিক
     */
    function togglePassBox() {
        const isChecked = document.getElementById("passswitch").checked;
        const passBox = document.getElementById("passbox");
        const passInput = document.getElementById("password");
        
        if (isChecked) {
            $(passBox).slideDown(200);
            passInput.disabled = false;
        } else {
            $(passBox).slideUp(200);
            passInput.disabled = true;
        }
    }

    /**
     * OTP জেনারেশন লজিক
     */
    function generate_otp() {
        const infor = "user=<?php echo $usr; ?>";
        
        $.ajax({
            type: "POST",
            url: "backend/genotp.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#keykey').html(`
                    <div class="otp-display-box" style="opacity: 0.7;">
                        <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                        <small class="fw-bold">Generating Token...</small>
                    </div>
                `);
            },
            success: function (html) {
                // রিফ্যাক্টর করা OTP ব্লক রিটার্ন করবে backend/genotp.php থেকে
                $("#keykey").hide().html(html).fadeIn(300);
            }
        });
    }

    // প্রাথমিক চেক
    $(document).ready(function() {
        togglePassBox();
    });
</script>