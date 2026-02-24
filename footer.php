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



function showPageDocs($conn, $curfile)
{
    // ডাটাবেস থেকে তথ্য আনা
    $stmt = $conn->prepare("SELECT * FROM page_docs WHERE pagename = ? LIMIT 1");
    $stmt->bind_param("s", $curfile);
    $stmt->execute();
    $doc = $stmt->get_result()->fetch_assoc();

    $title = $doc['title'] ?? 'No Title Set';
    $desc = $doc['description'] ?? '<p class="text-muted">এই পেজের জন্য কোনো বর্ণনা এখনো লেখা হয়নি।</p>';
    $tips = $doc['tips'] ?? '';
    $notes = $doc['notes'] ?? '';

    // হেল্প বাটন (Floating Button) এবং মডাল HTML
    echo '

    <div class="modal fade" id="pageDocModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius:16px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-black text-primary m-0"><i class="bi bi-info-circle me-2"></i> Page Guide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <h4 class="fw-black mb-3">' . $title . '</h4>
                    <div class="doc-body mb-4" style="font-size: 0.95rem; line-height: 1.6;">' . $desc . '</div>
                    
                    ' . (!empty($tips) ? '<div class="alert alert-success border-0 rounded-4 mb-3"><h6 class="fw-bold"><i class="bi bi-lightbulb"></i> Tips</h6><small>' . nl2br($tips) . '</small></div>' : '') . '
                    ' . (!empty($notes) ? '<div class="alert alert-warning border-0 rounded-4"><h6 class="fw-bold"><i class="bi bi-exclamation-triangle"></i> Note</h6><small>' . nl2br($notes) . '</small></div>' : '') . '
                </div>
                <div class="modal-footer border-0">
                    <a href="edit-docs.php?page=' . $curfile . '" class="btn btn-light rounded-pill px-4 fw-bold">
                        <i class="bi bi-pencil-square me-2"></i> Edit Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>';
}


function showUserStats($conn, $usr, $sccode) {
    // ১. লগবুক থেকে ভিজিট এবং সময় বের করা
    $stats_sql = "SELECT COUNT(id) as total_hits, SUM(duration) as total_time FROM logbook WHERE email = '$usr' AND sccode = '$sccode'";
    $stats = $conn->query($stats_sql)->fetch_assoc();
    
    // ২. ইউজার একশন থেকে মোট পয়েন্ট বের করা
    $pts_sql = "SELECT SUM(points) as total_pts FROM user_actions WHERE email = '$usr' AND sccode = '$sccode'";
    $pts_res = $conn->query($pts_sql)->fetch_assoc();
    $total_pts = (int)($pts_res['total_pts'] ?? 0);

    $total_hits = $stats['total_hits'] ?? 0;
    $total_time = $stats['total_time'] ?? 0; // সেকেন্ডে

    // ৩. র‍্যাংক এবং টাইটেল লজিক
    $title = "Newbie";
    $rank_color = "#9E9E9E"; // Grey
    if ($total_pts > 500) { $title = "Regular Explorer"; $rank_color = "#4CAF50"; }
    if ($total_pts > 2000) { $title = "System Veteran"; $rank_color = "#2196F3"; }
    if ($total_pts > 5000) { $title = "Master Contributor"; $rank_color = "#FFD700"; }

    // ৪. সময় ফরম্যাট (Hours and Minutes)
    $hours = floor($total_time / 3600);
    $mins = floor(($total_time % 3600) / 60);

    echo '
    <div class="modal fade" id="userStatsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:32px; overflow:hidden;">
                <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #6750A4 0%, #311B92 100%);">
                    <div class="text-center w-100">
                        <div class="mb-2" style="font-size: 3rem;"><i class="bi bi-patch-check-fill"></i></div>
                        <h4 class="fw-black mb-1">Achievement Board</h4>
                        <span class="badge rounded-pill px-3" style="background:rgba(255,255,255,0.2);">'. $usr .'</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute; top:20px; right:20px;"></button>
                </div>

                <div class="modal-body p-4 bg-light">
                    <div class="text-center mb-4">
                        <h2 class="fw-black m-0" style="color:'. $rank_color .';">'. $title .'</h2>
                        <div class="small fw-bold text-muted text-uppercase">Current Title</div>
                        <div class="mt-3 display-6 fw-black text-dark">'. number_format($total_pts) .' <small class="fs-6 opacity-50">PTS</small></div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-4 border text-center">
                                <i class="bi bi-clock-history text-primary fs-3"></i>
                                <div class="fw-black fs-5 mt-1">'. $hours .'h '. $mins .'m</div>
                                <div class="small text-muted fw-bold">Active Time</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-4 border text-center">
                                <i class="bi bi-lightning-fill text-warning fs-3"></i>
                                <div class="fw-black fs-5 mt-1">'. $total_hits .'</div>
                                <div class="small text-muted fw-bold">Total Hits</div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-black mt-4 mb-3 text-muted">EARNED BADGES</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        '. ($total_hits > 100 ? '<span class="badge bg-info p-2 rounded-3"><i class="bi bi-award-fill me-1"></i> Fast Learner</span>' : '') .'
                        '. ($hours > 10 ? '<span class="badge bg-success p-2 rounded-3"><i class="bi bi-clock-fill me-1"></i> Dedicated</span>' : '') .'
                        '. ($total_pts > 1000 ? '<span class="badge bg-warning text-dark p-2 rounded-3"><i class="bi bi-star-fill me-1"></i> Star Performer</span>' : '') .'
                        <span class="badge bg-secondary p-2 rounded-3">Verified User</span>
                    </div>
                </div>
                
                <div class="modal-footer border-0 bg-light justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-bold" data-bs-dismiss="modal">Keep Exploring</button>
                </div>
            </div>
        </div>
    </div>';
}


