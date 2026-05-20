<div id="bottom-nav-bar" class="bottom-nav-container noprint">
    <div class="bottom-nav">
            <a href="index.php" class="nav-item <?= isActive('index.php', $curfile) ?>" data-action="Navigation">
                <div class="icon-wrapper"><i class="bi bi-house-fill"></i></div>
                <span>Home</span>
            </a>
            <a href="privacy-policy.php" class="nav-item <?= isActive('privacy-policy.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-file-earmark-lock"></i></div>
                <span>Privacy Policy</span>
            </a>
            <a href="tc.php" class="nav-item <?= isActive('tc.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-journal-text"></i></div>
                <span>Terms & Conditions</span>
            </a>
         

      

    </div>
</div>

<style>
    :root {
        --m3-nav-bg: #FFFFFF;
        --m3-primary: #6750A4;
        --m3-secondary: #49454F;
        --m3-tonal-pill: #EADDFF;
    }

    /* Container Styling */
    .bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--m3-nav-bg);
        box-shadow: 0 -1px 10px rgba(0, 0, 0, 0.05);
        padding-bottom: env(safe-area-inset-bottom);
        z-index: 9999;
        border-top: 1px solid #F3EDF7;
    }

    .bottom-nav {
        display: flex;
        justify-content: space-around;
        align-items: center;
        height: 75px;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Nav Item Base */
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: var(--m3-secondary);
        transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        flex: 1;
        padding-top: 8px;
    }

    /* Icon Tonal Pill Effect (Material 3 Style) */
    .icon-wrapper {
        width: 64px;
        height: 32px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s ease;
        margin-bottom: 4px;
    }

    .nav-item i {
        font-size: 22px;
        transition: transform 0.2s ease;
    }

    .nav-item span {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.3px;
    }

    /* Active State (Highlight) */
    .nav-item.active {
        color: var(--m3-primary);
    }

    .nav-item.active .icon-wrapper {
        background-color: var(--m3-tonal-pill);
        /* Active Pill Background */
    }

    .nav-item.active i {
        color: #21005D;
        transform: scale(1.1);
    }

    .nav-item.active span {
        font-weight: 900;
    }

    /* Body Gap to prevent content overlap */
    body {
        padding-bottom: 90px !important;
    }
</style>


<style>
    /* Material 3 Modal Enhancements */
    .modal-m3-redesign {
        border-radius: 16px !important;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .modal-m3-redesign .modal-header {
        background: #fdf7ff;
        /* Very subtle tonal background */
        border-bottom: 1px solid #e7e0ec;
        padding: 24px;
    }

    .modal-m3-redesign .modal-title {
        font-weight: 800;
        color: #1c1b1f;
        letter-spacing: -0.5px;
    }

    .modal-m3-redesign .modal-body {
        padding: 24px;
    }

    /* Modern Input Styling */
    .m3-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6750A4;
        margin-bottom: 8px;
        margin-left: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .m3-input-field {
        border: 1.5px solid #79747E;
        border-radius: 12px;
        padding: 12px 16px;
        font-weight: 500;
        transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #fff;
    }

    .m3-input-field:focus {
        border-color: #6750A4;
        border-width: 2px;
        box-shadow: 0 0 0 4px rgba(103, 80, 164, 0.1);
        outline: none;
    }

    /* Action Button */
    .m3-btn-save {
        background-color: #6750A4;
        color: #fff;
        border-radius: 100px;
        /* Pill shape */
        padding: 12px 32px;
        font-weight: 700;
        border: none;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.2);
        transition: 0.3s;
    }

    .m3-btn-save:hover {
        background-color: #4f378b;
        box-shadow: 0 8px 16px rgba(103, 80, 164, 0.3);
        transform: translateY(-1px);
    }

    .feature-selector-content {
        display: flex;
        flex-direction: column;
    }

    /* Make modal content flex column */
    .modal-content {
        display: flex;
        flex-direction: column;
        max-height: 100vh;
        /* safeguard for very tall modals */
    }

    /* modal-body scrollable */
    .modal-body {
        overflow-y: auto;
    }
