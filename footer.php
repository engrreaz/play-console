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

<div id="bottom-nav-bar" class="bottom-nav-container noprint">
    <div class="bottom-nav">

        <?php if (in_array($userlevel, ['Head Teacher', 'Asstt. Head Teacher', 'Administrator', 'Super Administrator'])): ?>
            <a href="index.php" class="nav-item <?= isActive('index.php', $curfile) ?>" data-action="Navigation">
                <div class="icon-wrapper"><i class="bi bi-house-fill"></i></div>
                <span>Home</span>
            </a>
            <a href="reporthome.php" class="nav-item <?= isActive('reporthome.php', $curfile) ?>">
                <div class="icon-wrapper"><i class="bi bi-mortarboard-fill"></i></div>
                <span>Info Hub</span>
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






<style>
    /* Material 3 Modal Enhancements */
    .modal-m3-redesign {
        border-radius: 28px !important;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .modal-m3-redesign .modal-header {
        background: #fdf7ff;
        /* Very subtle tonal background */
        border-bottom: 1px solid #e7e0ec;
        padding: 24px 32px;
    }

    .modal-m3-redesign .modal-title {
        font-weight: 800;
        color: #1c1b1f;
        letter-spacing: -0.5px;
    }

    .modal-m3-redesign .modal-body {
        padding: 32px;
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
</style>

<div class="modal fade" id="devFeatureModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-m3-redesign">

            <form id="devFeatureForm">

                <div class="modal-header d-flex align-items-center">
                    <div class="icon-box me-3 text-primary">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <h5 class="modal-title m-0">Feature Timeline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="text" name="feature_name" id="df_feature">

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="m3-label">Platform</label>
                            <select name="platform" id="df_platform" class="form-select m3-input-field">
                                <option>Android</option>
                                <option>Web</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="m3-label">Action</label>
                            <select name="action_type" id="df_action" class="form-select m3-input-field">
                                <?php
                                foreach ($feature_types as $k => $v)
                                    echo "<option value='$k'>$k</option>";
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="m3-label">Status</label>
                            <select name="status" id="df_status" class="form-select m3-input-field">
                                <?php
                                foreach ($feature_status as $k => $v)
                                    echo "<option value='$k'>$k</option>";
                                ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="m3-label">Description / Update Notes</label>
                            <textarea name="description" id="df_desc" class="form-control m3-input-field" rows="4"
                                placeholder="Describe the update or feature progress..."></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none me-auto"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="m3-btn-save">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Save Timeline
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>







<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
    const featureTypes = {
        implement: { icon: 'bi-plus-circle' },
        update: { icon: 'bi-arrow-repeat' },
        bug_fix: { icon: 'bi-bug' },
        remove: { icon: 'bi-trash' },
        change: { icon: 'bi-sliders' },
        refactor: { icon: 'bi-tools' },
        optimize: { icon: 'bi-speedometer2' },
        security_patch: { icon: 'bi-shield-lock' },
        deprecate: { icon: 'bi-exclamation-triangle' },
        migrate: { icon: 'bi-arrow-left-right' },
        test_case: { icon: 'bi-check2-square' },
        rollback: { icon: 'bi-arrow-counterclockwise' },
        hotfix: { icon: 'bi-fire' }
    };

    const featureStatus = {
        draft: 'secondary',
        planning: 'info',
        in_progress: 'primary',
        testing: 'warning',
        alpha: 'dark',
        beta: 'primary',
        rc: 'info',
        staging: 'warning',
        stable: 'success',
        lts: 'success',
        deprecated: 'danger',
        archived: 'secondary'
    };

    const devTimeline = <?= $devFeaturesJSON ?>;
</script>




<script>
    function setCookie(name, value, days = 30) {
        let d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + "=" + value + "; expires=" + d.toUTCString() + "; path=/";
    }

</script>


<script>
    function toggle_developer_mode() {
        let mode = 1 - <?= $is_developer_mode ? '1' : '0' ?>;
        setCookie('developer_mode', mode);
        location.reload();
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
    function goMy() { }
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

<script>
    function openVideo(videoId) {

        var modal = document.getElementById("videoModal");
        var iframe = document.getElementById("ytPlayer");

        iframe.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";

        modal.style.display = "block";
    }

    function closeModal() {

        var modal = document.getElementById("videoModal");
        var iframe = document.getElementById("ytPlayer");

        iframe.src = ""; // stop video
        modal.style.display = "none";
    }

    // click outside modal to close
    window.onclick = function (event) {
        var modal = document.getElementById("videoModal");
        if (event.target === modal) {
            closeModal();
        }
    }


</script>



<script>
    document.querySelectorAll('.dd-item.perm').forEach(function (icon) {
        icon.addEventListener('click', function () {
            let permValue = this.dataset.perm;
            let url = new URL(window.location.href);

            // perm প্যারামিটার সেট বা update
            url.searchParams.set('perm', permValue);

            // redirect to updated URL
            window.location.href = url.toString();
        });
    });
</script>


<script>
    <?php if ($readonly): ?>

        document.addEventListener("DOMContentLoaded", () => {

            const mainBlock = document.querySelector("main");
            if (!mainBlock) return;

            // Stop clicks inside main only
            mainBlock.addEventListener('click', e => {
                e.stopPropagation();
                e.preventDefault();
            }, true);

            // Stop form submit inside main
            mainBlock.querySelectorAll('form').forEach(f => {
                f.addEventListener('submit', e => {
                    e.preventDefault();
                });
            });

            // Disable controls inside main
            mainBlock.querySelectorAll('input,select,textarea,button').forEach(el => {
                el.disabled = true;
            });

            // Remove bootstrap triggers inside main
            mainBlock.querySelectorAll('[data-bs-toggle]').forEach(el => {
                el.removeAttribute('data-bs-toggle');
            });

        });

    <?php endif; ?>


</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ১. ডেভেলপার মোড চেক
        let developerMode = <?= (int) ($is_developer_mode ?? 0) ?>;
        if (developerMode !== 1) return;

        // ২. মডাল অবজেক্ট তৈরি
        const modalDevEl = document.getElementById('devFeatureModal');
        if (!modalDevEl) return;
        const modalDev = new bootstrap.Modal(modalDevEl);

        // ৩. সকল ফিচার এলিমেন্ট প্রসেস করা
        document.querySelectorAll('[data-feature]').forEach(function (el) {
            // ক্লাস যোগ করা
            el.classList.add('card-wrapper');
            el.style.position = 'relative'; // ব্যাজ পজিশনিং এর জন্য

            const featureName = el.getAttribute('data-feature');
            const info = (typeof devTimeline !== 'undefined') ? devTimeline[featureName] : null;

            let icon = 'bi-question-circle';
            let color = 'dark';

            if (info) {
                if (typeof featureTypes !== 'undefined' && featureTypes[info.action_type]) {
                    icon = featureTypes[info.action_type].icon;
                }
                color = (typeof featureStatus !== 'undefined') ? (featureStatus[info.status] || color) : color;
            } else {
                icon = 'bi-exclamation-circle';
                color = 'danger';
            }

            // ৪. ব্যাজ তৈরি ও যোগ করা
            let badge = document.createElement('div');
            badge.className = 'm3-badge-new';
            badge.innerHTML = `
            <span class="badge bg-${color} shadow-sm">
                <i class="bi ${icon}"></i>
            </span>
        `;
            el.prepend(badge);

            // ৫. কার্ডের ক্লিক ইভেন্ট (লুপের ভেতরেই লিসেনার দেওয়া ভালো)
            el.addEventListener('click', function (e) {

                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                // যদি ব্যাজে ক্লিক করা হয়, তবে শুধু মডাল খুলবে কিন্তু অন্য কিছু হবে না
                // closest ব্যবহার করা হয়েছে যাতে ব্যাজের ভেতরের আইকনে ক্লিক করলেও ধরা যায়
                if (e.target.closest('.m3-badge-new')) {
                    // আপনি চাইলে ব্যাজে ক্লিক করলে আলাদা কিছু করতে পারেন
                }

                // মডাল ফর্ম ফিল্ডে ডাটা সেট করা
                document.getElementById('df_feature').value = featureName;

                // ফর্ম রিসেট (আগের ডাটা মুছে ফেলার জন্য)
                const devForm = document.getElementById('devFeatureForm');
                if (devForm) {
                    // ডাটা থাকলে সেট করুন, নাহলে ডিফল্ট
                    if (info) {
                        document.getElementById('df_action').value = info.action_type;
                        document.getElementById('df_status').value = info.status;
                        document.getElementById('df_desc').value = info.description || '';
                        document.getElementById('df_platform').value = info.platform || 'Android';
                    } else {
                        devForm.reset();
                        document.getElementById('df_feature').value = featureName;
                    }
                }

                modalDev.show();
            });
        });
    });
</script>

<script>
    document.getElementById('devFeatureForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // ১. সেভ করার সময় লোডিং দেখানো
        Swal.fire({
            title: 'সংরক্ষণ করা হচ্ছে...',
            text: 'দয়া করে কিছুক্ষণ অপেক্ষা করুন',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        let fd = new FormData(this);
        fd.append('save_timeline', 1);

        fetch('', {
            method: 'POST',
            body: fd
        })
            .then(r => r.text())
            .then(t => {
                if (t.includes("1")) {
                    // ২. সফল হলে সাকসেস মেসেজ দেখানো
                    Swal.fire({
                        icon: 'success',
                        title: 'সফলভাবে সংরক্ষিত হয়েছে!',
                        showConfirmButton: false,
                        timer: 1500 // ১.৫ সেকেন্ড পর অটো বন্ধ হবে
                    }).then(() => {
                        if (typeof modalDev !== 'undefined') modalDev.hide();
                        location.reload();
                    });
                } else {
                    // ৩. ব্যর্থ হলে এরর মেসেজ
                    Swal.fire({
                        icon: 'error',
                        title: 'ব্যর্থ হয়েছে',
                        text: 'উফ! তথ্য সেভ করা সম্ভব হয়নি। আবার চেষ্টা করুন।',
                        confirmButtonColor: '#6750A4'
                    });
                }
            })
            .catch(err => {
                // ৪. নেটওয়ার্ক এরর হলে
                Swal.fire({
                    icon: 'error',
                    title: 'সংযোগ বিচ্ছিন্ন',
                    text: 'সার্ভারের সাথে যোগাযোগ করা যাচ্ছে না।',
                });
            });
    });
</script>