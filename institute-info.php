<?php
/**
 * Institution Profile - Full M3 Card-Based Editor
 * Optimized for: Sectional Updates & Visual Clarity
 */
$page_title = "Institution Management";
include 'inc.php';

// ডাটা ফেচিং
$stmt = $conn->prepare("SELECT * FROM scinfo WHERE sccode = ? LIMIT 1");
$stmt->bind_param("s", $sccode);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) die("No data found.");
?>

<style>
    :root {
        --m3-surface: #F7F2FA;
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-outline: #79747E;
    }

    body { background-color: var(--m3-surface); font-family: 'Roboto', sans-serif; }

    /* অ্যান্ড্রয়েড স্টাইল হিরো */
    .m3-hero-header {
        background: linear-gradient(180deg, #6750A4 0%, #4F378B 100%);
        padding: 40px 16px 60px; text-align: center; color: #fff; border-radius: 0 0 32px 32px;
    }
    .hero-logo-box {
        width: 100px; height: 100px; background: #fff; border-radius: 24px;
        padding: 10px; margin: 0 auto 16px; box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* কন্টেইনার ও কার্ড */
    .m3-container { padding: 0 16px; margin-top: -30px; position: relative; z-index: 5; }
    
    .m3-card {
        background: #fff; border-radius: 16px; padding: 20px; margin-bottom: 12px;
        border: 1px solid rgba(0,0,0,0.05); transition: 0.2s; cursor: pointer;
    }
    .m3-card:active { background-color: var(--m3-primary-container); transform: scale(0.98); }

    .m3-section-label {
        font-size: 0.8rem; font-weight: 800; color: var(--m3-primary);
        margin: 20px 12px 10px; text-transform: uppercase; letter-spacing: 1px;
    }

    .data-row { display: flex; align-items: center; gap: 16px; padding: 8px 0; border-bottom: 1px solid #f1f1f1; }
    .data-row:last-child { border-bottom: none; }
    
    .icon-box {
        width: 38px; height: 38px; border-radius: 10px;
        background: var(--m3-primary-container); color: var(--m3-on-primary-container);
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }

    .label-text { font-size: 0.7rem; color: #757575; font-weight: 600; display: block; }
    .value-text { font-size: 0.95rem; font-weight: 700; color: #1C1B1F; word-break: break-all; }

    /* মডাল ও ইনপুট */
    .modal-m3 { border-radius: 28px !important; border: none; }
    .m3-input-box { margin-bottom: 20px; }
    .m3-input-box label { font-size: 0.75rem; font-weight: 700; color: var(--m3-primary); margin-left: 5px; margin-bottom: 5px; display: block; }
    .m3-input {
        border: 2px solid var(--m3-outline); border-radius: 12px;
        padding: 14px; width: 100%; font-weight: 600;
    }
    .m3-input:focus { border-color: var(--m3-primary); outline: none; box-shadow: 0 0 0 4px rgba(103, 80, 164, 0.1); }
</style>

<main class="pb-5">
    <div class="m3-hero-header">
        <div class="hero-logo-box">
            <img src="<?= $BASE_PATH_URL . 'logo/' . $sccode . '.png'; ?>" style="width:100%; height:100%; object-fit:contain;" onerror="this.src='https://eimbox.com/images/no-image.png'">
        </div>
        <h4 class="fw-bold mb-1"><?= $row['scname']; ?></h4>
        <div class="opacity-75 small">EIIN: <?= $sccode; ?> | Category: <?= $row['sccategory']; ?></div>
    </div>

    <div class="m3-container">

        <div class="m3-section-label">General Identity</div>
        <div class="m3-card shadow-sm" onclick="openCardEditor('identity')">
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-bank"></i></div>
                <div><span class="label-text">School Name</span><span class="value-text"><?= $row['scname']; ?></span></div>
            </div>
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-person-badge"></i></div>
                <div><span class="label-text">Head Name & Title</span><span class="value-text"><?= $row['headname']; ?> (<?= $row['headtitle']; ?>)</span></div>
            </div>
        </div>

        <div class="m3-section-label">Contact Details</div>
        <div class="m3-card shadow-sm" onclick="openCardEditor('contact')">
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-telephone"></i></div>
                <div><span class="label-text">Mobile</span><span class="value-text"><?= $row['mobile']; ?></span></div>
            </div>
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-envelope-at"></i></div>
                <div><span class="label-text">Official Email</span><span class="value-text"><?= $row['scmail'] ?: 'N/A'; ?></span></div>
            </div>
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-globe2"></i></div>
                <div><span class="label-text">Website</span><span class="value-text"><?= $row['scweb'] ?: 'N/A'; ?></span></div>
            </div>
        </div>

        <div class="m3-section-label">Address & Geography</div>
        <div class="m3-card shadow-sm" onclick="openCardEditor('location')">
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-geo-alt"></i></div>
                <div><span class="label-text">Full Address</span><span class="value-text"><?= $row['scadd1']; ?>, <?= $row['ps']; ?>, <?= $row['dist']; ?></span></div>
            </div>
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-pin-map"></i></div>
                <div><span class="label-text">Coordinates (GPS)</span><span class="value-text font-monospace"><?= $row['geolat']; ?>, <?= $row['geolon']; ?></span></div>
            </div>
        </div>

        <div class="m3-section-label">Attendance Protocol</div>
        <div class="m3-card shadow-sm" onclick="openCardEditor('protocol')">
            <div class="row g-2 mb-3">
                <div class="col-6"><span class="label-text">Standard In</span><span class="value-text text-success"><?= $row['intime']; ?></span></div>
                <div class="col-6"><span class="label-text">Standard Out</span><span class="value-text text-danger"><?= $row['outtime']; ?></span></div>
            </div>
            <div class="data-row border-0">
                <div class="icon-box"><i class="bi bi-radar"></i></div>
                <div><span class="label-text">Fence Radius & Buffer</span><span class="value-text"><?= $row['dista_differ']; ?> Meters | <?= $row['time_differ']/60; ?> Mins</span></div>
            </div>
        </div>

        <div class="m3-section-label">Payments & Banking</div>
        <div class="m3-card shadow-sm" onclick="openCardEditor('payments')">
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-wallet2"></i></div>
                <div><span class="label-text">bKash / Nagad</span><span class="value-text"><?= $row['bkash'] ?: 'OFF'; ?> / <?= $row['nagad'] ?: 'OFF'; ?></span></div>
            </div>
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-bank"></i></div>
                <div><span class="label-text">Bank Info</span><span class="value-text small"><?= $row['bank'] ?: 'Not configured'; ?></span></div>
            </div>
        </div>

        <div class="m3-section-label">SMS Configurations</div>
        <div class="m3-card shadow-sm" onclick="openCardEditor('sms')">
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-chat-dots"></i></div>
                <div><span class="label-text">Gateway API</span><span class="value-text small text-truncate d-block" style="max-width: 250px;"><?= $row['sms_gateway']; ?></span></div>
            </div>
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-file-text"></i></div>
                <div><span class="label-text">In-Time Template</span><span class="value-text small"><?= substr($row['sms_in'], 0, 50); ?>...</span></div>
            </div>
        </div>

        <div class="m3-section-label">System Security & Backup</div>
        <div class="m3-card shadow-sm" onclick="openCardEditor('system')">
            <div class="data-row">
                <div class="icon-box"><i class="bi bi-shield-lock"></i></div>
                <div><span class="label-text">Secret Key / Algo</span><span class="value-text"><?= $row['secret_key'] ?: 'Default'; ?> / <?= $row['algorithm']; ?></span></div>
            </div>
            <div class="data-row border-0">
                <div class="icon-box"><i class="bi bi-cloud-check"></i></div>
                <div><span class="label-text">Backup Status</span><span class="value-text"><?= $row['daily_backup'] ? 'Daily Backup ON' : 'Backup OFF'; ?></span></div>
            </div>
        </div>

    </div>
</main>

<div class="modal fade" id="m3EditorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-m3 p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-black" id="modalTitle">Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="editorForm">
                    <input type="hidden" name="card_type" id="card_type">
                    <div id="formContainer">
                        </div>
                    <button type="button" class="btn btn-primary w-100 py-3 mt-3 shadow" style="border-radius: 16px; font-weight: 800;" onclick="submitPartialUpdate()">
                        SAVE CONFIGURATION
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    const editorModal = new bootstrap.Modal(document.getElementById('m3EditorModal'));

    // কার্ড অনুযায়ী ফিল্ড লোড করার স্মার্ট ফাংশন
    function openCardEditor(type) {
        const container = $('#formContainer');
        const title = $('#modalTitle');
        $('#card_type').val(type);
        container.html('<div class="text-center"><div class="spinner-border text-primary"></div></div>');

        // কার্ড অনুযায়ী ফিল্ড ম্যাপিং (সব ডেটা লোড)
        let html = '';
        if (type === 'identity') {
            title.text("General Identity");
            html = `
                <div class="m3-input-box"><label>Institution Name</label><input name="scname" class="m3-input" value="<?= $row['scname']; ?>"></div>
                <div class="m3-input-box"><label>Short Name</label><input name="short" class="m3-input" value="<?= $row['short']; ?>"></div>
                <div class="m3-input-box"><label>Head Name</label><input name="headname" class="m3-input" value="<?= $row['headname']; ?>"></div>
                <div class="m3-input-box"><label>Head Title</label><input name="headtitle" class="m3-input" value="<?= $row['headtitle']; ?>"></div>
            `;
        } 
        else if (type === 'contact') {
            title.text("Communication Settings");
            html = `
                <div class="m3-input-box"><label>Mobile Number</label><input name="mobile" class="m3-input" value="<?= $row['mobile']; ?>"></div>
                <div class="m3-input-box"><label>Email Address</label><input name="scmail" class="m3-input" value="<?= $row['scmail']; ?>"></div>
                <div class="m3-input-box"><label>Website URL</label><input name="scweb" class="m3-input" value="<?= $row['scweb']; ?>"></div>
            `;
        }
        else if (type === 'location') {
            title.text("Location & GPS");
            html = `
                <div class="m3-input-box"><label>Address Line 1</label><input name="scadd1" class="m3-input" value="<?= $row['scadd1']; ?>"></div>
                <div class="m3-input-box"><label>Upazila / PS</label><input name="ps" class="m3-input" value="<?= $row['ps']; ?>"></div>
                <div class="m3-input-box"><label>District</label><input name="dist" class="m3-input" value="<?= $row['dist']; ?>"></div>
                <div class="row">
                    <div class="col-6"><div class="m3-input-box"><label>Latitude</label><input name="geolat" class="m3-input" value="<?= $row['geolat']; ?>"></div></div>
                    <div class="col-6"><div class="m3-input-box"><label>Longitude</label><input name="geolon" class="m3-input" value="<?= $row['geolon']; ?>"></div></div>
                </div>
            `;
        }
        else if (type === 'protocol') {
            title.text("Attendance Rules");
            html = `
                <div class="row">
                    <div class="col-6"><div class="m3-input-box"><label>In Time</label><input type="time" name="intime" class="m3-input" value="<?= $row['intime']; ?>"></div></div>
                    <div class="col-6"><div class="m3-input-box"><label>Out Time</label><input type="time" name="outtime" class="m3-input" value="<?= $row['outtime']; ?>"></div></div>
                </div>
                <div class="m3-input-box"><label>Fence Radius (Meters)</label><input type="number" name="dista_differ" class="m3-input" value="<?= $row['dista_differ']; ?>"></div>
                <div class="m3-input-box"><label>Time Buffer (Seconds)</label><input type="number" name="time_differ" class="m3-input" value="<?= $row['time_differ']; ?>"></div>
            `;
        }
        else if (type === 'payments') {
            title.text("Payment Gateway Settings");
            html = `
                <div class="m3-input-box"><label>bKash Personal</label><input name="bkash" class="m3-input" value="<?= $row['bkash']; ?>"></div>
                <div class="m3-input-box"><label>Nagad Personal</label><input name="nagad" class="m3-input" value="<?= $row['nagad']; ?>"></div>
                <div class="m3-input-box"><label>Bank Details</label><textarea name="bank" class="m3-input" rows="3"><?= $row['bank']; ?></textarea></div>
            `;
        }
        else if (type === 'sms') {
            title.text("SMS Gateway Configurations");
            html = `
                <div class="m3-input-box"><label>SMS Gateway URL</label><input name="sms_gateway" class="m3-input" value="<?= htmlspecialchars($row['sms_gateway']); ?>"></div>
                <div class="m3-input-box"><label>In-Time Template</label><textarea name="sms_in" class="m3-input" rows="3"><?= $row['sms_in']; ?></textarea></div>
                <div class="m3-input-box"><label>Absent Template</label><textarea name="sms_absent" class="m3-input" rows="3"><?= $row['sms_absent']; ?></textarea></div>
            `;
        }
        else if (type === 'system') {
            title.text("Advanced System Security");
            html = `
                <div class="m3-input-box"><label>Algorithm</label><input name="algorithm" class="m3-input" value="<?= $row['algorithm']; ?>"></div>
                <div class="m3-input-box"><label>Secret Key</label><input name="secret_key" class="m3-input" value="<?= $row['secret_key']; ?>"></div>
                <div class="m3-input-box">
                    <label>Daily Backup</label>
                    <select name="daily_backup" class="form-select m3-input">
                        <option value="1" <?= $row['daily_backup']?'selected':'' ?>>Enabled</option>
                        <option value="0" <?= !$row['daily_backup']?'selected':'' ?>>Disabled</option>
                    </select>
                </div>
            `;
        }

        container.html(html);
        editorModal.show();
    }

    function submitPartialUpdate() {
        const formData = $('#editorForm').serialize();
        $.ajax({
            url: 'settings/update-sc-info-partial.php',
            type: 'POST',
            data: formData,
            beforeSend: function() { Swal.showLoading(); },
            success: function(res) {
                if(res.trim() === 'success') {
                    Swal.fire({ icon: 'success', title: 'Card Updated', timer: 1000, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res, 'error');
                }
            }
        });
    }
</script>