<?php
$page_title = "Hub Manager";
include_once 'inc.php';

/* =============================
  SAVE MODULE PERMISSION (Original Logic remains same)
=============================*/
if (isset($_POST['module_id'], $_POST['sccode'], $_POST['role'])) {
    $module_id = intval($_POST['module_id']);
    $sccode = intval($_POST['sccode']);
    $roles = $_POST['role'];
    $stmt = $conn->prepare("DELETE FROM hub_module_permissions WHERE module_id=? AND sccode=?");
    $stmt->bind_param("ii", $module_id, $sccode);
    $stmt->execute();
    $ins = $conn->prepare("INSERT INTO hub_module_permissions (module_id, role, sccode) VALUES (?, ?, ?)");
    foreach ($roles as $r) {
        $ins->bind_param("isi", $module_id, $r, $sccode);
        $ins->execute();
    }
}

$cats = $conn->query("SELECT * FROM hub_categories ORDER BY sort_order");
$roles_list = ['Administrator', 'Super Administrator', 'Accountants', 'Teacher', 'Staff'];
?>

<style>
    /* Category Card Base */
    .cat-container-card {
        background: #fff;
        border-radius: 12px;
        margin: 16px 12px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    /* Category Header (Tonal) */
    .cat-header {
        background: var(--m3-tonal);
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(103, 80, 164, 0.1);
    }

    .cat-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--m3-on-tonal);
        margin: 0;
    }

    /* Module List Tiles */
    .module-tile {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #f8f8f8;
        transition: background 0.2s;
    }

    .module-tile:last-child {
        border-bottom: none;
    }

    .module-tile:active {
        background: #f3f3f3;
    }

    .mod-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #F7F2FA;
        color: var(--m3-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 1.2rem;
    }

    .mod-info {
        flex-grow: 1;
    }

    .mod-name {
        font-size: 0.88rem;
        font-weight: 700;
        color: #1C1B1F;
        display: block;
    }

    .mod-status {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    /* Action Buttons (Compact) */
    .m3-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        margin-left: 4px;
        transition: 0.2s;
    }
</style>

<main class="pb-5">
    <div class="hero-container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-black m-0">Hub Manager</h4>
                <p class="small m-0 opacity-75">Grouped categories and modules</p>
            </div>
            <button style="z-index:1000;" class="btn btn-light btn-sm fw-bold rounded-pill px-3" onclick="openCategoryModal()">
                <i class="bi bi-plus-lg me-1"></i> New Category
            </button>
        </div>
    </div>

    <?php while ($c = $cats->fetch_assoc()): ?>
        <div class="cat-container-card">
            <div class="cat-header">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="cat-title"><?= $c['name'] ?></h6>
                    <span class="status-chip <?= $c['status'] ? 'status-active' : 'status-disabled' ?>"
                        style="font-size: 0.55rem;">
                        <?= $c['status'] ? 'ACTIVE' : 'OFF' ?>
                    </span>
                </div>
                <div class="d-flex gap-1">
                    <button class="m3-icon-btn bg-white text-primary shadow-sm"
                        onclick='editCategory(<?= json_encode($c) ?>)'>
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="m3-icon-btn bg-white text-dark shadow-sm"
                        onclick="openModuleModalWithCat(<?= $c['id'] ?>)">
                        <i class="bi bi-plus-circle-fill"></i>
                    </button>
                    <button class="m3-icon-btn bg-white text-danger shadow-sm" onclick="deleteCategory(<?= $c['id'] ?>)">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>

            <div class="module-list">
                <?php
                $cat_id = $c['id'];
                $mod_query = $conn->query("SELECT * FROM hub_modules WHERE category_id = $cat_id ORDER BY sort_order");
                if ($mod_query->num_rows > 0):
                    while ($m = $mod_query->fetch_assoc()):
                        ?>
                        <div class="module-tile">
                            <div class="mod-icon-box shadow-sm">
                                <i class="<?= $m['icon'] ?>"></i>
                            </div>
                            <div class="mod-info">
                                <span class="mod-name"><?= $m['title'] ?></span>
                                <span class="mod-status <?= $m['active'] ? 'text-success' : 'text-muted' ?>">
                                    <?= $m['active'] ? '• Enabled' : '• Disabled' ?>
                                </span>
                            </div>
                            <div class="d-flex">
                                <button class="m3-icon-btn bg-light text-info" onclick='editModule(<?= json_encode($m) ?>)'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="m3-icon-btn bg-light text-secondary" onclick="openPermModal(<?= $m['id'] ?>)">
                                    <i class="bi bi-shield-lock"></i>
                                </button>
                                <button class="m3-icon-btn bg-light text-danger" onclick="deleteModule(<?= $m['id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php
                    endwhile;
                else:
                    echo '<div class="p-3 text-center small text-muted opacity-50">No modules in this category.</div>';
                endif;
                ?>
            </div>
        </div>
    <?php endwhile; ?>
