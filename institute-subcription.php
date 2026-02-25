<?php 
$page_title = "Subscription Hub"; 
include 'inc.php'; 

// ডাটা ফেচিং
$sql = "SELECT package_id, package_name, tier, expire, sms_balance, account_balance, 
               valid_module, active_module, valid_panel, active_panel, billing_data 
        FROM scinfo WHERE sccode = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sccode);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

// মডিউল লিস্ট ফেচিং (মডিউল টেবিল থেকে)
$modules_db = [];
$mod_res = $conn->query("SELECT module_name FROM modulelist ORDER BY module_name ASC");
while($m = $mod_res->fetch_assoc()) $modules_db[] = $m['module_name'];

// স্ট্রিং থেকে অ্যারেতে রূপান্তর (Helper Function)
function clean_explode($str) {
    return array_map('trim', explode('|', $str));
}

$valid_mods = clean_explode($row['valid_module']);
$active_mods = clean_explode($row['active_module']);
$valid_pans = clean_explode($row['valid_panel']);
$active_pans = clean_explode($row['active_panel']);
$billing = clean_explode($row['billing_data']);

// প্যানেল লিস্ট (আপনার দেয়া লিস্ট অনুযায়ী)
$panels_static = ['Administrator', 'Chief', 'Teacher', 'Accountant', 'Librarian', 'Guardian', 'Student', 'SMC'];
?>

<style>
    .m3-hero-sub { background: linear-gradient(180deg, #7B1FA2 0%, #4A148C 100%); padding: 50px 24px 80px; color: #fff; border-radius: 0 0 40px 40px; text-align: center; }
    .sub-container { margin-top: -60px; padding: 0 16px 50px; position: relative; z-index: 10; }
    .m3-sub-card { background: #fff; border-radius: 28px; padding: 24px; border: 1px solid #E7E0EC; margin-bottom: 16px; }
    .module-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; border-radius: 12px; margin-bottom: 6px; border: 1px solid transparent; transition: 0.2s; }
    .module-item.disabled { opacity: 0.4; background: #f0f0f0; pointer-events: none; }
    .module-item.enabled { background: #F3EDF7; border-color: #EADDFF; }
    .billing-pill { background: #EADDFF; color: #21005D; padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; font-weight: 800; display: inline-block; margin-right: 5px; margin-bottom: 5px; }
</style>

<main>
    <div class="m3-hero-sub shadow">
        <h3 class="fw-black mb-1">Service & Licensing</h3>
        <p class="small opacity-75 fw-bold mb-0">Subscription ID: #<?= $row['package_id'] ?> | Tier <?= $row['tier'] ?></p>
    </div>

    <div class="sub-container">
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary-container text-primary">
                    <small class="fw-bold opacity-75">ACCOUNT BALANCE</small>
                    <h4 class="fw-black m-0">৳<?= number_format($row['account_balance'], 2) ?></h4>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-success-subtle text-success">
                    <small class="fw-bold opacity-75">SMS CREDITS</small>
                    <h4 class="fw-black m-0"><?= number_format($row['sms_balance']) ?></h4>
                </div>
            </div>
        </div>

        <div class="m3-sub-card shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-black m-0 text-dark"><i class="bi bi-award-fill me-2"></i>Active Plan</h6>
                <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">Exp: <?= date('d M, Y', strtotime($row['expire'])) ?></span>
            </div>
            <h4 class="fw-black text-primary mb-3"><?= $row['package_name'] ?> Package</h4>
            
            <div class="billing-info">
                <?php if(count($billing) >= 5): ?>
                    <span class="billing-pill">Cycle: <?= $billing[0] ?></span>
                    <span class="billing-pill">Policy: <?= $billing[1] ?></span>
                    <span class="billing-pill">Slot: <?= $billing[2] ?></span>
                    <span class="billing-pill">Rate: <?= $billing[3] ?></span>
                    <span class="billing-pill">Amount: <?= $billing[4] ?></span>
                <?php endif; ?>
            </div>
            <button class="btn btn-m3-tonal w-100 mt-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#billingModal">Update Billing Info</button>
        </div>

        <div class="m3-section-title px-2">Software Modules</div>
        <div class="m3-sub-card shadow-sm">
            <p class="small text-muted mb-3 fw-bold"><i class="bi bi-info-circle me-1"></i> Checked modules are currently active. Disabled modules are not in your license.</p>
            <div class="module-list">
                <?php foreach($modules_db as $mod): 
                    $is_valid = in_array($mod, $valid_mods);
                    $is_active = in_array($mod, $active_mods);
                ?>
                <div class="module-item <?= $is_valid ? 'enabled' : 'disabled' ?>">
                    <span class="fw-bold small"><?= $mod ?></span>
                    <?php if($is_valid): ?>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input mod-toggle" type="checkbox" value="<?= $mod ?>" <?= $is_active ? 'checked' : '' ?>>
                        </div>
                    <?php else: ?>
                        <i class="bi bi-lock-fill text-muted"></i>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-primary w-100 rounded-pill py-2 mt-3 fw-black shadow-sm" onclick="savePermissions('active_module', 'mod-toggle')">SYNC MODULES</button>
        </div>

        <div class="m3-section-title px-2">Access Panels</div>
        <div class="m3-sub-card shadow-sm">
            <div class="row g-2">
                <?php foreach($panels_static as $pan): 
                    $is_valid = in_array($pan, $valid_pans);
                    $is_active = in_array($pan, $active_pans);
                ?>
                <div class="col-6">
                    <div class="module-item <?= $is_valid ? 'enabled' : 'disabled' ?>">
                        <span class="fw-bold small" style="font-size: 11px;"><?= strtoupper($pan) ?></span>
                        <input class="form-check-input pan-toggle" type="checkbox" value="<?= $pan ?>" <?= ($is_valid && $is_active) ? 'checked' : '' ?>>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-dark w-100 rounded-pill py-2 mt-2 fw-black" onclick="savePermissions('active_panel', 'pan-toggle')">SYNC PANELS</button>
        </div>
    </div>
</main>

<div class="modal fade" id="billingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <form id="billingForm" class="modal-body p-4">
                <h5 class="fw-black mb-4">Edit Billing & Package</h5>
                <div class="m3-input-box mb-3"><label>PACKAGE NAME</label><input type="text" name="package_name" value="<?= $row['package_name'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-3"><label>BILLING DATA (Cycle|Policy|Slot|Rate|Amount)</label>
                <input type="text" name="billing_data" value="<?= $row['billing_data'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-4"><label>EXPIRY DATE</label><input type="date" name="expire" value="<?= date('Y-m-d', strtotime($row['expire'])) ?>" class="m3-clean-input"></div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-black shadow">UPDATE BILLING</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script>
// ১. মডিউল এবং প্যানেল পারমিশন সেভ লজিক
function savePermissions(field, className) {
    let selected = [];
    $(`.${className}:checked`).each(function() { selected.push($(this).val()); });
    let valStr = selected.join(' | ');

    $.ajax({
        url: 'backend/save-institute-info.php',
        type: 'POST',
        data: { [field]: valStr },
        success: function(res) {
            Swal.fire({ icon: 'success', title: 'Permissions Synced', timer: 1000, showConfirmButton: false });
        }
    });
}

// ২. বিলিং ফর্ম সেভ লজিক
$('#billingForm').submit(function(e) {
    e.preventDefault();
    $.post('backend/save-institute-info.php', $(this).serialize(), function(res) {
        if(res.status == 'success') location.reload();
    }, 'json');
});
</script>