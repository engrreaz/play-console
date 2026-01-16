<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* M3 App Bar Style */
    .m3-app-bar {
        background-color: #FFFFFF;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    /* Communication Card Style */
    .comm-card {
        background-color: #FFFFFF;
        border: none;
        border-radius: 24px;
        padding: 16px;
        margin: 0 12px 12px 12px;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        cursor: pointer;
    }

    .comm-card:active {
        background-color: #EADDFF; /* M3 Primary Container on press */
        transform: scale(0.97);
    }

    /* Tonal Icon Container */
    .icon-box {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        flex-shrink: 0;
    }

    /* Segmented Colors */
    .bg-send { background-color: #E8F5E9; color: #2E7D32; } /* Success Green */
    .bg-camp { background-color: #FFF3E0; color: #E65100; } /* Warning Orange */
    .bg-hist { background-color: #E3F2FD; color: #1976D2; } /* Info Blue */
    .bg-temp { background-color: #F3EDF7; color: #6750A4; } /* Primary Purple */

    .comm-title { font-weight: 700; color: #1C1B1F; font-size: 1rem; margin-bottom: 2px; }
    .comm-desc { font-size: 0.75rem; color: #49454F; line-height: 1.3; }

    .section-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6750A4;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin: 24px 16px 12px 24px;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="settings_admin.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h4 class="fw-bold mb-0">SMS Manager</h4>
                <small class="text-muted">Communication Center</small>
            </div>
        </div>
    </div>

    <div class="section-label">Messaging Tools</div>

    <div class="comm-card shadow-sm" onclick="sms_manager_send();">
        <div class="icon-box bg-send">
            <i class="bi bi-send-plus-fill fs-4"></i>
        </div>
        <div class="flex-grow-1">
            <div class="comm-title">Send Message</div>
            <div class="comm-desc">Compose and send SMS to students or staff instantly</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="comm-card shadow-sm" onclick="sms_manager_campaign();">
        <div class="icon-box bg-camp">
            <i class="bi bi-megaphone-fill fs-4"></i>
        </div>
        <div class="flex-grow-1">
            <div class="comm-title">Marketing Campaign</div>
            <div class="comm-desc">Create broadcast campaigns for school events</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="comm-card shadow-sm" onclick="sms_manager_history();">
        <div class="icon-box bg-hist">
            <i class="bi bi-clock-history fs-4"></i>
        </div>
        <div class="flex-grow-1">
            <div class="comm-title">SMS History</div>
            <div class="comm-desc">Track delivery status and message logs</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <div class="comm-card shadow-sm" onclick="sms_manager_templetes();">
        <div class="icon-box bg-temp">
            <i class="bi bi-layout-text-window-reverse fs-4"></i>
        </div>
        <div class="flex-grow-1">
            <div class="comm-title">Message Templates</div>
            <div class="comm-desc">Manage rapid-response templates for common alerts</div>
        </div>
        <i class="bi bi-chevron-right text-muted small"></i>
    </div>

    <?php if ($usr == 'engrreaz@gmail.com'): ?>
        <div class="section-label">System Logs</div>
        <div class="mx-3 p-3 rounded-4 bg-white border border-light shadow-sm">
            <p class="small text-muted mb-0">Total Classes Assigned: <b><?php echo $count_class; ?></b></p>
        </div>
    <?php endif; ?>

</main>

<div style="height:70px;"></div>

<script>
    // Navigation Functions
    function sms_manager_send() { window.location.href = "sms-send.php"; }
    function sms_manager_campaign() { window.location.href = "sms-campaign.php"; }
    function sms_manager_history() { window.location.href = "sms-history.php"; }
    function sms_manager_templetes() { window.location.href = "sms-templates.php"; }

    // Legacy function placeholder (In case needed for sub-pages)
    function go() {
        var cls = document.getElementById("classname")?.value;
        if(cls) {
            // Logic here
        }
    }
</script>

<?php include 'footer.php'; ?>