</main>


<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-m3 px-2">
            <form method="post" id="catForm">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-black">Manage Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="cat_id" id="cat_id">
                    <div class="m3-floating-group">
                        <label class="m3-floating-label">Category Name</label>
                        <input type="text" name="name" id="cat_name" class="m3-input-floating"
                            placeholder="e.g. Academic">
                    </div>
                    <div class="m3-floating-group">
                        <label class="m3-floating-label">Display Status</label>
                        <select name="status" id="cat_status" class="m3-input-floating form-select">
                            <option value="1">Active</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="moduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-m3 px-2">
            <form method="post" id="moduleForm">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-black">Manage Module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="module_id" id="module_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Module Title</label>
                                <input type="text" name="title" id="mod_title" class="m3-input-floating">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Icon Class (Bootstrap/FontAwesome)</label>
                                <input type="text" name="icon" id="mod_icon" class="m3-input-floating">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Onclick JS Action</label>
                                <input type="text" name="onclick" id="mod_onclick" class="m3-input-floating">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Select Category</label>
                                <select name="category_id" id="mod_cat" class="m3-input-floating form-select">
                                    <?php
                                    $rc = $conn->query("SELECT * FROM hub_categories");
                                    while ($r = $rc->fetch_assoc())
                                        echo "<option value='{$r['id']}'>{$r['name']}</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Module Status</label>
                                <select name="active" id="mod_active" class="m3-input-floating form-select">
                                    <option value="1">Enabled</option>
                                    <option value="0">Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button class="btn btn-success w-100 py-3 fw-bold rounded-4 shadow">Save Module Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="permModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-m3 px-2">
            <form method="post" id="permForm">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-black">Access Control</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="module_id" id="perm_module_id">
                    <div class="m3-floating-group">
                        <label class="m3-floating-label">Target SCCODE (0 for All)</label>
                        <input type="number" name="sccode" class="m3-input-floating" value="0">
                    </div>
                    <div class="m3-floating-group">
                        <label class="m3-floating-label">Available Roles (Hold Ctrl to Multi-select)</label>
                        <select name="role[]" multiple class="form-control"
                            style="border-radius: 12px; height: 120px; border: 2px solid var(--m3-outline); padding: 10px;">
                            <?php foreach ($roles_list as $r)
                                echo "<option value='$r'>$r</option>"; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow">Save Permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>


<script>
    function openModuleModalWithCat(catId) {
        moduleForm.reset();
        document.getElementById('mod_cat').value = catId;
        modMdl.show();
    }
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        window.catMdl = new bootstrap.Modal(document.getElementById('categoryModal'));
        window.modMdl = new bootstrap.Modal(document.getElementById('moduleModal'));
        window.perMdl = new bootstrap.Modal(document.getElementById('permModal'));
    });

    function openCategoryModal() {  catMdl.show(); }

    function editCategory(d) { cat_id.value = d.id; cat_name.value = d.name; cat_status.value = d.status; catMdl.show(); }

    function openModuleModal() { moduleForm.reset(); modMdl.show(); }
    function editModule(d) {
        module_id.value = d.id;
        mod_title.value = d.title;
        mod_icon.value = d.icon;
        mod_onclick.value = d.onclick;
        mod_cat.value = d.category_id;
        mod_active.value = d.active;
        modMdl.show();
    }

    function openPermModal(id) { perm_module_id.value = id; perMdl.show(); }

    function deleteCategory(id) { if (confirm("Are you sure?")) console.log("Delete Category: " + id); }
    function deleteModule(id) { if (confirm("Are you sure?")) console.log("Delete Module: " + id); }
</script>

<?php
// Include the modals from your previous code here 
// Just ensure the IDs match the JS functions
?>