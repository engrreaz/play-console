<?php
ob_start();
$page_title = "Hub Manager";
include_once 'inc.php'; // DB connection & common includes

// ==============================
// DELETE CATEGORY AJAX
// ==============================
if (isset($_POST['delete_cat'])) {
    $id = intval($_POST['delete_cat']);
    $conn->query("DELETE FROM hub_module_permissions WHERE module_id IN (SELECT id FROM hub_modules WHERE category_id=$id)");
    $conn->query("DELETE FROM hub_modules WHERE category_id=$id");
    $conn->query("DELETE FROM hub_categories WHERE id=$id");
    echo "ok";
    exit;
}

// ==============================
// DELETE MODULE AJAX
// ==============================
if (isset($_POST['delete_mod'])) {
    $id = intval($_POST['delete_mod']);
    $conn->query("DELETE FROM hub_module_permissions WHERE module_id=$id");
    $conn->query("DELETE FROM hub_modules WHERE id=$id");
    echo "ok";
    exit;
}

// ==============================
// SAVE CATEGORY
// ==============================
if (isset($_POST['name'], $_POST['status']) && !isset($_POST['delete_cat'])) {
    $name = trim($_POST['name']);
    $status = intval($_POST['status']);
    if (!empty($_POST['cat_id'])) {
        $id = intval($_POST['cat_id']);
        $stmt = $conn->prepare("UPDATE hub_categories SET name=?, status=? WHERE id=?");
        $stmt->bind_param("sii", $name, $status, $id);
    } else {
        $so = $conn->query("SELECT IFNULL(MAX(sort_order),0)+1 s FROM hub_categories")->fetch_assoc()['s'];
        $stmt = $conn->prepare(query: "INSERT INTO hub_categories (name,status,sort_order) VALUES (?,?,?)");
        $stmt->bind_param("sii", $name, $status, $so);
    }
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ==============================
// SAVE MODULE
// ==============================
if (isset($_POST['title'], $_POST['icon'], $_POST['onclick']) && !isset($_POST['delete_mod'])) {
    $title = trim($_POST['title']);
    $icon = trim($_POST['icon']);
    $onclick = trim($_POST['onclick']);
    $cat = intval($_POST['category_id']);
    $active = intval($_POST['active']);

    if (!empty($_POST['module_id'])) {
        $id = intval($_POST['module_id']);
        $stmt = $conn->prepare("UPDATE hub_modules SET title=?,icon=?,onclick=?,category_id=?,active=? WHERE id=?");
        $stmt->bind_param("sssiii", $title, $icon, $onclick, $cat, $active, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO hub_modules (title,icon,onclick,category_id,active) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssii", $title, $icon, $onclick, $cat, $active);
    }
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* ==============================
   AJAX : SAVE MODULE PERMISSIONS
============================== */
/* ==============================
   AJAX : SAVE MODULE PERMISSIONS
============================== */




// ==============================
// FETCH DATA
// ==============================
$cats = $conn->query("SELECT * FROM hub_categories ORDER BY sort_order");
$roles_list = ['Administrator', 'Super Administrator', 'Accountants', 'Teacher', 'Staff'];
?>

<!-- ==============================
     STYLES
============================== -->



<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-gradient: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        --m3-tonal-container: #EADDFF;
        --m3-on-tonal-container: #21005D;
        --m3-outline: #79747E;
        --m3-outline-variant: #CAC4D0;
        --m3-radius: 8px;
    }

    body {
        background-color: var(--m3-surface);
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

    /* Hero Mesh Gradient Section */
    .hero-container {
        margin: 12px;
        padding: 24px 20px;
        border-radius: 16px;
        background: var(--m3-primary-gradient);
        color: white;
        box-shadow: 0 10px 20px rgba(103, 80, 164, 0.15);
    }

    /* Category Parent Card */
    .cat-container-card {
        background: #fff;
        border-radius: 12px !important;
        border: 1px solid #f0f0f0 !important;
        overflow: hidden;
        margin: 0 12px 16px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .cat-header {
        padding: 14px 16px;
        background-color: #F7F2FA;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--m3-outline-variant);
    }

    .cat-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--m3-on-tonal-container);
        margin: 0;
        text-transform: uppercase;
    }

    /* Module List Tile Styling */
    .module-tile {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #f9f9f9;
        transition: background 0.2s;
    }

    .module-tile:last-child {
        border-bottom: none;
    }

    .module-tile:active {
        background: #F3EDF7;
        transform: scale(0.99);
    }

    .mod-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        background: var(--m3-tonal-container);
        color: var(--m3-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .mod-info {
        flex-grow: 1;
    }

    .mod-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1C1B1F;
        display: block;
    }

    .mod-status {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Action Buttons */
    .m3-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-left: 6px;
        transition: 0.2s;
    }

    .m3-icon-btn:active {
        transform: scale(0.85);
    }

    /* Floating Input System for Modals */
    .m3-floating-group {
        position: relative;
        margin-bottom: 24px;
    }

    .m3-floating-label {
        position: absolute;
        left: 12px;
        top: -10px;
        background: #fff;
        padding: 0 6px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--m3-primary);
        z-index: 10;
        text-transform: uppercase;
    }

    .m3-field {
        width: 100%;
        height: 54px;
        padding: 12px 16px;
        font-size: 0.95rem;
        font-weight: 600;
        border: 2px solid var(--m3-outline-variant);
        border-radius: 8px !important;
        outline: none;
        transition: 0.2s;
    }

    .m3-field:focus {
        border-color: var(--m3-primary);
        background: #fff;
    }

    .modal-content-m3 {
        border-radius: 28px !important;
        border: none;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .cat-header {
        cursor: grab;
    }

    .module-tile {
        cursor: grab;
    }
</style>




<main class="pb-5">
    <div class="hero-container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-black m-0">Hub Manager</h4>
                <p class="small m-0 opacity-75">Control and categorize modules</p>
            </div>
            <button class="btn btn-light btn-sm fw-bold rounded-pill px-3 " style="z-index:2000;"
                onclick="openCategoryModal()">
                <i class="bi bi-plus-lg me-1"></i> New Category
            </button>
        </div>
    </div>

    <?php while ($c = $cats->fetch_assoc()): ?>
        <div class="cat-container-card shadow-sm sortable-category" data-id="<?= $c['id'] ?>">
            <div class="cat-header">
                <div>
                    <h6 class="cat-title"><?= $c['name'] ?></h6>
                    <span
                        class="badge rounded-pill mt-1 <?= $c['status'] ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>"
                        style="font-size: 0.6rem;">
                        <?= $c['status'] ? 'ACTIVE' : 'OFFLINE' ?>
                    </span>
                </div>
                <div class="d-flex gap-1">
                    <button class="m3-icon-btn bg-white text-primary border shadow-sm"
                        onclick='editCategory(<?= json_encode($c) ?>)'>
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="m3-icon-btn bg-white text-success border shadow-sm"
                        onclick="openModuleModalWithCat(<?= $c['id'] ?>)">
                        <i class="bi bi-plus-circle-fill"></i>
                    </button>
                    <button class="m3-icon-btn bg-white text-danger border shadow-sm"
                        onclick="deleteCategory(<?= $c['id'] ?>)">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>

            <div class="module-list sortable-modules" data-category="<?= $c['id'] ?>">
                <?php
                $mod_query = $conn->query("SELECT * FROM hub_modules WHERE category_id={$c['id']} ORDER BY sort_order");
                if ($mod_query->num_rows > 0):
                    while ($m = $mod_query->fetch_assoc()):
                        ?>
                        <div class="module-tile" data-id="<?= $m['id'] ?>">
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
                    <?php endwhile; else: ?>
                    <div class="p-4 text-center small text-muted opacity-50">
                        <i class="bi bi-box-seam display-6 d-block mb-2"></i>
                        No modules in this category.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</main>

<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-m3 p-2">
            <form method="post" id="catForm">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Manage Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <input type="hidden" name="cat_id" id="cat_id">
                    <div class="m3-floating-group">
                        <label class="m3-floating-label">Category Name</label>
                        <input type="text" name="name" id="cat_name" class="m3-field" placeholder="e.g. Academic">
                    </div>
                    <div class="m3-floating-group">
                        <label class="m3-floating-label">Display Status</label>
                        <select name="status" id="cat_status" class="m3-field form-select">
                            <option value="1">Active</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">SAVE
                        CATEGORY</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="moduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-m3 p-2">
            <form method="post" id="moduleForm">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Manage Module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <input type="hidden" name="module_id" id="module_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Module Title</label>
                                <input type="text" name="title" id="mod_title" class="m3-field">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Icon Class</label>
                                <input type="text" name="icon" id="mod_icon" class="m3-field" placeholder="bi bi-*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Onclick JS Action</label>
                                <input type="text" name="onclick" id="mod_onclick" class="m3-field">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Select Category</label>
                                <select name="category_id" id="mod_cat" class="m3-field form-select">
                                    <?php
                                    $rc = $conn->query("SELECT * FROM hub_categories ORDER BY sort_order");
                                    while ($r = $rc->fetch_assoc())
                                        echo "<option value='{$r['id']}'>{$r['name']}</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Module Status</label>
                                <select name="active" id="mod_active" class="m3-field form-select">
                                    <option value="1">Enabled</option>
                                    <option value="0">Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow">SAVE MODULE
                        SETTINGS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="permModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-m3 p-2">
            <form method="post" id="permForm">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Access Control</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <input type="hidden" name="module_id" id="perm_module_id">
                    <div class="m3-floating-group">
                        <label class="m3-floating-label">Target SCCODE (0 for All)</label>
                        <input type="number" name="sccode" class="m3-field" value="0">
                    </div>
                    <div class="m3-floating-group">
                        <label class="m3-floating-label">Roles (Multi-select)</label>
                        <select name="role[]" multiple class="form-control"
                            style="height:140px; border:2px solid var(--m3-outline-variant); border-radius:12px; padding:10px;">
                            <?php foreach ($roles_list as $r)
                                echo "<option value='$r' style='padding:5px;'>$r</option>"; ?>
                        </select>
                    </div>
                    <button type="button" id="permSaveBtn"
                        class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">SAVE PERMISSIONS</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- ==============================
     FOOTER
============================== -->
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    // ==========================
    // CATEGORY SORTING
    // ==========================

    new Sortable(document.querySelector("main"), {
        animation: 150,
        handle: ".cat-header",
        draggable: ".sortable-category",
        onEnd: function () {

            let order = [];

            document.querySelectorAll(".sortable-category").forEach((el, i) => {
                order.push({
                    id: el.dataset.id,
                    pos: i + 1
                });
            });

            fetch("ajax/update-cat-order.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(order)
            });
        }
    });

</script>

<script>
    // ==========================
    // MODULE SORTING
    // ==========================

    document.querySelectorAll(".sortable-modules").forEach(list => {

        new Sortable(list, {
            animation: 150,
            draggable: ".module-tile",
            onEnd: function () {

                let catId = list.dataset.category;

                let order = [];

                list.querySelectorAll(".module-tile").forEach((el, i) => {
                    order.push({
                        id: el.dataset.id,
                        pos: i + 1,
                        category: catId
                    });
                });

                fetch("ajax/update-module-order.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(order)
                });
            }
        });

    });

