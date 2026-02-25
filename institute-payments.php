<?php
$page_title = "Payment Gateways";
include 'inc.php';

// ডাটা ফেচিং
$row = $conn->query("SELECT bkash, nagad, rocket, bank FROM scinfo WHERE sccode = '$sccode'")->fetch_assoc();

/**
 * স্ট্রিং ডিকোড ফাংশন (পাইপ সেপারেটেড ডাটা রিড করার জন্য)
 */
function decode_gateway($data) {
    $parts = explode(' | ', $data ?? '');
    return [
        'name'     => trim($parts[0] ?? ''),
        'status'   => trim($parts[1] ?? '0'),
        'mode'     => trim($parts[2] ?? 'sandbox'),
        'app_key'  => trim($parts[3] ?? ''),
        'secret'   => trim($parts[4] ?? ''),
        'username' => trim($parts[5] ?? ''),
        'password' => trim($parts[6] ?? '')
    ];
}
?>

<style>
    .m3-hero-profile {
        background: linear-gradient(180deg, #EF6C00 0%, #E65100 100%);
        padding: 50px 24px 70px; color: #fff; border-radius: 0 0 40px 40px; text-align: center;
    }
    .gateway-card {
        background: #fff; border-radius: 24px; padding: 20px; margin-bottom: 16px;
        border: 1px solid #E7E0EC; display: flex; align-items: center; gap: 16px;
    }
    .status-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; }
    .status-1 { background: #4CAF50; box-shadow: 0 0 8px #4CAF50; } /* Online */
    .status-0 { background: #F44336; } /* Offline */
    
    /* M3 Input Box Styles */
    .m3-input-box { background: #F3EDF7; border-radius: 12px; padding: 10px 16px; border: 1px solid #E7E0EC; margin-bottom: 12px; }
    .m3-label-sm { font-size: 0.65rem; font-weight: 800; color: #6750A4; display: block; margin-bottom: 2px; text-transform: uppercase; }
    .m3-clean-input { border: none; background: transparent; width: 100%; font-weight: 700; color: #1C1B1F; outline: none; }
</style>

<main class="container py-4">
    <div class="m3-hero-profile mb-5 shadow">
        <h4 class="fw-black m-0">Payment Gateways</h4>
        <p class="small opacity-75 fw-bold">Manage Merchant API & Collection Methods</p>
    </div>

    <div class="row g-3">
        <?php
        $gateways = [
            'bkash'  => ['title' => 'bKash Merchant', 'color' => '#D12053', 'icon' => 'bi-qr-code'],
            'nagad'  => ['title' => 'Nagad Account', 'color' => '#EC1C24', 'icon' => 'bi-wallet2'],
            'rocket' => ['title' => 'Rocket Details', 'color' => '#8C3494', 'icon' => 'bi-phone-vibrate'],
            'bank'   => ['title' => 'Bank Transfer', 'color' => '#006A6A', 'icon' => 'bi-bank']
        ];

        foreach ($gateways as $key => $meta): 
            $data = decode_gateway($row[$key]);
        ?>
            <div class="col-12 col-md-6">
                <div class="gateway-card shadow-sm">
                    <div class="brand-logo d-flex align-items-center justify-content-center" style="background: <?= $meta['color'] ?>15; color: <?= $meta['color'] ?>;">
                        <i class="<?= $meta['icon'] ?> fs-3"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-black text-dark fs-6"><?= $meta['title'] ?></div>
                        <div class="small fw-bold">
                            <span class="status-dot status-<?= $data['status'] ?>"></span>
                            <?= ($data['status'] == '1') ? '<span class="text-success">Active</span>' : '<span class="text-muted">Disabled</span>' ?>
                            <span class="mx-1 text-muted">|</span>
                            <span class="text-primary text-uppercase"><?= $data['mode'] ?></span>
                        </div>
                    </div>
                    <button class="btn btn-tonal-orange btn-sm px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#modal_<?= $key ?>">EDIT</button>
                </div>
            </div>

            <div class="modal fade" id="modal_<?= $key ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-5 shadow-lg">
                        <form onsubmit="handlePaymentSave(event, '<?= $key ?>')" class="modal-body p-4">
                            <h5 class="fw-black mb-4 text-dark"><i class="bi bi-shield-lock me-2"></i><?= $meta['title'] ?></h5>
                            
                            <input type="hidden" name="gw_name" value="<?= $key ?>">

                            <div class="form-check form-switch mb-3 bg-light p-3 rounded-4 d-flex justify-content-between align-items-center">
                                <label class="fw-bold m-0 text-primary">Service Operational</label>
                                <input class="form-check-input ms-0" type="checkbox" name="status" value="1" <?= ($data['status'] == '1') ? 'checked' : '' ?>>
                            </div>

                            <div class="m3-input-box">
                                <label class="m3-label-sm">API Mode</label>
                                <select name="mode" class="m3-clean-input border-0 bg-transparent">
                                    <option value="sandbox" <?= ($data['mode'] == 'sandbox') ? 'selected' : '' ?>>Sandbox (Testing)</option>
                                    <option value="live" <?= ($data['mode'] == 'live') ? 'selected' : '' ?>>Live (Production)</option>
                                </select>
                            </div>

                            <div class="m3-input-box">
                                <label class="m3-label-sm">APP KEY / CLIENT ID</label>
                                <input type="text" name="app_key" value="<?= $data['app_key'] ?>" class="m3-clean-input" placeholder="Enter App Key">
                            </div>

                            <div class="m3-input-box">
                                <label class="m3-label-sm">APP SECRET</label>
                                <input type="password" name="secret" value="<?= $data['secret'] ?>" class="m3-clean-input" placeholder="••••••••••••">
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="m3-input-box"><label class="m3-label-sm">USERNAME</label>
                                    <input type="text" name="username" value="<?= $data['username'] ?>" class="m3-clean-input"></div>
                                </div>
                                <div class="col-6">
                                    <div class="m3-input-box"><label class="m3-label-sm">PASSWORD</label>
                                    <input type="password" name="password" value="<?= $data['password'] ?>" class="m3-clean-input"></div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="button" class="btn btn-light rounded-pill flex-grow-1 fw-bold" data-bs-dismiss="modal">CANCEL</button>
                                <button type="submit" class="btn btn-m3-primary rounded-pill flex-grow-1 fw-bold shadow">SAVE CONFIG</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'footer.php'; ?>

<script>
    /**
     * ডাটা প্রসেসিং এবং পাইপ স্ট্রিং বিল্ডার
     */
    function handlePaymentSave(e, key) {
        e.preventDefault();
        const form = e.target;
        const fd = new FormData(form);
        
        // পাইপ স্ট্রিং তৈরি করা
        const gwName   = fd.get('gw_name');
        const status   = form.querySelector('input[name="status"]').checked ? "1" : "0";
        const mode     = fd.get('mode');
        const appKey   = fd.get('app_key') || "null";
        const secret   = fd.get('secret') || "null";
        const username = fd.get('username') || "null";
        const password = fd.get('password') || "null";

        // ফাইনাল স্ট্রিং ফরম্যাট: name | status | mode | key | secret | user | pass
        const pipeString = `${gwName} | ${status} | ${mode} | ${appKey} | ${secret} | ${username} | ${password}`;

        const submitBtn = $(form).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: 'backend/save-institute-info.php',
            type: 'POST',
            data: { [key]: pipeString }, // ডাইনামিকালি কলাম নেম সেট করা (bkash, nagad, etc.)
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Gateway Synced', showConfirmButton: false, timer: 1000 })
                    .then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                    submitBtn.prop('disabled', false).text('SAVE CONFIG');
                }
            },
            error: function() {
                Swal.fire('Error', 'Connection failed', 'error');
                submitBtn.prop('disabled', false).text('SAVE CONFIG');
            }
        });
    }
</script>