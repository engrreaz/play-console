<?php
/**
 * Footer Navigation Bar - M3-EIM Style
 * Features: Dynamic Active Highlighting via $curfile
 */

// ১. ইউজার লগিন না থাকলে রিডাইরেক্ট
if (empty($usr) || $userlevel == 'Guest') {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

include_once('logbook.php');

// বর্তমানে কোন ফাইলে আছে তা চেক করার ফাংশন (Inline Active Check)
function isActive($targetFile, $currentFile)
{
    return ($targetFile === $currentFile) ? 'active' : '';
}
?>

<div class="bottom-nav-container noprint">
    <div class="bottom-nav">

        <?php if (in_array($userlevel, ['Head Teacher', 'Asstt. Head Teacher', 'Administrator', 'Super Administrator'])): ?>
            <a href="index.php" class="nav-item <?= isActive('index.php', $curfile) ?>" data-action="Navigation">
                <div class="icon-wrapper"><i class="bi bi-house-fill"></i></div>
                <span>Home</span>
            </a>
            <a href="reporthome.php" class="nav-item <?= isActive('reporthome.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-mortarboard-fill"></i></div>
                <span>Reports</span>
            </a>
            <a href="tools.php" class="nav-item <?= isActive('tools.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-grid-fill"></i></div>
                <span>Tools</span>
            </a>
            <a href="settings_admin.php" class="nav-item <?= isActive('settings_admin.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-gear-fill"></i></div>
                <span>Settings</span>
            </a>
            <a href="build.php" class="nav-item <?= isActive('build.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-person-circle"></i></div>
                <span>Profile</span>
            </a>

        <?php elseif (in_array($userlevel, ['Teacher', 'Asstt. Teacher', 'Class Teacher'])): ?>
            <a href="index.php" class="nav-item <?= isActive('index.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-house-fill"></i></div>
                <span>Home</span>
            </a>
            <a href="reporthome.php" class="nav-item <?= isActive('reporthome.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-mortarboard-fill"></i></div>
                <span>Academic</span>
            </a>
            <a href="tools.php" class="nav-item <?= isActive('tools.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-plus-circle-fill"></i></div>
                <span>Tools</span>
            </a>
            <a href="build.php" class="nav-item <?= isActive('build.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-person-circle"></i></div>
                <span>Profile</span>
            </a>

        <?php elseif ($userlevel == "Student" || $userlevel == "Guardian"): ?>
            <a href="index.php" class="nav-item <?= isActive('index.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-house-fill"></i></div>
                <span>Home</span>
            </a>
            <a href="my-profile.php" class="nav-item <?= isActive('my-profile.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-person-fill"></i></div>
                <span>Profile</span>
            </a>
            <a href="globalsetting.php" class="nav-item <?= isActive('globalsetting.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-sliders"></i></div>
                <span>Settings</span>
            </a>
        <?php endif; ?>

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




<!-- ----------------------------------------------------------- -->
<!-- SESSION MODAL -->

<div id="sessionModal" style="display:none; position:fixed; inset:0;
    background:rgba(0,0,0,.5); justify-content:center; align-items:center; z-index:9999;">

    <div style="background:#fff; width:75%; max-width: 80%; margin:auto; max-height:80%; 
    border-radius:8px; padding:15px; overflow:auto; position:relative;">

        <h4><i class="bi bi-calendar-check me-1"></i> Select Session</h4>

        <span onclick="closeSessionModal()" style="position:absolute; right:15px; top:10px;
            cursor:pointer;font-size:20px;font-weight:bold;">×</span>

        <hr>

        <div id="sessionList"></div>
    </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>






<script>
    function setCookie(name, value, days = 30) {
        let d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + "=" + value + "; expires=" + d.toUTCString() + "; path=/";
    }

</script>



<script>

    // open modal when pill clicked
    document.querySelectorAll('.session-pill').forEach(el => {
        el.onclick = function () {
            openSessionModal();
        }
    });

    function openSessionModal() {
        document.getElementById('sessionModal').style.display = 'flex';
        loadSessions();
    }

    function closeSessionModal() {
        document.getElementById('sessionModal').style.display = 'none';
    }

    // load active sessions
    function loadSessions() {
        fetch('ajax/get_active_sessions.php')
            .then(r => r.json())
            .then(data => {
                let html = '';

                const currentSessionYear = <?= json_encode($sessionyear); ?>;

                data.forEach(s => {

                    if (s.sessionyear == currentSessionYear) {

                        html += `<div class="session-item fw-bold text-primary" onclick="selectSession('${s.sessionyear}')">
            ${s.sessionyear}
        </div>`;

                    } else {

                        html += `<div class="session-item" onclick="selectSession('${s.sessionyear}')">
            ${s.sessionyear}
        </div>`;
                    }

                });



                document.getElementById('sessionList').innerHTML = html;
            });
    }

    // select + cookie + reload
    // function selectSession(val) {

    //     document.cookie = "query-session=" + val + "; path=/";

    //     location.reload();
    // }


    function selectSession(val) {
        var d = new Date();
        d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000)); // 7 days

        document.cookie = "query-session=" + val +
            "; expires=" + d.toUTCString() +
            "; path=/";

        location.reload();
    }


