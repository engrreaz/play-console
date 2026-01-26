<?php
$page_title = "Advanced Permission Mapper";
include 'inc.php';

/**
 * ১. রোল এবং মডিউল লিস্ট লোড করা
 */
$roles_list = [];
$role_res = $conn->query("SELECT userlevel FROM rolemanager WHERE sccode='0' ORDER BY id ASC");
while($r = $role_res->fetch_assoc()) $roles_list[] = $r['userlevel'];

$modules_list = [];
$mod_res = $conn->query("SELECT module_name FROM modulelist ORDER BY slno ASC");
while($m = $mod_res->fetch_assoc()) $modules_list[] = $m['module_name'];

/**
 * ২. সেভ/আপডেট লজিক (সব রোলের জন্য একসাথে)
 */
if (isset($_POST['save_all_permissions'])) {
    $page = mysqli_real_escape_string($conn, $_POST['page_name']);
    $module = mysqli_real_escape_string($conn, $_POST['module']);
    $root = mysqli_real_escape_string($conn, $_POST['root_page']);
    $permissions_array = $_POST['perm']; // এটি একটি অ্যারে [role => level]

    foreach ($permissions_array as $role => $perm_val) {
        $role = mysqli_real_escape_string($conn, $role);
        $perm_val = intval($perm_val);

        $check = $conn->query("SELECT id FROM permission_map_app WHERE page_name='$page' AND userlevel='$role' AND sccode='0'");
        
        if ($check->num_rows > 0) {
            $conn->query("UPDATE permission_map_app SET module='$module', root_page='$root', permission='$perm_val', updatedby='$usr', modifieedate='$cur' 
                          WHERE page_name='$page' AND userlevel='$role' AND sccode='0'");
        } else {
            $conn->query("INSERT INTO permission_map_app (page_name, module, root_page, sccode, userlevel, permission, updatedby, modifieedate) 
                          VALUES ('$page', '$module', '$root', '0', '$role', '$perm_val', '$usr', '$cur')");
        }
    }
    // header("Location: permission-mapper.php?target_level=" . $_GET['target_level']); exit();
}

/**
 * ৩. ফাইল স্ক্যানিং
 */
$target_level = $_GET['target_level'] ?? ($roles_list[0] ?? 'Administrator');
$php_files = glob("*.php");
$exclude = ['inc.php', 'footer.php', 'header.php', 'db.php', 'config.php', 'permission-mapper.php'];

// বর্তমান লিস্ট ভিউর জন্য ম্যাপ লোড
$mapped_permissions = [];
$all_mapped_raw = $conn->query("SELECT * FROM permission_map_app WHERE sccode='0'");
while($row = $all_mapped_raw->fetch_assoc()) {
    $mapped_permissions[$row['page_name']][$row['userlevel']] = $row;
}

$unassigned_count = 0;
foreach ($php_files as $file) {
    if (!in_array($file, $exclude) && !isset($mapped_permissions[$file])) {
        $unassigned_count++;
    }
}
?>

<style>
    /* মডাল কাস্টমাইজেশন */
    .m3-modal-dialog { max-height: 75vh; }
    .m3-modal-content { 
        height: 75vh; 
        border-radius: 8px !important; 
        display: flex; 
        flex-direction: column; 
        border: none;
    }
    .m3-modal-body { 
        overflow-y: auto; 
        flex: 1; 
        padding: 20px; 
        scrollbar-width: thin;
    }
    
    /* রোল সিলেকশন রো */
    .role-row {
        background: var(--m3-tonal-surface);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .perm-card { padding: 12px 16px; margin-bottom: 10px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.04); }
    .perm-card.unassigned { border: 1.5px dashed #B3261E; background: #FFF8F7; }
    .p-badge { font-size: 0.6rem; font-weight: 800; padding: 3px 12px; border-radius: 12px; text-transform: uppercase; background: #d4fad7; color: #079610; }
    .p-3 { background: #E8F5E9; color: #2E7D32; }
    .p-0 { background: #FFEBEE; color: #C62828; }
</style>

<main>
    <div class="hero-container" style="padding-bottom: 35px; ">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <div style="font-size: 1.4rem; font-weight: 900;">Permission Mapper</div>
            </div>
            <div class="text-end">
                <div style="font-size: 1.5rem; font-weight: 900; color:#FFD8D6;"><?php echo $unassigned_count; ?></div>
                <div style="font-size: 0.6rem; font-weight: 800; opacity: 0.8;">UNASSIGNED</div>
            </div>
        </div>
    </div>

    <div class="widget-grid" style="padding: 15px 0 100px;">
        <?php foreach ($php_files as $file): 
            if (in_array($file, $exclude)) continue;
            $file_data = $mapped_permissions[$file] ?? [];
            $is_unassigned = empty($file_data);
            
            // মডালের জন্য সব রোলের পারমিশন ডাটা একটা JSON হিসেবে রাখা
            $perm_json = [];
            foreach($roles_list as $r) { $perm_json[$r] = $file_data[$r]['permission'] ?? 0; }
            $current_module = reset($file_data)['module'] ?? '';
            $current_root = reset($file_data)['root_page'] ?? '';
        ?>
            <div class="m3-list-item perm-card shadow-sm <?php echo $is_unassigned ? 'unassigned' : ''; ?>" 
                 onclick='openPermModal("<?php echo $file; ?>", "<?php echo $current_module; ?>", "<?php echo $current_root; ?>", <?php echo json_encode($perm_json); ?>)'>
                <div class="d-flex align-items-center gap-3 w-100">
                    <div class="icon-box <?php echo $is_unassigned ? 'c-exit' : 'c-inst'; ?>" style="width:40px; height:40px;"><i class="bi bi-file-code"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-bold" style="font-size:0.9rem;"><?php echo $file; ?></div>
                        <div class="small opacity-75"><?php echo $current_module ?: 'No module assigned'; ?></div>
                    </div>
                    <?php if(!$is_unassigned): ?>
                        <span class="p-badge">Mapped</span>
                    <?php else: ?>
                        <i class="bi bi-exclamation-circle-fill text-danger"></i>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>



<div class="modal fade" id="permModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered m3-modal-dialog">
        <div class="modal-content m3-modal-content shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold" style="color: var(--m3-primary);"><i class="bi bi-shield-lock me-2"></i>Access Control</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="post" style="display:contents;">
                <div class="m3-modal-body">
                    <div class="m3-floating-group">
                        <i class="bi bi-file-earmark m3-field-icon"></i>
                        <input type="text" name="page_name" id="m_page" class="m3-input-floating bg-light" readonly>
                        <label class="m3-floating-label">FILE NAME</label>
                    </div>

                    <div class="m3-floating-group">
                        <i class="bi bi-collection m3-field-icon"></i>
                        <select name="module" id="m_module" class="m3-select-floating" required>
                            <option value=""></option>
                            <?php foreach($modules_list as $mod): ?>
                                <option value="<?php echo $mod; ?>"><?php echo $mod; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label class="m3-floating-label">ASSIGN MODULE</label>
                    </div>

                    <div class="m3-floating-group">
                        <i class="bi bi-house m3-field-icon"></i>
                        <input type="text" name="root_page" id="m_root" class="m3-input-floating" placeholder=" ">
                        <label class="m3-floating-label">ROOT PAGE</label>
                    </div>

                    <div class="m3-section-title mb-3 mt-4" style="font-size:0.75rem;">Role-based Permissions</div>

                    <?php foreach($roles_list as $role): ?>
                        <div class="role-row">
                            <div class="fw-bold small"><?php echo strtoupper($role); ?></div>
                            <select name="perm[<?php echo $role; ?>]" id="perm_<?php echo str_replace(' ', '_', $role); ?>" 
                                    style="border:none; background:transparent; font-size:0.8rem; font-weight:700; color:var(--m3-primary); outline:none; width:140px;">
                                <option value="3">Full Access</option>
                                <option value="2">Partial</option>
                                <option value="1">Read Only</option>
                                <option value="0">No Access</option>
                            </select>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light flex-fill py-2" style="border-radius:12px; font-weight:700;" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" name="save_all_permissions" class="btn btn-primary flex-fill py-2" style="border-radius:12px; font-weight:700;">SAVE ALL ROLES</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    const pModal = new bootstrap.Modal('#permModal');

    function openPermModal(file, module, root, permsJson) {
        document.getElementById('m_page').value = file;
        document.getElementById('m_module').value = module;
        document.getElementById('m_root').value = root || 'index.php';

        // প্রতিটি রোলের পারমিশন সেট করা
        for (const role in permsJson) {
            const selectId = "perm_" + role.replace(/\s+/g, '_');
            const selectEl = document.getElementById(selectId);
            if(selectEl) selectEl.value = permsJson[role];
        }
        
        pModal.show();
    }
</script>

