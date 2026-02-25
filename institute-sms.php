<?php 
$page_title = "Messaging Hub"; 
include 'inc.php'; 

// ডাটা ফেচিং
$sql = "SELECT sms_gateway, sms_in, sms_out, sms_absent, sms_payment, sms_dues, sms_month_report FROM scinfo WHERE sccode = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sccode);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

// JSON Decoding Helper
function get_json($data) {
    return json_decode($data ?? '{}', true);
}

$gw = get_json($row['sms_gateway']);
$s_in = get_json($row['sms_in']);
// বাকিগুলো একইভাবে ডিকোড করা যাবে...
?>

<style>
    .m3-hero-sms { background: linear-gradient(180deg, #2E7D32 0%, #1B5E20 100%); padding: 50px 24px 80px; color: #fff; border-radius: 0 0 40px 40px; text-align: center; }
    .sms-container { margin-top: -60px; padding: 0 16px 50px; position: relative; z-index: 10; }
    .m3-config-card { background: #fff; border-radius: 28px; padding: 24px; border: 1px solid #E7E0EC; margin-bottom: 16px; }
    .status-badge { font-size: 0.65rem; font-weight: 800; padding: 4px 12px; border-radius: 100px; }
    .code-view { background: #1C1B1F; color: #81C784; padding: 12px; border-radius: 12px; font-family: 'monospace'; font-size: 0.75rem; word-break: break-all; }
</style>

<main>
    <div class="m3-hero-sms shadow">
        <h3 class="fw-black mb-1">Messaging Gateways</h3>
        <p class="small opacity-75 fw-bold mb-0">API Configuration & Automation Templates</p>
    </div>

    <div class="sms-container">
        <div class="m3-config-card shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-black m-0 text-success uppercase">Gateway Configuration</h6>
                <span class="status-badge <?= ($gw['sms_api'] ?? 0) ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                    <?= ($gw['sms_api'] ?? 0) ? 'ACTIVE' : 'DISABLED' ?>
                </span>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-6 small fw-bold text-muted">API KEY: <span class="text-dark"><?= $gw['api_key'] ?? '---' ?></span></div>
                <div class="col-6 small fw-bold text-muted">USER: <span class="text-dark"><?= $gw['username'] ?? '---' ?></span></div>
            </div>
            <div class="code-view mb-3"><?= $gw['uri'] ?? 'URL not set' ?></div>
            <button class="btn btn-m3-tonal w-100 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#gatewayModal">Re-configure API</button>
        </div>

        <div class="m3-section-title px-2">Automation Templates</div>
        
        <div class="m3-config-card shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-in-right text-success fs-5"></i>
                    <h6 class="fw-black m-0">Student Entry (In-Time)</h6>
                </div>
                <span class="status-badge <?= ($s_in['sms_in'] ?? 0) ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-muted' ?>">
                    <?= ($s_in['sms_in'] ?? 0) ? 'ON' : 'OFF' ?>
                </span>
            </div>
            <div class="p-3 bg-light rounded-4 border mb-3 small italic fw-bold text-muted">
                "<?= $s_in['sms_in_text'] ?? 'Template content not defined.' ?>"
            </div>
            <div class="d-flex gap-2">
                <span class="m3-chip">P1: <?= $s_in['sms_in_priority_1'] ?? 'None' ?></span>
                <span class="m3-chip">Time: <?= $s_in['sms_in_fixed_time'] ?? 'N/A' ?></span>
            </div>
            <button class="btn btn-outline-success w-100 mt-3 rounded-pill fw-bold border-2" data-bs-toggle="modal" data-bs-target="#inModal">Edit Template</button>
        </div>

        </div>
</main>

<div class="modal fade" id="gatewayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <form id="gwForm" class="modal-body p-4">
                <h5 class="fw-black mb-4 text-success">Gateway API Settings</h5>
                
                <div class="form-check form-switch mb-3 p-0 d-flex justify-content-between align-items-center bg-light p-3 rounded-4">
                    <label class="fw-bold m-0">Enable SMS API</label>
                    <input class="form-check-input ms-0" type="checkbox" name="sms_api" value="1" <?= ($gw['sms_api'] ?? 0) ? 'checked' : '' ?>>
                </div>

                <div class="m3-input-box mb-3"><label>API KEY</label><input type="text" name="api_key" value="<?= $gw['api_key'] ?? '' ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-3"><label>SECRET KEY</label><input type="text" name="secret_key" value="<?= $gw['secret_key'] ?? '' ?>" class="m3-clean-input"></div>
                
                <div class="row g-2">
                    <div class="col-6"><div class="m3-input-box mb-3"><label>USERNAME</label><input type="text" name="username" value="<?= $gw['username'] ?? '' ?>" class="m3-clean-input"></div></div>
                    <div class="col-6"><div class="m3-input-box mb-3"><label>PASSWORD</label><input type="text" name="password" value="<?= $gw['password'] ?? '' ?>" class="m3-clean-input"></div></div>
                </div>

                <div class="m3-input-box mb-4">
                    <label>REQUEST URL (URI)</label>
                    <textarea name="uri" class="m3-clean-input w-100" rows="3"><?= $gw['uri'] ?? '' ?></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-black shadow">SAVE API CONFIG</button>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="inModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <form id="inForm" class="modal-body p-4">
                <h5 class="fw-black mb-4 text-success">In-Time Configuration</h5>
                
                <div class="form-check form-switch mb-3 bg-light p-3 rounded-4 d-flex justify-content-between align-items-center">
                    <label class="fw-bold m-0">Send Automated SMS</label>
                    <input class="form-check-input" type="checkbox" name="sms_in" value="1" <?= ($s_in['sms_in'] ?? 0) ? 'checked' : '' ?>>
                </div>

                <div class="m3-input-box mb-3">
                    <label>PRIORITY 1</label>
                    <select name="sms_in_priority_1" class="m3-clean-input border-0 bg-transparent">
                        <option value="on_submit" <?= ($s_in['sms_in_priority_1'] == 'on_submit') ? 'selected' : '' ?>>On Submit</option>
                        <option value="fixed_time" <?= ($s_in['sms_in_priority_1'] == 'fixed_time') ? 'selected' : '' ?>>Fixed Time</option>
                    </select>
                </div>

                <div class="m3-input-box mb-3"><label>FIXED SENDING TIME</label><input type="time" name="sms_in_fixed_time" value="<?= $s_in['sms_in_fixed_time'] ?? '' ?>" class="m3-clean-input"></div>

                <div class="m3-input-box mb-4">
                    <label>SMS TEXT CONTENT</label>
                    <textarea name="sms_in_text" class="m3-clean-input w-100" rows="3"><?= $s_in['sms_in_text'] ?? '' ?></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-black shadow">UPDATE TEMPLATE</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
// কমন সেভ ফংশন যা ফিল্ডগুলোকে JSON এ কনভার্ট করে পাঠাবে
function saveJsonConfig(formId, columnName) {
    $(`#${formId}`).on('submit', function(e) {
        e.preventDefault();
        
        // ফর্ম ডাটাকে অবজেক্টে রূপান্তর
        const formData = new FormData(this);
        const obj = {};
        formData.forEach((value, key) => {
            // চেকবক্স হ্যান্ডলিং (on/off এর বদলে 1/0)
            obj[key] = (value === '1') ? 1 : value;
        });
        
        // যদি চেকবক্স আনচেক থাকে তবে ভ্যালু ০ সেট করা (যেহেতু FormData আনচেকড বক্স নেয় না)
        $(this).find('input[type=checkbox]').each(function() {
            if (!this.checked) obj[this.name] = 0;
        });

        // JSON স্ট্রিং তৈরি এবং ব্যাকএন্ডে পাঠানো
        const jsonString = JSON.stringify(obj);
        
        $.ajax({
            url: 'backend/save-institute-info.php',
            type: 'POST',
            data: { [columnName]: jsonString }, // কলাম নেম ডাইনামিক
            success: function(res) {
                if(res.status == 'success') {
                    Swal.fire({ icon: 'success', title: 'Synced!', showConfirmButton: false, timer: 1000 })
                    .then(() => location.reload());
                }
            }
        });
    });
}

// প্রতিটি ফর্মের জন্য লজিক কল করা
saveJsonConfig('gwForm', 'sms_gateway');
saveJsonConfig('inForm', 'sms_in');
// বাকিগুলো একইভাবে... (outForm -> sms_out, absentForm -> sms_absent)
</script>