</style>


<style>
    /* মডাল কন্টেইনার */
    .modal-m3-redesign {
        border-radius: 16px !important;
        /* M3 Standard */
        border: none;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        background-color: #FEF7FF;
        /* M3 Surface color */
        display: flex;
        flex-direction: column;
        max-height: 100vh;
        /* safeguard */
    }

    /* হেডার সেকশন */
    .modal-m3-redesign .modal-header {
        border-bottom: none;
        padding: 24px 32px 8px;
    }

    .modal-m3-redesign .modal-title {
        font-weight: 800;
        color: #1D1B20;
        letter-spacing: -0.5px;
    }

    .modal-m3-redesign .modal-body {
        overflow-y: auto;
    }

    .icon-box-m3 {
        width: 48px;
        height: 48px;
        background: #EADDFF;
        /* Tonal Primary */
        color: #21005D;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ইনপুট লেবেল ও ফিল্ড */
    .m3-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #6750A4;
        margin-bottom: 8px;
        margin-left: 4px;
        display: block;
    }

    .m3-input-field {
        border: 1.5px solid #79747E;
        /* Outlined style */
        border-radius: 12px;
        padding: 14px 16px;
        font-weight: 500;
        color: #1D1B20;
        background-color: transparent;
        transition: all 0.2s;
    }

    .m3-input-field:focus {
        border-color: #6750A4;
        border-width: 2px;
        background-color: #fff;
        box-shadow: none;
        outline: none;
    }

    /* ফিচারের নাম (Read-only বা Locked লুক) */
    .m3-feature-name-display {
        background: #F3EDF7;
        border: 1px dashed #6750A4;
        color: #6750A4;
        font-weight: 800;
        text-align: left;
        padding-left: 50px;
        letter-spacing: 1px;
    }

    /* ফুটার ও বাটন */
    .modal-m3-redesign .modal-footer {
        border-top: none;
        padding: 16px 32px 32px;
    }

    .m3-btn-save {
        background-color: #6750A4;
        color: white;
        border-radius: 100px;
        /* Pill shape */
        padding: 12px 28px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.2);
    }

    .m3-btn-save:hover {
        background-color: #4F378B;
        box-shadow: 0 8px 24px rgba(103, 80, 164, 0.3);
        transform: translateY(-1px);
    }

    .btn-cancel-m3 {
        color: #6750A4;
        font-weight: 700;
        text-decoration: none;
    }

    .m3-floating-group {
        margin-bottom: 0;
    }

    .m3-field-icon2 {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--m3-primary);
        font-size: 1.5rem;
        z-index: 10;
        pointer-events: none;
    }
</style>

<style>
    .m3-search-modal {
        background-color: #FEF7FF;
        /* M3 Surface */
        border-radius: 28px !important;
    }

    .m3-search-input-wrapper {
        background-color: #F3EDF7;
        /* M3 Secondary Container */
        border-radius: 100px;
        border: 1px solid #E7E0EC;
    }

    .m3-search-input-wrapper input:focus {
        box-shadow: none;
        outline: none;
    }

    /* Result Card Style */
    .m3-result-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 16px;
        border-radius: 16px;
        cursor: pointer;
        text-decoration: none;
        color: #1C1B1F;
        transition: 0.2s;
        margin-bottom: 4px;
        background: #fff;
        border: 1px solid transparent;
    }

    .m3-result-item:hover {
        background-color: #EADDFF;
        /* Tonal Purple Hover */
        border-color: #6750A4;
    }

    .result-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #F3EDF7;
        color: #6750A4;
        font-size: 1.2rem;
    }

    .result-type-badge {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #79747E;
        background: #E7E0EC;
        padding: 2px 8px;
        border-radius: 4px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

    function goMy() { }

</script>