</script>


<script>
    function toggleAvatarMenu() {
        const m = document.getElementById("avatarMenu");
        m.style.display = (m.style.display === "block") ? "none" : "block";
    }

    document.addEventListener("click", e => {
        if (!e.target.closest(".top-avatar")) {
            document.getElementById("avatarMenu").style.display = "none";
        }
    });

    function goProfile() { location.href = "institute_profile.php"; }
    function goMy() {  }
    function goTicket() { location.href = "support_ticket.php"; }
    function goNotify() { location.href = "notifications.php"; }
    function task_manager() { location.href = "task-manager.php"; }

    function doLogout() {
        if (confirm("Logout now?")) {
            location.href = "logout.php";
        }
    }

    function toggleTheme() {
        document.body.classList.toggle("dark-mode");
        localStorage.setItem("theme",
            document.body.classList.contains("dark-mode") ? "dark" : "light");
    }

    // auto apply theme
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
    }
</script>


<script>
    // TOAST ------------------------------------------------------------------------
    function showToast(type, message, title = '', pos = 'bottom') {

        const allowedPos = ['top', 'bottom', 'center'];
        if (!allowedPos.includes(pos)) pos = 'bottom';

        let container = document.querySelector('.toast-container.' + pos);
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container ' + pos;
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = 'toast toast-' + type;

        toast.innerHTML = `
        ${title ? `<div class="toast-title">${title}</div>` : ''}
        <div>${message}</div>
    `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.remove();
            if (!container.children.length) container.remove();
        }, 3000);
    }
</script>




<!-- ----------------------------- TREE - UI ------------------------- -->
<?php include_once 'assets/js/tree-ui-js.php'; ?>
<?php include_once 'js.php'; ?>

<script>
    function addParams(params) {
        const url = new URL(window.location);
        Object.keys(params).forEach(key => {
            url.searchParams.set(key, params[key]);
        });
        history.pushState({}, '', url);
    }

    // usage
    // addParams({
    //     id: 10,
    //     class: 'Eight',
    //     session: 2026
    // });

    function removeParams(params) {
        const url = new URL(window.location.href);
        params.forEach(p => url.searchParams.delete(p));
        history.pushState({}, '', url);
    }

    // usage
    // removeParams(['id', 'session']);
</script>



<!-- ------------------------- last Function ------------------------------ -->
<script>
    window.addEventListener('load', function () {
        // পুরো পেজ load হলে backdrop remove
        let bd = document.getElementById('pageBackdrop');
        if (bd) bd.remove();
    });
</script>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        // ===========================
        // Track User Interaction
        // ===========================
        // console.log('Loaded');
        document.addEventListener("click", e => {
            // console.log('Trigger');
            const target = e.target.closest("button, a, input, [data-action]");
            if (!target || target.dataset.notrack) return;
            const action = target.dataset.feature || target.dataset.action || target.innerText.trim() || target.value;
            const point = target.dataset.point || 0;
            const url = window.location.pathname;
            const sccode = '<?php echo $sccode; ?>';
            if (sccode == '') sccode = 0;
            const page = '<?php echo $curfile; ?>';
            fetch("core/track_action.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    email: "<?php echo $usr ?? ''; ?>",
                    page: page, url: url, sccode: sccode, action: action, point: point, timestamp: new Date().toISOString()
                })
            })
                .then(res => res.text())
                .then(data => console.log("Track Response:", data))
                .catch(err => console.error(err));
        });
    });
</script>