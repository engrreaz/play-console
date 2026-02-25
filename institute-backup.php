<?php 
$page_title = "Data & Security"; 
include 'inc.php'; 

// ডাটা ফেচিং
$sql = "SELECT backup, algorithm, secret_key, api_key, backup_mail_2, backup_mail_3, 
               daily_backup, monthly_backup, cloud_storage, last_backup_time 
        FROM scinfo WHERE sccode = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sccode);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>

<style>
    /* Security Hero - Cyan/Teal Gradient */
    .m3-hero-sec {
        background: linear-gradient(180deg, #00838F 0%, #006064 100%);
        padding: 50px 24px 80px; color: #fff; border-radius: 0 0 40px 40px; text-align: center;
    }
    .sec-container { margin-top: -60px; padding: 0 16px 50px; position: relative; z-index: 10; }
    .m3-card { background: #fff; border-radius: 28px; padding: 24px; border: 1px solid #E7E0EC; margin-bottom: 16px; }
    
    /* Status Labels */
    .status-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .status-pill { padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; font-weight: 800; }
    
    .key-box { background: #F3EDF7; padding: 12px; border-radius: 12px; font-family: monospace; font-size: 0.8rem; border: 1px solid #EADDFF; color: #21005D; word-break: break-all; }
</style>

<main>
    <div class="m3-hero-sec shadow">
        <h3 class="fw-black mb-1">Security & Vault</h3>
        <p class="small opacity-75 fw-bold mb-0">Encryption protocols and automated backups</p>
    </div>

    <div class="sec-container">
        <div class="m3-card shadow-sm">
            <div class="status-row">
                <h6 class="fw-black m-0 text-dark">Backup System</h6>
                <span class="status-pill <?= $row['backup'] ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                    <?= $row['backup'] ? 'OPERATIONAL' : 'OFFLINE' ?>
                </span>
            </div>
            <div class="row text-center g-2 mt-2">
                <div class="col-4 border-end">
                    <small class="text-muted fw-bold d-block">DAILY</small>
                    <i class="bi <?= $row['daily_backup'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' ?>"></i>
                </div>
                <div class="col-4 border-end">
                    <small class="text-muted fw-bold d-block">MONTHLY</small>
                    <i class="bi <?= $row['monthly_backup'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' ?>"></i>
                </div>
                <div class="col-4">
                    <small class="text-muted fw-bold d-block">CLOUD</small>
                    <i class="bi <?= $row['cloud_storage'] ? 'bi-cloud-check-fill text-primary' : 'bi-x-circle text-muted' ?>"></i>
                </div>
            </div>
            <div class="mt-3 p-2 bg-light rounded-3 text-center small fw-bold text-muted">
                Last Sync: <?= $row['last_backup_time'] ?: 'Never' ?>
            </div>
        </div>

        <div class="m3-section-title px-2">Security Credentials</div>
        <div class="m3-card shadow-sm">
            <div class="mb-3">
                <label class="small fw-black text-muted text-uppercase">Active Algorithm</label>
                <div class="fw-bold text-primary"><?= $row['algorithm'] ?: 'Not Defined' ?></div>
            </div>
            <div class="mb-3">
                <label class="small fw-black text-muted text-uppercase">API Key</label>
                <div class="key-box"><?= $row['api_key'] ?: '****************' ?></div>
            </div>
            <button class="btn btn-primary w-100 rounded-pill fw-black py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#configModal">
                <i class="bi bi-shield-lock-fill me-2"></i>RE-CONFIGURE VAULT
            </button>
        </div>

        <div class="m3-section-title px-2">Backup Secondary Mails</div>
        <div class="m3-card shadow-sm">
            <div class="d-flex align-items-center gap-3 mb-3">
                <i class="bi bi-envelope-at text-teal fs-4"></i>
                <div class="small fw-bold text-muted"><?= $row['backup_mail_2'] ?: '<i>Second mail not set</i>' ?></div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-envelope-at text-teal fs-4"></i>
                <div class="small fw-bold text-muted"><?= $row['backup_mail_3'] ?: '<i>Third mail not set</i>' ?></div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="configModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-black text-teal">Update Security Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="secForm" class="modal-body px-4 pb-4">
                
                <p class="small fw-bold text-muted mb-3">AUTOMATION TOGGLES</p>
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="form-check form-switch bg-light p-3 rounded-4 d-flex justify-content-between align-items-center m-0">
                            <label class="fw-bold m-0 small">Main Backup</label>
                            <input class="form-check-input" type="checkbox" name="backup" value="1" <?= $row['backup'] ? 'checked' : '' ?>>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch bg-light p-3 rounded-4 d-flex justify-content-between align-items-center m-0">
                            <label class="fw-bold m-0 small">Cloud Storage</label>
                            <input class="form-check-input" type="checkbox" name="cloud_storage" value="1" <?= $row['cloud_storage'] ? 'checked' : '' ?>>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch bg-light p-3 rounded-4 d-flex justify-content-between align-items-center m-0">
                            <label class="fw-bold m-0 small">Daily Cycle</label>
                            <input class="form-check-input" type="checkbox" name="daily_backup" value="1" <?= $row['daily_backup'] ? 'checked' : '' ?>>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch bg-light p-3 rounded-4 d-flex justify-content-between align-items-center m-0">
                            <label class="fw-bold m-0 small">Monthly Cycle</label>
                            <input class="form-check-input" type="checkbox" name="monthly_backup" value="1" <?= $row['monthly_backup'] ? 'checked' : '' ?>>
                        </div>
                    </div>
                </div>

                <p class="small fw-bold text-muted mb-3">VAULT CREDENTIALS</p>
                <div class="m3-input-box mb-3"><label>ENCRYPTION ALGORITHM</label><input type="text" name="algorithm" value="<?= $row['algorithm'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-3"><label>API KEY</label><input type="text" name="api_key" value="<?= $row['api_key'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-4"><label>SECRET KEY</label><input type="text" name="secret_key" value="<?= $row['secret_key'] ?>" class="m3-clean-input"></div>

                <p class="small fw-bold text-muted mb-3">RECIPIENT EMAILS</p>
                <div class="m3-input-box mb-3"><label>BACKUP EMAIL 2</label><input type="email" name="backup_mail_2" value="<?= $row['backup_mail_2'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-4"><label>BACKUP EMAIL 3</label><input type="email" name="backup_mail_3" value="<?= $row['backup_mail_3'] ?>" class="m3-clean-input"></div>

                <button type="submit" class="btn btn-teal w-100 rounded-pill py-3 fw-black shadow text-white">
                    <i class="bi bi-cloud-upload me-2"></i>SYNC SECURITY CONFIG
                </button>
            </form>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>

<script>
    $('#secForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = $(form).find('button[type="submit"]');
        
        // চেকবক্স হ্যান্ডলিং (Unchecked values send 0)
        let formData = $(form).serializeArray();
        const unchecked = $(form).find('input[type=checkbox]:not(:checked)');
        unchecked.each(function() { formData.push({name: this.name, value: "0"}); });

        submitBtn.prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-2"></div> SAVING...');

        $.post('backend/save-institute-info.php', formData, function(res) {
            if(res.status == 'success') {
                Swal.fire({ icon: 'success', title: 'Vault Updated', showConfirmButton: false, timer: 1500 })
                .then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
                submitBtn.prop('disabled', false).html('<i class="bi bi-cloud-upload me-2"></i>SYNC SECURITY CONFIG');
            }
        }, 'json');
    });
</script>