</script>


<script>

    document.addEventListener("DOMContentLoaded", function () {
        // Bootstrap modal instances
        window.catMdl = new bootstrap.Modal(document.getElementById('categoryModal'));
        window.modMdl = new bootstrap.Modal(document.getElementById('moduleModal'));
        window.perMdl = new bootstrap.Modal(document.getElementById('permModal'));

        // -----------------------------
        // OPEN MODALS
        // -----------------------------
        window.openCategoryModal = function () {
            catForm.reset();
            cat_id.value = "";
            catMdl.show();
        }

        window.editCategory = function (d) {
            cat_id.value = d.id;
            cat_name.value = d.name;
            cat_status.value = d.status;
            catMdl.show();
        }

        window.openModuleModalWithCat = function (catId) {
            moduleForm.reset();
            mod_cat.value = catId;
            modMdl.show();
        }

        window.editModule = function (d) {
            module_id.value = d.id;
            mod_title.value = d.title;
            mod_icon.value = d.icon;
            mod_onclick.value = d.onclick;
            mod_cat.value = d.category_id;
            mod_active.value = d.active;
            modMdl.show();
        }

        window.openPermModal = function (id) {
            perm_module_id.value = id;
            fetch("ajax/load_permi.php?load_perm=" + id)
                .then(res => res.json())
                .then(data => {
                    let sel = document.querySelector("#permForm select");
                    [...sel.options].forEach(o => {
                        o.selected = data.roles.includes(o.value);
                    });
                    document.querySelector("#permForm input[name=sccode]").value = data.sccode;
                    perMdl.show();
                });
        }

        // -----------------------------
        // DELETE FUNCTIONS
        // -----------------------------
        window.deleteCategory = function (id) {
            Swal.fire({
                title: 'Delete Category?',
                text: 'All modules under this category will be removed!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch("hub-admin.php", {
                        method: "POST",
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: "delete_cat=" + id
                    }).then(() => location.reload());
                }
            });
        }

        window.deleteModule = function (id) {
            Swal.fire({
                title: 'Delete Module?',
                text: 'This module will be permanently removed!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch("hub-admin.php", {
                        method: "POST",
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: "delete_mod=" + id
                    }).then(() => location.reload());
                }
            });
        }
    });




    const permSaveBtn = document.getElementById('permSaveBtn');
    if (!permSaveBtn.dataset.listener) {
        // permSaveBtn.removeEventListener('click', savePermissions);
        permSaveBtn.addEventListener('click', savePermissions);
    }

    function savePermissions() {
        console.log("savePermissions called");
        const form = document.getElementById('permForm');
        const formData = new FormData(form);
        const roles = [...form.querySelector('select').selectedOptions].map(o => o.value);
        formData.delete('role');
        roles.forEach(r => formData.append('role[]', r));


        fetch('ajax/save-hub-admin.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === 'ok') {
                    Swal.fire({ icon: 'success', title: 'Permissions Saved!', timer: 1000, showConfirmButton: false });
                    perMdl.hide();
                } else {
                    Swal.fire('Error', 'Could not save permissions', 'error');
                }
            })
            .catch(err => console.error(err));
    }




</script>