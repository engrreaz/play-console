<?php
$page_title = "Advanced Permission Mapper";
include 'inc.php';

/* ========= LOAD ROLES & MODULES (আগের মতোই) ========= */
$roles = [];
$r = $conn->query("SELECT userlevel FROM rolemanager WHERE sccode='0' ORDER BY id");
while ($row = $r->fetch_assoc())
    $roles[] = $row['userlevel'];

$modules = [];
$m = $conn->query("SELECT module_name FROM modulelist ORDER BY slno");
while ($row = $m->fetch_assoc())
    $modules[] = $row['module_name'];

/* ========= SAVE LOGIC (আগের মতোই) ========= */
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

/* ========= FILE SCAN & DATA PREPARATION ========= */
$files = glob("*.php");
$exclude = ['inc.php', 'footer.php', 'header.php', 'db.php', 'config.php', 'permission-mapper.php'];

$mapped = [];
$q = $conn->query("SELECT * FROM permission_map_app WHERE sccode='0'");
while ($row = $q->fetch_assoc())
    $mapped[$row['page_name']][$row['userlevel']] = $row;

// সব ডেটা একটি অ্যারেতে গুছিয়ে নেওয়া (জাভাস্ক্রিপ্টের জন্য)
$js_data = [];
$unassigned = 0;

foreach ($files as $file) {
    if (in_array($file, $exclude))
        continue;
    $data = $mapped[$file] ?? [];
    $is_unassigned = empty($data);
    if ($is_unassigned)
        $unassigned++;

    $perm_map = [];
    foreach ($roles as $r)
        $perm_map[$r] = $data[$r]['permission'] ?? 0;

    $js_data[] = [
        'file' => $file,
        'title' => reset($data)['page_title'] ?? '',
        'module' => reset($data)['module'] ?? '',
        'root' => reset($data)['root_page'] ?? 'index.php',
        'desc' => reset($data)['description'] ?? '',
        'unassigned' => $is_unassigned,
        'perm' => $perm_map
    ];
}
?>

<style>
    /* --- আপনার আগের সব CSS এখানে থাকবে --- */
    /* অতিরিক্ত পারফরম্যান্স টিপ: জটিল শ্যাডো কমানো */
    .perm-card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }
</style>

<main>
    <div class="hero-container pb-4">
        <div class="d-flex justify-content-between align-items-center px-3 pt-3">
            <div style="font-size:1.4rem;font-weight:900;">Permission Mapper</div>
            <div class="text-end">
                <div style="font-size:1.5rem;font-weight:900;color:#FFD8D6;"><?php echo $unassigned; ?></div>
                <div style="font-size:.6rem;font-weight:800;">UNASSIGNED</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3 px-3">
            <button class="pill active" onclick="filterCards('all',this)">All</button>
            <button class="pill" onclick="filterCards('mapped',this)">Mapped</button>
            <button class="pill" onclick="filterCards('unassigned',this)">New</button>
        </div>
    </div>

    <div id="cardContainer" style="padding:15px 0 100px;"></div>
</main>

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
                    <button type="submit" name="save_all_permissions" class="btn-m3-primary shadow">UPDATE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    // ১. সব ডেটা জাভাস্ক্রিপ্ট ভেরিয়েবলে নিয়ে আসা (এটি মেমোরি কম খাবে)
    const masterData = <?php echo json_encode($js_data); ?>;
    let filteredData = [...masterData];
    let visibleCount = 0;
    const batchSize = 30;

    const container = document.getElementById('cardContainer');
    const modalEl = document.getElementById('permModal');
    const pModal = new bootstrap.Modal(modalEl);

    // ২. কার্ড রেন্ডার ফাংশন (জাভাস্ক্রিপ্ট দিয়ে HTML তৈরি)
    function createCardHTML(item) {
        const statusClass = item.unassigned ? 'unassigned' : 'mapped';
        const iconClass = item.unassigned ? 'c-unassigned' : 'c-mapped';
        const iconType = item.unassigned ? 'bi-file-earmark-x' : 'bi-file-earmark-check';
        const pillText = item.unassigned ? 'New' : 'Mapped';
        const pillClass = item.unassigned ? 'pill-unassigned' : 'pill-mapped';

        return `
            <div class="perm-card mx-3 d-flex ${statusClass}" onclick="openPermModalByIndex('${item.file}')">
                <div class="icon-box-m3 ${iconClass}" onclick="openActualPage(event, '${item.file}')" style="cursor: alias;">
                    <i class="bi ${iconType}"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="file-name text-truncate">${item.file}</div>
                    <div class="file-title text-truncate">${item.title || 'Untitled Page'}</div>
                    <div class="module-label"><i class="bi bi-box-seam me-1"></i>${item.module || 'No module'}</div>
                </div>
                <span class="m3-tonal-pill ${pillClass}">${pillText}</span>
            </div>
        `;
    }

    // ৩. ব্যাচ লোডিং (Webview-র জন্য সবচেয়ে নিরাপদ)
    function renderNextBatch() {
        const nextBatch = filteredData.slice(visibleCount, visibleCount + batchSize);
        if (nextBatch.length === 0) return;

        let html = '';
        nextBatch.forEach(item => {
            html += createCardHTML(item);
        });

        container.insertAdjacentHTML('beforeend', html);
        visibleCount += batchSize;
    }

    // ৪. ফিল্টারিং লজিক
    function filterCards(type, btn) {
        document.querySelectorAll('.pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        if (type === 'all') filteredData = [...masterData];
        else if (type === 'mapped') filteredData = masterData.filter(d => !d.unassigned);
        else filteredData = masterData.filter(d => d.unassigned);

        container.innerHTML = '';
        visibleCount = 0;
        renderNextBatch();
    }

    // ৫. মডাল ওপেন লজিক (ডেটা খুঁজে বের করা)
    function openPermModalByIndex(filename) {
        const item = masterData.find(d => d.file === filename);
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

    function openActualPage(event, filename) {
        event.stopPropagation();
        window.open(filename, '_blank');
    }

    // ৬. ইনিশিয়ালাইজেশন
    document.addEventListener('DOMContentLoaded', () => {
        renderNextBatch();
    });

    // স্ক্রল ইভেন্ট
    window.addEventListener('scroll', () => {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
            renderNextBatch();
        }
    });
</script>