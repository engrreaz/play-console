<?php
$page_title = "Advanced Permission Mapper";
include 'inc.php';

/* ========= LOAD ROLES & MODULES ========= */
$roles = [];
$r = $conn->query("SELECT userlevel FROM rolemanager WHERE sccode='0' ORDER BY id");
while ($row = $r->fetch_assoc()) $roles[] = $row['userlevel'];

$modules = [];
$m = $conn->query("SELECT module_name FROM modulelist ORDER BY slno");
while ($row = $m->fetch_assoc()) $modules[] = $row['module_name'];

/* ========= SAVE LOGIC ========= */
if (isset($_POST['save_all_permissions'])) {
    $page = mysqli_real_escape_string($conn, $_POST['page_name']);
    $module = mysqli_real_escape_string($conn, $_POST['module']);
    $root = mysqli_real_escape_string($conn, $_POST['root_page']);
    $title = mysqli_real_escape_string($conn, $_POST['page_title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    foreach ($_POST['perm'] as $role => $perm) {
        $role = mysqli_real_escape_string($conn, $role);
        $perm = intval($perm);
        $chk = $conn->query("SELECT id FROM permission_map_app WHERE page_name='$page' AND userlevel='$role' AND sccode='0'");

        if ($chk->num_rows) {
            $conn->query("UPDATE permission_map_app SET module='$module', root_page='$root', page_title='$title', description='$desc', permission='$perm', updatedby='$usr', modifieedate='$cur' WHERE page_name='$page' AND userlevel='$role' AND sccode='0'");
        } else {
            $conn->query("INSERT INTO permission_map_app (page_name,module,root_page,page_title,description,sccode,userlevel,permission,updatedby,modifieedate) VALUES ('$page','$module','$root','$title','$desc','0','$role','$perm','$usr','$cur')");
        }
    }
}

/* ========= DATA PREPARATION FOR JS ========= */
$files = glob("*.php");
$exclude = ['inc.php', 'footer.php', 'header.php', 'db.php', 'config.php', 'permission-mapper.php'];
$mapped = [];
$q = $conn->query("SELECT * FROM permission_map_app WHERE sccode='0'");
while ($row = $q->fetch_assoc()) $mapped[$row['page_name']][$row['userlevel']] = $row;

$js_data = [];
$unassigned_count = 0;
foreach ($files as $file) {
    if (in_array($file, $exclude)) continue;
    $data = $mapped[$file] ?? [];
    $is_un = empty($data);
    if ($is_un) $unassigned_count++;

    $perms = [];
    foreach ($roles as $role) $perms[$role] = $data[$role]['permission'] ?? 0;

    $js_data[] = [
        'file' => $file,
        'title' => reset($data)['page_title'] ?? '',
        'module' => reset($data)['module'] ?? '',
        'root' => reset($data)['root_page'] ?? 'index.php',
        'desc' => reset($data)['description'] ?? '',
        'unassigned' => $is_un,
        'perm' => $perms
    ];
}
?>

<style>
    /* --- ১. মেইন থিম ও হিরো --- */
    body { background-color: #F7F2FA; font-family: 'Roboto', sans-serif; margin: 0; }
    .hero-container { background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%); color: white; padding: 25px 20px; border-radius: 0 0 28px 28px; }
    
    /* --- ২. পিল ফিল্টার --- */
    .pill { border: none; padding: 8px 20px; border-radius: 50px; font-weight: 700; font-size: .75rem; background: rgba(255,255,255,0.2); color: white; cursor: pointer; transition: 0.3s; }
    .pill.active { background: white; color: #6750A4; }

    /* --- ৩. পারমিশন কার্ড (M3 Style) --- */
    .perm-card {
        background: #FFFFFF; border-radius: 16px; padding: 16px; margin: 12px 16px;
        display: flex; align-items: center; gap: 16px; cursor: pointer;
        border: 1px solid #E0E0E0; transition: 0.2s;
    }
    .perm-card:active { transform: scale(0.97); background-color: #F3EDF7; }
    .perm-card.unassigned { border-left: 5px solid #F2B8B5; }
    .perm-card.mapped { border-left: 5px solid #B4E4BC; }

    .icon-box-m3 {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;
    }
    .c-mapped { background-color: #EADDFF; color: #21005D; }
    .c-unassigned { background-color: #F9DEDC; color: #410E0B; }

    .file-name { font-size: 0.95rem; font-weight: 800; color: #1C1B1F; margin-bottom: 2px; }
    .file-title { font-size: 0.75rem; font-weight: 600; color: #49454F; line-height: 1.2; }
    .module-label { font-size: 0.65rem; font-weight: 700; color: #79747E; margin-top: 4px; display: block; }

    /* --- ৪. টোনাল পিলস --- */
    .m3-tonal-pill { padding: 4px 12px; border-radius: 8px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; }
    .pill-mapped { background-color: #E8F5E9; color: #2E7D32; }
    .pill-unassigned { background-color: #FDE7E9; color: #B3261E; }

    /* --- ৫. মডাল সিএসএস (M3) --- */
    .m3-modal-content { border-radius: 28px !important; border: none !important; }
    .m3-input-floating { width: 100%; background: #F3EDF7; border: none; border-bottom: 2px solid #79747E; border-radius: 12px 12px 4px 4px; padding: 12px; margin-bottom: 10px; font-weight: 600; }
    .role-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; background: #fff; border: 1px solid #eee; border-radius: 12px; margin-bottom: 6px; }
    .role-row select { background: #6750A4; color: white; border: none; border-radius: 20px; padding: 4px 10px; font-size: 0.75rem; font-weight: 700; }
</style>

<main>
    <div class="hero-container shadow">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div style="font-size:1.5rem;font-weight:900;">Permission Mapper</div>
                <div style="font-size:.7rem; opacity: 0.8; font-weight: 600;">System Access Control</div>
            </div>
            <div class="text-end">
                <div style="font-size:1.8rem;font-weight:900;"><?php echo $unassigned_count; ?></div>
                <div style="font-size:.6rem;font-weight:800; letter-spacing: 1px;">NEW FILES</div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button class="pill active" onclick="filterData('all', this)">All Files</button>
            <button class="pill" onclick="filterData('mapped', this)">Mapped</button>
            <button class="pill" onclick="filterData('unassigned', this)">New</button>
        </div>
    </div>

    <div id="cardList" style="padding-bottom: 100px;"></div>
</main>

<div class="modal fade" id="permModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 92%; margin: 10px auto;">
        <div class="modal-content m3-modal-content shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>Access Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body" style="max-height: 65vh; overflow-y: auto;">
                    <input type="text" name="page_name" id="m_page" class="m3-input-floating" readonly style="background: #e0e0e0;">
                    <input type="text" name="page_title" id="m_title" class="m3-input-floating" placeholder="Page Title" required>
                    <input type="text" name="description" id="m_desc" class="m3-input-floating" placeholder="Description">
                    
                    <select name="module" id="m_module" class="m3-input-floating">
                        <option value="">Select Module</option>
                        <?php foreach($modules as $m) echo "<option value='$m'>$m</option>"; ?>
                    </select>
                    
                    <input type="text" name="root_page" id="m_root" class="m3-input-floating" placeholder="Root (e.g. index.php)">
                    
                    <div style="font-size: 0.75rem; font-weight: 800; color: #6750A4; margin: 15px 5px 10px;">ROLE BASED PERMISSIONS</div>
                    <div id="roleContainer">
                        <?php foreach($roles as $role): ?>
                        <div class="role-row">
                            <div class="fw-bold small"><?php echo strtoupper($role); ?></div>
                            <select name="perm[<?php echo $role; ?>]" id="perm_<?php echo str_replace(' ', '_', $role); ?>">
                                <option value="3">Full</option>
                                <option value="2">Partial</option>
                                <option value="1">Read Only</option>
                                <option value="0">None</option>
                            </select>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill flex-fill fw-bold" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" name="save_all_permissions" class="btn btn-primary rounded-pill flex-fill fw-bold shadow">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    // ১. ডাটা ইনজেকশন
    const rawData = <?php echo json_encode($js_data); ?>;
    let filteredData = [...rawData];
    let offset = 0;
    const limit = 25;

    const listEl = document.getElementById('cardList');
    const pModal = new bootstrap.Modal(document.getElementById('permModal'));

    // ২. কার্ড টেমপ্লেট ফাংশন
    function renderCard(item) {
        const u = item.unassigned;
        return `
            <div class="perm-card ${u ? 'unassigned' : 'mapped'}" onclick="openEditor('${item.file}')">
                <div class="icon-box-m3 ${u ? 'c-unassigned' : 'c-mapped'}" onclick="openLink(event, '${item.file}')">
                    <i class="bi ${u ? 'bi-file-earmark-plus' : 'bi-file-earmark-check-fill'}"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="file-name text-truncate">${item.file}</div>
                    <div class="file-title text-truncate">${item.title || 'Untitled Page'}</div>
                    <div class="module-label"><i class="bi bi-layers-half me-1"></i>${item.module || 'No Module'}</div>
                </div>
                <span class="m3-tonal-pill ${u ? 'pill-unassigned' : 'pill-mapped'}">${u ? 'New' : 'Mapped'}</span>
            </div>
        `;
    }

    // ৩. ব্যাচ লোডার (ওয়েবভিউ ক্রাশ প্রতিরোধক)
    function loadBatch() {
        const batch = filteredData.slice(offset, offset + limit);
        if (batch.length === 0) return;

        let html = "";
        batch.forEach(item => { html += renderCard(item); });
        listEl.insertAdjacentHTML('beforeend', html);
        offset += limit;
    }

    // ৪. ফিল্টার ফাংশন
    function filterData(type, btn) {
        document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');

        if (type === 'all') filteredData = [...rawData];
        else if (type === 'mapped') filteredData = rawData.filter(d => !d.unassigned);
        else filteredData = rawData.filter(d => d.unassigned);

        listEl.innerHTML = "";
        offset = 0;
        loadBatch();
        window.scrollTo(0,0);
    }

    // ৫. এডিটর ওপেন লজিক
    function openEditor(filename) {
        const item = rawData.find(d => d.file === filename);
        if (!item) return;

        document.getElementById('m_page').value = item.file;
        document.getElementById('m_title').value = item.title;
        document.getElementById('m_module').value = item.module;
        document.getElementById('m_root').value = item.root;
        document.getElementById('m_desc').value = item.desc;

        for (const role in item.perm) {
            let id = "perm_" + role.replace(/\s+/g, '_');
            let sel = document.getElementById(id);
            if (sel) sel.value = item.perm[role];
        }
        pModal.show();
    }

    function openLink(e, file) {
        e.stopPropagation();
        window.open(file, '_blank');
    }

    // ৬. স্ক্রল ও ইনিশিয়াল লোড
    loadBatch();
    window.onscroll = function() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
            loadBatch();
        }
    };
</script>