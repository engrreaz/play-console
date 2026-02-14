<?php
$page_title = "Advanced Permission Mapper";
include 'inc.php';

/* ========= LOAD ROLES ========= */
$roles = [];
$r = $conn->query("SELECT userlevel FROM rolemanager WHERE sccode='0' ORDER BY id");
while ($row = $r->fetch_assoc())
    $roles[] = $row['userlevel'];

/* ========= LOAD MODULES ========= */
$modules = [];
$m = $conn->query("SELECT module_name FROM modulelist ORDER BY slno");
while ($row = $m->fetch_assoc())
    $modules[] = $row['module_name'];


/* ========= SAVE ========= */
if (isset($_POST['save_all_permissions'])) {

    $page = mysqli_real_escape_string($conn, $_POST['page_name']);
    $module = mysqli_real_escape_string($conn, $_POST['module']);
    $root = mysqli_real_escape_string($conn, $_POST['root_page']);
    $title = mysqli_real_escape_string($conn, $_POST['page_title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    foreach ($_POST['perm'] as $role => $perm) {

        $role = mysqli_real_escape_string($conn, $role);
        $perm = intval($perm);

        $chk = $conn->query("SELECT id FROM permission_map_app
WHERE page_name='$page' AND userlevel='$role' AND sccode='0'");

        if ($chk->num_rows) {

            $conn->query("UPDATE permission_map_app SET
module='$module',
root_page='$root',
page_title='$title',
description='$desc',
permission='$perm',
updatedby='$usr',
modifieedate='$cur'
WHERE page_name='$page' AND userlevel='$role' AND sccode='0'");

        } else {

            $conn->query("INSERT INTO permission_map_app
(page_name,module,root_page,page_title,description,
sccode,userlevel,permission,updatedby,modifieedate)
VALUES
('$page','$module','$root','$title','$desc',
'0','$role','$perm','$usr','$cur')");
        }
    }
}


/* ========= FILE SCAN ========= */
$files = glob("*.php");
$exclude = ['inc.php', 'footer.php', 'header.php', 'db.php', 'config.php', 'permission-mapper.php'];

/* ========= LOAD MAP ========= */
$mapped = [];
$q = $conn->query("SELECT * FROM permission_map_app WHERE sccode='0'");
while ($row = $q->fetch_assoc())
    $mapped[$row['page_name']][$row['userlevel']] = $row;

/* ========= COUNT ========= */
$unassigned = 0;
foreach ($files as $f)
    if (!in_array($f, $exclude) && !isset($mapped[$f]))
        $unassigned++;
?>


<style>
    /* --- M3 CARD & TONAL PILL REFACTOR --- */

    .perm-card {
        background: #FFFFFF;
        border-radius: 12px !important;
        /* M3 Medium Radius */
        padding: 16px 20px;
        margin-bottom: 12px;
        border: 1px solid #E0E0E0 !important;
        transition: all 0.2s cubic-bezier(0, 0, 0.2, 1);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .perm-card:hover {
        border-color: #6750A4 !important;
        background-color: #F7F2FA;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .perm-card:active {
        transform: scale(0.98);
    }

    /* আইকন বক্স */
    .icon-box-m3 {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .c-mapped {
        background-color: #EADDFF;
        color: #21005D;
    }

    /* M3 Primary Tonal */
    .c-unassigned {
        background-color: #F9DEDC;
        color: #410E0B;
    }

    /* M3 Error Tonal */

    /* --- টোনাল পিল (Mapped & Unassigned) --- */
    .m3-tonal-pill {
        padding: 4px 12px;
        border-radius: 8px;
        /* M3 Small Radius */
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .pill-mapped {
        background-color: #E8F5E9;
        /* Light Green Tonal */
        color: #2E7D32;
    }

    .pill-unassigned {
        background-color: #FDE7E9;
        /* Light Red Tonal */
        color: #B3261E;
    }

    /* টেক্সট স্টাইল */
    .file-name {
        font-size: 0.9rem;
        font-weight: 800;
        color: #1C1B1F;
        margin-bottom: 2px;
    }

    .file-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #49454F;
    }

    .module-label {
        font-size: 0.65rem;
        font-weight: 500;
        color: #79747E;
    }

    .pill {
        border: none;
        padding: 6px 18px;
        border-radius: 50px;
        font-weight: 700;
        font-size: .7rem;
        background: #EEE;
        cursor: pointer
    }

    .pill.active {
        background: #6750A4;
        color: #fff
    }


    /* --- M3 MODAL UI REFACTOR --- */

    /* ১. মডাল কন্টেইনার */
    .m3-modal-dialog {
        max-width: 92%;
        /* max-height:60%; */
        /* মোবাইল ফ্রেন্ডলি */
        margin: 1.75rem auto;
    }

    .m3-modal-content {
        background-color: #FFFFFF !important;
        border-radius: 12px !important;
        margin-top: -75px;
        /* M3 Extra Large Radius */
        border: none !important;
        overflow: hidden;
    }

    .m3-modal-body {
        padding: 24px;

        max-height: 55vh;
        overflow-y: auto;
    }

    /* ২. ইনপুট এবং সিলেক্ট বক্স */
    .m3-input-floating,
    .m3-select-floating {
        width: 100%;
        background-color: #F3EDF7;
        /* Tonal Surface */
        border: none;
        border-bottom: 2px solid #79747E;
        /* Outlined variant foundation */
        border-radius: 12px 12px 4px 4px;
        padding: 12px 16px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1C1B1F;
        margin-bottom: 12px;
        transition: all 0.2s;
    }

    .m3-input-floating:focus,
    .m3-select-floating:focus {
        background-color: #EADDFF;
        border-bottom-color: #6750A4;
        outline: none;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.1);
    }

    .m3-input-floating::placeholder {
        color: #79747E;
        font-weight: 500;
    }

    /* ৩. রোল পারমিশন রো (Role Rows) */
    .m3-section-title {
        font-size: 0.75rem;
        font-weight: 800;
        color: #6750A4;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 20px 0 10px;
        padding-left: 5px;
    }

    .role-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 16px;
        margin-bottom: 8px;
        transition: 0.2s;
    }

    .role-row:hover {
        background-color: #F7F2FA;
        border-color: #EADDFF;
    }

    .role-row select {
        background: #6750A4;
        color: white;
        border: none;
        border-radius: 100px;
        /* Pill shape */
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        outline: none;
    }

    /* ৪. মডাল ফুটার বাটন */
    .modal-footer {
        padding: 16px 24px 24px;
        gap: 12px;
    }

    .btn-m3-primary {
        background-color: #6750A4;
        color: white;
        border-radius: 100px;
        padding: 12px 24px;
        font-weight: 800;
        border: none;
        flex: 1;
        box-shadow: 0 2px 6px rgba(103, 80, 164, 0.3);
    }

    .btn-m3-light {
        background-color: #F3EDF7;
        color: #6750A4;
        border-radius: 100px;
        padding: 12px 24px;
        font-weight: 800;
        border: none;
        flex: 1;
    }

    /* স্ক্রলবার স্টাইল */
    .m3-modal-body::-webkit-scrollbar {
        width: 4px;
    }

    .m3-modal-body::-webkit-scrollbar-thumb {
        background: #EADDFF;
        border-radius: 10px;
    }


    .perm-card.unassigned {
        border-left: 4px solid #F9DEDC !important;
    }

    .perm-card.mapped {
        border-left: 4px solid #E8F5E9 !important;
    }
</style>


<main>

    <div class="hero-container pb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div style="font-size:1.4rem;font-weight:900;">Permission Mapper</div>
            <div class="text-end">
                <div style="font-size:1.5rem;font-weight:900;color:#FFD8D6;"><?php echo $unassigned; ?></div>
                <div style="font-size:.6rem;font-weight:800;">UNASSIGNED</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button class="pill active" onclick="filterCards('all',this)">All</button>
            <button class="pill" onclick="filterCards('mapped',this)">Mapped</button>
            <button class="pill" onclick="filterCards('unassigned',this)">Unassigned</button>
        </div>
    </div>


    <div class="widget-grid" style="padding:15px 0 100px;">

        <?php foreach ($files as $file):
            if (in_array($file, $exclude))
                continue;

            $data = $mapped[$file] ?? [];
            $un = empty($data);

            // পারমিশন ডাটা প্রসেসিং (আগের মতই)
            $perm_json = [];
            foreach ($roles as $r)
                $perm_json[$r] = $data[$r]['permission'] ?? 0;
            $module = reset($data)['module'] ?? '';
            $title = reset($data)['page_title'] ?? '';
            $desc = reset($data)['description'] ?? '';
            $root = reset($data)['root_page'] ?? '';
            ?>

            <div class="d-flex mx-3 perm-card <?php echo $un ? 'unassigned' : 'mapped'; ?>"
                data-file="<?php echo htmlspecialchars($file); ?>" data-title="<?php echo htmlspecialchars($title); ?>"
                data-module="<?php echo htmlspecialchars($module); ?>" data-root="<?php echo htmlspecialchars($root); ?>"
                data-desc="<?php echo htmlspecialchars($desc); ?>" data-perm='<?php echo json_encode($perm_json); ?>'
                onclick="openPermModalFromEl(this)">

                <div class="icon-box-m3 <?php echo $un ? 'c-unassigned' : 'c-mapped'; ?>"
                    onclick="openActualPage(event, '<?php echo $file; ?>')" style="cursor: alias;" title="Open Page">
                    <i class="bi <?php echo $un ? 'bi-file-earmark-x' : 'bi-file-earmark-check'; ?>"></i>
                </div>

                <div class="flex-grow-1 overflow-hidden">
                    <div class="file-name text-truncate"><?php echo $file; ?></div>
                    <div class="file-title text-truncate"><?php echo $title ?: 'Untitled Page'; ?></div>
                    <div class="file-title text-truncate" style="font-size:10px; font-weight:400;">
                        <?php echo $desc ?: '--'; ?>
                    </div>
                    <div class="module-label"><i class="bi bi-box-seam me-1"></i><?php echo $module ?: 'No module'; ?></div>
                </div>

                <?php if (!$un): ?>
                    <span class="m3-tonal-pill pill-mapped">Mapped</span>
                <?php else: ?>
                    <span class="m3-tonal-pill pill-unassigned">New</span>
                <?php endif; ?>

            </div>

        <?php endforeach; ?>
    </div>

</main>



<!-- ================= MODAL ================= -->
<div class="modal fade" id="permModal">
    <div class="modal-dialog modal-dialog-centered m3-modal-dialog">
        <div class="modal-content m3-modal-content shadow-lg">

            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="bi bi-shield-lock me-2"></i>Access Control</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="post" style="display:contents;">

                <div class="m3-modal-body">

                    <input type="text" name="page_name" id="m_page" class="m3-input-floating bg-light mb-2" readonly>
                    <input type="text" name="page_title" id="m_title" class="m3-input-floating mb-2"
                        placeholder="Title">
                    <input type="text" name="description" id="m_desc" class="m3-input-floating mb-2"
                        placeholder="Description">

                    <select name="module" id="m_module" class="m3-select-floating mb-2">
                        <option value=""></option>
                        <?php foreach ($modules as $mod): ?>
                            <option value="<?php echo $mod; ?>"><?php echo $mod; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text" name="root_page" id="m_root" class="m3-input-floating mb-3"
                        placeholder="Root Page">

                    <div class="m3-section-title mb-3 mt-3">Role Permissions</div>

                    <?php foreach ($roles as $role): ?>
                        <div class="role-row">
                            <div class="fw-bold small"><?php echo strtoupper($role); ?></div>
                            <select name="perm[<?php echo $role; ?>]" id="perm_<?php echo str_replace(' ', '_', $role); ?>">
                                <option value="3">Full</option>
                                <option value="2">Partial</option>
                                <option value="1">Read</option>
                                <option value="0">None</option>
                            </select>
                        </div>
                    <?php endforeach; ?>

                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn-m3-light" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" name="save_all_permissions" class="btn-m3-primary shadow">UPDATE
                        ACCESS</button>
                </div>

            </form>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>


<script>
    /* MODAL Logic */
    const modalEl = document.getElementById('permModal');
    const pModal = new bootstrap.Modal(modalEl);

    function openPermModalFromEl(el) {
        m_page.value = el.dataset.file;
        m_title.value = el.dataset.title;
        m_module.value = el.dataset.module;
        m_root.value = el.dataset.root || 'index.php';
        m_desc.value = el.dataset.desc || '';

        const p = JSON.parse(el.dataset.perm);
        for (const role in p) {
            let id = "perm_" + role.replace(/\s+/g, '_');
            let sel = document.getElementById(id);
            if (sel) sel.value = p[role];
        }
        pModal.show();
    }

    /* --- FILTER & LAZY LOAD COMBINED --- */
    let vis = 0;
    const batch = 40;
    let activeFilter = 'all';
    let allCards = [...document.querySelectorAll('.perm-card')];

    function filterCards(type, btn) {
        // ১. পিল এক্টিভ করা
        document.querySelectorAll('.pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // ২. ফিল্টার টাইপ আপডেট এবং ভিউ রিসেট
        activeFilter = type;
        vis = 0;

        // সব কার্ড প্রথমে হাইড করা
        allCards.forEach(c => c.style.display = 'none');

        // ৩. প্রথম ব্যাচ লোড করা
        loadBatch();
    }

    function loadBatch() {
        let count = 0;
        let started = 0;

        for (let i = 0; i < allCards.length; i++) {
            const card = allCards[i];
            let shouldShow = false;

            // ফিল্টার কন্ডিশন চেক
            if (activeFilter === 'all') shouldShow = true;
            else if (activeFilter === 'mapped' && !card.classList.contains('unassigned')) shouldShow = true;
            else if (activeFilter === 'unassigned' && card.classList.contains('unassigned')) shouldShow = true;

            if (shouldShow) {
                // আমরা অলরেডি যতগুলো দেখিয়েছি সেগুলো স্কিপ করে পরের ব্যাচ দেখানো
                if (started < vis) {
                    started++;
                    continue;
                }

                if (count < batch) {
                    card.style.display = 'flex'; // মেইন কন্টেইনারে d-flex ছিল তাই flex ব্যবহার করা নিরাপদ
                    count++;
                }
            }
        }
        vis += count;
    }

    // শুরুতে লোড করা
    document.addEventListener('DOMContentLoaded', () => {
        allCards.forEach(c => c.style.display = 'none');
        loadBatch();
    });

    // স্ক্রল ইভেন্ট
    window.addEventListener('scroll', () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
            loadBatch();
        }
    });



    function openActualPage(event, filename) {
        // এটি কার্ডের ক্লিক ইভেন্ট (মডাল ওপেন হওয়া) থামিয়ে দেবে
        event.stopPropagation();

        // নতুন ট্যাবে সংশ্লিষ্ট পেজটি ওপেন করবে
        window.open(filename, '_blank');
    }
</script>