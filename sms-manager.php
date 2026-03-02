<?php
$page_title = 'SMS Manager';
include 'inc.php';
?>

<style>
    :root {
        /* M3 Tonal Palette - Violet/Lavender Theme */
        --m3-surface: #FDFBFF;
        --m3-on-surface: #1C1B1F;
        --m3-surface-container: #F3EDF7;
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-secondary-container: #E8DEF8;
        --m3-outline-variant: #CAC4D0;
    }

    body {
        background-color: var(--m3-surface);
        font-family: 'Roboto', sans-serif;
        user-select: none;
        /* অ্যান্ড্রয়েড ওয়েবভিউ টেক্সট সিলেক্ট বন্ধ রাখতে */
    }

    /* Standard M3 Top App Bar */
    .m3-app-bar {
        background-color: #FFFFFF;
        height: 64px;
        display: flex;
        align-items: center;
        padding: 0 16px;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .m3-app-bar h1 {
        font-size: 22px;
        font-weight: 400;
        margin: 0;
        color: var(--m3-on-surface);
    }

    /* Tonal Card Design */
    .m3-list-item {
        background-color: var(--m3-surface);
        border: 1px solid var(--m3-outline-variant);
        border-radius: 16px;
        /* M3 Medium shape */
        padding: 16px;
        margin: 0 16px 12px 16px;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: background-color 0.2s, transform 0.1s;
    }

    /* Press/Ripple Effect Simulation */
    .m3-list-item:active {
        background-color: var(--m3-secondary-container);
        transform: scale(0.98);
    }

    /* Icon Container - Tonal Style */
    .m3-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
    }

    /* Tonal Variations for Icons */
    .tonal-green {
        background-color: #C2EFAD;
        color: #062100;
    }

    .tonal-orange {
        background-color: #FFDCC3;
        color: #301400;
    }

    .tonal-blue {
        background-color: #D1E4FF;
        color: #001D36;
    }

    .tonal-purple {
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
    }

    .m3-text-content {
        flex-grow: 1;
    }

    .m3-label-large {
        font-size: 16px;
        font-weight: 500;
        color: var(--m3-on-surface);
    }

    .m3-body-medium {
        font-size: 14px;
        color: #49454F;
    }

    .m3-section-header {
        font-size: 14px;
        font-weight: 500;
        color: var(--m3-primary);
        margin: 24px 16px 16px 24px;
        letter-spacing: 0.1px;
    }

    /* System Info Card */
    .m3-info-card {
        background-color: var(--m3-surface-container);
        margin: 0 16px;
        padding: 16px;
        border-radius: 12px;
        color: var(--m3-on-surface);
    }
</style>

<main>


    <div class="m3-section-header">Communication Tools</div>

    <div class="m3-list-item" onclick="navTo('sms-send.php')">
        <div class="m3-icon-box tonal-green">
            <i class="bi bi-send-fill fs-5"></i>
        </div>
        <div class="m3-text-content">
            <div class="m3-label-large">Send Message</div>
            <div class="m3-body-medium">Compose and send instant alerts</div>
        </div>
        <i class="bi bi-chevron-right opacity-50"></i>
    </div>

    <div class="m3-list-item" onclick="navTo('sms-campaign.php')">
        <div class="m3-icon-box tonal-orange">
            <i class="bi bi-megaphone-fill fs-5"></i>
        </div>
        <div class="m3-text-content">
            <div class="m3-label-large">Marketing Campaign</div>
            <div class="m3-body-medium">Broadcast to multiple segments</div>
        </div>
        <i class="bi bi-chevron-right opacity-50"></i>
    </div>

    <div class="m3-list-item" onclick="navTo('sms-history.php')">
        <div class="m3-icon-box tonal-blue">
            <i class="bi bi-clock-history fs-5"></i>
        </div>
        <div class="m3-text-content">
            <div class="m3-label-large">SMS History</div>
            <div class="m3-body-medium">Delivery logs and status tracking</div>
        </div>
        <i class="bi bi-chevron-right opacity-50"></i>
    </div>

    <div class="m3-list-item" onclick="navTo('sms-templates.php')">
        <div class="m3-icon-box tonal-purple">
            <i class="bi bi-layout-text-window-reverse fs-5"></i>
        </div>
        <div class="m3-text-content">
            <div class="m3-label-large">Message Templates</div>
            <div class="m3-body-medium">Quick responses and saved drafts</div>
        </div>
        <i class="bi bi-chevron-right opacity-50"></i>
    </div>

    <?php if ($usr == 'engrreaz@gmail.com'): ?>
        <div class="m3-section-header">System Insights</div>
        <div class="m3-info-card">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-3 color-primary"></i>
                <span class="m3-body-medium">Assigned Classes: <b><?php echo 0; ?></b></span>
            </div>
        </div>
    <?php endif; ?>

</main>

<?php include 'footer.php'; ?>

<script>
    // অ্যান্ড্রয়েড ওয়েবভিউতে দ্রুত নেভিগেশনের জন্য
    function navTo(url) {
        if (window.navigator.vibrate) window.navigator.vibrate(10); // হালকা হ্যাপটিক ফিডব্যাক
        window.location.href = url;
    }
</script>