showPageDocs($conn, $curfile);
showUserStats($conn, $usr, $sccode);
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



<div class="modal fade" id="featureSelectorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content feature-selector-content">

            <div class="modal-header">
                <h5 class="modal-title">Select Feature</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0 overflow-auto" id="featureSelectorBody">
                <ul class="list-group list-group-flush" id="featureListContainer"></ul>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>


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

<div class="modal fade" id="devFeatureModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-m3-redesign">

            <form id="devFeatureForm">

                <!-- Modal Header -->
                <div class="modal-header d-flex align-items-center">
                    <div class="icon-box-m3 me-3">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <h5 class="modal-title m-0">Feature Timeline</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body px-4 py-2">




                    <!-- Target Feature -->
                    <div class="m3-floating-group mb-4">
                        <i class="bi bi-card-text m3-field-icon"></i>
                        <input type="text" name="feature_name" id="df_feature"
                            class="form-control m3-input-field m3-feature-name-display shadow-sm" disabled>
                        <i class="bi bi-android2 m3-field-icon2"></i>
                    </div>

                    <div class="row g-4">

                        <!-- Platform -->
                        <div class="col-md-4 col-12" hidden>
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Platform</label>
                                <i class="bi bi-phone m3-field-icon"></i>
                                <select name="platform" id="df_platform" class="form-select m3-select-floating">
                                    <option>Android</option>
                                    <option>Web</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Type -->
                        <div class=" col-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Action Type</label>
                                <i class="bi bi-lightning-charge m3-field-icon"></i>
                                <select name="action_type" id="df_action" class="form-select m3-select-floating">
                                    <?php foreach ($feature_types as $k => $v)
                                        echo "<option value='$k'>$k</option>"; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div class="col-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Current Status</label>
                                <i class="bi bi-info-circle m3-field-icon"></i>
                                <select name="status" id="df_status" class="form-select m3-select-floating">
                                    <?php foreach ($feature_status as $k => $v)
                                        echo "<option value='$k'>$k</option>"; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Description / Update Notes -->
                        <div class="col-12">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Progress Description / Update Notes</label>
                                <i class="bi bi-pencil-square m3-field-icon"></i>
                                <textarea name="description" id="df_desc" class="form-control m3-input-floating"
                                    style="height:100px; padding-top:20px;" rows="4"
                                    placeholder="What's new in this update?"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-link btn-cancel-m3 text-decoration-none"
                        data-bs-dismiss="modal">Dismiss</button>
                    <button type="submit" class="m3-btn-save">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Update Timeline
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
    function goNotify() { location.href = "notification.php"; }
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
    function openFeatureSelector() {

        const listEl = document.getElementById('featureListContainer');
        if (!listEl) return;

        listEl.innerHTML = '';

        // Collect unique features + reference to parent card
        const featureMap = {};
        document.querySelectorAll('[data-feature]').forEach(el => {
            const name = el.getAttribute('data-feature');
            if (!featureMap[name]) {
                featureMap[name] = el;
            }
        });

        // Render list
        Object.keys(featureMap).forEach(featureName => {

            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.textContent = featureName;

            li.addEventListener('click', function () {

                // Close selector modal
                const selModalEl = document.getElementById('featureSelectorModal');
                const selModal = bootstrap.Modal.getInstance(selModalEl);
                if (selModal) selModal.hide();

                // dev mode check
                const developerMode = <?= (int) ($is_developer_mode ?? 0) ?>;

                const card = featureMap[featureName];
                const info = (typeof devTimeline !== 'undefined') ? devTimeline[featureName] : null;

                if (developerMode === 1) {
                    // Full devFeatureModal behavior
                    const badge = card.querySelector('.m3-badge-new');
                    if (badge) {
                        badge.click(); // reuse existing logic
                    } else {
                        card.click();
                    }
                } else {
                    // dev mode off → open modal manually but hide/disable platform input
                    const modalEl = document.getElementById('devFeatureModal');
                    if (!modalEl) return;

                    // Fill modal fields
                    document.getElementById('df_feature').value = featureName;
                    document.getElementById('df_action').value = info?.action_type || '';
                    document.getElementById('df_status').value = info?.status || '';
                    document.getElementById('df_desc').value = info?.description || '';

                    const platformEl = document.getElementById('df_platform');
                    if (platformEl) {
                        platformEl.value = info?.platform || 'Android';
                        platformEl.disabled = true; // hide / make readonly
                    }

                    // Show modal
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            });

            listEl.appendChild(li);
        });

        // Show selector modal
        const selectorModal = new bootstrap.Modal(
            document.getElementById('featureSelectorModal')
        );
        selectorModal.show();
    }
</script>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        // ✅ Developer mode check
        const developerMode = <?= (int) ($is_developer_mode ?? 0) ?>;
        if (developerMode !== 1) return;

        // ✅ Modal init
        const modalEl = document.getElementById('devFeatureModal');
        if (!modalEl) return;
        const modalDev = new bootstrap.Modal(modalEl);

        // ---------- Helper: form fill ----------
        function fillDevForm(featureName, info) {

            const form = document.getElementById('devFeatureForm');
            if (!form) return;

            document.getElementById('df_feature').value = featureName;

            if (info) {
                document.getElementById('df_action').value = info.action_type;
                document.getElementById('df_status').value = info.status;
                document.getElementById('df_desc').value = info.description || '';
                document.getElementById('df_platform').value = info.platform || 'Android';
            } else {
                form.reset();
                document.getElementById('df_feature').value = featureName;
            }
        }

        function openModal(featureName, info) {
            fillDevForm(featureName, info);
            modalDev.show();
        }

        // ✅ Process features
        document.querySelectorAll('[data-feature]').forEach(function (el) {

            el.classList.add('card-wrapper');
            el.style.position = 'relative';

            const featureName = el.getAttribute('data-feature');
            const info = (typeof devTimeline !== 'undefined') ? devTimeline[featureName] : null;

            let icon = 'bi-question-circle';
            let color = 'dark';

            if (info) {
                if (typeof featureTypes !== 'undefined' && featureTypes[info.action_type]) {
                    icon = featureTypes[info.action_type].icon;
                }
                if (typeof featureStatus !== 'undefined') {
                    color = featureStatus[info.status] || color;
                }
            } else {
                icon = 'bi-exclamation-circle';
                color = 'danger';
            }

            // ---------- Badge create ----------
            const badge = document.createElement('div');
            badge.className = 'm3-badge-new';
            badge.innerHTML = `
            <span class="badge bg-${color} shadow-sm">
                <i class="bi ${icon}"></i>
            </span>
        `;
            el.prepend(badge);

            // ⭐ Badge click (parent trigger হবে না)
            badge.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openModal(featureName, info);
            });

            // ---------- Card click ----------
            el.addEventListener('click', function (e) {

                // button/link/input click ignore
                if (e.target.closest('a,button,input,select,textarea')) return;

                e.preventDefault();
                openModal(featureName, info);
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



<script>
    (function () {

        function adjustModalBodyHeight(modalEl) {
            if (!modalEl) return;

            const modalContent = modalEl.querySelector('.modal-content');
            const modalBody = modalEl.querySelector('.modal-body');
            if (!modalContent || !modalBody) return;

            const header = modalEl.querySelector('.modal-header');
            const footer = modalEl.querySelector('.modal-footer');

            const headerH = header ? header.offsetHeight : 0;
            const footerH = footer ? footer.offsetHeight : 0;

            // Page fixed bars
            const pageHeader = document.getElementById('avatarMenu');
            const pageFooter = document.getElementById('bottom-nav-bar');

            const topOffset = pageHeader ? pageHeader.offsetHeight : 0;
            const bottomOffset = pageFooter ? pageFooter.offsetHeight : 0;

            const viewportH = window.innerHeight;

            // available height for modal body
            const available = viewportH - topOffset - bottomOffset - headerH - footerH - 20;

            modalBody.style.maxHeight = available + 'px';
            modalBody.style.overflowY = 'auto';
            modalContent.style.maxHeight = (viewportH - topOffset - bottomOffset - 70) + 'px';
            modalContent.style.top = topOffset - 10 + 'px';

        }

        // Whenever any modal opens
        document.addEventListener('shown.bs.modal', function (e) {
            adjustModalBodyHeight(e.target);
        });

        // On window resize, adjust open modals
        window.addEventListener('resize', function () {
            document.querySelectorAll('.modal.show').forEach(function (modal) {
                adjustModalBodyHeight(modal);
            });
        });

    })();
</script>