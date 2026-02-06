<?php
$page_title = "School Permission Manager";
include 'inc.php';


// ২.২ সেভ লজিক (Institutional & User Overrides)
if (isset($_POST['save_custom_perm'])) {
    $page = mysqli_real_escape_string($conn, $_POST['page_name']);
    $target_type = $_POST['target_type'];

    if ($target_type == 'role') {
        $roles_perms = $_POST['perm']; // [role_name => level]

        foreach ($roles_perms as $role => $submitted_val) {
            $role = mysqli_real_escape_string($conn, $role);
            $val = intval($submitted_val);

            // কাস্টম এবং গ্লোবাল পারমিশন চেক করি
            $check_custom = $conn->query("SELECT id FROM permission_map_app WHERE page_name='$page' AND sccode='$sccode' AND userlevel='$role' AND email IS NULL");

            // গ্লোবাল ভ্যালু কত ছিল তা বের করি (sccode=0 থেকে)
            $check_global = $conn->query("SELECT permission FROM permission_map_app WHERE page_name='$page' AND sccode='0' AND userlevel='$role' LIMIT 1");
            $global_val = ($check_global->num_rows > 0) ? intval($check_global->fetch_assoc()['permission']) : 0;

            if ($check_custom->num_rows > 0) {
                // ১. যদি কাস্টম রেকর্ড আগে থেকেই থাকে, তবে সেটি আপডেট হবে
                $conn->query("UPDATE permission_map_app SET permission='$val', updatedby='$usr', modifieedate='$cur' 
                              WHERE page_name='$page' AND sccode='$sccode' AND userlevel='$role' AND email IS NULL");
            } else {
                // ২. যদি কাস্টম রেকর্ড না থাকে, তবে তখনই ইনসার্ট হবে যদি সাবমিট করা ভ্যালু গ্লোবাল থেকে আলাদা হয়
                if ($val !== $global_val) {
                    $conn->query("INSERT INTO permission_map_app (page_name, module, root_page, sccode, userlevel, permission, updatedby, modifieedate) 
                                  SELECT page_name, module, root_page, '$sccode', '$role', '$val', '$usr', '$cur' 
                                  FROM permission_map_app WHERE page_name='$page' AND sccode='0' AND userlevel='$role' LIMIT 1");
                }
            }
        }
    } elseif ($target_type == 'user') {
        $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
        $user_perm = intval($_POST['user_permission']);

        if (!empty($user_email)) {
            $check = $conn->query("SELECT id FROM permission_map_app WHERE page_name='$page' AND sccode='$sccode' AND email='$user_email'");
            if ($check->num_rows > 0) {
                $conn->query("UPDATE permission_map_app SET permission='$user_perm', updatedby='$usr', modifieedate='$cur' 
                              WHERE page_name='$page' AND sccode='$sccode' AND email='$user_email'");
            } else {
                $conn->query("INSERT INTO permission_map_app (page_name, module, root_page, sccode, email, permission, updatedby, modifieedate) 
                              SELECT page_name, module, root_page, '$sccode', '$user_email', '$user_perm', '$usr', '$cur' 
                              FROM permission_map_app WHERE page_name='$page' AND sccode='0' LIMIT 1");
            }
        }
    }
    // header("Location: permission_manager.php"); 
}


// ২.১ রিসেট লজিক (Custom record delete to fallback to Global)
if (isset($_POST['reset_action'])) {
    $page = mysqli_real_escape_string($conn, $_POST['page_name']);
    $role = mysqli_real_escape_string($conn, $_POST['role_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');

    $where = "page_name='$page' AND sccode='$sccode'";
    if (!empty($email)) {
        $where .= " AND email='$email'";
    } else {
        $where .= " AND userlevel='$role' AND email IS NULL";
    }

    $conn->query("DELETE FROM permission_map_app WHERE $where");
    // header("Location: permission_manager.php"); exit();
}

// ২.২ সেভ লজিক (Institutional & User Overrides)
// ২.২ সেভ লজিক (Institutional & User Overrides)
if (isset($_POST['save_custom_perm'])) {
    $page = mysqli_real_escape_string($conn, $_POST['page_name']);
    $target_type = $_POST['target_type'];

    if ($target_type == 'role') {
        $roles_perms = $_POST['perm'];
        foreach ($roles_perms as $role => $submitted_val) {
            $role = mysqli_real_escape_string($conn, $role);
            $val = intval($submitted_val);

            // কাস্টম এবং গ্লোবাল পারমিশন ভ্যালু চেক করি
            $check_custom = $conn->query("SELECT id FROM permission_map_app WHERE page_name='$page' AND sccode='$sccode' AND userlevel='$role' AND email IS NULL");

            // গ্লোবাল ভ্যালু কত ছিল তা বের করি
            $check_global = $conn->query("SELECT permission FROM permission_map_app WHERE page_name='$page' AND sccode='0' AND userlevel='$role' LIMIT 1");
            $global_val = ($check_global->num_rows > 0) ? intval($check_global->fetch_assoc()['permission']) : 0;

            if ($check_custom->num_rows > 0) {
                // ১. যদি কাস্টম রেকর্ড থাকে, তবে সরাসরি আপডেট হবে
                $conn->query("UPDATE permission_map_app SET permission='$val', updatedby='$usr', modifieedate='$cur' WHERE page_name='$page' AND sccode='$sccode' AND userlevel='$role'");
            } else {
                // ২. যদি কাস্টম রেকর্ড না থাকে, তবে তখনই ইনসার্ট হবে যদি সাবমিট করা ভ্যালু গ্লোবাল থেকে আলাদা হয়
                if ($val !== $global_val) {
                    $conn->query("INSERT INTO permission_map_app (page_name, module, root_page, sccode, userlevel, permission, updatedby, modifieedate) 
                                  SELECT page_name, module, root_page, '$sccode', '$role', '$val', '$usr', '$cur' 
                                  FROM permission_map_app WHERE page_name='$page' AND sccode='0' AND userlevel='$role' LIMIT 1");
                }
            }
        }
    }
    // ... বাকি ইউজার লজিক একই থাকবে ...
    // header("Location: permission_manager.php"); exit();
}

/**
 * ৩. ডাটা ফেচিং
 */
// ৩.১ ইউজার লিস্ট
$users_list = [];
$u_res = $conn->query("SELECT u.email, u.userid, IFNULL(t.tname, u.email) as display_name 
                       FROM usersapp u LEFT JOIN teacher t ON u.userid = t.tid 
                       WHERE u.sccode='$sccode' ORDER BY display_name ASC");
while ($u = $u_res->fetch_assoc())
    $users_list[] = $u;

// ৩.২ রোল লিস্ট
$role_sql = $conn->query("SELECT userlevel FROM rolemanager WHERE sccode='0' OR sccode='$sccode' ORDER BY id ASC");
$roles = [];
while ($r = $role_sql->fetch_assoc())
    $roles[] = trim($r['userlevel']);

// ৩.৩ মডিউল ভিত্তিক পেজ লিস্ট (Global sccode=0)
$pages_by_module = [];
$res = $conn->query("SELECT * FROM permission_map_app WHERE sccode='0' ORDER BY module, page_name");
while ($row = $res->fetch_assoc()) {
    if (in_array($row['module'], $valid_modules)) {
        $pages_by_module[$row['module']][$row['page_name']]['global'] = $row;
    }
}

// ৩.৪ কাস্টম ওভাররাইড ডাটা (Institutional)
$custom_res = $conn->query("SELECT * FROM permission_map_app WHERE sccode='$sccode'");
$custom_perms = [];
while ($c = $custom_res->fetch_assoc()) {
    $key = $c['email'] ? 'user_' . $c['email'] : 'role_' . $c['userlevel'];
    $custom_perms[$c['page_name']][$key] = $c['permission'];
}


// গ্লোবাল পারমিশন লোড (সিস্টেম ডিফল্ট ভ্যালুগুলো একটি অ্যারেতে রাখা)
$global_role_perms = [];
$g_res = $conn->query("SELECT page_name, userlevel, permission FROM permission_map_app WHERE sccode='0'");
while ($gr = $g_res->fetch_assoc()) {
    $global_role_perms[$gr['page_name']][$gr['userlevel']] = $gr['permission'];
}
?>

<style>
    /* M3 Theme Overrides */
    .module-block {
        background: #fff;
        border-radius: 8px;
        margin-bottom: 24px;
        padding: 16px;
        padding-right: 0;
        border: 1px solid #eee;
    }

    .module-header {
        font-size: 0.75rem;
        font-weight: 900;
        color: var(--m3-primary);
        text-transform: uppercase;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .module-header::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--m3-tonal-container);
    }

    .page-card {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 8px;
        border: 1px solid rgba(0, 0, 0, 0.04);
        cursor: pointer;
        transition: 0.2s;
    }

    .page-card:active {
        transform: scale(0.98);
        background: #f5f5f5;
    }

    .page-card.disabled {
        opacity: 0.5;
        filter: grayscale(1);
        pointer-events: none;
        background: #fafafa;
    }

    .perm-pill {
        font-size: 0.6rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 6px;
        background: var(--m3-tonal-surface);
        color: #666;
    }

    .custom-badge {
        background: #E8F5E9;
        color: #2E7D32;
        border: 1px solid #C8E6C9;
    }

    /* Hero Module Pills */
    .module-pill-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 15px;
    }

    .m3-pill {
        font-size: 0.65rem;
        font-weight: 800;
        padding: 6px 12px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        border: 1px solid transparent;
    }

    .pill-active {
        background: #EADDFF;
        color: #21005D;
    }

    .pill-inactive {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.3);
        opacity: 0.7;
    }

    .pill-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .dot-active {
        background: #4CAF50;
        box-shadow: 0 0 5px #4CAF50;
    }

    .dot-inactive {
        background: #FFB4AB;
    }

    /* Modal Fixes */
    .m3-modal-dialog {
        max-height: 85vh;
        margin-top: 5vh;
    }

    .m3-modal-content {
        height: 85vh;
        border-radius: 12px !important;
        display: flex;
        flex-direction: column;
        border: none;
    }

    .m3-modal-body {
        overflow-y: auto;
        flex: 1;
        padding: 20px;
    }

    .reset-btn {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #F9DEDC;
        color: #B3261E;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        visibility: hidden;
        transition: 0.2s;
    }

    .reset-btn.visible {
        visibility: visible;
    }

    .reset-btn:hover {
        background: #F2B8B5;
    }

    .user-override-item {
        background: #fcfaff;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 8px;
        border: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* পারমিশন ভিত্তিক হালকা ব্যাকগ্রাউন্ড কালার */
    .bg-p0 {
        background-color: #f3d2d5 !important;
        border-color: #fc8d87 !important;
    }

    /* লাল - No Access */
    .bg-p1 {
        background-color: #f1e3b3 !important;
        border-color: #f0b863 !important;
    }

    /* অরেঞ্জ - Read Only */
    .bg-p2 {
        background-color: #E3F2FD !important;
        border-color: #90CAF9 !important;
    }

    /* নীল - Partial */
    .bg-p3 {
        background-color: #E8F5E9 !important;
        border-color: #83d486 !important;
    }

    /* সবুজ - Full Access */
</style>

<main>
    <div class="hero-container shadow-lg" style="padding-bottom: 30px; ">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;">Permission Manager</div>
                <div style="font-size: 0.8rem; opacity: 0.85;">Setting custom access for Institute:
                    <b><?php echo $sccode; ?></b>
                </div>
            </div>
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.15); border: none; color: #fff;"
                onclick="location.reload()">
                <i class="bi bi-arrow-repeat"></i>
            </div>
        </div>

        <div class="module-pill-container">
            <?php foreach ($valid_modules as $mod):
                $is_active = in_array($mod, $active_modules);
                $pill_class = $is_active ? 'pill-active' : 'pill-inactive';
                $dot_class = $is_active ? 'dot-active' : 'dot-inactive';
                ?>
                <div class="m3-pill <?php echo $pill_class; ?>">
                    <div class="pill-dot <?php echo $dot_class; ?>"></div>
                    <?php echo $mod; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="widget-grid" style="padding: 20px 12px 100px;">
        <?php foreach ($pages_by_module as $module => $pages):
            $is_active = in_array($module, $active_modules);
            ?>
            <div class="module-block shadow-sm">
                <div class="module-header"><i class="bi bi-box-seam-fill"></i> <?php echo $module; ?></div>
                <?php foreach ($pages as $page_name => $p_data):
                    $overrides = $custom_perms[$page_name] ?? [];
                    $globals = $global_role_perms[$page_name] ?? []; // এই লাইনটি নতুন
                    ?>
                    <div class="m3-list-item page-card shadow-sm <?php echo !$is_active ? 'disabled' : ''; ?>"
                        style="margin-right:12px;;"
                        onclick='openManagerModal("<?php echo $page_name; ?>", <?php echo json_encode($overrides); ?>, <?php echo json_encode($globals); ?>)'>
                        <div class="d-flex align-items-center w-100">
                            <div class="icon-box <?php echo $is_active ? 'c-inst' : 'c-util'; ?>"
                                style="width:38px; height:38px; font-size:1rem;">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark" style="font-size:0.9rem;">
                                    <?php echo $p_data['global']['page_title']; ?>
                                </div>
                             
                      
                                     <div class="flex-grow-1 text-muted" style="font-size:0.65rem;"><?php echo $page_name; ?></div>
                                <div class="d-flex gap-1 mt-1 text-right">
                                    <span class="perm-pill">Root: <?php echo $p_data['global']['root_page']; ?></span>
                                    <?php if (!empty($overrides))
                                        echo '<span class="perm-pill custom-badge">customized</span>'; ?>
                                </div>
                        
                               
                            </div>
                            <i class="bi bi-chevron-right opacity-25"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>



<div class="modal fade" id="managerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered m3-modal-dialog" style="margin:auto; width:92%;">
        <div class="modal-content m3-modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="fw-bold"><i class="bi bi-sliders2-vertical me-2"></i>Access Controller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="m3-modal-body">
                <div class="m3-section-title">Institutional Role Permissions</div>
                <form method="post" id="roleForm">
                    <input type="hidden" name="page_name" id="modal_page_name">
                    <input type="hidden" name="target_type" value="role">

                    <?php foreach ($roles as $role):
                        $safe_role_id = str_replace([' ', '@', '.'], '_', $role);
                        ?>
                        <div class="user-override-item shadow-sm">
                            <div class="fw-bold small"><?php echo strtoupper($role); ?></div>
                            <div class="d-flex align-items-center gap-2">
                                <select name="perm[<?php echo $role; ?>]" id="role_perm_<?php echo $safe_role_id; ?>"
                                    class="form-select-sm border-0 bg-transparent fw-bold text-primary"
                                    style="outline:none;">
                                    <option value="0">No Access</option>
                                    <option value="1">Read Only</option>
                                    <option value="2">Partial</option>
                                    <option value="3">Full Access</option>
                                </select>
                                <button type="button" class="reset-btn" id="reset_btn_<?php echo $safe_role_id; ?>"
                                    onclick="resetPermission('<?php echo $role; ?>')" title="Restore to Default">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="save_custom_perm" class="btn btn-primary w-100 py-2 mt-2 mb-4"
                        style="border-radius:12px; font-weight:800;">UPDATE ALL ROLES</button>
                </form>

                <hr class="opacity-10">

                <div id="user_overrides_section" style="display:none;">
                    <div class="m3-section-title mt-3">Active User Overrides</div>
                    <div id="user_list_container" class="mb-4"></div>
                </div>

                <div class="m3-section-title mt-4">Add User-Specific Override</div>
                <form method="post" class="p-3 border rounded-4 bg-light">
                    <input type="hidden" name="page_name" id="modal_page_name_user">
                    <input type="hidden" name="target_type" value="user">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Select Employee/User</label>
                        <select name="user_email" class="form-select" required>
                            <option value="">-- Choose User --</option>
                            <?php foreach ($users_list as $user): ?>
                                <option value="<?php echo $user['email']; ?>"><?php echo $user['display_name']; ?>
                                    (<?php echo $user['email']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Permission Level</label>
                        <select name="user_permission" class="form-select">
                            <option value="3">Full Access</option>
                            <option value="2">Partial</option>
                            <option value="1">Read Only</option>
                            <option value="0">Revoke Access</option>
                        </select>
                    </div>
                    <button type="submit" name="save_custom_perm" class="btn btn-dark w-100 py-2"
                        style="border-radius:12px; font-weight:800;">APPLY FOR USER</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    // মডাল ইনিশিয়ালাইজেশন
    let mModal;
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('managerModal');
        if (modalEl) mModal = new bootstrap.Modal(modalEl);
    });

    /**
     * মডাল ওপেন এবং ডাটা পপুলেট ফাংশন
     */

    function updateRowColor(selectEl) {
        const val = selectEl.value;
        const rowEl = selectEl.closest('.user-override-item');
        if (!rowEl) return;

        // আগের সব ক্লাস রিমুভ করে নতুন পারমিশন ক্লাস যোগ করা
        rowEl.classList.remove('bg-p0', 'bg-p1', 'bg-p2', 'bg-p3');
        rowEl.classList.add('bg-p' + val);
    }

    function openManagerModal(pageName, overrides, globals) {
        document.getElementById('modal_page_name').value = pageName;
        const roles = <?php echo json_encode($roles); ?>;

        roles.forEach(role => {
            const safeId = role.replace(/[\s@.]/g, '_');
            const selectEl = document.getElementById('role_perm_' + safeId);
            const resetBtn = document.getElementById('reset_btn_' + safeId);

            if (selectEl) {
                const customVal = overrides['role_' + role];
                const globalVal = globals[role];

                if (customVal !== undefined) {
                    selectEl.value = customVal;
                    if (resetBtn) resetBtn.classList.add('visible');
                    selectEl.style.color = 'var(--m3-primary)';
                    selectEl.style.fontWeight = '900';
                } else {
                    selectEl.value = (globalVal !== undefined) ? globalVal : 0;
                    if (resetBtn) resetBtn.classList.remove('visible');
                    selectEl.style.color = '#666';
                    selectEl.style.fontWeight = '400';
                }

                // মডাল খোলার সময় কালার সেট করা
                updateRowColor(selectEl);

                // ড্রপডাউন পরিবর্তন করলে সাথে সাথে কালার চেঞ্জ হওয়া
                selectEl.onchange = function () {
                    updateRowColor(this);
                };
            }
        });

        renderUserOverrides(overrides);
        mModal.show();
    }
    /**
     * বিদ্যমান ইউজার পারমিশন লিস্ট তৈরি
     */
    function renderUserOverrides(overrides) {
        const container = document.getElementById('user_list_container');
        const section = document.getElementById('user_overrides_section');
        if (!container || !section) return;

        container.innerHTML = '';
        let count = 0;
        const labels = { 0: 'Revoked', 1: 'Read', 2: 'Partial', 3: 'Full' };

        for (const key in overrides) {
            if (key.startsWith('user_')) {
                count++;
                const email = key.replace('user_', '');
                const perm = overrides[key];
                container.innerHTML += `
                    <div class="user-override-item">
                        <div>
                            <div class="small fw-bold text-truncate" style="max-width:200px;">${email}</div>
                            <div class="badge bg-white text-primary border" style="font-size:0.6rem;">${labels[perm]}</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="resetPermission('', '${email}')">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>`;
            }
        }
        section.style.display = count > 0 ? 'block' : 'none';
    }

    /**
     * রিসেট অ্যাকশন (Role/User)
     */
    function resetPermission(role, email = '') {
        const page = document.getElementById('modal_page_name').value;
        const text = email ? `Remove custom access for ${email}?` : `Reset ${role} to default settings?`;

        Swal.fire({
            title: 'Confirm Reset',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Reset'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="reset_action" value="1">
                    <input type="hidden" name="page_name" value="${page}">
                    <input type="hidden" name="role_name" value="${role}">
                    <input type="hidden" name="email" value="${email}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>