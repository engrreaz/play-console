<?php
$curfile = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$curfile = basename($_SERVER["SCRIPT_FILENAME"]);

function isActive($targetFile, $currentFile)
{
    return ($targetFile === $currentFile) ? 'active' : '';
}
?>

<style>
    a {
        text-align: center;;
    }
</style>

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
        <a href="account-deletion-policy.php" class="nav-item <?= isActive('account-deletion-policy.php', $curfile) ?>">
            <div class="icon-wrapper"><i class="bi bi-person-x-fill"></i></div>
            <span>Account Deletion Policy</span>
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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

    function goMy() { }

</script>