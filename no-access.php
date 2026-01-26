<?php 
// $page_title = "Access Denied";
include 'header.php'; 
?>

<style>
    /* ১. হিরো সেকশন কাস্টমাইজেশন (Error Theme) */
    .hero-no-access {
        background: linear-gradient(135deg, #B3261E 0%, #8C1D18 100%);
        padding-bottom: 50px;
        border-radius: 0 0 32px 32px;
    }

    /* ২. এরর কার্ড ডিজাইন */
    .error-card {
        background: #fff;
        border-radius: 16px !important; /* M3 standard for large containers */
        margin: -40px 20px 20px;
        padding: 40px 24px;
        text-align: center;
        border: 1px solid rgba(179, 38, 30, 0.1);
    }

    .restricted-icon-box {
        width: 80px; height: 80px;
        background: #F9DEDC; /* Tonal Error Container */
        color: #410E0B;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 20px;
        box-shadow: 0 8px 16px rgba(179, 38, 30, 0.1);
    }

    .btn-return {
        background: #B3261E;
        color: white;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        transition: 0.3s;
        display: inline-flex;
        align-items: center; gap: 8px;
        margin-top: 20px;
    }
    .btn-return:active { transform: scale(0.95); opacity: 0.9; }
</style>

<main>
    <div class="hero-container hero-no-access shadow-lg">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" onclick="history.back()">
                <i class="bi bi-chevron-left"></i>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;">Security Alert</div>
                <div style="font-size: 0.8rem; opacity: 0.85; font-weight: 600;">Unauthorized Access Attempt</div>
            </div>
        </div>
    </div>

    <div class="error-card shadow-lg">
        <div class="restricted-icon-box">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <h4 style="font-weight: 950; color: #1C1B1F; margin-bottom: 8px;">ACCESS RESTRICTED</h4>
        <p style="font-size: 0.9rem; color: #49454F; line-height: 1.5;">
            Sorry, you don't have the necessary <b>permissions</b> to view this module. 
            This action has been logged for security monitoring.
        </p>

        <div style="background: #F7F2FA; padding: 12px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #B3261E;">
            <div style="font-size: 0.7rem; font-weight: 800; color: #6750A4; text-transform: uppercase; text-align: left;">Reason:</div>
            <div style="font-size: 0.8rem; color: #444; text-align: left; font-weight: 600;">
                Missing User Level Authorization
            </div>
        </div>

        <button class="btn-return shadow-sm" onclick="location.href='index.php'">
            <i class="bi bi-house-door-fill"></i> BACK TO DASHBOARD
        </button>

        <div style="margin-top: 25px; font-size: 0.75rem; color: #999; font-weight: 600;">
            If you believe this is a mistake, please contact your <br>
            <span style="color: #6750A4;">System Administrator</span>.
        </div>
    </div>
</main>



<?php include 'footer.php'; ?>