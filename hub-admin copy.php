<?php
$page_title = "Hub Manager";
include_once 'inc.php';

/* =============================
  SAVE MODULE PERMISSION (Original Logic)
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
    // exit;
}

// LOAD DATA
$cats = $conn->query("SELECT * FROM hub_categories ORDER BY sort_order");
$roles_list = ['Administrator', 'Super Administrator', 'Accountants', 'Teacher', 'Staff'];
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-gradient: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        --m3-tonal: #F3EDF7;
        --m3-on-tonal: #21005D;
        --m3-outline: #CAC4D0;
    }

    body { background: var(--m3-surface); font-family: 'Segoe UI', Roboto, sans-serif; }

    /* Hero Styling */
    .hero-container {
        margin: 12px; padding: 24px 20px; border-radius: 16px;
        background: var(--m3-primary-gradient); color: white;
        box-shadow: 0 8px 24px rgba(103, 80, 164, 0.15);
    }

    /* M3 Card & Table */
    .m3-card {
        background: white; border-radius: 12px; margin: 0 12px 16px;
        border: 1px solid #F0F0F0; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .m3-header-tonal {
        background: var(--m3-tonal); padding: 12px 16px;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid var(--m3-outline);
    }
    .m3-header-title { font-size: 0.9rem; font-weight: 800; color: var(--m3-on-tonal); margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .table thead th { font-size: 0.75rem; text-transform: uppercase; color: #79747E; background: #fafafa; border-bottom: 2px solid #eee; }
    .table td { vertical-align: middle; font-size: 0.88rem; font-weight: 500; }

    /* Badges & Actions */
    .status-chip { font-size: 0.65rem; padding: 4px 10px; border-radius: 100px; font-weight: 800; }
    .status-active { background: #E8F5E9; color: #2E7D32; }
    .status-disabled { background: #F5F5F5; color: #757575; }

    .action-btn { width: 34px; height: 34px; border-radius: 8px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; }
    .action-btn:active { transform: scale(0.9); }

    /* Modals Floating Labels */
    .m3-floating-group { position: relative; margin-bottom: 20px; }
    .m3-floating-label {
        position: absolute; left: 12px; top: -10px; background: white;
        padding: 0 6px; font-size: 0.7rem; font-weight: 700; color: var(--m3-primary); z-index: 10;
    }
    .m3-input-floating {
        width: 100%; height: 50px; padding: 10px 14px; border-radius: 8px;
        border: 2px solid var(--m3-outline); outline: none; font-weight: 600;
    }
    .m3-input-floating:focus { border-color: var(--m3-primary); }

    .modal-m3 { border-radius: 24px !important; overflow: hidden; border: none; }
</style>

<main class="pb-5">
    <div class="hero-container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-black m-0">Hub Manager</h4>
                <p class="small m-0 opacity-75">Control modules, categories and permissions</p>
            </div>
            <i class="bi bi-grid-3x3-gap-fill display-5 opacity-25"></i>
        </div>
    </div>

    <div class="m3-card shadow-sm">
        <div class="m3-header-tonal">
            <h6 class="m3-header-title">Categories</h6>
            <button class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" onclick="openCategoryModal()">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </button>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Name</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($c = $cats->fetch_assoc()): ?>
                    <tr>
                        <td class="ps-3 fw-bold"><?= $c['name'] ?></td>
                        <td>
                            <span class="status-chip <?= $c['status'] ? 'status-active' : 'status-disabled' ?>">
                                <?= $c['status'] ? 'ACTIVE' : 'DISABLED' ?>
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <button class="action-btn bg-warning-subtle text-warning" onclick='editCategory(<?= json_encode($c) ?>)'>
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="action-btn bg-danger-subtle text-danger ms-1" onclick="deleteCategory(<?= $c['id'] ?>)">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="m3-card shadow-sm">
        <div class="m3-header-tonal">
            <h6 class="m3-header-title">Module List</h6>
            <button class="btn btn-sm btn-dark rounded-pill px-3 fw-bold" onclick="openModuleModal()">
                <i class="bi bi-plus-lg me-1"></i> Add Module
            </button>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Module</th>
                        <th>Category</th>
                        <th>Icon</th>
                        <th>Active</th>
                        <th class="text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = $conn->query("SELECT m.*, c.name cat FROM hub_modules m JOIN hub_categories c ON c.id=m.category_id ORDER BY c.sort_order, m.sort_order");
                    while ($m = $q->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="ps-3 fw-bold"><?= $m['title'] ?></td>
                        <td><span class="badge bg-light text-dark border"><?= $m['cat'] ?></span></td>
                        <td><i class="<?= $m['icon'] ?> text-primary fs-5"></i></td>
                        <td>
                            <span class="status-chip <?= $m['active'] ? 'status-active' : 'status-disabled' ?>">
                                <?= $m['active'] ? 'YES' : 'NO' ?>
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <button class="action-btn bg-info-subtle text-info" onclick='editModule(<?= json_encode($m) ?>)' title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="action-btn bg-secondary-subtle text-secondary ms-1" onclick="openPermModal(<?= $m['id'] ?>)" title="Permissions">
                                <i class="bi bi-shield-lock"></i>
                            </button>
                            <button class="action-btn bg-danger-subtle text-danger ms-1" onclick="deleteModule(<?= $m['id'] ?>)" title="Delete">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
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
                        <input type="text" name="name" id="cat_name" class="m3-input-floating" placeholder="e.g. Academic">
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
                                    while ($r = $rc->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['name']}</option>";
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
                        <select name="role[]" multiple class="form-control" style="border-radius: 12px; height: 120px; border: 2px solid var(--m3-outline); padding: 10px;">
                            <?php foreach ($roles_list as $r) echo "<option value='$r'>$r</option>"; ?>
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
    document.addEventListener("DOMContentLoaded", function () {
        window.catMdl = new bootstrap.Modal(document.getElementById('categoryModal'));
        window.modMdl = new bootstrap.Modal(document.getElementById('moduleModal'));
        window.perMdl = new bootstrap.Modal(document.getElementById('permModal'));
    });

    function openCategoryModal() { catForm.reset(); catMdl.show(); }
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

    function deleteCategory(id) { if(confirm("Are you sure?")) console.log("Delete Category: " + id); }
    function deleteModule(id) { if(confirm("Are you sure?")) console.log("Delete Module: " + id); }
</script>